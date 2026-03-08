<?php
require_once 'config.php';

// Database connection for subscription system
try {
    $pdo = new PDO("mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Security functions
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function validate_password($password) {
    global $password_requirements;
    
    if (strlen($password) < $password_requirements['min_length']) {
        return "Password must be at least " . $password_requirements['min_length'] . " characters long.";
    }
    
    if ($password_requirements['require_uppercase'] && !preg_match('/[A-Z]/', $password)) {
        return "Password must contain at least one uppercase letter.";
    }
    
    if ($password_requirements['require_lowercase'] && !preg_match('/[a-z]/', $password)) {
        return "Password must contain at least one lowercase letter.";
    }
    
    if ($password_requirements['require_number'] && !preg_match('/[0-9]/', $password)) {
        return "Password must contain at least one number.";
    }
    
    if ($password_requirements['require_special'] && !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        return "Password must contain at least one special character.";
    }
    
    return true;
}

function is_login_locked($username) {
    $lock_file = sys_get_temp_dir() . '/login_lock_' . md5($username);
    if (file_exists($lock_file)) {
        $lock_time = filemtime($lock_file);
        if (time() - $lock_time < $GLOBALS['rate_limit']['lockout_time']) {
            return true;
        } else {
            unlink($lock_file);
        }
    }
    return false;
}

function record_login_attempt($username) {
    $attempts_file = sys_get_temp_dir() . '/login_attempts_' . md5($username);
    $attempts = file_exists($attempts_file) ? (int)file_get_contents($attempts_file) : 0;
    $attempts++;
    
    if ($attempts >= $GLOBALS['rate_limit']['login_attempts']) {
        $lock_file = sys_get_temp_dir() . '/login_lock_' . md5($username);
        touch($lock_file);
        unlink($attempts_file);
        return false; // Locked
    }
    
    file_put_contents($attempts_file, $attempts);
    return true; // Not locked
}

function clear_login_attempts($username) {
    $attempts_file = sys_get_temp_dir() . '/login_attempts_' . md5($username);
    if (file_exists($attempts_file)) {
        unlink($attempts_file);
    }
}

function secure_session_start() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
        session_regenerate_id(true);
        
        // Set session timeout
        $_SESSION['last_activity'] = time();
        $_SESSION['created'] = time();
    }
}

function check_session_timeout() {
    if (isset($_SESSION['last_activity']) && 
        (time() - $_SESSION['last_activity'] > $GLOBALS['rate_limit']['session_timeout'])) {
        session_unset();
        session_destroy();
        return false;
    }
    $_SESSION['last_activity'] = time();
    return true;
}

function generate_csrf_token() {
    // Ensure session is started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function is_logged_in() {
    return isset($_SESSION['user_id']) && check_session_timeout();
}

function require_login_redirect() {
    // Check if session is valid
    if (!check_session_timeout()) {
        // Check if user is coming from a closed tab (session exists but expired)
        if (isset($_SESSION['user_id'])) {
            // Clear the expired session and redirect to landing page
            session_unset();
            session_destroy();
            header('Location: index.php?expired=1');
            exit();
        } else {
            // Redirect to login page for new sessions
            header('Location: login.php?timeout=1');
            exit();
        }
    }
}

function redirect_if_not_logged_in() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit();
    }
}

// ===== SUBSCRIPTION SYSTEM FUNCTIONS =====

function get_user($user_id, $pdo){
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function trial_active($user, $pdo){
    if($user['subscription_status'] != 'trial'){
        return false;
    }
    
    $trial_start = strtotime($user['trial_start']);
    
    // 1 hour trial (3600 seconds)
    if((time() - $trial_start) < 3600){
        return true;
    }
    
    /* trial expired - update database */
    $stmt = $pdo->prepare("UPDATE users SET subscription_status='expired' WHERE id=?");
    $stmt->execute([$user['id']]);
    
    return false;
}

function has_premium($user){
    return $user['subscription_status'] == 'premium';
}

function check_subscription($user, $pdo){
    // Check if premium has expired
    if($user['subscription_status'] == 'premium' && $user['subscription_end']){
        if(time() > strtotime($user['subscription_end'])){
            // Premium expired
            $stmt = $pdo->prepare("UPDATE users SET subscription_status='expired' WHERE id=?");
            $stmt->execute([$user['id']]);
            return false;
        }
    }
    
    return $user['subscription_status'] == 'premium' || trial_active($user, $pdo);
}

function get_subscription_status($user, $pdo){
    $stmt = $pdo->prepare("SELECT subscription_status, trial_start, subscription_end FROM users WHERE id=?");
    $stmt->execute([$user['id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$result) return ['status' => 'expired', 'remaining_seconds' => 0];
    
    if($result['subscription_status'] == 'trial'){
        $trial_start = strtotime($result['trial_start']);
        
        // Always give fresh 1-hour trial for trial users
        $current_time = time();
        $time_since_start = $current_time - $trial_start;
        
        // If trial just started (within last 5 seconds), give full hour
        if($time_since_start < 5) {
            // Update trial start to now for fresh trial
            $update_stmt = $pdo->prepare("UPDATE users SET trial_start = NOW() WHERE id=?");
            $update_stmt->execute([$user['id']]);
            return [
                'status' => 'trial',
                'remaining_seconds' => 3600,
                'remaining_minutes' => 60
            ];
        }
        
        // Calculate remaining time
        $remaining = 3600 - $time_since_start;
        return [
            'status' => 'trial',
            'remaining_seconds' => max(0, $remaining),
            'remaining_minutes' => max(0, floor($remaining / 60))
        ];
    }
    
    if($result['subscription_status'] == 'premium'){
        return [
            'status' => 'premium',
            'subscription_end' => $result['subscription_end'],
            'remaining_seconds' => 0
        ];
    }
    
    return ['status' => 'expired', 'remaining_seconds' => 0];
}
?>

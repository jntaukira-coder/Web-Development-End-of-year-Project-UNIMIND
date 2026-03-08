<?php
/**
 * Authentication Protection Script
 * Include this at the top of any page that requires user authentication
 */

require_once 'functions.php';
secure_session_start();

// Redirect to login if not authenticated
if (!is_logged_in()) {
    // Store the requested page for redirect after login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    
    // Redirect to login page
    header('Location: login.php');
    exit();
}

// Optional: Check if user account is active/verified
// You can add additional checks here if needed
// For example: check if email is verified, account is not suspended, etc.

// Set user data in session for easy access
if (!isset($_SESSION['user_data'])) {
    $user_id = $_SESSION['user_id'];
    $conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
    
    $stmt = $conn->prepare("SELECT id, fullname, username, email, regNumber, year_of_study FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $_SESSION['user_data'] = $result->fetch_assoc();
    }
    
    $stmt->close();
    $conn->close();
}
?>

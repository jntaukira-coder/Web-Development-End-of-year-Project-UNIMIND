<?php
session_start();
require_once 'db.php';
require_once 'functions.php';

echo "<h1>🧪 SaaS Subscription System Test</h1>";

// Test database connection
if($pdo) {
    echo "✅ Database connection: SUCCESS<br>";
} else {
    echo "❌ Database connection: FAILED<br>";
}

// Test if subscription columns exist
try {
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if(in_array('subscription_status', $columns) && in_array('trial_start', $columns) && in_array('subscription_end', $columns)) {
        echo "✅ Subscription columns: EXIST<br>";
    } else {
        echo "❌ Subscription columns: MISSING<br>";
    }
} catch(PDOException $e) {
    echo "❌ Error checking columns: " . $e->getMessage() . "<br>";
}

// Test subscription functions
if(function_exists('get_user') && function_exists('check_subscription') && function_exists('get_subscription_status')) {
    echo "✅ Subscription functions: LOADED<br>";
} else {
    echo "❌ Subscription functions: MISSING<br>";
}

// Test current user subscription
if(isset($_SESSION['user_id'])) {
    $user = get_user($_SESSION['user_id'], $pdo);
    if($user && is_array($user)) {
        $subscription = get_subscription_status($user, $pdo);
        echo "✅ User subscription: " . $subscription['status'] . "<br>";
        echo "📊 Remaining time: " . $subscription['remaining_seconds'] . " seconds<br>";
    } else {
        echo "❌ User not found in database<br>";
    }
} else {
    echo "ℹ️ No user logged in - <a href='login.php'>Login</a> to test subscription<br>";
    
    // Test with a dummy user structure
    $dummy_user = ['id' => 1, 'subscription_status' => 'trial'];
    echo "🧪 Testing with dummy user structure...<br>";
    if(function_exists('get_subscription_status')) {
        echo "✅ get_subscription_status function exists<br>";
    }
}

echo "<br><h2>🚀 Test Links:</h2>";
echo "<a href='Home.php'>🏠 Dashboard</a><br>";
echo "<a href='start_trial.php'>⚡ Start Trial</a><br>";
echo "<a href='upgrade.php'>💳 Upgrade Page</a><br>";
echo "<a href='payment.php'>💰 Payment Demo</a><br>";
echo "<a href='Accomodation.php'>🏠 Accommodation (Premium)</a><br>";
?>

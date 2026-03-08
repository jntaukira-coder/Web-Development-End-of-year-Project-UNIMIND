<?php
require_once 'db_connect.php';
require_once 'functions.php';

secure_session_start();

if(!is_logged_in()){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Start trial for user
$stmt = $conn->prepare("
    UPDATE users 
    SET trial_start = NOW(),
        subscription_status = 'trial'
    WHERE id = ?");

if($stmt->execute([$user_id])){
    header("Location: Home.php?trial_started=1");
} else {
    header("Location: Home.php?trial_error=1");
}
exit;
?>

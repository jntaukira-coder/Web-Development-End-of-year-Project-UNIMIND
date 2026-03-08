<?php
include 'db.php';
session_start();

// Get payment data from PayChangu callback
$tx_ref = $_POST['tx_ref'] ?? '';
$payment_status = $_POST['status'] ?? '';
$amount = $_POST['amount'] ?? '';
$plan = $_POST['meta']['plan'] ?? '';
$user_id = $_POST['meta']['user_id'] ?? '';

// Log callback data for debugging
file_put_contents('payment_callback.log', date('Y-m-d H:i:s') . " - " . json_encode($_POST) . "\n", FILE_APPEND);

if ($payment_status == 'success' && $plan && $user_id) {
    // Update user subscription
    $subscription_end = date('Y-m-d H:i:s', strtotime('+1 month'));
    
    $stmt = $pdo->prepare("UPDATE users SET subscription_status=?, subscription_end=? WHERE id=?");
    $result = $stmt->execute(['premium', $subscription_end, $user_id]);
    
    if ($result) {
        // Log successful update
        file_put_contents('payment_callback.log', date('Y-m-d H:i:s') . " - SUCCESS: Updated user $user_id to premium\n", FILE_APPEND);
        
        // Redirect to dashboard with success message
        header("Location: Home.php?payment_success=1&plan=" . $plan);
        exit;
    } else {
        file_put_contents('payment_callback.log', date('Y-m-d H:i:s') . " - ERROR: Failed to update user $user_id\n", FILE_APPEND);
    }
} else {
    file_put_contents('payment_callback.log', date('Y-m-d H:i:s') . " - INVALID: Payment status $payment_status or missing data\n", FILE_APPEND);
}

// Redirect back to dashboard
header("Location: Home.php?payment_status=" . $payment_status);
exit;
?>

<?php
require_once 'db_connect.php';
require_once 'functions.php';

secure_session_start();

if(!is_logged_in()){
    header("Location: login.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $plan = sanitize_input($_POST['plan'] ?? '');
    $user_id = $_SESSION['user_id'];
    
    if($plan == 'student'){
        // Student plan - 1 month
        $stmt = $conn->prepare("
            UPDATE users 
            SET subscription_status = 'premium',
                subscription_end = DATE_ADD(NOW(), INTERVAL 1 MONTH)
            WHERE id = ?");
        
        if($stmt->execute([$user_id])){
            header("Location: Home.php?payment_success=student");
        } else {
            header("Location: upgrade.php?payment_error=1");
        }
        
    } elseif($plan == 'premium'){
        // Premium plan - 1 month
        $stmt = $conn->prepare("
            UPDATE users 
            SET subscription_status = 'premium',
                subscription_end = DATE_ADD(NOW(), INTERVAL 1 MONTH)
            WHERE id = ?");
        
        if($stmt->execute([$user_id])){
            header("Location: Home.php?payment_success=premium");
        } else {
            header("Location: upgrade.php?payment_error=1");
        }
        
    } else {
        header("Location: upgrade.php?payment_error=invalid_plan");
    }
    
    exit;
}

// Simulate payment form (in real implementation, this would integrate with payment gateway)
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Complete Payment - UNIMIND</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<style>
body {
  font-family: 'Roboto', sans-serif;
  background: linear-gradient(135deg, #0a0a1a 0%, #1a0a2e 100%);
  color: #fff;
  min-height: 100vh;
  margin: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.payment-container {
  background: linear-gradient(135deg, rgba(15, 15, 32, 0.9), rgba(26, 10, 46, 0.9));
  border: 2px solid #00d4ff;
  border-radius: 20px;
  padding: 3rem;
  max-width: 500px;
  width: 100%;
  text-align: center;
  box-shadow: 0 20px 40px rgba(0, 212, 255, 0.3);
  backdrop-filter: blur(20px);
}

.payment-container h2 {
  font-size: 2rem;
  margin-bottom: 2rem;
  color: #00d4ff;
  text-shadow: 0 0 10px rgba(0, 212, 255, 0.5);
}

.payment-form {
  text-align: left;
}

.payment-form input {
  width: 100%;
  padding: 1rem;
  margin-bottom: 1rem;
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 10px;
  color: white;
  font-size: 1rem;
}

.payment-form button {
  width: 100%;
  padding: 1rem 2rem;
  background: linear-gradient(45deg, #00d4ff, #00ff88);
  color: white;
  border: none;
  border-radius: 50px;
  font-size: 1.1rem;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.3s ease;
}

.payment-form button:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 30px rgba(0, 212, 255, 0.5);
}

.demo-note {
  background: rgba(255, 193, 7, 0.2);
  border: 1px solid rgba(255, 193, 7, 0.3);
  border-radius: 10px;
  padding: 1rem;
  margin-top: 2rem;
  font-size: 0.9rem;
}

.plan-summary {
  background: rgba(0, 212, 255, 0.1);
  border: 1px solid rgba(0, 212, 255, 0.3);
  border-radius: 10px;
  padding: 1rem;
  margin-bottom: 2rem;
  text-align: center;
}
</style>
</head>
<body>
<div class="payment-container">
  <h2>💳 Complete Your Payment</h2>
  
  <?php
  $plan = sanitize_input($_GET['plan'] ?? '');
  $amount = 0;
  $plan_name = '';

  if($plan === 'student') {
      $amount = 500;
      $plan_name = 'Student';
  } elseif($plan === 'premium') {
      $amount = 1000;
      $plan_name = 'Premium';
  }
  ?>
  
  <div class="plan-summary">
    <strong>Plan:</strong> <?php echo $plan_name; ?><br>
    <strong>Price:</strong> MWK <?php echo number_format($amount); ?><br>
    <strong>Duration:</strong> 1 Month
  </div>
  
  <form method="POST" class="payment-form">
    <input type="hidden" name="plan" value="<?php echo $plan; ?>">
    
    <label>Card Number</label>
    <input type="text" placeholder="1234 5678 9012 3456" required>
    
    <label>Expiry Date</label>
    <input type="text" placeholder="MM/YY" required>
    
    <label>CVV</label>
    <input type="text" placeholder="123" required>
    
    <label>Cardholder Name</label>
    <input type="text" placeholder="John Doe" required>
    
    <button type="submit">Process Payment</button>
  </form>
  
  <div class="demo-note">
    <strong>🔒 DEMO MODE</strong><br>
    This is a demo payment form. In production, this would integrate with a real payment gateway like Mobile Money or PayPal.
  </div>
</div>
</body>
</html>

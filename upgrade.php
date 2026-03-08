<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UNIMIND - Upgrade Your Account</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root {
  --primary-color: #00d4ff;
  --secondary-color: #00ff88;
  --accent-color: #ff00ff;
  --bg-dark: #0a0a1a;
  --bg-medium: #1a0a2e;
  --bg-light: #2a0a4e;
  --text-primary: #ffffff;
  --text-secondary: #e0e0ff;
  --border-color: #4a00ff;
  --shadow-glow: rgba(0, 212, 255, 0.6);
  --neon-green: #00ff88;
  --neon-blue: #00d4ff;
  --neon-purple: #ff00ff;
  --neon-orange: #ff8800;
  --neon-cyan: #00ffff;
  --neon-pink: #ff00aa;
}

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

.container {
  max-width: 1200px;
  width: 100%;
  padding: 2rem;
}

.header {
  text-align: center;
  margin-bottom: 3rem;
}

.header h1 {
  font-family: 'Orbitron', sans-serif;
  font-size: 3rem;
  color: var(--primary-color);
  text-shadow: 0 0 20px rgba(0, 212, 255, 0.5);
  margin-bottom: 1rem;
}

.header p {
  font-size: 1.2rem;
  color: var(--text-secondary);
  margin-bottom: 2rem;
}

.plans {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 2rem;
  margin-bottom: 3rem;
}

.plan {
  background: linear-gradient(135deg, rgba(15, 15, 32, 0.9), rgba(26, 10, 46, 0.9));
  border: 2px solid var(--border-color);
  border-radius: 20px;
  padding: 2.5rem;
  text-align: center;
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
  backdrop-filter: blur(20px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
}

.plan.featured {
  border-color: var(--primary-color);
  transform: scale(1.05);
  box-shadow: 0 20px 40px rgba(0, 212, 255, 0.3);
}

.plan.featured::before {
  content: 'MOST POPULAR';
  position: absolute;
  top: 20px;
  right: -30px;
  background: var(--primary-color);
  color: white;
  padding: 5px 15px;
  font-size: 0.8rem;
  font-weight: bold;
  transform: rotate(45deg);
}

.plan h2 {
  font-family: 'Orbitron', sans-serif;
  font-size: 1.8rem;
  color: var(--text-primary);
  margin-bottom: 1rem;
}

.plan .price {
  font-size: 2.5rem;
  font-weight: bold;
  color: var(--primary-color);
  margin-bottom: 0.5rem;
}

.plan .price span {
  font-size: 1rem;
  color: var(--text-secondary);
}

.plan ul {
  list-style: none;
  padding: 0;
  margin: 2rem 0;
  text-align: left;
}

.plan li {
  padding: 0.8rem 0;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  color: var(--text-secondary);
}

.plan li:before {
  content: '✅ ';
  color: var(--neon-green);
  font-weight: bold;
}

.plan li.disabled:before {
  content: '❌ ';
  color: #ff4444;
}

.plan .btn {
  width: 100%;
  padding: 1rem 2rem;
  background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
  color: white;
  border: none;
  border-radius: 50px;
  font-size: 1.1rem;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  display: inline-block;
  margin-top: 2rem;
}

.plan .btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 30px rgba(0, 212, 255, 0.5);
}

.trial-banner {
  background: linear-gradient(45deg, #ff6b35, #f7931e);
  color: white;
  padding: 1rem 2rem;
  border-radius: 15px;
  text-align: center;
  margin-bottom: 2rem;
  font-weight: bold;
  box-shadow: 0 10px 30px rgba(255, 107, 53, 0.3);
}

@media (max-width: 768px) {
  .plans {
    grid-template-columns: 1fr;
  }
  .container {
    padding: 1rem;
  }
  .header h1 {
    font-size: 2rem;
  }
}
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <h1>Unlock Full UNIMIND</h1>
    <p>Get unlimited access to all premium features and transform your university experience</p>
  </div>

  <?php if(isset($_GET['trial_expired'])): ?>
    <div class="trial-banner">
      Your trial has expired! Upgrade now to continue enjoying premium features.
    </div>
  <?php endif; ?>

  <div class="plans">
    <div class="plan-card">
      <h3>Student Plan</h3>
      <div class="price">MWK 500<span>/month</span></div>
      <ul class="features">
        <li>All basic features</li>
        <li>Campus Services access</li>
        <li>Opportunities database</li>
        <li>AI Mentor matching</li>
        <li>Monthly billing</li>
      </ul>
      <a href="payment.php?plan=student" class="btn">Choose Student</a>
    </div>
    
    <div class="plan-card featured">
      <div class="popular-badge">MOST POPULAR</div>
      <h3>Premium Plan</h3>
      <div class="price">MWK 1,000<span>/month</span></div>
      <ul class="features">
        <li>Everything in Student</li>
        <li>Safe Accommodation access</li>
        <li>Yaza Corner exclusive</li>
        <li>Priority support</li>
        <li>Monthly billing</li>
      </ul>
      <a href="payment.php?plan=premium" class="btn">Choose Premium</a>
    </div>
  </div>
</div>
</body>
</html>

<?php
$page_title = "Sign Up";
require_once 'functions.php';

// Force session start to ensure CSRF token works
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token immediately
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UNIMIND — Create Account</title>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
/* ===== CSS VARIABLES ===== */
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
  --shadow-glow-hover: rgba(0, 212, 255, 0.9);
  --neon-green: #00ff88;
  --neon-blue: #00d4ff;
  --neon-purple: #ff00ff;
  --neon-orange: #ff8800;
  --neon-cyan: #00ffff;
  --neon-pink: #ff00aa;
}

/* ===== BODY & BACKGROUND ===== */
body {
  font-family: 'Roboto', sans-serif;
  background: #0f0f20;
  color: #fff;
  overflow-x: hidden;
  min-height: 100vh;
  position: relative;
}

/* Animated Background */
body::before {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: 
    radial-gradient(ellipse at top left, rgba(0, 212, 255, 0.08) 0%, transparent 50%),
    radial-gradient(ellipse at bottom right, rgba(0, 255, 136, 0.08) 0%, transparent 50%),
    radial-gradient(ellipse at center, rgba(255, 0, 255, 0.05) 0%, transparent 60%),
    linear-gradient(135deg, #0a0a1a 0%, #1a0a2e 100%);
  z-index: -2;
}

/* Subtle background animation */
body::after {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(45deg, 
    rgba(0, 212, 255, 0.02) 0%, 
    rgba(0, 255, 136, 0.02) 33%, 
    rgba(255, 0, 255, 0.02) 66%, 
    rgba(0, 212, 255, 0.02) 100%);
  animation: subtleBackgroundShift 25s ease-in-out infinite;
  z-index: -1;
}

@keyframes subtleBackgroundShift {
  0%, 100% { 
    transform: translateX(0) translateY(0);
    opacity: 0.3;
  }
  50% { 
    transform: translateX(-30px) translateY(-20px);
    opacity: 0.5;
  }
}

/* ===== NAVIGATION ===== */
nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 3rem;
  position: fixed;
  width: 100%;
  top: 0;
  z-index: 1000;
  background: rgba(15,15,32,0.8);
  backdrop-filter: blur(10px);
}

nav .logo {
  font-family: 'Orbitron', sans-serif;
  font-size: 1.5rem;
  color: #0ff;
  text-shadow: 0 0 8px #0ff;
  cursor: pointer;
  text-decoration: none;
  transition: 0.3s;
}

nav .logo:hover {
  color: #fff;
  text-shadow: 0 0 15px #0ff, 0 0 25px #0ff;
}

nav ul {
  display: flex;
  list-style: none;
  gap: 2rem;
}

nav ul li a {
  text-decoration: none;
  color: #fff;
  font-weight: 500;
  transition: 0.3s;
}

nav ul li a:hover {
  color: #0ff;
  text-shadow: 0 0 10px #0ff;
}

/* ===== SIGNUP CONTAINER ===== */
.signup-container {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  padding: 2rem;
  margin-top: 80px;
  position: relative;
  z-index: 10;
}

/* ===== SIGNUP CARD ===== */
.signup-card {
  background: linear-gradient(135deg, rgba(15, 15, 32, 0.9), rgba(26, 10, 46, 0.9));
  border: 1px solid rgba(0, 212, 255, 0.3);
  border-radius: 20px;
  padding: 3rem;
  width: 100%;
  max-width: 500px;
  backdrop-filter: blur(20px);
  box-shadow: 
    0 20px 60px rgba(0, 0, 0, 0.4),
    0 0 0 1px rgba(0, 212, 255, 0.2),
    inset 0 1px 0 rgba(255, 255, 255, 0.1);
  position: relative;
  overflow: hidden;
}

.signup-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 3px;
  background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--accent-color));
  transform: scaleX(0);
  transform-origin: left;
  transition: transform 0.4s ease;
}

.signup-card:hover::before {
  transform: scaleX(1);
}

/* ===== SIGNUP HEADER ===== */
.signup-header {
  text-align: center;
  margin-bottom: 2.5rem;
  position: relative;
}

.signup-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
  color: var(--primary-color);
  text-shadow: 0 0 20px rgba(0, 212, 255, 0.5);
  animation: iconPulse 3s ease-in-out infinite;
}

@keyframes iconPulse {
  0%, 100% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.05); opacity: 0.8; }
}

.signup-header h1 {
  font-family: 'Orbitron', sans-serif;
  font-size: 2.5rem;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 0.5rem;
  letter-spacing: -0.5px;
  position: relative;
}

.signup-header h1::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);
  width: 60px;
  height: 2px;
  background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
  border-radius: 2px;
}

.signup-header p {
  color: rgba(255, 255, 255, 0.7);
  font-size: 0.95rem;
  line-height: 1.5;
  margin-top: 1rem;
}

/* ===== FORM STYLES ===== */
.signup-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group {
  position: relative;
}

.form-label {
  display: block;
  font-weight: 500;
  color: var(--text-primary);
  margin-bottom: 0.5rem;
  font-size: 0.9rem;
  letter-spacing: 0.5px;
  text-transform: uppercase;
}

.form-input {
  width: 100%;
  padding: 1rem 1.25rem;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(0, 212, 255, 0.2);
  border-radius: 10px;
  color: var(--text-primary);
  font-size: 1rem;
  transition: all 0.3s ease;
  backdrop-filter: blur(10px);
}

.form-input:focus {
  outline: none;
  border-color: var(--primary-color);
  background: rgba(255, 255, 255, 0.08);
  box-shadow: 
    0 0 0 3px rgba(0, 212, 255, 0.1),
    0 4px 20px rgba(0, 212, 255, 0.2);
}

.form-input::placeholder {
  color: rgba(255, 255, 255, 0.4);
}

/* Fix for select dropdown */
select.form-input {
  color: var(--text-primary);
  background: rgba(255, 255, 255, 0.05);
  cursor: pointer;
}

select.form-input option {
  background: rgba(15, 15, 32, 0.95);
  color: var(--text-primary);
  padding: 0.5rem;
}

select.form-input option:hover {
  background: rgba(0, 212, 255, 0.2);
}

.form-help {
  font-size: 0.8rem;
  color: rgba(255, 255, 255, 0.5);
  margin-top: 0.5rem;
  line-height: 1.4;
}

/* ===== BUTTON ===== */
.signup-btn {
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  color: var(--text-primary);
  border: none;
  padding: 1rem 2rem;
  border-radius: 10px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 1px;
  margin-top: 1rem;
  position: relative;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0, 212, 255, 0.3);
}

.signup-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 212, 255, 0.4);
  background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
}

.signup-btn:active {
  transform: translateY(0);
}

/* ===== LOGIN LINK ===== */
.login-link {
  text-align: center;
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.login-link p {
  color: rgba(255, 255, 255, 0.7);
  font-size: 0.9rem;
}

.login-link a {
  color: var(--primary-color);
  text-decoration: none;
  font-weight: 600;
  transition: 0.3s ease;
}

.login-link a:hover {
  color: var(--secondary-color);
  text-shadow: 0 0 10px rgba(0, 255, 136, 0.5);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  nav {
    padding: 1rem 1.5rem;
  }
  
  nav ul {
    display: none;
  }
  
  .signup-container {
    padding: 1rem;
    margin-top: 100px;
  }
  
  .signup-card {
    padding: 2rem;
    max-width: 100%;
  }
  
  .signup-header h1 {
    font-size: 2rem;
  }
}

/* ===== FLOATING PARTICLES ===== */
.particles-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  overflow: hidden;
  z-index: 0;
  pointer-events: none;
}

.particle {
  position: absolute;
  background: var(--primary-color);
  width: 4px;
  height: 4px;
  border-radius: 50%;
  opacity: 0.6;
  animation: floatParticle 8s linear infinite;
}

@keyframes floatParticle {
  0% { transform: translateY(100vh) translateX(0); opacity: 0; }
  10% { opacity: 1; }
  90% { opacity: 1; }
  100% { transform: translateY(-10vh) translateX(20px); opacity: 0; }
}
</style>
</head>
<body>

<!-- Floating Particles -->
<div class="particles-container">
  <div class="particle"></div>
  <div class="particle"></div>
  <div class="particle"></div>
  <div class="particle"></div>
  <div class="particle"></div>
</div>

<!-- Navigation -->
<nav>
  <a href="index.php" class="logo">UNIMIND</a>
  <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="login.php">Log In</a></li>
  </ul>
</nav>

<!-- Signup Container -->
<div class="signup-container">
  <div class="signup-card">
    <div class="signup-header">
      <div class="signup-icon">U</div>
      <h1>Create Account</h1>
      <p>Join UNIMIND - Your Student Success Platform</p>
    </div>

    <form id="signupForm" action="signup.php" method="POST" class="signup-form">
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
      
      <div class="form-group">
        <label for="fullname" class="form-label">Full Name</label>
        <input type="text" id="fullname" name="fullname" class="form-input" placeholder="Enter your full name" required>
      </div>
      
      <div class="form-group">
        <label for="regNumber" class="form-label">Registration Number</label>
        <input type="text" id="regNumber" name="regNumber" class="form-input" placeholder="e.g., BECE/25/SS/001" required>
        <div class="form-help">Format: PROGRAM/YY/SS/NUMBER (All students welcome)</div>
      </div>
      
      <div class="form-group">
        <label for="year_of_study" class="form-label">Year of Study</label>
        <select id="year_of_study" name="year_of_study" class="form-input" required>
          <option value="">Select your year</option>
          <option value="1">Year 1 (First Year)</option>
          <option value="2">Year 2</option>
          <option value="3">Year 3</option>
          <option value="4">Year 4</option>
          <option value="5">Year 5</option>
          <option value="6">Graduate Student</option>
        </select>
        <div class="form-help">First-year students get priority access to special features</div>
      </div>
      
      <div class="form-group">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" id="email" name="email" class="form-input" placeholder="your.email@example.com" required>
        <div class="form-help">We'll use this for important updates and opportunities</div>
      </div>
      
      <div class="form-group">
        <label for="username" class="form-label">Username</label>
        <input type="text" id="username" name="username" class="form-input" placeholder="Choose a username" required>
      </div>
      
      <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <input type="password" id="password" name="password" class="form-input" placeholder="Create a strong password" required>
        <div class="form-help">Min 8 characters, include uppercase, lowercase, number & special character</div>
      </div>
      
      <button type="submit" class="signup-btn">Create Account</button>
    </form>
    
    <div class="login-link">
      <p>Already have an account? <a href="login.php">Log In</a></p>
    </div>
  </div>
</div>

<script>
document.getElementById('signupForm').addEventListener('submit', function(e) {
    const regInput = document.querySelector('input[name="regNumber"]').value.trim().toUpperCase();
    const currentYear = new Date().getFullYear() % 100; // last two digits
    const regPattern = /^([A-Z]{3,5})\/(\d{2})\/(SS)\/(\d{1,3})$/;

    const match = regInput.match(regPattern);
    if (!match) {
        alert('Invalid registration number format! Example: BECE/25/SS/001');
        e.preventDefault();
        return;
    }

    const pgCode = match[1];
    const year = parseInt(match[2], 10); 
    const ss = match[3];
    const individual = parseInt(match[4], 10);

    // SS check
    if (ss !== 'SS') {
        alert('Registration number must include SS!');
        e.preventDefault();
        return;
    }

    // First-year check (updated to allow all years)
    if (!(year === currentYear || year === currentYear - 1 || year === currentYear + 1 || year === currentYear + 2 || year === currentYear + 3 || year === currentYear + 4)) {
        alert('Please enter a valid registration year!');
        e.preventDefault();
        return;
    }

    // Individual number check
    if (individual < 1 || individual > 999) {
        alert('Individual registration number must be between 1 and 999!');
        e.preventDefault();
        return;
    }
    
    // Password validation
    const password = document.querySelector('input[name="password"]').value;
    if (password.length < 8) {
        alert('Password must be at least 8 characters long!');
        e.preventDefault();
        return;
    }
});

// Particles randomization
const particles = document.querySelectorAll('.particle');
particles.forEach(p => {
  p.style.left = Math.random() * 100 + 'vw';
  p.style.animationDelay = Math.random() * 5 + 's';
  p.style.width = p.style.height = (Math.random() * 4 + 2) + 'px';
});
</script>

</body>
</html>


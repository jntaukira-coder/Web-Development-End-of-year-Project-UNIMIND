<?php

$page_title = "Welcome";

require_once 'functions.php';

secure_session_start();

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>UNIMIND — Student Success Platform</title>

<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">

<meta http-equiv="Pragma" content="no-cache">

<meta http-equiv="Expires" content="0">

<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<link rel="stylesheet" href="home-styles.css?v=8.4">

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

/* ===== HERO + FLOATING CARD STYLES ===== */

body {

  font-family: 'Roboto', sans-serif;

  background: #0f0f20;

  color: #fff;

  overflow-x: hidden;

}



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



/* Hero Section */

.hero {

  height: 100vh;

  display: flex;

  align-items: center;

  justify-content: center;

  position: relative;

  text-align: center;

  background: 

    radial-gradient(ellipse at top left, rgba(0, 212, 255, 0.08) 0%, transparent 50%),

    radial-gradient(ellipse at bottom right, rgba(0, 255, 136, 0.08) 0%, transparent 50%),

    radial-gradient(ellipse at center, rgba(255, 0, 255, 0.05) 0%, transparent 60%),

    linear-gradient(135deg, #0a0a1a 0%, #1a0a2e 100%);

  overflow: hidden;

}



/* Subtle background animation */

.hero::before {

  content: '';

  position: absolute;

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

  z-index: 1;

}



/* Professional geometric accents */

.hero::after {

  content: '';

  position: absolute;

  top: 10%;

  right: 10%;

  width: 200px;

  height: 200px;

  border: 1px solid rgba(0, 212, 255, 0.1);

  border-radius: 50%;

  animation: rotateSlow 30s linear infinite;

  z-index: 1;

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



@keyframes rotateSlow {

  0% { transform: rotate(0deg); }

  100% { transform: rotate(360deg); }

}



.hero-content {

  position: relative;

  z-index: 10;

  max-width: 900px;

  padding: 2rem;

}



/* Professional hero badge */

.hero-badge {

  display: inline-block;

  background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(0, 255, 136, 0.1));

  border: 1px solid rgba(0, 212, 255, 0.3);

  color: var(--text-primary);

  padding: 0.6rem 1.5rem;

  border-radius: 30px;

  font-size: 0.85rem;

  font-weight: 600;

  letter-spacing: 2px;

  text-transform: uppercase;

  margin-bottom: 2rem;

  backdrop-filter: blur(10px);

  transition: all 0.3s ease;

}



.hero-badge:hover {

  border-color: rgba(0, 212, 255, 0.6);

  background: linear-gradient(135deg, rgba(0, 212, 255, 0.15), rgba(0, 255, 136, 0.15));

  transform: translateY(-2px);

}



/* Professional heading */

.hero-content h1 {

  font-family: 'Orbitron', sans-serif;

  font-size: 4rem;

  font-weight: 700;

  color: #ffffff;

  margin-bottom: 1.5rem;

  line-height: 1.1;

  letter-spacing: -1px;

  position: relative;

}



.hero-content h1::after {

  content: '';

  position: absolute;

  bottom: -10px;

  left: 50%;

  transform: translateX(-50%);

  width: 100px;

  height: 3px;

  background: linear-gradient(90deg, transparent, var(--primary-color), transparent);

  border-radius: 2px;

}



/* Professional paragraph */

.hero-content p {

  font-size: 1.25rem;

  font-weight: 300;

  margin-bottom: 3rem;

  color: rgba(255, 255, 255, 0.8);

  line-height: 1.7;

  max-width: 600px;

  margin-left: auto;

  margin-right: auto;

}



/* Professional button container */

.hero-buttons {

  display: flex;

  gap: 1.5rem;

  justify-content: center;

  flex-wrap: wrap;

}



/* Professional hero buttons */

.hero-buttons .hero-btn {

  display: inline-block;

  padding: 1rem 2.5rem;

  border-radius: 8px;

  font-weight: 600;

  font-size: 1rem;

  text-decoration: none;

  transition: all 0.3s ease;

  position: relative;

  overflow: hidden;

  letter-spacing: 0.5px;

}



.hero-btn-primary {

  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));

  color: var(--text-primary);

  border: none;

  box-shadow: 0 4px 15px rgba(0, 212, 255, 0.3);

}



.hero-btn-primary:hover {

  transform: translateY(-2px);

  box-shadow: 0 8px 25px rgba(0, 212, 255, 0.4);

  background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));

}



.hero-btn-secondary {

  background: transparent;

  color: var(--text-primary);

  border: 2px solid rgba(255, 255, 255, 0.3);

  backdrop-filter: blur(10px);

}



.hero-btn-secondary:hover {

  background: rgba(255, 255, 255, 0.1);

  border-color: rgba(255, 255, 255, 0.6);

  transform: translateY(-2px);

  color: var(--text-primary);

}



/* Hero content animations */

@keyframes heroContentFloat {

  0%, 100% { transform: translateY(0); }

  50% { transform: translateY(-10px); }

}



@keyframes badgeShimmer {

  0%, 100% { 

    box-shadow: 0 0 30px rgba(0, 212, 255, 0.5);

    transform: scale(1);

  }

  50% { 

    box-shadow: 0 0 50px rgba(0, 212, 255, 0.8);

    transform: scale(1.02);

  }

}



@keyframes badgeFloat {

  0%, 100% { transform: translateY(0); }

  50% { transform: translateY(-5px); }

}



@keyframes badgeShine {

  0% { left: -100%; }

  50%, 100% { left: 100%; }

}



@keyframes headingGlow {

  0%, 100% { 

    text-shadow: 

      0 0 20px rgba(0, 255, 255, 0.8),

      0 0 40px rgba(0, 255, 255, 0.6),

      0 0 60px rgba(0, 255, 255, 0.4),

      0 0 80px rgba(0, 255, 255, 0.2);

  }

  50% { 

    text-shadow: 

      0 0 30px rgba(0, 255, 255, 1),

      0 0 60px rgba(0, 255, 255, 0.8),

      0 0 90px rgba(0, 255, 255, 0.6),

      0 0 120px rgba(0, 255, 255, 0.4);

  }

}



@keyframes headingPulse {

  0%, 100% { transform: scale(1); }

  50% { transform: scale(1.02); }

}



@keyframes textFade {

  0%, 100% { opacity: 0.8; }

  50% { opacity: 1; }

}



@keyframes buttonsFloat {

  0%, 100% { transform: translateY(0); }

  50% { transform: translateY(-8px); }

}



@keyframes primaryButtonPulse {

  0%, 100% { 

    box-shadow: 

      0 5px 20px rgba(0, 212, 255, 0.4),

      0 0 40px rgba(0, 212, 255, 0.2),

      inset 0 0 20px rgba(255, 255, 255, 0.1);

  }

  50% { 

    box-shadow: 

      0 8px 30px rgba(0, 212, 255, 0.6),

      0 0 60px rgba(0, 212, 255, 0.4),

      inset 0 0 30px rgba(255, 255, 255, 0.2);

  }

}



@keyframes secondaryButtonPulse {

  0%, 100% { 

    box-shadow: 

      0 5px 20px rgba(0, 212, 255, 0.3),

      inset 0 0 20px rgba(0, 212, 255, 0.1);

  }

  50% { 

    box-shadow: 

      0 8px 30px rgba(0, 212, 255, 0.5),

      inset 0 0 30px rgba(0, 212, 255, 0.2);

  }

}



/* Floating Create Account Card */

.floating-card {

  position: fixed;

  top: 25%;

  right: 3%;

  background: linear-gradient(135deg, 

    rgba(15, 15, 32, 0.05), 

    rgba(26, 10, 46, 0.05),

    rgba(15, 15, 32, 0.08));

  border: 1px solid rgba(0, 212, 255, 0.2);

  border-radius: 16px;

  padding: 2rem;

  width: 280px;

  text-align: center;

  backdrop-filter: blur(15px);

  -webkit-backdrop-filter: blur(15px);

  box-shadow: 

    0 4px 16px rgba(0, 0, 0, 0.05),

    0 0 0 1px rgba(0, 212, 255, 0.1),

    inset 0 1px 0 rgba(255, 255, 255, 0.15),

    inset 0 -1px 0 rgba(255, 255, 255, 0.05);

  transition: all 0.3s ease;

  z-index: 9999;

}



.floating-card:hover {

  transform: translateY(-5px);

  border-color: rgba(0, 212, 255, 0.3);

  background: linear-gradient(135deg, 

    rgba(15, 15, 32, 0.08), 

    rgba(26, 10, 46, 0.08),

    rgba(15, 15, 32, 0.12));

  box-shadow: 

    0 8px 24px rgba(0, 0, 0, 0.08),

    0 0 0 1px rgba(0, 212, 255, 0.15),

    inset 0 1px 0 rgba(255, 255, 255, 0.2),

    inset 0 -1px 0 rgba(255, 255, 255, 0.1);

}



.floating-card h3 {

  font-family: 'Orbitron', sans-serif;

  font-size: 1.2rem;

  font-weight: 600;

  color: var(--text-primary);

  margin-bottom: 0.5rem;

  letter-spacing: 0.5px;

  text-shadow: 

    0 1px 3px rgba(0, 0, 0, 0.8),

    0 0 10px rgba(0, 212, 255, 0.3);

}



.floating-card .card-subtitle {

  font-size: 0.9rem;

  color: rgba(255, 255, 255, 0.9);

  margin-bottom: 1.5rem;

  line-height: 1.4;

  text-shadow: 

    0 1px 3px rgba(0, 0, 0, 0.8),

    0 0 8px rgba(0, 212, 255, 0.2);

}



.floating-card .card-icon {

  font-size: 2.5rem;

  margin-bottom: 1rem;

  opacity: 1;

  filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.8));

}



.floating-card a {

  display: block;

  padding: 0.875rem 1.5rem;

  margin: 0.75rem 0;

  border-radius: 8px;

  text-decoration: none;

  font-weight: 600;

  font-size: 0.9rem;

  text-transform: uppercase;

  letter-spacing: 0.5px;

  transition: all 0.3s ease;

  position: relative;

  overflow: hidden;

  text-shadow: 

    0 1px 3px rgba(0, 0, 0, 0.8),

    0 0 8px rgba(0, 212, 255, 0.2);

}



.floating-card .btn-primary {

  background: linear-gradient(135deg, 

    rgba(0, 212, 255, 0.7), 

    rgba(0, 255, 136, 0.7));

  color: var(--text-primary);

  border: none;

  box-shadow: 

    0 4px 15px rgba(0, 212, 255, 0.2),

    inset 0 1px 0 rgba(255, 255, 255, 0.2);

}



.floating-card .btn-primary:hover {

  transform: translateY(-2px);

  box-shadow: 

    0 8px 25px rgba(0, 212, 255, 0.3),

    inset 0 1px 0 rgba(255, 255, 255, 0.3);

  background: linear-gradient(135deg, 

    rgba(0, 255, 136, 0.7), 

    rgba(0, 212, 255, 0.7));

}



.floating-card .btn-secondary {

  background: rgba(255, 255, 255, 0.08);

  color: var(--text-primary);

  border: 1px solid rgba(255, 255, 255, 0.2);

  backdrop-filter: blur(8px);

  -webkit-backdrop-filter: blur(8px);

  box-shadow: 

    0 4px 15px rgba(0, 0, 0, 0.05),

    inset 0 1px 0 rgba(255, 255, 255, 0.15);

}



.floating-card .btn-secondary:hover {

  background: rgba(255, 255, 255, 0.12);

  border-color: rgba(255, 255, 255, 0.3);

  transform: translateY(-2px);

  color: var(--text-primary);

  box-shadow: 

    0 8px 25px rgba(0, 0, 0, 0.08),

    inset 0 1px 0 rgba(255, 255, 255, 0.2);

}



.floating-card .divider {

  height: 1px;

  background: linear-gradient(90deg, 

    transparent, 

    rgba(0, 212, 255, 0.3), 

    transparent);

  margin: 1.5rem 0;

  position: relative;

}



.floating-card .divider::before {

  content: '';

  position: absolute;

  top: -1px;

  left: 0;

  right: 0;

  height: 1px;

  background: linear-gradient(90deg, 

    transparent, 

    rgba(255, 255, 255, 0.15), 

    transparent);

}



/* Floating animation */

@keyframes float {

  0%, 100% { transform: translateY(0) translateX(0); }

  50% { transform: translateY(-10px) translateX(5px); }

}



/* Particles */

.particles-container {

  position: absolute;

  top: 0;

  left: 0;

  width: 100%;

  height: 100%;

  overflow: hidden;

  z-index: 0;

}



.particle {

  position: absolute;

  background: #0ff;

  width: 6px;

  height: 6px;

  border-radius: 50%;

  opacity: 0.7;

  animation: floatParticle 6s linear infinite;

}



@keyframes floatParticle {

  0% { transform: translateY(100vh) translateX(0); opacity: 0; }

  10% { opacity: 1; }

  100% { transform: translateY(-10vh) translateX(20px); opacity: 0; }

}



/* Enhanced Section Headers */

.section-header {

  text-align: center;

  margin-bottom: 4rem;

  position: relative;

}



.section-badge {

  display: inline-block;

  background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));

  color: var(--text-primary);

  padding: 0.5rem 1.5rem;

  border-radius: 50px;

  font-size: 0.9rem;

  font-weight: 600;

  letter-spacing: 2px;

  text-transform: uppercase;

  margin-bottom: 1rem;

  box-shadow: 0 0 20px rgba(0, 212, 255, 0.3);

  animation: badgeGlow 3s ease-in-out infinite;

}



.section-header h2 {

  font-family: 'Orbitron', sans-serif;

  font-size: 2.5rem;

  font-weight: 700;

  color: var(--text-primary);

  text-shadow: 0 0 20px rgba(0, 212, 255, 0.4);

  margin-bottom: 1rem;

}



/* Premium Features Grid */

.features-grid {

  display: grid;

  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));

  gap: 2rem;

  max-width: 1200px;

  margin: 0 auto;

  padding: 0 2rem;

}



/* Enhanced Feature Cards */

.feature-card {

  background: linear-gradient(135deg, rgba(15, 15, 32, 0.8), rgba(26, 10, 46, 0.8));

  border: 1px solid rgba(0, 212, 255, 0.3);

  border-radius: 20px;

  padding: 2.5rem;

  position: relative;

  overflow: hidden;

  transition: all 0.4s ease;

  backdrop-filter: blur(15px);

  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);

}



.feature-card::before {

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



.feature-card::after {

  content: '';

  position: absolute;

  top: 50%;

  left: 50%;

  width: 0;

  height: 0;

  background: radial-gradient(circle, rgba(0, 212, 255, 0.1) 0%, transparent 70%);

  transition: all 0.4s ease;

  transform: translate(-50%, -50%);

}



.feature-card:hover {

  transform: translateY(-15px) scale(1.02);

  border-color: var(--primary-color);

  box-shadow: 0 20px 40px rgba(0, 212, 255, 0.3);

  background: linear-gradient(135deg, rgba(15, 15, 32, 0.9), rgba(26, 10, 46, 0.9));

}



.feature-card:hover::before {

  transform: scaleX(1);

}



.feature-card:hover::after {

  width: 400px;

  height: 400px;

}



/* Premium Stats Section */

.stats-section {

  display: grid;

  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));

  gap: 2rem;

  max-width: 1000px;

  margin: 0 auto;

  padding: 3rem 2rem;

  background: linear-gradient(135deg, rgba(15, 15, 32, 0.6), rgba(26, 10, 46, 0.6));

  border-radius: 30px;

  backdrop-filter: blur(20px);

  border: 1px solid rgba(0, 212, 255, 0.2);

  position: relative;

  overflow: hidden;

}



.stats-section::before {

  content: '';

  position: absolute;

  top: 0;

  left: 0;

  width: 100%;

  height: 100%;

  background: conic-gradient(from 0deg, var(--primary-color), var(--secondary-color), var(--accent-color), var(--primary-color));

  opacity: 0.1;

  animation: rotateGradient 10s linear infinite;

}



.stat-item {

  text-align: center;

  position: relative;

  z-index: 2;

}



.stat-number {

  display: block;

  font-family: 'Orbitron', sans-serif;

  font-size: 3rem;

  font-weight: 700;

  color: var(--primary-color);

  text-shadow: 0 0 15px rgba(0, 212, 255, 0.5);

  margin-bottom: 0.5rem;

  animation: numberPulse 2s ease-in-out infinite;

}



.stat-label {

  font-size: 1.1rem;

  color: var(--text-secondary);

  font-weight: 500;

  letter-spacing: 1px;

}



/* Premium Buttons */

.btn {

  display: inline-block;

  padding: 0.8rem 2rem;

  border-radius: 50px;

  text-decoration: none;

  font-weight: 600;

  text-align: center;

  transition: all 0.3s ease;

  position: relative;

  overflow: hidden;

  border: none;

  cursor: pointer;

}



.btn-primary {

  background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));

  color: var(--text-primary);

  box-shadow: 0 5px 20px rgba(0, 212, 255, 0.3);

}



.btn-primary:hover {

  transform: translateY(-3px);

  box-shadow: 0 10px 30px rgba(0, 212, 255, 0.5);

}



.btn-secondary {

  background: transparent;

  color: var(--accent-color);

  border: 2px solid var(--accent-color);

  box-shadow: 0 5px 20px rgba(255, 0, 255, 0.2);

}



.btn-secondary:hover {

  background: var(--accent-color);

  color: var(--text-primary);

  transform: translateY(-3px);

  box-shadow: 0 10px 30px rgba(255, 0, 255, 0.4);

}



/* Animations */

@keyframes badgeGlow {

  0%, 100% { box-shadow: 0 0 20px rgba(0, 212, 255, 0.3); }

  50% { box-shadow: 0 0 30px rgba(0, 212, 255, 0.6); }

}



@keyframes rotateGradient {

  0% { transform: rotate(0deg); }

  100% { transform: rotate(360deg); }

}



@keyframes numberPulse {

  0%, 100% { opacity: 1; transform: scale(1); }

  50% { opacity: 0.8; transform: scale(1.05); }

}



</style>

</head>

<body>



<!-- Floating Particles -->

<div class="particles-container">

  <div class="particle"></div>

  <div class="particle"></div>

  <div class="particle"></div>

</div>



<!-- Navigation -->

<nav>

  <a href="index.php" class="logo">UNIMIND</a>

  <ul>

    <?php if (is_logged_in()): ?>

      <li><a href="index.php">Home</a></li>

      <li><a href="Home.php">Dashboard</a></li>

      <li><a href="logout.php">Logout</a></li>

    <?php else: ?>

      <li><a href="index.php">Home</a></li>

      <li><a href="signup_form.php">Sign Up</a></li>

      <li><a href="login.php">Log In</a></li>

    <?php endif; ?>

  </ul>

</nav>



<!-- Professional Floating Card -->

<?php if (!is_logged_in()): ?>

  <div class="floating-card">

    <div class="card-icon">U</div>

    <h3>Join UNIMIND Today</h3>

    <div class="card-subtitle">Transform your university journey with AI-powered guidance</div>

    <div class="divider"></div>

    <a href="signup_form.php" class="btn-primary">Create Account</a>

    <a href="login.php" class="btn-secondary">Log In</a>

  </div>

<?php endif; ?>



<!-- Hero Section -->

<section class="hero">

  <!-- Floating Orbs -->

  <div class="hero-orb"></div>

  <div class="hero-orb"></div>

  <div class="hero-orb"></div>

  

  <div class="hero-content">

    <div class="hero-badge">FUTURE OF STUDENT SUCCESS</div>

    <h1>Transform Your University Journey</h1>

    <p>Experience the future of student success with AI-powered guidance, seamless campus navigation, and unlimited opportunities for growth.</p>

    <div class="hero-buttons">

      <?php if (!is_logged_in()): ?>

        <a href="signup_form.php" class="hero-btn hero-btn-primary">Get Started</a>

        <a href="login.php" class="hero-btn hero-btn-secondary">Learn More</a>

      <?php else: ?>

        <a href="Home.php" class="hero-btn hero-btn-primary">Go to Dashboard</a>

        <a href="#features" class="hero-btn hero-btn-secondary">Explore Features</a>

      <?php endif; ?>

    </div>

  </div>

</section>



<!-- Features Section -->

<section id="features">

  <div class="section-header">

    <div class="section-badge">FEATURES</div>

    <h2>Revolutionary Tools</h2>

  </div>

  

  <div class="features-grid">

    <div class="feature-card">

      <div class="corner-decoration top-left"></div>

      <div class="corner-decoration top-right"></div>

      <div class="corner-decoration bottom-left"></div>

      <div class="corner-decoration bottom-right"></div>

      <div class="feature-icon">A</div>

      <h3>Safe Accommodation</h3>

      <p>Verified hostel listings to avoid scams and ensure your safety with AI-powered matching and virtual tours.</p>

      <a href="Accomodation.php" class="btn btn-primary">Explore →</a>

    </div>

    

    <div class="feature-card">

      <div class="corner-decoration top-left"></div>

      <div class="corner-decoration top-right"></div>

      <div class="corner-decoration bottom-left"></div>

      <div class="corner-decoration bottom-right"></div>

      <div class="feature-icon">S</div>

      <h3>Campus Services</h3>

      <p>Directories of nearby hospitals, shops, banks and facilities with real-time availability and reviews.</p>

      <a href="services.php" class="btn btn-primary">Discover →</a>

    </div>

    

    <div class="feature-card">

      <div class="corner-decoration top-left"></div>

      <div class="corner-decoration top-right"></div>

      <div class="corner-decoration bottom-left"></div>

      <div class="corner-decoration bottom-right"></div>

      <div class="feature-icon">C</div>

      <h3>Campus Life Plus</h3>

      <p>Event discovery and personalized activity recommendations tailored to your interests and schedule.</p>

      <a href="campus life.php" class="btn btn-primary">Join Now →</a>

    </div>

    

    <div class="feature-card">

      <div class="corner-decoration top-left"></div>

      <div class="corner-decoration top-right"></div>

      <div class="corner-decoration bottom-left"></div>

      <div class="corner-decoration bottom-right"></div>

      <div class="feature-icon">Y</div>

      <h3>Yaza Corner</h3>

      <p>Your exclusive space for relaxation, creativity, and personal growth with wellness activities and study lounges.</p>

      <?php if (!is_logged_in()): ?>

        <a href="login.php" class="btn btn-secondary">Login Required</a>

      <?php else: ?>

        <a href="yaza_corner.php" class="btn btn-primary">Enter Corner →</a>

      <?php endif; ?>

    </div>

    

    <div class="feature-card">

      <div class="corner-decoration top-left"></div>

      <div class="corner-decoration top-right"></div>

      <div class="corner-decoration bottom-left"></div>

      <div class="corner-decoration bottom-right"></div>

      <div class="feature-icon">M</div>

      <h3>AI Mentor Match</h3>

      <p>Connect with experienced students for guidance and support through smart compatibility matching.</p>

      <a href="register.php" class="btn btn-primary">Connect →</a>

    </div>

  </div>

</section>



<!-- Stats Section -->

<section>

  <div class="section-header">

    <div class="section-badge">IMPACT</div>

    <h2>By The Numbers</h2>

  </div>

  

  <div class="stats-section">

    <div class="stat-item">

      <span class="stat-number">5000+</span>

      <div class="stat-label">Active Students</div>

    </div>

    <div class="stat-item">

      <span class="stat-number">150+</span>

      <div class="stat-label">Partner Companies</div>

    </div>

    <div class="stat-item">

      <span class="stat-number">98%</span>

      <div class="stat-label">Success Rate</div>

    </div>

    <div class="stat-item">

      <span class="stat-number">24/7</span>

      <div class="stat-label">Support Available</div>

    </div>

  </div>

</section>



<!-- Step Further Section -->

<section id="step-further">

  <div class="section-header">

    <div class="section-badge">NEXT LEVEL</div>

    <h2>Step Further</h2>

    <p>Find yourself. Master your focus. Make your university journey yours. – Unlock Your Full Potential</p>

  </div>

  

  <div class="features-grid">

    <div class="feature-card">

      <div class="corner-decoration top-left"></div>

      <div class="corner-decoration top-right"></div>

      <div class="corner-decoration bottom-left"></div>

      <div class="corner-decoration bottom-right"></div>

      <div class="feature-icon">D</div>

      <h3>Discover Yourself</h3>

      <p>Personal growth and self-assessment tools with AI-powered insights and recommendations.</p>

      <?php if (!is_logged_in()): ?>

        <a href="login.php" class="btn btn-secondary">Login Required</a>

      <?php else: ?>

        <a href="aboutme.php" class="btn btn-secondary">Access Now →</a>

      <?php endif; ?>

    </div>

    

    <div class="feature-card">

      <div class="corner-decoration top-left"></div>

      <div class="corner-decoration top-right"></div>

      <div class="corner-decoration bottom-left"></div>

      <div class="corner-decoration bottom-right"></div>

      <div class="feature-icon">F</div>

      <h3>Focus Zone</h3>

      <p>Productivity and study enhancement tools with smart distraction blocking and time tracking.</p>

      <?php if (!is_logged_in()): ?>

        <a href="login.php" class="btn btn-secondary">Login Required</a>

      <?php else: ?>

        <a href="focus.php" class="btn btn-secondary">Enter Zone →</a>

      <?php endif; ?>

    </div>

  </div>

</section>



<!-- About UNIMIND -->

<section id="about">

  <div class="section-header">

    <div class="section-badge">ABOUT</div>

    <h2>UNIMIND</h2>

    <p>Your comprehensive student arrival toolkit designed to help first-year university students in Malawi navigate campus life safely and confidently.</p>

  </div>

  

  <div class="quick-links">

    <a href="index.php" class="quick-link">

      <span class="quick-link-icon">H</span>

      <span class="quick-link-text">Home</span>

    </a>

    <a href="Accomodation.php" class="quick-link">

      <span class="quick-link-icon">A</span>

      <span class="quick-link-text">Accommodation</span>

    </a>

    <a href="services.php" class="quick-link">

      <span class="quick-link-icon">S</span>

      <span class="quick-link-text">Services</span>

    </a>

    <a href="campus life.php" class="quick-link">

      <span class="quick-link-icon">C</span>

      <span class="quick-link-text">Campus Life</span>

    </a>

    <a href="campus_map.php" class="quick-link">

      <span class="quick-link-icon">M</span>

      <span class="quick-link-text">Campus Map</span>

    </a>

    <a href="opportunities.php" class="quick-link">

      <span class="quick-link-icon">O</span>

      <span class="quick-link-text">Opportunities</span>

    </a>

    <a href="yaza_corner.php" class="quick-link">

      <span class="quick-link-icon">Y</span>

      <span class="quick-link-text">Yaza Corner</span>

    </a>

    <a href="Home.php" class="quick-link">

      <span class="quick-link-icon">D</span>

      <span class="quick-link-text">Dashboard</span>

    </a>

  </div>

</section>



<footer>

  <p>UNIMIND 2025 | Revolutionizing Student Success</p>

</footer>



<script>

// Smooth scroll behavior

document.querySelectorAll('a[href^="#"]').forEach(anchor => {

  anchor.addEventListener('click', function (e) {

    e.preventDefault();

    const target = document.querySelector(this.getAttribute('href'));

    if (target) {

      target.scrollIntoView({ behavior: 'smooth' });

    }

  });

});



// Particles randomization

const particles = document.querySelectorAll('.particle');

particles.forEach(p => {

  p.style.left = Math.random() * 100 + 'vw';

  p.style.animationDelay = Math.random() * 5 + 's';

  p.style.width = p.style.height = (Math.random() * 6 + 3) + 'px';

});

</script>



</body>

</html>
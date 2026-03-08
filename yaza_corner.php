<?php
require_once 'auth_protect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UNIMIND — Yaza Corner</title>
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

/* ===== YAZA CORNER CONTAINER ===== */
.yaza-container {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  padding: 2rem;
  margin-top: 80px;
  position: relative;
  z-index: 10;
}

/* ===== YAZA CORNER CARD ===== */
.yaza-card {
  background: linear-gradient(135deg, rgba(15, 15, 32, 0.9), rgba(26, 10, 46, 0.9));
  border: 1px solid rgba(0, 212, 255, 0.3);
  border-radius: 20px;
  padding: 3rem;
  width: 100%;
  max-width: 800px;
  backdrop-filter: blur(20px);
  box-shadow: 
    0 20px 60px rgba(0, 0, 0, 0.4),
    0 0 0 1px rgba(0, 212, 255, 0.2),
    inset 0 1px 0 rgba(255, 255, 255, 0.1);
  position: relative;
  overflow: hidden;
}

.yaza-card::before {
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

.yaza-card:hover::before {
  transform: scaleX(1);
}

/* ===== YAZA HEADER ===== */
.yaza-header {
  text-align: center;
  margin-bottom: 3rem;
  position: relative;
}

.yaza-icon {
  font-size: 4rem;
  margin-bottom: 1rem;
  color: var(--primary-color);
  text-shadow: 0 0 30px rgba(0, 212, 255, 0.5);
  animation: iconPulse 3s ease-in-out infinite;
}

@keyframes iconPulse {
  0%, 100% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.1); opacity: 0.8; }
}

.yaza-header h1 {
  font-family: 'Orbitron', sans-serif;
  font-size: 3rem;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 1rem;
  letter-spacing: -1px;
  position: relative;
}

.yaza-header h1::after {
  content: '';
  position: absolute;
  bottom: -15px;
  left: 50%;
  transform: translateX(-50%);
  width: 100px;
  height: 3px;
  background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
  border-radius: 2px;
}

.yaza-header p {
  color: rgba(255, 255, 255, 0.7);
  font-size: 1.1rem;
  line-height: 1.6;
  margin-top: 2rem;
}

/* ===== YAZA FEATURES ===== */
.yaza-features {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
  margin-bottom: 3rem;
}

.yaza-feature {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(0, 212, 255, 0.2);
  border-radius: 15px;
  padding: 2rem;
  text-align: center;
  transition: all 0.3s ease;
  backdrop-filter: blur(10px);
}

.yaza-feature:hover {
  transform: translateY(-5px);
  border-color: var(--primary-color);
  background: rgba(255, 255, 255, 0.08);
  box-shadow: 0 10px 30px rgba(0, 212, 255, 0.2);
}

.yaza-feature-icon {
  font-size: 2.5rem;
  margin-bottom: 1rem;
  color: var(--secondary-color);
  text-shadow: 0 0 20px rgba(0, 255, 136, 0.5);
}

.yaza-feature h3 {
  font-family: 'Orbitron', sans-serif;
  font-size: 1.3rem;
  font-weight: 600;
  color: var(--text-primary);
  margin-bottom: 1rem;
}

.yaza-feature p {
  color: rgba(255, 255, 255, 0.7);
  line-height: 1.5;
}

/* ===== YAZA CONTENT ===== */
.yaza-content {
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(0, 212, 255, 0.1);
  border-radius: 15px;
  padding: 2.5rem;
  margin-bottom: 2rem;
}

.yaza-content h2 {
  font-family: 'Orbitron', sans-serif;
  font-size: 1.8rem;
  font-weight: 600;
  color: var(--primary-color);
  margin-bottom: 1.5rem;
  text-align: center;
}

.yaza-content p {
  color: rgba(255, 255, 255, 0.8);
  line-height: 1.6;
  margin-bottom: 1.5rem;
}

.yaza-content ul {
  list-style: none;
  padding: 0;
}

.yaza-content li {
  color: rgba(255, 255, 255, 0.8);
  margin-bottom: 1rem;
  padding-left: 2rem;
  position: relative;
}

.yaza-content li::before {
  content: '▸';
  position: absolute;
  left: 0;
  color: var(--secondary-color);
  font-weight: bold;
}

/* ===== YAZA ACTIONS ===== */
.yaza-actions {
  display: flex;
  gap: 2rem;
  justify-content: center;
  flex-wrap: wrap;
}

.yaza-btn {
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
  text-decoration: none;
  display: inline-block;
  box-shadow: 0 4px 15px rgba(0, 212, 255, 0.3);
}

.yaza-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 212, 255, 0.4);
  background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
}

.yaza-btn-secondary {
  background: transparent;
  color: var(--primary-color);
  border: 2px solid var(--primary-color);
  box-shadow: none;
}

.yaza-btn-secondary:hover {
  background: var(--primary-color);
  color: var(--text-primary);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  nav {
    padding: 1rem 1.5rem;
  }
  
  nav ul {
    display: none;
  }
  
  .yaza-container {
    padding: 1rem;
    margin-top: 100px;
  }
  
  .yaza-card {
    padding: 2rem;
    max-width: 100%;
  }
  
  .yaza-header h1 {
    font-size: 2rem;
  }
  
  .yaza-features {
    grid-template-columns: 1fr;
  }
  
  .yaza-actions {
    flex-direction: column;
    align-items: center;
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
    <li><a href="Accomodation.php">Accommodation</a></li>
    <li><a href="services.php">Services</a></li>
    <li><a href="campus life.php">Campus Life</a></li>
    <li><a href="campus_map.php">Campus Map</a></li>
    <li><a href="opportunities.php">Opportunities</a></li>
    <li><a href="yaza_corner.php" style="color: var(--secondary-color);">Yaza Corner</a></li>
    <li><a href="Home.php">Dashboard</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</nav>

<!-- Yaza Corner Container -->
<div class="yaza-container">
  <div class="yaza-card">
    <div class="yaza-header">
      <div class="yaza-icon">Y</div>
      <h1>Yaza Corner</h1>
      <p>Your exclusive space for relaxation, creativity, and personal growth</p>
    </div>

    <div class="yaza-features">
      <div class="yaza-feature">
        <div class="yaza-feature-icon">🎨</div>
        <h3>Creative Hub</h3>
        <p>Express yourself through art, music, and creative writing in our dedicated creative spaces</p>
      </div>
      
      <div class="yaza-feature">
        <div class="yaza-feature-icon">🧘</div>
        <h3>Wellness Zone</h3>
        <p>Meditation, yoga, and mindfulness activities to help you stay balanced and focused</p>
      </div>
      
      <div class="yaza-feature">
        <div class="yaza-feature-icon">📚</div>
        <h3>Study Lounge</h3>
        <p>Quiet, comfortable spaces designed for optimal studying and group collaboration</p>
      </div>
    </div>

    <div class="yaza-content">
      <h2>Welcome to Your Personal Sanctuary</h2>
      <p>Yaza Corner is more than just a space – it's your personal retreat within the bustling campus life. Here, you can unwind, recharge, and connect with like-minded students who share your interests.</p>
      
      <h2>What's Available</h2>
      <ul>
        <li>Comfortable seating areas with charging stations</li>
        <li>High-speed WiFi and computer workstations</li>
        <li>Art supplies and creative materials</li>
        <li>Quiet study rooms and collaboration spaces</li>
        <li>Wellness activities and meditation sessions</li>
        <li>Student-led workshops and events</li>
        <li>Refreshment corner with healthy snacks</li>
      </ul>
    </div>

    <div class="yaza-actions">
      <a href="#" class="yaza-btn">Reserve Space</a>
      <a href="#" class="yaza-btn yaza-btn-secondary">View Schedule</a>
    </div>
  </div>
</div>

<script>
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

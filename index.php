<?php
session_start();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UniMind — Welcome</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins', sans-serif; }
body { background:#021526; color:#fff; line-height:1.6; }

/* Navigation */
nav {
  width:100%;
  background:#021526;
  position:fixed;
  top:0; left:0;
  z-index:1000;
  box-shadow:0 2px 5px rgba(0,0,0,0.4);
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:10px 20px;
}
nav .logo { font-size:1.5rem; font-weight:700; color:#38bdf8; }
nav ul {
  list-style:none;
  display:flex;
  gap:20px;
}
nav ul li a {
  text-decoration:none;
  color:#fff;
  font-weight:600;
  transition:0.3s;
  cursor:pointer;
}
nav ul li a:hover { color:#38bdf8; }

.hamburger { display:none; cursor:pointer; flex-direction:column; gap:5px; }
.hamburger div { width:25px; height:3px; background:#fff; }
@media(max-width:768px) {
  nav ul { display:none; flex-direction:column; background:#021526; position:absolute; top:50px; right:20px; width:200px; padding:10px; border-radius:8px; }
  nav ul.show { display:flex; }
  .hamburger { display:flex; }
}

/* Hero */
.hero {
  height:100vh;
  background:url('pictures/308172-aestheticwp.jpg') center/cover no-repeat fixed;
  display:flex;
  justify-content:center;
  align-items:center;
  text-align:center;
  padding:0 20px;
  position:relative;
}
.hero::before {
  content:"";
  position:absolute; top:0; left:0; width:100%; height:100%;
  background:rgba(0,0,0,0.5);
  z-index:0;
}
.hero-content { position:relative; z-index:1; max-width:700px; }
.hero-content h1 { font-size:3rem; margin-bottom:20px; color:#38bdf8; }
.hero-content p { font-size:1.2rem; margin-bottom:30px; color:#fff; }

/* Main Purpose */
.main-purpose {
  padding:80px 20px 40px 20px;
  background:#0f1724;
  text-align:center;
}
.main-purpose h2 { font-size:2rem; color:#38bdf8; margin-bottom:20px; }
.main-purpose p { max-width:800px; margin:0 auto 30px auto; color:#fff; }

/* Additional Features */
.additional {
  padding:40px 20px;
  background:#1a2236;
  text-align:center;
}
.additional h2 { font-size:2rem; color:#38bdf8; margin-bottom:20px; }
.features { display:flex; flex-wrap:wrap; justify-content:center; gap:20px; }
.feature-card {
  background:#021526;
  padding:20px;
  border-radius:12px;
  width:200px;
  transition:0.3s;
  cursor:pointer;
}
.feature-card:hover { transform:scale(1.05); background:#0f1724; }
.locked {
  opacity:0.5;
  position:relative;
}
.locked::after {
  position:absolute;
  top:50%; left:50%;
  transform:translate(-50%, -50%);
  font-size:0.9rem;
  color:#f87171;
  background:rgba(0,0,0,0.6);
  padding:5px 10px;
  border-radius:8px;
}

/* Footer */
footer {
  background:#021526;
  padding:20px;
  text-align:center;
  color:#fff;
}
</style>
</head>
<body>

<!-- Navigation -->
<nav>
  <div class="logo">UNIMIND</div>
  <ul>
    <li><a href="signup_form.php">Sign Up</a></li>
    <li><a href="login.php">Log In</a></li>
  </ul>
  <div class="hamburger" onclick="toggleMenu()">
    <div></div><div></div><div></div>
  </div>
</nav>

<!-- Hero Section -->
<section class="hero">
  <div class="hero-content">
    <h1>Your Student Arrival Toolkit</h1>
    <p>Starting university in a new city is thrilling—but a little overwhelming. Where to stay? How to get around? What’s nearby? We’ve got the answers, so your first weeks are smooth, safe, and stress-free.</p>
  </div>
</section>

<!-- Main Purpose -->
<section class="main-purpose">
  <h2>Main Purpose</h2>
  <p>UniMind helps first-year students arriving from afar to find safe accommodation, navigate campus, locate nearby services, hospitals, shops, and banks. Everything you need to settle in quickly and safely.</p>
</section>

<!-- Additional Features -->
<section class="additional">
  <h2>Step Further</h2>
  <p>Find yourself. Master your focus. Make your university journey yours. – Unlock Your Full Potential</p>
  <div class="features">
    <div class="feature-card <?php if(!$isLoggedIn) echo 'locked'; ?>" data-locked="<?php echo !$isLoggedIn ? '1' : '0'; ?>">Discover Yourself</div>
    <div class="feature-card <?php if(!$isLoggedIn) echo 'locked'; ?>" data-locked="<?php echo !$isLoggedIn ? '1' : '0'; ?>">Focus Zone</div>
  </div>
</section>

<!-- Footer -->
<footer>
  <p>UniMind © 2025 | All rights reserved</p>
</footer>

<script>
function toggleMenu() {
  document.querySelector('nav ul').classList.toggle('show');
}

// Add click events for locked features
document.querySelectorAll('.feature-card').forEach(card => {
  card.addEventListener('click', () => {
    if (card.dataset.locked === '1') {
      alert("Please log in or sign up to access this feature!");
      return;
    }
    // Future: navigate to feature page if unlocked
  });
});
</script>

</body>
</html>

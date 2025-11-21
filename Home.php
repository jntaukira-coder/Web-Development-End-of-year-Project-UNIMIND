<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="en">
<head> 
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UniMind — Home</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Dancing+Script:wght@500&display=swap" rel="stylesheet">
<style>

/* Reset & base  */
body { margin:0; font-family:'Poppins', sans-serif; background:#021526; color:#fff; line-height:1.6; }
a { text-decoration:none; color:inherit; }

/* Navbar */
nav { width:100%; background:#021526; position:fixed; top:0; left:0; z-index:1000; display:flex; justify-content:space-between; align-items:center; padding:12px 25px; box-shadow:0 2px 5px rgba(0,0,0,0.5); }
nav .logo { font-size:1.6rem; font-weight:700; color:#38bdf8; }
nav ul { list-style:none; display:flex; gap:15px; margin:0 5% 0 0; padding:0; align-items:center; }
nav ul li a:hover { color:#38bdf8; }
.hamburger { display:none; flex-direction:column; gap:5px; cursor:pointer; }
.hamburger div { width:25px; height:3px; background:#fff; }
@media(max-width:768px){
  nav ul{display:none; flex-direction:column; position:absolute; top:50px; right:20px; width:200px; background:#021526; padding:10px; border-radius:8px;}
  nav ul.show{display:flex;}
  .hamburger{display:flex;}
}

/*  Hero Section */
.hero {
  height:90vh;
  background:url('pictures/308172-aestheticwp.jpg') center/cover no-repeat fixed;
  display:flex;
  justify-content:center;
  align-items:center;
  text-align:center;
  position:relative;
}
.hero::before {
  content:"";
  position:absolute;
  top:0;
  left:0;
  width:100%;
  height:100%;
  background:rgba(0,0,0,0.6);
  z-index:0;
}
.hero-content {
  position:relative;
  z-index:1;
  max-width:800px;
  padding:20px;
}
.hero-content h1 {
  font-size:3rem;
  color:#38bdf8;
  margin-bottom:20px;
  text-shadow: 1px 1px 6px #000;
}
.hero-content p {
  font-size:1.2rem;
  color:#fff;
  text-shadow: 1px 1px 5px #000;
}

/* Sections */
section { padding:60px 20px; text-align:center; }
.main-purpose, .additional {
  background:#0f1724;
  margin:30px auto;
  border-radius:12px;
  padding:50px 20px;
  max-width:1100px;
}
.main-purpose h2, .additional h2 {
  color:#38bdf8;
  font-size:2rem;
  margin-bottom:20px;
}
.main-purpose p, .additional p {
  color:#fff;
  font-size:1.1rem;
  max-width:900px;
  margin:auto;
}

/*  Features */
.features {
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
  gap:25px;
  margin-top:30px;
  justify-items:center;
}
.feature-card {
  position:relative;
  background:#021526;
  border-radius:12px;
  overflow:hidden;
  width:100%;
  min-height:180px;
  display:flex;
  justify-content:center;
  align-items:center;
  transition:0.3s;
  cursor:pointer;
  box-shadow:0 4px 15px rgba(0,0,0,0.3);
}
.feature-card img {
  width:100%;
  height:100%;
  object-fit:cover;
  position:absolute;
  top:0;
  left:0;
  z-index:0;
  opacity:0.7;
  transition:0.3s;
}
.feature-card:hover img { opacity:1; transform:scale(1.05); }
.feature-card h3 {
  position:relative;
  z-index:1;
  color:#38bdf8;
  font-size:1.3rem;
  text-align:center;
  text-shadow:1px 1px 5px #000;
}
</style>
</head>
<body>

<!-- Navbar -->
<nav>
  <div class="logo">UNIMIND</div>
  <ul id="navLinks">
    <li><a href="Accomodation.php">Hostels</a></li>
    <li><a href="services.php">Services</a></li>
    <li><a href="campus life.php">Campus Life</a></li>
    <li><a href="register.php">Find a mentor</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
  <div class="hamburger" onclick="toggleMenu()"><div></div><div></div><div></div></div>
</nav>

<!-- Hero Section -->
<section class="hero">
  <div class="hero-content">
    <h1>Welcome to your Student Arrival Toolkit</h1>
    <p>Starting university in a new city can be exciting—but also overwhelming. We provide guidance so you arrive prepared, confident, and ready to focus on your studies.</p>
  </div>
</section>

<!-- Purpose Section -->
<section class="main-purpose">
  <h2>Main Purpose</h2>
  <p>UniMind helps first-year students find safe accommodation, navigate campus, locate nearby services, hospitals, shops, and banks. Everything you need to settle in quickly and safely.</p>
</section>

<!-- More Features -->
<section class="additional">
   <h2>Step Further</h2>
  <p>Find yourself. Master your focus. Make your university journey yours. – Unlock Your Full Potential</p>
  <div class="features">
    <a href="aboutme.php" class="feature-card">
      <img src="pictures/g.jpg" alt="">
      <h3>Discover Yourself</h3>
    </a>
    <a href="focus.php" class="feature-card">
      <img src="pictures/cc4.jpg" alt="">
      <h3>Focus Zone</h3>
    </a>
  </div>
</section>

<footer>UniMind © 2025 | All rights reserved</footer>

<script>
// Toggle mobile menu
function toggleMenu() {
  document.getElementById('navLinks').classList.toggle('show');
}
</script>

</body>
</html>

<?php

require_once 'auth_protect.php';

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>UNIMIND — Campus Life</title>

<style>

body {

  margin:0;

  font-family: Arial, sans-serif;

  color:#f0f0f0;

  background:#0f1724;

}

nav {

  background:#021526;

  display:flex;

  justify-content:space-between;

  align-items:center;

  padding:12px 20px;

  border-radius:0 0 12px 12px;

  box-shadow:0 4px 12px rgba(0,0,0,0.5);

}

nav .logo { color:#38bdf8; font-weight:700; font-size:20px; }

nav .links { display:flex; gap:16px; }

nav .links a { color:#fff; text-decoration:none; font-weight:600; }

nav .links a:hover { color:#38bdf8; }

.container { max-width:800px; margin:32px auto; padding:20px; }

.hero {

  background:#1a2236;

  border-radius:12px;

  padding:30px 20px;

  text-align:center;

  margin-bottom:24px;

  box-shadow:0 6px 20px rgba(0,0,0,0.5);

}

.hero h1 { color:#38bdf8; font-size:28px; margin:0 0 8px; }

.hero p { color:#ddd; font-size:14px; }

.card {

  background:#1a2236;

  padding:18px;

  border-radius:10px;

  margin-bottom:18px;

  box-shadow: 0 6px 18px rgba(0,0,0,0.45);

}

.card h3 { margin:0 0 6px 0; color:#38bdf8; font-size:18px; }

.card ul { list-style:none; padding:0; margin:0; font-size:14px; }

.card ul li { margin-bottom:6px; color:#ddd; }

.card .note { font-size:13px; color:#cbd5e1; margin-top:6px; }

footer { text-align:center; padding:18px 10px; color:#9aa6b2; margin-top:18px; font-size:13px; }

</style>

</head>

<body>



<nav>

  <div class="logo">UNIMIND</div>

  <div class="links">

    <a href="Home.php">Home</a>

    <a href="Accomodation.php">Hostels</a>

    <a href="services.php">Services</a>

    <a href="campus life.php">Campus Life</a>

    <a href="register.php">Find a Mentor</a>

    <li><a href="logout.php">Logout</a></li>

  </div>

</nav>



<div class="container">

  <header class="hero">

    <h1>Campus Life at MUBAS</h1>

    <p>Discover libraries, sports, student unions, cafes, and other activities to make your student life full and exciting.</p>

  </header>



  <!-- Campus Life cards -->

  <article class="card">

    <h3>MUBAS Library</h3>

    <ul>

      <li>Type: Library</li>

      <li>Location: Main campus building</li>

      <li>Hours: 8am–8pm</li>

    </ul>

    <div class="note">Quiet study spaces, books, digital resources, group study rooms.</div>

  </article>



  <article class="card">

    <h3>Sports Complex</h3>

    <ul>

      <li>Type: Sports / Recreation</li>

      <li>Location: West wing of campus</li>

      <li>Facilities: Basketball, volleyball, gym</li>

    </ul>

    <div class="note">Open to all students, schedule your sessions at the front desk.</div>

  </article>



  <article class="card">

    <h3>Student Union / Clubs</h3>

    <ul>

      <li>Type: Student Activities</li>

      <li>Location: East campus, near main hall</li>

    </ul>

    <div class="note">Join academic clubs, tech & innovation groups, and cultural associations.</div>

  </article>



  <article class="card">

    <h3>Campus Cafes</h3>

    <ul>

      <li>Type: Food & Beverages</li>

      <li>Location: Spread across campus</li>

    </ul>

    <div class="note">Affordable meals, coffee, and snacks; social spaces to relax between classes.</div>

  </article>



  <article class="card">

    <h3>MUBAS Health & Wellness Center</h3>

    <ul>

      <li>Type: Health / Clinic</li>

      <li>Location: Near Student Union</li>

      <li>Phone: <span class="phone">+265-884-123-456</span></li>

    </ul>

    <div class="note">Medical consultations, mental health support, student counseling services.</div>

  </article>



  <article class="card">

    <h3>Campus Shuttle & Transport</h3>

    <ul>

      <li>Type: Transportation</li>

      <li>Location: Main gate pickup points</li>

      <li>Services: Shuttle to nearby areas and bus stops</li>

    </ul>

    <div class="note">Timely schedules for students commuting from hostels and neighborhoods.</div>

  </article>



  <footer>

    <p>UniMind © 2025 — All rights reserved</p>

  </footer>

</div>



</body>

</html>


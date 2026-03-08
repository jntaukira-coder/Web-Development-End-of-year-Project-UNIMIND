<?php
$page_title = "Services";
require_once 'functions.php';
secure_session_start();
?>
<?php require_once 'components/header.php'; ?>
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

.container { max-width:850px; margin:32px auto; padding:20px; }

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

.search-bar input {
  width:70%;
  padding:10px 14px;
  border-radius:8px;
  border:none;
  outline:none;
  background:#1a2236;
  color:#fff;
}
.search-bar button {
  background:#38bdf8;
  color:#021526;
  font-weight:700;
  border:none;
  padding:10px 16px;
  border-radius:8px;
  cursor:pointer;
  transition: background 0.3s;
}
.search-bar button:hover { background:#0ea5e9; }

/* Cards */
.card {
  background:#1a2236;
  padding:18px;
  border-radius:10px;
  margin-bottom:18px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.45);
  display:flex;
  align-items:flex-start;
  gap:14px;
}
.icon {
  font-size:28px;
  color:#38bdf8;
  flex-shrink:0;
}
.card h3 { margin:0 0 6px 0; color:#38bdf8; font-size:18px; }
.card ul { list-style:none; padding:0; margin:0; font-size:14px; }
.card ul li { margin-bottom:6px; color:#ddd; }
.phone { margin-top:6px; display:inline-block; background:#021526; padding:6px 10px; border-radius:8px; color:#38bdf8; font-weight:600; font-size:13px; }
.note { font-size:13px; color:#cbd5e1; margin-top:6px; }

/* Button */
.action-btn {
  display:block;
  text-align:center;
  margin:30px auto;
  background:#38bdf8;
  color:#021526;
  font-weight:700;
  padding:12px 24px;
  border:none;
  border-radius:10px;
  cursor:pointer;
  transition: background 0.3s;
}
.action-btn:hover { background:#0ea5e9; }

footer { text-align:center; padding:18px 10px; color:#9aa6b2; margin-top:18px; font-size:13px; }

@media (max-width:600px){
  .card { flex-direction:column; }
  .icon { font-size:24px; }
}
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
    <h1>Find Nearby Services</h1>
    <p>Hospitals, clinics, police, banks, pharmacies, and more — all around Malawi University of Business and Applied Sciences.</p>
  </header>


  <!-- Services -->
  <article class="card">
    <div class="icon">🏥</div>
    <div>
      <h3>Queen Elizabeth Central Hospital</h3>
      <ul>
        <li>Type: Hospital</li>
        <li>Address: Near MUBAS, along Chipatala Avenue</li>
        <li>Phone: <span class="phone">+265-884-112-334</span></li>
      </ul>
      <div class="note">Open 24/7 — emergency, surgery & outpatient services.</div>
    </div>
  </article>

  <article class="card">
    <div class="icon">🚓</div>
    <div>
      <h3>Blantyre Police Station</h3>
      <ul>
        <li>Type: Police Station</li>
        <li>Address: ~1 km from MUBAS main gate</li>
        <li>Phone: <span class="phone">+265-881-223-445</span></li>
      </ul>
      <div class="note">Student-friendly officers — emergency assistance anytime.</div>
    </div>
  </article>

  <article class="card">
    <div class="icon">💊</div>
    <div>
      <h3>Blantyre Pharmacy</h3>
      <ul>
        <li>Type: Pharmacy</li>
        <li>Address: Opposite campus bus stop</li>
        <li>Phone: <span class="phone">+265-882-998-112</span></li>
      </ul>
      <div class="note">Open 8am–8pm — quick prescriptions and first aid supplies.</div>
    </div>
  </article>

  <article class="card">
    <div class="icon">🤝</div>
    <div>
      <h3>Social Services Centre</h3>
      <ul>
        <li>Type: Community Support</li>
        <li>Address: Near Madalitso Market, ~0.8 km from campus</li>
        <li>Phone: <span class="phone">+265-886-445-778</span></li>
      </ul>
      <div class="note">Offers student counselling, welfare assistance, and documentation help.</div>
    </div>
  </article>

  <article class="card">
    <div class="icon">🏦</div>
    <div>
      <h3>National Bank ATM</h3>
      <ul>
        <li>Type: Bank / ATM</li>
        <li>Address: Near MUBAS main gate</li>
        <li>Phone: <span class="phone">+265-887-556-221</span></li>
      </ul>
      <div class="note">24/7 ATM access for cash withdrawals and deposits.</div>
    </div>
  </article>

  <footer>
    <p>UniMind © 2025 — All rights reserved</p>
  </footer>
</div>
</body>
</html>

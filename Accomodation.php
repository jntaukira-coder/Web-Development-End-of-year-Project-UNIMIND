<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UNIMIND — Hostels</title>

<style>
/* Base */
body {
  margin:0;
  font-family: Arial, sans-serif;
  color:#f0f0f0;
  background: url('pictures/students-hostel-bg.jpg') no-repeat center center fixed;
  background-size: cover;
}
body::before {
  content:"";
  position: fixed;
  inset:0;
  background: #021526;
  z-index:-1;
}

/* Navigation */
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
nav .links a { color:#fff; text-decoration:none; font-weight:600; transition: color 0.3s; }
nav .links a:hover { color:#38bdf8; }

/* Container */
.container { max-width:900px; margin:32px auto; padding:20px; }

/* Hero */
.hero {
  position:relative;
  height:42vh;
  min-height:220px;
  display:flex;
  align-items:center;
  justify-content:center;
  text-align:center;
  background: url('pictures/hostel-hero.jpg') center center/cover no-repeat;
  border-radius:12px;
  box-shadow: 0 8px 30px rgba(0,0,0,0.6);
  margin-bottom:26px;
}
.hero::after {
  content:"";
  position:absolute;
  inset:0;
  background:#1a2236;
  border-radius:12px;
}
.hero-inner { position:relative; z-index:1; padding:18px; }
.hero h1 { margin:0; font-size:30px; color:#38bdf8; text-shadow: 1px 1px 6px rgba(0,0,0,0.7); }
.hero p { margin:8px 0 0 0; color:#ddd; font-size:14px; max-width:700px; }

/* Filter Buttons */
.tab-btn {
  background: #1a2236;
  color:#38bdf8;
  border:1px solid rgba(56,189,248,0.3);
  padding:6px 12px;
  border-radius:8px;
  cursor:pointer;
  transition: all 0.2s;
  margin:2px;
}
.tab-btn:hover { background:#38bdf8; color:#1a2236; }

/* Cards */
.card {
  background:#1a2236;
  padding:18px;
  border-radius:10px;
  margin-bottom:18px;
  display:flex;
  gap:14px;
  align-items:flex-start;
  box-shadow: 0 6px 18px rgba(0,0,0,0.45);
  transition: transform 0.2s;
}
.card:hover { transform: translateY(-4px); }
.card img.thumb { width:140px; height:100px; object-fit:cover; border-radius:8px; flex-shrink:0; border: 2px solid rgba(255,255,255,0.04); }
.card .meta { flex:1; }
.card h3 { margin:0 0 6px 0; color:#38bdf8; font-size:18px; }
.card ul { list-style:none; padding:0; margin:0 0 8px 0; font-size:14px; }
.card ul li { margin-bottom:6px; color:#ddd; }
.card .phone { margin-top:6px; display:inline-block; background:#021526; padding:8px 12px; border-radius:8px; color:#38bdf8; font-weight:600; }
.card .directions { margin-top:8px; font-size:13px; color:#cbd5e1; }

/* Map preview */
.map-container {
  position:relative;
  width:40px;
  height:40px;
  cursor:pointer;
  display:inline-block;
  margin-top:8px;
}
.map-container img {
  width:100%;
  height:100%;
  border-radius:50%;
  border: 2px solid #38bdf8;
  transition: transform 0.2s;
}
.map-container:hover img { transform: scale(1.1); }
.map-popup {
  display:none;
  position:absolute;
  top:50px;
  left:0;
  width:300px;
  height:180px;
  z-index:10;
  border-radius:8px;
  overflow:hidden;
  box-shadow: 0 6px 18px rgba(0,0,0,0.5);
}
.map-container:hover .map-popup { display:block; }

/* Badges */
.badges { margin-top:8px; display:flex; gap:8px; flex-wrap:wrap; }
.badge { background: rgba(126, 134, 13, 0.12); color:#38bdf8; padding:6px 8px; border-radius:8px; font-size:13px; border:1px solid rgba(56,189,248,0.08); }

/* Footer */
footer { text-align:center; padding:18px 10px; color:#9aa6b2; margin-top:18px; font-size:13px; }

/* Responsive */
@media (max-width:600px) {
  .card { flex-direction:column; }
  .card img.thumb { width:100%; height:160px; }
  .hero { height:32vh; min-height:180px; }
  .map-popup { width:100%; height:200px; top:45px; left:0; }
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
    <a href="logout.php">Logout</a>
  </div>
</nav>

<div class="container">

<header class="hero">
  <div class="hero-inner">
    <h1>Find Your Perfect Hostel</h1>
    <p>Filter by area, gender, rent, and see mini-maps on hover.</p>
  </div>
</header>

<!-- Filters -->
<div>
  <strong>Filter by Area:</strong>
  <button class="tab-btn" data-filter-type="area" data-filter="all">All</button>
  <button class="tab-btn" data-filter-type="area" data-filter="Chichiri">Chichiri</button>
  <button class="tab-btn" data-filter-type="area" data-filter="Chitawira">Chitawira</button>
  <button class="tab-btn" data-filter-type="area" data-filter="Naperi">Naperi</button>
  <button class="tab-btn" data-filter-type="area" data-filter="Chinyonga">Chinyonga</button>
</div>


<div id="hostels" style="margin-top:20px;">

<!-- 1. Helped Yard -->
<article class="card" data-area="Madala" data-gender="Mixed" data-rent="100000">
  <img class="thumb" src="pictures/HOSTEL4.jpg" alt="Helped Yard Hostels">
  <div class="meta">
    <h3>Helped Yard Hostels</h3>
    <ul>
      <li>Rent: 100,000 MWK / month (120,000 MWK from Jan)</li>
      <li>Cooking: Yes</li>
      <li>Gender: Boys & Girls separate sides</li>
      <li>Phone: <span class="phone">+265‑886‑917‑558</span></li>
    </ul>
    <div class="badges"><span class="badge">Near Madala market</span></div>
    <div class="directions">Head towards Madala market, contact when near.</div>
    <div class="map-container">-
      <img src="pictures/map-icon.png" alt="Map Icon">
      <div class="map-popup">
        <iframe src="https://www.google.com/maps?q=Madala%20Blantyre&output=embed" width="100%" height="100%" style="border:0;"></iframe>
      </div>
    </div>
  </div>
</article>

<!-- 2. Chirwa -->
<article class="card" data-area="Chitawila" data-gender="Girls" data-rent="60000">
  <img class="thumb" src="pictures/HOSTEL2.jpg" alt="Future Hostel 1">
  <div class="meta">
    <h3>Chirwa Hostels</h3>
    <ul>
      <li>Rent: 60,000 MWK / month</li>
      <li>Cooking: Yes</li>
      <li>Gender: Girls only</li>
      <li>Phone: <span class="phone">+265‑892‑412‑703</span></li>
    </ul>
    <div class="badges"><span class="badge">White House area</span></div>
    <div class="directions">Close to Chitawila junction; call before arriving.</div>
    <div class="map-container">
      <img src="pictures/map-icon.png" alt="Map Icon">
      <div class="map-popup">
        <iframe src="https://www.google.com/maps?q=Chitawila%20Blantyre&output=embed" width="100%" height="100%" style="border:0;"></iframe>
      </div>
    </div>
  </div>
</article>

<!-- 3. Mangochi -->
<article class="card" data-area="Chitawila" data-gender="Girls" data-rent="65000">
  <img class="thumb" src="pictures/HOSTEL2.jpg" alt="Future Hostel 1">
  <div class="meta">
    <h3>Mangochi Hostel</h3>
    <ul>
      <li>Rent: 65,000 MWK / month</li>
      <li>Cooking: Yes</li>
      <li>Gender: Girls only</li>
      <li>Phone: <span class="phone">+265‑889‑222‑333</span></li>
    </ul>
    <div class="badges"><span class="badge">Behind bakery, near campus</span></div>
    <div class="directions">Turn left after Kudusa corridor, red gate.</div>
    <div class="map-container">
      <img src="pictures/map-icon.png" alt="Map Icon">
      <div class="map-popup">
        <iframe src="https://www.google.com/maps?q=Chitawila%20Blantyre&output=embed" width="100%" height="100%" style="border:0;"></iframe>
      </div>
    </div>
  </div>
</article>

<!-- 4. Hoho -->
<article class="card" data-area="Chichiri" data-gender="Girls" data-rent="75000">
  <img class="thumb" src="pictures/HOSTEL4.jpg" alt="Future Hostel 1">
  <div class="meta">
    <h3>Hoho Hostels</h3>
    <ul>
      <li>Rent: 75,000 MWK / month</li>
      <li>Cooking: Yes</li>
      <li>Gender: Girls only</li>
      <li>Phone: <span class="phone">+265‑888‑123‑456</span></li>
    </ul>
    <div class="badges"><span class="badge">~2 km from campus</span></div>
    <div class="directions">Located near Chichiri main road.</div>
    <div class="map-container">
      <img src="pictures/map-icon.png" alt="Map Icon">
      <div class="map-popup">
        <iframe src="https://www.google.com/maps?q=Chichiri%20Blantyre&output=embed" width="100%" height="100%" style="border:0;"></iframe>
      </div>
    </div>
  </div>
</article>


<!-- 6. Old Naperi -->
<article class="card" data-area="Naperi" data-gender="Boys" data-rent="90000">
  <img class="thumb" src="pictures/HOSTEL2.jpg" alt="Future Hostel 1">
  <div class="meta">
    <h3>Old Naperi</h3>
    <ul>
      <li>Rent: 90,000 MWK / month</li>
      <li>Cooking: Yes</li>
      <li>Gender: Boys only</li>
      <li>Phone: <span class="phone">+265‑887‑111‑444</span></li>
    </ul>
    <div class="badges"><span class="badge">Blue gate area</span></div>
    <div class="directions">Near Naperi main road.</div>
    <div class="map-container">
      <img src="pictures/map-icon.png" alt="Map Icon">
      <div class="map-popup">
        <iframe src="https://www.google.com/maps?q=Naperi%20Blantyre&output=embed" width="100%" height="100%" style="border:0;"></iframe>
      </div>
    </div>
  </div>
</article>

<!-- 7. Chitawira Hostel -->
<article class="card" data-area="Chitawira" data-gender="Girls" data-rent="70000">
  <img class="thumb" src="pictures/HOSTEL2.jpg" alt="Future Hostel 1">
  <div class="meta">
    <h3>Chitawira Hostel</h3>
    <ul>
      <li>Rent: 70,000 MWK / month</li>
      <li>Cooking: Yes</li>
      <li>Gender: Girls only</li>
      <li>Phone: <span class="phone">+265‑880‑987‑654</span></li>
    </ul>
    <div class="badges"><span class="badge">Maintenance fee 25K</span></div>
    <div class="directions">Chitawira area, landlady notes maintenance fee.</div>
    <div class="map-container">
      <img src="pictures/map-icon.png" alt="Map Icon">
      <div class="map-popup">
        <iframe src="https://www.google.com/maps?q=Chitawira%20Blantyre&output=embed" width="100%" height="100%" style="border:0;"></iframe>
      </div>
    </div>
  </div>
</article>

<!-- 8. Future Hostel 1 -->
<article class="card" data-area="Chinyonga" data-gender="Mixed" data-rent="70000">
  <img class="thumb" src="pictures/HOSTEL1.jpg" alt="Future Hostel 1">
  <div class="meta">
    <h3>Future Hostel 1</h3>
    <ul>
      <li>Rent: 70,000 MWK / month</li>
      <li>Cooking: Yes</li>
      <li>Gender: Mixed</li>
      <li>Phone: <span class="phone">+265‑891‑678‑901</span></li>
    </ul>
    <div class="badges"><span class="badge">Quiet neighborhood</span></div>
    <div class="directions">Near Chinyonga market.</div>
    <div class="map-container">
      <img src="pictures/map-icon.png" alt="Map Icon">
      <div class="map-popup">
        <iframe src="https://www.google.com/maps?q=Chinyonga%20Blantyre&output=embed" width="100%" height="100%" style="border:0;"></iframe>
      </div>
    </div>
  </div>
</article>

<!-- 9. Future Hostel 2 -->
<article class="card" data-area="Chichiri" data-gender="Girls" data-rent="70000">
  <img class="thumb" src="pictures/HOSTEL4.jpg" alt="Future Hostel 1">
  <div class="meta">
    <h3>Future Hostel 2</h3>
    <ul>
      <li>Rent: 70,000 MWK / month</li>
      <li>Cooking: Yes</li>
      <li>Gender: Girls only</li>
      <li>Phone: <span class="phone">+265‑890‑555‑777</span></li>
    </ul>
    <div class="badges"><span class="badge">Near main road</span></div>
    <div class="directions">Safe neighborhood.</div>
    <div class="map-container">
      <img src="pictures/map-icon.png" alt="Map Icon">
      <div class="map-popup">
        <iframe src="https://www.google.com/maps?q=Chichiri%20Blantyre&output=embed" width="100%" height="100%" style="border:0;"></iframe>
      </div>
    </div>
  </div>
</article>

<!-- 10. Boys Hostel 1 -->
<article class="card" data-area="Chichiri" data-gender="Boys" data-rent="65000">
  <img class="thumb" src="pictures/HOSTEL1.jpg" alt="Boys Hostel 1">
  <div class="meta">
    <h3>Boys Hostel 1</h3>
    <ul>
      <li>Rent: 65,000 MWK / month</li>
      <li>Cooking: Yes</li>
      <li>Gender: Boys only</li>
      <li>Phone: <span class="phone">+265‑895‑222‑888</span></li>
    </ul>
    <div class="badges"><span class="badge">Near campus</span></div>
    <div class="directions">Chichiri main road.</div>
    <div class="map-container">
      <img src="pictures/map-icon.png" alt="Map Icon">
      <div class="map-popup">
        <iframe src="https://www.google.com/maps?q=Chichiri%20Blantyre&output=embed" width="100%" height="100%" style="border:0;"></iframe>
      </div>
    </div>
  </div>
</article>

</div>

<footer>
  <p>UniMind © 2025 — All rights reserved</p>
</footer>
</div>

<script>
// Filtering
const buttons = document.querySelectorAll('.tab-btn');
const cards = document.querySelectorAll('.card');

let filters = { area: 'all', };

buttons.forEach(btn => {
  btn.addEventListener('click', () => {
    const type = btn.getAttribute('data-filter-type');
    const value = btn.getAttribute('data-filter');
    filters[type] = value;
    cards.forEach(card => {
      const matchArea = (filters.area === 'all' || card.getAttribute('data-area') === filters.area);
      card.style.display = (matchArea) ? 'flex' : 'none';
    });
  });
});

</script>

</body>
</html>

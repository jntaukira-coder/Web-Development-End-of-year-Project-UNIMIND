<?php
require_once 'functions.php';
secure_session_start();

// Check if user is a landlord
$is_landlord = false;
if (is_logged_in()) {
    $user_id = $_SESSION['user_id'];
    $conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM accommodations WHERE owner_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $is_landlord = $row['count'] > 0;
    $stmt->close();
    
    // Check if user is a company
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM companies WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $is_company = $row['count'] > 0;
    $stmt->close();
    $conn->close();
} else {
    $is_company = false;
}
?>

<nav class="navbar">
  <div class="navbar-content">
    <a href="index.php" class="navbar-brand">UNIMIND</a>
    
    <ul class="navbar-menu" id="navbarMenu">
      <?php if (is_logged_in()): ?>
        <li><a href="index.php" class="navbar-link">Home</a></li>
        <li><a href="Home.php" class="navbar-link">Dashboard</a></li>
        <li><a href="logout.php" class="navbar-link">Logout</a></li>
      <?php else: ?>
        <li><a href="index.php" class="navbar-link">Home</a></li>
        <li><a href="signup_form.php" class="navbar-link">Sign Up</a></li>
        <li><a href="login.php" class="navbar-link">Log In</a></li>
      <?php endif; ?>
    </ul>
    
    <div class="navbar-toggle" id="navbarToggle">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const navbarToggle = document.getElementById('navbarToggle');
  const navbarMenu = document.getElementById('navbarMenu');
  
  navbarToggle.addEventListener('click', function() {
    navbarMenu.classList.toggle('active');
  });
  
  // Close menu when clicking outside
  document.addEventListener('click', function(event) {
    if (!navbarToggle.contains(event.target) && !navbarMenu.contains(event.target)) {
      navbarMenu.classList.remove('active');
    }
  });
  
  // Close menu when clicking on a link (mobile)
  const links = navbarMenu.querySelectorAll('a');
  links.forEach(link => {
    link.addEventListener('click', function() {
      navbarMenu.classList.remove('active');
    });
  });
});
</script>

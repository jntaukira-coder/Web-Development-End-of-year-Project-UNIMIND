<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up - UniMind</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
body { background:#021526; color:white; font-family:Poppins, sans-serif; text-align:center; padding-top:80px; }
form { background:#0f1724; padding:30px; border-radius:12px; width:320px; margin:auto; }
input,button { padding:10px; border:none; border-radius:6px; margin-top:10px; width:90%; }
button { background:#38bdf8; color:#021526; font-weight:700; cursor:pointer; }
a { color:#38bdf8; text-decoration:none; }
</style>
</head>
<body>

<h2>Create Your UniMind Account</h2>
<p>Only for first-year students (verify your reg. number)</p>


    <form id="signupForm" action="signup.php" method="POST">
  <input type="text" name="fullname" placeholder="Full Name" required><br>
  <input type="text" name="regNumber" placeholder="Registration No (eg BECE/25/SS/001)" required><br>
  <input type="text" name="username" placeholder="Username" required><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <button type="submit">Sign Up</button>
</form>

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

    // First-year check
    if (!(year === currentYear || year === currentYear - 1)) {
        alert('Only 1st-year students allowed (current or previous year)!');
        e.preventDefault();
        return;
    }

    // Individual number check
    if (individual < 1 || individual > 999) {
        alert('Individual registration number must be between 1 and 999!');
        e.preventDefault();
        return;
    }
});
</script>



<p style="margin-top:20px;">Already have an account? <a href="login.php">Log In</a></p>

</body>
</html>


<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Find user from DB
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $dbUsername, $dbPassword);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $dbPassword)) {
        // Save username to session
        $_SESSION['user'] = $dbUsername;
        header("Location: Home.php");
        exit;
    } else {
        echo "<script>alert('Invalid username or password'); window.history.back();</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Log In - UniMind</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
body { background:#021526; color:white; font-family:Poppins, sans-serif; text-align:center; padding-top:80px; }
form { background:#0f1724; padding:30px; border-radius:12px; width:320px; margin:auto; }
input, button { padding:10px; border:none; border-radius:6px; margin-top:10px; width:90%; font-size:14px; }
button { background:#38bdf8; color:#021526; font-weight:700; cursor:pointer; transition:0.3s; }
button:hover { background:#0f1724; color:#38bdf8; border:2px solid #38bdf8; }
a { color:#38bdf8; text-decoration:none; }
.error { color: #ff6b6b; margin-top: 10px; }
</style>
</head>
<body>

<h2>Log In to UniMind</h2>

<form action="login.php" method="POST">
  <input type="text" name="username" placeholder="Username" required><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <button type="submit">Log In</button>
</form>

<?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>

<p style="margin-top:20px;">Don't have an account? <a href="signup_form.php">Sign Up</a></p>

</body>
</html>

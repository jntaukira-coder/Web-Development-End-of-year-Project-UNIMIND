<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $program = $_POST['program'];
    $interests = $_POST['interests'];
    $location = $_POST['location'];

    // Try to find the best mentor based on expertise, location, or interest
    $stmt = $conn->prepare("
        SELECT * FROM mentors 
        WHERE expertise LIKE CONCAT('%', ?, '%') 
           OR interests LIKE CONCAT('%', ?, '%') 
           OR location LIKE CONCAT('%', ?, '%') 
        ORDER BY RAND() LIMIT 1
    ");
    $stmt->bind_param("sss", $program, $interests, $location);
    $stmt->execute();
    $mentor = $stmt->get_result()->fetch_assoc();

    if ($mentor) {
        $mentor_id = $mentor['id'];
        $conn->query("INSERT INTO mentees (name, program, interests, location, mentor_id) 
                      VALUES ('$name', '$program', '$interests', '$location', $mentor_id)");
        $assignedMentor = $mentor['name'];
        $mentorPhone = $mentor['phone'];
        $mentorLoc = $mentor['location'];
        $message = "<p style='color:lightgreen;'>You’ve been matched with <strong>$assignedMentor</strong> 
                    ( $mentorLoc, $mentorPhone)</p>";
    } else {
        $message = "<p style='color:#ff6b6b;'>No suitable mentor found right now. Please try again later.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register as Mentee — UniMind</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; background:#021526; color:white; text-align:center; padding:40px; }
form { background:#0f1724; padding:25px; border-radius:10px; display:inline-block; width:320px; }
input, textarea, button, select { padding:10px; margin:10px; border:none; border-radius:5px; width:90%; }
button { background:#38bdf8; color:#021526; font-weight:bold; cursor:pointer; }
button:hover { background:#021526; color:#38bdf8; border:1px solid #38bdf8; }
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


<h1>Register as a Mentee</h1>

<form method="POST">
    <input type="text" name="name" placeholder="Full Name" required><br>
    <input type="text" name="program" placeholder="Program (e.g., Computer Engineering)" required><br>
    <textarea name="interests" placeholder="Your Interests (e.g., AI, Web Dev, Innovation)" required></textarea><br>
    <input type="text" name="location" placeholder="Your Location" required><br>
    <button type="submit">Find a Mentor</button>
</form>

<?php if (!empty($message)) echo $message; ?>

</body>
</html>

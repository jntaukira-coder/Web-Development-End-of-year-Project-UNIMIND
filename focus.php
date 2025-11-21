<?php
include 'db_connect.php';
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure user is logged in 
if (!isset($_SESSION['user'])) {
    die('User not logged in. Please log in to access this page.');
}

// Handle form submission 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_session'])) {
    $subject = $_POST['subject'];
    $duration = (int)$_POST['duration']; 
    $date = date('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO sessions (username, subject, study_date, duration_minutes) VALUES (?, ?, ?, ?)");
    if (!$stmt) { die("Prepare failed: " . $conn->error); }
    $stmt->bind_param("sssi", $_SESSION['user'], $subject, $date, $duration);
    $stmt->execute();
    $stmt->close();

    // Redirect to prevent resubmission
    header("Location: focus.php");
    exit;
}

//Fetch all study dates for streak
$stmt = $conn->prepare("SELECT study_date FROM sessions WHERE username=? ORDER BY study_date DESC");
if (!$stmt) { die("Prepare failed: " . $conn->error); }
$stmt->bind_param("s", $_SESSION['user']);
$stmt->execute();
$stmt->bind_result($study_date);
$dates = [];
while ($stmt->fetch()) { $dates[] = $study_date; }
$stmt->close();

// Calculate streak
$streak = 0;
$day = new DateTime(date('Y-m-d'));
foreach ($dates as $d) {
    if ($day->format('Y-m-d') == $d) {
        $streak++;
        $day->sub(new DateInterval('P1D'));
    } else if ($day->format('Y-m-d') > $d) {
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UNIMIND — Focus Zone</title>
<style>
body { margin:0; font-family: 'Poppins', Arial, sans-serif; background:#0f1724; color:#f0f0f0; overflow-x:hidden; position:relative; }
body::before { content:""; position:fixed; inset:0; background: linear-gradient(180deg, rgba(0,50,100,0.6), rgba(0,30,60,0.8)); z-index:-1; }
.navbar { position:fixed; top:0; left:0; right:0; background:#021526; display:flex; justify-content:space-between; align-items:center; padding:15px 40px; box-shadow:0 4px 10px rgba(0,0,0,0.5); z-index:1000; border-radius:0 0 12px 12px; }
.navbar .logo { color:#38bdf8; font-size:1.5em; font-weight:bold; letter-spacing:1px; }
.navbar ul { list-style:none; margin:0; padding:0; display:flex; gap:25px; }
.navbar a { color:#f0f0f0; text-decoration:none; font-weight:500; transition:color 0.3s; }
.navbar a:hover { color:#56ccf2; }
.container { max-width:800px; margin:120px auto 40px; padding:20px; }
.card { background:#1a2236; padding:30px; border-radius:12px; margin-bottom:20px; box-shadow:0 0 25px rgba(0,0,0,0.5); transition:0.3s; }
.card:hover { transform:scale(1.02); box-shadow:0 0 35px rgba(56,189,248,0.7); }
h1,h2 { color:#38bdf8; margin-top:0; }
.card:first-of-type { min-height:350px; }
.time { font-size:80px; text-align:center; margin:20px 0; font-weight:bold; color:#fff; transition:1s; }
button { margin:5px; padding:10px 15px; border:none; border-radius:6px; cursor:pointer; background:#38bdf8; color:#021526; font-weight:bold; transition:0.3s; }
button:hover { background:#56ccf2; transform:scale(1.05); }
input[type=text], select { width:100%; padding:10px; margin:5px 0; border-radius:6px; border:1px solid #333; background:#0f1724; color:#fff; }
footer { text-align:center; color:#aaa; margin-top:30px; font-size:0.9em; }
.embed-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius:12px; box-shadow:0 6px 25px rgba(0,0,0,0.45); margin-bottom:20px; }
.embed-container iframe { position: absolute; top:0; left:0; width:100%; height:100%; border:0; }
/* Notification style */
#notification { color:#38bdf8; font-weight:bold; margin-top:10px; display:none; text-align:center; }
</style>
</head>
<body>

<nav class="navbar">
  <div class="logo">UNIMIND</div>
  <ul>
     <a href="Home.php">Home</a>
    <a href="aboutme.php">Discover Yourself</a>
    <a href="focus.php" style="color:#38bdf8;">Focus Zone</a>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</nav>

<div class="container">

<!-- FORM for saving sessions -->
<form method="POST" id="sessionForm">
  <input type="hidden" name="subject" id="formSubject">
  <input type="hidden" name="duration" id="formDuration">
  <input type="hidden" name="save_session" value="1">
</form>

<!-- Focus Timer Card -->
<div class="card">
  <h1>Focus Timer</h1>
  <input type="text" id="task" placeholder="What are you focusing on?">
  <select id="duration">
    <option value="25">25 min</option>
    <option value="45">45 min</option>
    <option value="90">90 min</option>
  </select>
  <div class="time" id="display">25:00</div>
  <div>
    <button type="button" onclick="startTimer()">Start</button>
    <button type="button" onclick="pauseTimer()">Pause</button>
    <button type="button" onclick="resetTimer()">Reset</button>
    <button type="button" onclick="saveSession()">Save Session</button>
  </div>
  <div id="notification">Session saved!</div>
  <div class="stat">Streak: <span id="streak"><?php echo $streak; ?></span> days</div>
</div>

<!-- Study Music Card -->
<div class="card music">
  <h2>Study Music</h2>
  <div class="embed-container">
    <iframe src="https://open.spotify.com/embed/playlist/37i9dQZF1DX8NTLI2TtZa6" allow="encrypted-media"></iframe>
  </div>
</div>

<!-- Healthy Tips Card -->
<div class="card tips">
  <h2>Healthy Break Tips</h2>
  <ul>
    <li>Take a short walk every hour</li>
    <li>Drink water frequently</li>
    <li>Do stretching exercises</li>
    <li>Listen to calming music</li>
  </ul>
</div>

<footer>UniMind © 2025 | Focus Zone</footer>
</div>

<script>
// Timer
let timer = null;
let seconds = parseInt(document.getElementById('duration').value) * 60;
const notification = document.getElementById('notification');

// Format time as MM:SS
function formatTime(s) {
    let m = Math.floor(s / 60).toString().padStart(2,'0');
    let sec = (s % 60).toString().padStart(2,'0');
    return m + ':' + sec;
}

// Show subtle notification
function showNotification(message) {
    notification.innerText = message;
    notification.style.display = 'block';
    setTimeout(() => { notification.style.display = 'none'; }, 3000);
}

// Start timer
function startTimer() {
    if(timer) return;
    timer = setInterval(() => {
        seconds--;
        document.getElementById('display').innerText = formatTime(seconds);

        if(seconds <= 0) {
            clearInterval(timer);
            timer = null;
            seconds = parseInt(document.getElementById('duration').value) * 60;
            document.getElementById('display').innerText = formatTime(seconds);
            saveSession(true); 
        }
    }, 1000);
}

// Pause timer
function pauseTimer() {
    clearInterval(timer);
    timer = null;
}

// Reset timer
function resetTimer() {
    clearInterval(timer);
    timer = null;
    seconds = parseInt(document.getElementById('duration').value) * 60;
    document.getElementById('display').innerText = formatTime(seconds);
}

// Save session
function saveSession(autoSaved = false) {
    const taskInput = document.getElementById('task').value || 'No Task';
    document.getElementById('formSubject').value = taskInput;
    document.getElementById('formDuration').value = parseInt(document.getElementById('duration').value);
    document.getElementById('sessionForm').submit();

    if(autoSaved) {
        showNotification("Session auto-saved!");
    } else {
        showNotification("Session saved!");
    }
}

// Update timer if user changes duration
document.getElementById('duration').addEventListener('change', () => {
    seconds = parseInt(document.getElementById('duration').value) * 60;
    document.getElementById('display').innerText = formatTime(seconds);
});
</script>

</body>
</html>

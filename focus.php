<?php
include 'db.php'; // Use the same database connection
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure user is logged in 
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page
    header("Location: login.php");
    exit();
}

// Get user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$user) {
    header("Location: login.php");
    exit();
}

// Handle form submission 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_session'])) {
    $subject = $_POST['subject'];
    $duration = (int)$_POST['duration']; 
    $date = date('Y-m-d');

    $stmt = $pdo->prepare("INSERT INTO sessions (username, subject, study_date, duration_minutes) VALUES (?, ?, ?, ?)");
    if (!$stmt) { die("Prepare failed: " . $pdo->error); }
    $stmt->execute([$user['email'], $subject, $date, $duration]);

    // Redirect to prevent resubmission
    header("Location: focus.php");
    exit;
}

//Fetch all study dates for streak
$stmt = $pdo->prepare("SELECT study_date FROM sessions WHERE username=? ORDER BY study_date DESC");
if (!$stmt) { die("Prepare failed: " . $pdo->error); }
$stmt->execute([$user['email']]);
$dates = $stmt->fetchAll(PDO::FETCH_COLUMN);

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
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://in.paychangu.com/js/popup.js"></script>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background: #0f0f20;
    color: #ffffff;
    line-height: 1.6;
    scroll-behavior: smooth;
}

.app-container {
    display: flex;
    min-height: 100vh;
}

/* Sidebar */
.sidebar {
    width: 280px;
    background: linear-gradient(180deg, #1a0a2e 0%, #2a0a4e 100%);
    color: white;
    padding: 2rem 0;
    position: fixed;
    height: 100vh;
    overflow-y: auto;
    z-index: 1000;
}

.logo-section {
    padding: 0 2rem 2rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    margin-bottom: 2rem;
}

.logo {
    font-size: 1.5rem;
    font-weight: 700;
    color: #00d4ff;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.user-profile {
    padding: 0 2rem 2rem;
    margin-bottom: 2rem;
}

.user-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #00d4ff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 1.125rem;
}

.user-info {
    margin-top: 1rem;
}

.user-name {
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 0.25rem;
}

.user-status {
    font-size: 0.875rem;
    color: #e0e0ff;
    background: rgba(0, 212, 255, 0.1);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    display: inline-block;
}

.sidebar-nav {
    padding: 0 1rem;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    color: #e0e0ff;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.2s ease;
    margin-bottom: 0.5rem;
}

.nav-item:hover {
    background: rgba(0, 212, 255, 0.1);
    color: white;
}

.nav-item.active {
    background: #00d4ff;
    color: white;
}

.nav-item i {
    width: 20px;
    text-align: center;
}

/* Main Content */
.main-content {
    flex: 1;
    margin-left: 280px;
    padding: 2rem;
    background: #0f0f20;
}

.container {
    max-width: 800px;
    margin: 0 auto;
}

.card {
    background: linear-gradient(135deg, #1a0a2e, #2a0a4e);
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 0 25px rgba(0, 0, 0, 0.5);
    transition: 0.3s;
    border: 1px solid #4a00ff;
}

.card:hover {
    transform: scale(1.02);
    box-shadow: 0 0 35px rgba(56, 189, 248, 0.7);
}

h1, h2 {
    color: #00d4ff;
    margin-top: 0;
}

.card:first-of-type {
    min-height: 350px;
}

.time {
    font-size: 80px;
    text-align: center;
    margin: 20px 0;
    font-weight: bold;
    color: #fff;
    transition: 1s;
}

button {
    margin: 5px;
    padding: 10px 15px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    background: #00d4ff;
    color: #021526;
    font-weight: bold;
    transition: 0.3s;
}

button:hover {
    background: #00a8cc;
    transform: scale(1.05);
}

input[type=text], select {
    width: 100%;
    padding: 10px;
    margin: 5px 0;
    border-radius: 6px;
    border: 1px solid #333;
    background: #0f1724;
    color: #fff;
}

.embed-container {
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
    overflow: hidden;
    border-radius: 12px;
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.45);
    margin-bottom: 20px;
}

.embed-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 0;
}

/* Focus Modes */
.focus-modes {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.mode-btn {
    background: linear-gradient(135deg, #1a0a2e, #2a0a4e);
    border: 1px solid #4a00ff;
    color: #ffffff;
    padding: 1.5rem;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    font-weight: 600;
}

.mode-btn:hover {
    background: rgba(0, 212, 255, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 212, 255, 0.4);
}

.mode-btn.active {
    background: #00d4ff;
    color: #021526;
}

.mode-btn i {
    display: block;
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.mode-btn small {
    display: block;
    font-size: 0.75rem;
    opacity: 0.8;
    margin-top: 0.25rem;
}

/* Session Goal */
.goal-status {
    margin-top: 1rem;
    padding: 1rem;
    background: rgba(0, 212, 255, 0.1);
    border-radius: 8px;
    border: 1px solid #4a00ff;
}

.goal-buttons {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}

.complete-btn {
    background: #00ff88;
    color: #021526;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
}

.incomplete-btn {
    background: #ff8800;
    color: #ffffff;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
}

/* Timer Display */
.timer-display {
    text-align: center;
    margin-bottom: 2rem;
}

.session-info {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-top: 1rem;
    font-size: 0.875rem;
    color: #e0e0ff;
}

.timer-controls {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-top: 2rem;
}

.timer-controls button {
    padding: 1rem;
    font-size: 0.875rem;
}

.focus-reminder {
    background: rgba(255, 136, 0, 0.1);
    border: 1px solid #ff8800;
    padding: 1rem;
    border-radius: 8px;
    text-align: center;
    margin-top: 1.5rem;
    color: #ff8800;
    font-weight: 500;
}

.stats-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-top: 2rem;
}

.stat-card {
    background: linear-gradient(135deg, #1a0a2e, #2a0a4e);
    padding: 1.5rem;
    border-radius: 12px;
    text-align: center;
    border: 1px solid #4a00ff;
}

.stat-card h3 {
    color: #00d4ff;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.75rem;
    color: #e0e0ff;
    opacity: 0.8;
}

/* Progress Ring */
.progress-container {
    display: flex;
    align-items: center;
    gap: 2rem;
    margin-bottom: 2rem;
}

.progress-ring {
    position: relative;
    width: 200px;
    height: 200px;
}

.progress-svg {
    transform: rotate(-90deg);
    width: 100%;
    height: 100%;
}

.progress-bg {
    fill: none;
    stroke: rgba(0, 212, 255, 0.1);
    stroke-width: 8;
}

.progress-bar {
    fill: none;
    stroke: #00d4ff;
    stroke-width: 8;
    stroke-linecap: round;
    transition: stroke-dashoffset 0.5s ease;
}

.progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.progress-percentage {
    font-size: 1.5rem;
    font-weight: 700;
    color: #00d4ff;
    display: block;
    text-shadow: 0 0 20px rgba(0, 212, 255, 0.8);
}

.progress-label {
    font-size: 0.875rem;
    color: #e0e0ff;
    margin-top: 0.25rem;
}

.progress-stats {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.mini-stat {
    text-align: center;
    padding: 1rem;
    background: rgba(0, 212, 255, 0.1);
    border-radius: 8px;
    border: 1px solid #4a00ff;
}

.mini-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.25rem;
}

.mini-label {
    font-size: 0.75rem;
    color: #e0e0ff;
    opacity: 0.8;
}

/* Full Screen Progress Overlay */
.progress-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    display: none;
    z-index: 9999;
    backdrop-filter: blur(2px);
}

.progress-overlay.active {
    display: flex;
    align-items: center;
    justify-content: center;
}

.overlay-progress {
    width: 300px;
    height: 300px;
    position: relative;
}

.overlay-percentage {
    font-size: 4rem;
    font-weight: 700;
    color: #00d4ff;
    text-align: center;
    margin-bottom: 1rem;
    text-shadow: 0 0 30px rgba(0, 212, 255, 0.8);
}

.overlay-label {
    font-size: 1.25rem;
    color: #ffffff;
    text-align: center;
    opacity: 0.9;
}

.overlay-timer {
    font-size: 2rem;
    color: #e0e0ff;
    text-align: center;
    font-weight: 300;
}

/* Distraction Tracker */
.distraction-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.distraction-count, .focus-score {
    text-align: center;
    padding: 1.5rem;
    background: linear-gradient(135deg, #1a0a2e, #2a0a4e);
    border-radius: 12px;
    border: 1px solid #4a00ff;
}

.distraction-number, .score-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.distraction-number {
    color: #ff8800;
}

.score-number {
    color: #00ff88;
}

.distraction-label, .score-label {
    font-size: 0.875rem;
    color: #e0e0ff;
    opacity: 0.8;
}

.distraction-tips {
    background: rgba(255, 136, 0, 0.1);
    border: 1px solid #ff8800;
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #ff8800;
    font-size: 0.875rem;
}

/* Smart Study Suggestions */
.suggestion-content {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #1a0a2e, #2a0a4e);
    border-radius: 12px;
    border: 1px solid #4a00ff;
}

.suggestion-icon {
    font-size: 2rem;
    color: #00d4ff;
    text-align: center;
    min-width: 3rem;
}

.suggestion-text h3 {
    color: #00d4ff;
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.suggestion-text p {
    color: #e0e0ff;
    font-size: 0.875rem;
    line-height: 1.4;
}

.study-patterns {
    display: grid;
    gap: 1rem;
}

.pattern-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: rgba(0, 212, 255, 0.1);
    border-radius: 8px;
    border: 1px solid #4a00ff;
    font-size: 0.875rem;
    color: #e0e0ff;
}

.pattern-item i {
    color: #00d4ff;
    min-width: 1.25rem;
}

.pattern-item strong {
    color: #ffffff;
    font-weight: 600;
}

/* Session Report */
.session-report {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: linear-gradient(135deg, #1a0a2e, #2a0a4e);
    border: 2px solid #00d4ff;
    border-radius: 16px;
    padding: 2rem;
    z-index: 10000;
    min-width: 300px;
    box-shadow: 0 0 30px rgba(0, 212, 255, 0.5);
    animation: slideIn 0.3s ease-out;
}

.session-report h3 {
    color: #00d4ff;
    text-align: center;
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
}

.report-stats {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.report-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    background: rgba(0, 212, 255, 0.1);
    border-radius: 8px;
    border: 1px solid #4a00ff;
}

.report-label {
    color: #e0e0ff;
    font-size: 0.875rem;
}

.report-value {
    color: #ffffff;
    font-weight: 600;
    font-size: 1rem;
}

.report-close {
    width: 100%;
    background: #00d4ff;
    color: #021526;
    border: none;
    border-radius: 8px;
    padding: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.report-close:hover {
    background: #00a8cc;
    transform: translateY(-2px);
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translate(-50%, -60%);
    }
    to {
        opacity: 1;
        transform: translate(-50%, -50%);
    }
}

.target-display {
    text-align: center;
    margin-bottom: 1.5rem;
}

.target-label {
    font-size: 0.875rem;
    color: #e0e0ff;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.target-value {
    font-size: 2.5rem;
    font-weight: 700;
    color: #00d4ff;
    margin-bottom: 0.5rem;
}

.target-progress {
    background: rgba(0, 212, 255, 0.1);
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.target-bar {
    background: rgba(0, 212, 255, 0.2);
    border-radius: 8px;
    height: 12px;
    overflow: hidden;
}

.target-fill {
    background: linear-gradient(90deg, #00d4ff, #00ff88);
    height: 100%;
    border-radius: 8px;
    transition: width 0.5s ease;
}

.target-status {
    text-align: center;
    font-size: 0.875rem;
    color: #e0e0ff;
    font-weight: 500;
}
</style>
</head>
<body>

<div class="app-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo-section">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                UNIMIND
            </div>
        </div>
        
        <div class="user-profile">
            <div class="user-avatar">
                <?php echo isset($user['fullname']) ? substr($user['fullname'], 0, 1) : 'U'; ?>
            </div>
            <div class="user-info">
                <div class="user-name"><?php echo isset($user['fullname']) ? htmlspecialchars($user['fullname']) : 'Student'; ?></div>
                <div class="user-status">Focus Mode</div>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <a href="Home.php" class="nav-item">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
            <a href="focus.php" class="nav-item active">
                <i class="fas fa-brain"></i>
                Focus Zone
            </a>
            <a href="aboutme.php" class="nav-item">
                <i class="fas fa-user"></i>
                Discover Yourself
            </a>
            <a href="campus life.php" class="nav-item">
                <i class="fas fa-map-marked-alt"></i>
                Campus Navigation
            </a>
            <a href="login.php" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">

<!-- FORM for saving sessions -->
<form method="POST" id="sessionForm">
  <input type="hidden" name="subject" id="formSubject">
  <input type="hidden" name="duration" id="formDuration">
  <input type="hidden" name="save_session" value="1">
</form>

<!-- Focus Modes Card -->
<div class="card">
    <h2>Choose Focus Mode</h2>
    <div class="focus-modes">
        <button type="button" class="mode-btn" onclick="setFocusMode('deep')" data-mode="deep">
            <i class="fas fa-brain"></i>
            Deep Study
            <small>50 min focus / 10 min break</small>
        </button>
        <button type="button" class="mode-btn" onclick="setFocusMode('pomodoro')" data-mode="pomodoro">
            <i class="fas fa-clock"></i>
            Quick Revision
            <small>25 min / 5 min break</small>
        </button>
        <button type="button" class="mode-btn" onclick="setFocusMode('assignment')" data-mode="assignment">
            <i class="fas fa-pen"></i>
            Assignment Mode
            <small>90 min / 15 min break</small>
        </button>
        <button type="button" class="mode-btn" onclick="setFocusMode('exam')" data-mode="exam">
            <i class="fas fa-graduation-cap"></i>
            Exam Prep
            <small>60 min / 10 min break</small>
        </button>
    </div>
</div>

<!-- Session Goal Card -->
<div class="card">
    <h2>Session Goal</h2>
    <input type="text" id="sessionGoal" placeholder="What will you complete in this session?" maxlength="100">
    <div class="goal-status" id="goalStatus">
        <span id="goalText">Set your goal above</span>
        <div class="goal-buttons" id="goalButtons" style="display: none;">
            <button type="button" onclick="markGoalComplete(true)" class="complete-btn">
                <i class="fas fa-check"></i> Yes, Completed!
            </button>
            <button type="button" onclick="markGoalComplete(false)" class="incomplete-btn">
                <i class="fas fa-times"></i> Not Yet
            </button>
        </div>
    </div>
</div>

<!-- Focus Timer Card -->
<div class="card">
    <h1>Focus Timer</h1>
    <div class="timer-display">
        <div class="time" id="display">25:00</div>
        <div class="session-info">
            <span id="currentMode">Quick Revision</span>
            <span id="sessionCount">Session 1</span>
        </div>
    </div>
    <div class="timer-controls">
        <button type="button" id="startBtn" onclick="startTimer()">
            <i class="fas fa-play"></i> Start
        </button>
        <button type="button" id="pauseBtn" onclick="pauseTimer()">
            <i class="fas fa-pause"></i> Pause
        </button>
        <button type="button" onclick="resetTimer()">
            <i class="fas fa-redo"></i> Reset
        </button>
        <button type="button" onclick="saveSession()">
            <i class="fas fa-save"></i> Save Session
        </button>
    </div>
    <div class="focus-reminder" id="focusReminder">
        <i class="fas fa-bell"></i>
        <span>Focus Zone Active - Put your phone away and close social media</span>
    </div>
    <div id="notification">Session saved!</div>
    <div class="stats-row">
        <div class="stat-card">
            <h3>Focus Streak</h3>
            <div class="stat-value" id="streak"><?php echo $streak; ?></div>
            <div class="stat-label">days</div>
        </div>
        <div class="stat-card">
            <h3>Today's Focus</h3>
            <div class="stat-value" id="todayFocus">0h 0m</div>
            <div class="stat-label">total time</div>
        </div>
    </div>
</div>

<!-- Distraction Tracker Card -->
<div class="card">
    <h2>Distraction Tracker</h2>
    <div class="distraction-stats">
        <div class="distraction-count">
            <div class="distraction-number" id="distractionCount">0</div>
            <div class="distraction-label">Distractions</div>
        </div>
        <div class="focus-score">
            <div class="score-number" id="focusScore">100%</div>
            <div class="score-label">Focus Score</div>
        </div>
    </div>
    <div class="distraction-tips">
        <i class="fas fa-info-circle"></i>
        <span>Stay focused! Switching tabs or minimizing window counts as distraction.</span>
    </div>
</div>

<!-- Smart Study Suggestions Card -->
<div class="card">
    <h2>Smart Study Suggestions</h2>
    <div class="suggestion-content" id="suggestionContent">
        <div class="suggestion-icon">
            <i class="fas fa-lightbulb"></i>
        </div>
        <div class="suggestion-text">
            <h3>Personalized Recommendation</h3>
            <p id="suggestionText">Your best focus sessions are 25 minutes. Try Quick Revision mode!</p>
        </div>
    </div>
    <div class="study-patterns">
        <div class="pattern-item">
            <i class="fas fa-clock"></i>
            <span>Best time: <strong id="bestTime">Evening</strong></span>
        </div>
        <div class="pattern-item">
            <i class="fas fa-chart-line"></i>
            <span>Optimal duration: <strong id="optimalDuration">25 min</strong></span>
        </div>
        <div class="pattern-item">
            <i class="fas fa-fire"></i>
            <span>Peak day: <strong id="peakDay">Monday</strong></span>
        </div>
    </div>
</div>

<!-- Daily Target Card -->
<div class="card">
    <h2>Daily Focus Target</h2>
    <div class="target-container">
        <div class="target-display">
            <div class="target-label">Today's Goal</div>
            <div class="target-value">
                <span id="targetHours">2</span>h <span id="targetMinutes">0</span>m
            </div>
        </div>
        <div class="target-progress">
            <div class="target-bar">
                <div class="target-fill" id="targetFill" style="width: 0%"></div>
            </div>
            <div class="target-status" id="targetStatus">0h 0m completed</div>
        </div>
    </div>
</div>

<!-- Full Screen Progress Overlay -->
<div class="progress-overlay" id="progressOverlay">
    <div class="overlay-content">
        <div class="overlay-progress">
            <svg class="overlay-svg" width="120" height="120">
                <circle class="progress-bg" cx="60" cy="60" r="54"></circle>
                <circle class="progress-bar" id="overlayProgressRing" cx="60" cy="60" r="54"></circle>
            </svg>
            <div class="overlay-percentage" id="overlayPercentage">0%</div>
        </div>
        <div class="overlay-label">Focus Time</div>
        <div class="overlay-timer" id="overlayTimer">25:00</div>
    </div>
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
    <li>Take breaks to meditate</li>
    <li>Avoid screens before bedtime</li>
  </ul>
</div>

<footer>UniMind &copy; 2025 | Focus Zone</footer>
</div>

<script>
// Focus modes configuration
const focusModes = {
    deep: { duration: 50, breakTime: 10, name: 'Deep Study' },
    pomodoro: { duration: 25, breakTime: 5, name: 'Quick Revision' },
    assignment: { duration: 90, breakTime: 15, name: 'Assignment Mode' },
    exam: { duration: 60, breakTime: 10, name: 'Exam Prep' }
};

let currentMode = 'pomodoro';
let timer = null;
let seconds = 25 * 60; // Default to pomodoro
let sessionCount = 1;
let todayFocusTime = 0;
let goalCompleted = false;
let distractionCount = 0;
let sessionStartTime = null;
let lastActiveTime = null;
const notification = document.getElementById('notification');

// Initialize distraction tracking
document.addEventListener('DOMContentLoaded', function() {
    setFocusMode('pomodoro'); // Set default mode
    updateTodayStats();
    initializeSmartSuggestions();
    setupDistractionTracking();
});

// Setup distraction tracking
function setupDistractionTracking() {
    // Track page visibility changes
    document.addEventListener('visibilitychange', function() {
        if (timer && document.hidden) {
            distractionCount++;
            updateDistractionTracker();
        }
    });
    
    // Track window focus/blur
    window.addEventListener('blur', function() {
        if (timer) {
            distractionCount++;
            updateDistractionTracker();
        }
    });
    
    // Track mouse inactivity
    let inactivityTimer;
    document.addEventListener('mousemove', function() {
        clearTimeout(inactivityTimer);
        inactivityTimer = setTimeout(() => {
            if (timer) {
                distractionCount++;
                updateDistractionTracker();
            }
        }, 30000); // 30 seconds of inactivity
    });
}

// Update distraction tracker
function updateDistractionTracker() {
    document.getElementById('distractionCount').textContent = distractionCount;
    
    // Calculate focus score
    const totalMinutes = (Date.now() - sessionStartTime) / 60000;
    const focusScore = Math.max(0, 100 - (distractionCount * 5));
    document.getElementById('focusScore').textContent = focusScore + '%';
}

// Initialize smart suggestions
function initializeSmartSuggestions() {
    // Simulate smart suggestions based on user patterns
    const hour = new Date().getHours();
    const day = new Date().getDay();
    
    // Time-based suggestions
    if (hour >= 20 || hour < 6) {
        document.getElementById('suggestionText').textContent = 
            "You study best at night! Try a 50-minute Deep Study session.";
        document.getElementById('bestTime').textContent = "Night";
    } else if (hour >= 14 && hour < 17) {
        document.getElementById('suggestionText').textContent = 
            "Afternoon focus is strong! Perfect for assignment work.";
        document.getElementById('bestTime').textContent = "Afternoon";
    } else {
        document.getElementById('suggestionText').textContent = 
            "Your best focus sessions are 25 minutes. Try Quick Revision mode!";
        document.getElementById('bestTime').textContent = "Evening";
    }
    
    // Day-based patterns
    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    document.getElementById('peakDay').textContent = days[day];
    
    // Optimal duration based on past sessions
    document.getElementById('optimalDuration').textContent = "25 min";
}

// Set focus mode
function setFocusMode(mode) {
    currentMode = mode;
    const modeConfig = focusModes[mode];
    
    // Update UI
    document.getElementById('currentMode').textContent = modeConfig.name;
    document.getElementById('display').textContent = formatTime(modeConfig.duration * 60);
    seconds = modeConfig.duration * 60;
    
    // Update button states
    document.querySelectorAll('.mode-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.mode === mode) {
            btn.classList.add('active');
        }
    });
    
    // Reset timer if running
    if (timer) {
        pauseTimer();
    }
}

// Format time as MM:SS
function formatTime(s) {
    let m = Math.floor(s / 60).toString().padStart(2, '0');
    let sec = (s % 60).toString().padStart(2, '0');
    return m + ':' + sec;
}

// Show notification
function showNotification(message) {
    notification.textContent = message;
    notification.style.display = 'block';
    setTimeout(() => { notification.style.display = 'none'; }, 3000);
}

// Start timer
function startTimer() {
    if (timer) return;
    
    // Reset distraction count for new session
    distractionCount = 0;
    sessionStartTime = Date.now();
    updateDistractionTracker();
    
    // Show full-screen progress overlay
    document.getElementById('progressOverlay').classList.add('active');
    
    // Show goal buttons
    const goalText = document.getElementById('sessionGoal').value;
    if (goalText) {
        document.getElementById('goalButtons').style.display = 'flex';
        document.getElementById('goalText').textContent = 'Working on: ' + goalText;
    }
    
    // Show focus reminder
    document.getElementById('focusReminder').style.display = 'block';
    
    timer = setInterval(() => {
        seconds--;
        document.getElementById('display').textContent = formatTime(seconds);
        todayFocusTime++; // Track today's focus time
        updateProgressRing(); // Update full-screen progress

        if (seconds <= 0) {
            clearInterval(timer);
            timer = null;
            
            // Show session completion report
            showSessionReport();
            
            // Auto-save session
            saveSession(true);
            
            // Show goal completion
            if (goalText) {
                document.getElementById('goalButtons').style.display = 'flex';
            }
        }
    }, 1000);
    
    // Update button states
    document.getElementById('startBtn').style.display = 'none';
    document.getElementById('pauseBtn').style.display = 'inline-block';
}

// Show session completion report
function showSessionReport() {
    const sessionDuration = Math.floor((Date.now() - sessionStartTime) / 60000);
    const focusScore = Math.max(0, 100 - (distractionCount * 5));
    
    // Create and show report
    const report = document.createElement('div');
    report.className = 'session-report';
    report.innerHTML = `
        <h3>🎯 Session Complete!</h3>
        <div class="report-stats">
            <div class="report-item">
                <span class="report-label">Session Duration:</span>
                <span class="report-value">${sessionDuration} min</span>
            </div>
            <div class="report-item">
                <span class="report-label">Distractions:</span>
                <span class="report-value">${distractionCount}</span>
            </div>
            <div class="report-item">
                <span class="report-label">Focus Score:</span>
                <span class="report-value">${focusScore}%</span>
            </div>
        </div>
        <button onclick="this.parentElement.remove()" class="report-close">Continue</button>
    `;
    
    document.body.appendChild(report);
    setTimeout(() => report.remove(), 5000);
}

// Pause timer
function pauseTimer() {
    if (!timer) return;
    
    clearInterval(timer);
    timer = null;
    
    // Hide full-screen progress overlay
    document.getElementById('progressOverlay').classList.remove('active');
    
    // Update button states
    document.getElementById('startBtn').style.display = 'inline-block';
    document.getElementById('pauseBtn').style.display = 'none';
}

// Reset timer
function resetTimer() {
    clearInterval(timer);
    timer = null;
    
    // Hide full-screen progress overlay
    document.getElementById('progressOverlay').classList.remove('active');
    
    const modeConfig = focusModes[currentMode];
    seconds = modeConfig.duration * 60;
    document.getElementById('display').textContent = formatTime(seconds);
    
    // Update button states
    document.getElementById('startBtn').style.display = 'inline-block';
    document.getElementById('pauseBtn').style.display = 'none';
    
    // Reset goal
    document.getElementById('goalButtons').style.display = 'none';
    document.getElementById('goalText').textContent = 'Set your goal above';
}

// Mark goal completion
function markGoalComplete(completed) {
    goalCompleted = completed;
    const statusText = completed ? ' Goal Completed!' : ' Still working on it...';
    document.getElementById('goalText').textContent = statusText;
    
    // Hide buttons after marking
    setTimeout(() => {
        document.getElementById('goalButtons').style.display = 'none';
    }, 2000);
}

// Save session
function saveSession(autoSaved = false) {
    const goalText = document.getElementById('sessionGoal').value || 'No specific goal';
    const modeConfig = focusModes[currentMode];
    
    // Hide full-screen progress overlay
    document.getElementById('progressOverlay').classList.remove('active');
    
    // Update session count
    sessionCount++;
    document.getElementById('sessionCount').textContent = 'Session ' + sessionCount;
    
    // Update today's stats
    updateTodayStats();
    
    // Prepare form data
    document.getElementById('formSubject').value = `${modeConfig.name}: ${goalText}`;
    document.getElementById('formDuration').value = modeConfig.duration;
    document.getElementById('sessionForm').submit();
    
    // Show notification
    const message = autoSaved ? 'Session auto-saved!' : 'Session saved!';
    showNotification(message);
    
    // Hide focus reminder
    document.getElementById('focusReminder').style.display = 'none';
}

// Update today's focus statistics
function updateTodayStats() {
    const hours = Math.floor(todayFocusTime / 3600);
    const minutes = Math.floor((todayFocusTime % 3600) / 60);
    document.getElementById('todayFocus').textContent = `${hours}h ${minutes}m`;
    
    // Update progress ring
    updateProgressRing();
    
    // Update daily target
    updateDailyTarget();
}

// Update progress ring
function updateProgressRing() {
    const modeConfig = focusModes[currentMode];
    const progress = ((modeConfig.duration * 60 - seconds) / (modeConfig.duration * 60)) * 100;
    const percentage = Math.round(progress);
    
    document.getElementById('progressPercentage').textContent = `${percentage}%`;
    
    // Update SVG circle
    const circle = document.getElementById('progressRing');
    const circumference = 2 * Math.PI * 54;
    const offset = circumference - (percentage / 100) * circumference;
    
    circle.style.strokeDasharray = `${circumference} ${circumference}`;
    circle.style.strokeDashoffset = offset;
}

// Update daily target
function updateDailyTarget() {
    const dailyTargetSeconds = 2 * 3600; // 2 hours default
    const progress = Math.min((todayFocusTime / dailyTargetSeconds) * 100, 100);
    
    document.getElementById('targetFill').style.width = `${progress}%`;
    
    const completedHours = Math.floor(todayFocusTime / 3600);
    const completedMinutes = Math.floor((todayFocusTime % 3600) / 60);
    document.getElementById('targetStatus').textContent = `${completedHours}h ${completedMinutes}m completed`;
}

// Update session statistics
function updateSessionStats() {
    document.getElementById('sessionsToday').textContent = sessionCount;
    
    const avgSeconds = sessionCount > 0 ? todayFocusTime / sessionCount : 0;
    const avgMinutes = Math.floor(avgSeconds / 60);
    document.getElementById('avgFocusTime').textContent = `${avgMinutes}m`;
}

// Update timer if user changes duration (from mode selection)
document.getElementById('duration')?.addEventListener('change', () => {
    const newDuration = parseInt(document.getElementById('duration').value);
    if (newDuration && focusModes[currentMode]) {
        focusModes[currentMode].duration = newDuration;
        seconds = newDuration * 60;
        document.getElementById('display').textContent = formatTime(seconds);
    }
});
</script>

</body>
</html>

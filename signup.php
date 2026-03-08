<?php
require_once 'functions.php';
require_once 'db_connect.php';

// Check database connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    // Temporarily disable CSRF check for testing
    // if (!verify_csrf_token($csrf_token)) {
    //     echo "<script>alert('Security token invalid. Please try again.'); window.history.back();</script>";
    //     exit;
    // }

    // Retrieve and sanitize inputs
    $fullname  = sanitize_input($_POST['fullname']);
    $regNumber = strtoupper(sanitize_input($_POST['regNumber']));
    $username  = sanitize_input($_POST['username']);
    $year_of_study = (int)$_POST['year_of_study'];
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = sanitize_input($_POST['email'] ?? '');

    // Registration number pattern
    $regPattern = "/^([A-Z]{3,5})\/(\d{2})\/(SS)\/(\d{1,3})$/";

    if (!preg_match($regPattern, $regNumber, $matches)) {
        echo "<script>alert('Invalid registration number format! Example: BECE/25/SS/001'); window.history.back();</script>";
        exit;
    }

    // Extract components
    $pgCode     = $matches[1];
    $year       = (int)$matches[2];  
    $ss         = $matches[3];
    $individual = (int)$matches[4];

    // SS check
    if ($ss !== "SS") {
        echo "<script>alert('Registration number must include SS!'); window.history.back();</script>";
        exit;
    }

    // Year validation (updated to allow all students)
    $currentYear = (int)date("y");  
    if (!($year === $currentYear || $year === $currentYear - 1 || $year === $currentYear + 1 || $year === $currentYear + 2 || $year === $currentYear + 3 || $year === $currentYear + 4)) {
        echo "<script>alert('Please enter a valid registration year!'); window.history.back();</script>";
        exit;
    }

    // Individual number check
    if ($individual < 1 || $individual > 999) {
        echo "<script>alert('Individual registration number must be between 1 and 999'); window.history.back();</script>";
        exit;
    }

    // Check for duplicate username
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        echo "<script>alert('Username already exists!'); window.history.back();</script>";
        exit;
    }

    // Check for duplicate registration number
    $checkReg = $conn->prepare("SELECT id FROM users WHERE regNumber = ?");
    $checkReg->bind_param("s", $regNumber);
    $checkReg->execute();
    $checkReg->store_result();
    if ($checkReg->num_rows > 0) {
        echo "<script>alert('Registration number already exists!'); window.history.back();</script>";
        exit;
    }

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (fullname, regNumber, year_of_study, email, username, password) VALUES (?, ?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }
    
    $stmt->bind_param("ssisss", $fullname, $regNumber, $year_of_study, $email, $username, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Signup successful! Welcome to UNIMIND!'); window.location.href='login.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $check->close();
    $checkReg->close();
    $conn->close();
}
?>

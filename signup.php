<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve and sanitize inputs
    $fullname  = trim($_POST['fullname']);
    $regNumber = strtoupper(trim($_POST['regNumber']));
    $username  = trim($_POST['username']);
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);

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

    // First-year check
    $currentYear = (int)date("y");  
    if (!($year === $currentYear || $year === $currentYear - 1)) {
        echo "<script>alert('Only 1st-year students allowed (current or previous year)!'); window.history.back();</script>";
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

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (fullname, regNumber, username, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullname, $regNumber, $username, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Signup successful!'); window.location.href='Home.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $check->close();
    $conn->close();
}
?>

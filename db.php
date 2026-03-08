<?php
// Database configuration
$host = "localhost";
$dbname = "unimind";
$username = "root";
$password = "";

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode to associative
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Comment out success message for production
    // echo "✅ Database connection successful!";
    
} catch(PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}
?>
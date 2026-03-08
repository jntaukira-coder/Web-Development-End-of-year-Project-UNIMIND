<?php
include 'db.php';

try {
    // Create sessions table for Focus Zone
    $sql = "CREATE TABLE IF NOT EXISTS sessions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        subject VARCHAR(255) NOT NULL,
        study_date DATE NOT NULL,
        duration_minutes INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $result = $pdo->exec($sql);
    
    if ($result) {
        echo "✅ Sessions table created successfully!<br>";
    } else {
        echo "❌ Error creating sessions table<br>";
    }
    
    // Test the table
    $stmt = $pdo->query("DESCRIBE sessions");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>🔍 Sessions Table Columns:</h3>";
    foreach ($columns as $column) {
        echo "- $column<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage();
}
?>

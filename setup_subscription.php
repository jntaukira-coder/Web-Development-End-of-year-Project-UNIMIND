<?php
require_once 'db.php';

// Create subscription system
try {
    // Add subscription fields to users table
    $sql = "ALTER TABLE users 
        ADD COLUMN subscription_status ENUM('trial', 'premium', 'expired') DEFAULT 'trial',
        ADD COLUMN trial_start DATETIME,
        ADD COLUMN subscription_end DATETIME";
    
    if ($pdo->exec($sql)) {
        echo "✅ Database structure updated successfully!<br>";
        
        // Update existing users to trial status
        $update_sql = "UPDATE users SET 
            subscription_status = 'trial',
            trial_start = NOW(),
            subscription_end = NULL 
            WHERE subscription_status IS NULL";
        
        if ($pdo->exec($update_sql)) {
            echo "✅ Existing users updated to trial status!<br>";
        }
        
        // Test the structure
        $test_sql = "DESCRIBE users";
        $result = $pdo->query($test_sql);
        
        echo "<h3>Current Users Table Structure:</h3>";
        echo "<table border='1' style='border-collapse: collapse; margin: 20px 0;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        
        while ($row = $result->fetch()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
    } else {
        echo "❌ Error updating database: " . print_r($pdo->errorInfo(), true);
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage();
}
?>

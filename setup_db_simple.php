<?php
require_once 'db.php';

echo "<h1>🔧 Database Setup for SaaS Subscription</h1>";

try {
    // Check if columns exist first
    $check_sql = "SHOW COLUMNS FROM users LIKE 'subscription_status'";
    $stmt = $pdo->query($check_sql);
    $column_exists = $stmt->rowCount() > 0;
    
    if ($column_exists) {
        echo "✅ Subscription columns already exist!<br>";
    } else {
        echo "➕ Adding subscription columns...<br>";
        
        // Add columns one by one to avoid conflicts
        $sql1 = "ALTER TABLE users ADD COLUMN subscription_status ENUM('trial', 'premium', 'expired') DEFAULT 'trial'";
        $sql2 = "ALTER TABLE users ADD COLUMN trial_start DATETIME";
        $sql3 = "ALTER TABLE users ADD COLUMN subscription_end DATETIME";
        
        try {
            $pdo->exec($sql1);
            echo "✅ Added subscription_status column<br>";
        } catch (Exception $e) {
            echo "⚠️ subscription_status already exists or error: " . $e->getMessage() . "<br>";
        }
        
        try {
            $pdo->exec($sql2);
            echo "✅ Added trial_start column<br>";
        } catch (Exception $e) {
            echo "⚠️ trial_start already exists or error: " . $e->getMessage() . "<br>";
        }
        
        try {
            $pdo->exec($sql3);
            echo "✅ Added subscription_end column<br>";
        } catch (Exception $e) {
            echo "⚠️ subscription_end already exists or error: " . $e->getMessage() . "<br>";
        }
        
        // Update existing users
        $update_sql = "UPDATE users SET subscription_status = 'trial', trial_start = NOW() WHERE subscription_status IS NULL OR subscription_status = ''";
        $pdo->exec($update_sql);
        echo "✅ Updated existing users to trial status<br>";
    }
    
    // Show final table structure
    echo "<h3>📋 Current Users Table Structure:</h3>";
    $result = $pdo->query("DESCRIBE users");
    
    echo "<table border='1' style='border-collapse: collapse; margin: 20px 0;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    
    while ($row = $result->fetch()) {
        echo "<tr>";
        echo "<td><strong>" . $row['Field'] . "</strong></td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test subscription columns specifically
    echo "<h3>🔍 Subscription Columns Check:</h3>";
    $subscription_columns = ['subscription_status', 'trial_start', 'subscription_end'];
    
    foreach ($subscription_columns as $col) {
        $check = "SHOW COLUMNS FROM users LIKE '$col'";
        $stmt = $pdo->query($check);
        if ($stmt->rowCount() > 0) {
            echo "✅ $col - EXISTS<br>";
        } else {
            echo "❌ $col - MISSING<br>";
        }
    }
    
    echo "<br><h2>🚀 Next Steps:</h2>";
    echo "<a href='test_subscription.php'>🧪 Test Subscription System</a><br>";
    echo "<a href='Home.php'>🏠 Go to Dashboard</a><br>";
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage();
}
?>

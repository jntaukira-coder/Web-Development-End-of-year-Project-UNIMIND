<?php
// Simple connection test
echo "<h1>UNIMIND Connection Test</h1>";

echo "<h2>1. Testing PHP</h2>";
echo "<p>✅ PHP is working (Version: " . PHP_VERSION . ")</p>";

echo "<h2>2. Testing Database Connection</h2>";
try {
    $conn = new mysqli('localhost', 'root', '', 'unimind');
    if ($conn->connect_error) {
        echo "<p>❌ Database connection failed: " . $conn->connect_error . "</p>";
    } else {
        echo "<p>✅ Database connection successful!</p>";
        
        // Test if tables exist
        $result = $conn->query("SHOW TABLES");
        if ($result) {
            echo "<p>✅ Found " . $result->num_rows . " tables in database</p>";
            echo "<ul>";
            while ($row = $result->fetch_array()) {
                echo "<li>" . $row[0] . "</li>";
            }
            echo "</ul>";
        }
        $conn->close();
    }
} catch (Exception $e) {
    echo "<p>❌ Exception: " . $e->getMessage() . "</p>";
}

echo "<h2>3. File Paths</h2>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Current File: " . __FILE__ . "</p>";
echo "<p>Request URI: " . $_SERVER['REQUEST_URI'] . "</p>";

echo "<h2>4. Configuration Check</h2>";
echo "<p>✅ Config file exists: " . (file_exists('config.php') ? 'YES' : 'NO') . "</p>";
echo "<p>✅ Functions file exists: " . (file_exists('functions.php') ? 'YES' : 'NO') . "</p>";
echo "<p>✅ .htaccess file exists: " . (file_exists('.htaccess') ? 'YES' : 'NO') . "</p>";

echo "<h2>5. Test Links</h2>";
echo '<p><a href="index.php">🏠 Test Homepage</a></p>';
echo '<p><a href="signup_form.php">📝 Test Registration</a></p>';
echo '<p><a href="campus_map.php">🗺️ Test Campus Map</a></p>';
?>

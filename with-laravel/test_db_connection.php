<?php
/**
 * Database Connection Test Script
 * Use this to test the database connection before deploying
 */

// Database credentials from your original project
$dbHost = 'localhost';
$dbName = 'luxdemoestate_cv'; 
$dbUser = 'luxdemoestate_CV';
$dbPass = '!]5=Y75m}+MCuSU7';

echo "<h2>Database Connection Test</h2>";
echo "<p>Testing connection to: <strong>$dbHost</strong></p>";
echo "<p>Database: <strong>$dbName</strong></p>";
echo "<p>User: <strong>$dbUser</strong></p>";

try {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    
    if ($conn->connect_error) {
        echo "<div style='color: red; background: #ffe6e6; padding: 10px; border: 1px solid red; border-radius: 5px;'>";
        echo "<h3>❌ Connection Failed</h3>";
        echo "<p><strong>Error:</strong> " . htmlspecialchars($conn->connect_error) . "</p>";
        echo "</div>";
    } else {
        echo "<div style='color: green; background: #e6ffe6; padding: 10px; border: 1px solid green; border-radius: 5px;'>";
        echo "<h3>✅ Connection Successful!</h3>";
        echo "<p>Database connection established successfully.</p>";
        
        // Test a simple query
        $result = $conn->query("SHOW TABLES");
        if ($result) {
            echo "<h4>Available Tables:</h4>";
            echo "<ul>";
            while ($row = $result->fetch_array()) {
                echo "<li>" . htmlspecialchars($row[0]) . "</li>";
            }
            echo "</ul>";
        }
        
        // Check if we have any CVs
        $cvCount = $conn->query("SELECT COUNT(*) as count FROM cv")->fetch_assoc()['count'];
        echo "<p><strong>Total CVs in database:</strong> $cvCount</p>";
        
        echo "</div>";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<div style='color: red; background: #ffe6e6; padding: 10px; border: 1px solid red; border-radius: 5px;'>";
    echo "<h3>❌ Exception Occurred</h3>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}

echo "<hr>";
echo "<h3>Laravel Environment Configuration</h3>";
echo "<p>Add these settings to your Laravel <code>.env</code> file:</p>";
echo "<pre style='background: #f5f5f5; padding: 10px; border-radius: 5px;'>";
echo "DB_CONNECTION=mysql\n";
echo "DB_HOST=localhost\n";
echo "DB_PORT=3306\n";
echo "DB_DATABASE=luxdemoestate_cv\n";
echo "DB_USERNAME=luxdemoestate_CV\n";
echo "DB_PASSWORD=!]5=Y75m}+MCuSU7\n";
echo "</pre>";

echo "<p><strong>Note:</strong> Delete this test file after deployment for security.</p>";
?>

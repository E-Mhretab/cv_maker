<?php
/**
 * Setup Sessions Table
 * Creates the user_sessions table if it doesn't exist
 */

require_once 'connection.php';

echo "Checking user_sessions table...\n";

// Check if user_sessions table exists
$result = $conn->query("SHOW TABLES LIKE 'user_sessions'");
if ($result->num_rows === 0) {
    echo "user_sessions table does not exist. Creating...\n";
    
    // Create user_sessions table
    $createTableSQL = "
    CREATE TABLE user_sessions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        session_id VARCHAR(255) NOT NULL UNIQUE,
        user_id INT NOT NULL,
        ip_address VARCHAR(45) NOT NULL,
        user_agent TEXT NOT NULL,
        device_id VARCHAR(255) NULL,
        refresh_token VARCHAR(255) NULL,
        expires_at TIMESTAMP NOT NULL,
        refresh_expires_at TIMESTAMP NULL,
        last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_session_id (session_id),
        INDEX idx_user_id (user_id),
        INDEX idx_expires_at (expires_at),
        INDEX idx_device_id (device_id),
        INDEX idx_refresh_token (refresh_token),
        INDEX idx_last_activity (last_activity),
        INDEX idx_user_device (user_id, device_id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    if ($conn->query($createTableSQL)) {
        echo "✅ user_sessions table created successfully!\n";
    } else {
        echo "❌ Error creating user_sessions table: " . $conn->error . "\n";
    }
} else {
    echo "✅ user_sessions table already exists.\n";
    
    // Check if the table has the required columns
    $result = $conn->query("DESCRIBE user_sessions");
    $columns = [];
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
    
    $requiredColumns = ['device_id', 'refresh_token', 'last_activity'];
    $missingColumns = array_diff($requiredColumns, $columns);
    
    if (!empty($missingColumns)) {
        echo "Adding missing columns: " . implode(', ', $missingColumns) . "\n";
        
        $alterSQL = "ALTER TABLE user_sessions 
            ADD COLUMN device_id VARCHAR(255) NULL,
            ADD COLUMN refresh_token VARCHAR(255) NULL,
            ADD COLUMN last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        
        if ($conn->query($alterSQL)) {
            echo "✅ Missing columns added successfully!\n";
        } else {
            echo "❌ Error adding columns: " . $conn->error . "\n";
        }
    } else {
        echo "✅ All required columns exist.\n";
    }
}

// Test session creation
echo "\nTesting session creation...\n";
try {
    require_once 'session/session_manager.php';
    $sessionManager = new SessionManager($conn);
    
    // Test with a dummy user ID (assuming admin user exists)
    $testResult = $sessionManager->createSession(1);
    
    if ($testResult['success']) {
        echo "✅ Session creation test successful!\n";
        echo "Session ID: " . $testResult['session_id'] . "\n";
        
        // Clean up test session
        $sessionManager->destroySession($testResult['session_id']);
        echo "✅ Test session cleaned up.\n";
    } else {
        echo "❌ Session creation test failed: " . $testResult['error'] . "\n";
    }
} catch (Exception $e) {
    echo "❌ Session creation test error: " . $e->getMessage() . "\n";
}

echo "\nSetup complete!\n";
?>

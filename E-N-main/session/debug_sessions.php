<?php
/**
 * Debug script to check session management
 */

require_once '../connection.php';

echo "<h2>Session Management Debug</h2>";

// Check database connection
if (!$conn) {
    echo "<p style='color: red;'>Database connection failed!</p>";
    exit;
}

echo "<p style='color: green;'>Database connection successful!</p>";

// Check if user_sessions table exists
$result = $conn->query("SHOW TABLES LIKE 'user_sessions'");
if ($result && $result->num_rows > 0) {
    echo "<p style='color: green;'>user_sessions table exists!</p>";
} else {
    echo "<p style='color: red;'>user_sessions table does NOT exist!</p>";
    exit;
}

// Check table structure
echo "<h3>Table Structure:</h3>";
$result = $conn->query("DESCRIBE user_sessions");
if ($result) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Field'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['Type'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['Null'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['Key'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['Default'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['Extra'] ?? '') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>Error describing table: " . $conn->error . "</p>";
}

// Check total sessions
$result = $conn->query("SELECT COUNT(*) as total FROM user_sessions");
if ($result) {
    $row = $result->fetch_assoc();
    echo "<p><strong>Total sessions in database:</strong> " . $row['total'] . "</p>";
} else {
    echo "<p style='color: red;'>Error counting sessions: " . $conn->error . "</p>";
}

// Check active sessions (not expired)
$result = $conn->query("SELECT COUNT(*) as total FROM user_sessions WHERE expires_at > NOW()");
if ($result) {
    $row = $result->fetch_assoc();
    echo "<p><strong>Active sessions (not expired):</strong> " . $row['total'] . "</p>";
} else {
    echo "<p style='color: red;'>Error counting active sessions: " . $conn->error . "</p>";
}

// Show all sessions
echo "<h3>All Sessions:</h3>";
$result = $conn->query("SELECT * FROM user_sessions ORDER BY created_at DESC LIMIT 10");
if ($result) {
    $sessions = $result->fetch_all(MYSQLI_ASSOC);
    if (count($sessions) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>User ID</th><th>Session ID</th><th>IP</th><th>Created</th><th>Expires</th><th>Last Activity</th></tr>";
        foreach ($sessions as $session) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($session['id']) . "</td>";
            echo "<td>" . htmlspecialchars($session['user_id']) . "</td>";
            echo "<td>" . htmlspecialchars(substr($session['id'], 0, 20)) . "...</td>";
            echo "<td>" . htmlspecialchars($session['ip_address']) . "</td>";
            echo "<td>" . htmlspecialchars($session['created_at']) . "</td>";
            echo "<td>" . htmlspecialchars($session['expires_at']) . "</td>";
            echo "<td>" . htmlspecialchars($session['last_activity']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: orange;'>No sessions found in database.</p>";
    }
} else {
    echo "<p style='color: red;'>Error querying sessions: " . $conn->error . "</p>";
}

// Check current session
echo "<h3>Current Session Info:</h3>";
// Session should already be started by the main page
echo "<p><strong>Session ID:</strong> " . session_id() . "</p>";
echo "<p><strong>Session Status:</strong> " . session_status() . "</p>";
echo "<p><strong>Session Data:</strong></p>";
echo "<pre>";
print_r($_SESSION ?? []);
echo "</pre>";

// Test SessionManager
echo "<h3>SessionManager Test:</h3>";
require_once 'session_manager.php';
$sessionManager = new SessionManager($conn);

if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    echo "<p><strong>Current User ID:</strong> " . $userId . "</p>";
    
    try {
        $sessions = $sessionManager->getUserSessions($userId);
        echo "<p><strong>Sessions found by SessionManager:</strong> " . count($sessions) . "</p>";
        
        if (count($sessions) > 0) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>Session ID</th><th>Device ID</th><th>IP</th><th>Last Activity</th><th>Expires</th></tr>";
            foreach ($sessions as $session) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars(substr($session['id'], 0, 20)) . "...</td>";
                echo "<td>" . htmlspecialchars(substr($session['device_id'], 0, 16)) . "...</td>";
                echo "<td>" . htmlspecialchars($session['ip_address']) . "</td>";
                echo "<td>" . htmlspecialchars($session['last_activity']) . "</td>";
                echo "<td>" . htmlspecialchars($session['expires_at']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error getting sessions: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: orange;'>No user logged in (no user_id in session).</p>";
}

echo "<p><a href='session_management.php'>Go to Session Management</a></p>";
?>

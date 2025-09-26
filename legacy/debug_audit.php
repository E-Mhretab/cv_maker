<?php
/**
 * Debug version of audit viewer to identify issues
 */

try {
    // Start session first, before any output
    // session_start();  // COMENTADO: No es necesario en Laravel

    echo "<h2>Debug Audit Viewer</h2>";

    // Check session
    echo "<h3>Session Information:</h3>";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";

    require_once '../connection.php';
    require_once '../includes/auth.php';
    require_once 'audit_logger.php';

    echo "<h3>Database Connection:</h3>";
    if ($conn) {
        echo "<p style='color: green;'>Database connection successful!</p>";
    } else {
        echo "<p style='color: red;'>Database connection failed!</p>";
        exit;
    }

    // Create auth instance
    $auth = new Auth($conn);

    echo "<h3>Authentication Status:</h3>";
    echo "<p>Is logged in: " . ($auth->isLoggedIn() ? 'YES' : 'NO') . "</p>";
    echo "<p>Is admin: " . ($auth->isAdmin() ? 'YES' : 'NO') . "</p>";

    if ($auth->isLoggedIn()) {
        $user = $auth->getCurrentUser();
        echo "<p>Current user: " . htmlspecialchars($user['username'] ?? 'Unknown') . "</p>";
        echo "<p>User role: " . htmlspecialchars($user['role'] ?? 'Unknown') . "</p>";
    }

    // Check if user is admin
    if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
        echo "<p style='color: red;'>Access denied - not admin or not logged in</p>";
        echo "<p><a href='../login.php'>Go to login</a></p>";
        exit;
    }

    echo "<h3>Audit Logs Query Test:</h3>";

    // Test the exact query from audit_viewer.php
    $sql = "
        SELECT al.*, u.username 
        FROM audit_logs al 
        LEFT JOIN users u ON al.user_id = u.id 
        ORDER BY al.timestamp DESC 
        LIMIT 10
    ";

    $result = $conn->query($sql);
    if ($result) {
        $auditLogs = $result->fetch_all(MYSQLI_ASSOC);
        echo "<p>Query successful! Found " . count($auditLogs) . " audit logs:</p>";
        
        if (count($auditLogs) > 0) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>User</th><th>Action</th><th>Table</th><th>Record ID</th><th>Timestamp</th><th>IP</th></tr>";
            
            foreach ($auditLogs as $log) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($log['id']) . "</td>";
                echo "<td>" . htmlspecialchars($log['username'] ?? 'Unknown') . "</td>";
                echo "<td>" . htmlspecialchars($log['action']) . "</td>";
                echo "<td>" . htmlspecialchars($log['table_name']) . "</td>";
                echo "<td>" . htmlspecialchars($log['record_id']) . "</td>";
                echo "<td>" . htmlspecialchars($log['timestamp']) . "</td>";
                echo "<td>" . htmlspecialchars($log['ip_address']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: orange;'>No audit logs found in the query result.</p>";
        }
    } else {
        echo "<p style='color: red;'>Error executing query: " . $conn->error . "</p>";
    }

    echo "<h3>Direct Database Test:</h3>";
    $result = $conn->query("SELECT COUNT(*) as total FROM audit_logs");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "<p>Total audit logs in database: <strong>" . $row['total'] . "</strong></p>";
    } else {
        echo "<p style='color: red;'>Error querying audit_logs table: " . $conn->error . "</p>";
    }

    echo "<p><a href='audit_viewer.php'>Go to normal audit viewer</a></p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Error interno en debug: " . $e->getMessage() . "</p>";
}
?>

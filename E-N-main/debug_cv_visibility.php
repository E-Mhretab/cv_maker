<?php
// Start session first
session_start();

require_once 'connection.php';
require_once 'includes/auth.php';
require_once 'includes/middleware.php';

echo "<h1>CV Visibility Debug</h1>";

// Test auth
$auth = new Auth($conn);
$middleware = new Middleware($auth);
$userContext = $middleware->getUserContext();

echo "<h2>User Context:</h2>";
echo "Is logged in: " . ($userContext['is_logged_in'] ? 'YES' : 'NO') . "<br>";
echo "Is admin: " . ($userContext['is_admin'] ? 'YES' : 'NO') . "<br>";
if ($userContext['is_logged_in']) {
    echo "User ID: " . $userContext['user']['id'] . "<br>";
    echo "Username: " . $userContext['user']['username'] . "<br>";
    echo "Email: " . $userContext['user']['email'] . "<br>";
}

// Test CV query
echo "<h2>CV Query Test:</h2>";

// Build query like in cv_list.php
$whereConditions = [];
$params = [];
$paramTypes = '';

// Add user restriction (unless admin)
if (!$userContext['is_admin']) {
    if ($userContext['is_logged_in']) {
        // Show CVs owned by user OR CVs created with same email (guest CVs that can be claimed)
        $whereConditions[] = "(c.user_id = ? OR (c.user_id IS NULL AND c.email = ?))";
        $params[] = $userContext['user']['id'];
        $params[] = $userContext['user']['email'];
        $paramTypes .= 'is';
    } else {
        // Guest can see CVs they created (user_id IS NULL)
        $whereConditions[] = "c.user_id IS NULL";
    }
}

$whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

$query = "SELECT c.id, c.name, c.profile_summary, c.user_id, c.email,
                 m.created_at, m.template_type, m.is_public, m.published_at 
          FROM cv c 
          LEFT JOIN cv_metadata m ON c.id = m.cv_id 
          $whereClause 
          ORDER BY m.created_at DESC, c.id DESC";

echo "<h3>Query:</h3>";
echo "<pre>" . htmlspecialchars($query) . "</pre>";

echo "<h3>Parameters:</h3>";
echo "<pre>" . print_r($params, true) . "</pre>";

$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($paramTypes, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

echo "<h3>Results:</h3>";
echo "Number of CVs found: " . $result->num_rows . "<br><br>";

while ($cv = $result->fetch_assoc()) {
    echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
    echo "<strong>ID:</strong> " . $cv['id'] . "<br>";
    echo "<strong>Name:</strong> " . $cv['name'] . "<br>";
    echo "<strong>Email:</strong> " . $cv['email'] . "<br>";
    echo "<strong>User ID:</strong> " . ($cv['user_id'] ?? 'NULL') . "<br>";
    echo "<strong>Template Type:</strong> " . ($cv['template_type'] ?? 'NULL') . "<br>";
    echo "<strong>Is Public:</strong> " . ($cv['is_public'] ?? 'NULL') . "<br>";
    echo "<strong>Created At:</strong> " . ($cv['created_at'] ?? 'NULL') . "<br>";
    echo "</div>";
}

// Test all CVs (admin view)
echo "<h2>All CVs (Admin View):</h2>";
$query = "SELECT c.id, c.name, c.email, c.user_id, m.template_type, m.is_public, m.created_at 
          FROM cv c 
          LEFT JOIN cv_metadata m ON c.id = m.cv_id 
          ORDER BY m.created_at DESC, c.id DESC";

$result = $conn->query($query);
echo "Total CVs in database: " . $result->num_rows . "<br><br>";

while ($cv = $result->fetch_assoc()) {
    echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
    echo "<strong>ID:</strong> " . $cv['id'] . "<br>";
    echo "<strong>Name:</strong> " . $cv['name'] . "<br>";
    echo "<strong>Email:</strong> " . $cv['email'] . "<br>";
    echo "<strong>User ID:</strong> " . ($cv['user_id'] ?? 'NULL') . "<br>";
    echo "<strong>Template Type:</strong> " . ($cv['template_type'] ?? 'NULL') . "<br>";
    echo "<strong>Is Public:</strong> " . ($cv['is_public'] ?? 'NULL') . "<br>";
    echo "<strong>Created At:</strong> " . ($cv['created_at'] ?? 'NULL') . "<br>";
    echo "</div>";
}

$conn->close();
?>

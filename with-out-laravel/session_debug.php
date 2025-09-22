<?php
// Start session first, before any output
session_start();

require_once 'connection.php';
require_once 'includes/auth.php';

echo "<h1>Session Debug</h1>";

// Test session
echo "<h2>Session Info:</h2>";
echo "Session ID: " . session_id() . "<br>";
echo "Session Status: " . session_status() . "<br>";
echo "Session Name: " . session_name() . "<br>";
echo "Session Save Path: " . session_save_path() . "<br>";

echo "<h2>Session Variables:</h2>";
echo "User ID: " . ($_SESSION['user_id'] ?? 'NOT SET') . "<br>";
echo "Username: " . ($_SESSION['username'] ?? 'NOT SET') . "<br>";
echo "Email: " . ($_SESSION['email'] ?? 'NOT SET') . "<br>";
echo "Role: " . ($_SESSION['role'] ?? 'NOT SET') . "<br>";
echo "Session ID: " . ($_SESSION['session_id'] ?? 'NOT SET') . "<br>";

echo "<h2>All Session Data:</h2>";
echo "<pre>" . print_r($_SESSION, true) . "</pre>";

// Test auth
echo "<h2>Auth Test:</h2>";
$auth = new Auth($conn);
echo "Is logged in: " . ($auth->isLoggedIn() ? 'YES' : 'NO') . "<br>";
echo "Is admin: " . ($auth->isAdmin() ? 'YES' : 'NO') . "<br>";

$user = $auth->getCurrentUser();
if ($user) {
    echo "User found: " . json_encode($user) . "<br>";
} else {
    echo "No user found<br>";
}

echo "<h2>Cookie Info:</h2>";
echo "Session Cookie: " . ($_COOKIE[session_name()] ?? 'NOT SET') . "<br>";
echo "All Cookies: " . print_r($_COOKIE, true) . "<br>";

echo "<h2>Links:</h2>";
echo "<a href='login.php'>Go to Login</a><br>";
echo "<a href='manage/cv_list.php'>Go to CV List</a><br>";
echo "<a href='index.php'>Go to Home</a><br>";

$conn->close();
?>

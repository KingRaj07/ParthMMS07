<?php
// Enable error reporting (for development only)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

// Step 1: Read and decode JSON input
$data = json_decode(file_get_contents("php://input"), true);
$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');

// Step 2: Basic validation
if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'error' => 'Username and password required.']);
    exit;
}

// Step 3: Load users from users.json
$usersFile = 'users.json';
if (!file_exists($usersFile)) {
    echo json_encode(['success' => false, 'error' => 'User database not found.']);
    exit;
}

$users = json_decode(file_get_contents($usersFile), true);

// Step 4: Verify credentials
foreach ($users as $user) {
    if (
        isset($user['username'], $user['password']) &&
        $user['username'] === $username &&
        $user['password'] === $password
    ) {
        $_SESSION['username'] = $username;
        echo json_encode(['success' => true]);
        exit;
    }
}

// Step 5: Invalid credentials
echo json_encode(['success' => false, 'error' => 'Invalid username or password.']);
?>

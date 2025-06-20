<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');

if (!$username || !$password) {
  echo json_encode(['success' => false, 'error' => 'Missing fields']);
  exit;
}

$users = json_decode(file_get_contents('users.json'), true);
foreach ($users as $user) {
  if ($user['username'] === $username && $user['password'] === $password) {
    $_SESSION['username'] = $username;
    echo json_encode(['success' => true]);
    exit;
  }
}

echo json_encode(['success' => false, 'error' => 'Invalid credentials']);
?>

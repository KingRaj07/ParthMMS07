<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
  echo json_encode(['success' => false, 'error' => 'Not logged in']);
  exit;
}

$uploadDir = 'uploads/';
$maxFileSize = 100 * 1024 * 1024;
$allowedTypes = ['video/mp4', 'video/webm', 'video/ogg'];

if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

if (!isset($_FILES['video']) || !isset($_POST['title'])) {
  echo json_encode(['success' => false, 'error' => 'Missing fields']);
  exit;
}

$video = $_FILES['video'];
$title = htmlspecialchars(trim($_POST['title']));
$username = $_SESSION['username'];

if ($video['error'] !== UPLOAD_ERR_OK) {
  echo json_encode(['success' => false, 'error' => 'Upload error']);
  exit;
}
if ($video['size'] > $maxFileSize) {
  echo json_encode(['success' => false, 'error' => 'File too large']);
  exit;
}
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $video['tmp_name']);
finfo_close($finfo);
if (!in_array($mimeType, $allowedTypes)) {
  echo json_encode(['success' => false, 'error' => 'Invalid video type']);
  exit;
}

$ext = pathinfo($video['name'], PATHINFO_EXTENSION);
$baseName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $title);
$fileName = $baseName . '_' . time() . '.' . $ext;
$destination = $uploadDir . $fileName;

if (move_uploaded_file($video['tmp_name'], $destination)) {
  $metadataFile = $uploadDir . 'metadata.json';
  $entry = [
    'title' => $title,
    'username' => $username,
    'filename' => $fileName,
    'uploaded_at' => date('Y-m-d H:i:s')
  ];
  $metadataList = [];
  if (file_exists($metadataFile)) {
    $metadataList = json_decode(file_get_contents($metadataFile), true) ?? [];
  }
  $metadataList[] = $entry;
  file_put_contents($metadataFile, json_encode($metadataList, JSON_PRETTY_PRINT));
  echo json_encode(['success' => true, 'file' => $fileName]);
} else {
  echo json_encode(['success' => false, 'error' => 'Save failed']);
}
?>

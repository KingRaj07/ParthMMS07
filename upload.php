<?php
header('Content-Type: application/json');

// Configuration
$uploadDir = 'uploads/';
$maxFileSize = 100 * 1024 * 1024; // 100 MB
$allowedTypes = ['video/mp4', 'video/webm', 'video/ogg'];

// Create upload directory if not exists
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Check if form data is set
if (!isset($_FILES['video']) || !isset($_POST['title']) || !isset($_POST['username'])) {
    echo json_encode(['success' => false, 'error' => 'Incomplete form data.']);
    exit;
}

$video = $_FILES['video'];
$title = htmlspecialchars(trim($_POST['title']));
$username = htmlspecialchars(trim($_POST['username']));

// File error check
if ($video['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'File upload error.']);
    exit;
}

// Size check
if ($video['size'] > $maxFileSize) {
    echo json_encode(['success' => false, 'error' => 'File is too large.']);
    exit;
}

// Type check
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $video['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $allowedTypes)) {
    echo json_encode(['success' => false, 'error' => 'Invalid video type.']);
    exit;
}

// Generate safe filename
$ext = pathinfo($video['name'], PATHINFO_EXTENSION);
$baseName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $title);
$fileName = $baseName . '_' . time() . '.' . $ext;
$destination = $uploadDir . $fileName;

// Move file
if (move_uploaded_file($video['tmp_name'], $destination)) {
    echo json_encode(['success' => true, 'file' => $fileName]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to save video.']);
}
// Save metadata
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

?>

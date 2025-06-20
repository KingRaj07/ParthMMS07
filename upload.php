<?php
$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $username = $_POST['username'];
    $file = $_FILES['video'];

    if ($file && $file['error'] === 0) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $videoData = [
                'title' => $title,
                'filename' => $filename,
                'channel' => $username,
                'url' => $targetPath
            ];
            $jsonPath = 'videos.json';
            $videos = file_exists($jsonPath) ? json_decode(file_get_contents($jsonPath), true) : [];
            $videos[] = $videoData;
            file_put_contents($jsonPath, json_encode($videos, JSON_PRETTY_PRINT));
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'File move failed.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Upload error.']);
    }
}
?>

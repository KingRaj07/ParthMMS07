<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: index.html");
  exit;
}
$uploadDir = 'uploads/';
$metadataFile = $uploadDir . 'metadata.json';
$videos = file_exists($metadataFile) ? json_decode(file_get_contents($metadataFile), true) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Video Dashboard</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f2f2f2;
      padding: 30px;
    }
    h1 {
      color: #333;
    }
    .video-card {
      background: white;
      border-radius: 10px;
      padding: 15px;
      margin: 15px 0;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    video {
      width: 100%;
      max-height: 300px;
      border-radius: 8px;
      margin-top: 10px;
    }
    .uploader {
      font-size: 14px;
      color: #666;
    }
  </style>
</head>
<body>
  <h1>📺 Uploaded Videos</h1>

  <?php if (empty($videos)): ?>
    <p>No videos uploaded yet.</p>
  <?php else: ?>
    <?php foreach (array_reverse($videos) as $video): ?>
      <div class="video-card">
        <h3><?= htmlspecialchars($video['title']) ?></h3>
        <p class="uploader">Uploaded by: <?= htmlspecialchars($video['username']) ?> on <?= $video['uploaded_at'] ?></p>
        <video controls>
          <source src="<?= $uploadDir . htmlspecialchars($video['filename']) ?>" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</body>
</html>

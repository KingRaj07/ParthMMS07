<?php
$targetDir = "uploads/";

if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_FILES["video"]) && isset($_POST["title"])) {
        $file = $_FILES["video"];
        $title = trim($_POST["title"]);
        $username = isset($_POST["username"]) ? trim($_POST["username"]) : "Unknown";

        // File validation
        $allowedTypes = ['video/mp4', 'video/webm', 'video/ogg'];
        if (!in_array($file['type'], $allowedTypes)) {
            echo json_encode(["success" => false, "error" => "❌ Invalid file type."]);
            exit;
        }

        $fileName = basename($file["name"]);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            // Add video info to videos.json
            $jsonPath = "videos.json";
            $videos = file_exists($jsonPath) ? json_decode(file_get_contents($jsonPath), true) : [];

            $videos[] = [
                "title" => htmlspecialchars($title),
                "channel" => htmlspecialchars($username),
                "url" => $targetFile
            ];

            file_put_contents($jsonPath, json_encode($videos, JSON_PRETTY_PRINT));
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "⚠️ Upload failed."]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "⚠️ Missing title or video."]);
    }
} else {
    echo json_encode(["success" => false, "error" => "❌ Invalid request."]);
}
?>

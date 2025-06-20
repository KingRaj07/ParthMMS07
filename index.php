<?php
session_start();

$users = [
  "ParthMMS" => "MMSXXX",
  "Jyot2025" => "P@ss1234",
  "AdminYT" => "admin321",
  "VideoUser" => "uploadit",
  "ViewerOnly" => "watch123"
];

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  if (isset($users[$username]) && $users[$username] === $password) {
    $_SESSION["username"] = $username;
    header("Location: Welcome.html");
    exit();
  } else {
    $error = "❌ Invalid Username or Password!";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login | MyTube</title>
  <style>
    body { font-family: sans-serif; background: #f0f0f0; display: flex; height: 100vh; justify-content: center; align-items: center; }
    .login-container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 5px gray; width: 300px; }
    input { width: 100%; padding: 10px; margin-top: 10px; }
    button { width: 100%; padding: 10px; background-color: #007BFF; color: white; margin-top: 10px; border: none; cursor: pointer; }
    .error { color: red; margin-top: 10px; }
  </style>
</head>
<body>
  <form method="POST" class="login-container">
    <h2>Login</h2>
    <input type="text" name="username" placeholder="Username" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit">Login</button>
    <?php if ($error): ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>
  </form>
</body>
</html>

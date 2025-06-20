<?php
session_start();
session_unset();
session_destroy();

// Clear browser-side storage too (client JS must handle this)
header("Location: index.html");
exit;
?>

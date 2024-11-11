<?php
$BASE_URL = "http://localhost/library/";

// Destroy all session data
session_start();
session_unset();
session_destroy();

// Redirect to the home page
header("Location: " . $BASE_URL . "index.php");
exit();

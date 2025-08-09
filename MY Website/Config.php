<?php
// config.php
session_start();

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';      // set your DB password
$db_name = 'foodapp';

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_error) {
    die("DB Connection failed: " . $mysqli->connect_error);
}

// helper
function esc($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>

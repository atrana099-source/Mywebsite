<?php
require 'config.php';
if (!isset($_SESSION['admin'])) { header('Location: admin.php'); exit; }
$order_id = intval($_GET['order_id'] ?? 0);
$status = $mysqli->real_escape_string($_GET['status'] ?? '');
$allowed = ['received','preparing','out_for_delivery','delivered'];
if ($order_id && in_array($status, $allowed)) {
    $mysqli->query("UPDATE orders SET status='$status' WHERE id=$order_id");
}
header("Location: admin.php");
exit;

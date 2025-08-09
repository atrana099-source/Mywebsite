<?php
require 'config.php';
$order_id = intval($_GET['order_id'] ?? 0);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Success</title></head><body>
  <h1>Order placed!</h1>
  <p>Your order id is <strong><?php echo $order_id; ?></strong>.</p>
  <p>We will call you on the phone number you provided. <a href="index.php">Back to menu</a></p>
</body></html>

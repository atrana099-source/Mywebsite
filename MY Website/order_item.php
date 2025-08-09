<?php
require 'config.php';
if (!isset($_SESSION['admin'])) { header('Location: admin.php'); exit; }
$order_id = intval($_GET['order_id'] ?? 0);
$res = $mysqli->query("SELECT oi.*, i.name FROM order_items oi LEFT JOIN items i ON oi.item_id = i.id WHERE oi.order_id=$order_id");
$rows = [];
while($r = $res->fetch_assoc()) $rows[] = $r;
?>
<!doctype html><html><head><meta charset="utf-8"><title>Order Items</title></head><body>
  <h1>Items for Order #<?php echo $order_id; ?></h1>
  <table border="1" cellpadding="6"><tr><th>Item</th><th>Qty</th><th>Price</th></tr>
  <?php foreach($rows as $r): ?>
    <tr><td><?php echo esc($r['name']); ?></td><td><?php echo $r['qty']; ?></td><td>₹ <?php echo number_format($r['price'],2); ?></td></tr>
  <?php endforeach; ?>
  </table>
</body></html>

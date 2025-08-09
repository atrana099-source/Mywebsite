<?php
require 'config.php';

// add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $id = intval($_POST['item_id']);
    $qty = max(1, intval($_POST['qty']));
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id] += $qty;
    else $_SESSION['cart'][$id] = $qty;
    header("Location: index.php");
    exit;
}

// fetch items
$res = $mysqli->query("SELECT * FROM items WHERE available=1 ORDER BY created_at DESC");
$items = $res->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Food Menu</title>
  <style>
    body{font-family:Arial;margin:20px}
    .item{border:1px solid #ddd;padding:10px;margin:10px;display:inline-block;width:220px;vertical-align:top}
    .cart{position:fixed;right:20px;top:20px;border:1px solid #ccc;padding:10px;background:#f9f9f9}
    a.button{display:inline-block;padding:6px 10px;background:#28a745;color:#fff;text-decoration:none;border-radius:4px}
  </style>
</head>
<body>
  <h1>Menu</h1>
  <a href="cart.php" class="button">View Cart (<?php echo array_sum($_SESSION['cart'] ?? []); ?>)</a>
  <div>
    <?php foreach($items as $it): ?>
      <div class="item">
        <h3><?php echo esc($it['name']); ?></h3>
        <p><?php echo esc($it['description']); ?></p>
        <p>₹ <?php echo number_format($it['price'],2); ?></p>
        <form method="post" style="margin-top:8px">
          <input type="hidden" name="action" value="add">
          <input type="hidden" name="item_id" value="<?php echo $it['id']; ?>">
          Qty <input type="number" name="qty" value="1" min="1" style="width:50px">
          <button type="submit">Add</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>

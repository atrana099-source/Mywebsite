<?php
require 'config.php';

// remove item
if (isset($_GET['remove'])) {
    $rid = intval($_GET['remove']);
    if (isset($_SESSION['cart'][$rid])) unset($_SESSION['cart'][$rid]);
    header('Location: cart.php'); exit;
}

// place order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $name = $mysqli->real_escape_string($_POST['name']);
    $phone = $mysqli->real_escape_string($_POST['phone']);
    $address = $mysqli->real_escape_string($_POST['address']);
    $cart = $_SESSION['cart'] ?? [];
    if (empty($cart)) { $err="Cart empty"; }
    else {
        $total = 0;
        foreach($cart as $id => $qty) {
            $r = $mysqli->query("SELECT price FROM items WHERE id=" . intval($id));
            $row = $r->fetch_assoc();
            $total += $row['price'] * $qty;
        }
        $mysqli->query("INSERT INTO orders (customer_name,phone,address,total) VALUES ('$name','$phone','$address',$total)");
        $order_id = $mysqli->insert_id;
        foreach($cart as $id => $qty) {
            $r = $mysqli->query("SELECT price FROM items WHERE id=" . intval($id));
            $row = $r->fetch_assoc();
            $price = $row['price'];
            $mysqli->query("INSERT INTO order_items (order_id,item_id,qty,price) VALUES ($order_id,".intval($id).", ".intval($qty).", $price)");
        }
        unset($_SESSION['cart']);
        header("Location: success.php?order_id=$order_id");
        exit;
    }
}

// load cart items
$cart = $_SESSION['cart'] ?? [];
$items = [];
if ($cart) {
    $ids = implode(',', array_map('intval', array_keys($cart)));
    $res = $mysqli->query("SELECT * FROM items WHERE id IN ($ids)");
    while($r = $res->fetch_assoc()) {
        $items[$r['id']] = $r;
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Cart</title></head><body>
  <h1>Your Cart</h1>
  <?php if(!empty($err)) echo "<p style='color:red'>".esc($err)."</p>"; ?>
  <?php if(empty($cart)): ?>
    <p>Cart is empty. <a href="index.php">Go to menu</a></p>
  <?php else: ?>
    <table border="1" cellpadding="6">
      <tr><th>Item</th><th>Price</th><th>Qty</th><th>Subtotal</th><th>Remove</th></tr>
      <?php $total=0; foreach($cart as $id=>$qty): $it=$items[$id]; $sub=$it['price']*$qty; $total += $sub; ?>
        <tr>
          <td><?php echo esc($it['name']); ?></td>
          <td>₹ <?php echo number_format($it['price'],2); ?></td>
          <td><?php echo intval($qty); ?></td>
          <td>₹ <?php echo number_format($sub,2); ?></td>
          <td><a href="cart.php?remove=<?php echo $id; ?>">Remove</a></td>
        </tr>
      <?php endforeach; ?>
      <tr><td colspan="3"><strong>Total</strong></td><td>₹ <?php echo number_format($total,2); ?></td><td></td></tr>
    </table>

    <h2>Checkout (Cash on Delivery)</h2>
    <form method="post">
      Name:<br><input name="name" required><br>
      Phone:<br><input name="phone" required><br>
      Address:<br><textarea name="address" required></textarea><br>
      <button type="submit" name="place_order">Place Order</button>
    </form>
  <?php endif; ?>
</body></html>

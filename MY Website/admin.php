<?php
require 'config.php';

// login
if (isset($_POST['login'])) {
    $user = $mysqli->real_escape_string($_POST['username']);
    $pass = $_POST['password'];
    $r = $mysqli->query("SELECT * FROM users WHERE username='$user' AND role='admin' LIMIT 1");
    if ($r && $row = $r->fetch_assoc()) {
        if (password_verify($pass, $row['password_hash'])) {
            $_SESSION['admin'] = $row['username'];
            header("Location: admin.php"); exit;
        } else $err = "Invalid credentials";
    } else $err = "Invalid credentials";
}

// logout
if (isset($_GET['logout'])) { unset($_SESSION['admin']); header("Location: admin.php"); exit; }

// require login for admin actions
$logged = isset($_SESSION['admin']);

// handle add item
if ($logged && isset($_POST['add_item'])) {
    $n = $mysqli->real_escape_string($_POST['name']);
    $d = $mysqli->real_escape_string($_POST['description']);
    $p = floatval($_POST['price']);
    $mysqli->query("INSERT INTO items (name,description,price) VALUES ('$n','$d',$p)");
    header("Location: admin.php"); exit;
}

// toggle availability
if ($logged && isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $mysqli->query("UPDATE items SET available = 1 - available WHERE id=$id");
    header("Location: admin.php"); exit;
}

// view orders
$orders = [];
if ($logged) {
  $res = $mysqli->query("SELECT * FROM orders ORDER BY created_at DESC");
  while($r = $res->fetch_assoc()) $orders[] = $r;
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Admin</title></head><body>
<?php if(!$logged): ?>
  <h1>Admin Login</h1>
  <?php if(!empty($err)) echo "<p style='color:red'>".esc($err)."</p>"; ?>
  <form method="post">
    Username:<br><input name="username"><br>Password:<br><input type="password" name="password"><br>
    <button name="login">Login</button>
  </form>
<?php else: ?>
  <p>Welcome, <?php echo esc($_SESSION['admin']); ?> — <a href="?logout=1">Logout</a></p>

  <h2>Add Item</h2>
  <form method="post">
    Name<br><input name="name" required><br>
    Description<br><input name="description"><br>
    Price<br><input name="price" required step="0.01"><br>
    <button name="add_item">Add</button>
  </form>

  <h2>Items</h2>
  <table border="1" cellpadding="6"><tr><th>Name</th><th>Price</th><th>Available</th><th>Toggle</th></tr>
  <?php $res = $mysqli->query("SELECT * FROM items ORDER BY created_at DESC");
    while($it = $res->fetch_assoc()): ?>
    <tr>
      <td><?php echo esc($it['name']); ?></td>
      <td>₹ <?php echo number_format($it['price'],2); ?></td>
      <td><?php echo $it['available'] ? 'Yes':'No'; ?></td>
      <td><a href="?toggle=<?php echo $it['id']; ?>">Toggle</a></td>
    </tr>
  <?php endwhile; ?>
  </table>

  <h2>Orders</h2>
  <?php foreach($orders as $o): ?>
    <div style="border:1px solid #ccc;padding:8px;margin:8px 0">
      <strong>Order #<?php echo $o['id']; ?></strong> - <?php echo esc($o['customer_name']); ?> - ₹ <?php echo number_format($o['total'],2); ?> - <?php echo esc($o['status']); ?>
      <div>Phone: <?php echo esc($o['phone']); ?> | Address: <?php echo esc($o['address']); ?></div>
      <div>
        <a href="order_items.php?order_id=<?php echo $o['id']; ?>" target="_blank">View items</a>
        |
        <a href="change_status.php?order_id=<?php echo $o['id']; ?>&status=preparing">Preparing</a>
        |
        <a href="change_status.php?order_id=<?php echo $o['id']; ?>&status=out_for_delivery">Out for delivery</a>
        |
        <a href="change_status.php?order_id=<?php echo $o['id']; ?>&status=delivered">Delivered</a>
      </div>
      <small><?php echo $o['created_at']; ?></small>
    </div>
  <?php endforeach; ?>

<?php endif; ?>
</body></html>

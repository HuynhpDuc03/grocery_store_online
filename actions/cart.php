<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$action = $_POST['action'] ?? $_GET['action'] ?? '';
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$id = (int)($_POST['product_id'] ?? $_GET['product_id'] ?? 0);
if ($action === 'add') {
    $stmt = db()->prepare('SELECT id,name,price,image_url,stock FROM products WHERE id=?'); $stmt->execute([$id]); $p = $stmt->fetch();
    if ($p && (int)$p['stock'] > 0) { $qty = max(1, (int)($_POST['quantity'] ?? 1)); $_SESSION['cart'][$id] = ['id'=>(int)$p['id'],'name'=>$p['name'],'price'=>(float)$p['price'],'image_url'=>$p['image_url'],'quantity'=>min($qty, (int)$p['stock'])]; }
}
if ($action === 'update') foreach (($_POST['quantities'] ?? []) as $productId => $quantity) { if (isset($_SESSION['cart'][$productId])) $_SESSION['cart'][$productId]['quantity'] = max(1, (int)$quantity); }
if ($action === 'remove') { unset($_SESSION['cart'][$id]); flash('success','Đã xóa sản phẩm khỏi giỏ hàng.'); }
if ($action === 'add') flash('success','Đã thêm sản phẩm vào giỏ hàng.');
if ($action === 'update') flash('success','Đã cập nhật giỏ hàng.');
redirect('../pages/cart.php');

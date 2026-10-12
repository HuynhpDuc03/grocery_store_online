<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_login();
$items = $_SESSION['cart'] ?? [];
if (!$items) redirect('../pages/cart.php?error=empty');
$name = trim($_POST['customer_name'] ?? ''); $phone = trim($_POST['phone'] ?? ''); $address = trim($_POST['address'] ?? '');
if (!$name || !$phone || !$address) redirect('../pages/cart.php?error=required');
$pdo = db(); $pdo->beginTransaction();
try {
    $total = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $items));
    $stmt = $pdo->prepare('INSERT INTO orders (user_id,customer_name,phone,email,address,note,total) VALUES (?,?,?,?,?,?,?)');
    $u = current_user(); $stmt->execute([$u['id'],$name,$phone,$u['email'],$address,trim($_POST['note'] ?? ''),$total]); $orderId = (int)$pdo->lastInsertId();
    $line = $pdo->prepare('INSERT INTO order_items (order_id,product_id,product_name,price,quantity) VALUES (?,?,?,?,?)');
    foreach ($items as $item) {
        $line->execute([$orderId,$item['id'],$item['name'],$item['price'],$item['quantity']]);
        $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?')->execute([$item['quantity'], $item['id'], $item['quantity']]);
    }
    $pdo->commit(); unset($_SESSION['cart']); flash('success','Đặt hàng thành công! Cảm ơn bạn đã mua hàng.'); redirect('../pages/cart.php');
} catch (Throwable $e) { $pdo->rollBack(); flash('error','Đặt hàng thất bại, vui lòng thử lại.'); redirect('../pages/cart.php'); }

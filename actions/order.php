<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_login();
$items = $_SESSION['cart'] ?? [];
if (!$items) redirect('../pages/cart.php?error=empty');
$name = trim($_POST['customer_name'] ?? ''); $phone = trim($_POST['phone'] ?? ''); $email = trim($_POST['email'] ?? ''); $address = trim($_POST['address'] ?? ''); $payment = $_POST['payment_method'] ?? 'cod';
if (!$name || !$phone || !$address) redirect('../pages/cart.php?error=required');
$pdo = db(); $pdo->beginTransaction();
try {
    $subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $items));
    $total = $subtotal + 15000;
    $stmt = $pdo->prepare('INSERT INTO orders (user_id,customer_name,phone,email,address,note,payment_method,total) VALUES (?,?,?,?,?,?,?,?)');
    $u = current_user(); if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $email = $u['email']; if (!in_array($payment, ['cod','banking'], true)) $payment = 'cod'; $stmt->execute([$u['id'],$name,$phone,$email,$address,trim($_POST['note'] ?? ''),$payment,$total]); $orderId = (int)$pdo->lastInsertId();
    $line = $pdo->prepare('INSERT INTO order_items (order_id,product_id,product_name,price,quantity) VALUES (?,?,?,?,?)');
    foreach ($items as $item) {
        $line->execute([$orderId,$item['id'],$item['name'],$item['price'],$item['quantity']]);
        $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?')->execute([$item['quantity'], $item['id'], $item['quantity']]);
    }
    $pdo->commit(); unset($_SESSION['cart']); flash('success','Đặt hàng thành công! Cảm ơn bạn đã mua hàng.'); redirect('../pages/cart.php');
} catch (Throwable $e) { $pdo->rollBack(); flash('error','Đặt hàng thất bại, vui lòng thử lại.'); redirect('../pages/cart.php'); }

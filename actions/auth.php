<?php
require_once __DIR__ . '/../includes/bootstrap.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';
if ($action === 'register') {
    $name = trim($_POST['full_name'] ?? ''); $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) { flash('error','Vui lòng nhập đầy đủ thông tin và mật khẩu tối thiểu 6 ký tự.'); redirect('../pages/register.php'); }
    $stmt = db()->prepare('INSERT INTO users (full_name,email,password_hash) VALUES (?,?,?)');
    try { $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]); flash('success','Đăng ký tài khoản thành công! Bạn có thể đăng nhập.'); redirect('../pages/login.php'); }
    catch (PDOException $e) { flash('error','Email này đã được sử dụng.'); redirect('../pages/register.php'); }
}
if ($action === 'login') {
    $stmt = db()->prepare('SELECT * FROM users WHERE email = ?'); $stmt->execute([strtolower(trim($_POST['email'] ?? ''))]);
    $user = $stmt->fetch();
    if ($user && password_verify($_POST['password'] ?? '', $user['password_hash'])) { unset($user['password_hash']); $_SESSION['user'] = $user; flash('success','Đăng nhập thành công!'); redirect('../index.php'); }
    flash('error','Email hoặc mật khẩu không chính xác.'); redirect('../pages/login.php');
}
if ($action === 'logout') { session_destroy(); session_start(); flash('success','Bạn đã đăng xuất thành công.'); redirect('../index.php'); }

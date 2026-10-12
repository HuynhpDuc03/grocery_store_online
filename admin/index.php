<?php require_once __DIR__.'/../includes/bootstrap.php'; require_admin(); $users=(int)db()->query('SELECT COUNT(*) FROM users')->fetchColumn(); $products=(int)db()->query('SELECT COUNT(*) FROM products')->fetchColumn(); $orders=(int)db()->query('SELECT COUNT(*) FROM orders')->fetchColumn(); $revenue=(float)db()->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status <> 'cancelled'")->fetchColumn(); ?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard quản trị</title>
    <link rel="stylesheet" href="../assets/css/style-admin.css">
</head>

<body>
    <div class="admin-container">
        <aside class="sidebar">
            <div class="brand">🛡 ADMIN PANEL</div>
            <ul class="menu">
                <li class="active"><a href="index.php">📊 Dashboard</a></li>
                <li><a href="products.php">📦 Quản lý sản phẩm</a></li>
                <li><a href="categories.php">☷ Danh mục sản phẩm</a></li>
                <li><a href="orders.php">🧾 Quản lý đơn hàng</a></li>
                <li><a href="../index.php">← Về trang chủ</a></li>
            </ul>
        </aside>
        <main class="main-content">
            <div class="top-header">
                <h2>Dashboard / Tổng quan</h2><span>Xin chào, <?=e(current_user()['full_name'])?></span>
            </div>
            <div class="stats-grid">
                <div class="card">
                    <div class="card-title">Người dùng</div>
                    <div class="card-value"><?=$users?></div>
                </div>
                <div class="card">
                    <div class="card-title">Sản phẩm</div>
                    <div class="card-value"><?=$products?></div>
                </div>
                <div class="card">
                    <div class="card-title">Đơn hàng</div>
                    <div class="card-value"><?=$orders?></div>
                </div>
                <div class="card">
                    <div class="card-title">Doanh thu</div>
                    <div class="card-value"><?=number_format($revenue,0,',','.')?> đ</div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
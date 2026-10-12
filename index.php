<?php require_once __DIR__ . '/includes/bootstrap.php'; $products = db()->query('SELECT * FROM products ORDER BY created_at DESC LIMIT 8')->fetchAll(); ?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Tạp Hóa Online</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style-index.css">
</head>

<body>
    <!-- <header class="site-header">
        <div class="top-bar">
            <div class="container top-bar-flex"><span><i class="fa-solid fa-phone"></i> Hotline: 1900
                    6868</span><span>Hệ thống Cửa Hàng Tạp Hóa Online</span></div>
        </div>
        <div class="main-header">
            <div class="container header-flex"><a href="index.php" class="logo">
                    <div class="logo-icon-wrap"><i class="fa-solid fa-cart-shopping"></i></div>
                    <div class="logo-text">
                        <h2>Tạp Hóa Online</h2><span>Tiện lợi - Tươi ngon mỗi ngày</span>
                    </div>
                </a>
                <div class="header-actions"><a href="pages/login.php" class="action-item"><i
                            class="fa-regular fa-user action-icon"></i><span><?= current_user() ? e(current_user()['full_name']) : 'Tài khoản' ?></span></a><a
                        href="pages/cart.php" class="action-item"><i
                            class="fa-solid fa-basket-shopping action-icon"></i><span>Giỏ hàng</span></a></div>
            </div>
        </div>
        <nav class="main-nav">
            <div class="container">
                <ul class="nav-menu">
                    <li class="active"><a href="index.php">Trang chủ</a></li>
                    <li><a href="pages/products.php">Sản phẩm</a></li>
                    <li><a href="pages/cart.php">Giỏ hàng & Thanh toán</a></li>
                    <?php if(current_user() && current_user()['role']==='admin'): ?><li><a
                            href="admin/products.php">Quản trị</a></li><?php endif; ?>
                </ul>
            </div>
        </nav>
    </header> -->
    <?php $base = ''; require __DIR__.'/./includes/site-header.php'; ?>
    <main class="container page-layout">
        <aside class="sidebar">
            <div class="sidebar-box">
                <h3 class="box-title">Danh mục hàng hóa</h3>
                <ul class="cat-list"><?php foreach(db()->query('SELECT * FROM categories ORDER BY name') as $c): ?><li>
                        <a href="pages/products.php?category=<?= $c['id'] ?>"><?= e($c['name']) ?></a>
                    </li>
                    <?php endforeach; ?></ul>
            </div>
        </aside>
        <section class="content-area">
            <div class="main-banner">
                <h2>Tạp Hóa Gần Bạn - Giao Hàng Siêu Tốc</h2>
                <p>Thực phẩm tươi sạch mỗi ngày, giá cả bình ổn.</p>
            </div>
            <h3>Sản phẩm nổi bật</h3>
            <div class="product-grid-demo"><?php foreach($products as $p): ?><article class="product-item"><img
                        src="<?= e($p['image_url']) ?>" alt="<?= e($p['name']) ?>">
                    <h4><?= e($p['name']) ?></h4>
                    <p class="price-current"><?= number_format((float)$p['price'],0,',','.') ?> đ</p>
                    <br />
                    <a class="btn-add" href="pages/products.php">Xem sản phẩm</a>
                </article><?php endforeach; ?></div>
        </section>
    </main>
    <?php require __DIR__.'/./includes/site-footer.php'; ?>
</body>

</html>

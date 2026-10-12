<?php $base=$base??(str_contains($_SERVER['SCRIPT_NAME']??'','/pages/')?'../':''); require_once __DIR__.'/flash-popup.php'; ?><header class="site-header">
    <div class="top-bar">
        <div class="container top-bar-flex"><span>☎ Hotline: 1900 6868</span><span>Hệ thống Cửa Hàng Tạp Hóa
                Online</span></div>
    </div>
    <div class="main-header">
        <div class="container header-flex"><a href="<?=$base?>index.php" class="logo">
                <div class="logo-icon-wrap">🛒</div>
                <div class="logo-text">
                    <h2>Tạp Hóa Online</h2><span>Tiện lợi - Tươi ngon mỗi ngày</span>
                </div>
            </a>
            <div class="header-actions"><a href="<?=$base?>pages/login.php" class="action-item">👤
                    <span><?=current_user()?e(current_user()['full_name']):'Tài khoản'?></span></a><?php if(current_user()): ?><a
                    href="<?=$base?>actions/auth.php?action=logout" class="action-item">Đăng xuất</a><?php endif; ?><a
                    href="<?=$base?>pages/cart.php" class="action-item">🛍 <span>Giỏ hàng</span></a></div>
        </div>
    </div>
    <nav class="main-nav">
        <div class="container">
            <ul class="nav-menu">
                <li><a href="<?=$base?>./index.php">Trang chủ</a></li>
                <li><a href="<?=$base?>pages/products.php">Sản phẩm</a></li>
                <li><a href="<?=$base?>pages/cart.php">Giỏ hàng & Thanh toán</a></li>
                <?php if(current_user()&&current_user()['role']==='admin'): ?><li><a
                        href="<?=$base?>admin/index.php">Quản trị</a></li><?php endif; ?>
            </ul>
        </div>
    </nav>
</header>

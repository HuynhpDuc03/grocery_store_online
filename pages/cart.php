<?php require_once __DIR__.'/../includes/bootstrap.php'; $base='../'; $items=$_SESSION['cart']??[]; $total=array_sum(array_map(fn($i)=>$i['price']*$i['quantity'],$items)); ?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Giỏ hàng - Tạp Hóa Online</title>
    <link rel="stylesheet" href="../assets/css/style-cart.css">
</head>

<body><?php require __DIR__.'/../includes/site-header.php'; ?><main class="container page-layout">
        <section class="content-area">
            <h1 class="section-title">🛒 Giỏ hàng & Thanh toán</h1><?php if(!$items): ?><div class="checkout-card">
                <p>Giỏ hàng đang trống.</p><a href="products.php" class="btn-continue-shopping">Tiếp tục mua hàng</a>
            </div><?php else: ?><div class="checkout-grid">
                <div class="checkout-card">
                    <h3 class="card-title">Sản phẩm trong giỏ</h3>
                    <form method="post" action="../actions/cart.php"><input type="hidden" name="action" value="update">
                        <div class="cart-items-container"><?php foreach($items as $i): ?><div class="cart-item">
                                <div class="item-info">
                                    <div class="item-title"><?=e($i['name'])?></div>
                                    <div class="item-price"><?=number_format($i['price'],0,',','.')?> đ</div>
                                </div>
                                <div>
                                    <input type="number" min="1" name="quantities[<?=$i['id']?>]"
                                        value="<?=$i['quantity']?>">
                                       
                                </div>
                                 <a
                                        href="../actions/cart.php?action=remove&product_id=<?=$i['id']?>">Xóa</a>
                            </div><?php endforeach; ?></div><button class="btn-checkout">Cập nhật giỏ hàng</button>
                    </form>
                </div>
                <div class="checkout-card">
                    <h3 class="card-title">Thông tin đặt hàng</h3>
                    <h2><?=number_format($total,0,',','.')?> đ</h2>
<form method="post" action="../actions/order.php"><input name="customer_name"
                            placeholder="Họ tên người nhận" required><input name="phone" placeholder="Số điện thoại"
                            required><input name="address" placeholder="Địa chỉ giao hàng" required><textarea
                            name="note" placeholder="Ghi chú"></textarea><button class="btn-checkout">Đặt hàng
                            ngay</button></form>
                </div>
            </div><?php endif; ?>
        </section>
    </main><?php require __DIR__.'/../includes/site-footer.php'; ?></body>

</html>

<?php
require_once __DIR__.'/../includes/bootstrap.php'; $base='../';
$category=(int)($_GET['category']??0); $cats=db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();
$sql='SELECT p.*,c.name category_name FROM products p JOIN categories c ON c.id=p.category_id'; $params=[];
if($category){$sql.=' WHERE p.category_id=?';$params[]=$category;} $sql.=' ORDER BY p.created_at DESC'; $st=db()->prepare($sql);$st->execute($params);$products=$st->fetchAll();
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sản phẩm - Tạp Hóa Online</title>
    <link rel="stylesheet" href="../assets/css/style-sanpham.css">
</head>

<body><?php require __DIR__.'/../includes/site-header.php'; ?><main class="container page-layout">
        <aside class="sidebar">
            <div class="sidebar-box">
                <h3 class="box-title">Danh mục hàng hóa</h3>
                <ul class="cat-list"><?php foreach($cats as $c): ?><li><a
                            href="?category=<?=$c['id']?>"><?=e($c['name'])?></a></li><?php endforeach; ?></ul>
            </div>
        </aside>
        <section class="content-area">
            <h1 class="section-title">Tất cả sản phẩm</h1>
            <div class="product-grid"><?php foreach($products as $p): ?><article class="product-item"><img
                        src="<?=e($p['image_url'])?>" alt="<?=e($p['name'])?>">
                    <h3><?=e($p['name'])?></h3>
                    <p class="price-current"><?=number_format((float)$p['price'],0,',','.')?> đ</p><small>Còn
                        <?=$p['stock']?> sản phẩm</small><?php if($p['stock']>0): ?><form method="post"
                        action="../actions/cart.php"><input type="hidden" name="action" value="add"><input type="hidden"
                            name="product_id" value="<?=$p['id']?>"><button class="btn-add">Thêm vào giỏ</button></form>
                    <?php endif; ?>
                </article><?php endforeach; ?></div>
        </section>
    </main><?php require __DIR__.'/../includes/site-footer.php'; ?></body>

</html>
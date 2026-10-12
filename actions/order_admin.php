<?php
require_once __DIR__ . '/../includes/bootstrap.php'; require_admin();
$id=(int)($_POST['id']??0); $status=$_POST['status']??'';
if($id && in_array($status,['pending','confirmed','shipping','completed','cancelled'],true)){db()->prepare('UPDATE orders SET status=? WHERE id=?')->execute([$status,$id]); flash('success','Đã cập nhật trạng thái đơn hàng.');}
redirect('../admin/orders.php');

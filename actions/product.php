<?php
require_once __DIR__ . '/../includes/bootstrap.php'; require_admin();
$action=$_POST['action']??''; $id=(int)($_POST['id']??0);
if($action==='delete'){db()->prepare('DELETE FROM products WHERE id=?')->execute([$id]); flash('success','Đã xóa sản phẩm.');}
if($action==='save'){$data=[trim($_POST['name']),trim($_POST['slug']),trim($_POST['description']??''),(float)$_POST['price'],trim($_POST['image_url']??''),(int)$_POST['stock'],(int)$_POST['category_id']]; if($id){$st=db()->prepare('UPDATE products SET name=?,slug=?,description=?,price=?,image_url=?,stock=?,category_id=? WHERE id=?');$st->execute([...$data,$id]); flash('success','Đã cập nhật sản phẩm.');}else{$st=db()->prepare('INSERT INTO products(name,slug,description,price,image_url,stock,category_id) VALUES(?,?,?,?,?,?,?)');$st->execute($data); flash('success','Đã thêm sản phẩm mới.');}}
redirect('../admin/products.php');

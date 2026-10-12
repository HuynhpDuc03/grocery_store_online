<?php
require_once __DIR__ . '/../includes/bootstrap.php'; require_admin();
$action = $_POST['action'] ?? '';
if ($action === 'save') {
    $id=(int)($_POST['id']??0); $name=trim($_POST['name']??''); $slug=trim($_POST['slug']??'');
    if ($name && $slug) {
        if ($id) { $s=db()->prepare('UPDATE categories SET name=?,slug=? WHERE id=?'); $s->execute([$name,$slug,$id]); flash('success','Đã cập nhật danh mục.'); }
        else { $s=db()->prepare('INSERT INTO categories(name,slug) VALUES(?,?)'); $s->execute([$name,$slug]); flash('success','Đã thêm danh mục mới.'); }
    }
}
if ($action === 'delete') { db()->prepare('DELETE FROM categories WHERE id=?')->execute([(int)$_POST['id']]); flash('success','Đã xóa danh mục.'); }
redirect('../admin/categories.php');

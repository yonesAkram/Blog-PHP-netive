<?php
require_once __DIR__ . '/../function/func.php';
csrf_check();

$post_id = (int)($_POST['post_id'] ?? 0);
$slug    = $_POST['slug'] ?? '';
$body    = trim($_POST['body'] ?? '');

if ($post_id <= 0 || $body === '') {
  set_flash('err', 'التعليق فاضي أو فيه خطأ');
  redirect(url('/index.php'));
}

$c = db();

if (is_logged_in()) {
  $u = current_user();
  $uid = (int)$u['id'];

  $sql = "INSERT INTO comments (post_id,user_id,body) VALUES (?,?,?)";
  $st = mysqli_prepare($c, $sql);
  mysqli_stmt_bind_param($st, "iis", $post_id, $uid, $body);
  mysqli_stmt_execute($st);
} else {
  $name  = trim($_POST['author_name'] ?? '');
  $email = trim($_POST['author_email'] ?? '');

  if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_flash('err', 'اكتب الاسم والإيميل بشكل صحيح');
    redirect(url('/resources/view/post.php?slug=' . urlencode($slug)));
  }

  $sql = "INSERT INTO comments (post_id,author_name,author_email,body) VALUES (?,?,?,?)";
  $st = mysqli_prepare($c, $sql);
  mysqli_stmt_bind_param($st, "isss", $post_id, $name, $email, $body);
  mysqli_stmt_execute($st);
}

set_flash('ok', 'تم إضافة التعليق');
redirect(url('/resources/view/post.php?slug=' . urlencode($slug)));

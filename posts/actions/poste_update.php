<?php
require_once __DIR__ . '/../function/func.php';
require_login();
csrf_check();

$c = db();
$me = auth_user();

$id = (int)($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$cat_id = (int)($_POST['cat_id'] ?? 0);

$res = mysqli_query($c, "SELECT * FROM post WHERE id=$id LIMIT 1");
$post = $res ? mysqli_fetch_assoc($res) : null;

if (!$post) {
  set_flash('err', 'Post not found');
  redirect(url('/index.php'));
  exit;
}

if (!can_manage_post($me, $post)) {
  set_flash('err', 'Not allowed');
  redirect(url('/index.php'));
  exit;
}

if ($title === '' || $content === '' || $cat_id <= 0) {
  set_flash('err', 'Check data');
  redirect(url('/resources/view/post_edit.php?id=' . $id));
  exit;
}

$title_esc = mysqli_real_escape_string($c, $title);
$content_esc = mysqli_real_escape_string($c, $content);
$today = date('Y-m-d');

$sql = "
UPDATE post
SET title='$title_esc', content='$content_esc', cat_id=$cat_id, updated_at='$today'
WHERE id=$id
";
$ok = mysqli_query($c, $sql);
if (!$ok) die("DB Error (UPDATE post): " . mysqli_error($c));

set_flash('ok', 'Post updated');
redirect(url('/index.php'));
exit;

<?php
require_once __DIR__ . '/../function/func.php';
require_login();
csrf_check();

$me = auth_user();

$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$cat_id = (int)($_POST['cat_id'] ?? 0);

if ($title === '' || $content === '' || $cat_id <= 0) {
  set_flash('err', 'Check data');
  redirect(url('/resources/view/post_create.php'));
  exit;
}

$c = db();

$title_esc = mysqli_real_escape_string($c, $title);
$content_esc = mysqli_real_escape_string($c, $content);

$today = date('Y-m-d');

$sql = "
INSERT INTO post (title, content, post_img, status, user_id, cat_id, created_at, updated_at)
VALUES ('$title_esc', '$content_esc', NULL, 1, " . (int)$me['id'] . ", $cat_id, '$today', '$today')
";

$ok = mysqli_query($c, $sql);
if (!$ok) die("DB Error (INSERT post): " . mysqli_error($c));

set_flash('ok', 'Post created');
redirect(url('/index.php'));
exit;

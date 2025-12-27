<?php
require_once __DIR__ . '/../function/func.php';
csrf_check();

$name      = trim($_POST['name'] ?? '');
$email     = strtolower(trim($_POST['email'] ?? ''));
$pass      = $_POST['password'] ?? '';
$last_name = trim($_POST['last_name'] ?? '');

if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 6) {
  set_flash('err', 'بيانات التسجيل غير صحيحة');
  redirect(url('/resources/view/register.php'));
}

$c = db();

// check email
$sql = "SELECT id FROM users WHERE email=? LIMIT 1";
$st = mysqli_prepare($c, $sql);
mysqli_stmt_bind_param($st, "s", $email);
mysqli_stmt_execute($st);
$r = mysqli_stmt_get_result($st);

if (mysqli_fetch_assoc($r)) {
  set_flash('err', 'الإيميل مستخدم قبل كده');
  redirect(url('/resources/view/register.php'));
}

$hash = password_hash($pass, PASSWORD_DEFAULT);

$status     = 1;                 
$is_active  = 1;                 
$deleted_at = 0;                 
$today      = date('Y-m-d');     

$sql2 = "INSERT INTO users
  (name, email, password, last_name, status, created_at, updated_at, is_active, deleted_at)
  VALUES (?,?,?,?,?,?,?,?,?)";

$st2 = mysqli_prepare($c, $sql2);
mysqli_stmt_bind_param(
  $st2,
  "ssssissii",
  $name,
  $email,
  $hash,
  $last_name,
  $status,
  $today,
  $today,
  $is_active,
  $deleted_at
);

mysqli_stmt_execute($st2);

$_SESSION['user'] = [
  'id'    => mysqli_insert_id($c),
  'name'  => $name,
  'email' => $email,
  'role'  => 'user'
];

set_flash('ok', 'Crated Account');
redirect(url('/index.php'));

<?php
require_once __DIR__ . '/../function/func.php';
csrf_check();

$email = strtolower(trim($_POST['email'] ?? ''));
$pass  = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $pass === '') {
  set_flash('err', 'بيانات الدخول غير صحيحة');
  redirect(url('/resources/view/login.php'));
}

$c = db();

$sql = "SELECT id, name, email, password, status FROM users WHERE email=? LIMIT 1";
$st = mysqli_prepare($c, $sql);
mysqli_stmt_bind_param($st, "s", $email);
mysqli_stmt_execute($st);
$r = mysqli_stmt_get_result($st);
$user = mysqli_fetch_assoc($r);

if (!$user || !password_verify($pass, $user['password'])) {
  set_flash('err', 'Email أو Password غلط');
  redirect(url('/resources/view/login.php'));
}

$role = ((int)$user['status'] === 2) ? 'admin' : 'user'; 

$_SESSION['user'] = [
  'id'    => (int)$user['id'],
  'name'  => $user['name'],
  'email' => $user['email'],
  'role'  => $role
];

set_flash('ok', 'تم تسجيل الدخول');

if ($role === 'admin') redirect(url('/admin/dashboard.php'));
redirect(url('/index.php'));

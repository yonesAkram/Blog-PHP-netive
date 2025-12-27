<?php
require_once __DIR__ . '/../function/func.php';

$name  = post_name('name');
$email = post_email('email');
$pass  = $_POST['password'] ?? '';


if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 6) {
  set_flash('err', 'Check Data Please');
  redirect(url('/resources/view/register.php'));
  exit;
}

$c = db();

$email_esc = mysqli_real_escape_string($c, $email);
$sql = "SELECT id FROM users WHERE email='$email_esc' LIMIT 1";
$result = mysqli_query($c, $sql);

if (!$result) {
  die("DB Error (SELECT): " . mysqli_error($c));
}

if (mysqli_num_rows($result) > 0) {
  set_flash('err', 'Please Check Email');
  redirect('/resources/view/register.php');
}
// echo "asd";

// dd("adad");

$hash = password_hash($pass, PASSWORD_DEFAULT);

$today      = date('Y-m-d');
$avatarPath = 'default.png';

$name_esc  = mysqli_real_escape_string($c, $name);
$hash_esc  = mysqli_real_escape_string($c, $hash);
$avatar_esc = mysqli_real_escape_string($c, $avatarPath);
// echo "Created Account";

$sql2 = "
INSERT INTO users
(name, email, password, status, avatar, created_at, updated_at, is_active, deleted_at)
VALUES
('$name_esc', '$email_esc', '$hash_esc', 1, '$avatar_esc', '$today', '$today', 1, NULL)";

$insert = mysqli_query($c, $sql2);
if (!$insert) {
  die("DB Error (INSERT): " . mysqli_error($c));
}

echo "Created Account";
$_SESSION['user'] = [
  'id'    => $user_id,
  'name'  => $name,
  'email' => $email,
];
redirect('');

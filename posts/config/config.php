<?php
// config/config.php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '123456');
define('DB_NAME', 'blug');

define('BASE_URL', '');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
  echo "Check Connect DB" . redirect('/index.php');

}

mysqli_set_charset($conn, 'utf8mb4');

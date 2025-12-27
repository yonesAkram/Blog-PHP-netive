<?php
// config/config.php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'artical');

define('BASE_URL', '');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
  die('DB Connection Failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');

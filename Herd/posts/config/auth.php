<?php
// config/auth.php
require_once __DIR__ . '/config.php';

function is_logged_in() {
  return !empty($_SESSION['user']);
}

function current_user() {
  return $_SESSION['user'] ?? null;
}

function require_login() {
  if (!is_logged_in()) {
    header("Location: " . BASE_URL . "/resources/view/login.php");
    exit;
  }
}

function require_admin() {
  require_login();
  $u = current_user();
  if (($u['role'] ?? 'user') !== 'admin') {
    http_response_code(403);
    exit('Forbidden');
  }
}

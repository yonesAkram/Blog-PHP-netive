<?php
// config/csrf.php
require_once __DIR__ . '/config.php';

function csrf_token() {
  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf_token'];
}

function csrf_input() {
  echo '<input type="hidden" name="csrf_token" value="' .
        htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') .
       '">';
}

function csrf_check() {
  $t = $_POST['csrf_token'] ?? '';
  if (!$t || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $t)) {
    http_response_code(403);
    exit('CSRF token mismatch');
  }
}

<?php
// function/func.php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/csrf.php';

function db() {
  global $conn;
  return $conn;
}


function url($path = '') {
  $path = '/' . ltrim($path, '/');
  return BASE_URL . $path; 
}

function e($str) {
  return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}


//valdite Post Name
function post_name(string $key, string $default = ''): string {
  return trim($_POST[$key] ?? $default);
}

//valdite Post Email
function post_email(string $key = 'email'): string {
  return strtolower(trim($_POST[$key] ?? ''));
}

//valdite Post Email
function post_pass(string $key = 'password'): string {
  return $_POST[$key] ?? '';
} 

function validate_register(string $name, string $email, string $pass): array {
  $errors = [];
//validate Form 
  if ($name === '') $errors[] = 'Check Name';
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Correct Email';
  if (strlen($pass) < 5) $errors[] = 'The password must be 5 characters or more';
  return $errors;
}

//redirect path
function redirect($to = null) {
  if (empty($to)) {
    $to = url('/index.php');
  } elseif (is_string($to) && str_starts_with($to, '/')) {
    $to = url($to);
  }

  header("Location: " . $to);
 exit;
}

// Flash
function set_flash($key, $msg) {
  $_SESSION['flash'][$key] = $msg;
}
function flash($key) {
  if (!empty($_SESSION['flash'][$key])) {
    $m = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);
    return $m;
  }
  return '';
}

// Slug
function slugify($text) {
  $text = trim(mb_strtolower($text));
  $text = preg_replace('/[^\p{L}\p{N}]+/u', '-', $text);
  $text = trim($text, '-');
  if ($text === '') $text = 'post-' . time();
  return mb_substr($text, 0, 200);
}

function slug_exists($slug) {
  $c = db();
  $sql = "SELECT id FROM posts WHERE slug=? LIMIT 1";
  $st = mysqli_prepare($c, $sql);
  mysqli_stmt_bind_param($st, "s", $slug);
  mysqli_stmt_execute($st);
  $res = mysqli_stmt_get_result($st);
  return (bool)mysqli_fetch_assoc($res);
}

function make_unique_slug($title) {
  $base = slugify($title);
  $slug = $base;
  $i = 2;
  while (slug_exists($slug)) {
    $slug = $base . '-' . $i;
    $i++;
  }
  return $slug;
}
function auth_user() {
  return $_SESSION['user'] ?? null;
}

function require_login() {
  if (!auth_user()) {
    set_flash('err', 'Login required');
    redirect(url('/resources/view/login.php'));
    exit;
  }
}

function is_admin($me): bool {
  return ($me['role'] ?? '') === 'admin';
}


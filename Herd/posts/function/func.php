<?php
// function/func.php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/csrf.php';

function db() {
  global $conn;
  return $conn;
}

function e($str) {
  return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

function url($path = '') {
  $path = '/' . ltrim($path, '/');
  return BASE_URL . $path; 
}

function redirect($to) {
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

<?php
require_once __DIR__ . '/../function/func.php';

$_SESSION = [];
session_destroy();

redirect(url('/index.php'));

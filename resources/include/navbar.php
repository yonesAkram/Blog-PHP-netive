<?php // resources/include/navbar.php
$u = current_user();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="<?= e(url('/index.php')) ?>">POSTS</a>

    <div class="d-flex gap-2 align-items-center">
      <?php if ($u): ?>
        <?php if (($u['role'] ?? '') === 'admin'): ?>
          <a class="btn btn-outline-warning btn-sm" href="<?= e(url('/admin/dashboard.php')) ?>">Dashboard</a>
        <?php endif; ?>

        <span class="text-white small">أهلاً، <?= e($u['name']) ?></span>
        <a class="btn btn-outline-light btn-sm" href="<?= e(url('/actions/logout.php')) ?>">Logout</a>
      <?php else: ?>
        <a class="btn btn-outline-light btn-sm" href="<?= e(url('/resources/view/login.php')) ?>">Login</a>
        <a class="btn btn-warning btn-sm" href="<?= e(url('/resources/view/register.php')) ?>">Register</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

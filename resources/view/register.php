<?php
require_once __DIR__ . '/../../function/func.php';

$pageTitle = 'Register';

include __DIR__ . '/../include/header.php';
include __DIR__ . '/../include/navbar.php';
?>
<div class="container py-5" style="max-width:520px">

  <?php if ($m = flash('err')): ?>
    <div class="alert alert-danger"><?= e($m) ?></div>
  <?php endif; ?>

  <?php if ($m = flash('ok')): ?>
    <div class="alert alert-success"><?= e($m) ?></div>
  <?php endif; ?>

  <h1 class="h4 mb-3">إنشاء حساب</h1>

  <form class="card p-3 needs-validation" novalidate method="post" action="<?= e(url('/actions/auth_register.php')) ?>">
    <?php csrf_input(); ?>

    <label class="form-label">First Name</label>
    <input
      class="form-control"
      name="name"
      required
      maxlength="80"
      value="<?= e($_POST['name'] ?? '') ?>"
    >

    <label class="form-label mt-2">Last Name</label>
    <input
      class="form-control"
      name="last_name"
      required
      maxlength="80"
      value="<?= e($_POST['last_name'] ?? '') ?>"
    >

    <label class="form-label mt-2">Email</label>
    <input
      class="form-control"
      name="email"
      type="email"
      required
      maxlength="190"
      value="<?= e($_POST['email'] ?? '') ?>"
    >

    <label class="form-label mt-2">Password</label>
    <input class="form-control" name="password" type="password" required minlength="6">

    <button class="btn btn-warning mt-3">Create account</button>
  </form>
</div>
<?php include __DIR__ . '/../include/footer.php'; ?>

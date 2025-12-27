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

  <h1 class="h4 mb-3" style="text-align: left;">Register</h1>

  <form class="card p-3 needs-validation" novalidate method="post"  enctype="multipart/form-data" action="<?= e(url('/actions/auth_register.php')) ?>">
    <?php csrf_input(); ?>

    <label class="form-label" style="text-align: left;">First Name</label>
    <input
      class="form-control"
      style="text-align: left;"
      name="name"
      required
      maxlength="180"
      value="<?= e($_POST['name'] ?? '') ?>"
    >
    <label class="form-label mt-2" style="text-align: left;">Email</label>
    <input
      class="form-control"
      style="text-align: left;"
      name="email"
      type="email"
      required
      maxlength="190"
      value="<?= e($_POST['email'] ?? '') ?>"
    >

    <label class="form-label mt-2" style="text-align: left;">Password</label>
    <input class="form-control" style="text-align: left;" name="password" type="password" required minlength="6">    
  
    <label class="form-label mt-2" style="text-align: left;">Avtar</label>
    <input class="form-control" name="profile_img" type="file" accept="image/*">
    <div class="form-text text-start">JPG/PNG/WEBP - Max 2MB</div>
      
    <button class="btn btn-warning mt-3">Create account</button>
  </form>
</div>
<?php include __DIR__ . '/../include/footer.php'; ?>

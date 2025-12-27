<?php
require_once __DIR__ . '/../../function/func.php';

$pageTitle = 'Login';

include __DIR__ . '/../include/header.php';
include __DIR__ . '/../include/navbar.php';
?>
<div class="container py-5" style="max-width:520px">

  <!-- <?php //if ($m = flash('err')): ?>
    <div class="alert alert-danger"><?= e($m) ?></div>
  <?php //endif; ?> -->

  <h1 class="h4 mb-3" style="text-align: left;">Login</h1>

  <form class="card p-3 needs-validation" novalidate method="post" action="<?= e(url('/actions/auth_login.php')) ?>">
    <?php csrf_input(); ?>

    <label class="form-label" style="text-align: left;">Email</label>
             <input class="form-control"  name="email" type="email" required>

    <label class="form-label mt-2" style="text-align: left;">Password</label>
    <input class="form-control" name="password" type="password" required minlength="6">
        <div class="d-flex justify-content-between align-items-center mt-2">
    <a href="#" class="small text-decoration-none">Forgot Password?</a>
             </div>
    <button class="btn btn-dark mt-3" >Login</button>
  </form>
  
</div>
<?php include __DIR__ . '/../include/footer.php'; ?>

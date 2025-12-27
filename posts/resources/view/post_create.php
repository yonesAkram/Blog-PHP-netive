<?php
require_once __DIR__ . '/../../function/func.php';
require_login();

$pageTitle = 'Create Post';
$c = db();

// لو عندك جدول categories
$cats = [];
$r = mysqli_query($c, "SELECT id, name FROM categories ORDER BY id DESC");
if ($r) while ($row = mysqli_fetch_assoc($r)) $cats[] = $row;

include __DIR__ . '/../include/header.php';
include __DIR__ . '/../include/navbar.php';
?>
<div class="container py-4">
  <h1 class="h4 mb-3">إضافة بوست</h1>

  <?php if ($m = flash('err')): ?>
    <div class="alert alert-danger"><?= e($m) ?></div>
  <?php endif; ?>

  <form method="post" action="<?= e(url('/actions/post_store.php')) ?>">
    <?php csrf_input(); ?>

    <div class="mb-3">
      <label class="form-label">Title</label>
      <input name="title" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Content</label>
      <textarea name="content" class="form-control" rows="6" required></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Category</label>
      <select name="cat_id" class="form-select" required>
        <?php foreach ($cats as $cat): ?>
          <option value="<?= (int)$cat['id'] ?>"><?= e($cat['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <button class="btn btn-warning">Publish</button>
  </form>
</div>
<?php include __DIR__ . '/../include/footer.php'; ?>

<?php
require_once __DIR__ . '/../../function/func.php';
require_login();

$c = db();
$me = auth_user();

$id = (int)($_GET['id'] ?? 0);
$res = mysqli_query($c, "SELECT * FROM post WHERE id=$id LIMIT 1");
$post = $res ? mysqli_fetch_assoc($res) : null;

if (!$post) {
  set_flash('err', 'Post not found');
  redirect(url('/index.php'));
  exit;
}

if (!can_manage_post($me, $post)) {
  set_flash('err', 'Not allowed');
  redirect(url('/index.php'));
  exit;
}

$cats = [];
$r = mysqli_query($c, "SELECT id, name FROM categories ORDER BY id DESC");
if ($r) while ($row = mysqli_fetch_assoc($r)) $cats[] = $row;

include __DIR__ . '/../include/header.php';
include __DIR__ . '/../include/navbar.php';
?>
<div class="container py-4">
  <h1 class="h4 mb-3">تعديل بوست</h1>

  <?php if ($m = flash('err')): ?>
    <div class="alert alert-danger"><?= e($m) ?></div>
  <?php endif; ?>

  <form method="post" action="<?= e(url('/actions/post_update.php')) ?>">
    <?php csrf_input(); ?>
    <input type="hidden" name="id" value="<?= (int)$post['id'] ?>">

    <div class="mb-3">
      <label class="form-label">Title</label>
      <input name="title" class="form-control" value="<?= e($post['title']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Content</label>
      <textarea name="content" class="form-control" rows="6" required><?= e($post['content']) ?></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Category</label>
      <select name="cat_id" class="form-select" required>
        <?php foreach ($cats as $cat): ?>
          <option value="<?= (int)$cat['id'] ?>" <?= ((int)$post['cat_id'] === (int)$cat['id']) ? 'selected' : '' ?>>
            <?= e($cat['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <button class="btn btn-dark">Save</button>
  </form>
</div>
<?php include __DIR__ . '/../include/footer.php'; ?>

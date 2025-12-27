<?php
require_once __DIR__ . '/../function/func.php';
require_admin();

$c = db();
$u = current_user();

// Create post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create_post') {
  csrf_check();

  $title = trim($_POST['title'] ?? '');
  $body  = trim($_POST['body'] ?? '');

  if ($title === '' || $body === '') {
    set_flash('err', 'العنوان والمحتوى مطلوبين');
    redirect(url('/admin/dashboard.php'));
  }

  $slug = make_unique_slug($title);
  $uid  = (int)$u['id'];

  $sql = "INSERT INTO posts (user_id,title,slug,body) VALUES (?,?,?,?)";
  $st = mysqli_prepare($c, $sql);
  mysqli_stmt_bind_param($st, "isss", $uid, $title, $slug, $body);
  mysqli_stmt_execute($st);

  set_flash('ok', 'تم إضافة المقال');
  redirect(url('/admin/dashboard.php'));
}

// Delete post
if (!empty($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  if ($id > 0) {
    $sql = "DELETE FROM posts WHERE id=?";
    $st = mysqli_prepare($c, $sql);
    mysqli_stmt_bind_param($st, "i", $id);
    mysqli_stmt_execute($st);
    set_flash('ok', 'تم حذف المقال');
  }
  redirect(url('/admin/dashboard.php'));
}

// Stats
$usersCount = (int)mysqli_fetch_row(mysqli_query($c, "SELECT COUNT(*) FROM users"))[0];
$postsCount = (int)mysqli_fetch_row(mysqli_query($c, "SELECT COUNT(*) FROM posts"))[0];
$commCount  = (int)mysqli_fetch_row(mysqli_query($c, "SELECT COUNT(*) FROM comments"))[0];

// Latest posts
$sql = "SELECT p.id,p.title,p.slug,p.created_at,u.name AS author
        FROM posts p JOIN users u ON u.id=p.user_id
        ORDER BY p.created_at DESC LIMIT 15";
$res = mysqli_query($c, $sql);
$posts = [];
while ($row = mysqli_fetch_assoc($res)) $posts[] = $row;

$pageTitle = 'Dashboard';
include __DIR__ . '/../resources/include/header.php';
include __DIR__ . '/../resources/include/navbar.php';
?>
<div class="container py-4">

  <?php if ($m = flash('ok')): ?>
    <div class="alert alert-success"><?= e($m) ?></div>
  <?php endif; ?>
  <?php if ($m = flash('err')): ?>
    <div class="alert alert-danger"><?= e($m) ?></div>
  <?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h5 m-0">Admin Dashboard</h1>
    <span class="badge text-bg-warning">Admin</span>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-md-4"><div class="card shadow-sm"><div class="card-body">
      <div class="text-muted small">Users</div><div class="h3"><?= $usersCount ?></div>
    </div></div></div>
    <div class="col-md-4"><div class="card shadow-sm"><div class="card-body">
      <div class="text-muted small">Posts</div><div class="h3"><?= $postsCount ?></div>
    </div></div></div>
    <div class="col-md-4"><div class="card shadow-sm"><div class="card-body">
      <div class="text-muted small">Comments</div><div class="h3"><?= $commCount ?></div>
    </div></div></div>
  </div>

  <div class="card shadow-sm mb-3">
    <div class="card-header bg-white"><strong>إضافة مقال جديد</strong></div>
    <div class="card-body">
      <form class="needs-validation" novalidate method="post">
        <input type="hidden" name="action" value="create_post">
        <?php csrf_input(); ?>

        <label class="form-label">العنوان</label>
        <input class="form-control" name="title" required maxlength="200">
        <div class="invalid-feedback">اكتب عنوان.</div>

        <label class="form-label mt-2">المحتوى</label>
        <textarea class="form-control" name="body" rows="6" required></textarea>
        <div class="invalid-feedback">اكتب المحتوى.</div>

        <button class="btn btn-dark btn-sm mt-3">نشر</button>
      </form>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header bg-white"><strong>آخر المقالات</strong></div>
    <div class="table-responsive">
      <table class="table table-striped mb-0 align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Author</th>
            <th>Date</th>
            <th style="width:220px">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!$posts): ?>
            <tr><td colspan="5" class="text-center text-muted p-4">No posts yet</td></tr>
          <?php endif; ?>

          <?php foreach ($posts as $p): ?>
            <tr>
              <td><?= (int)$p['id'] ?></td>
              <td><?= e($p['title']) ?></td>
              <td><?= e($p['author']) ?></td>
              <td class="text-muted small"><?= e($p['created_at']) ?></td>
              <td>
                <a class="btn btn-outline-dark btn-sm"
                   href="<?= e(url('/resources/view/post.php?slug=' . urlencode($p['slug']))) ?>">View</a>

                <a class="btn btn-outline-danger btn-sm"
                   onclick="return confirm('متأكد الحذف؟')"
                   href="<?= e(url('/admin/dashboard.php?delete=' . (int)$p['id'])) ?>">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
<?php include __DIR__ . '/../resources/include/footer.php'; ?>

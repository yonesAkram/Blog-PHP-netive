<?php
require_once __DIR__ . '/function/func.php';

$pageTitle = 'Home';

$c = db();

$sql = "SELECT p.*, u.name AS author
        FROM post p
        JOIN users u ON u.id = p.user_id
        ORDER BY p.id DESC
        LIMIT 30";

$res = mysqli_query($c, $sql);

$posts = [];
while ($row = mysqli_fetch_assoc($res)) $posts[] = $row;

// المستخدم الحالي (لو مسجل دخول)
$me = $_SESSION['user'] ?? null;

// دالة صلاحيات: يقدر يعدل/يحذف لو admin أو صاحب البوست
function can_manage_post($me, $post): bool {
  if (!$me) return false;
  if (($me['role'] ?? '') === 'admin') return true;
  return (int)$post['user_id'] === (int)$me['id'];
}

include __DIR__ . '/resources/include/header.php';
include __DIR__ . '/resources/include/navbar.php';
?>
<div class="container py-4">

  <?php if ($m = flash('ok')): ?>
    <div class="alert alert-success"><?= e($m) ?></div>
  <?php endif; ?>
  <?php if ($m = flash('err')): ?>
    <div class="alert alert-danger"><?= e($m) ?></div>
  <?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 m-0">آخر المقالات</h1>

    <?php if ($me): ?>
      <a class="btn btn-warning btn-sm" href="<?= e(url('/resources/view/post_create.php')) ?>">
        + إضافة بوست
      </a>
    <?php else: ?>
      <a class="btn btn-outline-dark btn-sm" href="<?= e(url('/resources/view/login.php')) ?>">
        سجل دخول عشان تضيف بوست
      </a>
    <?php endif; ?>
  </div>

  <?php //if (!$posts): ?>
    <div class="alert alert-info">مفيش مقالات لسه.</div>
  <?php //endif; ?>

  <div class="row g-3">
    <?php foreach ($posts as $p): ?>
      <div class="col-md-6">
        <div class="card shadow-sm h-100">
          <div class="card-body">

            <div class="d-flex justify-content-between gap-2">
              <h2 class="h5 mb-1"><?= e($p['title']) ?></h2>

              <?php if (can_manage_post($me, $p)): ?>
                <div class="btn-group btn-group-sm">
                  <a class="btn btn-outline-secondary"
                     href="<?= e(url('/resources/view/post_edit.php?id=' . (int)$p['id'])) ?>">
                    تعديل
                  </a>

                  <form method="post" action="<?= e(url('/actions/post_delete.php')) ?>" onsubmit="return confirm('متأكد تحذف البوست؟')">
                    <?php csrf_input(); ?>
                    <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                    <button class="btn btn-outline-danger">حذف</button>
                  </form>
                </div>
              <?php endif; ?>
            </div>

            <div class="text-muted small mb-2">
              بواسطة <?= e($p['author']) ?>
              • <?= e(date('Y-m-d', strtotime($p['created_at'] ?? 'now'))) ?>
            </div>

            <p class="mb-3">
              <?= e(mb_substr(strip_tags($p['content']), 0, 240)) ?><?= (mb_strlen(strip_tags($p['content'])) > 240 ? '...' : '') ?>
            </p>

            <a class="btn btn-dark btn-sm">
               href="<?= e(url('/resources/view/post.php?id=' . (int)$p['id'])) ?>" 
                Read Commment
            </a>

          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

</div>
<?php include __DIR__ . '/resources/include/footer.php'; ?>

<?php
require_once __DIR__ . '/../../function/func.php';

$slug = $_GET['slug'] ?? '';
if ($slug === '') { http_response_code(400); exit('Bad Request'); }

$c = db();

// Post
$sql = "SELECT p.*, u.name AS author
        FROM posts p JOIN users u ON u.id=p.user_id
        WHERE p.slug=? LIMIT 1";
$st = mysqli_prepare($c, $sql);
mysqli_stmt_bind_param($st, "s", $slug);
mysqli_stmt_execute($st);
$r = mysqli_stmt_get_result($st);
$post = mysqli_fetch_assoc($r);

if (!$post) { http_response_code(404); exit('Post not found'); }

$pageTitle = $post['title'];

// Comments
$sql2 = "SELECT c.*, COALESCE(u.name, c.author_name, 'Guest') AS display_name
         FROM comments c
         LEFT JOIN users u ON u.id = c.user_id
         WHERE c.post_id=?
         ORDER BY c.created_at DESC";
$st2 = mysqli_prepare($c, $sql2);
$post_id = (int)$post['id'];
mysqli_stmt_bind_param($st2, "i", $post_id);
mysqli_stmt_execute($st2);
$r2 = mysqli_stmt_get_result($st2);

$comments = [];
while ($row = mysqli_fetch_assoc($r2)) $comments[] = $row;

include __DIR__ . '/../include/header.php';
include __DIR__ . '/../include/navbar.php';
?>
<div class="container py-4">

  <?php if ($m = flash('ok')): ?>
    <div class="alert alert-success"><?= e($m) ?></div>
  <?php endif; ?>
  <?php if ($m = flash('err')): ?>
    <div class="alert alert-danger"><?= e($m) ?></div>
  <?php endif; ?>

  <article class="card shadow-sm">
    <div class="card-body">
      <h1 class="h4"><?= e($post['title']) ?></h1>
      <div class="text-muted small mb-3">
        بواسطة <?= e($post['author']) ?> • <?= e($post['created_at']) ?>
      </div>
      <div class="content"><?= nl2br(e($post['body'])) ?></div>
    </div>
  </article>

  <section class="mt-4">
    <h2 class="h6 mb-2">التعليقات (<?= count($comments) ?>)</h2>

    <?php if (!$comments): ?>
      <div class="alert alert-light border">مفيش تعليقات لسه.</div>
    <?php endif; ?>

    <div class="vstack gap-2">
      <?php foreach ($comments as $cmt): ?>
        <div class="card">
          <div class="card-body py-3">
            <div class="small text-muted mb-2">
              <?= e($cmt['display_name']) ?> • <?= e($cmt['created_at']) ?>
            </div>
            <div><?= nl2br(e($cmt['body'])) ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="mt-4">
    <h3 class="h6">أضف تعليق</h3>

    <form class="card p-3 needs-validation" novalidate method="post" action="<?= e(url('/actions/comment_create.php')) ?>">
      <?php csrf_input(); ?>
      <input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>">
      <input type="hidden" name="slug" value="<?= e($post['slug']) ?>">

      <?php if (!is_logged_in()): ?>
        <div class="row g-2">
          <div class="col-md-6">
            <label class="form-label">الاسم</label>
            <input class="form-control" name="author_name" required maxlength="80">
            <div class="invalid-feedback">اكتب اسمك.</div>
          </div>
          <div class="col-md-6">
            <label class="form-label">الإيميل</label>
            <input class="form-control" name="author_email" type="email" required maxlength="190">
            <div class="invalid-feedback">اكتب إيميل صحيح.</div>
          </div>
        </div>
      <?php endif; ?>

      <div class="mt-2">
        <label class="form-label">التعليق</label>
        <textarea class="form-control" name="body" rows="3" required></textarea>
        <div class="invalid-feedback">اكتب التعليق.</div>
      </div>

      <button class="btn btn-dark btn-sm mt-3">إرسال</button>
    </form>
  </section>

</div>
<?php include __DIR__ . '/../include/footer.php'; ?>

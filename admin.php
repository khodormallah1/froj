<?php
require 'config.php';
if (empty($_SESSION['admin'])) { header('Location: login.php'); exit; }
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));

// رفع صورة وحفظها بمجلد image وإرجاع مسارها
function save_upload($field) {
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) return '';
    $f = $_FILES[$field];
    if ($f['error'] !== UPLOAD_ERR_OK) { $_SESSION['msg'] = 'فشل رفع الصورة (ممكن حجمها كبير)'; return ''; }
    if ($f['size'] > 5 * 1024 * 1024) { $_SESSION['msg'] = 'الصورة أكبر من 5 ميغا'; return ''; }
    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg','jpeg','png','webp','gif','jfif'], true) || !@getimagesize($f['tmp_name'])) {
        $_SESSION['msg'] = 'الملف لازم يكون صورة (jpg, png, webp, gif)'; return '';
    }
    $dir = __DIR__ . '/image';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $name = bin2hex(random_bytes(6)) . '.' . $ext;
    if (!move_uploaded_file($f['tmp_name'], "$dir/$name")) { $_SESSION['msg'] = 'تعذّر حفظ الصورة بالمجلد'; return ''; }
    return 'image/' . $name;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) die('Invalid token');
    $a     = $_POST['action'] ?? '';
    $id    = (int)($_POST['id'] ?? 0);
    $cat   = (int)($_POST['category_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $name  = trim($_POST['name'] ?? '');
    $en    = trim($_POST['name_en'] ?? '');
    $price = (float)($_POST['price'] ?? 0);

    $up = in_array($a, ['add_cat','edit_cat'], true) ? save_upload('image_file') : '';
    if ($up !== '') $image = $up;

    if ($a === 'add_cat' && $title !== '') {
        $st = $con->prepare("INSERT INTO categories (title,image) VALUES (?,?)");
        $st->bind_param('ss', $title, $image); $st->execute();
        $_SESSION['msg'] = $_SESSION['msg'] ?? 'تمت إضافة القسم';
    } elseif ($a === 'edit_cat' && $title !== '') {
        $st = $con->prepare("UPDATE categories SET title=?, image=? WHERE id=?");
        $st->bind_param('ssi', $title, $image, $id); $st->execute();
        $_SESSION['msg'] = $_SESSION['msg'] ?? 'تم حفظ القسم';
    } elseif ($a === 'del_cat') {
        $st = $con->prepare("DELETE FROM categories WHERE id=?");
        $st->bind_param('i', $id); $st->execute();
        $_SESSION['msg'] = 'تم حذف القسم مع كل أصنافه';
    } elseif ($a === 'add_item' && $name !== '') {
        $st = $con->prepare("INSERT INTO items (category_id,name,name_en,price) VALUES (?,?,?,?)");
        $st->bind_param('issd', $cat, $name, $en, $price); $st->execute();
        $_SESSION['msg'] = 'تمت إضافة الصنف';
    } elseif ($a === 'edit_item' && $name !== '') {
        $st = $con->prepare("UPDATE items SET name=?, name_en=?, price=? WHERE id=?");
        $st->bind_param('ssdi', $name, $en, $price, $id); $st->execute();
        $_SESSION['msg'] = 'تم حفظ الصنف';
    } elseif ($a === 'del_item') {
        $st = $con->prepare("DELETE FROM items WHERE id=?");
        $st->bind_param('i', $id); $st->execute();
        $_SESSION['msg'] = 'تم حذف الصنف';
    }
    header('Location: admin.php'); exit;
}

$msg = $_SESSION['msg'] ?? ''; unset($_SESSION['msg']);
$cats = $con->query("SELECT * FROM categories ORDER BY id")->fetch_all(MYSQLI_ASSOC);
$items = [];
foreach ($con->query("SELECT * FROM items ORDER BY id")->fetch_all(MYSQLI_ASSOC) as $it) {
    $items[$it['category_id']][] = $it;
}
$t = e($_SESSION['csrf']);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>لوحة الإدارة | فروج وتوم</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;800&display=swap" rel="stylesheet">
<style>
:root{--bg:#111;--card:#1b1b1b;--line:#2c2c2c;--text:#f5f1ea;--muted:#a8a29a;--accent:#f5a623;--red:#ff4757}
*{box-sizing:border-box;margin:0;padding:0}
body{background:var(--bg);color:var(--text);font-family:'Cairo',Tahoma,sans-serif;line-height:1.6;padding-bottom:40px}
.wrap{max-width:820px;margin:0 auto;padding:0 16px}
.top{display:flex;justify-content:space-between;align-items:center;padding:20px 0}
.top h1{color:var(--accent);font-size:1.6rem}
a.btn,button.btn{display:inline-block;background:var(--card);color:var(--text);border:1px solid var(--line);border-radius:999px;padding:8px 16px;font:600 .9rem 'Cairo',sans-serif;text-decoration:none;cursor:pointer}
button.save{background:var(--accent);color:#111;border-color:var(--accent)}
button.del{background:var(--red);color:#fff;border-color:var(--red)}
.msg{background:#143d22;color:#5be08b;padding:10px 14px;border-radius:12px;margin-bottom:16px}
.cat{background:var(--card);border:1px solid var(--line);border-radius:18px;padding:16px;margin-bottom:20px}
.row{display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin-bottom:8px}
input{background:var(--bg);border:1px solid var(--line);color:var(--text);border-radius:10px;padding:8px 10px;font:inherit;font-size:.9rem}
.grow{flex:1;min-width:140px}
.p{width:90px}
.cat-head input{font-weight:800}
.sub{color:var(--muted);font-size:.85rem;margin:10px 0 6px}
.new{border-top:1px dashed var(--line);padding-top:10px;margin-top:10px}
.newcat{border:2px dashed var(--accent)}
.thumb{width:48px;height:48px;object-fit:cover;border-radius:8px;border:1px solid var(--line)}
</style>
</head>
<body>
<div class="wrap">
  <div class="top">
    <h1>لوحة الإدارة</h1>
    <div><a class="btn" href="index.php" target="_blank">صفحة الزبون</a> <a class="btn" href="logout.php">خروج</a></div>
  </div>
  <?php if ($msg): ?><div class="msg"><?= e($msg) ?></div><?php endif; ?>

  <!-- إضافة قسم -->
  <form class="cat newcat" method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= $t ?>">
    <input type="hidden" name="action" value="add_cat">
    <b>+ إضافة قسم جديد (Category)</b>
    <div class="row" style="margin-top:8px">
      <input class="grow" name="title" placeholder="اسم القسم" required>
      <label class="grow" style="color:var(--muted);font-size:.9rem">صورة القسم:
        <input type="file" name="image_file" accept="image/*" style="width:100%;margin-top:4px">
      </label>
      <input type="hidden" name="image" value="">
      <button class="btn save" type="submit">إضافة</button>
    </div>
  </form>

  <?php foreach ($cats as $c): ?>
  <div class="cat">
    <!-- تعديل / حذف القسم -->
    <form method="post" class="row cat-head" enctype="multipart/form-data">
      <input type="hidden" name="csrf" value="<?= $t ?>">
      <input type="hidden" name="id" value="<?= $c['id'] ?>">
      <input class="grow" name="title" value="<?= e($c['title']) ?>" required>
      <?php if ($c['image']): ?><img class="thumb" src="<?= e(str_replace(' ', '%20', $c['image'])) ?>" alt=""><?php endif; ?>
      <input type="hidden" name="image" value="<?= e($c['image']) ?>">
      <input class="grow" type="file" name="image_file" accept="image/*" title="اختار صورة جديدة لتغيير الصورة">
      <button class="btn save" name="action" value="edit_cat">حفظ</button>
      <button class="btn del" name="action" value="del_cat" onclick="return confirm('بتحذف القسم وكل أصنافه. متأكد؟')">حذف القسم</button>
    </form>

    <div class="sub">الأصناف والأسعار (Price)</div>
    <?php foreach ($items[$c['id']] ?? [] as $it): ?>
    <form method="post" class="row">
      <input type="hidden" name="csrf" value="<?= $t ?>">
      <input type="hidden" name="id" value="<?= $it['id'] ?>">
      <input class="grow" name="name" value="<?= e($it['name']) ?>" required>
      <input class="grow" name="name_en" value="<?= e($it['name_en']) ?>" placeholder="English (اختياري)" dir="ltr">
      <input class="p" name="price" type="number" step="0.01" min="0" value="<?= e($it['price']) ?>" required dir="ltr">
      <button class="btn save" name="action" value="edit_item">حفظ</button>
      <button class="btn del" name="action" value="del_item" onclick="return confirm('حذف هالصنف؟')">حذف</button>
    </form>
    <?php endforeach; ?>

    <!-- إضافة صنف -->
    <form method="post" class="row new">
      <input type="hidden" name="csrf" value="<?= $t ?>">
      <input type="hidden" name="action" value="add_item">
      <input type="hidden" name="category_id" value="<?= $c['id'] ?>">
      <input class="grow" name="name" placeholder="+ صنف جديد" required>
      <input class="grow" name="name_en" placeholder="English (اختياري)" dir="ltr">
      <input class="p" name="price" type="number" step="0.01" min="0" placeholder="السعر" required dir="ltr">
      <button class="btn save" type="submit">إضافة</button>
    </form>
  </div>
  <?php endforeach; ?>
</div>
</body>
</html>

<?php
require 'config.php';
if (!empty($_SESSION['admin'])) { header('Location: admin.php'); exit; }
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';
    if (hash_equals(ADMIN_USER, $u) && hash_equals(ADMIN_PASS, $p)) {
        session_regenerate_id(true);
        $_SESSION['admin'] = true;
        header('Location: admin.php'); exit;
    }
    $err = 'اسم المستخدم أو كلمة السر غلط';
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>دخول الإدارة | فروج وتوم</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;800&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{background:#111;color:#f5f1ea;font-family:'Cairo',Tahoma,sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:16px}
.box{background:#1b1b1b;border:1px solid #2c2c2c;border-radius:18px;padding:28px;width:100%;max-width:380px}
h1{color:#f5a623;font-size:1.8rem;text-align:center;margin-bottom:20px}
input{width:100%;background:#111;border:1px solid #2c2c2c;color:#f5f1ea;border-radius:12px;padding:12px;font:inherit;margin-bottom:12px}
button{width:100%;background:#f5a623;color:#111;border:0;border-radius:999px;padding:12px;font:800 1rem 'Cairo',sans-serif;cursor:pointer}
.err{background:#40161a;color:#ff7b86;padding:8px 12px;border-radius:10px;margin-bottom:12px;text-align:center}
</style>
</head>
<body>
<form class="box" method="post">
  <h1>دخول الإدارة</h1>
  <?php if ($err): ?><div class="err"><?= e($err) ?></div><?php endif; ?>
  <input name="username" placeholder="اسم المستخدم" required autofocus>
  <input name="password" type="password" placeholder="كلمة السر" required>
  <button type="submit">دخول</button>
</form>
</body>
</html>

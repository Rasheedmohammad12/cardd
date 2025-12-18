<?php
require "db.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'shop'){
    die("غير مصرح");
}

$uid = (int)$_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if(!$user) die("مستخدم غير موجود");

// الاشتراك
if(date("Y-m-d") > $user['expire_date']){
    die("❌ انتهت مدة الاشتراك");
}

// إجمالي الأرقام
$stmt = $conn->prepare("SELECT COUNT(*) c FROM numbers WHERE user_id=?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$total = (int)$stmt->get_result()->fetch_assoc()['c'];

// المرسل اليوم
$stmt = $conn->prepare("SELECT COUNT(*) c FROM numbers WHERE user_id=? AND sent_date=CURDATE()");
$stmt->bind_param("i", $uid);
$stmt->execute();
$sent_today = (int)$stmt->get_result()->fetch_assoc()['c'];

$remaining = (int)$user['daily_limit'] - $sent_today;
if($remaining < 0) $remaining = 0;
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>لوحة المحل</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

  <div class="card">
    <h2>أهلاً <?= htmlspecialchars($user['shop_name']) ?></h2>
    <div class="stats">
      <div class="stat">📞 إجمالي الأرقام: <b><?= $total ?></b></div>
      <div class="stat">📤 تم الإرسال اليوم: <b><?= $sent_today ?></b></div>
      <div class="stat">⏳ المتبقي اليوم: <b><?= $remaining ?></b></div>
      <div class="stat">🗓️ ينتهي الاشتراك: <b><?= htmlspecialchars($user['expire_date']) ?></b></div>
    </div>
  </div>

  <div class="card">
    <a class="link" href="upload_numbers.php">➕ رفع أرقام</a>

    <a class="link" href="export_today.php">⬇️ تحضير أرقام اليوم (CSV)</a>

    <div class="divider"></div>

    <h3 style="margin-top:0">🚀 طرق الإرسال</h3>
    <a class="link" href="free_sender.php">🆓 إرسال مجاني (WhatsApp Web)</a>

    <a class="link danger" href="logout.php">🚪 خروج</a>
  </div>

</div>
</body>
</html>

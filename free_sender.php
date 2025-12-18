<?php
require "db.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'shop'){
    die("غير مصرح");
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>الإرسال المجاني</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

  <div class="card">
    <h2>🆓 الإرسال المجاني عبر WhatsApp Web</h2>
    <p>
      هذا الخيار مجاني ولا يحتاج API مدفوع.<br>
      الإرسال يتم عبر واتساب ويب باستخدام جهازك.
    </p>
  </div>

  <div class="card">
    <h3>📘 الخطوات</h3>
    <ol>
      <li>اضغط على <b>تحضير أرقام اليوم (CSV)</b></li>
      <li>افتح <b>WA Web Sender</b></li>
      <li>اربط جهازك بـ QR Code</li>
      <li>استخدم أداة إرسال جماعي أو WA Web</li>
      <li>ارفع ملف <b>today.csv</b> واضغط "إرسال"</li>
    </ol>
  </div>

  <div class="card">
    <h3>🔗 روابط مفيدة</h3>
    <a class="link" target="_blank" href="export_today.php">⬇️ تحضير الأرقام اليوم (CSV)</a>
    <a class="link" target="_blank" href="https://wasender.wadesk.io/ext-bulk-sender-downloader-guide?utm_source=bulkSender_extension">
      🌐 فتح WA Web Sender
    </a>
    <p style="color:#d9534f">
      ⚠️ لا تتجاوز الحد اليومي لتجنب الحظر، ويفضل إرسال تدريجي.
    </p>
  </div>

  <a class="link" href="dashboard.php">⬅️ رجوع للوحة التحكم</a>

</div>

</body>
</html>

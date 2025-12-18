<?php
require "db.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'shop'){
    die("غير مصرح");
}

$uid = (int)$_SESSION['user_id'];

$user = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT expire_date FROM users WHERE id=$uid"));

if(date("Y-m-d") > $user['expire_date']){
    die("❌ انتهت مدة الاشتراك");
}

$msg = "";

if(isset($_POST['upload'])){
    $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
    if($ext !== 'csv') die("❌ الرجاء رفع ملف CSV فقط");

    $handle = fopen($_FILES['file']['tmp_name'], "r");
    if(!$handle) die("❌ لا يمكن قراءة الملف");

    // تجاهل الهيدر
    fgetcsv($handle, 2000, ",");

    $stmt = $conn->prepare("INSERT IGNORE INTO numbers (user_id, phone, name) VALUES (?, ?, ?)");

    $added=0; $skipped=0;

    while(($data = fgetcsv($handle, 2000, ",")) !== false){
        $phone = preg_replace('/[^0-9]/', '', $data[0] ?? '');
        $name  = trim($data[1] ?? '');

        if(strlen($phone) < 9){ $skipped++; continue; }

        $stmt->bind_param("iss", $uid, $phone, $name);
        $stmt->execute();

        if($stmt->affected_rows > 0) $added++;
        else $skipped++;
    }

    fclose($handle);
    $stmt->close();

    $msg = "✅ تم الرفع | أضيف: $added | تم تجاهل: $skipped";
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>رفع الأرقام</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="card">
  <h2>رفع ملف الأرقام (CSV)</h2>

  <p>صيغة الملف:</p>
  <pre>phone,name
96279999999,أحمد
96278888888,سارة</pre>

  <form method="post" enctype="multipart/form-data">
    <input type="file" name="file" accept=".csv" required>
    <button class="btn" name="upload">رفع</button>
  </form>

  <?php if($msg): ?><p class="ok"><?= $msg ?></p><?php endif; ?>

  <a class="link" href="dashboard.php">⬅️ رجوع</a>
</div>

</body>
</html>

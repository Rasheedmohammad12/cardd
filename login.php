<?php
require "db.php";

$error = "";

if(isset($_POST['login'])){
    $username = trim($_POST['username']);
    $password = md5($_POST['password']);

    $stmt = $conn->prepare("SELECT id, role FROM users WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows === 1){
        $user = $res->fetch_assoc();
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['role']    = $user['role'];

        if($user['role'] === 'admin'){
            header("Location: admin_dashboard.php");
        } else {
            header("Location: dashboard.php");
        }
        exit;
    } else {
        $error = "❌ اسم المستخدم أو كلمة المرور غير صحيحة";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>تسجيل الدخول</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="card">
  <h2>تسجيل الدخول</h2>

  <form method="post">
    <label>اسم المستخدم</label>
    <input type="text" name="username" required>

    <label>كلمة المرور</label>
    <input type="password" name="password" required>

    <button class="btn" name="login">دخول</button>
  </form>

  <?php if($error): ?>
    <p class="error"><?= $error ?></p>
  <?php endif; ?>
</div>
</body>
</html>


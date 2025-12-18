<?php
require "db.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    die("غير مصرح");
}

$q = "
SELECT u.*,
 (SELECT COUNT(*) FROM numbers n WHERE n.user_id=u.id) total_numbers,
 (SELECT COUNT(*) FROM numbers n WHERE n.user_id=u.id AND n.sent_date=CURDATE()) sent_today
FROM users u
WHERE u.role='shop'
ORDER BY u.id DESC";
$res = $conn->query($q);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>لوحة الأدمن</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
  <div class="card">
    <h2>لوحة تحكم الأدمن</h2>
    <a class="link danger" href="logout.php">🚪 تسجيل خروج</a>
  </div>

  <div class="card">
    <h3>➕ إضافة محل جديد</h3>
    <form method="post" action="create_shop.php" class="grid">
      <input name="shop_name" placeholder="اسم المحل" required>
      <input name="username" placeholder="اسم المستخدم" required>
      <input type="password" name="password" placeholder="كلمة المرور" required>
      <input type="date" name="expire_date" required>
      <input type="number" name="daily_limit" value="100" required>
      <button class="btn" type="submit">إضافة</button>
    </form>
  </div>

  <div class="card">
    <h3>🏪 المحلات</h3>
    <table>
      <tr>
        <th>المحل</th>
        <th>المستخدم</th>
        <th>ينتهي</th>
        <th>الحد اليومي</th>
        <th>إجمالي الأرقام</th>
        <th>أُرسل اليوم</th>
        <th>تمديد</th>
        <th>تعديل الحد</th>
        <th>حذف</th>
      </tr>

      <?php while($u = $res->fetch_assoc()):
        $expired = (date("Y-m-d") > $u['expire_date']);
      ?>
      <tr class="<?= $expired ? 'expired' : '' ?>">
        <td><?= htmlspecialchars($u['shop_name']) ?></td>
        <td><?= htmlspecialchars($u['username']) ?></td>
        <td><?= htmlspecialchars($u['expire_date']) ?></td>
        <td><?= (int)$u['daily_limit'] ?></td>
        <td><?= (int)$u['total_numbers'] ?></td>
        <td><?= (int)$u['sent_today'] ?></td>

        <td>
          <form method="post" action="extend.php" class="inline">
            <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
            <input class="small" type="number" name="days" placeholder="أيام" required>
            <button class="btn" type="submit">تمديد</button>
          </form>
        </td>

        <td>
          <form method="post" action="update_limit.php" class="inline">
            <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
            <input class="small" type="number" name="limit" value="<?= (int)$u['daily_limit'] ?>" required>
            <button class="btn warn" type="submit">تحديث</button>
          </form>
        </td>

        <td>
          <form method="post" action="delete_shop.php" class="inline" onsubmit="return confirm('تأكيد الحذف؟')">
            <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
            <button class="btn danger" type="submit">حذف</button>
          </form>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>
</div>

</body>
</html>

</body>
</html>

<?php
require "db.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'shop'){
    die("غير مصرح");
}

$uid = (int)$_SESSION['user_id'];

// بيانات المستخدم
$stmt = $conn->prepare("SELECT shop_name, daily_limit, expire_date FROM users WHERE id=?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if(!$user) die("مستخدم غير موجود");
if(date("Y-m-d") > $user['expire_date']) die("❌ انتهت مدة الاشتراك");

// كم انرسل اليوم؟
$stmt = $conn->prepare("SELECT COUNT(*) c FROM numbers WHERE user_id=? AND sent_date=CURDATE()");
$stmt->bind_param("i", $uid);
$stmt->execute();
$sent_today = (int)$stmt->get_result()->fetch_assoc()['c'];

$remaining = (int)$user['daily_limit'] - $sent_today;
if($remaining <= 0) die("❌ وصلت للحد اليومي");

// هات أرقام غير مرسلة
$res = $conn->query("SELECT id, phone, name FROM numbers WHERE user_id=$uid AND sent=0 LIMIT $remaining");

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="today.csv"');

$out = fopen("php://output", "w");
fputcsv($out, ["phone","message"]);

$ids = [];
$shop = $user['shop_name'];

while($row = $res->fetch_assoc()){
    $customer = trim($row['name'] ?? '');
    if($customer === "") $customer = "عزيزنا";

    $message = "مرحبًا $customer 👋\n"
             . "نحن $shop\n"
             . "حابين نعرّفك على عرضنا اليوم 🎉\n"
             . "لو حاب تستفسر، رد علينا مباشرة 💬";

    fputcsv($out, [$row['phone'], $message]);
    $ids[] = (int)$row['id'];
}

fclose($out);

// تعليم كمُرسّل
if(count($ids) > 0){
    $id_list = implode(",", $ids);
    $conn->query("UPDATE numbers SET sent=1, sent_date=CURDATE() WHERE id IN ($id_list)");
}
exit;


<?php
require "db.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    die("غير مصرح");
}

$shop_name = trim($_POST['shop_name']);
$username  = trim($_POST['username']);
$password  = md5($_POST['password']);
$expire    = $_POST['expire_date'];
$limit     = (int)$_POST['daily_limit'];

$stmt = $conn->prepare("INSERT INTO users (shop_name, username, password, expire_date, daily_limit, role) VALUES (?,?,?,?,?,'shop')");
$stmt->bind_param("ssssi", $shop_name, $username, $password, $expire, $limit);

try{
    $stmt->execute();
}catch(Exception $e){
    die("❌ اسم المستخدم مستخدم من قبل");
}

header("Location: admin_dashboard.php");
exit;

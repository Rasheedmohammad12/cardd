<?php
require "db.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    die("غير مصرح");
}

$id   = (int)$_POST['id'];
$days = (int)$_POST['days'];

$conn->query("UPDATE users SET expire_date = DATE_ADD(expire_date, INTERVAL $days DAY) WHERE id=$id");
header("Location: admin_dashboard.php");
exit;

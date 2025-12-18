<?php
require "db.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    die("غير مصرح");
}

$id    = (int)$_POST['id'];
$limit = (int)$_POST['limit'];

$conn->query("UPDATE users SET daily_limit=$limit WHERE id=$id");
header("Location: admin_dashboard.php");
exit;

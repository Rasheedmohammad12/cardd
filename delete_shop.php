<?php
require "db.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    die("غير مصرح");
}

$id = (int)$_POST['id'];

$conn->query("DELETE FROM numbers WHERE user_id=$id");
$conn->query("DELETE FROM users WHERE id=$id AND role='shop'");

header("Location: admin_dashboard.php");
exit;

<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// الاتصال بقاعدة البيانات
$conn = new mysqli("localhost", "if0_40708376", "your_password", "if0_40708376_wa_portal_db");

// التأكد من الاتصال بنجاح
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4"); // لضمان دعم اللغة العربية

session_start();
?>

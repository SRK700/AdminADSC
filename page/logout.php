<?php
session_start();
session_destroy(); // ล้างข้อมูล session ทั้งหมด
header('Location: login.php'); // เปลี่ยนเส้นทางไปยังหน้า login
exit();
?>
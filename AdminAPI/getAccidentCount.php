<?php
// เชื่อมต่อกับฐานข้อมูล
include 'conn.php';

// Query นับจำนวนอุบัติเหตุทั้งหมด
$sql = "SELECT COUNT(*) AS total_accidents FROM accident_reasons";
$result = $conn->query($sql);

// ตรวจสอบผลลัพธ์
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(["total_accidents" => $row['total_accidents']]);
} else {
    echo json_encode(["total_accidents" => 0]);
}

// ปิดการเชื่อมต่อ
$conn->close();
?>
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// เชื่อมต่อฐานข้อมูล
include 'conn.php'; // include ไฟล์เชื่อมต่อฐานข้อมูล

// สร้าง SQL Query สำหรับดึงผู้ใช้ที่มีสถานะ 'รออนุมัติ'
$sql = "SELECT user_id, first_name, last_name, gender, agency, status FROM users WHERE status = 'รออนุมัติ'";
$result = $conn->query($sql);

$users = array();

if ($result->num_rows > 0) {
    // เก็บข้อมูลใน array
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    // ส่งข้อมูลเป็น JSON response
    echo json_encode($users);
} else {
    // ถ้าไม่มีข้อมูลที่รอการอนุมัติ
    echo json_encode(array("message" => "No pending users found."));
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

// เชื่อมต่อฐานข้อมูล
include 'conn.php';

// ตรวจสอบประเภทคำขอ
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // ดึงข้อมูลสาเหตุอุบัติเหตุพร้อมตำแหน่งกล้อง
    $sql = "SELECT ar.id AS reason_id, ar.reason, ar.details, ar.agency, ar.recorded_at, an.camera_location
            FROM accident_reasons ar
            JOIN accident_notifications an ON ar.notification_id = an.id
            WHERE ar.status = 'active'";
    
    $result = $conn->query($sql);

    $reasons = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reasons[] = $row;
        }
    }
    
    echo json_encode($reasons);
} else {
    echo json_encode(["message" => "Invalid request method"]);
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
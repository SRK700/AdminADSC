<?php

// Headers เพื่ออนุญาตการเรียก API จากที่อื่น
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

// เชื่อมต่อฐานข้อมูล
include 'conn.php';

// ตรวจสอบประเภทคำขอ
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // รับข้อมูลที่ส่งมาจากคำขอ
    $input = json_decode(file_get_contents('php://input'), true);

    // ตรวจสอบข้อมูลว่ามี ID และ Status ถูกส่งมาหรือไม่
    if (isset($input['id']) && isset($input['status'])) {
        $id = $input['id'];
        $status = $input['status'];

        // เตรียมคำสั่ง SQL สำหรับการอัปเดตสถานะ
        $stmt = $conn->prepare("UPDATE accident_reasons SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $status, $id);

        // ตรวจสอบการอัปเดตสถานะ
        if ($stmt->execute()) {
            echo json_encode(["message" => "Status updated successfully"]);
        } else {
            echo json_encode(["error" => "Error updating status: " . $stmt->error]);
        }

        // ปิด statement
        $stmt->close();
    } else {
        echo json_encode(["error" => "Invalid input"]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();

?>
<?php
// เชื่อมต่อกับฐานข้อมูล
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // รับข้อมูลที่ส่งมาจากคำขอ
    $input = json_decode(file_get_contents('php://input'), true);
    $user_id = $input['user_id'];
    $status = $input['status'];

    // ตรวจสอบว่าได้ส่ง user_id และ status มาหรือไม่
    if (isset($user_id) && isset($status)) {
        // สร้าง SQL query เพื่ออัปเดตสถานะของผู้ใช้
        $sql = "UPDATE users SET status='$status' WHERE user_id = $user_id";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "User status updated successfully"]);
        } else {
            echo json_encode(["error" => "Error updating status: " . $conn->error]);
        }
    } else {
        echo json_encode(["error" => "Invalid input"]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
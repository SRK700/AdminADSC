<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

// เชื่อมต่อฐานข้อมูล
include 'conn.php';

// ตรวจสอบประเภทคำขอ
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['location_id']) && isset($input['status'])) {
        $location_id = $input['location_id'];
        $status = $input['status'];

        // อัปเดตสถานะของตำแหน่งกล้อง
        $stmt = $conn->prepare("UPDATE cameralocations SET status = ? WHERE camera_location_id = ?");
        $stmt->bind_param('si', $status, $location_id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Status updated successfully"]);
        } else {
            echo json_encode(["error" => "Error updating status: " . $stmt->error]);
        }
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
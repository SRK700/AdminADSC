<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json'); // กำหนดประเภทเนื้อหาเป็น JSON เสมอ

// เชื่อมต่อฐานข้อมูล
include 'conn.php';

// ตรวจสอบประเภทคำขอ
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST': // เพิ่มข้อมูลตำแหน่งกล้อง
        $input = json_decode(file_get_contents('php://input'), true);
        $location_name = $input['location_name'];
        $status = $input['status'];

        $stmt = $conn->prepare("INSERT INTO cameralocations (location_name, status) VALUES (?, ?)");
        $stmt->bind_param('ss', $location_name, $status);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Camera location added successfully"]);
        } else {
            echo json_encode(["error" => "Error adding camera location: " . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'PUT': // แก้ไขข้อมูลตำแหน่งกล้อง
        $input = json_decode(file_get_contents('php://input'), true);
        $location_id = $input['location_id'];
        $location_name = $input['location_name'];
        $status = $input['status'];

        $stmt = $conn->prepare("UPDATE cameralocations SET location_name = ?, status = ? WHERE camera_location_id = ?");
        $stmt->bind_param('ssi', $location_name, $status, $location_id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Camera location updated successfully"]);
        } else {
            echo json_encode(["error" => "Error updating record: " . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'GET': // ดึงข้อมูลตำแหน่งกล้อง
        if (isset($_GET['location_id'])) {
            // ดึงข้อมูลตำแหน่งกล้องเฉพาะ location_id
            $location_id = $_GET['location_id'];
            $stmt = $conn->prepare("SELECT * FROM cameralocations WHERE camera_location_id = ?");
            $stmt->bind_param('i', $location_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo json_encode($result->fetch_assoc());
            } else {
                echo json_encode(["error" => "Camera location not found"]);
            }
            $stmt->close();
        } else {
            // ดึงข้อมูลตำแหน่งกล้องทั้งหมด
            $sql = "SELECT * FROM cameralocations";
            $result = $conn->query($sql);

            $locations = [];
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $locations[] = $row;
                }
                echo json_encode($locations);
            } else {
                echo json_encode([]);
            }
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
        break;
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
<?php
include 'conn.php'; // เชื่อมต่อฐานข้อมูล

// ดึงจำนวนกล้องทั้งหมด
$sql = "SELECT COUNT(*) as total_cameras FROM cameralocations";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['total_cameras' => $row['total_cameras']]);
} else {
    echo json_encode(['total_cameras' => 0]);
}

$conn->close();
?>
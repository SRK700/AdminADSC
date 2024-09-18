<?php
include 'conn.php'; // เชื่อมต่อฐานข้อมูล

if (isset($_GET['id'])) {
    $cameraId = $_GET['id'];
    
    $sql = "SELECT * FROM cameralocations WHERE location_id = $cameraId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $camera = $result->fetch_assoc();
        echo json_encode($camera);
    } else {
        echo json_encode(['error' => 'Camera not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}

$conn->close();
?>
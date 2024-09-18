<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include 'conn.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $conn->prepare("SELECT * FROM accident_reasons WHERE status = 'active'");
        $stmt->execute();
        $result = $stmt->get_result();

        $reasons = [];
        while ($row = $result->fetch_assoc()) {
            $reasons[] = $row;
        }
        echo json_encode($reasons);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['notification_id'], $data['reason'], $data['details'], $data['agency'])) {
            $stmt = $conn->prepare("INSERT INTO accident_reasons (notification_id, reason, details, agency, status) VALUES (?, ?, ?, ?, 'active')");
            $stmt->bind_param('isss', $data['notification_id'], $data['reason'], $data['details'], $data['agency']);
            if ($stmt->execute()) {
                echo json_encode(["message" => "Accident reason added successfully"]);
            } else {
                echo json_encode(["error" => "Failed to add accident reason"]);
            }
        } else {
            echo json_encode(["error" => "Invalid input"]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $_GET['id'];

        if (isset($data['reason'], $data['details'], $data['agency'], $data['status'])) {
            $stmt = $conn->prepare("UPDATE accident_reasons SET reason = ?, details = ?, agency = ?, status = ? WHERE id = ?");
            $stmt->bind_param('ssssi', $data['reason'], $data['details'], $data['agency'], $data['status'], $id);

            if ($stmt->execute()) {
                echo json_encode(["message" => "Accident reason updated successfully"]);
            } else {
                echo json_encode(["error" => "Failed to update accident reason"]);
            }
        } else {
            echo json_encode(["error" => "Invalid input"]);
        }
        break;
}

$conn->close();
?>
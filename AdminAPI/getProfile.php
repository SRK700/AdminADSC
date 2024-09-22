<?php
include 'conn.php'; // Database connection

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Fetch user profile data from the database using email
    $stmt = $conn->prepare("SELECT first_name, last_name, phone_number, img FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $profile = array(
            "name" => $user['first_name'] . ' ' . $user['last_name'],
            "email" => $email,
            "phone" => $user['phone_number'],
            "img" => !empty($user['img']) ? $user['img'] : null // null if no image
        );
        echo json_encode([
            "success" => true,
            "profile" => $profile
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "ไม่พบข้อมูลผู้ใช้"]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}

$conn->close();
?>
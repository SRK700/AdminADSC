<?php
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $email = isset($input['email']) ? $input['email'] : '';
    $password = isset($input['password']) ? $input['password'] : '';

    if ($email && $password) {
        // ตรวจสอบว่าอีเมลและรหัสผ่านตรงกันในฐานข้อมูลหรือไม่
        $sql = "SELECT * FROM Admins WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();

            // ตรวจสอบว่ารหัสผ่านตรงกันหรือไม่
            if ($password === $admin['password']) {
                // รหัสผ่านตรงกัน เข้าสู่ระบบสำเร็จ
                echo json_encode([
                    "message" => "Login successful",
                    "admin_id" => $admin['admin_id'],
                    "first_name" => $admin['first_name'],
                    "last_name" => $admin['last_name'],
                    "email" => $admin['email'],
                    "role" => $admin['role']
                ]);
            } else {
                // รหัสผ่านไม่ถูกต้อง
                echo json_encode(["error" => "Invalid email or password"]);
            }
        } else {
            // ไม่พบอีเมลในฐานข้อมูล
            echo json_encode(["error" => "Invalid email or password"]);
        }
    } else {
        echo json_encode(["error" => "Email and password are required"]);
    }
}

$conn->close();
?>
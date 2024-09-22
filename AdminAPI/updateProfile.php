<?php
session_start();
include 'conn.php'; // เชื่อมต่อกับฐานข้อมูล
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email']; // ใช้ email เพื่อระบุผู้ใช้
    $name = $_POST['name']; // รับข้อมูลที่แก้ไขมา
    $phone = $_POST['phone']; // รับเบอร์โทรศัพท์ที่แก้ไขมา

    // ตรวจสอบว่ามีการอัปโหลดรูปภาพใหม่หรือไม่
    if (isset($_FILES['profileImage']['name']) && $_FILES['profileImage']['name'] != '') {
        // ตั้งค่าโฟลเดอร์สำหรับการอัปโหลดไฟล์
        $target_dir = "profile_images/"; // สามารถเปลี่ยนตามความต้องการ
        $target_file = $target_dir . basename($_FILES['profileImage']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // ตรวจสอบประเภทไฟล์ที่อนุญาต
        $allowed_types = array("jpg", "png", "jpeg", "gif");
        if (!in_array($imageFileType, $allowed_types)) {
            echo json_encode(["success" => false, "message" => "ไฟล์ไม่ใช่รูปภาพที่อนุญาต"]);
            exit;
        }

        // ย้ายไฟล์รูปภาพไปยังโฟลเดอร์ที่กำหนด
        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $target_file)) {
            // บันทึก path ของรูปภาพลงในฐานข้อมูล
            $imgPath = $target_file;
        } else {
            echo json_encode(["success" => false, "message" => "เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ"]);
            exit;
        }
    } else {
        // หากไม่มีการอัปโหลดรูปใหม่ ให้ใช้ path เดิมจากฐานข้อมูล
        $imgPath = $_POST['existing_img'];
    }

    // อัปเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE admins SET first_name = ?, phone_number = ?, img = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(["success" => false, "message" => "เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL"]);
        exit;
    }

    $stmt->bind_param('ssss', $name, $phone, $imgPath, $email);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "อัปเดตข้อมูลสำเร็จ"]);
    } else {
        echo json_encode(["success" => false, "message" => "เกิดข้อผิดพลาดในการอัปเดตข้อมูล"]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}

$conn->close();
?>
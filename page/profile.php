<?php
session_start();

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

// ข้อมูลโปรไฟล์ตัวอย่าง (สามารถดึงข้อมูลจากฐานข้อมูลแทนได้)
$profile = array(
    "name" => "แอดมิน",
    "email" => "yourname@gmail.com",
    "phone" => "086154512184"
);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - ADSC</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f0f0f0;
    }

    .profile-container {
        max-width: 400px;
        margin: 100px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .profile-container h2 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }

    .profile-container .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
    }

    .profile-container img {
        display: block;
        margin: 0 auto 10px;
        border-radius: 50%;
        width: 80px;
        height: 80px;
    }

    .profile-container table {
        width: 100%;
        border-collapse: collapse;
    }

    .profile-container table td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    .profile-container table td:first-child {
        font-weight: bold;
        color: #555;
    }

    .profile-container table td:last-child {
        text-align: right;
        color: #333;
    }
    </style>
</head>

<body>

    <div class="profile-container">
        <button class="close-btn" onclick="window.location.href='dashboard.php'">&times;</button>
        <img src="img/user-profile.png" alt="User Profile">
        <table>
            <tr>
                <td>ชื่อ</td>
                <td><?php echo $profile['name']; ?></td>
            </tr>
            <tr>
                <td>อีเมล</td>
                <td><?php echo $profile['email']; ?></td>
            </tr>
            <tr>
                <td>เบอร์โทรศัพท์</td>
                <td><?php echo $profile['phone']; ?></td>
            </tr>
        </table>
    </div>

</body>

</html>
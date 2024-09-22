<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มชื่อกล้องวงจรปิด - ADSC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f0f0f0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 400px;
        text-align: center;
    }

    .container h2 {
        font-size: 1.5rem;
        color: #333;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
        text-align: left;
    }

    .form-group label {
        font-size: 1rem;
        margin-bottom: 5px;
        display: block;
        color: #333;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
        color: #333;
        background-color: #f9f9f9;
    }

    .form-group select {
        appearance: none;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #6b4df2;
        outline: none;
    }

    .btn {
        background-color: #00bfff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;
        margin-top: 20px;
        width: 100%;
    }

    .btn:hover {
        background-color: #009fd1;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>เพิ่มชื่อกล้องวงจรปิด</h2>
        <form id="addCameraForm">
            <div class="form-group">
                <label for="location_name">ชื่อที่ตั้งกล้อง</label>
                <input type="text" id="location_name" name="location_name" required>
            </div>
            <div class="form-group">
                <label for="status">สถานะกล้อง</label>
                <select id="status" name="status" required>
                    <option value="">เลือกสถานะ</option>
                    <option value="ใช้งาน">ใช้งาน</option>
                    <option value="ระงับ">ระงับการใช้งาน</option>
                </select>
            </div>

            <button type="submit" class="btn">เพิ่ม</button>
        </form>
    </div>

    <script>
    document.getElementById('addCameraForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const locationName = document.getElementById('location_name').value;
        const status = document.getElementById('status').value;

        // ส่งข้อมูลไปยัง API
        fetch('http://localhost:81/AdminADSC/AdminAPI/CameraLocationAPI.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    location_name: locationName,
                    status: status // ตรวจสอบว่ามีการส่ง status ไปด้วย
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    window.location.href =
                        'camera_detail.php'; // ย้ายไปหน้ารายละเอียดกล้องหลังจากเพิ่มสำเร็จ
                } else {
                    alert('เกิดข้อผิดพลาดในการเพิ่มข้อมูล');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับ API');
            });
    });
    </script>
</body>

</html>
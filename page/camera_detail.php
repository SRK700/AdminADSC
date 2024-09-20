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
    <title>รายละเอียดกล้องวงจรปิด - ADSC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f0f0f0;
        display: flex;
        height: 100vh;
    }

    .content {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
    }

    .content h2 {
        font-size: 1.5rem;
        color: #333;
        margin-bottom: 20px;
    }

    .camera-list {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .camera-box {
        flex: 1 1 300px;
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .camera-box i {
        font-size: 3rem;
        color: #6b4df2;
        margin-bottom: 10px;
    }

    .camera-box h3 {
        margin: 0;
        font-size: 1.25rem;
    }

    .camera-box p {
        margin: 5px 0;
        font-size: 1rem;
    }

    .status {
        font-size: 1rem;
        color: #333;
    }

    .status-working {
        color: green;
    }

    .status-not-working {
        color: red;
    }

    .camera-box.add-new {
        background-color: #e0d7ff;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .camera-box.add-new i {
        font-size: 3rem;
        color: #6b4df2;
    }

    .camera-box.add-new:hover {
        background-color: #d0c7f7;
    }

    /* Modal Styling */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        width: 300px;
        text-align: center;
    }

    .modal-content select {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
    }

    .modal-content button {
        margin-top: 20px;
        padding: 10px;
        background-color: #6b4df2;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .modal-content button:hover {
        background-color: #5434b9;
    }

    .filter-btn {
        margin: 10px;
        padding: 10px 20px;
        background-color: #6b4df2;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .filter-btn:hover {
        background-color: #5434b9;
    }
    </style>
</head>

<body>
    <!-- เรียกใช้ Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Content -->
    <div class="content">
        <h2>รายละเอียดกล้องวงจรปิด</h2>

        <!-- ปุ่มกรอง -->
        <button class="filter-btn" onclick="filterStatus('ใช้งาน')">แสดงกล้องใช้งาน</button>
        <button class="filter-btn" onclick="filterStatus('ระงับ')">แสดงกล้องระงับ</button>
        <button class="filter-btn" onclick="fetchCameraLocations()">แสดงทั้งหมด</button>

        <div class="camera-list" id="cameraList">
            <!-- ข้อมูลกล้องวงจรปิดจะถูกแสดงที่นี่ -->
        </div>
    </div>

    <!-- Modal -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <h3>แก้ไขสถานะกล้อง</h3>
            <select id="newStatus">
                <option value="ใช้งาน">ใช้งาน</option>
                <option value="ระงับ">ระงับ</option>
            </select>
            <button id="saveStatusBtn">บันทึก</button>
        </div>
    </div>

    <script>
    let selectedCameraId;

    function fetchCameraLocations() {
        fetch('http://localhost:81/AdminADSC/AdminAPI/CameraLocationAPI.php')
            .then(response => response.json())
            .then(data => {
                renderCameras(data);
            })
            .catch(error => {
                console.error('Error fetching camera locations:', error);
                document.getElementById('cameraList').innerHTML = '<p>เกิดข้อผิดพลาดในการดึงข้อมูล</p>';
            });
    }

    function renderCameras(data) {
        const cameraList = document.getElementById('cameraList');
        cameraList.innerHTML = ''; // ล้างรายการเดิมก่อนแสดงใหม่
        if (data.length > 0) {
            data.forEach(camera => {
                const cameraBox = document.createElement('div');
                cameraBox.className = 'camera-box';
                cameraBox.id = `camera-${camera.camera_location_id}`;

                cameraBox.innerHTML = `
                    <i class="fas fa-video"></i>
                    <h3>${camera.location_name}</h3>
                    <p>สถานะ: <span class="status ${camera.status === 'ใช้งาน' ? 'status-working' : 'status-not-working'}">${camera.status}</span></p>
                    <button onclick="openModal(${camera.camera_location_id})">แก้ไขสถานะ</button>
                `;

                cameraList.appendChild(cameraBox);
            });

            // เพิ่มกล่อง "เพิ่มชื่อกล้องวงจรปิด" ที่ท้ายสุด
            const addNewBox = document.createElement('div');
            addNewBox.className = 'camera-box add-new';
            addNewBox.innerHTML = `
                <i class="fas fa-plus-circle"></i>
                <h3>เพิ่มชื่อกล้องวงจรปิด</h3>
            `;
            addNewBox.addEventListener('click', function() {
                window.location.href = 'add_camera_form.php';
            });
            cameraList.appendChild(addNewBox);
        } else {
            cameraList.innerHTML = '<p>ไม่พบข้อมูลกล้อง</p>';
        }
    }

    function filterStatus(status) {
        fetch('http://localhost:81/AdminADSC/AdminAPI/CameraLocationAPI.php')
            .then(response => response.json())
            .then(data => {
                const filteredCameras = data.filter(camera => camera.status === status);
                renderCameras(filteredCameras);
            })
            .catch(error => {
                console.error('Error filtering camera locations:', error);
            });
    }

    // เปิด modal
    function openModal(cameraId) {
        selectedCameraId = cameraId;
        document.getElementById('statusModal').style.display = 'flex';
    }

    // ปิด modal
    function closeModal() {
        document.getElementById('statusModal').style.display = 'none';
    }

    // บันทึกสถานะใหม่
    document.getElementById('saveStatusBtn').addEventListener('click', function() {
        const newStatus = document.getElementById('newStatus').value;

        fetch('http://localhost:81/AdminADSC/AdminAPI/editStatus.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    location_id: selectedCameraId,
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert("อัพเดตสถานะสำเร็จ");
                    fetchCameraLocations(); // รีเฟรชข้อมูลกล้องทั้งหมด
                    closeModal();
                } else {
                    alert("เกิดข้อผิดพลาดในการอัพเดตสถานะ");
                }
            })
            .catch(error => {
                console.error('Error updating status:', error);
            });
    });

    // เรียกใช้ฟังก์ชันเพื่อดึงข้อมูลกล้องเมื่อหน้าเว็บโหลด
    document.addEventListener('DOMContentLoaded', fetchCameraLocations);

    // ฟังก์ชันเปิด/ปิด Dropdown
    function toggleDropdown() {
        const dropdownMenu = document.getElementById('userDropdown');
        const dropdownIcon = document.querySelector('.dropdown-icon');
        dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        dropdownIcon.classList.toggle('rotate');
    }
    </script>

</body>

</html>
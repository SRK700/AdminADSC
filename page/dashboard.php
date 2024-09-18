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
    <title>Dashboard - ADSC</title>
    <!-- FontAwesome and Custom CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="style.css"> <!-- Add the external CSS here -->
    <style>
    /* Global Styles */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f0f0f0;
    }

    .container {
        display: flex;
        height: 100vh;
    }

    /* Sidebar Styles */
    .sidebar {
        width: 240px;
        background-color: #fff;
        padding: 20px;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .sidebar img {
        max-width: 100px;
        margin-bottom: 20px;
    }

    .sidebar h1 {
        font-size: 1.2rem;
        color: #6b4df2;
        margin-bottom: 30px;
    }

    .sidebar ul {
        list-style-type: none;
        padding: 0;
        flex-grow: 1;
    }

    .sidebar ul li {
        margin-bottom: 20px;
    }

    .sidebar ul li a {
        text-decoration: none;
        color: #333;
        font-size: 1rem;
        display: flex;
        align-items: center;
    }

    .sidebar ul li a:hover {
        color: #6b4df2;
    }

    .sidebar ul li a i {
        margin-right: 10px;
    }

    /* User Info in Sidebar */
    .user-info {
        display: flex;
        align-items: center;
        margin-top: 30px;
        position: relative;
    }

    .user-info img {
        border-radius: 50%;
        margin-right: 10px;
    }

    .user-info div {
        text-align: left;
        cursor: pointer;
    }

    .user-info div p {
        margin: 0;
    }

    .dropdown-user-menu {
        display: none;
        position: absolute;
        bottom: -60px;
        left: 0;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
        padding: 10px;
        z-index: 1000;
    }

    .dropdown-user-menu a {
        display: block;
        padding: 10px 20px;
        text-decoration: none;
        color: #333;
    }

    .dropdown-user-menu a:hover {
        background-color: #f0f0f0;
    }

    .dropdown-icon {
        font-size: 1.2rem;
        margin-left: 5px;
    }

    .dropdown-icon.rotate {
        transform: rotate(180deg);
    }

    /* Content Styles */
    .content {
        flex: 1;
        padding: 20px;
    }

    .card-container {
        display: flex;
        justify-content: space-between;
        gap: 20px;
    }

    .card {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .card h3 {
        font-size: 2rem;
        color: #333;
        margin-bottom: 10px;
    }

    .card p {
        font-size: 1rem;
        color: #666;
    }

    .card i {
        font-size: 3rem;
        color: #6b4df2;
        margin-bottom: 10px;
    }

    .status {
        display: flex;
        justify-content: space-between;
        width: 100%;
        margin-top: 10px;
    }

    .status div {
        text-align: center;
        flex: 1;
    }

    .status div p {
        margin: 5px 0;
        padding: 10px;
        border-radius: 10px;
    }

    /* สีสำหรับสถานะต่างๆ */
    .status-active {
        background-color: #4CAF50;
        color: white;
    }

    .status-pending {
        background-color: #FFC107;
        color: white;
    }

    .status-suspended {
        background-color: #F44336;
        color: white;
    }

    .footer {
        margin-top: 40px;
        text-align: center;
        color: #666;
        font-size: 0.9rem;
    }
    </style>
</head>

<body>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <!-- Use Sidebar component -->

        <div class="content">
            <h2>หน้าหลัก</h2>
            <div class="card-container">
                <div class="card">
                    <i class="fas fa-users"></i>
                    <h3 id="total-users">0</h3>
                    <p>ผู้ใช้ทั้งหมด (คน)</p>
                    <div class="status">
                        <div>
                            <p id="active-users">0</p>
                            <p>ใช้งาน</p>
                        </div>
                        <div>
                            <p id="pending-users">0</p>
                            <p>รออนุมัติ</p>
                        </div>
                        <div>
                            <p id="suspended-users">0</p>
                            <p>ระงับ</p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <i class="fas fa-car-crash"></i>
                    <h3 id="total-accidents">0</h3>
                    <p>อุบัติเหตุทั้งหมด (ครั้ง)</p>
                </div>
                <div class="card">
                    <a href="camera_detail.php" style="text-decoration: none;">
                        <i class="fas fa-video"></i>
                        <h3 id="total-cameras">0</h3>
                        <p>กล้องวงจรปิดทั้งหมด (จุด)</p>
                    </a>
                </div>
            </div>

            <?php include 'footer.php'; ?>
            <!-- Use Footer component -->
        </div>
    </div>

    <script>
    // Fetch user, accident, and camera data from API
    document.addEventListener('DOMContentLoaded', function() {
        fetch('http://localhost:81/AdminADSC/AdminAPI/UserSummaryAPI.php?summary=true')
            .then(response => response.json())
            .then(data => {
                if (data) {
                    document.getElementById('total-users').textContent = data.total_users;
                    document.getElementById('active-users').textContent = data.active_users;
                    document.getElementById('pending-users').textContent = data.pending_users;
                    document.getElementById('suspended-users').textContent = data.suspended_users;
                    document.getElementById('active-users').classList.add('status-active');
                    document.getElementById('pending-users').classList.add('status-pending');
                    document.getElementById('suspended-users').classList.add('status-suspended');
                }
            })
            .catch(error => console.error('Error fetching user data:', error));
    });

    document.addEventListener('DOMContentLoaded', function() {
        fetch('http://localhost:81/AdminADSC/AdminAPI/getCamerasCount.php')
            .then(response => response.json())
            .then(data => {
                if (data) {
                    document.getElementById('total-cameras').textContent = data.total_cameras;
                }
            })
            .catch(error => console.error('Error fetching cameras data:', error));
    });

    document.addEventListener('DOMContentLoaded', function() {
        fetch('http://localhost:81/AdminADSC/AdminAPI/getAccidentCount.php')
            .then(response => response.json())
            .then(data => {
                if (data) {
                    document.querySelector('.card:nth-child(2) h3').textContent = data.total_accidents;
                }
            })
            .catch(error => console.error('Error fetching accident count:', error));
    });
    </script>
</body>

</html>
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
    <title>อนุมัติผู้ใช้ - ADSC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
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

    .content {
        flex: 1;
        padding: 20px;
    }

    .content h2 {
        font-size: 1.5rem;
        color: #333;
        margin-bottom: 20px;
    }

    .header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 20px;
    }

    .search-bar {
        display: flex;
        align-items: center;
        background-color: #e6e6e6;
        padding: 10px;
        border-radius: 5px;
        width: 300px;
    }

    .search-bar input {
        border: none;
        background: transparent;
        outline: none;
        width: 100%;
        margin-left: 10px;
    }

    .table-container {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    table th,
    table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        word-wrap: break-word;
    }

    table th {
        background-color: #f4f4f4;
        cursor: pointer;
    }

    .actions button {
        width: 80px;
        padding: 8px;
        background-color: #6b4df2;
        color: white;
        border: none;
        border-radius: 5px;
        text-align: center;
        cursor: pointer;
    }

    .status select {
        width: 100px;
        padding: 5px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .status select.option-รออนุมัติ {
        background-color: #FFC107;
    }

    .status select.option-อนุมัติ {
        background-color: #4CAF50;
    }

    .status select.option-ระงับ {
        background-color: #F44336;
    }

    .actions button {
        background-color: #6b4df2;
        border: none;
        color: white;
        padding: 8px 12px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        border-radius: 5px;
        margin: 2px;
        cursor: pointer;
    }

    .actions button.approve {
        background-color: #4CAF50;
    }

    .actions button.reject {
        background-color: #F44336;
    }
    </style>
</head>

<body>
    <div class="container">
        <!-- Include Sidebar -->
        <?php include 'sidebar.php'; ?>

        <div class="content">
            <h2>อนุมัติผู้ใช้</h2>
            <div class="header-bar">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="ค้นหา...">
                </div>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ชื่อ</th>
                            <th>เพศ</th>
                            <th>หน่วยงาน</th>
                            <th>สถานะ</th>
                            <th>การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="user-approval-table-body">
                        <!-- User data will be appended here by JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Include Footer -->
            <?php include 'footer.php'; ?>
        </div>
    </div>

    <script>
    // ฟังก์ชันการค้นหา
    document.getElementById('searchInput').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#user-approval-table-body tr');
        rows.forEach(row => {
            let name = row.cells[0].textContent.toLowerCase();
            if (name.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Fetch user data from API
    document.addEventListener('DOMContentLoaded', function() {
        fetch('http://localhost:81/AdminADSC/AdminAPI/FetchPendingUsers.php')
            .then(response => response.json())
            .then(users => {
                const userTableBody = document.getElementById('user-approval-table-body');
                users.forEach(user => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td>${user.first_name} ${user.last_name}</td>
                        <td>${user.gender}</td>
                        <td>${user.agency || 'N/A'}</td>
                        <td>${user.status}</td>
                        <td class="actions">
                            <button class="approve" onclick="approveUser(${user.user_id})">อนุมัติ</button>
                            <button class="reject" onclick="rejectUser(${user.user_id})">ปฏิเสธ</button>
                        </td>
                    `;

                    userTableBody.appendChild(row);
                });
            })
            .catch(error => console.error('Error fetching users:', error));
    });

    // ฟังก์ชันสำหรับอนุมัติผู้ใช้
    function approveUser(userId) {
        if (confirm('คุณต้องการอนุมัติผู้ใช้นี้ใช่หรือไม่?')) {
            updateUserStatus(userId, 'อนุมัติ');
        }
    }

    // ฟังก์ชันสำหรับปฏิเสธผู้ใช้
    function rejectUser(userId) {
        if (confirm('คุณต้องการปฏิเสธผู้ใช้นี้ใช่หรือไม่?')) {
            updateUserStatus(userId, 'ระงับ');
        }
    }

    // ฟังก์ชันอัปเดตสถานะผู้ใช้
    function updateUserStatus(userId, newStatus) {
        fetch('http://localhost:81/AdminADSC/AdminAPI/UpdateUserStatus.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user_id: userId,
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    window.location.reload(); // รีเฟรชหน้าเว็บหลังจากอัปเดตสถานะ
                } else {
                    alert('Error updating user status');
                }
            })
            .catch(error => console.error('Error:', error));
    }
    </script>
</body>

</html>
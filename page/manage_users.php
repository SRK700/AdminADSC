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
    <title>Manage Users - ADSC</title>
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

    .add-user-btn {
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        border-radius: 50%;
        font-size: 1.5rem;
        cursor: pointer;
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
        width: 60px;
        padding: 8px;
        background-color: #6b4df2;
        color: white;
        border: none;
        border-radius: 5px;
        text-align: center;
        cursor: pointer;
    }

    .status select {
        width: 120px;
        padding: 5px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .status-approved {
        background-color: #4CAF50 !important;
        color: white;
    }

    .status-suspended {
        background-color: #F44336 !important;
        color: white;
    }
    </style>
</head>

<body>
    <div class="container">
        <!-- Include Sidebar -->
        <?php include 'sidebar.php'; ?>

        <div class="content">
            <h2>จัดการข้อมูลผู้ใช้</h2>
            <div class="header-bar">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="ค้นหา...">
                </div>
                <div class="add-user-btn" onclick="window.location.href='add_user.php'">
                    <i class="fas fa-plus"></i>
                </div>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th onclick="sortTable(0)">ชื่อ <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable(1)">เพศ <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable(2)">หน่วยงาน <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable(3)">สถานะ <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable(4)">สร้างเมื่อ <i class="fas fa-sort"></i></th>
                            <th>การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="user-table-body">
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
        let rows = document.querySelectorAll('#user-table-body tr');
        rows.forEach(row => {
            let name = row.cells[0].textContent.toLowerCase();
            if (name.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // ฟังก์ชันการเรียงลำดับ
    function sortTable(n) {
        let table = document.querySelector('table');
        let rows = table.rows;
        let switching = true;
        let dir = 'asc';
        let switchcount = 0;

        while (switching) {
            switching = false;
            let shouldSwitch = false;

            for (let i = 1; i < (rows.length - 1); i++) {
                let x = rows[i].getElementsByTagName('td')[n];
                let y = rows[i + 1].getElementsByTagName('td')[n];
                if (dir === 'asc' && x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase() ||
                    dir === 'desc' && x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            }

            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount++;
            } else {
                if (switchcount === 0 && dir === 'asc') {
                    dir = 'desc';
                    switching = true;
                }
            }
        }
    }

    // Fetch user data from API
    document.addEventListener('DOMContentLoaded', function() {
        fetch('http://localhost:81/AdminADSC/AdminAPI/AdminActionUser.php')
            .then(response => response.json())
            .then(users => {
                const userTableBody = document.getElementById('user-table-body');
                users.forEach(user => {
                    const row = document.createElement('tr');

                    const statusOptions = `
                        <select class="status-select ${user.status === 'อนุมัติ' ? 'status-approved' : 'status-suspended'}"
                                onchange="changeStatus(${user.user_id}, this)">
                            <option value="อนุมัติ" ${user.status === 'อนุมัติ' ? 'selected' : ''}>อนุมัติ</option>
                            <option value="ระงับ" ${user.status === 'ระงับ' ? 'selected' : ''}>ระงับ</option>
                        </select>`;

                    row.innerHTML = `
                            <td>${user.first_name} ${user.last_name}</td>
                            <td>${user.gender}</td>
                            <td>${user.agency || 'N/A'}</td>
                            <td class="status">${statusOptions}</td>
                            <td>${user.created_at}</td>
                            <td class="actions">
                                <button onclick="editUser(${user.user_id})">แก้ไข</button>
                                <button onclick="deleteUser(${user.user_id})">ลบ</button>
                            </td>
                        `;

                    userTableBody.appendChild(row);
                });
            })
            .catch(error => console.error('Error fetching users:', error));
    });

    // ฟังก์ชันเปลี่ยนสถานะ
    function changeStatus(userId, selectElement) {
        const newStatus = selectElement.value;

        // เปลี่ยนสีตามสถานะ
        if (newStatus === 'อนุมัติ') {
            selectElement.classList.remove('status-suspended');
            selectElement.classList.add('status-approved');
        } else {
            selectElement.classList.remove('status-approved');
            selectElement.classList.add('status-suspended');
        }

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
                } else {
                    alert('Error updating status');
                }
            })
            .catch(error => console.error('Error updating status:', error));
    }

    // ฟังก์ชันลบผู้ใช้
    function deleteUser(userId) {
        if (confirm('Are you sure you want to delete this user?')) {
            fetch('http://localhost:81/AdminADSC/AdminAPI/AdminActionUser.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        user_id: userId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    window.location.reload();
                })
                .catch(error => console.error('Error deleting user:', error));
        }
    }
    </script>
</body>

</html>
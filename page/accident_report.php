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
    <title>รายงานสาเหตุอุบัติเหตุ - ADSC</title>
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

    .search-bar {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        background-color: #e6e6e6;
        padding: 10px;
        border-radius: 5px;
        width: 300px;
        margin-bottom: 20px;
    }

    .search-bar input {
        border: none;
        background: transparent;
        outline: none;
        width: 100%;
        margin-left: 10px;
    }

    .filter-bar {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        margin-bottom: 20px;
        gap: 10px;
    }

    .filter-item select {
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
        width: 200px;
    }

    .table-container {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th,
    table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    table th {
        background-color: #f4f4f4;
        cursor: pointer;
    }

    table th i {
        margin-left: 5px;
        cursor: pointer;
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

    .actions button.delete {
        background-color: #4CAF50;
    }
    </style>
</head>

<body>
    <div class="container">
        <!-- Include Sidebar -->
        <?php include 'sidebar.php'; ?>

        <div class="content">
            <h2>รายงานสาเหตุอุบัติเหตุ</h2>

            <!-- กล่องค้นหาอยู่ด้านบนของดรอปดาวน์ -->
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="ค้นหา...">
            </div>

            <div class="filter-bar">
                <div class="filter-item">
                    <label for="reasonFilter">สาเหตุอุบัติเหตุ</label>
                    <select id="reasonFilter">
                        <option value="all">ทั้งหมด</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label for="cameraFilter">สถานที่ตั้งกล้อง</label>
                    <select id="cameraFilter">
                        <option value="all">ทั้งหมด</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label for="dateFilter">วันที่</label>
                    <select id="dateFilter">
                        <option value="all">ทั้งหมด</option>
                    </select>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>สาเหตุ</th>
                            <th>รายละเอียด</th>
                            <th>หน่วยงาน</th> <!-- เพิ่มคอลัมน์หน่วยงาน -->
                            <th>สถานที่ตั้งกล้อง</th>
                            <th>วันที่บันทึก</th>
                            <th>การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="accident-table-body">
                        <!-- Accident reasons data will be appended here by JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Include Footer -->
            <?php include 'footer.php'; ?>
        </div>
    </div>

    <script>
    // ฟังก์ชันการกรองข้อมูล
    document.addEventListener('DOMContentLoaded', function() {
        fetch('http://localhost:81/AdminADSC/AdminAPI/AccidentReasonsAPI.php')
            .then(response => response.json())
            .then(reasons => {
                populateFilters(reasons);
                renderTable(reasons);

                function filterData() {
                    const reasonValue = document.getElementById('reasonFilter').value;
                    const cameraValue = document.getElementById('cameraFilter').value;
                    const dateValue = document.getElementById('dateFilter').value;
                    const searchValue = document.getElementById('searchInput').value.toLowerCase();

                    const filteredReasons = reasons.filter(reason => {
                        const reasonMatch = reasonValue === 'all' || reason.reason === reasonValue;
                        const cameraMatch = cameraValue === 'all' || reason.camera_location ===
                            cameraValue;
                        const dateMatch = dateValue === 'all' || reason.recorded_at.startsWith(
                            dateValue);
                        const searchMatch = reason.reason.toLowerCase().includes(searchValue) ||
                            reason.details.toLowerCase().includes(searchValue);

                        return reasonMatch && cameraMatch && dateMatch && searchMatch;
                    });

                    renderTable(filteredReasons);
                }

                document.getElementById('reasonFilter').addEventListener('change', filterData);
                document.getElementById('cameraFilter').addEventListener('change', filterData);
                document.getElementById('dateFilter').addEventListener('change', filterData);
                document.getElementById('searchInput').addEventListener('input', filterData);
            })
            .catch(error => console.error('Error fetching accident reasons:', error));

        function populateFilters(reasons) {
            const reasonFilter = document.getElementById('reasonFilter');
            const cameraFilter = document.getElementById('cameraFilter');
            const dateFilter = document.getElementById('dateFilter');

            const reasonsSet = new Set();
            const camerasSet = new Set();
            const datesSet = new Set();

            reasons.forEach(reason => {
                reasonsSet.add(reason.reason);
                camerasSet.add(reason.camera_location);
                datesSet.add(reason.recorded_at.split(' ')[0]);
            });

            reasonsSet.forEach(reason => {
                const option = document.createElement('option');
                option.value = reason;
                option.textContent = reason;
                reasonFilter.appendChild(option);
            });

            camerasSet.forEach(camera => {
                const option = document.createElement('option');
                option.value = camera;
                option.textContent = camera;
                cameraFilter.appendChild(option);
            });

            datesSet.forEach(date => {
                const option = document.createElement('option');
                option.value = date;
                option.textContent = date;
                dateFilter.appendChild(option);
            });
        }

        function renderTable(data) {
            const accidentTableBody = document.getElementById('accident-table-body');
            accidentTableBody.innerHTML = '';

            data.forEach(reason => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${reason.reason}</td>
                    <td>${reason.details || 'ไม่มีข้อมูล'}</td>
                    <td>${reason.agency || 'ไม่ระบุ'}</td> <!-- เพิ่มแสดงข้อมูลหน่วยงาน -->
                    <td>${reason.camera_location || 'ไม่ระบุ'}</td>
                    <td>${reason.recorded_at}</td>
                    <td class="actions">
                        <button class="delete" onclick="toggleReason(${reason.id}, '${reason.status}')">${reason.status === 'active' ? 'ระงับ' : 'ใช้งาน'}</button>
                    </td>
                `;
                accidentTableBody.appendChild(row);
            });
        }
    });

    function toggleReason(reasonId, currentStatus) {
        const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
        fetch('http://localhost:81/AdminADSC/AdminAPI/ToggleAccidentReasonStatus.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: reasonId,
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                window.location.reload();
            })
            .catch(error => console.error('Error updating status:', error));
    }
    </script>
</body>

</html>
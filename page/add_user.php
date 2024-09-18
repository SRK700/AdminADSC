<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มผู้ใช้ - ADSC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f7f8fc;
    }

    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .form-container {
        background-color: #fff;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 600px;
    }

    .form-container h2 {
        text-align: center;
        color: #4A4A4A;
        margin-bottom: 30px;
        font-size: 24px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #555;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #ddd;
        background-color: #f9f9f9;
        font-size: 16px;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #6b4df2;
    }

    .form-group button {
        width: 100%;
        padding: 12px;
        background-color: #6b4df2;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form-group button:hover {
        background-color: #5a3ae4;
    }

    .form-group .error {
        color: red;
        font-size: 0.9rem;
    }

    /* เพิ่มสไตล์สำหรับ dropdown icon */
    .form-group select {
        appearance: none;
        background-image: url('https://cdn-icons-png.flaticon.com/512/60/60995.png');
        /* ตัวอย่างไอคอน dropdown */
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 20px;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2>เพิ่มผู้ใช้</h2>
            <form id="addUserForm">
                <div class="form-group">
                    <label for="prefix">คำนำหน้าชื่อ</label>
                    <select id="prefix" name="prefix" required>
                        <option value="นาย">นาย</option>
                        <option value="นาง">นาง</option>
                        <option value="นางสาว">นางสาว</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="first_name">ชื่อ</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>

                <div class="form-group">
                    <label for="last_name">นามสกุล</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>

                <div class="form-group">
                    <label for="gender">เพศ</label>
                    <select id="gender" name="gender" required>
                        <option value="ชาย">ชาย</option>
                        <option value="หญิง">หญิง</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="agency">หน่วยงาน</label>
                    <select id="agency" name="agency" required>
                        <option value="เจ้าหน้าที่กู้ภัย">เจ้าหน้าที่กู้ภัย</option>
                        <option value="เจ้าหน้าที่ตำรวจ">เจ้าหน้าที่ตำรวจ</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">สถานะการใช้งาน</label>
                    <select id="status" name="status" required>
                        <option value="ใช้งาน">ใช้งาน</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="phone">เบอร์โทรศัพท์</label>
                    <input type="text" id="phone" name="phone" required>
                </div>

                <div class="form-group">
                    <label for="email">อีเมล</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">รหัสผ่าน</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <button type="submit">เพิ่ม</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.getElementById('addUserForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = {
            prefix: document.getElementById('prefix').value,
            first_name: document.getElementById('first_name').value,
            last_name: document.getElementById('last_name').value,
            gender: document.getElementById('gender').value,
            agency: document.getElementById('agency').value,
            status: 'อนุมัติ', // ตั้งค่าเป็น "อนุมัติ" โดยอัตโนมัติ
            phone: document.getElementById('phone').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
        };

        fetch('http://localhost:81/AdminADSC/AdminAPI/AdminActionUser.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData),
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    window.location.href = 'manage_users.php';
                } else {
                    alert('เกิดข้อผิดพลาดในการเพิ่มผู้ใช้');
                }
            })
            .catch(error => console.error('Error:', error));
    });
    </script>
</body>

</html>
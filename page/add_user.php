<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มผู้ใช้ - ADSC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        position: relative;
    }

    .form-container {
        background-color: #fff;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 600px;
        transition: all 0.3s ease;
    }

    .form-container:hover {
        transform: translateY(-5px);
    }

    .form-container h2 {
        text-align: center;
        color: #333;
        margin-bottom: 30px;
        font-size: 26px;
        font-weight: bold;
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
        transition: all 0.2s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #6b4df2;
        background-color: #eef2ff;
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
        margin-top: 20px;
    }

    .form-group button:hover {
        background-color: #5a3ae4;
    }

    .form-group .error {
        color: red;
        font-size: 0.9rem;
    }

    .back-btn {
        position: absolute;
        top: 20px;
        left: 20px;
        background-color: #6b4df2;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    .back-btn:hover {
        background-color: #d73833;
    }

    .back-btn i {
        margin-right: 8px;
    }

    .form-group select {
        appearance: none;
        background-image: url('https://cdn-icons-png.flaticon.com/512/60/60995.png');
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 20px;
    }
    </style>
</head>

<body>
    <div class="container">
        <!-- ปุ่มย้อนกลับด้านซ้ายบน -->
        <a href="manage_users.php" class="back-btn"><i class="fas fa-arrow-left"></i> ย้อนกลับ</a>

        <div class="form-container">
            <h2>เพิ่มผู้ใช้</h2>
            <form id="addUserForm">
                <div class="form-group">
                    <label for="prefix">คำนำหน้าชื่อ</label>
                    <input type="text" id="prefix" name="prefix" list="prefixOptions"
                        placeholder="เช่น นาย, นาง, นางสาว, ร้อยตรี" required>
                    <datalist id="prefixOptions">
                        <option value="นาย">
                        <option value="นาง">
                        <option value="นางสาว">
                    </datalist>
                    <div class="error" id="prefixError"></div>
                </div>

                <div class="form-group">
                    <label for="first_name">ชื่อ</label>
                    <input type="text" id="first_name" name="first_name" required>
                    <div class="error" id="firstNameError"></div>
                </div>

                <div class="form-group">
                    <label for="last_name">นามสกุล</label>
                    <input type="text" id="last_name" name="last_name" required>
                    <div class="error" id="lastNameError"></div>
                </div>

                <div class="form-group">
                    <label for="gender">เพศ</label>
                    <select id="gender" name="gender" required>
                        <option value="">เลือกเพศ</option>
                        <option value="ชาย">ชาย</option>
                        <option value="หญิง">หญิง</option>
                    </select>
                    <div class="error" id="genderError"></div>
                </div>

                <div class="form-group">
                    <label for="agency">หน่วยงาน</label>
                    <select id="agency" name="agency" required>
                        <option value="">เลือกหน่วยงาน</option>
                        <option value="เจ้าหน้าที่กู้ภัย">เจ้าหน้าที่กู้ภัย</option>
                        <option value="เจ้าหน้าที่ตำรวจ">เจ้าหน้าที่ตำรวจ</option>
                        <option value="หน่วยงานที่เกี่ยวข้อง">หน่วยงานที่เกี่ยวข้อง</option>
                    </select>
                    <div class="error" id="agencyError"></div>
                </div>

                <div class="form-group">
                    <label for="phone">เบอร์โทรศัพท์</label>
                    <input type="text" id="phone" name="phone" required pattern="\d{10}"
                        placeholder="กรุณาใส่เบอร์โทร 10 หลัก">
                    <div class="error" id="phoneError"></div>
                </div>

                <div class="form-group">
                    <label for="email">อีเมล</label>
                    <input type="email" id="email" name="email" required>
                    <div class="error" id="emailError"></div>
                </div>

                <div class="form-group">
                    <label for="password">รหัสผ่าน</label>
                    <input type="password" id="password" name="password" required>
                    <div class="error" id="passwordError"></div>
                </div>

                <!-- เพิ่มฟิลด์ยืนยันรหัสผ่าน -->
                <div class="form-group">
                    <label for="confirm_password">ยืนยันรหัสผ่าน</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <div class="error" id="confirmPasswordError"></div>
                </div>

                <div class="form-group">
                    <button type="submit">เพิ่มผู้ใช้</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.getElementById('addUserForm').addEventListener('submit', function(event) {
        event.preventDefault();
        let valid = validateForm();
        if (!valid) {
            return;
        }

        const formData = {
            prefix: document.getElementById('prefix').value,
            first_name: document.getElementById('first_name').value,
            last_name: document.getElementById('last_name').value,
            gender: document.getElementById('gender').value,
            agency: document.getElementById('agency').value,
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
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: 'เพิ่มผู้ใช้สำเร็จ',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        window.location.href =
                            'manage_users.php'; // เปลี่ยนเส้นทางหลังจากการแจ้งเตือนสำเร็จ
                    });
                } else {
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'เกิดข้อผิดพลาดในการเพิ่มผู้ใช้',
                        icon: 'error',
                        confirmButtonText: 'ลองอีกครั้ง'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์',
                    icon: 'error',
                    confirmButtonText: 'ลองอีกครั้ง'
                });
                console.error('Error:', error);
            });

    });

    function validateForm() {
        let valid = true;

        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        const confirmPasswordError = document.getElementById('confirmPasswordError');

        // ตรวจสอบรหัสผ่านว่าตรงกันหรือไม่
        if (password.value !== confirmPassword.value) {
            confirmPasswordError.textContent = 'รหัสผ่านไม่ตรงกัน';
            valid = false;
        } else {
            confirmPasswordError.textContent = '';
        }

        // ตรวจสอบฟิลด์อื่นๆ
        const prefix = document.getElementById('prefix');
        const prefixError = document.getElementById('prefixError');
        if (prefix.value === '') {
            prefixError.textContent = 'กรุณาเลือกคำนำหน้า';
            valid = false;
        } else {
            prefixError.textContent = '';
        }

        const firstName = document.getElementById('first_name');
        const firstNameError = document.getElementById('firstNameError');
        if (firstName.value.trim() === '') {
            firstNameError.textContent = 'กรุณากรอกชื่อ';
            valid = false;
        } else {
            firstNameError.textContent = '';
        }

        const lastName = document.getElementById('last_name');
        const lastNameError = document.getElementById('lastNameError');
        if (lastName.value.trim() === '') {
            lastNameError.textContent = 'กรุณากรอกนามสกุล';
            valid = false;
        } else {
            lastNameError.textContent = '';
        }

        const gender = document.getElementById('gender');
        const genderError = document.getElementById('genderError');
        if (gender.value === '') {
            genderError.textContent = 'กรุณาเลือกเพศ';
            valid = false;
        } else {
            genderError.textContent = '';
        }

        const agency = document.getElementById('agency');
        const agencyError = document.getElementById('agencyError');
        if (agency.value === '') {
            agencyError.textContent = 'กรุณาเลือกหน่วยงาน';
            valid = false;
        } else {
            agencyError.textContent = '';
        }

        const phone = document.getElementById('phone');
        const phoneError = document.getElementById('phoneError');
        const phonePattern = /^\d{10}$/;
        if (!phonePattern.test(phone.value)) {
            phoneError.textContent = 'กรุณากรอกเบอร์โทรศัพท์ให้ถูกต้อง (10 หลัก)';
            valid = false;
        } else {
            phoneError.textContent = '';
        }

        const email = document.getElementById('email');
        const emailError = document.getElementById('emailError');
        if (email.value.trim() === '') {
            emailError.textContent = 'กรุณากรอกอีเมล';
            valid = false;
        } else {
            emailError.textContent = '';
        }

        return valid;
    }
    </script>
</body>

</html>
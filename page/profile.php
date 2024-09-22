<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

$email = $_SESSION['email']; // ใช้ email จาก session เพื่อดึงข้อมูลโปรไฟล์
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

    .profile-container img {
        display: block;
        margin: 0 auto 10px;
        border-radius: 50%;
        width: 80px;
        height: 80px;
        cursor: pointer;
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

    .profile-container .edit-btn,
    .profile-container .save-btn,
    .profile-container .cancel-btn {
        margin-top: 20px;
        display: block;
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        border: none;
        background-color: #6b4df2;
        color: white;
    }

    .profile-container .save-btn,
    .profile-container .cancel-btn {
        background-color: #5a3ae4;
        display: none;
    }

    .profile-container .edit-btn:hover,
    .profile-container .save-btn:hover,
    .profile-container .cancel-btn:hover {
        background-color: #4a30c7;
    }

    input[type="file"] {
        display: none;
    }
    </style>
</head>

<body>

    <div class="profile-container">
        <h2>โปรไฟล์ผู้ใช้</h2>
        <img id="profile-img" src="img/default-profile.png" alt="User Profile">
        <input type="file" id="fileInput">
        <table>
            <tr>
                <td>ชื่อ</td>
                <td id="profile-name"></td>
            </tr>
            <tr>
                <td>อีเมล</td>
                <td id="profile-email"></td>
            </tr>
            <tr>
                <td>เบอร์โทรศัพท์</td>
                <td id="profile-phone"></td>
            </tr>
        </table>

        <button class="edit-btn" onclick="enableEdit()">แก้ไข</button>
        <button class="save-btn" onclick="saveProfile()">บันทึก</button>
        <button class="cancel-btn" onclick="cancelEdit()">ยกเลิก</button>
    </div>

    <script>
    let originalData = {};
    const fileInput = document.getElementById('fileInput');
    const profileImg = document.getElementById('profile-img');

    // Function to load the profile data from the API
    function loadProfile() {
        const email = "<?php echo $email; ?>"; // Get session email

        fetch('http://localhost:81/AdminADSC/AdminAPI/getProfile.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    email: email // ใช้อีเมลจาก session เพื่อดึงข้อมูลโปรไฟล์
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const profile = data.profile;
                    document.getElementById('profile-name').textContent = profile.name;
                    document.getElementById('profile-email').textContent = profile.email;
                    document.getElementById('profile-phone').textContent = profile.phone;

                    // ใช้ path ที่ถูกต้องของรูปภาพ
                    const profileImagePath = profile.img ? `http://localhost:81/AdminADSC/AdminAPI/${profile.img}` :
                        'img/default-profile.png';
                    document.getElementById('profile-img').src = profileImagePath;
                } else {
                    alert('Error fetching profile data');
                }
            })
            .catch(error => console.error('Error:', error));


    }

    // Load the profile data on page load
    window.onload = loadProfile;

    // Function to enable editing
    function enableEdit() {
        originalData.name = document.getElementById('profile-name').textContent;
        originalData.phone = document.getElementById('profile-phone').textContent;
        originalData.img = profileImg.src;

        document.getElementById('profile-name').innerHTML =
            `<input type="text" id="edit-name" value="${originalData.name}" />`;
        document.getElementById('profile-phone').innerHTML =
            `<input type="text" id="edit-phone" value="${originalData.phone}" />`;

        document.querySelector('.edit-btn').style.display = 'none';
        document.querySelector('.save-btn').style.display = 'block';
        document.querySelector('.cancel-btn').style.display = 'block';
    }

    // Function to cancel editing
    function cancelEdit() {
        document.getElementById('profile-name').textContent = originalData.name;
        document.getElementById('profile-phone').textContent = originalData.phone;
        profileImg.src = originalData.img;

        document.querySelector('.edit-btn').style.display = 'block';
        document.querySelector('.save-btn').style.display = 'none';
        document.querySelector('.cancel-btn').style.display = 'none';
    }

    // Click on image to upload new picture
    profileImg.addEventListener('click', () => {
        fileInput.click(); // Trigger the file input dialog
    });

    // When a file is selected
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profileImg.src = e.target.result; // Preview the new image
            }
            reader.readAsDataURL(file);
        }
    });

    // Function to save profile data
    function saveProfile() {
        const updatedName = document.getElementById('edit-name').value;
        const updatedPhone = document.getElementById('edit-phone').value;

        const formData = new FormData();
        formData.append('name', updatedName);
        formData.append('phone', updatedPhone);
        formData.append('email', document.getElementById('profile-email').textContent); // Email should remain unchanged

        const file = fileInput.files[0];
        if (file) {
            formData.append('profileImage', file); // If a new image is selected
        }

        // Send the updated data to the API
        fetch('http://localhost:81/AdminADSC/AdminAPI/updateProfile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text()) // Changed to .text() to inspect the response as plain text
            .then(data => {
                try {
                    const jsonResponse = JSON.parse(data); // Try to parse the JSON response
                    if (jsonResponse.success) {
                        alert('โปรไฟล์อัปเดตเรียบร้อยแล้ว');
                        loadProfile(); // Reload the profile data
                    } else {
                        alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                    }
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                    console.log('Server response:', data); // Log the raw response for debugging
                }
            })
            .catch(error => console.error('Error:', error));
    }
    </script>

</body>

</html>
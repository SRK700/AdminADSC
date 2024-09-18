<div class="sidebar"
    style="width: 240px; height: 100vh; background-color: white; padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
    <div>
        <img src="img/Logo.png" alt="ADSC Logo" style="max-width: 100%; height: auto; margin-bottom: 20px;">
        <h1 style="font-size: 1.2rem; color: #6b4df2; text-align: center;">ADSC</h1>
        <ul style="list-style-type: none; padding: 0;">
            <li style="margin-bottom: 20px;">
                <a href="Dashboard.php" style="text-decoration: none; color: #333; display: flex; align-items: center;">
                    <i class="fas fa-tachometer-alt" style="margin-right: 10px;"></i> หน้าหลัก
                </a>
            </li>
            <li style="margin-bottom: 20px;">
                <a href="manage_users.php"
                    style="text-decoration: none; color: #333; display: flex; align-items: center;">
                    <i class="fas fa-users" style="margin-right: 10px;"></i> จัดการข้อมูลผู้ใช้
                </a>
            </li>
            <li style="margin-bottom: 20px;">
                <a href="manage_accidents.php"
                    style="text-decoration: none; color: #333; display: flex; align-items: center;">
                    <i class="fas fa-exclamation-triangle" style="margin-right: 10px;"></i> จัดการสาเหตุอุบัติเหตุ
                </a>
            </li>
            <li style="margin-bottom: 20px;">
                <a href="accident_reports.php"
                    style="text-decoration: none; color: #333; display: flex; align-items: center;">
                    <i class="fas fa-file-alt" style="margin-right: 10px;"></i> รายงานอุบัติเหตุ
                </a>
            </li>
        </ul>
    </div>
    <div class="user-info" style="cursor: pointer;" onclick="toggleDropdown()">
        <img src="img/user-profile.png" alt="User Profile"
            style="border-radius: 50%; margin-right: 10px; width: 50px; height: 50px;">
        <div>
            <p style="margin: 0; color: #333;">แอดมิน</p>
            <p style="margin: 0; color: #333;">ผู้ดูแลระบบ <i class="fas fa-caret-down dropdown-icon"></i></p>
        </div>
        <div class="dropdown-user-menu"
            style="display: none; position: absolute; background-color: white; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); padding: 10px; border-radius: 5px; z-index: 100;">
            <a href="profile.php" style="text-decoration: none; display: block; padding: 10px; color: #333;">โปรไฟล์</a>
            <a href="logout.php"
                style="text-decoration: none; display: block; padding: 10px; color: #333;">ออกจากระบบ</a>
        </div>
    </div>
</div>


<script>
function toggleDropdown() {
    const dropdownMenu = document.querySelector('.dropdown-user-menu');
    dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
}
</script>
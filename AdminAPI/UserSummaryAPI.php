<?php
include 'conn.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['summary']) && $_GET['summary'] == 'true') {
            $sql = "SELECT 
                        COUNT(*) AS total_users,
                        COUNT(CASE WHEN status = 'อนุมัติ' THEN 1 END) AS active_users,
                        COUNT(CASE WHEN status = 'รออนุมัติ' THEN 1 END) AS pending_users,
                        COUNT(CASE WHEN status = 'ระงับ' THEN 1 END) AS suspended_users
                    FROM users";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo json_encode($result->fetch_assoc());
            } else {
                echo json_encode(["error" => "No users found"]);
            }
        }
        break;
}

$conn->close();
?>
<?php
// เชื่อมต่อกับฐานข้อมูล
include 'conn.php';

// ตรวจสอบประเภทของคำขอ
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST': // เพิ่มข้อมูล
    $input = json_decode(file_get_contents('php://input'), true);
    $agency = $input['agency'];
    $first_name = $input['first_name'];
    $last_name = $input['last_name'];
    $gender = $input['gender'];
    $prefix = $input['prefix'];
    $email = $input['email'];
    $password = $input['password']; // ไม่เข้ารหัสรหัสผ่าน
    $phone = $input['phone'];

    // ตั้งค่า status เป็น 'อนุมัติ' ไว้ล่วงหน้า
    $status = 'อนุมัติ';

    $sql = "INSERT INTO users (agency, first_name, last_name, gender, prefix, status, email, password, phone) 
            VALUES ('$agency', '$first_name', '$last_name', '$gender', '$prefix', '$status', '$email', '$password', '$phone')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "User added successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . $sql . "<br>" . $conn->error]);
    }
    break;


    case 'GET': // เรียกดูข้อมูล
        $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

        if ($user_id) {
            // เรียกดูข้อมูลผู้ใช้เฉพาะ ID
            $sql = "SELECT * FROM users WHERE user_id = $user_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo json_encode($result->fetch_assoc());
            } else {
                echo json_encode(["error" => "User not found"]);
            }
        } else {
            // เรียกดูข้อมูลผู้ใช้ทั้งหมด
            $sql = "SELECT * FROM users";
            $result = $conn->query($sql);

            $users = [];
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $users[] = $row;
                }
                echo json_encode($users);
            } else {
                echo json_encode([]);
            }
        }
        break;

    case 'PUT': // แก้ไขข้อมูล
        $input = json_decode(file_get_contents("php://input"), true);
        $user_id = $input['user_id'];
        $agency = $input['agency'];
        $first_name = $input['first_name'];
        $last_name = $input['last_name'];
        $gender = $input['gender'];
        $prefix = $input['prefix'];
        $status = $input['status'];
        $email = $input['email'];
        $password = $input['password']; // ไม่เข้ารหัสรหัสผ่าน
        $phone = $input['phone'];

        $sql = "UPDATE users SET 
                agency='$agency', 
                first_name='$first_name', 
                last_name='$last_name', 
                gender='$gender', 
                prefix='$prefix', 
                status='$status', 
                email='$email', 
                password='$password', 
                phone='$phone'
                WHERE user_id = $user_id";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "User updated successfully"]);
        } else {
            echo json_encode(["error" => "Error updating record: " . $conn->error]);
        }
        break;

    case 'DELETE': // ลบข้อมูล
        $input = json_decode(file_get_contents("php://input"), true);
        $user_id = $input['user_id'];

        $sql = "DELETE FROM users WHERE user_id = $user_id";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "User deleted successfully"]);
        } else {
            echo json_encode(["error" => "Error deleting record: " . $conn->error]);
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
        break;
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
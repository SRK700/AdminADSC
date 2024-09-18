<?php
include 'conn.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);

        $first_name = isset($input['first_name']) ? $input['first_name'] : '';
        $last_name = isset($input['last_name']) ? $input['last_name'] : '';
        $email = isset($input['email']) ? $input['email'] : '';
        $phone_number = isset($input['phone_number']) ? $input['phone_number'] : '';
        $password = isset($input['password']) ? $input['password'] : '';
        $role = isset($input['role']) ? $input['role'] : 'admin';

        if($first_name && $last_name && $email && $phone_number && $password) {
            // ตรวจสอบว่าอีเมลมีอยู่แล้วหรือไม่
            $checkEmail = "SELECT email FROM Admins WHERE email='$email'";
            $result = $conn->query($checkEmail);
            
            if ($result->num_rows > 0) {
                echo json_encode(["error" => "Email already exists"]);
            } else {
                $sql = "INSERT INTO Admins (first_name, last_name, email, phone_number, password, role)
                        VALUES ('$first_name', '$last_name', '$email', '$phone_number', '$password', '$role')";

                if ($conn->query($sql) === TRUE) {
                    echo json_encode(["message" => "Admin created successfully"]);
                } else {
                    echo json_encode(["error" => "Error: " . $sql . "<br>" . $conn->error]);
                }
            }
        } else {
            echo json_encode(["error" => "All fields are required"]);
        }
        break;

    case 'GET':
        $sql = "SELECT * FROM Admins";
        $result = $conn->query($sql);

        $admins = [];

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $admins[] = $row;
            }
            echo json_encode($admins);
        } else {
            echo json_encode([]);
        }
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $_PUT);
        $admin_id = $_PUT['admin_id'];
        $first_name = $_PUT['first_name'];
        $last_name = $_PUT['last_name'];
        $email = $_PUT['email'];
        $phone_number = $_PUT['phone_number'];
        $role = isset($_PUT['role']) ? $_PUT['role'] : 'admin';

        $sql = "UPDATE Admins SET first_name='$first_name', last_name='$last_name', email='$email', phone_number='$phone_number', role='$role' WHERE admin_id='$admin_id'";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Admin updated successfully"]);
        } else {
            echo json_encode(["error" => "Error updating record: " . $conn->error]);
        }
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $_DELETE);
        $admin_id = $_DELETE['admin_id'];

        $sql = "DELETE FROM Admins WHERE admin_id='$admin_id'";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Admin deleted successfully"]);
        } else {
            echo json_encode(["error" => "Error deleting record: " . $conn->error]);
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
        break;
}

$conn->close();
?>
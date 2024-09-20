<?php
session_start();

$loginStatus = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ส่งข้อมูลไปยัง API เพื่อเช็คการล็อกอิน
    $data = array(
        "email" => $email,
        "password" => $password
    );

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data)
        )
    );

    $context  = stream_context_create($options);
    $result = file_get_contents('http://localhost:81/AdminADSC/AdminAPI/checkLogin.php', false, $context);
    $response = json_decode($result, true);

    if (isset($response['message']) && $response['message'] == 'Login successful') {
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        $loginStatus = 'success';
    } else {
        $loginStatus = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - ADSC</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
    body {
        background-color: #f0f2f5;
    }

    .login-container {
        max-width: 400px;
        margin: 50px auto;
        padding: 20px;
    }

    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .card img {
        width: 100px;
        margin: 20px auto 10px;
    }

    .card-title {
        text-align: center;
        color: #6b4df2;
        margin-bottom: 30px;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #6b4df2;
    }

    .btn-primary {
        background-color: #6b4df2;
        border: none;
    }

    .btn-primary:hover {
        background-color: #5a3ae4;
    }

    .toggle-password {
        cursor: pointer;
        position: absolute;
        right: 15px;
        top: calc(50% - 0.5em);
        color: #aaa;
    }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="card p-4">
            <img src="img/Logo.png" alt="ADSC Logo">
            <h3 class="card-title">ADSC Admin</h3>
            <form action="login.php" method="POST">
                <div class="form-group position-relative">
                    <input type="email" name="email" class="form-control" placeholder="อีเมล" required>
                </div>
                <div class="form-group position-relative">
                    <input type="password" name="password" id="password" class="form-control" placeholder="รหัสผ่าน"
                        required>
                    <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                </div>
                <button type="submit" class="btn btn-primary btn-block">เข้าสู่ระบบ</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    // Toggle password visibility
    $(document).ready(function() {
        $("#togglePassword").click(function() {
            const password = $("#password");
            const type = password.attr("type") === "password" ? "text" : "password";
            password.attr("type", type);
            $(this).toggleClass("fa-eye-slash");
        });

        <?php if ($loginStatus === 'success'): ?>
        Swal.fire({
            icon: 'success',
            title: 'เข้าสู่ระบบสำเร็จ!',
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            window.location.href = 'dashboard.php';
        });
        <?php elseif ($loginStatus === 'error'): ?>
        Swal.fire({
            icon: 'error',
            title: 'เข้าสู่ระบบล้มเหลว!',
            text: 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'
        });
        <?php endif; ?>
    });
    </script>
</body>

</html>
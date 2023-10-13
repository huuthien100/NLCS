<?php
require_once "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $checkEmailQuery = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($checkEmailQuery);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo '<script>alert("Email đã tồn tại. Vui lòng sử dụng một email khác!");</script>';
        echo '<script>setTimeout(function(){ window.location.href = "register.php"; }, 100);</script>';
        exit();
    }
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $insertQuery = "INSERT INTO users (username, email, password, access) VALUES (:username, :email, :password, '1')";
    $insertStmt = $pdo->prepare($insertQuery);
    $insertStmt->bindParam(':username', $username);
    $insertStmt->bindParam(':email', $email);
    $insertStmt->bindParam(':password', $hashedPassword);

    if ($insertStmt->execute()) {
        echo '<script>alert("Đăng ký thành công! Hãy thực hiện việc đăng nhập ngay!");</script>';
        echo '<script>setTimeout(function(){ window.location.href = "login.php"; }, 100);</script>';
        exit();
    } else {
        echo "Lỗi: " . $insertStmt->errorInfo()[2];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="icon" type="image/png" href="../asset/icon/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../asset/style.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="../asset/script.js"></script>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <form name="form_register" id="form_register" action="register.php" method="POST">
                <div class="title-image">
                    <img src="../asset/icon/register.png" alt="Hình ảnh tiêu đề">
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label"><i class="fa-solid fa-user"></i> Tên người dùng</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Tên người dùng"
                        required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label"><i class="fa-solid fa-envelope"></i> Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label"><i class="fa-solid fa-key"></i> Mật khẩu</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu"
                        required>
                </div>
                <div class="mb-3">
                    <label for="repassword" class="form-label"><i class="fa-solid fa-key"></i> Nhập Lại Mật khẩu</label>
                    <input type="password" class="form-control" id="repassword" name="repassword"
                        placeholder="Nhập lại mật khẩu" required>
                </div>
                Đã có tài khoản?<a href="../index.php"> Đăng nhập</a>
                <div class="center-button mt-3">
                    <button type="submit" class="btn btn-success">Đăng ký</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
<?php
session_start();
include 'connect.php';

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = :login OR username = :login";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':login', $login);

    try {
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['password'] == $password) {
                if ($user['access'] == 1) {
                    $_SESSION['id_user'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['username'] = $user['username'];
                    header("Location: ../index.php");
                    exit();
                } elseif ($user['access'] == 0) {
                    $_SESSION['id_user'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['username'] = $user['username'];
                    header("Location: admin.php");
                    exit();
                }
            } else {
                $login_error = "Sai mật khẩu. Vui lòng thử lại.";
            }
        } else {
            $login_error = "Email hoặc tên đăng nhập không tồn tại. Vui lòng thử lại.";
        }        
    } catch (PDOException $e) {
        echo "Lỗi trong quá trình kiểm tra đăng nhập: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="icon" type="image.png" href="asset/icon/favicon.png">
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
            <form name="form_login" id="form_login" action="login.php" method="POST">
                <div class="title-image">
                    <img src="../asset/icon/login.png" alt="Hình ảnh tiêu đề">
                </div>
                <div class="mb-3">
                    <label for="login" class="form-label"><i class="fa-solid fa-envelope"></i> Email hoặc tên đăng nhập</label>
                    <input type="text" class="form-control" id="login" name="login" placeholder="Email hoặc tên đăng nhập" required>
                    <?php
                    if (!empty($login_error)) {
                        echo '<div class="alert alert-danger mt-1">' . $login_error . '</div>';
                    }
                    ?>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label"><i class="fa-solid fa-key"></i> Mật khẩu</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu" required>
                    <?php
                    if (!empty($password_error)) {
                        echo '<div class="alert alert-danger mt-1">' . $password_error . '</div>';
                    }
                    ?>
                </div>
                Chưa có tài khoản?<a href="register.php"> Đăng ký</a>
                <div class="center-button mt-3">
                    <button type="submit" class="btn btn-success">Đăng ký</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
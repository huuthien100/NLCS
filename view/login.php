<?php
session_start();
include '../include/connect.php';

$login_error = '';
$password_error = '';

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
            if (password_verify($password, $user['password'])) {
                $_SESSION['id_user'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['username'] = $user['username'];
                header("Location: ../index.php");
                exit();
            } else {
                $password_error = "Sai mật khẩu. Vui lòng thử lại.";
            }
        } else {
            $login_error = "Email hoặc tên đăng nhập không tồn tại. Vui lòng thử lại.";
        }
    } catch (PDOException $e) {
        echo "Lỗi trong quá trình kiểm tra đăng nhập: " . $e->getMessage();
    }
}
?>

<?php include '../include/header.html'; ?>
<title>Đăng nhập</title>
<div class="container">
    <div class="form-container">
        <!-- Form -->
        <form name="form_login" id="form_login" action="login.php" method="POST">
            <div class="title-image">
                <img src="../asset/icon/login.png" alt="Hình ảnh tiêu đề">
            </div>
            <div class="mb-3">
                <label for="login" class="form-label"><i class="fa-solid fa-envelope"></i> Email hoặc tên đăng
                    nhập</label>
                <input type="text" class="form-control" id="login" name="login" placeholder="Email hoặc tên đăng nhập"
                    required>
                <?php
                if (!empty($login_error)) {
                    echo '<div class="alert alert-danger mt-1">' . $login_error . '</div>';
                }
                ?>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"><i class="fa-solid fa-key"></i> Mật khẩu</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu"
                    required>
                <?php
                if (!empty($password_error)) {
                    echo '<div class="alert alert-danger mt-1">' . $password_error . '</div>';
                }
                ?>
            </div>
            Chưa có tài khoản?<a href="register.php"> Đăng ký</a>
            <div class="center-button mt-3">
                <button type="submit" class="btn btn-success">Đăng nhập</button>
            </div>
        </form>
        <!-- End Form -->
    </div>
</div>
</body>

</html>
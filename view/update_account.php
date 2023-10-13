<?php
session_start();

require_once "connect.php";

if (isset($_POST['submit'])) {
    $newUsername = $_POST['newUsername'];
    $newEmail = $_POST['newEmail'];
    $newPassword = $_POST['newPassword'];

    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];

        if (!empty($newUsername) && !empty($newEmail)) {
            $sql = "UPDATE users SET username = :newUsername, email = :newEmail";

            if (!empty($newPassword)) {
                $sql .= ", password = :newPassword";
            }

            $sql .= " WHERE email = :email";

            if ($stmt = $pdo->prepare($sql)) {
                $stmt->bindParam(":newUsername", $newUsername, PDO::PARAM_STR);
                $stmt->bindParam(":newEmail", $newEmail, PDO::PARAM_STR);

                if (!empty($newPassword)) {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $stmt->bindParam(":newPassword", $hashedPassword, PDO::PARAM_STR);
                }

                $stmt->bindParam(":email", $email, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $_SESSION['username'] = $newUsername;
                    $_SESSION['email'] = $newEmail;
                    echo '<script>alert("Cập nhật thông tin thành công");</script>';
                    echo '<script>setTimeout(function(){ window.location.href = "account.php"; }, 100);</script>';
                    exit();
                } else {
                    echo "<script>alert('Lỗi: Không thể cập nhật thông tin người dùng.');</script>";
                }
            }
        } else {
            echo "<script>alert('Vui lòng cung cấp tên người dùng và email hợp lệ.');</script>";
        }
    } else {
        echo "<script>alert('Email người dùng không được tìm thấy trong phiên. Vui lòng đăng nhập lại.');</script>";
    }
}

unset($pdo);
?>



<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài Khoản</title>
    <link rel="icon" type="image/png" href="../asset/icon/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../asset/style.css">
    <script src="../asset/script.js"></script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-lg">
            <a class="navbar-brand p-1" href="../index.php">
                <img id="logo" src="../asset/icon/icon.png" alt="Logo">
            </a>

            <div class="d-flex justify-content-between mt-2">
                <a href="account.php" class="nav-icon1">
                    <i class="fa-solid fa-user"></i>
                </a>
                <span class="vr mx-2"></span>
                <a href="cart.html" class="nav-icon2"><i class="fas fa-shopping-cart"></i></a>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->

    <div class="container">
        <div class="form-container">
            <form name="form_account" id="form_account" action="update_account.php" method="POST">
                <div class="title-image mb-3">
                    <img src="../asset/icon/account.png" alt="Hình ảnh tiêu đề">
                </div>
                <div class="mb-3">
                    <label for="newUsername" class="form-label"><i class="fa-solid fa-user"></i> Tên người dùng
                        mới:</label>
                    <input type="text" class="form-control" id="newUsername" name="newUsername"
                        value="<?php echo $_SESSION['username']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="newEmail" class="form-label"><i class="fa-solid fa-envelope"></i> Email mới:</label>
                    <input type="text" class="form-control" id="newEmail" name="newEmail"
                        value="<?php echo $_SESSION['email']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="newPassword" class="form-label"><i class="fa-solid fa-key"></i> Mật khẩu mới:</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword"
                        placeholder="Nhập mật khẩu mới (để trống nếu không thay đổi)">
                </div>
                <div class="mb-3 center-button">
                    <button type="submit" name="submit" class="btn btn-success">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
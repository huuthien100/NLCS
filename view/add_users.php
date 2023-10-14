<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['submit'])) {
    $username = $_POST['newUsername'];
    $email = $_POST['newEmail'];
    $password = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);

    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $checkEmailQuery = "SELECT email FROM users WHERE email = :email";
        $checkEmailStmt = $pdo->prepare($checkEmailQuery);
        $checkEmailStmt->bindParam(':email', $email);
        $checkEmailStmt->execute();

        if ($checkEmailStmt->rowCount() > 0) {
            echo "<script>alert('Email đã được sử dụng! Vui lòng sử dụng Email khác!');</script>";
        } else {
            $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);

            if ($stmt->execute()) {
                echo "<script>alert('Thêm người dùng thành công!'); setTimeout(function(){window.location.href='users_manage.php';}, 1);</script>";
            } else {
                echo "<script>alert('Thêm người dùng thất bại!');</script>";
            }
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm người dùng</title>
    <link rel="icon" type="image/png" href="../asset/icon/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="../asset/style.css">
    <script src="../asset/script.js"></script>
</head>

<body>
    <!-- Nav 1 -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-lg">
            <a class="navbar-brand p-1" href="#">
                <img id="logo" src="../asset/icon/icon.png" alt="Logo">
            </a>

            <div class="d-flex justify-content-between">
                <div class="dropdown pt-3">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                        id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                        if (isset($_SESSION['username'])) {
                            echo "<span style='color: black;'>Xin chào, " . $_SESSION['username'] . "!</span>__";
                        }
                        ?>

                        <img src="../asset/icon/profile-user.png" alt="user.png" width="35" height="35"
                            class="rounded-circle">
                    </a>
                    <ul class="dropdown-menu bg-body-tertiary dropdown-menu-lg-end" style="z-index: 100000;">
                        <li><a class="dropdown-item" href="account.php">Tài khoản</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="users_manage.php">Quay lại</a></li>
                        <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Nav 1 -->
    <div class="container">
        <div class="form-container">
            <form name="form_add_user" id="form_add_user" action="add_users.php" method="POST">
                <div class="title-image mb-3">
                    <img src="../asset/icon/add_user.png" alt="Hình ảnh tiêu đề">
                </div>
                <div class="mb-3">
                    <label for="newUsername" class="form-label"><i class="fa-solid fa-user"></i> Tên người dùng:</label>
                    <input type="text" class="form-control" id="newUsername" name="newUsername" placeholder="Nhập tên người dùng" required>
                </div>
                <div class="mb-3">
                    <label for="newEmail" class="form-label"><i class="fa-solid fa-envelope"></i> Email:</label>
                    <input type="email" class="form-control" id="newEmail" name="newEmail" placeholder="Nhập Email" required>
                </div>
                <div class="mb-3">
                    <label for="newPassword" class="form-label"><i class="fa-solid fa-key"></i> Mật khẩu:</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Nhập mật khẩu" required>
                </div>

                <div class="mb-3 center-button">
                    <button type="submit" name="submit" class="btn btn-success">Thêm người dùng</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $("#form_add_user").validate({
                rules: {
                    newUsername: {
                        required: true,
                        minlength: 2,
                    },
                    newEmail: {
                        required: true,
                        email: true,
                    },
                    newPassword: {
                        required: true,
                        minlength: 6,
                        customPassword: true,
                    },
                },
                messages: {
                    newUsername: {
                        required: 'Bạn chưa nhập tên người dùng',
                        minlength: 'Tên người dùng phải có ít nhất 2 ký tự'
                    },
                    newEmail: 'Địa chỉ Email không hợp lệ',
                    newPassword: {
                        required: 'Bạn chưa nhập mật khẩu',
                        minlength: 'Mật khẩu phải có ít nhất 6 ký tự'
                    },
                },
                errorElement: 'div',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    if (element.prop('type') == 'checkbox') {
                        error.insertAfter(element.siblings('label'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-valid').removeClass('is-invalid');
                },
            });
        });
    </script>
</body>

</html>
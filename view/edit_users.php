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

            if (!empty($renewPassword)) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt->bindParam(":newPassword", $hashedPassword, PDO::PARAM_STR);
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
                    echo '<script>setTimeout(function(){ window.location.href = "users_manage.php"; }, 100);</script>';
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
    <link rel="stylesheet" href="../asset/style.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
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
                        <li><a class="dropdown-item" href="users_manage.php">Tài khoản</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#" onclick="history.go(-1);">Quay lại</a></li>
                        <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Nav 1 -->

    <div class="container">
        <div class="form-container">
            <form name="form_account" id="form_account" action="edit_users.php" method="POST">
                <div class="title-image mb-3">
                    <img src="../asset/icon/edit_user.png" alt="Hình ảnh tiêu đề">
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
                        placeholder="Nhập mật khẩu mới">
                </div>

                <div class="mb-3 center-button">
                    <button type="submit" name="submit" class="btn btn-success">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $.validator.addMethod(
            "customPassword",
            function (value, element) {
                return /^(?=.*[A-Za-z])(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]).*$/.test(value);
            },
            "Mật khẩu phải chứa ít nhất 1 chữ cái và 1 ký tự đặc biệt"
        );

        $("#form_account").validate({
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
    </script>
</body>

</html>
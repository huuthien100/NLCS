<?php
require '../include/connect.php';
require '../include/user_session.php';

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
include '../include/header.php';
?>
<title>Tài khoản</title>

<div class="container">
    <div class="form-container mb-5">
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
                    placeholder="Nhập mật khẩu mới">
            </div>
            <div class="mb-3">
                <label for="newPassword" class="form-label"><i class="fa-solid fa-key"></i> Nhập lại mật khẩu
                    mới:</label>
                <input type="password" class="form-control" id="renewPassword" name="renewPassword"
                    placeholder="Nhập lại mật khẩu mới">
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
            renewPassword: {
                required: true,
                minlength: 6,
                equalTo: "#newPassword",
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
            renewPassword: {
                required: 'Bạn chưa nhập mật khẩu',
                minlength: 'Mật khẩu phải có ít nhất 6 ký tự',
                equalTo: 'Mật khẩu không trùng khớp với mật khẩu đã nhập'
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
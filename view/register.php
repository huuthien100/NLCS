<?php
require '../include/connect.php';
require '../include/user_session.php';

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
<?php include '../include/header.html'; ?>
<title>Đăng ký</title>
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
    <script>
        $(document).ready(function (){
            $.validator.addMethod(
                "customPassword",
                function (value, element) {
                    return /^(?=.*[A-Za-z])(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]).*$/.test(value);
                },
                "Mật khẩu phải chứa ít nhất 1 chữ cái và 1 ký tự đặc biệt"
            );
            $("#form_register").validate({
                rules: {
                    username: {
                        required: true,
                        minlength: 2,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    password: {
                        required: true,
                        minlength: 6,
                        customPassword: true,
                    },
                    repassword: {
                        required: true,
                        equalTo: "#password",
                    },
                },
                messages: {
                    username: {
                        required: 'Bạn chưa nhập tên đăng nhập',
                        minlength: 'Tên đăng nhập phải có ít nhất 2 ký tự'
                    },
                    email: 'Địa chỉ Email không hợp lệ',
                    password: {
                        required: 'Bạn chưa nhập mật khẩu',
                        minlength: 'Mật khẩu phải có ít nhất 6 ký tự'
                    },
                    repassword: {
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
        )};
    </script>
</body>

</html>
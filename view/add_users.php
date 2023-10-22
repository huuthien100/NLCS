<?php
require '../include/connect.php';
require '../include/user_session.php';

function isEmailUsed($pdo, $email)
{
    $checkEmailQuery = "SELECT email FROM users WHERE email = :email";
    $checkEmailStmt = $pdo->prepare($checkEmailQuery);
    $checkEmailStmt->bindParam(':email', $email);
    $checkEmailStmt->execute();

    return $checkEmailStmt->rowCount() > 0;
}

function isUsernameUsed($pdo, $username)
{
    $checkUsernameQuery = "SELECT username FROM users WHERE username = :username";
    $checkUsernameStmt = $pdo->prepare($checkUsernameQuery);
    $checkUsernameStmt->bindParam(':username', $username);
    $checkUsernameStmt->execute();

    return $checkUsernameStmt->rowCount() > 0;
}

$name_error = "";
$email_error = "";

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['userRole'];

    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (isEmailUsed($pdo, $email)) {
            $email_error = "<span style='color: red;'>Email đã được sử dụng! Vui lòng sử dụng Email khác.</span>";
        } elseif (isUsernameUsed($pdo, $username)) {
            $name_error = "<span style='color: red;'>Tên người dùng đã được sử dụng! Vui lòng sử dụng tên khác.</span>";
        } else {
            $sql = "INSERT INTO users (username, email, password, access) VALUES (:username, :email, :password, :access)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':access', $role);

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
<?php include '../include/header-ad.php'; ?>
<title>Thêm thành viên</title>

<!-- Form -->
<div class="container">
    <div class="form-container">
        <form name="form_add_user" id="form_add_user" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
            method="POST">
            <div class="title-image mb-3">
                <img src="../asset/icon/add_user.png" alt="Hình ảnh tiêu đề">
            </div>
            <div class="mb-3">
                <label for="username" class="form-label"><i class="fa-solid fa-user"></i> Tên người dùng:</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Nhập tên người dùng"
                    required>
                <span class="error">
                    <?php echo $name_error; ?>
                </span>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label"><i class="fa-solid fa-envelope"></i> Email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Nhập Email" required>
                <span class="error">
                    <?php echo $email_error; ?>
                </span>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"><i class="fa-solid fa-key"></i> Mật khẩu:</label>
                <input type="password" class="form-control" id="Password" name="password" placeholder="Nhập mật khẩu"
                    required>
            </div>
            <div class="mb-3">
                <label for="userRole" class="form-label"><i class="fa-solid fa-users"></i> Vai trò:</label>
                <select class="form-control" id="userRole" name="userRole" required>
                    <option value="0">Admin</option>
                    <option value="1">User</option>
                </select>
            </div>
            <div class="mb-3 center-button">
                <button type="submit" name="submit" class="btn btn-success">Thêm người dùng</button>
            </div>
        </form>
    </div>
</div>
<!-- End Form -->
<script>
    $(document).ready(function () {
        $("#form_add_user").validate({
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
            },
            messages: {
                username: {
                    required: 'Bạn chưa nhập tên người dùng',
                    minlength: 'Tên người dùng phải có ít nhất 2 ký tự'
                },
                email: 'Địa chỉ Email không hợp lệ',
                password: {
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
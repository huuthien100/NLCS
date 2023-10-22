<?php
require '../include/connect.php';
require '../include/user_session.php';

function isEmailUsed($pdo, $email, $username)
{
    $checkEmailQuery = "SELECT email FROM users WHERE email = :email AND username != :username";
    $checkEmailStmt = $pdo->prepare($checkEmailQuery);
    $checkEmailStmt->bindParam(':email', $email);
    $checkEmailStmt->bindParam(':username', $username);
    $checkEmailStmt->execute();

    return $checkEmailStmt->rowCount() > 0;
}

function isUsernameUsed($pdo, $username, $currentUsername)
{
    $checkUsernameQuery = "SELECT username FROM users WHERE username = :username AND username != :currentUsername";
    $checkUsernameStmt = $pdo->prepare($checkUsernameQuery);
    $checkUsernameStmt->bindParam(':username', $username);
    $checkUsernameStmt->bindParam(':currentUsername', $currentUsername);
    $checkUsernameStmt->execute();

    return $checkUsernameStmt->rowCount() > 0;
}

$username = $_GET['username'];
$name_error = "";
$email_error = "";
$password_error = "";

if (isset($_POST['submit'])) {
    $newUsername = $_POST['username'];
    $email = $_POST['email'];
    $newPassword = $_POST['password'];
    $role = $_POST['userRole'];

    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (isEmailUsed($pdo, $email, $username)) {
            $email_error = "<span style='color: red;'>Email đã được sử dụng! Vui lòng sử dụng Email khác.</span>";
        } elseif (isUsernameUsed($pdo, $newUsername, $username)) {
            $name_error = "<span style='color: red;'>Tên người dùng đã được sử dụng! Vui lòng sử dụng tên khác.</span>";
        } else {
            $updateQuery = "UPDATE users SET username = :newUsername, email = :email, access = :access WHERE username = :currentUsername";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->bindParam(':newUsername', $newUsername);
            $updateStmt->bindParam(':email', $email);
            $updateStmt->bindParam(':access', $role);
            $updateStmt->bindParam(':currentUsername', $username);

            if ($updateStmt->execute()) {
                if (!empty($newPassword)) {
                    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                    $passwordUpdateQuery = "UPDATE users SET password = :password WHERE username = :currentUsername";
                    $passwordUpdateStmt = $pdo->prepare($passwordUpdateQuery);
                    $passwordUpdateStmt->bindParam(':password', $passwordHash);
                    $passwordUpdateStmt->bindParam(':currentUsername', $username);
                    $passwordUpdateStmt->execute();
                }

                echo "<script>alert('Cập nhật người dùng thành công!'); setTimeout(function(){window.location.href='users_manage.php';}, 1);</script>";
            } else {
                echo "<script>alert('Cập nhật người dùng thất bại!');</script>";
            }
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}

$selectQuery = "SELECT * FROM users WHERE username = :username";
$selectStmt = $pdo->prepare($selectQuery);
$selectStmt->bindParam(':username', $username);
$selectStmt->execute();
$userData = $selectStmt->fetch();

?>
<?php include '../include/header-ad.php'; ?>
<title>Chỉnh sửa thành viên</title>

<div class="container mb-5">
    <div class="form-container">
        <form name="form_edit_user" id="form_edit_user"
            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . '?username=' . $username); ?>" method="POST">
            <div class="title-image mb-3">
                <img src="../asset/icon/edit_user.png" alt="Hình ảnh tiêu đề">
            </div>
            <div class="mb-3">
                <label for="username" class="form-label"><i class="fa-solid fa-user"></i> Tên người dùng:</label>
                <input type="text" class="form-control" id="username" name="username"
                    value="<?php echo $userData['username']; ?>" required>
                <span class="error">
                    <?php echo $name_error; ?>
                </span>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label"><i class="fa-solid fa-envelope"></i> Email:</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="<?php echo $userData['email']; ?>" required>
                <span class="error">
                    <?php echo $email_error; ?>
                </span>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"><i class="fa-solid fa-key"></i> Mật khẩu:</label>
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="Để trống nếu không muốn thay đổi">
                <span class="error">
                    <?php echo $password_error; ?>
                </span>
            </div>
            <div class="mb-3">
                <label for="userRole" class="form-label"><i class="fa-solid fa-users"></i> Vai trò:</label>
                <select class="form-control" id="userRole" name="userRole" required>
                    <option value="0" <?php if ($userData['access'] == 0)
                        echo "selected"; ?>>Admin</option>
                    <option value="1" <?php if ($userData['access'] == 1)
                        echo "selected"; ?>>User</option>
                </select>
            </div>
            <div class="mb-3 center-button">
                <button type="submit" name="submit" class="btn btn-success">Cập nhật người dùng</button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#form_edit_user").validate({
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
                    minlength: 6,
                },
            },
            messages: {
                username: {
                    required: 'Bạn chưa nhập tên người dùng',
                    minlength: 'Tên người dùng phải có ít nhất 2 ký tự'
                },
                email: 'Địa chỉ Email không hợp lệ',
                password: 'Mật khẩu phải có ít nhất 6 ký tự'
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
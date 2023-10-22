<?php
require '../include/connect.php';
require '../include/user_session.php';
include '../include/header.php';
?>
<title>Tài khoản</title>
<!-- Display Account Infomation -->
<div class="container-fluid d-flex justify-content-end align-items-center mb-5">
    <div class="form-container">
        <form name="form_account" id="form_account" action="update_account.php" method="POST">
            <div class="title-image m-3">
                <img src="../asset/icon/account.png" alt="Hình ảnh tiêu đề">
            </div>
            <div class="mb-3">
                <label for="username" class="form-label"><i class="fa-solid fa-user"></i> Tên người dùng:</label>
                <input type="text" class="form-control" id="username" name="username"
                    value="<?php echo $user['username']; ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label"><i class="fa-solid fa-envelope"></i> Email:</label>
                <input type="text" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>"
                    readonly>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"><i class="fa-solid fa-key"></i> Mật khẩu:</label>
                <input type="password" class="form-control" id="password" name="password"
                    value="<?php echo $user['password']; ?>" readonly>
            </div>
            <div class="mb-3 center-button">
                <a href="update_account.php" class="btn btn-success">Cập nhật thông tin</a>
            </div>
        </form>
    </div>
</div>
<!-- End Display Account Infomation -->
</body>

</html>
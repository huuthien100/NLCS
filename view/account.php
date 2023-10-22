<?php
require '../include/connect.php';
require '../include/user_session.php';
?>
<?php include '../include/header.html'; ?>
<title>Tài khoản</title>
<!-- Nav 1 -->
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="../index.php">
            <img id="logo" src="../asset/icon/icon.png" alt="Logo" class="ms-5">
        </a>
        <div class="dropdown pt-3">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle me-4"
                id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <?php
                if (isset($_SESSION['username'])) {
                    echo "<span style='color: black;'>Xin chào, " . $_SESSION['username'] . "!</span>__";
                }
                ?>
                <img src="../asset/icon/profile-user.png" alt="user.png" width="35" height="35" class="rounded-circle">
            </a>
            <ul class="dropdown-menu bg-body-tertiary dropdown-menu-lg-end" style="z-index: 10000;">
                <li><a class="dropdown-item" href="../index.php">Trang chủ</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="view/logout.php">Đăng xuất</a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Nav 1 -->
<!-- Display Account Infomation -->
<div class="container-fluid d-flex justify-content-end align-items-center">
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
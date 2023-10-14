<?php
session_start();

if (!isset($_SESSION['email']) || !isset($_SESSION['username'])) {
    header("Location: view/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý</title>
    <link rel="icon" type="image/png" href="../asset/icon/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
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

                        <img src="../asset/icon/user.png" alt="user.png" width="35" height="35" class="rounded-circle">
                    </a>
                    <ul class="dropdown-menu bg-body-tertiary dropdown-menu-lg-end" style="z-index: 100000;">
                        <li><a class="dropdown-item" href="account.php">Tài khoản</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Nav 1 -->

    <!-- Sidebar -->
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-body-tertiary">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                    <ul class="nav flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                        <li class="nav-item">
                            <a href="#" class="nav-link align-middle px-0 font-black">
                                <i class="fas fa-home"></i> Trang chủ
                            </a>
                        </li>
                        <li>
                            <a href="#submenu1" data-bs-toggle="collapse" class="nav-link px-0 align-middle font-black">
                                <i class="fas fa-users"></i> Quản lý thành viên
                            </a>
                            <ul class="collapse nav flex-column ms-1" id="submenu1" data-bs-parent="#menu">
                                <li class="w-100">
                                    <a href="#" class="nav-link px-0 font-black"><i class="fa-solid fa-circle-plus"
                                            style="color: #0bef5b;"></i> Thêm mới</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#submenu2" data-bs-toggle="collapse" class="nav-link px-0 align-middle font-black">
                                <i class="fas fa-list"></i> Quản lý danh mục
                            </a>
                            <ul class="collapse nav flex-column ms-1" id="submenu2" data-bs-parent="#menu">
                                <li class="w-100">
                                    <a href="#" class="nav-link px-0 font-black"><i class="fa-solid fa-circle-plus"
                                            style="color: #0bef5b;"></i> Thêm mới</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#submenu3" data-bs-toggle="collapse" class="nav-link px-0 align-middle font-black">
                                <i class="fas fa-box-open"></i> Quản lý sản phẩm
                            </a>
                            <ul class="collapse nav flex-column ms-1" id="submenu3" data-bs-parent="#menu">
                                <li class="w-100">
                                    <a href="#" class="nav-link px-0 font-black"><i class="fa-solid fa-circle-plus"
                                            style="color: #0bef5b;"></i> Thêm mới</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="nav-link px-0 align-middle font-black">
                                <i class="fas fa-comments"></i> Quản lý bình luận
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col py-3">
                Trang quản lý
                <!-- Đây là nơi để bạn đặt nội dung bạn muốn hiển thị -->
                <p>Xin chào, đây là trang quản lý.</p>
                <p>Bạn có thể thêm bất kỳ nội dung nào ở đây, chẳng hạn như biểu đồ, báo cáo, danh sách thành viên, v.v.
                </p>
                <p>Hãy chỉnh sửa và thêm mã HTML, CSS và JavaScript tương ứng cho nội dung bạn muốn hiển thị.</p>
            </div>

        </div>
    </div>
    <!-- End Sidebar -->

</body>

</html>
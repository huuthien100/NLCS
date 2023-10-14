<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['username'])) {
    header("Location: view/login.php");
    exit;
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sqlMembers = "SELECT COUNT(*) FROM users";
    $stmtMembers = $pdo->prepare($sqlMembers);
    $stmtMembers->execute();
    $totalMembers = $stmtMembers->fetchColumn();

    $sqlProducts = "SELECT COUNT(*) FROM products";
    $stmtProducts = $pdo->prepare($sqlProducts);
    $stmtProducts->execute();
    $totalProducts = $stmtProducts->fetchColumn();

    $sqlComments = "SELECT COUNT(*) FROM comments";
    $stmtComments = $pdo->prepare($sqlComments);
    $stmtComments->execute();
    $totalComments = $stmtComments->fetchColumn();
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
    exit;
}
?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý bình luận</title>
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

                        <img src="../asset/icon/profile-user.png" alt="user.png" width="35" height="35"
                            class="rounded-circle">
                    </a>
                    <ul class="dropdown-menu bg-body-tertiary dropdown-menu-lg-end" style="z-index: 100000;">
                        <li><a class="dropdown-item" href="account.php">Tài khoản</a></li>
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
    <div class="container-fluid">
        <div class="row flex-nowrap">
             <!-- Sidebar -->
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-body-tertiary">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                    <ul class="nav flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                        <li class="nav-item">
                            <a href="admin.php" class="nav-link align-middle px-0 font-black">
                                <i class="fas fa-home"></i> Trang chủ
                            </a>
                        </li>
                        <li>
                            <a href="users_manage.php" class="nav-link px-0 align-middle font-black">
                                <i class="fas fa-users"></i> Quản lý thành viên
                            </a>
                        </li>
                        <li>
                            <a href="category_manage.php" class="nav-link px-0 align-middle font-black">
                                <i class="fas fa-list"></i> Quản lý danh mục
                            </a>
                        </li>
                        <li>
                            <a href="products_manage.php" class="nav-link px-0 align-middle font-black">
                                <i class="fas fa-box-open"></i> Quản lý sản phẩm
                            </a>
                        </li>
                        <li>
                            <a href="comment_manage.php" class="nav-link px-0 align-middle font-black">
                                <i class="fas fa-comments"></i> Quản lý bình luận
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- End Sidebar -->
            <div class="col py-3">
                <h1>Quản lý bình luận</h1>
                <div class="row">
                    <!-- Card số thành viên -->
                    <div class="col-md-4">
                        <div class="card mb-3" style="max-width: 540px;">
                            <div class="row g-0">
                                <div class="col-md-4 p-3 bg-primary">
                                    <img src="../asset/icon/user.png" class="img-fluid rounded-start" alt="...">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">Thành viên</h5>
                                        <p class="card-text">Số thành viên:
                                            <?php echo $totalMembers; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card sản phẩm -->
                    <div class="col-md-4">
                        <div class="card mb-3" style="max-width: 540px;">
                            <div class="row g-0">
                                <div class="col-md-4 p-3 bg-info">
                                    <img src="../asset/icon/shopping_cart.png" class="img-fluid rounded-start"
                                        alt="Sản phẩm">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">Sản phẩm</h5>
                                        <p class="card-text">Số sản phẩm:
                                            <?php echo $totalProducts; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card bình luận -->
                    <div class="col-md-4">
                        <div class="card mb-3" style="max-width: 540px;">
                            <div class="row g-0">
                                <div class="col-md-4 p-3 bg-warning">
                                    <img src="../asset/icon/comment.png" class="img-fluid rounded-start" alt="Sản phẩm">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">Bình luận</h5>
                                        <p class="card-text">Số bình luận:
                                            <?php echo $totalComments; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
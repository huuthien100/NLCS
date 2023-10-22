<?php
require '../include/connect.php';
require '../include/user_session.php';
try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sqlUsers = "SELECT COUNT(*) FROM users";
    $stmtUsers = $pdo->prepare($sqlUsers);
    $stmtUsers->execute();
    $totalUsers = $stmtUsers->fetchColumn();

    $sqlProducts = "SELECT COUNT(*) FROM products";
    $stmtProducts = $pdo->prepare($sqlProducts);
    $stmtProducts->execute();
    $totalProducts = $stmtProducts->fetchColumn();

    $sqlComments = "SELECT COUNT(*) FROM comments";
    $stmtComments = $pdo->prepare($sqlComments);
    $stmtComments->execute();
    $totalComments = $stmtComments->fetchColumn();

    $sqlCategory = "SELECT COUNT(*) FROM category";
    $stmtCategory = $pdo->prepare($sqlCategory);
    $stmtCategory->execute();
    $totalCategory = $stmtCategory->fetchColumn();
} catch (PDOException $e) {
    echo "Lỗi kết nối đến cơ sở dữ liệu: " . $e->getMessage();
    exit;
}
?>
<?php include '../include/header.html'; ?>
<title>Trang chủ quản lý</title>
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
                <li><a class="dropdown-item" href="admin.php">Trang quản lý</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Nav 1 -->
<!-- Main -->
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
            <h1 class="pb-2">Trang chủ quản lý</h1>
            <div class="row">
                <!-- Card thành viên -->
                <div class="col-md-6">
                    <div class="card mb-3" style="max-width: 540px;">
                        <div class="row g-0">
                            <div class="col-md-4 p-2 pt-1 bg-primary">
                                <img src="../asset/icon/user.png" class="img-fluid rounded-start" alt="...">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">Thành viên</h5>
                                    <p class="card-text">Số thành viên:
                                        <?php echo $totalUsers; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card danh mục -->
                <div class="col-md-6">
                    <div class="card mb-3" style="max-width: 540px;">
                        <div class="row g-0">
                            <div class="col-md-4 p-2 bg-success">
                                <img src="../asset/icon/list.png" class="img-fluid rounded-start" alt="Danh mục">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">Danh mục</h5>
                                    <p class="card-text">Số danh mục:
                                        <?php echo $totalCategory; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card sản phẩm -->
                <div class="col-md-6">
                    <div class="card mb-3" style="max-width: 540px;">
                        <div class="row g-0">
                            <div class="col-md-4 p-2 bg-info">
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
                <div class="col-md-6">
                    <div class="card mb-3" style="max-width: 540px;">
                        <div class="row g-0">
                            <div class="col-md-4 p-2 bg-warning">
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
<!-- End Main -->
</body>

</html>
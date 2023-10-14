<?php
session_start();
require 'connect.php';

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
    <title>Quản lý sản phẩm</title>
    <link rel="icon" type="image/png" href="../asset/icon/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../asset/style.css">
    <script src="../asset/script.js"></script>
    <style>
        .mr-3 {
            margin-right: 10px;
        }
    </style>

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
            <div class="col p-3">
                <h1>Quản lý sản phẩm</h1>
                <div class="wrapper">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mt-5 mb-3 clearfix">
                                    <a href="add_products.php" class="btn btn-success pull-right"><i
                                            class="fa fa-plus"></i> Thêm sản phẩm mới</a>
                                </div>
                                <?php
                                try {
                                    require_once "connect.php";

                                    $sql = "SELECT products.*, category.name_category FROM products JOIN category ON products.id_category = category.id_category";
                                    $stmt = $pdo->query($sql);

                                    if ($stmt->rowCount() > 0) {
                                        echo '<table class="table table-bordered table-striped">';
                                        echo "<thead>";
                                        echo "<tr>";
                                        echo "<th>Grade</th>";
                                        echo "<th>Tên sản phẩm</th>";
                                        echo "<th>Hình ảnh sản phẩm</th>";
                                        echo "<th>Giá sản phẩm</th>";
                                        echo "<th>Thao tác</th>";
                                        echo "</tr>";
                                        echo "</thead>";
                                        echo "<tbody>";

                                        while ($row = $stmt->fetch()) {
                                            echo "<tr>";
                                            echo "<td>" . $row['name_category'] . "</td>";
                                            echo "<td>" . $row['product_name'] . "</td>";
                                            echo "<td><img src='" . $row['product_img'] . "' width='50' height='50'></td>";
                                            echo "<td>" . $row['product_price'] . " VNĐ</td>";
                                            echo "<td>";
                                            echo '<a href="update_product.php?id=' . $row['id_product'] . '" class="mr-3" title="Chỉnh sửa sản phẩm" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="#" class="mr-3 delete-product" title="Xóa sản phẩm" data-toggle="tooltip" data-product-id="' . $row['id_product'] . '"><span class="fa fa-trash"></span></a>';

                                            echo "</td>";
                                            echo "</tr>";
                                        }

                                        echo "</tbody>";
                                        echo "</table>";
                                    } else {
                                        echo '<div class="alert alert-danger"><em>Không tìm thấy bản ghi nào.</em></div>';
                                    }
                                } catch (PDOException $e) {
                                    echo "Oops! Có lỗi xảy ra: " . $e->getMessage();
                                }
                                $pdo = null;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="confirmDeleteProductModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabelProduct" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabelProduct">Xác nhận xóa sản phẩm</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                style="border: none; box-shadow: none; background: none; font-size: 25px">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Bạn có chắc chắn muốn xóa sản phẩm này?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                            <button type="button" class="btn btn-danger" id="confirmDeleteProductButton">Xóa</button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function () {
                    $(".delete-product").on("click", function () {
                        var productId = $(this).data("product-id");

                        $("#confirmDeleteProductButton").data("product-id", productId);

                        $("#confirmDeleteProductModal").modal("show");
                    });

                    $("#confirmDeleteProductButton").on("click", function () {
                        var productId = $(this).data("product-id");
                        window.location.href = "delete_product.php?id=" + productId;
                    });

                    $("#confirmDeleteProductModal .btn-secondary").on("click", function () {
                        $("#confirmDeleteProductModal").modal("hide");
                    });

                    $("#confirmDeleteProductModal .close").on("click", function () {
                        $("#confirmDeleteProductModal").modal("hide");
                    });
                });
            </script>
        </div>
    </div>
</body>

</html>
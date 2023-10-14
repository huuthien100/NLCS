<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['submit'])) {
    $categoryName = $_POST['newCategoryName'];

    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $checkCategoryQuery = "SELECT name_category FROM category WHERE name_category = :categoryName";
        $checkCategoryStmt = $pdo->prepare($checkCategoryQuery);
        $checkCategoryStmt->bindParam(':categoryName', $categoryName);
        $checkCategoryStmt->execute();

        if ($checkCategoryStmt->rowCount() > 0) {
            echo "<script>alert('Tên danh mục đã được sử dụng! Vui lòng sử dụng tên khác!');</script>";
        } else {
            $sql = "INSERT INTO category (name_category) VALUES (:categoryName)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':categoryName', $categoryName);

            if ($stmt->execute()) {
                echo "<script>alert('Thêm danh mục thành công!' );</script>";
                echo "<script>setTimeout(function() {
                    window.location.href = 'category_manage.php';
                }, 10);</script>";
            } else {
                echo "<script>alert('Thêm danh mục thất bại!');</script>";
            }
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm danh mục</title>
    <link rel="icon" type="image/png" href="../asset/icon/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
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
                        <li><a class="dropdown-item" href="category_manage.php">Quay lại</a></li>
                        <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Nav 1 -->
    <div class="container">
        <div class="form-container">
            <form name="form_add_category" id="form_add_category" action="add_category.php" method="POST">
                <div class="title-image mb-3">
                    <img src="../asset/icon/add_category.png" alt="Hình ảnh tiêu đề" style="width: 150%">
                </div>
                <div class="mb-3">
                    <label for="newCategoryName" class="form-label"><i class="fa-solid fa-list"></i> Tên danh
                        mục:</label>
                    <input type="text" class="form-control" id="newCategoryName" name="newCategoryName"
                        placeholder="Nhập tên danh mục" required>
                </div>

                <div class="mb-3 center-button">
                    <button type="submit" name="submit" class="btn btn-success">Thêm danh mục</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $("#form_add_category").validate({
                rules: {
                    newCategoryName: {
                        required: true,
                        minlength: 2,
                    },
                },
                messages: {
                    newCategoryName: {
                        required: 'Bạn chưa nhập tên danh mục',
                        minlength: 'Tên danh mục phải có ít nhất 2 ký tự'
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
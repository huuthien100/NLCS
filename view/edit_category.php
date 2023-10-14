<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['username'])) {
    header("Location: view/login.php");
    exit;
}

if (isset($_POST['edit_category'])) {
    try {
        $category_id = $_POST['category_id'];
        $new_category_name = $_POST['new_category_name'];

        $updateSql = "UPDATE category SET name_category = :new_category_name WHERE id_category = :category_id";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->bindParam(':new_category_name', $new_category_name, PDO::PARAM_STR);
        $updateStmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);

        if ($updateStmt->execute()) {
            echo '<script>alert("Danh mục đã được cập nhật thành công.");</script>';
            echo '<script>setTimeout(function(){ window.location.href = "category_manage.php"; }, 100);</script>';
            exit();
        } else {
            echo '<script>alert("Có lỗi xảy ra: Không thể cập nhật danh mục.");</script>';
            header("Location: category_manage.php");
            exit();
        }
    } catch (PDOException $e) {
        echo '<script>alert("Có lỗi xảy ra: ' . $e->getMessage() . '");</script>';
        header("Location: category_manage.php");
        exit();
    }
}

if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    try {
        $sql = "SELECT name_category FROM category WHERE id_category = :category_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo '<script>alert("Có lỗi xảy ra: ' . $e->getMessage() . '");</script>';
        header("Location: category_manage.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa danh mục</title>
    <link rel="icon" type="image/png" href="../asset/icon/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="../asset/script.js"></script>
    <link rel="stylesheet" href="../asset/style.css">
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
                        <li><a class="dropdown-item" href="admin.php">Quay lại</a></li>
                        <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Nav 1 -->

    <div class="container">
        <div class="form-container">
            <form method="post" id="form_category">
                <div class="title-image mb-3">
                    <img src="../asset/icon/edit_category.png" alt="Hình ảnh tiêu đề">
                </div>
                <div class="mb-3">
                    <label for="new_category_name" class="form-label"><i class="fa-solid fa-list"></i> Tên danh mục
                        mới:</label>
                    <input type="text" class="form-control" id="new_category_name" name="new_category_name"
                        value="<?php echo $category['name_category']; ?>" required>
                </div>
                <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
                <div class="mb-3 center-button">
                    <button type="submit" name="edit_category" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $("#form_category").validate({
            rules: {
                new_category_name: {
                    required: true,
                    minlength: 2,
                },
            },
            messages: {
                new_category_name: {
                    required: 'Vui lòng nhập tên danh mục',
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
    </script>
</body>

</html>
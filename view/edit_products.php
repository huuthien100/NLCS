<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$product_id = $category_id = $product_name = $product_img = $product_price = "";
$category_id_err = $product_name_err = $product_img_err = $product_price_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['edit_product'])) {
        try {
            $product_id = $_POST['product_id'];
            $category_id = $_POST['category_id'];
            $product_name = $_POST['product_name'];
            $product_price = $_POST['product_price'];

            if (isset($_FILES["product_img"]) && $_FILES["product_img"]["error"] === UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["product_img"]["tmp_name"];
                $file_name = $_FILES["product_img"]["name"];
                $upload_dir = "../asset/product_img/";

                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $product_img = $upload_dir . $file_name;

                if (move_uploaded_file($tmp_name, $product_img)) {
                } else {
                    $product_img_err = "Không thể tải lên ảnh.";
                }
            } else {
                $product_img = $_POST['existing_product_img'];
            }

            $updateSql = "UPDATE products 
                          SET id_category = :category_id, 
                              product_name = :product_name, 
                              product_img = :product_img, 
                              product_price = :product_price 
                          WHERE id_product = :product_id";

            $updateStmt = $pdo->prepare($updateSql);

            $updateStmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $updateStmt->bindParam(':product_name', $product_name, PDO::PARAM_STR);
            $updateStmt->bindParam(':product_img', $product_img, PDO::PARAM_STR);
            $updateStmt->bindParam(':product_price', $product_price, PDO::PARAM_STR);
            $updateStmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);

            if ($updateStmt->execute()) {
                echo '<script>alert("Sản phẩm đã được cập nhật thành công.");</script>';
                echo '<script>setTimeout(function(){ window.location.href = "products_manage.php"; }, 100);</script>';
                exit();
            } else {
                echo '<script>alert("Có lỗi xảy ra: Không thể cập nhật sản phẩm.");</script>';
                header("Location: products_manage.php");
                exit();
            }
        } catch (PDOException $e) {
            echo '<script>alert("Có lỗi xảy ra: ' . $e->getMessage() . '");</script>';
            header("Location: products_manage.php");
            exit();
        }
    }
}

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    try {
        $sql = "SELECT p.id_product, p.product_name, p.product_img, p.product_price, c.name_category 
                FROM products p
                INNER JOIN category c ON p.id_category = c.id_category
                WHERE p.id_product = :product_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo '<script>alert("Có lỗi xảy ra: ' . $e->getMessage() . '");</script>';
        header("Location: products_manage.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa sản phẩm</title>
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
                        <li><a class="dropdown-item" href="products_manage.php">Quay lại</a></li>
                        <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Nav 1 -->

    <div class="container">
        <div class="form-container">
            <form method="post" id="form_edit_product" enctype="multipart/form-data">
                <div class="title-image mb-3">
                    <img src="../asset/icon/edit_product.png" alt="Hình ảnh tiêu đề">
                </div>
                <div class="mb-3">
                    <label for="category_id" class="form-label"><i class="fa-solid fa-list"></i> Danh mục:</label>
                    <select class="form-control" id="category_id" name="category_id" required>
                        <option value="">Chọn danh mục</option>
                        <?php
                        $categorySql = "SELECT id_category, name_category FROM category";
                        $categoryResult = $pdo->query($categorySql);

                        while ($row = $categoryResult->fetch(PDO::FETCH_ASSOC)) {
                            $categoryId = $row['id_category'];
                            $categoryName = $row['name_category'];
                            $selected = ($categoryId == $product['id_category']) ? "selected" : "";
                            echo "<option value='$categoryId' $selected>$categoryName</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="product_name" class="form-label"><i class="fa-solid fa-file"></i> Tên sản phẩm:</label>
                    <input type="text" class="form-control" id="product_name" name="product_name"
                        value="<?php echo $product['product_name']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="product_img" class="form-label"><i class="fa-solid fa-image"></i> Hình ảnh sản phẩm:</label>
                    <input type="file" class="form-control" id="product_img" name="product_img" accept="image/*">
                    <input type="hidden" name="existing_product_img" value="<?php echo $product['product_img']; ?>">
                </div>
                <div class="mb-3">
                    <label for="product_price" class="form-label"><i class="fa-solid fa-dollar-sign"></i> Giá sản phẩm:</label>
                    <input type="text" class="form-control" id="product_price" name="product_price"
                        value="<?php echo $product['product_price']; ?>" required>
                </div>
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <div class="mb-3 center-button">
                    <button type="submit" name="edit_product" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>

<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['username'])) {
    header("Location: view/login.php");
    exit;
}

$category_id = $product_name = $product_img = $product_price = $product_detail = "";
$category_id_err = $product_name_err = $product_img_err = $product_price_err = $product_detail_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate category ID
    $input_category_id = trim($_POST["category_id"]);
    if (empty($input_category_id)) {
        $category_id_err = "Please select a category.";
    } else {
        $category_id = $input_category_id;
    }

    $input_product_name = trim($_POST["product_name"]);
    if (empty($input_product_name)) {
        $product_name_err = "Please enter a product name.";
    } else {
        $product_name = $input_product_name;
    }

    if (isset($_FILES["product_img"]) && $_FILES["product_img"]["error"] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES["product_img"]["tmp_name"];
        $file_name = $_FILES["product_img"]["name"];
        $upload_dir = "../asset/product_img";

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $product_img = $upload_dir . $file_name;

        if (move_uploaded_file($tmp_name, $product_img)) {
        } else {
            $product_img_err = "Failed to upload the image.";
        }
    } else {
        $product_img_err = "Please select a valid image file.";
    }

    $input_product_price = trim($_POST["product_price"]);
    if (empty($input_product_price) || !is_numeric($input_product_price) || $input_product_price <= 0) {
        $product_price_err = "Please enter a valid product price.";
    } else {
        $product_price = $input_product_price;
    }

    $input_product_detail = trim($_POST["product_detail"]);
    if (empty($input_product_detail)) {
        $product_detail_err = "Please enter product details.";
    } else {
        $product_detail = $input_product_detail;
    }

    if (empty($category_id_err) && empty($product_name_err) && empty($product_img_err) && empty($product_price_err) && empty($product_detail_err)) {
        $sql = "INSERT INTO products (id_category, product_name, product_img, product_price, product_detail) VALUES (:id_category, :product_name, :product_img, :product_price, :product_detail)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":id_category", $category_id, PDO::PARAM_INT);
        $stmt->bindParam(":product_name", $product_name, PDO::PARAM_STR);
        $stmt->bindParam(":product_img", $product_img, PDO::PARAM_STR);
        $stmt->bindParam(":product_price", $product_price, PDO::PARAM_STR);
        if ($stmt->execute()) {
            header("location: products_manage.php");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }
    }
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
    <div class="container">
        <div class="form-container">
            <form name="form_add_product" id="form_add_product"
                action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"
                enctype="multipart/form-data">
                <div class="title-image mb-3">
                    <img src="../asset/icon/add_product.png" alt="Hình ảnh tiêu đề" style="width: 150%">
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
                            echo "<option value='$categoryId'>$categoryName</option>";
                        }
                        ?>
                    </select>
                    <span class="help-block">
                        <?php echo $category_id_err; ?>
                    </span>
                </div>
                <div class="mb-3">
                    <label for="product_name" class="form-label"><i class="fa-solid fa-file"></i> Tên sản phẩm:</label>
                    <input type="text" class="form-control" id="product_name" name="product_name"
                        placeholder="Nhập tên sản phẩm" required value="<?php echo $product_name; ?>">
                    <span class="help-block">
                        <?php echo $product_name_err; ?>
                    </span>
                </div>
                <div class="mb-3">
                    <label for="product_img" class="form-label"><i class="fa-solid fa-image"></i> Hình ảnh sản
                        phẩm:</label>
                    <input type="file" class="form-control" id="product_img" name="product_img" accept="image/*"
                        required>
                    <span class="help-block">
                        <?php echo $product_img_err; ?>
                    </span>
                </div>

                <div class="mb-3">
                    <label for="product_price" class="form-label"><i class="fa-solid fa-dollar-sign"></i> Giá sản
                        phẩm:</label>
                    <input type="text" class="form-control" id="product_price" name="product_price"
                        placeholder="Nhập giá sản phẩm" required value="<?php echo $product_price; ?>">
                    <span class="help-block">
                        <?php echo $product_price_err; ?>
                    </span>
                </div>
                <div class="center-button">
                    <button type="submit" name="submit" class="btn btn-success">Thêm sản phẩm</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
<?php
require '../include/connect.php';
require '../include/user_session.php';
function isProductNameExistsInCategory($pdo, $product_name, $category_id)
{
    $check_name_query = "SELECT product_name FROM products WHERE product_name = :product_name AND id_category = :category_id";
    $check_name_stmt = $pdo->prepare($check_name_query);
    $check_name_stmt->bindParam(':product_name', $product_name, PDO::PARAM_STR);
    $check_name_stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    $check_name_stmt->execute();

    return $check_name_stmt->rowCount() > 0;
}
function getCategoryName($pdo, $category_id)
{
    $query = "SELECT name_category FROM category WHERE id_category = :category_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":category_id", $category_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['name_category'] : false;
}

$category_error = $name_error = $img_error = $price_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_category_id = trim($_POST["category_id"]);
    if (empty($input_category_id)) {
        $category_error = "<span style='color: red;'>Bạn chưa chọn Grade.</span>";
    } else {
        $category_id = $input_category_id;
    }

    $input_product_name = trim($_POST["product_name"]);
    if (empty($input_product_name)) {
        $name_error = "<span style='color: red;'>Bạn chưa nhập tên sản phẩm</span>";
    } else {
        $product_name = $input_product_name;

        if (isProductNameExistsInCategory($pdo, $product_name, $category_id)) {
            $name_error = "<span style='color: red;'>Tên sản phẩm đã tồn tại</span>";
        }
    }

    if (isset($_FILES["product_img"]) && $_FILES["product_img"]["error"] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES["product_img"]["tmp_name"];
        $file_name = $_FILES["product_img"]["name"];

        $folder_name = preg_replace('/ \(\d+\)\.jpg/', '', $file_name);

        $category_name = getCategoryName($pdo, $category_id);

        if ($category_name) {
            $upload_dir = "../asset/product/$category_name/$folder_name/";

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $product_img = $upload_dir . $file_name;

            if (move_uploaded_file($tmp_name, $product_img)) {
            } else {
                $img_error = "<span style='color: red;'>Bạn chưa chọn hình ảnh sản phẩm.</span>";
            }
        }
    }

    $input_product_price = trim($_POST["product_price"]);
    if (empty($input_product_price) || !is_numeric($input_product_price) || $input_product_price <= 0) {
        $price_error = "<span style='color: red;'>Giá tiền bạn chọn không hợp lệ.</span>";
    } else {
        $product_price = $input_product_price;
    }

    if (empty($category_error) && empty($name_error) && empty($img_error) && empty($price_error)) {
        $sql = "INSERT INTO products (id_category, product_name, product_img, product_price) VALUES (:id_category, :product_name, :product_img, :product_price)";

        $addStmt = $pdo->prepare($sql);

        $addStmt->bindParam(":id_category", $category_id, PDO::PARAM_INT);
        $addStmt->bindParam(":product_name", $product_name, PDO::PARAM_STR);
        $addStmt->bindParam(":product_img", $product_img, PDO::PARAM_STR);
        $addStmt->bindParam(":product_price", $product_price, PDO::PARAM_STR);

        if ($addStmt->execute()) {
            echo '<script>alert("Sản phẩm đã được thêm thành công.");</script>';
            echo '<script>setTimeout(function(){ window.location.href = "products_manage.php"; }, 100);</script>';
            exit();
        } else {
            echo '<script>alert("Có lỗi xảy ra. Không thể thêm sản phẩm.");</script>';
            header("Location: products_manage.php");
            exit();
        }
    }
}
?>
<?php include '../include/header.html'; ?>
<title>Thêm sản phẩm</title>
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
                    echo "<span style='color: black;'>Xin chào, " . $user['username'] . "!</span>__";
                    ?>

                    <img src="../asset/icon/profile-user.png" alt="user.png" width="35" height="35"
                        class="rounded-circle">
                </a>
                <ul class="dropdown-menu bg-body-tertiary dropdown-menu-lg-end" style="z-index: 100000;">
                    <li><a class="dropdown-item" href="admin.php">Trang chủ</a></li>
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
<!-- Form -->
<div class="container">
    <div class="form-container">
        <form name="form_add_product" id="form_add_product"
            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
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
                <span class="error">
                    <?php echo $category_error; ?>
                </span>
            </div>
            <div class="mb-3">
                <label for="product_name" class="form-label"><i class="fa-solid fa-file"></i> Tên sản phẩm:</label>
                <input type="text" class="form-control" id="product_name" name="product_name"
                    placeholder="Nhập tên sản phẩm" required>
                <span class="error">
                    <?php echo $name_error; ?>
                </span>
            </div>
            <div class="mb-3">
                <label for="product_img" class="form-label"><i class="fa-solid fa-image"></i> Hình ảnh sản
                    phẩm:</label>
                <input type="file" class="form-control" id="product_img" name="product_img" accept="image/*" required>
                <span class="error">
                    <?php echo $img_error; ?>
                </span>
            </div>

            <div class="mb-3">
                <label for="product_price" class="form-label"><i class="fa-solid fa-dollar-sign"></i> Giá sản
                    phẩm:</label>
                <input type="text" class="form-control" id="product_price" name="product_price"
                    placeholder="Nhập giá sản phẩm" required>
                <span class="error">
                    <?php echo $price_error; ?>
                </span>
            </div>
            <div class="center-button">
                <button type="submit" name="submit" class="btn btn-success">Thêm sản phẩm</button>
            </div>
        </form>
    </div>
</div>
<!-- End Form -->
</body>

</html>
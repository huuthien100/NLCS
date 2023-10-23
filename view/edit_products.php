<?php
require '../include/connect.php';
require '../include/user_session.php';
function getCategoryName($pdo, $category_id)
{
    $query = "SELECT name_category FROM category WHERE id_category = :category_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":category_id", $category_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['name_category'] : false;
}

function isProductNameExistsInCategory($pdo, $product_name, $category_id, $product_id)
{
    $check_name_query = "SELECT product_name FROM products WHERE product_name = :product_name AND id_category = :category_id AND id_product <> :product_id";
    $check_name_stmt = $pdo->prepare($check_name_query);
    $check_name_stmt->bindParam(':product_name', $product_name, PDO::PARAM_STR);
    $check_name_stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    $check_name_stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $check_name_stmt->execute();

    return $check_name_stmt->rowCount() > 0;
}

$product_id = $category_id = $product_name = $product_img = $product_price = "";
$category_error = $name_error = $img_error = $price_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['edit_product'])) {
        try {
            $product_id = $_POST['product_id'];
            $category_id = $_POST['category_id'];
            $product_name = $_POST['product_name'];
            $product_price = $_POST['product_price'];

            if (empty($category_id)) {
                $category_error = "<span style='color: red;'>Vui lòng chọn danh mục.</span>";
            }

            if (empty($product_name)) {
                $name_error = "<span style='color: red;'>Vui lòng nhập tên sản phẩm.</span>";
            } elseif (isProductNameExistsInCategory($pdo, $product_name, $category_id, $product_id)) {
                $name_error = "<span style='color: red;'>Tên sản phẩm đã tồn tại trong danh mục này.</span>";
            }

            if (isset($_FILES["product_img"]) && $_FILES["product_img"]["error"] === UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["product_img"]["tmp_name"];
                $file_name = $_FILES["product_img"]["name"];

                $category_name = getCategoryName($pdo, $category_id);
                $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

                $new_file_name = $product_name . '-' . 1 . '.' . $file_extension;

                $upload_dir = "../asset/product/$category_name/$category_name-$product_name/";

                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $product_img = $upload_dir . $new_file_name;

                if (move_uploaded_file($tmp_name, $product_img)) {
                } else {
                    $img_error = "<span style='color: red;'>Lỗi khi tải lên hình ảnh sản phẩm.</span>";
                }
            }

            if (empty($product_price) || !is_numeric($product_price) || $product_price <= 0) {
                $price_error = "<span style='color: red;'>Giá sản phẩm không hợp lệ.</span>";
            }

            if (empty($category_error) && empty($name_error) && empty($img_error) && empty($price_error)) {
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
<?php include '../include/header-ad.php'; ?>
<title>Chỉnh sửa sản phẩm</title>

<!-- Form -->
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
                <span class="error">
                    <?php echo $category_error; ?>
                </span>
            </div>
            <div class="mb-3">
                <label for="product_name" class="form-label"><i class="fa-solid fa-file"></i> Tên sản phẩm:</label>
                <input type="text" class="form-control" id="product_name" name="product_name"
                    value="<?php echo $product['product_name']; ?>" required>
                <span class="error">
                    <?php echo $name_error; ?>
                </span>
            </div>
            <div class="mb-3">
                <label for="product_img" class="form-label"><i class="fa-solid fa-image"></i> Hình ảnh sản phẩm:</label>
                <input type="file" class="form-control" id="product_img" name="product_img" accept="image/*">
                <input type="hidden" name="existing_product_img" value="<?php echo $product['product_img']; ?>">
                <span class="error">
                    <?php echo $img_error; ?>
                </span>
            </div>
            <div class="mb-3">
                <label for="product_price" class="form-label"><i class="fa-solid fa-dollar-sign"></i> Giá sản
                    phẩm:</label>
                <input type="text" class="form-control" id="product_price" name="product_price"
                    value="<?php echo $product['product_price']; ?>" required>
                <span class="error">
                    <?php echo $price_error; ?>
                </span>
            </div>
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            <div class="mb-3 center-button">
                <button type="submit" name="edit_product" class="btn btn-primary">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
<!-- End Form -->
</body>

</html>
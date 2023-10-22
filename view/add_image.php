<?php
require '../include/connect.php';
require '../include/user_session.php';
$img_error = '';

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    function getCategoriesId($pdo, $product_id) {
        $query = "SELECT id_category FROM products WHERE id_product = :product_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":product_id", $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['id_category'] : false;
    }

    function getCategoryName($pdo, $category_id) {
        $query = "SELECT name_category FROM category WHERE id_category = :category_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":category_id", $category_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['name_category'] : false;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_FILES["product_img"]) && $_FILES["product_img"]["error"] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES["product_img"]["tmp_name"];
            $file_name = $_FILES["product_img"]["name"];

            $folder_name = preg_replace('/ \(\d+\)\.jpg/', '', $file_name);
            $category_id = getCategoriesId($pdo, $product_id);
            $category_name = getCategoryName($pdo, $category_id);

            if ($category_name) {
                $upload_dir = "../asset/product/$category_name/$folder_name/";

                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $product_img = $upload_dir . $file_name;

                if (move_uploaded_file($tmp_name, $product_img)) {
                    $sql = "INSERT INTO product_img (img_url) VALUES (:img_url)";
                    $addStmt = $pdo->prepare($sql);
                    $addStmt->bindParam(":img_url", $product_img, PDO::PARAM_STR);
                
                    if ($addStmt->execute()) {
                        echo '<script>alert("Hình ảnh đã được thêm thành công.");</script>';
                        echo '<script>setTimeout(function(){ window.location.href = "products_manage.php"; }, 100);</script>';
                        exit();
                    } else {
                        echo '<script>alert("Có lỗi xảy ra. Không thể thêm sản phẩm.");</script>';
                        header("Location: products_manage.php");
                        exit();
                    }
                } else {
                    $img_error = "<span style='color: red;'>Không thể di chuyển tệp hình ảnh.</span>";
                }
                
            }                
        }
    }
}
?>
<?php include '../include/header-ad.php'; ?>
<title>Thêm hình ảnh</title>

<!-- Form -->
<div class="container">
    <div class="form-container">
        <form name="form_add_product" id="form_add_product"
            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?product_id=$product_id"; ?>" method="POST" enctype="multipart/form-data">
            <div class="title-image mb-3">
                <img src="../asset/icon/add_product.png" alt="Hình ảnh tiêu đề" style="width: 150%">
            </div>
            <div class="mb-3">
                <label for="product_img" class="form-label"><i class="fa-solid fa-image"></i> Hình ảnh sản
                    phẩm:</label>
                <input type="file" class="form-control" id="product_img" name="product_img" accept="image/*" required>
                <span class="error">
                    <?php echo $img_error; ?>
                </span>
            </div>
            <div class="center-button">
                <button type="submit" name="submit" class="btn btn-success">Thêm hình ảnh</button>
            </div>
        </form>
    </div>
</div>
<!-- End Form -->
</body>
</html>

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

        $category_name = getCategoryName($pdo, $category_id);

        if ($category_name) {
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
        } else {
            $img_error = "<span style='color: red;'>Không tìm thấy tên danh mục.</span>";
        }
    }

    $input_product_price = trim($_POST["product_price"]);
    if (empty($input_product_price) || !is_numeric($input_product_price) || $input_product_price <= 0) {
        $price_error = "<span style='color: red;'>Giá tiền bạn chọn không hợp lệ.</span>";
    } else {
        $product_price = $input_product_price;
    }

    if (empty($category_error) && empty($name_error) && empty($img_error) && empty($price_error)) {
        $insertProductSql = "INSERT INTO products (id_category, product_name, product_img, product_price) VALUES (:id_category, :product_name, :product_img, :product_price)";

        $insertImgSql = "INSERT INTO product_img (id_product, img_url) VALUES (:id_product, :img_url)";

        try {
            $pdo->beginTransaction();

            $insertProductStmt = $pdo->prepare($insertProductSql);
            $insertProductStmt->bindParam(":id_category", $category_id, PDO::PARAM_INT);
            $insertProductStmt->bindParam(":product_name", $product_name, PDO::PARAM_STR);
            $insertProductStmt->bindParam(":product_img", $product_img, PDO::PARAM_STR);
            $insertProductStmt->bindParam(":product_price", $product_price, PDO::PARAM_STR);
            $insertProductStmt->execute();

            $newProductId = $pdo->lastInsertId();

            $insertImgStmt = $pdo->prepare($insertImgSql);
            $insertImgStmt->bindParam(":id_product", $newProductId, PDO::PARAM_INT);
            $insertImgStmt->bindParam(":img_url", $product_img, PDO::PARAM_STR);
            $insertImgStmt->execute();

            $pdo->commit();

            echo '<script>alert("Sản phẩm đã được thêm thành công.");</script>';
            echo '<script>setTimeout(function(){ window.location.href = "products_manage.php"; });</script>';
            exit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo '<script>alert("Có lỗi xảy ra. Không thể thêm sản phẩm.");</script>';
            header("Location: products_manage.php");
            exit();
        }
    }
}
?>
<?php include '../include/header-ad.php'; ?>
<title>Thêm sản phẩm</title>

<!-- Form -->
<div class="container">
    <div class="form-container mb-5">
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
<script>
    $(document).ready(function () {
        $("#form_add_product").validate({
            rules: {
                category_id: {
                    required: true,
                },
                product_name: {
                    required: true,
                    minlength: 2,
                },
                product_img: {
                    required: true,
                    extension: "jpg|jpeg|png|gif",
                },
                product_price: {
                    required: true,
                    number: true,
                    min: 0,
                },
            },
            messages: {
                category_id: {
                    required: 'Bạn chưa chọn danh mục',
                },
                product_name: {
                    required: 'Bạn chưa nhập tên sản phẩm',
                    minlength: 'Tên sản phẩm phải có ít nhất 2 ký tự',
                },
                product_img: {
                    required: 'Bạn chưa chọn hình ảnh sản phẩm',
                    extension: 'Hình ảnh phải có định dạng jpg, jpeg, png, hoặc gif',
                },
                product_price: {
                    required: 'Bạn chưa nhập giá sản phẩm',
                    number: 'Vui lòng nhập số hợp lệ',
                    min: 'Giá sản phẩm không được âm',
                },
            },
            errorElement: 'div',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                error.insertAfter(element);
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
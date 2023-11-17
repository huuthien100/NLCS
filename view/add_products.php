<?php
require '../include/connect.php';
require '../include/user_session.php';

function isProductNameExistsInCategory($pdo, $product_name, $category_id)
{
    $check_name_query = "SELECT product_name FROM products WHERE product_name = :product_name AND id_category = :category_id";
    $check_name_stmt = $pdo->prepare($check_name_query);
    $check_name_stmt->execute([':product_name' => $product_name, ':category_id' => $category_id]);

    return $check_name_stmt->rowCount() > 0;
}

function getCategoryName($pdo, $category_id)
{
    $query = "SELECT name_category FROM category WHERE id_category = :category_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':category_id' => $category_id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['name_category'] : false;
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = trim($_POST["category_id"]);
    $product_name = trim($_POST["product_name"]);
    $stock_quantity = trim($_POST["stock_quantity"]);
    $product_price = trim($_POST["product_price"]);

    if (empty($category_id)) {
        $errors['category'] = "Bạn chưa chọn Grade.";
    }

    if (empty($product_name)) {
        $errors['name'] = "Bạn chưa nhập tên sản phẩm";
    } elseif (isProductNameExistsInCategory($pdo, $product_name, $category_id)) {
        $errors['name'] = "Tên sản phẩm đã tồn tại";
    }

    if (empty($stock_quantity) || !is_numeric($stock_quantity) || $stock_quantity < 0) {
        $errors['stock_quantity'] = "Số lượng tồn kho không hợp lệ.";
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

            if (!move_uploaded_file($tmp_name, $product_img)) {
                $errors['img'] = "Lỗi khi tải lên hình ảnh sản phẩm.";
            }
        } else {
            $errors['img'] = "Không tìm thấy tên danh mục.";
        }
    }

    if (empty($product_price) || !is_numeric($product_price) || $product_price <= 0) {
        $errors['price'] = "Giá tiền bạn chọn không hợp lệ.";
    }

    if (empty($errors)) {
        $insertProductSql = "INSERT INTO products (id_category, product_name, product_img, product_price, stock_quantity) VALUES (:id_category, :product_name, :product_img, :product_price, :stock_quantity)";

        $insertImgSql = "INSERT INTO product_img (id_product, img_url) VALUES (:id_product, :img_url)";

        try {
            $pdo->beginTransaction();

            $insertProductStmt = $pdo->prepare($insertProductSql);
            $insertProductStmt->execute([
                ':id_category' => $category_id,
                ':product_name' => $product_name,
                ':product_img' => $product_img,
                ':product_price' => $product_price,
                ':stock_quantity' => $stock_quantity,
            ]);

            $newProductId = $pdo->lastInsertId();

            $insertImgStmt = $pdo->prepare($insertImgSql);
            $insertImgStmt->execute([':id_product' => $newProductId, ':img_url' => $product_img]);

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
        <form name="form_add_product" id="form_add_product" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
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
            </div>
            <div class="mb-3">
                <label for="product_name" class="form-label"><i class="fa-solid fa-file"></i> Tên sản phẩm:</label>
                <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Nhập tên sản phẩm" required>
            </div>
            <div class="mb-3">
                <label for="product_img" class="form-label"><i class="fa-solid fa-image"></i> Hình ảnh sản phẩm:</label>
                <input type="file" class="form-control" id="product_img" name="product_img" accept="image/*" required>
            </div>

            <div class="mb-3">
                <label for="product_price" class="form-label"><i class="fa-solid fa-dollar-sign"></i> Giá sản phẩm:</label>
                <input type="text" class="form-control" id="product_price" name="product_price" placeholder="Nhập giá sản phẩm" required>
            </div>
            <div class="mb-3">
                <label for="stock_quantity" class="form-label"><i class="fa-solid fa-list-ol"></i> Số lượng tồn kho:</label>
                <input type="text" class="form-control" id="stock_quantity" name="stock_quantity" placeholder="Nhập số lượng tồn kho" required>
            </div>
            <div class="center-button">
                <button type="submit" name="submit" class="btn btn-success">Thêm sản phẩm</button>
            </div>
        </form>
    </div>
</div>
<!-- End Form -->
<script>
    $(document).ready(function() {
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
                stock_quantity: {
                    required: true,
                    digits: true,
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
                stock_quantity: {
                    required: 'Bạn chưa nhập số lượng tồn kho',
                    digits: 'Vui lòng chỉ nhập số nguyên dương',
                    min: 'Số lượng tồn kho không được âm',
                },
            },
            errorElement: 'div',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                error.insertAfter(element);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).addClass('is-valid').removeClass('is-invalid');
            },
        });
    });
</script>

</body>

</html>

<?php
require '../include/connect.php';
require '../include/user_session.php';

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    function getNextFileNumber($category_name, $product_name, $pdo)
    {
        $files = scandir("../asset/product/$category_name/$category_name-$product_name/");
        $file_numbers = array();

        foreach ($files as $file) {
            if (preg_match('/^' . preg_quote($product_name, '/') . '-(\d+)\..*$/', $file, $matches)) {
                $file_numbers[] = intval($matches[1]);
            }
        }
        $next_file_number = 1;
        while (in_array($next_file_number, $file_numbers)) {
            $next_file_number++;
        }

        return $next_file_number;
    }

    function getCategoriesId($pdo, $product_id)
    {
        $query = "SELECT id_category FROM products WHERE id_product = :product_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":product_id", $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['id_category'] : false;
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

    $img_error = '';
    $notificationDisplayed = false;

    $successMessage = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_FILES["product_img"]) && is_array($_FILES["product_img"]["name"])) {
            $uploaded_files = $_FILES["product_img"];

            $success = true;

            foreach ($uploaded_files["name"] as $key => $file_name) {
                if ($uploaded_files["error"][$key] === UPLOAD_ERR_OK) {
                    $tmp_name = $uploaded_files["tmp_name"][$key];

                    $category_id = getCategoriesId($pdo, $product_id);
                    $category_name = getCategoryName($pdo, $category_id);

                    if ($category_name) {
                        $query = "SELECT product_name FROM products WHERE id_product = :product_id";
                        $stmt = $pdo->prepare($query);
                        $stmt->bindParam(":product_id", $product_id, PDO::PARAM_INT);
                        $stmt->execute();
                        $product_data = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($product_data) {
                            $product_name = $product_data['product_name'];

                            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
                            $new_file_name = $product_name . '-' . getNextFileNumber($category_name, $product_name, $pdo) . '.' . $file_extension;

                            $upload_dir = "../asset/product/$category_name/$category_name-$product_name/";

                            if (!is_dir($upload_dir)) {
                                mkdir($upload_dir, 0777, true);
                            }

                            $product_img = $upload_dir . $new_file_name;

                            if (!move_uploaded_file($tmp_name, $product_img)) {
                                $success = false;
                                break;
                            } else {
                                $query = "INSERT INTO product_img (id_product, img_url) VALUES (:product_id, :img_url)";
                                $stmt = $pdo->prepare($query);
                                $stmt->bindParam(":product_id", $product_id, PDO::PARAM_INT);
                                $stmt->bindParam(":img_url", $product_img, PDO::PARAM_STR);

                                if (!$stmt->execute()) {
                                    $success = false;
                                    break;
                                }
                            }
                        } else {
                            $img_error = "<span style='color: red;'>Không tìm thấy tên sản phẩm.</span>";
                        }
                    } else {
                        $img_error = "<span style='color: red;'>Không tìm thấy tên danh mục.</span>";
                    }
                }
            }

            if ($success) {
                echo '<script>alert("Upload thành công!");</script>';
                echo '<script>setTimeout(function(){ window.location.href = "products_manage.php"; }, 100);</script>';
                exit;
            }
            
        }
    }
}
?>

<?php include '../include/header-ad.php'; ?>
<title>Thêm hình ảnh</title>

<div class="container">
    <div class="form-container">
        <form name="form_add_product" id="form_add_product"
            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?product_id=$product_id"; ?>" method="POST"
            enctype="multipart/form-data">
            <div class="title-image mb-3">
                <img src="../asset/icon/add_img.png" alt="Hình ảnh tiêu đề" style="width: 150%">
            </div>
            <div class="mb-3">
                <label for="product_img" class="form-label"><i class="fa-solid fa-image"></i> Hình ảnh sản phẩm:</label>
                <input type="file" class="form-control" id="product_img" name="product_img[]" accept="image/*" multiple
                    required>
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
</body>
</html>

<?php
require '../include/connect.php';
require '../include/user_session.php';

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    $product_data = [];
    $detail_data = [];
    $images = [];

    $product_query = "SELECT product_name, product_price FROM products WHERE id_product = :product_id";
    $product_stmt = $pdo->prepare($product_query);
    $product_stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);

    $detail_query = "SELECT scale, detail, equipment, decal, stand, origin, description FROM product_detail WHERE id_product = :product_id";
    $detail_stmt = $pdo->prepare($detail_query);
    $detail_stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);

    $image_query = "SELECT img_id, img_url FROM product_img WHERE id_product = :product_id";
    $image_stmt = $pdo->prepare($image_query);
    $image_stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);

    try {
        $product_stmt->execute();
        $product_data = $product_stmt->fetch(PDO::FETCH_ASSOC);

        $detail_stmt->execute();
        $detail_data = $detail_stmt->fetch(PDO::FETCH_ASSOC);

        $image_stmt->execute();
        $images = $image_stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['delete_images'])) {
            $selectedImages = isset($_POST['selected_images']) ? $_POST['selected_images'] : [];
            if (!empty($selectedImages)) {
                foreach ($selectedImages as $imageId) {
                    $imageQuery = "SELECT img_url FROM product_img WHERE img_id = :img_id";
                    $imageStmt = $pdo->prepare($imageQuery);
                    $imageStmt->bindParam(':img_id', $imageId, PDO::PARAM_INT);
                    $imageStmt->execute();
                    $imageData = $imageStmt->fetch(PDO::FETCH_ASSOC);
                    if ($imageData !== false && isset($imageData['img_url'])) {
                        $imagePath = $imageData['img_url'];

                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                        $deleteImageQuery = "DELETE FROM product_img WHERE img_id = :img_id";
                        $deleteImageStmt = $pdo->prepare($deleteImageQuery);
                        $deleteImageStmt->bindParam(':img_id', $imageId, PDO::PARAM_INT);
                        $deleteImageStmt->execute();
                    }
                }
            }
        }
    }
}
include '../include/header-ad.php'; ?>
<title>Chi tiết sản phẩm</title>

<style>
    .image-list {
        white-space: nowrap;
        overflow-x: auto;
    }

    .image-list img {
        max-width: 450px;
        margin: 5px;
    }

    .center-image {
        width: 450px;
    }
</style>

<div class="container mt-5">
    <div class="text-center">
        <img src="../asset/icon/product_detail.png" alt="Thông tin chi tiết sản phẩm" class="text-center center-image">
    </div>

    <div class="row">
        <div class="col-md-5">
            <h2>Thông tin sản phẩm</h2>
            <ul class="list-group">
                <li class="list-group-item">
                    <strong>Tên sản phẩm:</strong>
                    <?php echo !empty($product_data['product_name']) ? $product_data['product_name'] : 'Trống'; ?>
                </li>
                <li class="list-group-item">
                    <strong>Giá sản phẩm:</strong>
                    <?php echo !empty($product_data['product_price']) ? $product_data['product_price'] : 'Trống'; ?>
                </li>
                <li class="list-group-item">
                    <strong>Tỉ lệ:</strong>
                    <?php echo !empty($detail_data['scale']) ? $detail_data['scale'] : 'Trống'; ?>
                </li>
                <li class="list-group-item">
                    <strong>Chi tiết:</strong>
                    <?php echo !empty($detail_data['detail']) ? $detail_data['detail'] : 'Trống'; ?>
                </li>
                <li class="list-group-item">
                    <strong>Trang bị:</strong>
                    <?php echo !empty($detail_data['equipment']) ? $detail_data['equipment'] : 'Trống'; ?>
                </li>
                <li class="list-group-item">
                    <strong>Decal:</strong>
                    <?php echo !empty($detail_data['decal']) ? $detail_data['decal'] : 'Trống'; ?>
                </li>
                <li class="list-group-item">
                    <strong>Đế dựng:</strong>
                    <?php echo !empty($detail_data['stand']) ? $detail_data['stand'] : 'Trống'; ?>
                </li>
                <li class="list-group-item">
                    <strong>Xuất xứ:</strong>
                    <?php echo !empty($detail_data['origin']) ? $detail_data['origin'] : 'Trống'; ?>
                </li>
                <li class="list-group-item">
                    <strong>Mô tả:</strong>
                    <?php echo !empty($detail_data['description']) ? $detail_data['description'] : 'Trống'; ?>
                </li>
                <div class="my-3">
                    <a href="edit_detail.php?product_id=<?php echo $product_id; ?>" class="btn btn-primary">Chỉnh sửa
                        thông tin</a>
                </div>
            </ul>
        </div>
        <div class="col-md-7 mb-5">
            <h2>Danh sách ảnh sản phẩm</h2>
            <form method="post">
                <div class="image-list">
                    <div class="d-flex">
                        <?php if (!empty($images)): ?>
                            <?php foreach ($images as $image): ?>
                                <div class="mb-2 mr-2">
                                    <label class="d-flex flex-column align-items-center">
                                        <img src="<?php echo $image['img_url']; ?>" alt="Ảnh sản phẩm" class="img-thumbnail">
                                        <input type="checkbox" name="selected_images[]" value="<?php echo $image['img_id']; ?>">
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Không có ảnh sản phẩm nào.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="mt-3">
                    <?php
                    $idProduct = $_GET['product_id'] ?? null;
                    if ($idProduct) {
                        echo "<a href='add_image.php?product_id=$idProduct' class='btn btn-success add-image'>
                <i class='fas fa-camera'></i>
            </a>";
                    } else {
                        echo "ID not found in the URL";
                    }
                    ?>
                    <button type="submit" name="delete_images" class="btn btn-danger"><i
                            class='fas fa-trash-alt'></i></button>
                    <button type="button" class="btn btn-primary" id="select-all">Chọn tất cả</button>
                    <button type="button" class="btn btn-primary" id="deselect-all">Bỏ chọn</button>
                </div>
            </form>

            <script>
                document.getElementById("select-all").addEventListener("click", function () {
                    let checkboxes = document.getElementsByName("selected_images[]");
                    for (let checkbox of checkboxes) {
                        checkbox.checked = true;
                    }
                });

                document.getElementById("deselect-all").addEventListener("click", function () {
                    let checkboxes = document.getElementsByName("selected_images[]");
                    for (let checkbox of checkboxes) {
                        checkbox.checked = false;
                    }
                });
            </script>
        </div>

    </div>
</div>
</body>

</html>
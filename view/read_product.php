<?php
require '../include/connect.php';
require '../include/user_session.php';

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    $product_data = [];
    $detail_data = [];
    $images = [];

    // Truy vấn thông tin sản phẩm từ bảng products
    $product_query = "SELECT product_name, product_price FROM products WHERE id_product = :product_id";
    $product_stmt = $pdo->prepare($product_query);
    $product_stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);

    // Truy vấn thông tin chi tiết sản phẩm từ bảng product_detail
    $detail_query = "SELECT scale, detail, equipment, decal, stand, origin FROM product_detail WHERE id_product = :product_id";
    $detail_stmt = $pdo->prepare($detail_query);
    $detail_stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);

    // Truy vấn danh sách ảnh sản phẩm từ bảng product_img
    $image_query = "SELECT img_url FROM product_img WHERE id_product = :product_id";
    $image_stmt = $pdo->prepare($image_query);
    $image_stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);

    // Execute the queries and handle errors
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
}
?>

<?php include '../include/header-ad.php'; ?>
<title>Chi tiết sản phẩm</title>

<style>
    .image-list {
        white-space: nowrap;
        overflow-x: auto;
    }

    .image-list img {
        max-width: 328px;
        margin: 5px;
    }

    .center-image {
        width: 450px;

    }
</style>

<div class="container mt-5 ">
    <div class="text-center">
        <img src="../asset/icon/product_detail.png" alt="Thông tin chi tiết sản phẩm" class="text-center center-image">
    </div>

    <div class="row">
        <div class="col-md-6">
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
                    <strong>Scale:</strong>
                    <?php echo !empty($detail_data['scale']) ? $detail_data['scale'] : 'Trống'; ?>
                </li>
                <li class="list-group-item">
                    <strong>Detail:</strong>
                    <?php echo !empty($detail_data['detail']) ? $detail_data['detail'] : 'Trống'; ?>
                </li>
                <li class="list-group-item">
                    <strong>Equipment:</strong>
                    <?php echo !empty($detail_data['equipment']) ? $detail_data['equipment'] : 'Trống'; ?>
                </li>
                <li class="list-group-item">
                    <strong>Decal:</strong>
                    <?php echo !empty($detail_data['decal']) ? $detail_data['decal'] : 'Trống'; ?>
                </li>
                <li class="list-group-item">
                    <strong>Stand:</strong>
                    <?php echo !empty($detail_data['stand']) ? $detail_data['stand'] : 'Trống'; ?>
                </li>
                <li class="list-group-item">
                    <strong>Origin:</strong>
                    <?php echo !empty($detail_data['origin']) ? $detail_data['origin'] : 'Trống'; ?>
                </li>
                <div class="my-3">
                    <a href="edit_detail.php?product_id=<?php echo $product_id; ?>" class="btn btn-primary">Chỉnh sửa
                        thông tin</a>
                </div>
            </ul>
        </div>

        <div class="col-md-6">
            <h2>Danh sách ảnh sản phẩm</h2>
            <div class="image-list">
                <?php if (!empty($images)): ?>
                    <?php foreach ($images as $image): ?>
                        <img src="<?php echo $image['img_url']; ?>" alt="Ảnh sản phẩm" class="img-thumbnail">
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Không có ảnh sản phẩm nào.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
</body>

</html>
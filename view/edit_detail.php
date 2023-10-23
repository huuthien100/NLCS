<?php
require '../include/connect.php';
require '../include/user_session.php';

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    $query = "SELECT * FROM product_detail WHERE id_product = :product_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product_detail = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $scale = $_POST['scale'];
        $detail = $_POST['detail'];
        $equipment = $_POST['equipment'];
        $decal = $_POST['decal'];
        $stand = $_POST['stand'];
        $origin = $_POST['origin'];
        $description = $_POST['description'];

        if ($product_detail) {
            $updateQuery = "UPDATE product_detail SET scale = :scale, detail = :detail, equipment = :equipment, decal = :decal, stand = :stand, origin = :origin, description = :description WHERE id_product = :product_id";
            $updateStmt = $pdo->prepare($updateQuery);
        } else {
            $insertQuery = "INSERT INTO product_detail (id_product, scale, detail, equipment, decal, stand, origin, description) VALUES (:product_id, :scale, :detail, :equipment, :decal, :stand, :origin, :description)";
            $insertStmt = $pdo->prepare($insertQuery);
        }

        $pdoStatement = $product_detail ? $updateStmt : $insertStmt;
        $pdoStatement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $pdoStatement->bindParam(':scale', $scale, PDO::PARAM_STR);
        $pdoStatement->bindParam(':detail', $detail, PDO::PARAM_STR);
        $pdoStatement->bindParam(':equipment', $equipment, PDO::PARAM_STR);
        $pdoStatement->bindParam(':decal', $decal, PDO::PARAM_STR);
        $pdoStatement->bindParam(':stand', $stand, PDO::PARAM_STR);
        $pdoStatement->bindParam(':origin', $origin, PDO::PARAM_STR);
        $pdoStatement->bindParam(':description', $description, PDO::PARAM_STR);

        if ($pdoStatement->execute()) {
            header("Location: read_product.php?product_id=$product_id");
            exit;
        } else {
            echo "Operation failed";
        }
    }
}
?>

<?php include '../include/header-ad.php'; ?>
<title>Chỉnh sửa chi tiết sản phẩm</title>

<div class="container mt-5">
    <div class="text-center">    <h1>Chỉnh sửa chi tiết sản phẩm</h1>
</div>
    <div class="card mb-5">
        <div class="card-body">
            <form method="post">
                <div class="form-group">
                    <label for="scale">Tỉ lệ:</label>
                    <input type="text" class="form-control" name="scale" id="scale"
                        value="<?php echo isset($product_detail['scale']) ? $product_detail['scale'] : ''; ?>">
                </div>
                <div class form-group>
                    <label for="detail">Chi tiết:</label>
                    <input type="text" class="form-control" name="detail" id="detail"
                        value="<?php echo isset($product_detail['detail']) ? $product_detail['detail'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="equipment">Trang bị:</label>
                    <input type="text" class="form-control" name="equipment" id="equipment"
                        value="<?php echo isset($product_detail['equipment']) ? $product_detail['equipment'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="decal">Decal:</label>
                    <input type="text" class="form-control" name="decal" id="decal"
                        value="<?php echo isset($product_detail['decal']) ? $product_detail['decal'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="stand">Đế dựng:</label>
                    <input type="text" class="form-control" name="stand" id="stand"
                        value="<?php echo isset($product_detail['stand']) ? $product_detail['stand'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="origin">Xuất xứ:</label>
                    <input type="text" class="form-control" name="origin" id="origin"
                        value="<?php echo isset($product_detail['origin']) ? $product_detail['origin'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="description">Mô tả:</label>
                    <textarea class="form-control" name="description" id="description"
                        rows="3"><?php echo isset($product_detail['description']) ? $product_detail['description'] : ''; ?></textarea>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary mt-3">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>

</html>
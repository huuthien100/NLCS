<?php
require '../include/connect.php';
require '../include/user_session.php';

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Check if the product detail record already exists
    $query = "SELECT * FROM product_detail WHERE id_product = :product_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product_detail = $stmt->fetch(PDO::FETCH_ASSOC);

    // Handle the form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve and validate form data
        $scale = $_POST['scale'];
        $detail = $_POST['detail'];
        $equipment = $_POST['equipment'];
        $decal = $_POST['decal'];
        $stand = $_POST['stand'];
        $origin = $_POST['origin'];

        if ($product_detail) {
            // The record already exists, so update it
            $updateQuery = "UPDATE product_detail SET scale = :scale, detail = :detail, equipment = :equipment, decal = :decal, stand = :stand, origin = :origin WHERE id_product = :product_id";
            $updateStmt = $pdo->prepare($updateQuery);
        } else {
            // The record does not exist, so insert a new record
            $insertQuery = "INSERT INTO product_detail (id_product, scale, detail, equipment, decal, stand, origin) VALUES (:product_id, :scale, :detail, :equipment, :decal, :stand, :origin)";
            $insertStmt = $pdo->prepare($insertQuery);
        }

        // Bind parameters and execute the appropriate statement (insert or update)
        $pdoStatement = $product_detail ? $updateStmt : $insertStmt;
        $pdoStatement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $pdoStatement->bindParam(':scale', $scale, PDO::PARAM_STR);
        $pdoStatement->bindParam(':detail', $detail, PDO::PARAM_STR);
        $pdoStatement->bindParam(':equipment', $equipment, PDO::PARAM_STR);
        $pdoStatement->bindParam(':decal', $decal, PDO::PARAM_STR);
        $pdoStatement->bindParam(':stand', $stand, PDO::PARAM_STR);
        $pdoStatement->bindParam(':origin', $origin, PDO::PARAM_STR);

        if ($pdoStatement->execute()) {
            // Redirect back to the product details page after successful update or insert
            header("Location: read_product.php?product_id=$product_id");
            exit;
        } else {
            // Handle the error
            echo "Operation failed";
        }
    }
}
?>

<?php include '../include/header-ad.php'; ?>
<title>Chỉnh sửa chi tiết sản phẩm</title>

<div class="container mt-5">
    <h1>Chỉnh sửa chi tiết sản phẩm</h1>
    <form method="post">
        <div class="form-group">
            <label for="scale">Scale:</label>
            <input type="text" class="form-control" name="scale" id="scale"
                value="<?php echo isset($product_detail['scale']) ? $product_detail['scale'] : ''; ?>">
        </div>
        <div class="form-group">
            <label for="detail">Detail:</label>
            <input type="text" class="form-control" name="detail" id="detail"
                value="<?php echo isset($product_detail['detail']) ? $product_detail['detail'] : ''; ?>">
        </div>
        <div class="form-group">
            <label for="equipment">Equipment:</label>
            <input type="text" class="form-control" name="equipment" id="equipment"
                value="<?php echo isset($product_detail['equipment']) ? $product_detail['equipment'] : ''; ?>">
        </div>
        <div class="form-group">
            <label for="decal">Decal:</label>
            <input type="text" class="form-control" name="decal" id="decal"
                value="<?php echo isset($pSroduct_detail['decal']) ? $product_detail['decal'] : ''; ?>">
        </div>
        <div class="form-group">
            <label for="stand">Stand:</label>
            <input type="text" class="form-control" name="stand" id="stand"
                value="<?php echo isset($product_detail['stand']) ? $product_detail['stand'] : ''; ?>">
        </div>
        <div class="form-group">
            <label for="origin">Origin:</label>
            <input type="text" class="form-control" name="origin" id="origin"
                value="<?php echo isset($product_detail['origin']) ? $product_detail['origin'] : ''; ?>">
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary mt-3">Lưu thay đổi</button>
        </div>
    </form>
</div>
</body>

</html>
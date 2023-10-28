<?php
require '../include/connect.php';
require '../include/user_session.php';

if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../view/login.php");
        exit;
    }

    $id_product = $_POST['id_product'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    $query = "SELECT * FROM products WHERE id_product = :id_product";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_product', $id_product, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header("Location: ../view/error.php");
        exit;
    }

    $price = $product['product_price'];

    $user_id = $_SESSION['user_id'];

    $checkQuery = "SELECT id, quantity, total_price FROM cart WHERE user_id = :user_id AND id_product = :id_product";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $checkStmt->bindParam(':id_product', $id_product, PDO::PARAM_INT);
    $checkStmt->execute();
    $existingCartItem = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($existingCartItem) {
        $newQuantity = $existingCartItem['quantity'] + $quantity;
        $newTotalPrice = $newQuantity * $price;

        $updateQuery = "UPDATE cart SET quantity = :newQuantity, total_price = :newTotalPrice WHERE id = :itemId";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':newQuantity', $newQuantity, PDO::PARAM_INT);
        $updateStmt->bindParam(':newTotalPrice', $newTotalPrice, PDO::PARAM_INT);
        $updateStmt->bindParam(':itemId', $existingCartItem['id'], PDO::PARAM_INT);
        $updateStmt->execute();
    } else {
        $newTotalPrice = $quantity * $price;
        
        $insertQuery = "INSERT INTO cart (user_id, id_product, quantity, price, total_price, created_at, updated_at)
                        VALUES (:user_id, :id_product, :quantity, :price, :total_price, NOW(), NOW())";
        $stmt = $pdo->prepare($insertQuery);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':id_product', $id_product, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':price', $price, PDO::PARAM_INT);
        $stmt->bindParam(':total_price', $newTotalPrice, PDO::PARAM_INT);
        $stmt->execute();
    }

    $successMessage = "Đã thêm sản phẩm vào giỏ hàng.";
    $_SESSION['successMessage'] = $successMessage;

    header("Location: product_detail.php?id_product=" . $id_product);
    exit;
}

?>

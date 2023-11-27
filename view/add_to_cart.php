<?php
require '../include/connect.php';
require '../include/user_session.php';

if (isset($_POST['add_to_cart'])) {
    // Check user session
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../view/login.php");
        exit;
    }

    // Get product information
    $id_product = $_POST['id_product'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    $product = getProductById($pdo, $id_product);

    // Redirect to error page if product not found
    if (!$product) {
        header("Location: ../view/error.php");
        exit;
    }

    // User information
    $user_id = $_SESSION['user_id'];

    // Check if the product is already in the cart
    $existingCartItem = getCartItem($pdo, $user_id, $id_product);

    // Check if quantity exceeds stock quantity
    if ($quantity > $product['stock_quantity']) {
        $errorMessage = "Số lượng sản phẩm vượt quá số lượng trong kho.";
        $_SESSION['errorMessage'] = $errorMessage;
        header("Location: product_detail.php?id_product=" . $id_product);
        exit;
    }

    if ($existingCartItem) {
        // Update existing cart item
        updateCartItem($pdo, $existingCartItem['id'], $quantity, $product['product_price']);
    } else {
        // Insert new cart item
        insertCartItem($pdo, $user_id, $id_product, $quantity, $product['product_price']);
    }

    // Set success message
    $successMessage = "Đã thêm sản phẩm vào giỏ hàng.";
    $_SESSION['successMessage'] = $successMessage;

    // Redirect to product detail page
    header("Location: product_detail.php?id_product=" . $id_product);
    exit;
}

// Function to get product information by ID
function getProductById($pdo, $id_product)
{
    $query = "SELECT * FROM products WHERE id_product = :id_product";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_product', $id_product, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to get cart item information
function getCartItem($pdo, $user_id, $id_product)
{
    $query = "SELECT id, quantity, total_price FROM cart WHERE user_id = :user_id AND id_product = :id_product";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':id_product', $id_product, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to update cart item
function updateCartItem($pdo, $itemId, $quantity, $price)
{
    $newQuantity = $quantity + 1;
    $newTotalPrice = $newQuantity * $price;

    $updateQuery = "UPDATE cart SET quantity = :newQuantity, total_price = :newTotalPrice WHERE id = :itemId";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':newQuantity', $newQuantity, PDO::PARAM_INT);
    $updateStmt->bindParam(':newTotalPrice', $newTotalPrice, PDO::PARAM_INT);
    $updateStmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
    $updateStmt->execute();
}

// Function to insert new cart item
function insertCartItem($pdo, $user_id, $id_product, $quantity, $price)
{
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
?>

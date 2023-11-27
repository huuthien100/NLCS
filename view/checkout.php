<?php
require '../include/connect.php';
require '../include/user_session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
    $user_id = $_SESSION['user_id'];
    $shipping_address = $_POST['shipping_address'];
    $total_price = $_POST['total-value'];

    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $order_date = date('Y-m-d H:i:s');
    $status = 'Chờ xác nhận';

    // Insert order information
    $insertOrderQuery = "INSERT INTO orders (user_id, order_date, shipping_address, total_price, status) 
                         VALUES (:user_id, :order_date, :shipping_address, :total_price, :status)";
    $orderStmt = $pdo->prepare($insertOrderQuery);
    $orderStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $orderStmt->bindParam(':order_date', $order_date);
    $orderStmt->bindParam(':shipping_address', $shipping_address);
    $orderStmt->bindParam(':total_price', $total_price, PDO::PARAM_INT);
    $orderStmt->bindParam(':status', $status);

    if ($orderStmt->execute()) {
        $orderId = $pdo->lastInsertId();

        if (isset($_POST['selectedItems'])) {
            $selectedItems = explode(',', $_POST['selectedItems']);

            foreach ($selectedItems as $itemId) {
                // Fetch cart item details
                $query = "SELECT c.id_product, c.quantity, p.product_name, p.stock_quantity
                          FROM cart c
                          JOIN products p ON c.id_product = p.id_product
                          WHERE c.id = :itemId AND c.user_id = :user_id";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();
                $item = $stmt->fetch(PDO::FETCH_ASSOC);
            
                // Validate quantity
                if ($item['quantity'] > $item['stock_quantity']) {
                    echo "<script>alert('Số lượng sản phẩm {$item['product_name']} vượt quá số lượng tồn kho.');</script>";
                    echo "<script>
                            setTimeout(function() {
                                window.location.href = 'cart.php';
                            }, 1);
                          </script>";
                    exit;
                }

                // Insert order item
                $insertOrderItemQuery = "INSERT INTO order_detail (order_id, id_product, quantity) 
                                         VALUES (:order_id, :id_product, :quantity)";
                $orderItemStmt = $pdo->prepare($insertOrderItemQuery);
                $orderItemStmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
                $orderItemStmt->bindParam(':id_product', $item['id_product'], PDO::PARAM_INT);
                $orderItemStmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
                $orderItemStmt->execute();

                // Update stock quantity
                $newStockQuantity = $item['stock_quantity'] - $item['quantity'];
                $updateStockQuery = "UPDATE products SET stock_quantity = :new_stock_quantity 
                                     WHERE id_product = :id_product";
                $updateStockStmt = $pdo->prepare($updateStockQuery);
                $updateStockStmt->bindParam(':new_stock_quantity', $newStockQuantity, PDO::PARAM_INT);
                $updateStockStmt->bindParam(':id_product', $item['id_product'], PDO::PARAM_INT);
                $updateStockStmt->execute();

                // Delete item from the cart
                $deleteCartItemQuery = "DELETE FROM cart WHERE id = :itemId AND user_id = :user_id";
                $deleteCartItemStmt = $pdo->prepare($deleteCartItemQuery);
                $deleteCartItemStmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
                $deleteCartItemStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $deleteCartItemStmt->execute();
            }
        }
    } else {
        echo "Có lỗi xảy ra khi đặt hàng.";
    }
}

require '../include/header.php';
?>
<title>Thành công</title>
<div class="container">
    <div class="row">
        <div class="col">
            <div class="text-center mt-5">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 offset-md-3 text-center">
                            <div class="alert alert-success">
                                <h1>Đặt hàng thành công!</h1>
                                <p>Cảm ơn bạn đã mua hàng. Đơn hàng của bạn đã được đặt thành công.</p>
                                <p>Chúng tôi sẽ xác nhận đơn hàng của bạn trong thời gian sớm nhất.</p>
                                <a href="../index.php" class="btn btn-primary mt-3">Quay lại trang chủ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require '../include/footer.html';
?>
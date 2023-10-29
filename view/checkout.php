<?php
require '../include/connect.php';
require '../include/user_session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
    $user_id = $_SESSION['user_id'];
    $shipping_address = $_POST['shipping_address'];
    $total_price = $_POST['total-value'];

    $order_date = date('Y-m-d H:i:s');
    $status = 'Chờ xác nhận';

    $insertOrderQuery = "INSERT INTO orders (user_id, order_date, shipping_address, total_price, status) VALUES (:user_id, :order_date, :shipping_address, :total_price, :status)";
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
                $query = "SELECT id_product, quantity FROM cart WHERE id = :itemId AND user_id = :user_id";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();
                $item = $stmt->fetch(PDO::FETCH_ASSOC);

                $insertOrderItemQuery = "INSERT INTO order_detail (order_id, id_product, quantity) VALUES (:order_id, :id_product, :quantity)";
                $orderItemStmt = $pdo->prepare($insertOrderItemQuery);
                $orderItemStmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
                $orderItemStmt->bindParam(':id_product', $item['id_product'], PDO::PARAM_INT);
                $orderItemStmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
                $orderItemStmt->execute();

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
require '../include/header-ad.php';
?>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="text-center mt-5">
                <h1>Đặt hàng thành công!</h1>
                <p>Cảm ơn bạn đã mua hàng. Đơn hàng của bạn đã được đặt thành công.</p>
                <p>Chúng tôi sẽ gửi email xác nhận đơn hàng của bạn trong thời gian sớm nhất.</p>
                <a href="../index.php" class="btn btn-primary mt-3">Quay lại trang chủ</a>
            </div>
        </div>
    </div>
</div>

<?php
require '../include/footer.html';
?>

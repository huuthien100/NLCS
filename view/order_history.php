<?php
require '../include/connect.php';
require '../include/user_session.php';

function getUserId()
{
    if (isset($_SESSION['user_id'])) {
        return $_SESSION['user_id'];
    } else {
        header("Location: login.php");
        exit();
    }
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $userSessionId = getUserId();

    $sql = "SELECT o.order_id, u.username as user_id, o.order_date, o.shipping_address, o.total_price, o.status 
            FROM orders o
            INNER JOIN users u ON o.user_id = u.user_id
            WHERE u.user_id = :userSessionId";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userSessionId', $userSessionId, PDO::PARAM_INT);

    $stmt->execute();

    $orderInformation = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}

$totalOrders = count($orderInformation);

?>


<?php include '../include/header.php'; ?>
<style>
    .status-confirmed {
        color: #28a745 !important;
        font-weight: bold !important;
    }

    .status-pending {
        color: red !important;
        font-weight: bold !important;
    }
</style>
<title>Lịch sử đặt hàng</title>

<div class="container-fluid">
    <div class="row flex-nowrap">
        <div class="col py-3">
            <div class="d-flex justify-content-between">
                <h1>Lịch sử đặt hàng</h1>
                <p class='text-end me-2'><Strong>Tổng số đơn:</Strong> <?php echo $totalOrders; ?></p>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Người đặt hàng</th>
                        <th>Ngày đặt hàng</th>
                        <th>Địa chỉ giao hàng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($orderInformation) && is_array($orderInformation)) {
                        foreach ($orderInformation as $order) {
                            echo "<tr>";
                            echo "<td>" . $order['user_id'] . "</td>";
                            echo "<td>" . $order['order_date'] . "</td>";
                            echo "<td>" . $order['shipping_address'] . "</td>";
                            echo "<td>" . number_format($order['total_price']) . ' VNĐ' . "</td>";
                            echo "<td class='" . ($order['status'] == 'Đã xác nhận' ? 'status-confirmed' : 'status-pending') . "'>" . $order['status'] . "</td>";
                            echo "<td>
                            <a href='order_detail_u.php?order_id=" . $order['order_id'] . "' class='btn btn-info'>
                                <i class='fas fa-eye' style='color: #ffffff;'></i>
                            </a>
                        </td>";
                            echo "</tr>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>

</html>
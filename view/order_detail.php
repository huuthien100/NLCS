<?php
require '../include/connect.php';
require '../include/user_session.php';

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
} else {
    header('Location: orders_manage.php');
    exit;
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT o.order_id, u.username as user_id, o.order_date, o.shipping_address, o.total_price, o.status, od.id_product, od.quantity, p.product_name, p.product_img 
            FROM orders o
            INNER JOIN users u ON o.user_id = u.user_id
            INNER JOIN order_detail od ON o.order_id = od.order_id
            INNER JOIN products p ON od.id_product = p.id_product
            WHERE o.order_id = :order_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();

    $orderInformation = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}

include '../include/header-ad.php';
?>
<style>
    .status-green {
        color: #28a745;
        font-weight: bold;
    }

    .status-red {
        color: red;
        font-weight: bold;
    }
</style>
<title>Chi tiết đơn hàng</title>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <!-- Sidebar -->
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-body-tertiary">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                <ul class="nav flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                    <li class="nav-item">
                        <a href="admin.php" class="nav-link align-middle px-0 font-black">
                            <i class="fas fa-home"></i> Trang quản lý
                        </a>
                    </li>
                    <li>
                        <a href="users_manage.php" class="nav-link px-0 align-middle font-black">
                            <i class="fas fa-users"></i> Quản lý thành viên
                        </a>
                    </li>
                    <li>
                        <a href="category_manage.php" class="nav-link px-0 align-middle font-black">
                            <i class="fas fa-list"></i> Quản lý danh mục
                        </a>
                    </li>
                    <li>
                        <a href="products_manage.php" class="nav-link px-0 align-middle font-black">
                            <i class="fas fa-box-open"></i> Quản lý sản phẩm
                        </a>
                    </li>
                    <li>
                        <a href="orders_manage.php" class="nav-link px-0 align-middle font-black">
                            <i class="fas fa-shopping-cart"></i> Quản lý đơn hàng
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- End Sidebar -->
        <div class="col py-3">
            <div class="d-flex justify-content-between">
                <h1>Chi tiết đơn hàng #<?php echo $orderInformation['order_id']; ?></h1>
            </div>
            <table class="table">
                <tr>
                    <th>Người đặt hàng:</th>
                    <td><?php echo $orderInformation['user_id']; ?></td>
                </tr>
                <tr>
                    <th>Ngày đặt hàng:</th>
                    <td><?php echo $orderInformation['order_date']; ?></td>
                </tr>
                <tr>
                    <th>Địa chỉ giao hàng:</th>
                    <td><?php echo $orderInformation['shipping_address']; ?></td>
                </tr>
                <tr>
                    <th>Tổng tiền:</th>
                    <td><?php echo number_format($orderInformation['total_price']) . ' VNĐ'; ?></td>
                </tr>
                <tr>
                    <th>Trạng thái:</th>
                    <td>
                        <?php
                        $status = $orderInformation['status'];
                        if ($status === 'Đã xác nhận') {
                            echo "<span class='status-green'>$status</span>";
                        } else if ($status === 'Chờ xác nhận') {
                            echo "<span class='status-red'>$status</span>";
                        } else {
                            echo $status;
                        }
                        ?>
                    </td>
                </tr>

            </table>

            <h2>Danh sách sản phẩm trong đơn hàng:</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $sql = "SELECT od.id_product, od.quantity, p.product_name, p.product_img 
                    FROM order_detail od
                    INNER JOIN products p ON od.id_product = p.id_product
                    WHERE od.order_id = :order_id";

                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                        $stmt->execute();

                        $orderProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($orderProducts as $product) {
                            echo "<tr>";
                            echo "<td><img src='{$product['product_img']}' alt='{$product['product_name']}' width='100'></td>";
                            echo "<td>{$product['product_name']}</td>";
                            echo "<td>{$product['quantity']}</td>";
                            echo "</tr>";
                        }
                    } catch (PDOException $e) {
                        echo "Lỗi: " . $e->getMessage();
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>

</html>
<?php
require '../include/connect.php';
require '../include/user_session.php';

if (isset($_POST['confirm_order'])) {
    $order_id_to_confirm = $_POST['confirm_order'];

    $confirm_sql = "UPDATE orders SET status = 'Đã xác nhận' WHERE order_id = :order_id";
    $confirm_stmt = $pdo->prepare($confirm_sql);
    $confirm_stmt->bindParam(':order_id', $order_id_to_confirm, PDO::PARAM_INT);

    if ($confirm_stmt->execute()) {
        echo '<script>alert("Xác nhận đơn hàng thành công.");</script>';
    } else {
        echo '<script>alert("Xác nhận đơn hàng thất bại.");</script>';
    }
}

if (isset($_POST['confirm_delete_order'])) {
    $order_id_to_delete = $_POST['confirm_delete_order'];

    $delete_order_detail_sql = "DELETE FROM order_detail WHERE order_id = :order_id";
    $delete_order_detail_stmt = $pdo->prepare($delete_order_detail_sql);
    $delete_order_detail_stmt->bindParam(':order_id', $order_id_to_delete, PDO::PARAM_INT);

    if ($delete_order_detail_stmt->execute()) {
        $delete_order_sql = "DELETE FROM orders WHERE order_id = :order_id";
        $delete_order_stmt = $pdo->prepare($delete_order_sql);
        $delete_order_stmt->bindParam(':order_id', $order_id_to_delete, PDO::PARAM_INT);

        if ($delete_order_stmt->execute()) {
            echo '<script>alert("Xóa đơn hàng thành công.");</script>';
        } else {
            echo '<script>alert("Xóa đơn hàng thất bại.");</script>';
        }
    }
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT o.order_id, u.username as user_id, o.order_date, o.shipping_address, o.total_price, o.status 
            FROM orders o
            INNER JOIN users u ON o.user_id = u.user_id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $orderInformation = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}
?>

<?php include '../include/header-ad.php'; ?>
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
<title>Quản lý đơn hàng</title>

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
                <h1>Quản lý đơn hàng</h1>
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
                                    <button type='button' class='btn btn-success confirm-order' data-order_id='" . $order['order_id'] . "'>
                                        <i class='fas fa-check' style='color: #ffffff;'></i>
                                    </button>
                                    <a href='order_detail.php?order_id=" . $order['order_id'] . "' class='btn btn-info'>
                                        <i class='fas fa-eye' style='color: #ffffff;'></i>
                                    </a>
                                    <button type='button' class='btn btn-danger delete-order' data-order_id='" . $order['order_id'] . "'>
                                        <i class='fas fa-trash' style='color: #ffffff;'></i>
                                    </button>
                                    <form method='post' class='confirm-order-form' style='display: none;' data-order_id='" . $order['order_id'] . "'>
                                        <input type='hidden' name='confirm_order' value='" . $order['order_id'] . "'>
                                    </form>
                                    <form method='post' class='delete-order-form' style='display: none;' data-order_id='" . $order['order_id'] . "'>
                                        <input type='hidden' name='confirm_delete_order' value='" . $order['order_id'] . "'>
                                    </form>
                                </td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa đơn hàng -->
<div class="modal fade" id="confirmDeleteOrderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelOrder" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabelOrder">Xác nhận xóa đơn hàng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="border: none; box-shadow: none; background: none; font-size: 25px">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa đơn hàng này?
            </div>
            <div class="modal-footer">
                <form method="post" id="confirmDeleteOrderForm">
                    <input type="hidden" name="confirm_delete_order" id="confirmDeleteOrderInput">
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteOrderButton">Xóa</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $(".confirm-order").on("click", function() {
            var order_id = $(this).data("order_id");
            $("form[data-order_id='" + order_id + "'].confirm-order-form").submit();
        });
        $(".delete-order").on("click", function() {
            var order_id = $(this).data("order_id");
            $("#confirmDeleteOrderInput").val(order_id); // Set the value of the input in the modal
            $("#confirmDeleteOrderModal").modal("show");
        });

        $("#confirmDeleteOrderButton").on("click", function() {
            $("#confirmDeleteOrderForm").submit(); // Submit the delete order form
        });

        $(document).on("keypress", function(e) {
            if (e.key === "Enter") {
                $("#confirmDeleteOrderButton").click();
            }
        });

        $("#confirmDeleteOrderModal .btn-secondary").on("click", function() {
            $("#confirmDeleteOrderModal").modal("hide");
        });

        $("#confirmDeleteOrderModal .close").on("click", function() {
            $("#confirmDeleteOrderModal").modal("hide");
        });
    });
</script>
</body>

</html>
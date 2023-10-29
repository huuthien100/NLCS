<?php
require '../include/connect.php';
require '../include/user_session.php';
include('../include/header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['checkout'])) {
        $selectedItems = $_POST['selectedItems'];
        if (!empty($selectedItems)) {
            foreach ($selectedItems as $selectedItemId) {
                $query = "DELETE FROM cart WHERE id = :itemId";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':itemId', $selectedItemId, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
        header('Location: checkout.php');
    }
}
?>

<title>Cart</title>

<div class="container">
    <h1>Giỏ hàng</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col" style="text-align: center;">
                    <input type="checkbox" id="select-all">
                </th>
                <th scope="col" class="align-middle">Sản phẩm</th>
                <th scope="col" class="align-middle">Giá</th>
                <th scope="col" class="align-middle">Số lượng</th>
                <th scope="col" class="align-middle">Thành tiền</th>
                <th scope="col" class="align-middle">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $user_id = $_SESSION['user_id'];

            $query = "SELECT c.id, c.id_product,c.total_price, p.product_name, p.product_price, c.quantity, p.product_img
                              FROM cart c
                              INNER JOIN products p ON c.id_product = p.id_product
                              WHERE c.user_id = :user_id";

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($cartItems as $item) {
                echo '<tr>';
                echo '<td style="text-align: center;" class="align-middle">';
                echo '<input type="checkbox" class="product-checkbox" data-id="' . $item['id'] . '">';
                echo '</td>';
                echo '<td class="align-middle">';
                echo '<div class="d-flex align-items-center">';
                echo '<img src="' . $item['product_img'] . '" alt="Product Image" width="100" height="100">';
                echo '<span class="ms-2">' . $item['product_name'] . '</span>';
                echo '</div>';
                echo '</td>';
                echo '<td class="align-middle">' . number_format($item['product_price']) . ' VNĐ</td>';
                echo '<td class="align-middle">';
                echo '<button class="btn btn-light btn-lg decrease-qty" id="decreaseQty" data-id="' . $item['id'] . '"><i class="fas fa-minus"></i></button>';
                echo '<input class="btn btn-light btn-lg quantity-input m-1" type="number" value="' . $item['quantity'] . '" style="width: 100px;" id="quantity-' . $item['id'] . '" readonly />';
                echo '<button class="btn btn-light btn-lg increase-qty" id="increaseQty" data-id="' . $item['id'] . '"><i class="fas fa-plus"></i></button>';
                echo '</td>';
                echo '<td class="align-middle total-amount">' . number_format($item['total_price']) . ' VNĐ</td>';
                echo '<td class="align-middle">';
                echo '<button class="btn btn-danger remove-product" data-id="' . $item['id'] . '"><i class="fas fa-trash-alt"></i></button>';
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
        <tr>
            <form method="POST" action="checkout.php" class="">
                <td colspan="2">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="shipping_address" class="align-middle mt-1"><b>Địa chỉ giao hàng:</b></label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="shipping_address" id="shipping_address" required>
                        </div>
                    </div>
                </td>
                <td class="align-middle" colspan="2">
                    <div class="row">
                        <label class="text-end"><b>Tổng tiền:</b></label>
                    </div>
                </td>
                <td id="total" class="align-middle">
                    0 VNĐ
                </td>
                <input type="hidden" id="total-value" name="total-value" value="0">
                <td class="align-middle">
                    <button type="submit" name="checkout" class="btn btn-success checkout" id="checkout-button">Xác nhận thanh toán</button>
                </td>
            </form>
        </tr>

    </table>
</div>

<?php include('../include/footer.html') ?>
<script>
    $(document).ready(function() {
        $('.increase-qty').click(function() {
            const quantityInput = $(this).closest('tr').find('.quantity-input');
            let quantity = parseInt(quantityInput.val());
            quantity += 1;
            quantityInput.val(quantity);
            const itemId = $(this).data('id');
            updateQuantityInDatabase(itemId, quantity);
        });

        $('.decrease-qty').click(function() {
            const quantityInput = $(this).closest('tr').find('.quantity-input');
            let quantity = parseInt(quantityInput.val());
            if (quantity > 1) {
                quantity -= 1;
                quantityInput.val(quantity);
                const itemId = $(this).data('id');
                updateQuantityInDatabase(itemId, quantity);
            }
        });

        function updateQuantityInDatabase(itemId, quantity) {
            $.ajax({
                type: 'POST',
                url: 'cart.php',
                data: {
                    itemId: itemId,
                    quantity: quantity
                },
                success: function(data) {
                    location.reload();
                },

                error: function() {}
            });
        }
        $('#select-all').change(function() {
            var isChecked = $(this).is(':checked');
            $('.product-checkbox').prop('checked', isChecked);
        });

        if (<?php echo count($cartItems); ?> === 1) {
            $('.product-checkbox').change(function() {
                var isChecked = $(this).is(':checked');
                $('#select-all').prop('checked', isChecked);
            });
        }

        function calculateTotalPrice() {
            var total = 0;
            $('.product-checkbox:checked').each(function() {
                var itemId = $(this).data('id');
                var total_price_str = $(this).closest('tr').find('.total-amount').text().replace(' VNĐ', '').replace(/,/g, ''); // Remove existing commas
                var total_price = parseFloat(total_price_str);
                total += total_price;
            });

            $('#total-value').val(total);
            $('#total').text(total.toLocaleString('vi-VN') + ' VNĐ');
        }
        $('.product-checkbox, #select-all').change(function() {
            calculateTotalPrice();
        });

        $('.increase-qty, .decrease-qty').click(function() {
            calculateTotalPrice();
        });

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    });
</script>

</body>

</html>
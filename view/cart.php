<?php
require '../include/connect.php';
require '../include/user_session.php';
include('../include/header.php');
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
            <!-- Sample product 1 -->
            <tr>
                <td style="text-align: center;" class="align-middle">
                    <input type="checkbox" class="product-checkbox" data-id="1">
                </td>
                <td class="align-middle">
                    <div class="d-flex align-items-center">
                        <img src="../asset/product/hg/hg-aerial gundam/aerial gundam-1.jpg" alt="Product Image" width="100" height="100">
                        <span class="ms-2">Sản phẩm mẫu 1</span>
                    </div>
                </td>
                <td id="price_1" class="align-middle"> 100,000 VNĐ</td>
                <td class="align-middle">
                    <button class="btn btn-light btn-lg decrease-qty" data-id="1">
                        <i class="fas fa-minus"></i>
                    </button>
                    <input class="btn btn-light btn-lg quantity-input" type="number" value="1" style="width: 100px;" id="quantity-1" readonly />
                    <button class="btn btn-light btn-lg increase-qty" data-id="1">
                        <i class="fas fa-plus"></i>
                    </button>
                </td>
                <td id="subtotal_1" class="align-middle">100,000 VNĐ</td>
                <td class="align-middle">
                    <button class="btn btn-danger remove-product" data-id="1">
                        <i class="fas fa-trash-alt"></i> <!-- Delete icon -->
                    </button>
                </td>
            </tr>
            <!-- End of sample product 1 -->

            <!-- Sample product 2 -->
            <tr>
                <td style="text-align: center;" class="align-middle">
                    <input type="checkbox" class="product-checkbox" data-id="2">
                </td>
                <td class="align-middle">
                    <div class="d-flex align-items-center">
                        <img src="product_image_url_2.jpg" alt="Product Image 2" width="100" height="100">
                        <span class="ms-2">Sản phẩm mẫu 2</span>
                    </div>
                </td>
                <td id="price_2" class="align-middle">120,000 VNĐ</td>
                <td class="align-middle">
                    <button class="btn btn-light btn-lg decrease-qty" data-id="2">
                        <i class="fas fa-minus"></i>
                    </button>
                    <input class="btn btn-light btn-lg quantity-input" type="number" value="1" style="width: 100px;" id="quantity-2" readonly />
                    <button class="btn btn-light btn-lg increase-qty" data-id="2">
                        <i class="fas fa-plus"></i>
                    </button>
                </td>
                <td id="subtotal_2" class="align-middle">120,000 VNĐ</td>
                <td class="align-middle">
                    <button class="btn btn-danger remove-product" data-id="2">
                        <i class="fas fa-trash-alt"></i> <!-- Delete icon -->
                    </button>
                </td>
            </tr>
            <!-- End of sample product 2 -->

            <!-- Calculate and display the total -->
            <tr>
                <td colspan="4" class="align-middle">Tổng tiền:</td>
                <td id="total" class="align-middle">0 VNĐ</td>
                <td class="align-middle">
                    <button class="btn btn-success checkout">Thanh toán</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?php include('../include/footer.html') ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        const selectAllCheckbox = $('#select-all');
        const productCheckboxes = $('.product-checkbox');
        const quantityInputs = $('.quantity-input');
        const decreaseButtons = $('.decrease-qty');
        const increaseButtons = $('.increase-qty');
        const removeButtons = $('.remove-product');

        selectAllCheckbox.change(function () {
            productCheckboxes.prop('checked', selectAllCheckbox.prop('checked'));
            calculateTotal();
        });

        productCheckboxes.change(function () {
            calculateTotal();
        });

        quantityInputs.keydown(function (event) {
            if (event.key === 'Enter') {
                updateQuantity($(this));
            }
        });

        decreaseButtons.click(function () {
            const productId = $(this).data('id');
            const input = $(`#quantity-${productId}`);
            updateQuantityInput(input, -1);
        });

        increaseButtons.click(function () {
            const productId = $(this).data('id');
            const input = $(`#quantity-${productId}`);
            updateQuantityInput(input, 1);
        });

        removeButtons.click(function () {
            removeProduct($(this).data('id'));
        });

        function updateQuantityInput(input, change) {
            const productId = input.attr('id').split('-')[1];
            const subtotalElement = $(`#subtotal_${productId}`);
            const priceElement = $(`#price_${productId}`);

            const quantity = parseInt(input.val());
            if (quantity + change >= 1) {
                input.val(quantity + change);

                const price = parseInt(priceElement.text().replace(/[^0-9]/g, ''));
                const subtotal = price * (quantity + change);
                subtotalElement.text(subtotal.toLocaleString('vi-VN') + ' VNĐ');
            }

            calculateTotal();
        }

        function removeProduct(productId) {
            const productRow = $(`tr[data-id="${productId}"]`);
            productRow.remove();
            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            productCheckboxes.each(function () {
                if ($(this).prop('checked')) {
                    const productId = $(this).data('id');
                    const input = $(`#quantity-${productId}`);
                    const subtotalElement = $(`#subtotal_${productId}`);
                    const priceElement = $(`#price_${productId}`);

                    const quantity = parseInt(input.val());
                    const price = parseInt(priceElement.text().replace(/[^0-9]/g, ''));
                    const subtotal = price * quantity;
                    subtotalElement.text(subtotal.toLocaleString('vi-VN') + ' VNĐ');
                    total += subtotal;
                }
            });

            $('#total').text(total.toLocaleString('vi-VN') + ' VNĐ');
        }
    });

    $('.checkout').click(function () {
        alert('Redirect to payment page or perform checkout here.');
    });
</script>
</body>
</html>

<?php
require '../include/connect.php';
require '../include/user_session.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['delete_product'])) {
    try {
        $product_id = $_POST['delete_product'];

        $deleteOrderDetailSql = "DELETE FROM order_detail WHERE id_product = :product_id";
        $deleteOrderDetailStmt = $pdo->prepare($deleteOrderDetailSql);
        $deleteOrderDetailStmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $deleteOrderDetailStmt->execute();

        $deleteImgSql = "DELETE FROM product_img WHERE id_product = :product_id";
        $deleteImgStmt = $pdo->prepare($deleteImgSql);
        $deleteImgStmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $deleteImgStmt->execute();

        $deleteSql = "DELETE FROM products WHERE id_product = :product_id";
        $deleteStmt = $pdo->prepare($deleteSql);
        $deleteStmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $deleteStmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT p.id_product, p.product_name, p.product_img, p.product_price, c.name_category FROM products p
            INNER JOIN category c ON p.id_category = c.id_category";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $productInformation = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
// Handle search
$searchResults = array();

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $search = htmlspecialchars($search);

    $searchSql = "SELECT p.id_product, p.product_name, p.product_img, p.product_price, c.name_category 
                  FROM products p
                  INNER JOIN category c ON p.id_category = c.id_category
                  WHERE p.product_name LIKE :search";

    $searchStmt = $pdo->prepare($searchSql);
    $searchStmt->bindValue(':search', "%" . $search . "%", PDO::PARAM_STR);
    $searchStmt->execute();
    $searchResults = $searchStmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch all products (with or without search)
try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT p.id_product, p.product_name, p.product_img, p.product_price, c.name_category FROM products p
            INNER JOIN category c ON p.id_category = c.id_category";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $productInformation = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<?php include '../include/header-ad.php'; ?>
<title>Quản lý sản phẩm</title>

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
                <h1>Quản lý sản phẩm</h1>
            </div>
            <!-- Search Form -->
            <div class="d-flex justify-content-end mb-3">
                <form class="nav-item d-flex pe-3" role="search" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input class="form-control me-2" type="search" name="search" placeholder="Tìm kiếm" aria-label="Search">
                    <button class="btn btn-dark rounded-2" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
                <a href="add_products.php" class="btn btn-success pt-3 d-flex justify-content-center align-middle">
                    <i class="fa fa-plus" style="color: #ffffff"></i>
                </a>
            </div>
            <!-- End Search Form -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tên danh mục</th>
                        <th>Tên sản phẩm</th>
                        <th>Hình ảnh sản phẩm</th>
                        <th>Giá sản phẩm</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $displayedProducts = isset($searchResults) && !empty($searchResults) ? $searchResults : $productInformation;

                    if (isset($displayedProducts) && is_array($displayedProducts)) {
                        foreach ($displayedProducts as $product) {
                            echo "<tr>";
                            echo "<td>" . $product['name_category'] . "</td>";
                            echo "<td>" . $product['product_name'] . "</td>";
                            echo "<td><img src='" . $product['product_img'] . "' alt='" . $product['product_name'] . "' width='200' height='200'></td>";
                            echo "<td>" . number_format($product['product_price'], 0, '', '.') . " VNĐ</td>";
                            echo "<td>
                                    <a href='read_product.php?product_id=" . $product['id_product'] . "' class='btn btn-info'>
                                        <i class='fas fa-eye' style='color: #ffffff;'></i>
                                    </a>
                                    <a href='add_image.php?product_id=" . $product['id_product'] . "' class='btn btn-success add-image'>
                                        <i class='fas fa-camera'></i>
                                    </a>
                                    <a href='edit_products.php?product_id=" . $product['id_product'] . "' class='btn btn-primary'>
                                        <i class='fas fa-pencil' style='color: #ffffff;'></i>
                                    </a>
                                    
                                    <button type='button' class='btn btn-danger delete-product' data-product-id='" . $product['id_product'] . "'>
                                        <i class='fas fa-trash' style='color: #ffffff;'></i>
                                    </button>
                                    
                                    <form method='post' class='delete-product-form' data-product-id='" . $product['id_product'] . "'>
                                        <input type='hidden' name='delete_product' value='" . $product['id_product'] . "'>
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

<div class="modal fade" id="confirmDeleteProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelProduct" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabelProduct">Xác nhận xóa sản phẩm</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="border: none; box-shadow: none; background: none; font-size:25px">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa sản phẩm này?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteProductButton">Xóa</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".delete-product").on("click", function() {
            var productId = $(this).data("product-id");

            $("#confirmDeleteProductButton").data("product-id", productId);

            $("#confirmDeleteProductModal").modal("show");
        });

        $(document).on("keypress", function(e) {
            if (e.key === "Enter") {
                $("#confirmDeleteProductButton").click();
            }
        });

        $("#confirmDeleteProductButton").on("click", function() {
            var productId = $(this).data("product-id");
            $(".delete-product-form[data-product-id='" + productId + "']").submit();
            $("#confirmDeleteProductModal").modal("hide");
        });

        $("#confirmDeleteProductModal .btn-secondary").on("click", function() {
            $("#confirmDeleteProductModal").modal("hide");
        });

        $("#confirmDeleteProductModal .close").on("click", function() {
            $("#confirmDeleteProductModal").modal("hide");
        });
    });
</script>
</body>

</html>
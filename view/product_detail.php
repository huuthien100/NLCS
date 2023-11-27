<?php
require '../include/connect.php';
require '../include/user_session.php';

function getCategories($pdo)
{
    $query = "SELECT * FROM category";
    $stmt = $pdo->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProductInformation($pdo, $id_product)
{
    $sql = "SELECT p.id_product, p.product_name, p.product_price, p.stock_quantity, c.name_category, p.product_img
            FROM products p
            INNER JOIN category c ON p.id_category = c.id_category
            WHERE p.id_product = :id_product";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_product', $id_product, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUserInformation($pdo, $userEmail)
{
    $query = "SELECT user_id, access FROM users WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $userEmail, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getProductDetail($pdo, $id_product)
{
    $sql = "SELECT scale, detail, equipment, decal, stand, origin, description
            FROM product_detail
            WHERE id_product = :id_product";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_product', $id_product, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_GET['id_product'])) {
    $id_product = $_GET['id_product'];

    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get product information
        $productInformation = getProductInformation($pdo, $id_product);

        // Get user information
        $userEmail = $_SESSION['email'];
        $user = getUserInformation($pdo, $userEmail);

        $access = $user['access'];
        $user_id = $user['user_id'];

        // Get product detail
        $productDetail = getProductDetail($pdo, $id_product);

        if (isset($_SESSION['successMessage'])) {
            echo '<script>alert("' . $_SESSION['successMessage'] . '");</script>';
            unset($_SESSION['successMessage']);
            echo '<script>window.location.href = "product_detail.php?id_product=' . $id_product . '";</script>';
        }
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        header("Location: ../view/error.php");
        exit;
    }
}
$categories = getCategories($pdo);
include '../include/header-pd.php';
?>

<title>
    <?php echo $productInformation['name_category'] . ' - ' . $productInformation['product_name']; ?>
</title>

<div class="container-lg mt-3">
    <!-- Main Content -->
    <div class="row">
        <!-- Left Column - Product Images -->
        <div class="col-lg-6 col-md-6">
            <!-- Product Image Carousel -->
            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    $imagePath = '../asset/product/' . strtolower($productInformation['name_category']) . '/' . strtolower($productInformation['name_category']) . '-' . strtolower($productInformation['product_name']);
                    $images = glob($imagePath . '/*.{jpg,png,gif}', GLOB_BRACE);

                    foreach ($images as $index => $image) {
                        $activeClass = ($index === 0) ? 'active' : '';
                        echo '<div class="carousel-item ' . $activeClass . '">
                            <img src="' . $image . '" class="d-block w-100" alt="Ảnh sản phẩm ' . ($index + 1) . '">
                        </div>';
                    }
                    ?>
                </div>
                <!-- Carousel Navigation Buttons -->
                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

        <!-- Right Column - Product Information and Actions -->
        <div class="col-lg-6 col-md-6">
            <!-- Product Name and Category Badge -->
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h1 id="product_name">
                        <?php echo $productInformation['product_name']; ?>
                    </h1>
                </div>
                <div class="col-lg-12 col-md-12">
                    <span class="badge bg-primary" id="category">
                        <?php echo $productInformation['name_category']; ?>
                    </span>
                </div>
            </div>

            <!-- Product Rating -->
            <div class="row">
                <div class="rating">
                    <span class="star" data-rating="1">&#9733;</span>
                    <span class="star" data-rating="2">&#9733;</span>
                    <span class="star" data-rating="3">&#9733;</span>
                    <span class="star" data-rating="4">&#9733;</span>
                    <span class="star" data-rating="5">&#9733;</span>
                </div>
            </div>

            <!-- Product Price -->
            <div class="row">
                <div class="col-lg-12 col-md-12 bottom-rule">
                    <h2 class="product-price" style="color: red;">
                        <?php echo number_format($productInformation['product_price']); ?> VNĐ
                    </h2>
                </div>
            </div>

            <!-- Product Details -->
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <ul style="font-size: 18px;">
                        <li><span style="font-weight: bold;">Tỉ lệ: </span>
                            <?php echo !empty($productDetail['scale']) ? $productDetail['scale'] : 'Trống'; ?>
                        </li>
                        <li><span style="font-weight: bold;">Xuất xứ: </span>
                            <?php echo !empty($productDetail['origin']) ? $productDetail['origin'] : 'Trống'; ?>
                        </li>
                        <li><span style="font-weight: bold;">Hàng có sẵn: </span>
                            <?php echo $productInformation['stock_quantity']; ?>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Quantity Selector and Add to Cart Button -->
            <div class="row add-to-cart">
                <div class="col-5 product-qty">
                    <form id="addToCartForm" action="add_to_cart.php" method="post" onsubmit="return validateForm()">
                        <button class="btn btn-light btn-lg" type="button" id="decreaseQty" aria-label="Decrease Quantity">
                            <i class="fas fa-minus"></i>
                        </button>

                        <input class="btn btn-light btn-lg" type="number" name="quantity" value="1" style="width: 100px;" id="quantity" readonly placeholder="1">

                        <button class="btn btn-light btn-lg" type="button" id="increaseQty" aria-label="Increase Quantity">
                            <i class="fas fa-plus"></i>
                        </button>
                </div>
                <div class="col-7">
                    <?php
                    if ($productInformation['stock_quantity'] > 0) {
                        echo '<input type="hidden" name="id_product" value="' . $id_product . '">';
                        echo '<button type="submit" class="btn btn-success p-2" name="add_to_cart">THÊM VÀO GIỎ HÀNG</button>';
                    } else {
                        echo '<button type="button" class="btn btn-danger p-2" disabled>SẢN PHẨM ĐÃ HẾT HÀNG</button>';
                    }
                    ?>
                    </form> 
                </div>
            </div>

            <!-- Horizontal Rule -->
            <hr style="color: white;">

            <!-- Product Tabs - Description, Features, Reviews -->
            <ul class="nav nav-tabs" id="myTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="description-tab" data-bs-toggle="tab" href="#description" role="tab" aria-controls="description" aria-selected="true">Mô tả</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="features-tab" data-bs-toggle="tab" href="#features" role="tab" aria-controls="features" aria-selected="false">Đặc điểm</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="reviews-tab" data-bs-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">Đánh giá</a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Description Tab -->
                <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                    <?php echo !empty($productDetail['description']) ? $productDetail['description'] : 'Trống'; ?>
                    <div class="image-container">
                        <?php
                        $imagePath = '../asset/product/' . strtolower($productInformation['name_category']) . '/' . strtolower($productInformation['name_category']) . '-' . strtolower($productInformation['product_name']);
                        $images = glob($imagePath . '/*.{jpg,png,gif}', GLOB_BRACE);

                        foreach ($images as $index => $image) {
                            echo '<img src="' . $image . '" alt="Ảnh sản phẩm ' . ($index + 1) . '">';
                        }
                        ?>
                    </div>
                </div>

                <!-- Features Tab -->
                <div class="tab-pane fade" id="features" role="tabpanel" aria-labelledby="features-tab">
                    <ul>
                        <li><strong>Tỉ lệ:</strong>
                            <?php echo !empty($productDetail['scale']) ? $productDetail['scale'] : 'Trống'; ?>
                        </li>
                        <li><strong>Chi tiết:</strong>
                            <?php echo !empty($productDetail['detail']) ? $productDetail['detail'] : 'Trống'; ?>
                        </li>
                        <li><strong>Trang bị:</strong>
                            <?php echo !empty($productDetail['equipment']) ? $productDetail['equipment'] : 'Trống'; ?>
                        </li>
                        <li><strong>Decal:</strong>
                            <?php echo !empty($productDetail['decal']) ? $productDetail['decal'] : 'Trống'; ?>
                        </li>
                        <li><strong>Đế dựng:</strong>
                            <?php echo !empty($productDetail['stand']) ? $productDetail['stand'] : 'Trống'; ?>
                        </li>
                        <li><strong>Xuất xứ:</strong>
                            <?php echo !empty($productDetail['origin']) ? $productDetail['origin'] : 'Trống'; ?>
                        </li>
                    </ul>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                    <div class="review">
                        <div class="review-avatar">
                            <img src="https://image.ibb.co/jw55Ex/def_face.jpg" alt="Avatar" class="review-avatar-img">
                            <p class="review-time">15 Phút Trước</p>
                        </div>
                        <div class="review-content">
                            <div class="review-header">
                                <a href="https://maniruzzaman-akash.blogspot.com/p/contact.html" class="review-author">Person 1</a>
                                <div class="review-rating">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                            </div>
                            <p class="review-text">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. In enim blanditiis quod
                                facere eos voluptas eaque voluptates, harum amet deserunt veniam maxime velit, dicta
                                molestias mollitia officia aliquam maiores? Corrupti.
                            </p>
                            <div class="review-actions">
                                <a href="#" class="btn btn-outline-primary">Phản hồi <i class="fas fa-reply"></i></a>
                                <a href="#" class="btn btn-danger">Thích <i class="fas fa-heart"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="review">
                        <div class="review-avatar">
                            <img src="https://image.ibb.co/jw55Ex/def_face.jpg" alt="Avatar" class="review-avatar-img">
                            <p class="review-time">30 Phút Trước</p>
                        </div>
                        <div class="review-content">
                            <div class="review-header">
                                <a href="https://maniruzzaman-akash.blogspot.com/p/contact.html" class="review-author">Person 2</a>
                                <div class="review-rating">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                            </div>
                            <p class="review-text">
                                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Distinctio illo
                                praesentium
                                placeat ipsam vitae consequatur officiis accusantium ut molestiae iure. Ad eligendi
                                suscipit molestias maiores. Illo mollitia nam sapiente nostrum.
                            </p>
                            <div class="review-actions">
                                <a href="#" class="btn btn-outline-primary">Phản hồi <i class="fas fa-reply"></i></a>
                                <a href="#" class="btn btn-danger">Thích <i class="fas fa-heart"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Tab Panel -->
        </div>
        <!-- End Main Content -->
    </div>
</div>
<?php include('../include/footer.html'); ?>
<script>
    $(document).ready(function() {
        // Rating
        $('.star').on('click', function() {
            const rating = $(this).data('rating');
            $('#selected-rating').text(rating);
            $('.star').removeClass('active');

            for (let i = 1; i <= rating; i++) {
                $('.star[data-rating="' + i + '"]').addClass('active');
            }
        });

        // Quantity + -
        const quantityInput = $("input#quantity");
        const decreaseButton = $("#decreaseQty");
        const increaseButton = $("#increaseQty");

        decreaseButton.click(function(event) {
            event.preventDefault();
            let currentValue = parseInt(quantityInput.val());
            if (!isNaN(currentValue) && currentValue > 1) {
                quantityInput.val(currentValue - 1);
            }
        });

        increaseButton.click(function(event) {
            event.preventDefault();
            let currentValue = parseInt(quantityInput.val());
            if (!isNaN(currentValue)) {
                quantityInput.val(currentValue + 1);
            }
        });
    });
</script>
</body>

</html>
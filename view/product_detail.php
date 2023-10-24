<?php
require '../include/connect.php';
require '../include/user_session.php';

if (isset($_GET['id_product'])) {
    $id_product = $_GET['id_product'];

    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT p.product_name, p.product_price, c.name_category, p.product_img
                FROM products p
                INNER JOIN category c ON p.id_category = c.id_category
                WHERE p.id_product = :id_product";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_product', $id_product, PDO::PARAM_INT);
        $stmt->execute();
        $productInformation = $stmt->fetch(PDO::FETCH_ASSOC);

        $userEmail = $_SESSION['email'];

        $query = "SELECT access FROM users WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $userEmail, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $access = $user['access'];

        function getCategories($pdo)
        {
            $query = "SELECT * FROM category";
            $stmt = $pdo->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $categories = getCategories($pdo);

        $sql = "SELECT scale, detail, equipment, decal, stand, origin, description
                FROM product_detail
                WHERE id_product = :id_product";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_product', $id_product, PDO::PARAM_INT);
        $stmt->execute();
        $productDetail = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        header("Location: ../view/error.php");
        exit;
    }
} else {
    echo "Không có ID.";
}
include '../include/header-pd.php';
?>
<title>
    <?php echo $productInformation['name_category'] . ' - ' . $productInformation['product_name']; ?>
</title>
<div class="container-lg mt-3">
    <!-- Main Content -->
    <div class="row">
        <div class="col-lg-6 col-md-6">
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
                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

        <div class="col-lg-6 col-md-6">
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

            <div class="row">
                <div class="rating">
                    <span class="star" data-rating="1">&#9733;</span>
                    <span class="star" data-rating="2">&#9733;</span>
                    <span class="star" data-rating="3">&#9733;</span>
                    <span class="star" data-rating="4">&#9733;</span>
                    <span class="star" data-rating="5">&#9733;</span>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 bottom-rule">
                    <h2 class="product-price" style="color: red;">
                        <?php echo number_format($productInformation['product_price']); ?> VNĐ
                    </h2>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <ul style="font-size: 18px;">
                        <li><span style="font-weight: bold;">Tỉ lệ: </span>
                            <?php echo !empty($productDetail['scale']) ? $productDetail['scale'] : 'Trống'; ?>
                        </li>
                        <li><span style="font-weight: bold;">Xuất xứ: </span>
                            <?php echo !empty($productDetail['origin']) ? $productDetail['origin'] : 'Trống'; ?>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="row add-to-cart">
                <div class="col-md-5 product-qty">
                    <button class="btn btn-light btn-lg" id="decreaseQty">
                        <i class="fas fa-minus"></i>
                    </button>
                    <input class="btn btn-light btn-lg" type="number" value="1" style="width: 100px;" id="quantity"
                        readonly />
                    <button class="btn btn-light btn-lg" id="increaseQty">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="col-md-7">
                    <button href="#" class="btn btn-danger">THÊM VÀO GIỎ HÀNG</button>
                </div>
            </div>

            <hr style="color: white;">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="myTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="description-tab" data-bs-toggle="tab" href="#description" role="tab"
                        aria-controls="description" aria-selected="true">Mô tả</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="features-tab" data-bs-toggle="tab" href="#features" role="tab"
                        aria-controls="features" aria-selected="false">Đặc điểm</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="reviews-tab" data-bs-toggle="tab" href="#reviews" role="tab"
                        aria-controls="reviews" aria-selected="false">Đánh giá</a>
                </li>
            </ul>
            <!-- End Nav tabs -->

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane fade show active" id="description" role="tabpanel"
                    aria-labelledby="description-tab">
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

                <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                    <div class="review">
                        <div class="review-avatar">
                            <img src="https://image.ibb.co/jw55Ex/def_face.jpg" alt="Avatar" class="review-avatar-img">
                            <p class="review-time">15 Phút Trước</p>
                        </div>
                        <div class="review-content">
                            <div class="review-header">
                                <a href="https://maniruzzaman-akash.blogspot.com/p/contact.html"
                                    class="review-author">Person 1</a>
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
                                <a href="https://maniruzzaman-akash.blogspot.com/p/contact.html"
                                    class="review-author">Person 2</a>
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
</body>

</html>
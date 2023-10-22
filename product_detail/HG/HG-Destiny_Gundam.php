<?php
require '../../include/connect.php';
require '../../include/user_session.php';

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
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        header("Location: ../../view/error.php");
        exit;
    }
} else {
    echo "Không có ID.";
}
include '../../include/header-pd.php';
?>

<div class="container-lg mt-3">
    <!-- Main Content -->
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    $imagePath = '../../asset/product/' . strtolower($productInformation['name_category']) . '/' . strtolower($productInformation['name_category']) . '-' . strtolower($productInformation['product_name']);
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
                    <ul style="font-size: 18px; font-weight: bold;">
                        <li>Grade: HG - High Grade</li>
                        <li>Xuất xứ: Nhật Bản</li>
                        <li>Chất liệu: Nhựa</li>
                        <li>Hàng chính hãng Bandai</li>
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
                    <p>GAT-X105B/FP Build Strike Gundam Full Package ( HGBF - 1/144) là Gunpla xuất hiện trong
                        series anime Gundam Build Fighters. <br>Mẫu Gunpla này được build bởi Sei Iori và điều khiển
                        bởi Reiji.

                    </p>
                    <div class="image-container">
                        <?php
                        $imagePath = '../../asset/product/' . strtolower($productInformation['name_category']) . '/' . strtolower($productInformation['name_category']) . '-' . strtolower($productInformation['product_name']);
                        $images = glob($imagePath . '/*.{jpg,png,gif}', GLOB_BRACE);

                        foreach ($images as $index => $image) {
                            echo '<img src="' . $image . '" alt="Ảnh sản phẩm ' . ($index + 1) . '">';
                        }
                        ?>
                    </div>
                </div>

                <div class="tab-pane fade" id="features" role="tabpanel" aria-labelledby="features-tab">
                    <ul>
                        <li><strong>Cấp độ:</strong> HG với tỷ lệ 1/144</li>
                        <li><strong>Độ chi tiết:</strong> Vừa phải, khớp chuyển động tương đối linh hoạt. Ráp theo
                            kiểu bấm khớp, không cần dùng keo dán.</li>
                        <li><strong>Trang bị:</strong> Beam Rifle, Beam Gun, Rifle được chỉnh sửa, khiên chắn, 2
                            Beam Saber, 2 Cannon.</li>
                        <li><strong>Decal:</strong> Đính kèm decal dán.</li>
                        <li><strong>Đế dựng:</strong>Không kèm đế dựng.</li>
                        <li><strong>Xuất xứ:</strong> Sản phẩm Gunpla chính hãng của Bandai, sản xuất tại Nhật Bản.
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
<!-- Footer -->
<footer>
    <div class="container-fluid">
        <div class="row">
            <!-- Address -->
            <div class="col-6 pt-2 ps-5"><a href="#"><img src="../../asset/icon/icon.png" alt=""
                        style="width: 250px;"></a>
                <p>Địa chỉ: Đ. 3/2, P. Xuân Khánh, Q. Ninh Kiều, TP. CT</p>
            </div>
            <!-- End Address -->

            <!-- Contact -->
            <div class="col-6 text-end pt-5 mt-3 pe-5">
                <a href="https://facebook.com" target="_blank"><i class="icon fa-brands fa-facebook"></i></a>
                <a href="https://tiktok.com" target="_blank"><i class="icon fa-brands fa-tiktok"></i></a>
                <a href="https://youtube.com" target="_blank"><i class="icon fa-brands fa-youtube"></i></a>
                <a href="https://twitter.com" target="_blank"><i class="fa-brands fa-twitter"></i></a>
                <a href="https://instagram.com" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                <p>Liên hệ để được tư vấn miễn phí.</p>
            </div>
            <!-- End Contact -->
        </div>
    </div>
</footer>
<!-- End Footer -->
</body>

</html>
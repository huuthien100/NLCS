<?php
session_start();

require 'include/connect.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['username'])) {
    header("Location: view/login.php");
    exit;
}

$userEmail = $_SESSION['email'];
$query = "SELECT access FROM users WHERE email = :email";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':email', $userEmail, PDO::PARAM_STR);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$access = $user['access'];

function getProductsGroupedByCategory($pdo)
{
    $query = "SELECT category.id_category, category.name_category, 
              products.product_name, products.product_img, products.product_price
              FROM category
              LEFT JOIN products ON category.id_category = products.id_category
              ORDER BY category.id_category, products.product_name";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $productsByCategory = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $category = $row['name_category'];
        $product = array(
            'name' => $row['product_name'],
            'image' => $row['product_img'],
            'price' => $row['product_price'],
        );

        $productsByCategory[$category][] = $product;
    }

    return $productsByCategory;
}

$productsByCategory = getProductsGroupedByCategory($pdo);


//List Group
function getCategories($pdo)
{
    $query = "SELECT * FROM category";
    $stmt = $pdo->query($query);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$categories = getCategories($pdo);

function getCategoriesWithProductCount($pdo)
{
    $query = "SELECT category.id_category, category.name_category, COUNT(products.id_product) as product_count
              FROM category
              LEFT JOIN products ON category.id_category = products.id_category
              GROUP BY category.id_category, category.name_category";

    $stmt = $pdo->query($query);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$categories = getCategoriesWithProductCount($pdo);
//List Group End
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gundam Shop</title>
    <link rel="icon" type="image.png" href="asset/icon/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="asset/style.css">
    <script src="asset/script.js"></script>
</head>

<body>
    <!-- Nav 1 -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img id="logo" src="asset/icon/icon.png" alt="Logo" class="ms-5">
            </a>
            <div class="dropdown pt-3">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle me-4"
                    id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php
                    if (isset($_SESSION['username'])) {
                        echo "<span style='color: black;'>Xin chào, " . $_SESSION['username'] . "!</span>__";
                    }
                    ?>

                    <img src="asset/icon/profile-user.png" alt="user.png" width="35" height="35" class="rounded-circle">
                </a>
                <ul class="dropdown-menu bg-body-tertiary dropdown-menu-lg-end" style="z-index: 10000;">
                    <?php
                    if ($access == 0) {
                        echo '<li><a class="dropdown-item" href="view/admin.php">Quản lý</a></li>';
                    } else if ($access == 1) {
                        echo '<li><a class="dropdown-item" href="view/account.php">Tài khoản</a></li>';
                    }
                    ?>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="view/logout.php">Đăng xuất</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Nav 1 -->
    <div class="divider"></div>
    <!-- Nav 2 -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary stick-nav">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-5">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Gundam
                        </a>
                        <ul class="dropdown-menu">
                            <?php
                            foreach ($categories as $category) {
                                $categoryName = strtolower($category['name_category']);
                                if ($categoryName !== 'tool' && $categoryName !== 'base') {
                                    echo '<a class="dropdown-item" href="view/products.php?category=' . $categoryName . '">' . $category['name_category'] . '</a>';
                                }
                            }
                            ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view/products.php?category=tool">Tool</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view/products.php?category=base">Base</a>
                    </li>
                </ul>
            </div>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="cart.html" class="nav-icon2"><i class="fas fa-shopping-cart me-5"></i></a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End Nav 2 -->
    <!-- Body -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <!-- Listgroup Category -->
                <div class="list-group mt-1">
                    <?php
                    foreach ($categories as $category) {
                        echo '<a href="view/products.php?category=' . strtolower($category['name_category']) . '" class="list-group-item d-flex justify-content-between align-items-center">' . $category['name_category'] . '<span class="badge bg-primary rounded-pill">' . $category['product_count'] . '</span></a>';
                    }
                    ?>
                </div>
                <!-- End Listgroup Category -->

            </div>
            <div class="col-md-10">
                <!-- Carousel -->
                <div id="carouselAutoplaying" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselAutoplaying" data-bs-slide-to="0" class="active"
                            aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselAutoplaying" data-bs-slide-to="1"
                            aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselAutoplaying" data-bs-slide-to="2"
                            aria-label="Slide 3"></button>
                        <button type="button" data-bs-target="#carouselAutoplaying" data-bs-slide-to="3"
                            aria-label="Slide 4"></button>
                        <button type="button" data-bs-target="#carouselAutoplaying" data-bs-slide-to="4"
                            aria-label="Slide 5"></button>
                    </div>
                    <div class="carousel-inner mt-1">
                        <div class="carousel-item active">
                            <img src="asset/carousel/HG.png" class="d-block w-100" alt="HG.png">
                        </div>
                        <div class="carousel-item">
                            <img src="asset/carousel/RG.png" class="d-block w-100" alt="RG.png">
                        </div>
                        <div class="carousel-item">
                            <img src="asset/carousel/MG.png" class="d-block w-100" alt="MG.png">
                        </div>
                        <div class="carousel-item">
                            <img src="asset/carousel/PG.png" class="d-block w-100" alt="PG.png">
                        </div>
                        <div class="carousel-item">
                            <img src="asset/carousel/SD.png" class="d-block w-100" alt="SD.png">
                        </div>
                    </div>
                </div>
                <!-- End Carousel -->
                <!-- Product -->
                <div class="m-3 text-center mx-auto">
                    <?php
                    foreach ($productsByCategory as $category => $products) {
                        echo '<div class="row">';
                        shuffle($products);
                        $randomProducts = array_slice($products, 0, 3);

                        foreach ($randomProducts as $product) {
                            echo '<div class="col-lg-4 col-md-6 mt-3">
                                <div class="card">
                                    <img src="' . substr($product['image'], 3) . '"
                                        class="card-img-top p-3" alt="' . $product['image'] . '">
                                    <div class="card-body">
                                        <h5 class="card-title">' . $product['name'] . '</h5>
                                        <p class="card-text">' . number_format($product['price']) . ' VNĐ</p>
                                        <a href="product_detail/' . strtolower($category) . '/' . strtolower($category) . '-' . str_replace(' ', '_', strtolower($product['name'])) . '.php" class="btn btn-danger">Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>';
                        }

                        echo '</div>';
                    }
                    ?>
                </div>
                <!-- End Product -->
            </div>
        </div>
    </div>
    <!-- End Body -->
    <footer>
        <div class="container-fluid">
            <div class="row">
                <!-- Address -->
                <div class="col-6 pt-2 ps-5"><a href="#"><img src="asset/icon/icon.png" alt=""
                            style="width: 250px; margin-left: -25px;"></a>
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
</body>

</html>
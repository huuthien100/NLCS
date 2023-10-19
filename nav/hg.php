<?php
session_start();
require '../view/connect.php'; // Assuming this includes your database connection

if (!isset($_SESSION['email']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$categoryName = isset($_GET['category_name']) ? $_GET['category_name'] : "HG";

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Use prepared statements
    $sql = "SELECT p.product_name, p.product_img, p.product_price, c.name_category 
            FROM products p
            INNER JOIN category c ON p.id_category = c.id_category
            WHERE c.name_category = :categoryName";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':categoryName', $categoryName, PDO::PARAM_STR);
    $stmt->execute();
    $productInformation = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $userEmail = $_SESSION['email'];

    // Use prepared statements for user query
    $query = "SELECT access FROM users WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $userEmail, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $access = $user['access'];
    
    // Function to get categories
    function getCategories($pdo) {
        $query = "SELECT * FROM category";
        $stmt = $pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $categories = getCategories($pdo);
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    header("Location: ../view/error.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HG - High Grade</title>
    <link rel="icon" type="image/png" href="../asset/icon/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../asset/style.css">
    <script src="../asset/script.js"></script>
</head>

<body>
    <!-- Nav 1 -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-lg">
            <a class="navbar-brand p-1" href="../index.php">
                <img id="logo" src="../asset/icon/icon.png" alt="Logo">
            </a>

            <div class="d-flex justify-content-between">
                <div class="dropdown pt-3" style="margin-left: 943px;">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                        id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                        if (isset($_SESSION['username'])) {
                            echo "<span style='color: black;'>Xin chào, " . $_SESSION['username'] . "!</span>__";
                        }
                        ?>

                        <img src="../asset/icon/profile-user.png" alt="user.png" width="35" height="35"
                            class="rounded-circle">
                    </a>
                    <ul class="dropdown-menu bg-body-tertiary dropdown-menu-lg-end" style="z-index: 100000;">
                        <?php
                        if ($access == 0) {
                            echo '<li><a class="dropdown-item" href="../view/admin.php">Quản lý</a></li>';
                        } else if ($access == 1) {
                            echo '<li><a class="dropdown-item" href="../view/account.php">Tài khoản</a></li>';
                        }
                        ?>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="view/logout.php">Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Nav 1 -->
    <div class="divider"></div>
    <!-- Nav 2 -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary stick-nav">
        <div class="container-lg">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <div class="row">
                    <div class="col-lg-10">
                        <ul class="navbar-nav d-flex">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Gundam
                                </a>
                                <ul class="dropdown-menu">
                                    <?php
                                    foreach ($categories as $category) {
                                        echo '<a class="dropdown-item" href="' . strtolower($category['name_category']) . '.php">' . $category['name_category'] . '</a>';
                                    }
                                    ?>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="tool.php">Tool</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="base.php">Base</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-2">
                        <ul class="navbar-nav" style="margin-right: 1533px;">
                            <li class="nav-item">
                                <a href="cart.html" class="nav-icon2"><i class="fas fa-shopping-cart"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Nav 2 -->
    <!-- Product -->
    <div class="container-lg">
        <div class="m-3 text-center mx-auto">
            <div class="row">
                <?php
                // Hiển thị sản phẩm từ cơ sở dữ liệu
                if (isset($productInformation) && is_array($productInformation)) {
                    foreach ($productInformation as $proinf) {
                        echo '<div class="col-md-4 mt-3 col-sm-6">
                            <div class="card">
                                <img src="' . $proinf["product_img"] . '" class="card-img-top p-3" alt="' . $proinf["product_name"] . '">
                                <div class="card-body">
                                    <h5 class="card-title">' . $proinf["product_name"] . '</h5>
                                    <p class="card-text">' . number_format($proinf["product_price"], 0, '', '.') . ' VNĐ</p>
                                    <a href="../product_detail/' . strtolower($proinf['name_category']) . '/' . strtolower($proinf['name_category']) . '-' . str_replace(' ', '_', $proinf["product_name"]) . '.php" class="btn btn-danger">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <!-- End Product -->

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <!-- Address -->
                <div class="col address"><a href="#"><img src="../asset/icon/icon.png" alt=""
                            style="width: 300px; margin-left: -25px;"></a>
                    <p>Địa chỉ: Đ. 3/2, P. Xuân Khánh, Q. Ninh Kiều, TP. CT</p>
                </div>
                <!-- End Address -->

                <!-- Contact -->
                <div class="col contact text-end">
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
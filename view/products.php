<?php
require '../include/connect.php';
require '../include/user_session.php';

$categoryName = isset($_GET['category']) ? $_GET['category'] : pathinfo(basename($_SERVER['PHP_SELF']), PATHINFO_FILENAME);

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT p.product_name, p.product_img, p.product_price, c.name_category,p.id_product
            FROM products p
            INNER JOIN category c ON p.id_category = c.id_category
            WHERE c.name_category = :categoryName";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':categoryName', $categoryName, PDO::PARAM_STR);
    $stmt->execute();
    $productInformation = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    header("Location: ../view/error.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../asset/icon/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../asset/style.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="../asset/script.js"></script>
    <title>
        <?php
        $formattedCategoryName = htmlspecialchars($categoryName);

        if (strlen($categoryName) > 2) {
            $formattedCategoryName = ucwords($formattedCategoryName);
        } else {
            $formattedCategoryName = strtoupper($formattedCategoryName);
        }

        echo $formattedCategoryName;
        ?>
    </title>
</head>

<body>
    <!-- Nav 1 -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">
                <img id="logo" src="../asset/icon/icon.png" alt="Logo" class="ms-5">
            </a>
            <div class="dropdown pt-3">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle me-4"
                    id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php
                    if (isset($_SESSION['username'])) {
                        echo "<span style='color: black;'>Xin chào, " . $_SESSION['username'] . "!</span>__";
                    }
                    ?>
                    <img src="../asset/icon/profile-user.png" alt="user.png" width="35" height="35"
                        class="rounded-circle">
                </a>
                <ul class="dropdown-menu bg-body-tertiary dropdown-menu-lg-end" style="z-index: 10000;">
                    <?php
                    if ($access == 0) {
                        echo '<li><a class="dropdown-item" href="admin.php">Quản lý</a></li>';
                    } else if ($access == 1) {
                        echo '<li><a class="dropdown-item" href="account.php">Tài khoản</a></li>';
                    }
                    ?>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
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
                                    echo '<a class="dropdown-item" href="products.php?category=' . $categoryName . '">' . $category['name_category'] . '</a>';
                                }
                            }
                            ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php?category=tool">Tool</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php?category=base">Base</a>
                    </li>
                </ul>
            </div>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="cart.html" class="nav-icon"><i class="fas fa-shopping-cart me-5"></i></a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End Nav 2 -->
    <!-- Product -->
    <div class="container-lg">
        <div class="m-3 text-center mx-auto">
            <div class="row">
                <?php
                if (isset($productInformation) && is_array($productInformation)) {
                    foreach ($productInformation as $proinf) {
                        echo '<div class="col-md-4 mt-3 col-sm-6">
                        <div class="card">
                            <img src="' . $proinf["product_img"] . '" class="card-img-top p-3" alt="' . $proinf["product_name"] . '">
                            <div class="card-body">
                                <h5 class="card-title">' . $proinf["product_name"] . '</h5>
                                <p class="card-text">' . number_format($proinf["product_price"], 0, '', '.') . ' VNĐ</p>
                                <a href="product_detail.php?id_product=' . $proinf["id_product"] . '" class="btn btn-danger">Xem chi tiết</a>
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
    <?php include '../include/footer.html'; ?>
</body>

</html>
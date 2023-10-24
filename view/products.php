<?php
require '../include/connect.php';
require '../include/user_session.php';

$categoryName = isset($_GET['category']) ? $_GET['category'] : pathinfo(basename($_SERVER['PHP_SELF']), PATHINFO_FILENAME);

$searchText = isset($_POST['search']) ? $_POST['search'] : '';

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!empty($searchText)) {
        $sql = "SELECT p.product_name, p.product_img, p.product_price, c.name_category, p.id_product
                FROM products p
                INNER JOIN category c ON p.id_category = c.id_category
                WHERE c.name_category = :categoryName AND p.product_name LIKE :searchText";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':categoryName', $categoryName, PDO::PARAM_STR);
        $stmt->bindValue(':searchText', '%' . $searchText . '%', PDO::PARAM_STR);
        $stmt->execute();
        $productInformation = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $sql = "SELECT p.product_name, p.product_img, p.product_price, c.name_category, p.id_product
                FROM products p
                INNER JOIN category c ON p.id_category = c.id_category
                WHERE c.name_category = :categoryName";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':categoryName', $categoryName, PDO::PARAM_STR);
        $stmt->execute();
        $productInformation = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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
//Search
$searchResults = array();

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $search = htmlspecialchars($search);

    $query = "SELECT * FROM products WHERE product_name LIKE :search";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':search', "%" . $search . "%", PDO::PARAM_STR);
    $stmt->execute();
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
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
                    <div class="col-md-6 d-flex justify-content-end">
                        <ul class="navbar-nav me-5">
                            <form class="nav-item d-flex pe-3" role="search" method="POST"
                                action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input class="form-control me-2" type="search" name="search" placeholder="Tìm kiếm"
                                    aria-label="Search">
                                <button class="btn btn-dark rounded-2" type="submit"><i
                                        class="fa-solid fa-magnifying-glass"></i></button>
                            </form>
                            <li class="nav-item">
                                <a href="cart.html" class="nav-icon"><i class="fas fa-shopping-cart "></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Nav 2 -->
    <!-- Product and Search -->
    <div class="container-lg">
    <?php
    if (isset($_POST['search'])) {
        $searchText = $_POST['search'];
        if (strlen($searchText) === 0) {
            echo '<div class="alert alert-danger text-center mt-3" role="alert">Vui lòng nhập từ khóa để tìm kiếm sản phẩm</div>';
        } else {
            if (!empty($searchResults)) {
                echo '<div class="row ">';
                foreach ($searchResults as $product) {
                    echo '<div class="col-lg-4 col-sm-6 col-md-6 mt-3 mb-3">
                    <div class="card text-center">
                        <img src="' .$product['product_img']. '"
                            class="card-img-top p-3" alt="' . $product['product_img'] . '">
                        <div class="card-body">
                            <h5 class="card-title">' . $product['product_name'] . '</h5>
                            <p class="card-text">' . number_format($product['product_price']) . ' VNĐ</p>
                            <a href="product_detail.php?id_product=' . $product["id_product"] . '" class="btn btn-danger">Xem chi tiết</a>
                        </div>
                    </div>
                </div>';
                }
                echo '</div>';
            } else {
                echo '<div class="alert alert-danger text-center mt-3" role="alert">Sản phẩm bạn tìm kiếm không tồn tại</div>';
            }
        }
    } else {
        echo '<div class="row ">';
        if (isset($productInformation) && is_array($productInformation)) {
            foreach ($productInformation as $proinf) {
                echo '<div class="col-md-4 mt-3 col-sm-6 text-center">
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
        echo '</div>';
    }
    ?>
    <!-- End Product and Search -->
    </div>

    <?php include '../include/footer.html'; ?>
</body>

</html>
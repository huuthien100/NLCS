<?php
require '../include/connect.php';
require '../include/user_session.php';

$categoryName = isset($_GET['category']) ? $_GET['category'] : pathinfo(basename($_SERVER['PHP_SELF']), PATHINFO_FILENAME);

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT p.product_name, p.product_img, p.product_price, c.name_category 
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
<?php include '../include/header.php'; ?>
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
<?php include '../include/footer.html'; ?>
</body>

</html>
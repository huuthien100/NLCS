<?php
require '../include/connect.php';
require '../include/user_session.php';

if (isset($_GET['id_product'])) {
    $product_id = $_GET['id_product'];

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $product_query = "SELECT p.product_name, p.product_price, pd.scale, pd.detail, pd.equipment, pd.decal, pd.stand, pd.origin, pi.img_url
                         FROM products p
                         INNER JOIN product_detail pd ON p.id_product = pd.id_product
                         LEFT JOIN product_img pi ON p.product_id = pi.product_id
                         WHERE p.id_product = :id_product";

        $stmt = $pdo->prepare($product_query);
        $stmt->bindParam(':id_product', $product_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            echo '<table>';
            echo '<tr><th>Name</th><th>Price</th><th>Scale</th><th>Detail</th><th>Equipment</th><th>Decal</th><th>Stand</th><th>Origin</th><th>Image</th></tr>';
            echo '<tr>';
            echo '<td>' . $product['product_name'] . '</td>';
            echo '<td>' . $product['product_price'] . '</td>';
            echo '<td>' . $product['scale'] . '</td>';
            echo '<td>' . $product['detail'] . '</td>';
            echo '<td>' . $product['equipment'] . '</td>';
            echo '<td>' . $product['decal'] . '</td>';
            echo '<td>' . $product['stand'] . '</td>';
            echo '<td>' . $product['origin'] . '</td>';
            echo '<td><img src="' . $product['img_url'] . '" alt="Product Image" width="100"></td>';
            echo '</tr>';
            echo '</table>';
        } else {
            echo 'Product not found';
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>

<?php include '../include/header-ad.php'; ?>
<title>Chi tiết sản phẩm</title>
</body>
</html>

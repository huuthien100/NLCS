<?php
session_start();

require_once "connect.php";

$id_user = 1;

$sql = "SELECT username, email, password FROM users WHERE id_user = :id_user";

if ($stmt = $pdo->prepare($sql)) {
    $stmt->bindParam(":id_user", $id_user, PDO::PARAM_INT);

    if ($stmt->execute()) {
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['username'] = $user["username"];
            $_SESSION['email'] = $user["email"];
            // Không lưu trữ mật khẩu trong phiên
        }
    }
}

unset($stmt);

unset($pdo);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài Khoản</title>
    <link rel="icon" type="image/png" href="../asset/icon/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../asset/style.css">
    <script src="../asset/script.js"></script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <img src="../asset/icon/icon.png" alt="Logo" id="logo">
            </a>
            <div class="d-flex">
                <a href="account.php" class="nav-link">
                    <i class="fas fa-user"></i>
                </a>
                <span class="mx-2">|</span>
                <a href="cart.html" class="nav-link">
                    <i class="fas fa-shopping-cart"></i>
                </a>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->

    <div class="container">
        <div class="form-container">
            <form name="form_account" id="form_account" action="update_account.php" method="POST">
                <div class="title-image">
                    <img src="../asset/icon/account.png" alt="Hình ảnh tiêu đề">
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label"><i class="fa-solid fa-user"></i> Tên người dùng:</label>
                    <input type="text" class="form-control" id="username" name="username"
                        value="<?php echo $_SESSION['username']; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label"><i class="fa-solid fa-envelope"></i> Email:</label>
                    <input type="text" class="form-control" id="email" name="email"
                        value="<?php echo $_SESSION['email']; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label"><i class="fa-solid fa-key"></i> Mật khẩu:</label>
                    <input type="password" class="form-control" id="password" name="password" value="......" readonly>
                </div>
                <div class="mb-3 center-button">
                    <button type="button" class="btn btn-danger btn-logout">
                        Đăng xuất
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
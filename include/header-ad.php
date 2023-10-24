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
</head>

<body>
    <!-- Nav 1 -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin.php">
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
                    <li><a class="dropdown-item" href="admin.php">Trang quản lý</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Nav 1 -->
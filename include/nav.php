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
                <img src="../asset/icon/profile-user.png" alt="user.png" width="35" height="35" class="rounded-circle">
            </a>
            <ul class="dropdown-menu bg-body-tertiary dropdown-menu-lg-end" style="z-index: 10000;">
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
                <a href="cart.html" class="nav-icon2"><i class="fas fa-shopping-cart me-5"></i></a>
            </li>
        </ul>
    </div>
</nav>
<!-- End Nav 2 -->
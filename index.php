<?php
session_start();

if (!isset($_SESSION['email']) || !isset($_SESSION['username'])) {
    header("Location: view/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gundam Shop</title>
    <link rel="icon" type="image/png" href="asset/icon/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="   https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="asset/style.css">
    <script src="asset/script.js"></script>
<body>
    <!-- Nav 1 -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-lg">
            <a class="navbar-brand p-1" href="index.php">
                <img id="logo" src="asset/icon/icon.png" alt="Logo">
            </a>

            <div class="d-flex justify-content-between mt-2">
                <a href="view/account.php" class="nav-icon1">
                    <i class="fa-solid fa-user"></i>
                </a>
                <span class="vr mx-2"></span>
                <a href="cart.html" class="nav-icon2"><i class="fas fa-shopping-cart"></i></a>
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
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Gundam
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="nav/hg.html">HG</a></li>
                            <li><a class="dropdown-item" href="nav/rg.html">RG</a></li>
                            <li><a class="dropdown-item" href="nav/mg.html">MG</a></li>
                            <li><a class="dropdown-item" href="nav/pg.html">PG</a></li>
                            <li><a class="dropdown-item" href="nav/sd.html">SD</a></li>
                            <li><a class="dropdown-item" href="nav/mb.html">MB</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="nav/tool.html">Tool</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="nav/base.html">Base</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Nav 2 -->
    <!-- Body -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <!-- Listgroup Category -->
                <div class="list-group mt-1">
                    <a href="nav/hg.html" class="list-group-item d-flex justify-content-between align-items-center">
                        HG
                        <span class="badge bg-primary rounded-pill">6</span>
                    </a>
                    <a href="nav/rg.html" class="list-group-item d-flex justify-content-between align-items-center">
                        RG
                        <span class="badge bg-primary rounded-pill">6</span>
                    </a>
                    <a href="nav/mg.html" class="list-group-item d-flex justify-content-between align-items-center">
                        MG
                        <span class="badge bg-primary rounded-pill">6</span>
                    </a>
                    <a href="nav/pg.html" class="list-group-item d-flex justify-content-between align-items-center">
                        PG
                        <span class="badge bg-primary rounded-pill">6</span>
                    </a>
                    <a href="n../av/sd.html" class="list-group-item d-flex justify-content-between align-items-center">
                        SD
                        <span class="badge bg-primary rounded-pill">6</span>
                    </a>
                    <a href="nav/mb.html" class="list-group-item d-flex justify-content-between align-items-center">
                        MB
                        <span class="badge bg-primary rounded-pill">6</span>
                    </a>
                    <a href="nav/tool.html" class="list-group-item d-flex justify-content-between align-items-center">
                        Tool
                        <span class="badge bg-primary rounded-pill">6</span>
                    </a>
                    <a href="nav/base.html" class="list-group-item d-flex justify-content-between align-items-center">
                        Base
                        <span class="badge bg-primary rounded-pill">6</span>
                    </a>
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
                    <div class="row">
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <img src="asset/product/HG/HG-Aerial Gundam/HG-Aerial Gundam (1).jpg"
                                    class="card-img-top p-3" alt="HG-Aerial Gundam (1).jpg">
                                <div class="card-body">
                                    <h5 class="card-title">Aerial Gundam</h5>
                                    <p class="card-text">580.000 VNĐ</p>
                                    <a href="product_detail/HG/HG-Aerial_Gundam.html" class="btn btn-danger">Xem chi
                                        tiết</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <img src="asset/product/RG/RG-Aile Strike Gundam/RG-Aile Strike Gundam (1).jpg"
                                    class="card-img-top p-3" alt="RG-Aile Strike Gundam (1).jpg">
                                <div class="card-body">
                                    <h5 class="card-title">Aile Strike Gundam</h5>
                                    <p class="card-text">730.000 VNĐ</p>
                                    <a href="product_detail/RG/RG-Aile_Strike_Gundam.html" class="btn btn-danger">Xem
                                        chi tiết</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <img src="asset/product/MG/MG-Avalanche Exia Dash Gundam/MG-Avalanche Exia Dash Gundam (1).jpg"
                                    class="card-img-top p-3" alt="MG-Avalanche Exia Dash Gundam (1).jpg">
                                <div class="card-body">
                                    <h5 class="card-title">Avalanche Exia Dash Gundam</h5>
                                    <p class="card-text">1.850.000 VNĐ</p>
                                    <a href="product_detail/MG/MG-Avalanche_Exia_Dash_Gundam.html"
                                        class="btn btn-danger">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <img src="asset/product/PG/PG-00 Seven Sword Gundam/PG-00 Seven Sword Gundam (1).jpg"
                                    class="card-img-top p-3" alt="PG-00 Seven Sword Gundam (1).jpg">
                                <div class="card-body">
                                    <h5 class="card-title">00 Seven Sword Gundam</h5>
                                    <p class="card-text">7.400.000 VNĐ</p>
                                    <a href="product_detail/PG/PG-00_Seven_Sword_Gundam.html" class="btn btn-danger">Xem
                                        chi tiết</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <img src="asset/product/SD/SD-00 Gundam/SD-00 Gundam (1).jpg" class="card-img-top p-3"
                                    alt="SD-00 Gundam (1).jpg">
                                <div class="card-body">
                                    <h5 class="card-title">00 Gundam</h5>
                                    <p class="card-text">250.000 VNĐ</p>
                                    <a href="product_detail/SD/SD-00_Gundam.html" class="btn btn-danger">Xem chi
                                        tiết</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <img src="asset/product/MB/MB-Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)/MB-Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.) (1).jpg"
                                    class="card-img-top p-3"
                                    alt="MB-Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.) (1).jpg">
                                <div class="card-body">
                                    <h5 class="card-title">Astray GF Amatsu Mina Gundam</h5>
                                    <p class="card-text">4.500.000 VNĐ</p>
                                    <a href="product_detail/MB/MB-Astray_Gold_Frame_Amatsu_Mina_Gundam.html"
                                        class="btn btn-danger">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Product -->
            </div>
        </div>
    </div>
    <!-- End Body -->
    <!-- Footer -->
    <footer>
        <div class="container-lg">
            <div class="row">
                <!-- Address -->
                <div class="col address"><a href="#"><img src="asset/icon/icon.png" alt="icon.png"
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
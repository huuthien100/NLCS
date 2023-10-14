<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['username'])) {
    header("Location: view/login.php");
    exit;
}

if (isset($_POST['delete_user'])) {
    try {
        $user_id = $_POST['delete_user'];

        $deleteSql = "DELETE FROM users WHERE id_user = :user_id";
        $deleteStmt = $pdo->prepare($deleteSql);
        $deleteStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $deleteStmt->execute();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT id_user, username, email FROM users";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $userInformation = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng</title>
    <link rel="icon" type="image/png" href="../asset/icon/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="../asset/style.css">
    <script src="../asset/script.js"></script>
</head>

<body>
    <!-- Nav 1 -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-lg">
            <a class="navbar-brand p-1" href="#">
                <img id="logo" src="../asset/icon/icon.png" alt="Logo">
            </a>

            <div class="d-flex justify-content-between">
                <div class="dropdown pt-3" style="margin-left: 930px;">
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
                        <li><a class="dropdown-item" href="../admin.php">Trang sản phẩm</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Nav 1 -->

    <div class="container-fluid">
        <div class="row flex-nowrap">
            <!-- Sidebar -->
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-body-tertiary">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                    <ul class="nav flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                        <li class="nav-item">
                            <a href="admin.php" class="nav-link align-middle px-0 font-black">
                                <i class="fas fa-home"></i> Trang chủ
                            </a>
                        </li>
                        <li>
                            <a href="users_manage.php" class="nav-link px-0 align-middle font-black">
                                <i class="fas fa-users"></i> Quản lý thành viên
                            </a>
                        </li>
                        <li>
                            <a href="category_manage.php" class="nav-link px-0 align-middle font-black">
                                <i class="fas fa-list"></i> Quản lý danh mục
                            </a>
                        </li>
                        <li>
                            <a href="products_manage.php" class="nav-link px-0 align-middle font-black">
                                <i class="fas fa-box-open"></i> Quản lý sản phẩm
                            </a>
                        </li>
                        <li>
                            <a href="comment_manage.php" class="nav-link px-0 align-middle font-black">
                                <i class="fas fa-comments"></i> Quản lý bình luận
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- End Sidebar -->
            <div class="col py-3">
                <div class="d-flex justify-content-between">
                    <h1>Quản lý thành viên</h1>
                    <a href="add_users.php" class="btn btn-success pt-3">
                        Thêm người dùng <i class="fa-solid fa-plus" style="color: #ffffff;"></i>
                    </a>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tên người dùng</th>
                            <th>Email</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($userInformation) && is_array($userInformation)) {
                            foreach ($userInformation as $user) {
                                echo "<tr>";
                                echo "<td>" . $user['username'] . "</td>";
                                echo "<td>" . $user['email'] . "</td>";
                                echo "<td>
                                <a href='edit_users.php?user_id=" . $user['id_user'] . "' class='btn btn-primary'>Edit <i class='fa-solid fa-pencil' style='color: #ffffff;'></i></a>
                                <button type='button' class='btn btn-danger delete-user' data-user-id='" . $user['id_user'] . "'>Delete <i class='fa-solid fa-trash' style='color: #ffffff;'></i></button>
                
                                <form method='post' class='delete-user-form' data-user-id='" . $user['id_user'] . "'>
                                    <input type='hidden' name='delete_user' value='" . $user['id_user'] . "'>
                                </form>
                                </td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmDeleteUserModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabelUser" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabelUser">Xác nhận xóa người dùng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="border: none; box-shadow: none; background: none; font-size:25px">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa người dùng này?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteUserButton">Xóa</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $(".delete-user").on("click", function () {
                var userId = $(this).data("user-id");

                $("#confirmDeleteUserButton").data("user-id", userId);

                $("#confirmDeleteUserModal").modal("show");
            });

            $("#confirmDeleteUserButton").on("click", function () {
                var userId = $(this).data("user-id");
                $(".delete-user-form[data-user-id='" + userId + "']").submit();
                $("#confirmDeleteUserModal").modal("hide");
            });

            $("#confirmDeleteUserModal .btn-secondary").on("click", function () {
                $("#confirmDeleteUserModal").modal("hide");
            });

            $("#confirmDeleteUserModal .close").on("click", function () {
                $("#confirmDeleteUserModal").modal("hide");
            });
        });
    </script>
</body>

</html>
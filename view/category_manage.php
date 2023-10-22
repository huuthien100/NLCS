<?php
require '../include/connect.php';
require '../include/user_session.php';

if (isset($_POST['delete_category'])) {
    try {
        $category_id = $_POST['delete_category'];

        $deleteSql = "DELETE FROM category WHERE id_category = :category_id";
        $deleteStmt = $pdo->prepare($deleteSql);
        $deleteStmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $deleteStmt->execute();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT id_category, name_category FROM category";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $categoryInformation = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<?php include '../include/header.html'; ?>
<title>Quản lý danh mục</title>
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
                    echo "<span style='color: black;'>Xin chào, " . $user['username'] . "!</span>__";
                    ?>
                    <img src="../asset/icon/profile-user.png" alt="user.png" width="35" height="35"
                        class="rounded-circle">
                </a>
                <ul class="dropdown-menu bg-body-tertiary dropdown-menu-lg-end" style="z-index: 100000;">
                    <li><a class="dropdown-item" href="../index.php">Trang sản phẩm</a></li>
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
        <!-- Category Table -->
        <div class="col py-3">
            <div class="d-flex justify-content-between">
                <h1>Quản lý danh mục</h1>
                <a href="add_category.php" class="btn btn-success pt-3">
                    Thêm danh mục <i class="fa-solid fa-plus" style="color: #ffffff;"></i>
                </a>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tên danh mục</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($categoryInformation) && is_array($categoryInformation)) {
                        foreach ($categoryInformation as $category) {
                            echo "<tr>";
                            echo "<td>" . $category['name_category'] . "</td>";
                            echo "<td>
                                <a href='edit_category.php?category_id=" . $category['id_category'] . "' class='btn btn-primary'><i class='fa-solid fa-pencil' style='color: #ffffff;'></i></a>
                                <button type='button' class='btn btn-danger delete-category' data-category-id='" . $category['id_category'] . "'><i class='fa-solid fa-trash' style='color: #ffffff;'></i></button>
                                
                                <form method='post' class='delete-category-form' data-category-id='" . $category['id_category'] . "'>
                                    <input type='hidden' name='delete_category' value='" . $category['id_category'] . "'>
                                </form>
                            </td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- End Category Table -->
    </div>
</div>
<!-- Delete Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Xác nhận xóa danh mục</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    style="border: none; box-shadow: none; background: none; font-size:25px">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa danh mục này?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Xóa</button>
            </div>
        </div>
    </div>
</div>
<!-- End Delete Modal -->
<script>
    $(document).ready(function () {
        $(".delete-category").on("click", function () {
            var categoryId = $(this).data("category-id");

            $("#confirmDeleteButton").data("category-id", categoryId);

            $("#confirmDeleteModal").modal("show");
        });

        $(document).on("keypress", function (e) {
            if (e.key === "Enter") {
                $("#confirmDeleteButton").click();
            }
        });

        $("#confirmDeleteButton").on("click", function () {
            var categoryId = $(this).data("category-id");
            $(".delete-category-form[data-category-id='" + categoryId + "']").submit();
            $("#confirmDeleteModal").modal("hide");
        });

        $("#confirmDeleteModal .btn-secondary").on("click", function () {
            $("#confirmDeleteModal").modal("hide");
        });

        $("#confirmDeleteModal .close").on("click", function () {
            $("#confirmDeleteModal").modal("hide");
        });
    });
</script>
</body>

</html>
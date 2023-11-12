<?php
require '../include/connect.php';
require '../include/user_session.php';

if (isset($_POST['delete_user'])) {
    try {
        $username = $_POST['delete_user'];

        // Xóa dữ liệu trong bảng cart liên kết với người dùng
        $deleteCartSql = "DELETE FROM cart WHERE user_id IN (SELECT user_id FROM users WHERE username = :username)";
        $deleteCartStmt = $pdo->prepare($deleteCartSql);
        $deleteCartStmt->bindParam(':username', $username, PDO::PARAM_STR);
        $deleteCartStmt->execute();

        // Sau đó, xóa người dùng
        $deleteUserSql = "DELETE FROM users WHERE username = :username";
        $deleteUserStmt = $pdo->prepare($deleteUserSql);
        $deleteUserStmt->bindParam(':username', $username, PDO::PARAM_STR);
        $deleteUserStmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Tiếp tục với việc lấy thông tin người dùng sau khi xóa
try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT username, email FROM users";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $userInformation = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<?php include '../include/header-ad.php'; ?>
<title>Quản lý thành viên</title>

<div class="container-fluid">
    <div class="row flex-nowrap">
        <!-- Sidebar -->
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-body-tertiary">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                <ul class="nav flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                    <li class="nav-item">
                        <a href="admin.php" class="nav-link align-middle px-0 font-black">
                            <i class="fas fa-home"></i> Trang quản lý
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
                        <a href="orders_manage.php" class="nav-link px-0 align-middle font-black">
                            <i class="fas fa-shopping-cart"></i> Quản lý đơn hàng
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
                <a href='edit_users.php?username=" . $user['username'] . "' class='btn btn-primary'><i class='fa-solid fa-pencil' style='color: #ffffff;'></i></a>
                <button type='button' class='btn btn-danger delete-user' data-username='" . $user['username'] . "'><i class='fa-solid fa-trash' style='color: #ffffff;'></i></button>

                <form method='post' class='delete-user-form' data-username='" . $user['username'] . "'>
                <input type='hidden' name='delete_user' value='" . $user['username'] . "'>
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

<div class="modal fade" id="confirmDeleteUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelUser" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabelUser">Xác nhận xóa người dùng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="border: none; box-shadow: none; background: none; font-size:25px">
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
    $(document).ready(function() {
        $(".delete-user").on("click", function() {
            var username = $(this).data("username");

            $("#confirmDeleteUserButton").data("username", username);

            $("#confirmDeleteUserModal").modal("show");
        });

        $(document).on("keypress", function(e) {
            if (e.key === "Enter") {
                $("#confirmDeleteUserButton").click();
            }
        });

        $("#confirmDeleteUserButton").on("click", function() {
            var username = $(this).data("username");
            $(".delete-user-form[data-username='" + username + "']").submit();
            $("#confirmDeleteUserModal").modal("hide");
        });

        $("#confirmDeleteUserModal .btn-secondary").on("click", function() {
            $("#confirmDeleteUserModal").modal("hide");
        });

        $("#confirmDeleteUserModal .close").on("click", function() {
            $("#confirmDeleteUserModal").modal("hide");
        });
    });
</script>
</body>

</html>
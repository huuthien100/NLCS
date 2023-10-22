<?php
require '../include/connect.php';
require '../include/user_session.php';

function isCategoryNameDuplicate($pdo, $categoryName, $category_id = null)
{
    $query = "SELECT name_category FROM category WHERE name_category = :categoryName";
    if ($category_id !== null) {
        $query .= " AND id_category <> :category_id";
    }

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':categoryName', $categoryName);
    if ($category_id !== null) {
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    }

    $stmt->execute();

    return $stmt->rowCount() > 0;
}

$errorMsg = '';

if (isset($_POST['submit'])) {
    $categoryName = $_POST['newCategoryName'];
    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (isCategoryNameDuplicate($pdo, $categoryName)) {
            $errorMsg = 'Tên danh mục đã được sử dụng! Vui lòng sử dụng tên khác!';
        } else {
            $insert = "INSERT INTO category (name_category) VALUES (:categoryName)";
            $stmt = $pdo->prepare($insert);
            $stmt->bindParam(':categoryName', $categoryName);

            if ($stmt->execute()) {
                echo "<script>alert('Thêm danh mục thành công!' );</script>";
                echo "<script>setTimeout(function() {
                    window.location.href = 'category_manage.php';
                }, 10);</script>";
            } else {
                echo "<script>alert('Thêm danh mục thất bại!');</script>";
            }
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
include '../include/header-ad.php';
?>
<title>Thêm danh mục</title>

<!-- Form -->
<div class="container">
    <div class="form-container">
        <form name="form_add_category" id="form_add_category"
            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="title-image mb-3">
                <img src="../asset/icon/add_category.png" alt="Hình ảnh tiêu đề" style="width: 150%">
            </div>
            <div class="mb-3">
                <label for="newCategoryName" class="form-label"><i class="fa-solid fa-list"></i> Tên danh mục:</label>
                <input type="text" class="form-control" id="newCategoryName" name="newCategoryName"
                    placeholder="Nhập tên danh mục" required>
            </div>

            <div class="mb-3 center-button">
                <button type="submit" name="submit" class="btn btn-success">Thêm danh mục</button>
            </div>
            <?php if (!empty($errorMsg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errorMsg; ?>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>
<!-- End Form -->
<script>
    $(document).ready(function () {
        $("#form_add_category").validate({
            rules: {
                newCategoryName: {
                    required: true,
                    minlength: 2,
                },
            },
            messages: {
                newCategoryName: {
                    required: 'Bạn chưa nhập tên danh mục',
                    minlength: 'Tên danh mục phải có ít nhất 2 ký tự'
                },
            },
            errorElement: 'div',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                if (element.prop('type') == 'checkbox') {
                    error.insertAfter(element.siblings('label'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).addClass('is-valid').removeClass('is-invalid');
            },
        });
    });
</script>
</body>

</html>
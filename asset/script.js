$(document).ready(function () {
    // Rating
    $('.star').on('click', function () {
        const rating = $(this).data('rating');
        $('#selected-rating').text(rating);
        $('.star').removeClass('active');

        for (let i = 1; i <= rating; i++) {
            $('.star[data-rating="' + i + '"]').addClass('active');
        }
    });

    // Quantity + - 
    const quantityInput = $("input#quantity");
    const decreaseButton = $("#decreaseQty");
    const increaseButton = $("#increaseQty");

    decreaseButton.click(function () {
        let currentValue = parseInt(quantityInput.val());
        if (!isNaN(currentValue) && currentValue > 1) {
            quantityInput.val(currentValue - 1);
        }
    });

    increaseButton.click(function () {
        let currentValue = parseInt(quantityInput.val());
        if (!isNaN(currentValue)) {
            quantityInput.val(currentValue + 1);
        }
    });
    // Validate Register
    $.validator.addMethod(
        "customPassword",
        function (value, element) {
            return /^(?=.*[A-Za-z])(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]).*$/.test(value);
        },
        "Mật khẩu phải chứa ít nhất 1 chữ cái và 1 ký tự đặc biệt"
    );
    $("#form_register").validate({
        rules: {
            username: {
                required: true,
                minlength: 2,
            },
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
                minlength: 6,
                customPassword: true,
            },
            repassword: {
                required: true,
                equalTo: "#password",
            },
        },
        messages: {
            username: {
                required: 'Bạn chưa nhập tên đăng nhập',
                minlength: 'Tên đăng nhập phải có ít nhất 2 ký tự'
            },
            email: 'Địa chỉ Email không hợp lệ',
            password: {
                required: 'Bạn chưa nhập mật khẩu',
                minlength: 'Mật khẩu phải có ít nhất 6 ký tự'
            },
            repassword: {
                required: 'Bạn chưa nhập mật khẩu',
                minlength: 'Mật khẩu phải có ít nhất 6 ký tự',
                equalTo: 'Mật khẩu không trùng khớp với mật khẩu đã nhập'
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
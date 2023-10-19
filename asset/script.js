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
});


$(function () {

    var payment_counter = 0;

    $('.kv-row-checkbox').on('change', function (e) {
        if ($(e.target).is(':checked')) {
            payment_counter++;
        } else {
            payment_counter--;
        }
        // Counter
        $('.js-payment-count').text(payment_counter);

        $('.js-payment-validate').toggleClass('hide', payment_counter <= 0);
        $('.js-payment-warning').toggleClass('hide', payment_counter > 0);
    });

    $('.select-on-check-all').on('change', function (e) {
        if ($(e.target).is(':checked')) {
            payment_counter = 20;
            $('.js-payment-count').text('todos los ');
        } else {
            payment_counter = 0;
            $('.js-payment-count').text(payment_counter);
        }
    })
});
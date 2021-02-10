$(function () {

    $('.time-block').on('change', function (e) {
        var $input = $(e.target)
            , $td = $(e.target).closest('td');

        $td.toggleClass('danger', $input.is(':checked'));
        $td.find('input[type="time"]').prop('disabled', $input.is(':checked'));
    });
});
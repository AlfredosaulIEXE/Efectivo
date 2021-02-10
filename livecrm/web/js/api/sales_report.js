/* jshint ignore:start */
$(function () {
    $('#datepicker .input-daterange').datepicker({
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true,
        language: 'es',
        format: "dd/mm/yyyy"
    });

    $('select[name="office_id"],select[name="agent_id"],select[name="mean_id"],select[name="status_appointment"],select[name="lead_status"],select[name="payment_type"],select[name="master_status_id"] , select[name = "payment_state"] , select[name = "migratelead_form"] , select[name = "type"],  select[name= "unitGenerate"], select[name="customerowner"], select[name="statuscustomer"]' ).on('change', function (e) {
        if ($(e.target).attr('name') === 'office_id') {
            $('select[name="agent_id"]').val('')
        }


        submitReportFilterForm();
    });

    $('.js-switch').on('click', function (e) {
        var $el = $(e.target);
        var type = $el.data('type');
        var $off = $('input[name="office_id"]');

        if (type === 'agent') {
            $off.val('')
        } else {
            $off.val('true');
        }

        submitReportFilterForm();
    });

    $('input[type=radio][name=type_appointment]').change(function (e)
    {
        submitReportFilterForm();
    });


    $('#filter-tabs').find('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $('select[name="agent_id"]').val('');
        $('select[name="mean_id"]').val('');
    });

    $('select[name="agent_id"]').select2();
    $('select[name="mean_id"]').select2();
});

function submitReportFilterForm() {
    $('#report_form').submit();
}

$('#data_appointments').on('change',function (e) {
    location.reload();

});
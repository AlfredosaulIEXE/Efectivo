/* jshint ignore:start */
// Code here will be linted with ignored by JSHint.
$(function () {

    // Plugin configuration
    toastr.options = {
        closeButton: true,
        progressBar: true,
        showMethod: 'slideDown',
        timeOut: 4000
    };

    // Currency mask
    $('.currency').inputmask("numeric", {
        radixPoint: ".",
        groupSeparator: ",",
        digits: 2,
        autoGroup: true,
        prefix: '$ ', //Space after $, this will not truncate the first character.
        rightAlign: false,
        oncleared: function (e) { $(e.target).val(''); }
    });

    // Slider
    $("#lead-loan_interest").ionRangeSlider({
        min:1,
        max: 16,
        step: 0.1,
        type: 'single',
        postfix: "%",
        prettify: false,
        hasGrid: true,
        onChange: calculateLoanCommission
    });

    // Slider
    $('#lead-loan_amount').on('keyup', calculateLoanCommission);
    $('#lead-loan_commission').on('keyup', calculateLoanInterest);
    $('#lead-loan_commission').on('change', function () {
        calculateLoanInterest(true)
    });






    //Modal Wizard
        $("#myBtnwizard").click(function(){
            $("#modal_wizard").modal();
                // Slider
                $("#lead-loan_interest1").ionRangeSlider({
                    min:1,
                    max: 16,
                    step: 0.1,
                    type: 'single',
                    postfix: "%",
                    prettify: false,
                    hasGrid: true,
                    onChange: calculateLoanCommission1
                });

                // Slider
                $('#lead-loan_amount').on('keyup', calculateLoanCommission1);
                $('#lead-loan_commission1').on('keyup', calculateLoanInterest1);
                $('#lead-loan_commission1').on('change', function () {
                    calculateLoanInterest1(true)
                });


        });

    // Calculate age
    $('#lead-birthdate').on('change', function () {
        var $field = $(this);
        var birthdate = $field.val();

        $field.closest('.form-group').removeClass('has-error').find('.help-block').remove();

        if (isValidDate(birthdate)) {
            birthdate = strToDate(birthdate);

            $('#lead-age').val(calculateAge(birthdate));
        } else {
            // Show error
            $field.closest('.form-group').addClass('has-error');
            $field.closest('.form-group').append('<div class="help-block">Ingresa una fecha válida.</div>');
        }
    });

    // Form Submit: Contact / Address
    $('#form-lead-contact').on('submit', updateData);
    $('#form-lead-economic').on('submit', updateData);
    $('#form-lead-spouse').on('submit', updateData);
    $('#form-lead-general').on('submit', updateData);
    $('.js-form-reference').on('submit', updateData);

    $('.js-print-btn').on('click', function (e) {
        e.preventDefault();

        var $el = $(e.currentTarget);

        var mw = window.open('/livecrm/web/index.php?r=sales/lead/print&id=' + $el.data('id') + '&type=' + $el.data('type'), 'print', 'width=780, height=510');

        // Imprimir
        mw.focus();
        mw.print();
    });

    $('.js-lead-owner').select2();

    // Migration
    /*$('.js-lead-migrate').on('click', function (e) {
        e.preventDefault();

        if (confirm('Estás a punto de migrar este lead del departamento de Ventas al departamento de Atención a Clientes. ¿Deseas continuar?')) {
            $.ajax({
                url: '/livecrm/web/index.php?r=sales/lead/migrate&id=' + $(e.target).data('id'),
                success: function (res) {
                    if (res === 'success') {
                        toastr.success( 'El lead ha sido migrado correctamente.' );

                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    } else if (res === 'error') {
                        toastr.error( 'No tienes permisos para migrar este lead.' );
                    } else {
                        toastr.error( 'Algo salió mal, inténtalo de nuevo.' );
                    }
                }
            })
        }
    });*/
});
/**
 * Update form with Ajax
 * @param e
 */
function updateData(e) {
    e.preventDefault();

    if ( ! confirm('Estás a punto de actualizar la información. ¿Deseas continuar?'))
        return false;

    var $form = $(this)
        , action = $form.attr('id').replace('form-lead-', '')
        , ibox = $form.closest('.ibox').attr('id');

    // Start loading
    loading(ibox);

    $.ajax({
        url: '/livecrm/web/index.php?r=sales/lead/' + action  + '&type=update',
        type: 'POST',
        data: $form.serialize(),
        success: successUpdated,
        error: errorUpdated
    })
}

/**
 * Show success message
 * @param msg
 */
function successUpdated(res) {
    // stop loading
    loading();

    //
    if (res === 'reload') {
        toastr.success( 'Los cambios fueron actualizados.' );
        window.location.reload();
    } else if (res === 'forbidden') {
        toastr.error( 'No tienes permisos para actualizar este lead.' );
    } else {
        toastr.success( 'Los cambios fueron actualizados.' );
    }
}

function errorUpdated() {
    toastr.error('Error al guardar los datos, inténtalo de nuevo.');
    // Stop loading
    loading();
}

/**
 * Show loading indicator
 * @type {null}
 */
var lastLoadingId = null;
function loading(id) {
    var elId = id || lastLoadingId;
    lastLoadingId = elId;
    $('#' + elId).find('.ibox-content').toggleClass('sk-loading');
}

function calculateLoanCommission() {
    var amount = parseCurrency($('#lead-loan_amount').val());
    var interest = parseFloat($('#lead-loan_interest').val());
    var commission = (amount * interest) / 100;
    $('#lead-loan_commission').val(commission);
}
function calculateLoanCommission1() {
    var amount = parseCurrency($('#lead-loan_amount').val());
    var interest = parseFloat($('#lead-loan_interest1').val());
    var commission = (amount * interest) / 100;
    $('#lead-loan_commission1').val(commission);
}

function calculateLoanInterest(changed) {
    var amount = parseCurrency($('#lead-loan_amount').val());
    var commission = parseCurrency($('#lead-loan_commission').val());
    var interest = (commission / amount) * 100;
    var slider = $('#lead-loan_interest').data('ionRangeSlider');

    if (changed === true && interest < 1) {
        alert('ATENCIÓN: La comisión no puede ser menor a 1%');
        calculateLoanCommission();
    }

    slider.update({
        from: interest
    });
}
function calculateLoanInterest1(changed) {
    var amount = parseCurrency($('#lead-loan_amount1').val());
    var commission = parseCurrency($('#lead-loan_commission1').val());
    var interest = (commission / amount) * 100;
    var slider = $('#lead-loan_interest1').data('ionRangeSlider');

    if (changed === true && interest < 1) {
        alert('ATENCIÓN: La comisión no puede ser menor a 1%');
        calculateLoanCommission1();
    }

    slider.update({
        from: interest
    });
}

function parseCurrency(amount) {
    amount = amount.replace(/,/g, '');
    amount = amount.replace(/\$/g, '');
    amount = amount.replace(/ /g, '');
    return parseFloat(amount);
}

function strToDate(str1){
// str1 format should be dd/mm/yyyy. Separator can be anything e.g. / or -. It wont effect
    var dt1   = parseInt(str1.substring(0,2));
    var mon1  = parseInt(str1.substring(3,5));
    var yr1   = parseInt(str1.substring(6,10));
    var date1 = new Date(yr1, mon1-1, dt1);
    return date1;
}

// Validates that the input string is a valid date formatted as "mm/dd/yyyy"
function isValidDate(dateString)
{
    // First check for the pattern
    if(!/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(dateString))
        return false;

    // Parse the date parts to integers
    var parts = dateString.split("/");
    var day = parseInt(parts[0], 10);
    var month = parseInt(parts[1], 10);
    var year = parseInt(parts[2], 10);

    // Check the ranges of month and year
    if(year < 1000 || year > 3000 || month == 0 || month > 12)
        return false;

    var monthLength = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];

    // Adjust for leap years
    if(year % 400 == 0 || (year % 100 != 0 && year % 4 == 0))
        monthLength[1] = 29;

    // Check the range of the day
    return day > 0 && day <= monthLength[month - 1];
}

function calculateAge(birthday) { // birthday is a date
    var ageDifMs = Date.now() - birthday.getTime();
    var ageDate = new Date(ageDifMs); // miliseconds from epoch
    return Math.abs(ageDate.getUTCFullYear() - 1970);
}

/* jshint ignore:end */

// on change payment method create new field to pay into bank
$('input[name="payment_method_bank"]').on('change', function () {
    $(this).siblings('input[type="checkbox"]').not(this).prop('checked', false);
    if ($('input[name="payment_method_bank"]:checked').length > 0)
    {
        if (!$('#bank_field').length)
        {
            let content =
                '<div id="bank_field" class="form-group row">\n' +
                '<label for="amount_paid_bank" class="col-sm-4 col-form-label">المبلغ المدفوع فى البنك</label>\n' +
                '<div class="col-sm-2">\n' +
                '<input type="text" name="amount_paid_bank"  class="form-control amount_paid_bank" id="amount_paid_bank" autocomplete="off">\n' +
                '</div>\n'+
                '<span id="amount_paid_bank_error" style="display: none; color: red"></span>\n' +
                '</div>';
            $('#amounts_group_field').append(content);
        }

    }else{
        if ($('#bank_field').length)
        {
            $('#bank_field').remove();
        }
    }
})

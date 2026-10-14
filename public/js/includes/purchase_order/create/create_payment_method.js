$('input[name="payment_method"]').on('change', function () {
    if ($(this).is(':checked'))
    {
        if (!$('#safe_amount_field').length)
        {
            let content =
                '<div id="safe_amount_field" class="form-group row">\n' +
                '<label for="amount_paid" class="col-sm-4 col-form-label">المبلغ المدفوع فى\n' +
                'الخزنة</label>\n' +
                '<div class="col-sm-2">\n' +
                '<input type="text" name="amount_paid" class="form-control amount_paid"\n' +
                'id="amount_paid" autocomplete="off">\n' +
                '</div>\n' +
                '<span id="amount_paid_error" style="display: none; color: red"></span>\n' +
                '</div>';
            $('#amounts_group_field').prepend(content);
        }

    }else{
        if ($('#safe_amount_field').length)
        {
            $('#safe_amount_field').remove();
        }
    }
})

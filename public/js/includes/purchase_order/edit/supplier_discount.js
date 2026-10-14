// create supplier discount
$('#supplier_discount_button').on('change', function () {
    $('.supplier_discount_container').empty();
    if ($(this).is(':checked'))
    {
        let html = '<label for="supplier_discount_amount">مبلغ الخصم</label>\n' +
            '    <div class="input-group my-group supplier_discount_amount">\n' +
            '        <select id="supplier_discount_type" class="form-control supplier_discount_type" name="supplier_discount_type">\n' +
            '            <option value="0">دولار</option>\n' +
            '            <option value="1">%</option>\n' +
            '        </select>\n' +
            '       <input id="supplier_discount" name="supplier_discount" type="text" class="form-control" autocomplete="off">\n' +
            '       <input id="supplier_discount_amount" name="supplier_discount_amount" type="hidden">\n' +
            '    </div>\n' ;
        $('.supplier_discount_container').append(html);
    }
    else
    {
        $('.supplier_discount_amount').remove();
    }
});


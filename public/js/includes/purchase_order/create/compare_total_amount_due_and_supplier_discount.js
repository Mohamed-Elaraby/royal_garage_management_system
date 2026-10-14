$(document).on('keyup change', '#supplier_discount, #supplier_discount_type', function () {

    check_supplier_discount();

});

function check_supplier_discount()
{

    let supplier_discount_amount = calc_supplier_discount_amount();

    let total_amount_due_element_value = parseFloat($('#total_amount_due').val() || 0);

    let min_number = 1;

    let error_message = get_discount_amount_or_percentage('يجب ان تكون قيمة الخصم بين 1 : 100 % من اجمالى قيمة الفاتورة ', ' يجب ان تكون قيمة الخصم بين  ' + min_number + ' : ' + total_amount_due_element_value + ' دولار ')

    if (number_between_range(supplier_discount_amount, min_number, total_amount_due_element_value) || $('#supplier_discount').val() == '')
    {

        $('#supplier_discount_error').removeClass('hasError').css('display', 'none').text();

    } else
        {

            $('#supplier_discount_error').addClass('hasError').css({
                'display': 'inline',
                'font-size': 'x-small',
                'font-style': 'italic',
                'margin-bottom': '5px',
                'font-weight': '700'
            }).text(error_message);

        }
}

function calc_supplier_discount_amount()
{

    let _total_amount_due_element = $('#total_amount_due');

    let supplier_discount_amount = parseFloat($('#supplier_discount').val()); // if discount value not empty get value else get zero

    let total_amount_due = _total_amount_due_element.val() || 0;

    let discount_amount_or_percentage = get_discount_amount_or_percentage((total_amount_due * supplier_discount_amount / 100), supplier_discount_amount);

    return parseFloat(discount_amount_or_percentage || 0);
}

function get_discount_amount_or_percentage(value_is_percentage_do_something_here, value_is_amount_do_something_here)
{

    let supplier_discount_type = $('#supplier_discount_type').val();

    return supplier_discount_type == 1 ? value_is_percentage_do_something_here : value_is_amount_do_something_here;
}

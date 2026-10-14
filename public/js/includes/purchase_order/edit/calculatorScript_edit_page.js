// toSafeNumber:begin
function toSafeNumber(value) {
    // Normalize Arabic digits and separators; return 0 for anything non-numeric (never NaN)
    var s = String(value === undefined || value === null ? '' : value).trim();
    s = s.replace(/[\u0660-\u0669]/g, function (d) { return String(d.charCodeAt(0) - 1632); })
         .replace(/[\u06F0-\u06F9]/g, function (d) { return String(d.charCodeAt(0) - 1776); })
         .replace(/\u066B/g, '.')
         .replace(/[\u066C\u060C\s]/g, ',');
    if (/^-?\d{1,3}(,\d{3})+(\.\d+)?$/.test(s)) { s = s.replace(/,/g, ''); } else { s = s.replace(/,/g, '.'); }
    var n = parseFloat(s);
    return isFinite(n) ? n : 0;
}
// toSafeNumber:end

$(document).ready(function () {

    let count = 1,
        price,
        quantity,
        calc_amount_taxable,
        discount_per_item,
        calc_tax_amount,
        calc_subTotal,
        discount_row_removed,
        sub_total_row_removed,
        amount_taxable_row_removed,
        amount_paid,
        amount_paid_bank,
        tax_row_removed,
        calcTotal,
        calcSubTotal,
        discount_amount_or_percentage,
        calc_tax,
        calc_taxable_amount,
        calc_discount,
        discount_type,
        _total_amount_due_hidden_input_without_round_element = $('#total_amount_due_hidden_input_without_round'),
        _total_element = $('#total'),
        _discount_element = $('#discount'),
        _discount_type_element = $('.discount_type'),
        _taxable_amount_element = $('#taxable_amount'),
        _total_amount_due_element = $('#total_amount_due'),
        // _amount_paid_element = $('#amount_paid'),
        _amount_due_element = $('#amount_due'),
        _total_vat_element = $('#total_vat'),
        _card_details_tax_element = $('#card_details_tax');


    // make button to remove select elements
    $(document).on('click', '.removeField', function () {
        let selectedRow = $(this).closest('tr');
        let productCode = selectedRow.find('.item_code').val();

        $('#product_code_'+ productCode).addClass('btn-success').removeClass('btn-default disabled');
        amount_taxable_row_removed = $(this).closest('tr').find('.item_amount_taxable').val();
        discount_row_removed = $(this).closest('tr').find('.item_discount_amount').val();
        tax_row_removed = $(this).closest('tr').find('.item_tax_amount').val();
        sub_total_row_removed = $(this).closest('tr').find('.item_sub_total').val();
        calc_items_total_amounts(amount_taxable_row_removed, discount_row_removed, tax_row_removed, sub_total_row_removed);
        putHandLabourAndPartsAmount();
        $(this).closest('tr').remove();
    });

    // calculating invoice data
    function calc_items_total_amounts(taxable_amount_removed = 0, discount_removed = 0, tax_removed = 0, subtotal_removed = 0) {
        amount_paid_bank = parseFloat($('#amount_paid_bank').val()) || 0 ; // init amount paid
        amount_paid = parseFloat($('#amount_paid').val()) || 0 ; // init amount paid
        let total_amounts = toTwoDecimalPlaces((amount_paid + amount_paid_bank)) ;

        // console.log(amount_paid_bank);
        // console.log(amount_paid);
        // calc amount_taxable value and insert value into total element
        let amount_taxable_value = 0;
        $('input.item_amount_taxable').each(function () {
            amount_taxable_value += toSafeNumber($(this).val());
            calcTotal = (amount_taxable_value);
            _total_element.val((calcTotal - taxable_amount_removed).toFixed(2)); // insert total amount inside total input
        });

        // calc discount value and insert value into discount element
        let discount_value = 0;
        $('input.item_discount_amount').each(function () {
            discount_value += +$(this).val() || 0;
            calc_discount = (discount_value);
            _discount_element.val((calc_discount - discount_removed).toFixed(2)); // insert discount amount inside discount input
        });

        // calc item tax amount value and insert value into total vat element
        let tax_value = 0;
        $('input.item_tax_amount').each(function () {
            tax_value += +$(this).val() || 0;
            calc_tax = (tax_value);
            _total_vat_element.val((calc_tax - tax_removed).toFixed(2)); // insert tax_amount amount inside tax_amount input
            _card_details_tax_element.val((calc_tax - tax_removed).toFixed(2)); // insert total amount inside total input
        });

            // calc item sub total value and insert value into total amount due element and amount due element
        let subtotal_value = 0;
        $('input.item_sub_total').each(function () {
            subtotal_value += +$(this).val() || 0;
            calcSubTotal = (subtotal_value);
            _total_amount_due_hidden_input_without_round_element.val(toTwoDecimalPlaces(calcSubTotal - subtotal_removed));

            _total_amount_due_element.val(toTwoDecimalPlaces(calcSubTotal - subtotal_removed));
            _amount_due_element.val(toTwoDecimalPlaces(_total_amount_due_element.val() - total_amounts - calc_supplier_discount_amount()));

            if ($('#rounding_amount').hasClass('btn-danger'))
            {
                _total_amount_due_element.val(Math.round(calcSubTotal - subtotal_removed).toFixed(2));
                _amount_due_element.val(toTwoDecimalPlaces(_total_amount_due_element.val() - total_amounts - calc_supplier_discount_amount()));
            }

        });

        // calc item taxable amount value and insert value into taxable amount element
        calc_taxable_amount = _total_element.val() && _discount_element.val()? parseFloat(_total_element.val() - _discount_element.val() || 0).toFixed(2) : '';
        _taxable_amount_element.val(calc_taxable_amount);

    }

    $('#rounding_amount').on('click', function () {
        rounding_amount();
    })

    // Rounding [ total_amount_due ] and [ amount_due ]
    function rounding_amount (){
        amount_paid = parseFloat($('#amount_paid').val()) || 0 ; // init amount paid
        amount_paid_bank = parseFloat($('#amount_paid_bank').val()) || 0 ; // init amount paid
        let total_amounts = toTwoDecimalPlaces((amount_paid + amount_paid_bank)) ;
        let that = $('#rounding_amount');
        if (that.hasClass('btn-danger'))
        {
            // Remove Rounding
            _total_amount_due_element.val(_total_amount_due_hidden_input_without_round_element.val());
            _amount_due_element.val(toTwoDecimalPlaces(_total_amount_due_element.val() - total_amounts - calc_supplier_discount_amount()));
            that.addClass('btn-success').removeClass('btn-danger').text('تقريب المبلغ');
            console.log('Remove Round Button Mode');
            console.log('_total_amount_due_hidden_input_without_round_element : '+_total_amount_due_hidden_input_without_round_element.val());
        }
        else
        {
            // Make Rounding
            _total_amount_due_element.val(Math.round( _total_amount_due_element.val()).toFixed(2));
            _amount_due_element.val(toTwoDecimalPlaces(_total_amount_due_element.val() - total_amounts - calc_supplier_discount_amount()));
            that.removeClass('btn-success').addClass('btn-danger').text('الغاء التقريب');
            console.log('Make Round Button Mode');
        }
    }


    function round_amount(amount) {
        return Math.round(amount);
    }

    function makeToFixedNumber(num) {
        // return Math.trunc(num*100)/100;
        return Math.round(num * 100) / 100;
    }

    function toTwoDecimalPlaces(str) {
        // Convert the string to a number
        const num = Number(str);

        // Check if the number is finite (not NaN, Infinity, or -Infinity)
        if (isFinite(num)) {
            // Use `toFixed()` to get the string representation of the number with two decimal places
            const fixedString = num.toFixed(2);

            // Convert the fixed string back to a number and return it
            return Number(fixedString);
        } else {
            // If the input string is not a valid number, return 0
            return 0;
        }
    }



    // calculating sub total amount per item
    $(document).on('keyup change', ':input', function () {

        let row = $(this).closest('tr');

        quantity = toSafeNumber(row.find('.item_quantity').val()); // get quantity
        price = toSafeNumber(row.find('.item_price').val()); // get price

        calc_amount_taxable = parseFloat(((price * quantity).toFixed(2))); // calculation amount taxable for item
        row.find('.item_amount_taxable').val(calc_amount_taxable); // insert amount taxable items into amount taxable field

        discount_type = row.find('.discount_type').val();

        discount_per_item = toSafeNumber(row.find('.item_discount').val()); // if discount value not empty get value else get zero

        discount_amount_or_percentage = discount_type == 1 ? ( calc_amount_taxable * discount_per_item /100):discount_per_item;

        row.find('.item_discount_amount').val(discount_amount_or_percentage);
        row.find('.item_sub_total_after_discount').val(calc_amount_taxable - discount_amount_or_percentage);

        // console.log(discount_amount_or_percentage);

        calc_tax_amount = 0;
        row.find('.item_tax_amount').val(calc_tax_amount);
        calc_subTotal = (calc_amount_taxable - discount_amount_or_percentage).toFixed(2); // calculation sub total for item
        row.find('.item_sub_total').val(calc_subTotal); // insert sub total items into sub total field
        calc_items_total_amounts();
        $('#supplier_discount_amount').val(calc_supplier_discount_amount());
        putHandLabourAndPartsAmount();
    });


    function calc_supplier_discount_amount() {

        supplier_discount_type = parseInt($('#supplier_discount_type').val());

        supplier_discount = parseFloat( $('#supplier_discount').val() || 0); // if discount value not empty get value else get zero

        let total_amount_due = _total_amount_due_element.val() || 0;

        discount_amount_or_percentage = supplier_discount_type === 1 ? ( total_amount_due * supplier_discount /100):supplier_discount;

        return discount_amount_or_percentage  || 0;
    }

    putHandLabourAndPartsAmount();

    function putHandLabourAndPartsAmount() {

        let amount_paid = parseFloat($('#amount_paid').val())|| 0;
        let amount_paid_bank = parseFloat($('#amount_paid_bank').val())|| 0;

        let total_amounts_paid  = parseFloat(amount_paid + amount_paid_bank);
        // console.log('total_amounts_paid = ' + total_amounts_paid, typeof total_amounts_paid);

        let hand_labour = parseFloat($('#hand_labour').val())|| 0;
        let new_parts = parseFloat($('#new_parts').val())|| 0;
        let used_parts = parseFloat($('#used_parts').val())|| 0;


        let calculate_tax_amount = 0;
        let card_details_tax = $('#card_details_tax');
        let total_card_details_amount = makeToFixedNumber(hand_labour + new_parts + used_parts + calculate_tax_amount);

        card_details_tax.val(calculate_tax_amount);
        // console.log('total_card_details_amount = ' + total_card_details_amount, typeof total_card_details_amount);
        // console.log('calculate_tax_amount = ' + calculate_tax_amount, typeof calculate_tax_amount);




        // let total_vat = parseFloat(_total_vat_element.val())|| 0;
        // let total_amount_due = parseFloat($('#total_amount_due').val());
        // let total_card_details_amount = hand_labour + new_parts + used_parts + total_vat;

        if (total_amounts_paid > 0)
        {
            if (total_card_details_amount < 1 || total_card_details_amount > total_amounts_paid)
            {
                $('#card_details_error').addClass('hasError').css({'display': 'inline', 'font-size': 'small', 'font-style': 'italic', 'margin-bottom': '5px', 'font-weight': '700'}).text('برجاء ادخال اجمالى مبالغ تفاصيل الكارت بشكل صحيح بحيث يكون الاجمالى = ' + total_amounts_paid);
            }
            else if (total_card_details_amount !== total_amounts_paid)
            {
                let calc = parseFloat(total_amounts_paid - total_card_details_amount).toFixed(2);
                $('#card_details_error').addClass('hasError').css({'display': 'inline', 'font-size': 'small', 'font-style': 'italic', 'margin-bottom': '5px', 'font-weight': '700'}).text('اجمالى المبلغ الذى ادخلته ' + total_card_details_amount + ' مع الضريبة لا يساوى اجمالى المبلغ المدفوع المقدر ب ' + total_amounts_paid + ' متبقى ' + calc);
            }
            else
            {
                $('#card_details_error').removeClass('hasError').css('display','none').text();
            }
        }else
        {
            $('#hand_labour, #new_parts, #used_parts').val('');
            $('#card_details_error').removeClass('hasError').css('display','none').text();
        }
    }

    putHandLabourAndPartsAmount();
});

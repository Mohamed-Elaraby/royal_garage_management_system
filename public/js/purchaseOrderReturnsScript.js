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



    let quantity,
        price,
        calc_amount_taxable,
        discount_type,
        discount_per_item,
        discount_amount_or_percentage,
        calc_tax_amount,
        calc_subTotal,
        _total_element = $('#total'),
        _discount_element = $('#discount'),
        _taxable_amount_element = $('#taxable_amount'),
        _total_vat_element = $('#total_vat'),
        _total_return_items_element = $('#total_return_items');
    // var total_return_items_without_round ;

    function calcTotalAmounts() {
        let total                           = 0;
        let discount                        = 0;
        let taxable_amount                  = 0;
        let total_vat                       = 0;
        let total_return_items_with_round   = 0;

        // let total_return_items = 0;
        $('input[type="checkbox"]:checked').each(function () {
            let that = $(this);
            let rowSelected = that.closest('tr');
            total += toSafeNumber(rowSelected.find('.item_amount_taxable').val());
            discount += parseFloat(rowSelected.find('.item_discount_amount').val());
            taxable_amount += toSafeNumber(rowSelected.find('.item_sub_total_after_discount').val());
            total_vat += toSafeNumber(rowSelected.find('.item_tax_amount').val());
            total_return_items_with_round += parseFloat(rowSelected.find('.item_sub_total ').val());
            // total_return_items_without_round += parseFloat(rowSelected.find('.item_sub_total ').val());
            // total_return_items += parseFloat(rowSelected.find('.item_sub_total ').val());
        });
        console.log('total_return_items_with_round = '+ total_return_items_with_round)
        _total_element.val((total).toFixed(2)); // insert total amount inside total input
        _discount_element.val((discount).toFixed(2)); // insert total amount inside total input
        _taxable_amount_element.val((taxable_amount).toFixed(2)); // insert total amount inside total input
        _total_vat_element.val((total_vat).toFixed(2)); // insert total amount inside total input
        _total_return_items_element.val(Math.round(total_return_items_with_round).toFixed(2)); // insert total amount inside total input
        get_safeMoney_and_bank();

        // _card_details_tax_element.val(get_safeMoney_and_bank().vat_of_total_amount);
    }

    function get_safeMoney_and_bank(){
        let amount_paid                 = 0;
        let amount_paid_bank            = 0;

        $('.return_amount_in').each(function () {
            let that = $(this);
            let rowSelected = that.closest('tr');

            if (that.val() == 'money_safe')
            {
                amount_paid += Math.round(rowSelected.find('.item_sub_total').val());
            }
            else if(that.val() == 'bank')
            {
                amount_paid_bank += Math.round(rowSelected.find('.item_sub_total').val());

            }

        });
        $('#amount_paid').val(amount_paid);
        $('#amount_paid_bank').val(amount_paid_bank);
    }

    // calculating sub total amount per item
    $(document).on('keyup change', '.item_quantity, input, select', function () {

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
        calcTotalAmounts();
    });


    // function get_safeMoney_and_bank(){
    //     let amount_paid                 = 0;
    //     let amount_paid_bank            = 0;
    //     let vat_amount_paid             = 0;
    //     let vat_amount_paid_bank        = 0;
    //     $('.return_amount_in').each(function () {
    //         let that = $(this);
    //         let rowSelected = that.closest('tr');
    //
    //         if (that.val() == 'money_safe')
    //         {
    //             amount_paid += Math.round(rowSelected.find('.item_sub_total').val());
    //             vat_amount_paid += Math.round(rowSelected.find('.item_tax_amount').val());
    //
    //
    //         }
    //         else if(that.val() == 'bank')
    //         {
    //             amount_paid_bank += Math.round(rowSelected.find('.item_sub_total').val());
    //             vat_amount_paid_bank += Math.round(rowSelected.find('.item_tax_amount').val());
    //
    //
    //         }
    //
    //     });
    //     $('#amount_paid').val(amount_paid);
    //     $('#amount_paid_bank').val(amount_paid_bank);
    //     let amounts_and_vat_of_returns_items = {
    //         'total_amount': toTwoDecimalPlaces(amount_paid + amount_paid_bank),
    //         'vat_of_total_amount': toTwoDecimalPlaces(vat_amount_paid + vat_amount_paid_bank),
    //
    //     };
    //
    //     return amounts_and_vat_of_returns_items;
    // }

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

});

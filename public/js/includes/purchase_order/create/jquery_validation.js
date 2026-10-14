// JQuery Validation
$('form').on('submit', function (e) {
    $('select.branch_id').each(function () {$(this).rules('add', {required: true});});
    $('input.invoice_number').each(function () {$(this).rules('add', {required: true, digits:true});});
    $('input.invoice_date').each(function () {$(this).rules('add', {required: true});});
    $('select.category').each(function () {$(this).rules('add', {required: true});});
    $('input.item_quantity').each(function () {$(this).rules('add', {required: true, digits:true, range:[1,100000]});});
    $('input.item_price').each(function () {$(this).rules('add', {required: true, range:[0,1000000]});});
    $('input#item_discount').each(function () {$(this).rules('add', {digits:true, range:[1,100000]});});
    $('input#item_amount_paid').each(function () {$(this).rules('add', {required: true, digits:true});});

    e.preventDefault();
});
// JQuery Validation
$("#purchaseOrders").validate({
    submitHandler: function(form) {
        form.submit();
    }
});

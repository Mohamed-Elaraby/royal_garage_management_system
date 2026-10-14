// prevent double submission of forms
$('form').on('submit', function (e) {
    if ($('.hasError').length > 0 || $('.error').length > 0)
    {
        e.preventDefault();
    }else
    {
        $('button[type=submit], input[type=submit]').prop('disabled',true).prop('value', 'برجاء الانتظار ....');
        return true;
    }

})

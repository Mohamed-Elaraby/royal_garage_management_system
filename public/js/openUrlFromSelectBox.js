$(document).on('change', '#selectAction', function () {
    let url = $(this).children('option:selected').attr('value');
    window.open(url, '_blank');
}) // end on change

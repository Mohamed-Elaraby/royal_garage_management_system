$(document).on('submit', '#create_new_element_form', function(e) {
    e.preventDefault(); // avoid to execute the actual submit of the form.
    let form = $(this);
    let method = form.attr('method');
    let actionUrl = form.attr('action');
    $.ajax({
        method: method,
        url: actionUrl,
        data: form.serialize(), // serializes the form's elements.
        success: function(data)
        {
            form.trigger("reset");
            load_elements();
        }
    }); // end of ajax
}); // end of submit function


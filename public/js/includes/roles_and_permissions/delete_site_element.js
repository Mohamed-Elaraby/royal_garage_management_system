$(document).on('click', '#delete_site_element', function(e) {
    let that = $(this);
    let role_id = that.data('role-id');
    let url = route('admin.roleElement.delete_element');
    $.ajax({
        url: url,
        data: {role_id: role_id},
        success: function () {
            load_elements();
        }
    });// }); // end of ajax
}); // end of submit function


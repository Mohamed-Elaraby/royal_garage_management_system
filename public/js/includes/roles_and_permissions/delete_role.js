$(document).on('click', '#delete_role', function(e) {
    let that = $(this);
    let role_id = that.data('role-id');
    let url = route('admin.rolesManager.delete_role');
    $.ajax({
        url: url,
        data: {role_id: role_id},
        success: function () {
            load_roles_list();
            // location.reload();
        }
    });// }); // end of ajax
}); // end of submit function


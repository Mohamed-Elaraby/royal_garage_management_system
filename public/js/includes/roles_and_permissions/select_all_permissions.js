$("#selectAll").click(function(){
    // on change select all checkbox to checked change all checkbox in page to checked and remove checked remove on all checkbox in page
    $(".role_permissions_body input[type=checkbox]").prop('checked', $(this).prop('checked'));
    let that = $(this);
    let role_id = that.data('role-id');
    let url = route('admin.rolesManager.sync_select_all_permissions');
    let input_status = that.is(':checked');
    let permissions_selected_list = $('.permission:checkbox'); // get all checkbox for class permission
    let permissions_name_list = []; // get all checkbox checked name property list
    $.each(permissions_selected_list, function (key, value) {
        permissions_name_list.push(value.name);
    });
    $.ajax({
        url: url,
        data: {permissions_name_list: permissions_name_list, role_id: role_id, checked: input_status}
    }); // end of ajax
});


// $("#selectAll").click(function(){
//     $(".role_permissions_body input[type=checkbox]").prop('checked', $(this).prop('checked'));
//     let that = $(this);
//     let role_id = that.data('role-id');
//     let url = route('admin.rolesManager.sync_select_all_permissions');
//     let permissions_selected_list = $('.permission_selected_list:checkbox:checked');
//     let permissions_name_list = [];
//     $.each(permissions_selected_list, function (key, value) {
//         permissions_name_list.push(value.name);
//     });
//     let checked_value = that.is(':checked') ? true : false;
//     $.ajax({
//         url: url,
//         data: {permissions_name_list: permissions_name_list, role_id: role_id, checked: checked_value}
//     }); // end of ajax
// });

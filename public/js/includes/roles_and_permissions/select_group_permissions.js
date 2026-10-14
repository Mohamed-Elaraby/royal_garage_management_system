$(document).on('change', '.select_group', function () {
    let that = $(this);
    let group_name = that.attr('name');
    let role_id = that.data('role-id');
    let url = route('admin.rolesManager.sync_group_permissions');
    let permissions_selected_list = $('input[data-group-name="'+group_name+'"]');
    let input_status = that.is(':checked');
    let permissions_name_list = [];
    $.each(permissions_selected_list, function (key, value) {
        permissions_name_list.push(value.name);
    });
    $('input[data-group-name="'+group_name+'"]').prop('checked', $(this).prop('checked'));
    $.ajax({
            url: url,
            data: {permissions_name_list: permissions_name_list, role_id: role_id, checked: input_status}
        }); // end of ajax
    change_checkAll_checkbox_status();
})

// $(document).on('change', '.select_group', function () {
//     let that = $(this);
//     let group_name = that.attr('name');
//     let role_id = that.data('role-id');
//     let url = route('admin.rolesManager.sync_group_permissions');
//     let permissions_selected_list = $('input[data-group="'+group_name+'"]');
//     let permissions_name_list = [];
//     $.each(permissions_selected_list, function (key, value) {
//         permissions_name_list.push(value.name);
//     });
//     if (that.is(':checked'))
//     {
//         $('input[data-group="'+group_name+'"]').prop('checked', $(this).prop('checked'));
//         $.ajax({
//             url: url,
//             data: {permissions_name_list: permissions_name_list, role_id: role_id, checked: true}
//         }); // end of ajax
//     }
//     else
//     {
//         $('input[data-group="'+group_name+'"]').prop('checked', false);
//         $.ajax({
//             url: url,
//             data: {permissions_name_list: permissions_name_list, role_id: role_id, checked: false}
//         }); // end of ajax
//     }
// })

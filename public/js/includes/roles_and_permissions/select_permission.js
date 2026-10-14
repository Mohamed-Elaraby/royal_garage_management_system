$(document).on('change', '.permission', function () {
    let that = $(this);
    let role_id = that.data('role-id');
    let permission_name = that.attr('name');
    let url = route('admin.rolesManager.syncPermissions');
    let group_name = that.data('group-name');
    let input_status = that.is(':checked');
    !input_status ?  select_all_element.prop("checked", false) : ''; // if unchecked permission unchecked select all input
    $.ajax({
        url: url,
        data: {permission_name: permission_name, role_id: role_id, checked: input_status}
    }); // end of ajax

    change_group_checkbox_status(group_name);
    change_checkAll_checkbox_status();
});



// $(document).on('change', '.permission_selected_list', function () {
//     let that = $(this);
//     let role_id = that.data('role-id');
//     let permission_name = that.attr('name');
//     let group_name = that.data('group');
//     let url = route('admin.rolesManager.syncPermissions');
//     if (that.is(':checked')){
//         xxxxxxx(group_name);
//         $.ajax({
//             url: url,
//             data: {permission_name: permission_name, role_id: role_id, checked: true}
//         }); // end of ajax
//     }else
//     {
//         $("#selectAll").prop("checked", false);
//         // xxxxxxx(group_name);
//         // that.closest('ul').find('input[name="'+that.data('group')+'"]').prop('checked', false);
//         $.ajax({
//             url: url,
//             data: {permission_name: permission_name, role_id: role_id, checked: false}
//         }); // end of ajax
//     }
// });
//
// function xxxxxxx (group_name) {
//     let parent_checkbox_group = $("input[value='"+group_name+"']");
//     let elements_selected_checked = $("input[data-group='"+group_name+"']:checked").length;
//     let elements_selected = $("input[data-group='"+group_name+"']").length;
//     let all_elements_of_group_checked = elements_selected == elements_selected_checked ? true : false ;
//     parent_checkbox_group.prop('checked', all_elements_of_group_checked);
// }

// get all permissions for role
$(document).on('click', '.rolePermissions', function () {
    let group_namespace = 'G_';
    let that = $(this);
    let role_id = that.attr('id');
    let role_name = that.data('name');
    let url = route('admin.rolesManager.getRolePermissions');
    $('#permission_name_in_model_title').html(role_name);
    $('#selectAll').attr('data-role-id', role_id);
    $.ajax({
        url: url,
        data: {role_id: role_id},
        success: function (data) {
            let content = '';
            var i = 0;
            var m = 'value';
            $.each(data.elements_list, function (key, value) {

                content += '<ul>';
                content += '<li>'+value.name+'<input type="checkbox" name="'+ group_namespace + value.name +'" data-role-id="'+ role_id +'" class="select_group"></li>';
                content += "<input id='create_"+value.name+"' data-role-id='"+ role_id +"' data-group-name='"+ group_namespace +value.name+"' class='permission' type='checkbox' name='create-"+value.name+"' " + ( checked_in_array(data.role_permissions, "create-"  + value.name) ) + ">";
                content += "<label for='create_"+value.name+"'>Create</label>";
                content += "<input id='read_"+value.name+"' data-role-id='"+ role_id +"' data-group-name='"+ group_namespace +value.name+"' class='permission' type='checkbox' name='read-"+value.name+"' " + ( checked_in_array(data.role_permissions, "read-"  + value.name) ) + ">";
                content += "<label for='read_"+value.name+"'>Read</label>";
                content += "<input id='update_"+value.name+"' data-role-id='"+ role_id +"' data-group-name='"+ group_namespace +value.name+"' class='permission' type='checkbox' name='update-"+value.name+"' " + ( checked_in_array(data.role_permissions, "update-"  + value.name) ) + ">";
                content += "<label for='update_"+value.name+"'>Update</label>";
                content += "<input id='delete_"+value.name+"' data-role-id='"+ role_id +"' data-group-name='"+ group_namespace +value.name+"' class='permission' type='checkbox' name='delete-"+value.name+"' " + ( checked_in_array(data.role_permissions, "delete-"  + value.name) ) + ">";
                content += "<label for='delete_"+value.name+"'>Delete</label>";
                content += '</ul>';
            })
            $('.role_permissions_body').html(content);
            // let group_name = 'group_users';
            get_all_checkbox_has_group(data.elements_list, group_namespace);
            change_checkAll_checkbox_status();
        } // end of success
    }); // end of ajax
}); // end of function

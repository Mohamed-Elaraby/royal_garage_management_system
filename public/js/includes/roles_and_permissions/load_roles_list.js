function load_roles_list (){
    let url = route('admin.rolesManager.load_roles_list');
    $.ajax({
        url: url,
        success: function(data)
        {
            let content = '';
            $.each(data.roles_list.reverse(), function (key, value) {
                content += '<tr>';
                content += '<td>'+ (key+1) +'</td>';
                content += '<td>'+value.name+'</td>';
                content += '<td><button id="'+value.id+'" data-name="'+value.name+'" class="btn btn-primary btn-sm rolePermissions" data-toggle="modal" data-target="#myModal">Permissions</button></td>';
                // content += '<td><button disabled id="delete_role" data-role-id="'+value.id+'" class="btn btn-sm btn-danger">حذف <i class="fa fa-remove"></button></td>';
                content += '<tr>';
            });
            $('#roles_list_body').html(content);
        }
    }); // end of ajax
    // $('#roles_body')
}
load_roles_list();

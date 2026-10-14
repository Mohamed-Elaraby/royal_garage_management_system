function load_elements (){
    let url = route('admin.roleElement.load_elements');
    $.ajax({
        url: url,
        success: function(data)
        {
            let content = '';
            $.each(data.elements_list.reverse(), function (key, value) {
                content += '<tr>';
                content += '<td>'+ (key+1) +'</td>';
                content += '<td>'+value.name+'</td>';
                content += '<td><button id="delete_site_element" data-role-id="'+value.id+'" class="btn btn-sm btn-danger">حذف <i class="fa fa-remove"></button></td>';
                content += '<tr>';
            });
            $('#elements_body').html(content);
        }
    }); // end of ajax
    // $('#elements_body')
}
load_elements();

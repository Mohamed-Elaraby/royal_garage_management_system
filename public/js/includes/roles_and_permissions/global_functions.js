let select_all_element = $("#selectAll");

function change_group_checkbox_status (group_name) {
    let parent_checkbox_group = $("input[name='"+group_name+"']");
    let elements_selected_checked = $("input[data-group-name='"+group_name+"']:checked").length;
    let elements_selected = $("input[data-group-name='"+group_name+"']").length;
    let status = elements_selected == elements_selected_checked ?  true : false ;
    parent_checkbox_group.prop('checked', status);

}
function change_checkAll_checkbox_status ()
{

    let all_checkbox_elements = $('input[type="checkbox"]:not("#selectAll")').length;
    let all_checkbox_elements_checked = $('input[type="checkbox"]:not("#selectAll"):checked').length;
    let status  = all_checkbox_elements == all_checkbox_elements_checked ? true : false;
    select_all_element.prop('checked', status) ;
}

// check if value in array return checked else return empty
function checked_in_array (myArray, value)
{
    return myArray.includes(value) ? "checked" : "" ;
}

// get all checkbox has attr data-group
function get_all_checkbox_has_group(elements_list, group_namespace) {
    $.each(elements_list, function (key, value) {
        let group_name = group_namespace + value.name;
        let parent_checkbox_group = $("input[name='"+group_name+"']");
        let elements_selected_checked = $("input[data-group-name='"+group_name+"']:checked").length;
        let elements_selected = $("input[data-group-name='"+group_name+"']").length;
        let all_elements_of_group_checked = elements_selected == elements_selected_checked ? true : false ;
        parent_checkbox_group.prop('checked', all_elements_of_group_checked);
    });
}

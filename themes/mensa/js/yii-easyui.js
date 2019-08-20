/*
 * Yii EasyUI Basic Code
 *
 * @author 		Dida Nurwanda123
 * @email		dida_n@ymail.com
 * @blog		didanurwanda.blogspot.com
 * @create		April 2013
 *
 */

        /**
         * Link to form
         */
        var link_to_form;

/**
 * Open Tabs
 *
 * @url 	link to items
 * @title	title tabs
 * @icon	icon tabs
 */
function open_tabs(url, title)
{
    if (jQuery('#main-tabs').tabs('exists', title)) {
        jQuery('#main-tabs').tabs('select', title);
    } else {
        jQuery('#main-tabs').tabs('add', {
            title: title,
            href: url,
            closable: true,
        });
    }
}

/**
 * Create or Open Dialog Create
 *
 * @dialog		identity dialog
 * @form		identity form
 * @title 		title dialog
 */
function create_data(dialog, form, title, url)
{
    jQuery(dialog).dialog('open').dialog('setTitle', title).dialog('refresh');
    jQuery(form).form('clear');
    link_to_form = url;
}

/**
 * Update or Open Dialog Update
 *
 * @dialog		identity dialog
 * @form		identity form
 * @title 		title dialog
 * @grid		identity grid
 */
function update_data(dialog, form, title, grid, url)
{
    var row = jQuery(grid).datagrid('getSelected');
    if (row) {
        jQuery(dialog).dialog('open').dialog('setTitle', title).dialog('refresh');
        jQuery(form).form('load', row);
        // url = url.substring(0,url.length - 1);
        link_to_form = url + 'id/' + row.id;
    } else {
        jQuery.messager.alert({
            title: 'Warning',
            msg: 'Please select item will be edited',
            icon: 'warning',
            ok: 'OK'
        });
    }
}

/**
 * Save data
 *
 * @form		identity form
 * @dialog		identity dialog
 * @grid		identity grid
 */
function save_data(form, dialog, grid)
{
    jQuery(form).form('submit', {
        url: link_to_form,
        onSubmit: function () {
            return jQuery(this).form('validate');
        },
        success: function (result) {
            var result = eval('(' + result + ')');
            if (result.success) {
                jQuery(dialog).dialog('close');
                jQuery(grid).datagrid('reload');
                jQuery.messager.alert({
                    title: 'Success',
                    msg: result.msg,
                    icon: 'info',
                    timeout: 5000,
                    showType: 'show',
                    style: {
                        right: '',
                        bottom: ''
                    }
                });
            } else {
                jQuery.messager.alert({
                    title: 'Error',
                    msg: result.msg,
                    icon: 'error',
                    ok: 'OK'
                });
            }
        }
    });
}

/**
 * Remove data
 *
 * @grid		identity grid
 */
function remove_data(grid, url) {
    var row = jQuery(grid).datagrid('getSelected');
    if (row) {
        jQuery.messager.confirm('Confirm', 'Are you sure you want to remove this data ?', function (r) {
            if (r) {
                jQuery.post(url, {row: row}, function (result) {
                    if (result.success) {
                        jQuery(grid).datagrid('reload');
                        jQuery.messager.show({
                            title: 'Success',
                            msg: result.msg,
                            timeout: 5000,
                            icon: 'info',
                            showType: 'show',
                            style: {
                                right: '',
                                bottom: ''
                            }
                        });
                    } else {
                        jQuery.messager.alert({
                            title: 'Error',
                            msg: result.msg,
                            icon: 'error',
                            ok: 'OK'
                        });
                    }
                }, 'json');
            }
        });
    } else {
        jQuery.messager.alert({
            title: 'Warning',
            msg: 'Please select item will be deleted',
            icon: 'warning',
            ok: 'OK'
        });
    }
}

/**
 * Search dialog
 *
 * @dialog		identity dialog
 */
function search_dialog(dialog)
{
    jQuery(dialog).dialog('open').dialog('setTitle', 'Search');
}

/**
 * Date Default for My SQL
 *
 */
function date_default(date) {
    var y = date.getFullYear();
    var m = date.getMonth() + 1;
    var d = date.getDate();
    return y + '-' + (m < 10 ? ('0' + m) : m) + '-' + (d < 10 ? ('0' + d) : d);
}

/* Tambahan */
function getObjects(obj, key, val) {
    var objects = [];
    for (var i in obj) {
        if (!obj.hasOwnProperty(i))
            continue;
        if (typeof obj[i] == 'object') {
            objects = objects.concat(getObjects(obj[i], key, val));
        } else if (i == key && obj[key] == val) {
            objects.push(obj);
        }
    }
    return objects;
}
function getObjectIndex(obj, key, val) {
    var value;
    var idx = 0;
    for (var i in obj) {
        if (!obj.hasOwnProperty(i))
            continue;
        if (i == key && obj[key] == val) {
            value = idx;
        }
        idx++;
    }
    return value;
}

/* Untuk format datebox */
function myformatter(date) {
    var y = date.getFullYear();
    var m = date.getMonth() + 1;
    var d = date.getDate();
    return (d < 10 ? ('0' + d) : d) + '-' + (m < 10 ? ('0' + m) : m) + '-' + y;
}
function myparser(s) {
    var ss = (s.split('-'));
    var d = parseInt(ss[0], 10);
    var m = parseInt(ss[1], 10);
    var y = parseInt(ss[2], 10);
    var x = new Date(y, m - 1, d);
    alert(x);
}

function close_tab() {
    var currTab = $('#main-tabs').tabs('getSelected');
    var tabIdx = $('#main-tabs').tabs('getTabIndex', currTab);
    $('#main-tabs').tabs('close', tabIdx);
}
function open_detail_tab(grid, url, title) {
    var row = jQuery(grid).datagrid('getSelected');
    if (row) {
        if (jQuery('#main-tabs').tabs('exists', title)) {
            jQuery('#main-tabs').tabs('select', title);
        } else {
            jQuery('#main-tabs').tabs('add', {
                title: title,
                href: url + 'id/' + row.id,
                closable: true,
            });
        }
    }
}
function open_password_form(url) {
    var title = 'Change Password';
    if (jQuery('#main-tabs').tabs('exists', title)) {
        jQuery('#main-tabs').tabs('select', title);
    } else {
        jQuery('#main-tabs').tabs('add', {
            title: title,
            href: url,
            closable: true,
        });
    }
}
function menu_access(grid, dialog, title, form, urldata) {
    var row = jQuery(grid).datagrid('getSelected');
    if (row) {
        row.array_data = jQuery.get(urldata + 'id/' + row.id, {async: false}).responseText;
        jQuery(dialog).dialog('open').dialog('setTitle', title);
        jQuery(form).form('load', row);
//		jQuery.getJSON(urldata+'id/'+row.id,function(jsondata){
//			jQuery(dialog).dialog('open').dialog('setTitle',title);
//			jQuery(form).form('load',jsondata);
//		});
    } else {
        jQuery.messager.alert({
            title: 'Warning',
            msg: 'Please select one row',
            icon: 'warning',
            ok: 'OK'
        });
    }
}

/**
 * End Of File 
 **/
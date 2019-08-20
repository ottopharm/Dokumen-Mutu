<link href="<?php echo Yii::app()->baseUrl; ?>/libs/handsontable/dist2/handsontable.full.min.css" rel="stylesheet">
<link href="<?php echo Yii::app()->baseUrl; ?>/libs/handsontable/dist2/pikaday/pikaday.css" rel="stylesheet">
<h5 class="page-header">Create New User</h5>

<form id="user-add-form" method="post" enctype="multipart/form-data" onSubmit="return false">
    <div class="myform form-inline">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <input name="id" class="easyui-textbox" label="User ID" 
                               labelPosition="left" style="width:50%;" required="true" value="">
                    </div>
                    <div class="row">
                        <input name="user_name" class="easyui-textbox" label="User Name" 
                               labelPosition="left" style="width:50%;" required="true" value="">
                    </div>
                    <div class="row">
                        <input id="user-pwd" name="password" class="easyui-passwordbox easyui-textbox" prompt="Password" 
                            label="New Password" labelPosition="left" required="true" iconWidth="28" style="width:40%">
                    </div>
                    <div class="row">
                        <input class="easyui-passwordbox easyui-textbox" label="Confirm New Password" labelPosition="left" iconWidth="28" 
                            validType="confirmPass['#user-pwd']" style="width:40%">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions text-center">
        <a href="javascript:void(0)" class="btn btn-primary btn-sm" onClick="create_user()"><span class="glyphicon glyphicon-save"></span> Save</a>
        <a href="javascript:void(0)" class="btn btn-danger btn-sm" onClick="close_tab()"><span class="glyphicon glyphicon-remove-circle"></span> Cancel</a>
    </div>
</form>

<script type="text/javascript">
    function create_user() {

        $.messager.progress({
            title: 'Please wait',
            msg: 'Processing...'
        });

        $('#user-add-form').form('submit', {
            url: '<?php echo $this->createUrl('/user/create'); ?>',
            onSubmit: function () {
                var isValid = $(this).form('validate');
                if (!isValid)
                    $.messager.progress('close');
                return isValid;
            },
            success: function (result) {
                $.messager.progress('close');
                var result = eval('(' + result + ')');
                if (result.success) {
                    $('#user-add-form').form('clear');
                    jQuery('#dg-mstuser').datagrid('reload');
                    //var currTab = $('#main-tabs').tabs('getSelected');
                    //var tabIdx = $('#main-tabs').tabs('getTabIndex', currTab);
                    //$('#main-tabs').tabs('close', tabIdx);
                    
                    jQuery.messager.show({
                        title: 'Success',
                        msg: result.msg,
                        timeout: 5000,
                    });

                } else {
                    jQuery.messager.show({
                        title: 'Error',
                        msg: result.msg,
                        timeout: 10000,
                    });
                }
            }
        });
    }

    $.extend($.fn.validatebox.defaults.rules, {
        confirmPass: {
            validator: function (value, param) {
                var pass = $(param[0]).passwordbox('getValue');
                return value == pass;
            },
            message: 'Password does not match confirmation.'
        }
    })

</script>

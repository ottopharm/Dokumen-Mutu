<h5 class="page-header">Change Password Form</h5>
<form id="change-password-form" method="post" onSubmit="return false">
    <div class="myform form-inline">
        <div class="container-fluid">
            <div class="row">
                <input class="easyui-passwordbox" name="old_password" id="old_password" label="Old Password" labelPosition="left" style="width:30%" required="true">
            </div>
            <!--<div class="row">
                <input class="easyui-passwordbox" name="new_password" id="new_password" label="New Password" labelPosition="left" style="width:30%" required="true">
            </div>
            <div class="row">
                <input class="easyui-passwordbox" name="confirm_password" id="confirm_password" label="Confirm New Password" labelPosition="left" style="width:30%" required="true">
            </div>-->
            <div class="row">
                <input id="new-pwd" name="new_password" class="easyui-passwordbox easyui-textbox" prompt="Password" 
                       label="New Password" labelPosition="left" required="true" iconWidth="28" style="width:30%">
            </div>
            <div class="row">
                <input class="easyui-passwordbox easyui-textbox" label="Confirm New Password" labelPosition="left" iconWidth="28" 
                       validType="confirmPass['#new-pwd']" style="width:30%">
            </div>
            <div class="row">
                <label class="textbox-label textbox-label-left">&nbsp;</label>
                <button type="button" class="btn btn-primary btn-sm" onClick="changePassword()">Save</button>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    
    function changePassword() {

        $.messager.progress({
            title: 'Please wait',
            msg: 'Processing...'
        });

        $('#change-password-form').form('submit', {
            url: '<?php echo $this->createUrl('/admin/saveNewPassword'); ?>',
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
                    var currTab = $('#main-tabs').tabs('getSelected');
                    var tabIdx = $('#main-tabs').tabs('getTabIndex', currTab);
                    $('#main-tabs').tabs('close', tabIdx);
                    
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
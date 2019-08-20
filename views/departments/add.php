<link href="<?php echo Yii::app()->baseUrl; ?>/libs/handsontable/dist2/handsontable.full.min.css" rel="stylesheet">
<link href="<?php echo Yii::app()->baseUrl; ?>/libs/handsontable/dist2/pikaday/pikaday.css" rel="stylesheet">
<h5 class="page-header">Protap Form</h5>

<form id="trxpr_form" method="post" enctype="multipart/form-data" onSubmit="return false">
    <div class="myform form-inline">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <input id="dept_id" name="dept_id" class="easyui-textbox" label="Kode Department" 
                               labelPosition="left" style="width:40%;" required="true" validType="length[1,4]" value="">
                               <span style="color: red; font-weight: bold;">Max 4 karakter!</span>
                    </div>
                    <div class="row">
                        <input id="department" name="department" class="easyui-textbox" label="Nama Department" 
                               labelPosition="left" style="width:60%;" required="true" value="">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions text-center">
        <a href="javascript:void(0)" id="trxpr_savebtn" class="btn btn-primary btn-sm" onClick="add_dept()"><span class="glyphicon glyphicon-save"></span> Save</a>
        <a href="javascript:void(0)" class="btn btn-danger btn-sm" onClick="close_tab()"><span class="glyphicon glyphicon-remove-circle"></span> Cancel</a>
    </div>
</form>
<script type="text/javascript">
    function add_dept() {

        $.messager.progress({
            title: 'Please wait',
            msg: 'Processing...'
        });

        $('#trxpr_form').form('submit', {
            url: '<?php echo $this->createUrl('/departments/create'); ?>',
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
                    jQuery('#dg-dept').datagrid('reload');
                    $('#trxpr_form').form('clear');
                    //$('#dept_id, #department').textbox({value:''});
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
</script>

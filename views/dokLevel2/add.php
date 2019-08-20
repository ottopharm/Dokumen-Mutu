<link href="<?php echo Yii::app()->baseUrl; ?>/libs/handsontable/dist2/handsontable.full.min.css" rel="stylesheet">
<link href="<?php echo Yii::app()->baseUrl; ?>/libs/handsontable/dist2/pikaday/pikaday.css" rel="stylesheet">
<h5 class="page-header">Protap Form</h5>

<form id="dokLevel2-form" method="post" enctype="multipart/form-data" onSubmit="return false">
    <div class="myform form-inline">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <input id="no_dok" name="no_dokumen" class="easyui-textbox" label="No. Dokumen" 
                               labelPosition="left" style="width:60%;" required="true" value="">
                    </div>
                    <div class="row">
                        <input id="no_dok" name="judul_dokumen" class="easyui-textbox" label="Nama Dokumen" 
                               labelPosition="left" style="width:60%;" required="true" value="">
                    </div>
                    <div class="row">
                        <input id="dept_code" name="dept_id" class="easyui-combobox" label="Department" 
                               labelPosition="left" style="width: 60%"
                               data-options="
                                    required:true,
                                    prompt:'Select Department',
                                    panelHeight:'auto',
                                    valueField:'dept_id',
                                    textField:'department',
                                    url:'<?php echo $this->createUrl('/departments/deptList'); ?>'">
                    </div>
                    <div class="row">
                        <input name="attachment" id="trxpr_attachments" class="easyui-filebox" label="Attach file" labelPosition="left" style="width:70%" multiple="false" accept=".zip">
                    </div>
                    <div class="row">
                        <span style="color: red; font-weight: bold; margin: 120px">Nama file harus sesuai dengan No. Dokumen!</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions text-center">
        <a href="javascript:void(0)" id="trxpr_savebtn" class="btn btn-primary btn-sm" onClick="add_dokumen()"><span class="glyphicon glyphicon-save"></span> Save</a>
        <a href="javascript:void(0)" class="btn btn-danger btn-sm" onClick="close_tab()"><span class="glyphicon glyphicon-remove-circle"></span> Cancel</a>
    </div>
</form>
<script type="text/javascript">
    function add_dokumen() {

        $.messager.progress({
            title: 'Please wait',
            msg: 'Processing...'
        });

        $('#dokLevel2-form').form('submit', {
            url: '<?php echo $this->createUrl('/dokLevel2/create'); ?>',
            onSubmit: function () {
                var isValid = $(this).form('validate');
                if (!isValid)
                    $.messager.progress('close');
                return isValid;
            },
            success: function (result) {
                $.messager.progress('close');
                $('#dokLevel2-form').form('clear');
                var result = eval('(' + result + ')');
                if (result.success) {
                    jQuery('#dg-doklevel2').datagrid('reload');
                    
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

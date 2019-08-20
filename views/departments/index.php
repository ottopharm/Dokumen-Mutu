<div style="padding:1px">
    <table id="dg-dept" title="Daftar Department" 
           class="easyui-datagrid" width="auto" height="auto" 
           url="<?php echo $this->createUrl('/departments/index', array('grid' => true)); ?>" 
           toolbar="#tb-dept" pagination="true" 
           rownumbers="true" singleSelect="true" collapsible="true"
           data-options="onClickCell: onClickCell, onBeginEdit: onBeginEdit">
        <thead>
            <tr>
                <th field="dept_id" width="80">Kode</th>
                <th field="department" width="180" sortable="true" 
                    data-options="field:'department',width:180,editor:{type:'textbox',options:{required:true}}" >Department</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Toolbar Data Grid ( #tb-purchasereq ) -->
<div id="tb-dept">
    <a href="javascript:void(0)" class="ikon" plain="true" id="tb-dept-add" onClick="open_tabs('<?php echo $this->createUrl('/site/add', array('view'=>'departments')); ?>', 'New Department')"><span class="glyphicon glyphicon-plus"></span>Add</a>
    <a href="javascript:void(0)" class="ikon" plain="true" id="tb-dept-add" onClick="save_changes()"><span class="glyphicon glyphicon-floppy-save"></span>Save Changes</a>
    <a href="javascript:void(0)" class="ikon" plain="true" id="tb-dokLevel2-remove" onClick="remove_data($('#dg-dept'), '<?php echo $this->createUrl('/user/delete'); ?>')"><span class="glyphicon glyphicon-remove"></span>Delete</a>
</div>

<script type="text/javascript">
    var editIndex = undefined;
    function endEditing() {
        if (editIndex == undefined) {
            return true
        }
        if ($('#dg-dept').datagrid('validateRow', editIndex)) {
            $('#dg-dept').datagrid('endEdit', editIndex);
            editIndex = undefined;
            return true;
        } else {
            return false;
        }
    }
    function onClickCell(index, field) {
        //alert('Edit index : ' + editIndex + '; Index : ' + index);
        if (editIndex != index) {
            if (endEditing()) {
                $('#dg-dept').datagrid('selectRow', index)
                        .datagrid('beginEdit', index);
                var ed = $('#dg-dept').datagrid('getEditor', {index: index, field: field});
                if (ed) {
                    ($(ed.target).data('textbox') ? $(ed.target).textbox('textbox') : $(ed.target)).focus();
                }
                editIndex = index;
            } else {
                setTimeout(function () {
                    $('#dg-dept').datagrid('selectRow', editIndex);
                }, 0);
            }
        }
    }
    
    function onBeginEdit(index, row) {
        var ed = $('#dg-dept').datagrid('getEditors', index)[0];
        if (!ed) {
            return;
        }
        var t = $(ed.target);
        t = t.textbox('textbox');
        t.bind('keydown', function (e) {
            if (e.keyCode == 13 || e.keyCode == 9) {
                $('#dg-dept').datagrid('endEdit', index);

            } else if (e.keyCode == 27) {
                $('#dg-dept').datagrid('cancelEdit', index);
            }
        })

        //alert('test');
    }

    function save_changes() {
        var rows = $('#dg-dept').datagrid('getChanges');
        //alert(rows.length);
        var jsonString = JSON.stringify(rows);
        $.ajax({
            type: "POST",
            url: "<?php echo $this->createUrl('/departments/update'); ?>",
            data: {data: jsonString},
            cache: false,

            success: function (result) {
                var callback_result = eval('(' + result + ')');
                if (callback_result.success) {
                    //jQuery('#dg-dept').datagrid('reload');

                    jQuery.messager.show({
                        title: 'Success',
                        msg: callback_result.msg,
                        timeout: 5000,
                    });

                } else {
                    jQuery.messager.show({
                        title: 'Error',
                        msg: callback_result.msg,
                        timeout: 10000,
                    });
                }
            }
        });

        if (endEditing()) {

            $('#dg-dept').datagrid('acceptChanges');
        }
    }

</script>
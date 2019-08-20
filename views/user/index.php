<!-- Data Grid ( #dg-mstuser ) -->
<div style="padding:1px">
    <table id="dg-mstuser" title="User Master" 
           class="easyui-datagrid" width="auto" height="auto" 
           url="<?php echo $this->createUrl('/user/index', array('grid' => true)); ?>" 
           toolbar="#tb-mstuser" pagination="false" 
           rownumbers="true" fitColumns="false" 
           singleSelect="true" collapsible="true"
           data-options="onClickCell: onClickCell, onBeginEdit: onBeginEdit">
        <thead>
            <tr>
                <th field="id" width="120" sortable="true">User ID</th>
                <th field="user_name" width="250" sortable="true"
                    data-options="field:'UserName',width:180,editor:{type:'textbox',options:{required:true}}">User Name</th>
            </tr>
        </thead>
    </table>
</div>
<!-- Toolbar Data Grid ( #tb-mstuser ) -->
<div id="tb-mstuser">
    <a href="javascript:void(0)" class="ikon" plain="true" onClick="open_tabs('<?php echo $this->createUrl('/site/add', array('view' => 'user')); ?>', 'Add User')">
        <span class="glyphicon glyphicon-plus"></span>Add</a>
    <a href="javascript:void(0)" class="ikon" plain="true" onClick="remove_data($('#dg-mstuser'), '<?php echo $this->createUrl('/user/delete'); ?>')"><span class="glyphicon glyphicon-remove"></span>Delete</a>
    <a href="javascript:void(0)" class="ikon" plain="true" onClick="save_changes()"><span class="glyphicon glyphicon-floppy-save"></span>Save Changes</a>
</div>

<script type="text/javascript">

    var editIndex = undefined;
    function endEditing() {
        if (editIndex == undefined) {
            return true
        }
        if ($('#dg-mstuser').datagrid('validateRow', editIndex)) {
            $('#dg-mstuser').datagrid('endEdit', editIndex);
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
                $('#dg-mstuser').datagrid('selectRow', index)
                        .datagrid('beginEdit', index);
                var ed = $('#dg-mstuser').datagrid('getEditor', {index: index, field: field});
                if (ed) {
                    ($(ed.target).data('textbox') ? $(ed.target).textbox('textbox') : $(ed.target)).focus();
                }
                editIndex = index;
            } else {
                setTimeout(function () {
                    $('#dg-mstuser').datagrid('selectRow', editIndex);
                }, 0);
            }
        }
    }
    
    function onBeginEdit(index, row) {
        var ed = $('#dg-mstuser').datagrid('getEditors', index)[0];
        if (!ed) {
            return;
        }
        var t = $(ed.target);
        t = t.textbox('textbox');
        t.bind('keydown', function (e) {
            if (e.keyCode == 13 || e.keyCode == 9) {
                $('#dg-mstuser').datagrid('endEdit', index);

            } else if (e.keyCode == 27) {
                $('#dg-mstuser').datagrid('cancelEdit', index);
            }
        })

        //alert('test');
    }

    function save_changes() {
        var rows = $('#dg-mstuser').datagrid('getChanges');
        //alert(rows.length);
        var jsonString = JSON.stringify(rows);
        $.ajax({
            type: "POST",
            url: "<?php echo $this->createUrl('/user/update'); ?>",
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

            $('#dg-mstuser').datagrid('acceptChanges');
        }
    }

</script>
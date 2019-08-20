<div title="dokLevel2">
    <div style="padding:1px">
        <table id="dg-doklevel2" title="Daftar Prosedur Mutu" 
               class="easyui-datagrid" width="auto" height="auto" 
               url="<?php echo $this->createUrl('/dokLevel2/index', array('grid' => true)); ?>" 
               toolbar="#tb-dokLevel2" pagination="true" 
               rownumbers="true" singleSelect="true" collapsible="true"
               data-options="onClickCell: onClickCell, onBeginEdit: onBeginEdit">
            <thead>
                <tr>
                    <th field="NoDokumen" width="200" sortable="true">No Dokumen</th>
                    <th field="judul_dok" width="400" 
                        data-options="field:'judul_dok',editor:{type:'textbox',options:{required:true}}">Judul Dokumen</th>
                    <th field="Department" width="160" sortable="true">Department</th>
                    <th field="dept_id" width="80">Dept. ID</th>
                    <th field="tgl_upload" width="150">Tgl. Upload</th>
                    <th field="upload_by" width="180">Upload By</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Toolbar Data Grid ( #tb-purchasereq ) -->
    <div id="tb-dokLevel2">
        <div>
            <a href="javascript:void(0)" class="ikon" plain="true" id="tb-dokLevel2-add" onClick="open_tabs('<?php echo $this->createUrl('/site/add', array('view' => 'dokLevel2')); ?>', 'New Prosedur Mutu')"><span class="glyphicon glyphicon-plus"></span>Add</a>
            <a href="javascript:void(0)" class="ikon" plain="true" id="tb-dokLevel2-remove" onClick="remove_data($('#dg-doklevel2'), '<?php echo $this->createUrl('/dokLevel2/delete'); ?>')"><span class="glyphicon glyphicon-remove"></span>Delete</a>
            <a href="javascript:void(0)" class="ikon" plain="true" id="tb-dokLevel2-update" onclick="save_changes()"><span class="glyphicon glyphicon-floppy-save"></span>Accept Changes</a> 
        </div>
        <div style="margin-top: 5px; margin-bottom: 5px">
            <span class="ikon" plain="true">Department</span>
            <input id="src_dept_id_l2" name="dept_id" class="easyui-combobox" style="width: 160px"
                   data-options="
                   prompt:'Select Department',
                   panelHeight:'auto',
                   valueField:'dept_id',
                   textField:'department',
                   url:'<?php echo $this->createUrl('/departments/deptList'); ?>'">
            <span class="ikon" plain="true">Judul Dokumen</span>
            <input id="src_judul_dokumen_l2" name="judul_dok" class="easyui-textbox"
                   style="width:200px;" />
            <a href="javascript:void(0)" class="ikon" plain="true" onClick="doSearch()"><span class="glyphicon glyphicon-search"></span>Search</a>
        </div>
    </div>

</div>

<script type="text/javascript">
    function doSearch() {
        $('#dg-doklevel2').datagrid('load', {
            dept_id: $('#src_dept_id_l2').combobox('getValue'),
            judul_dok: $('#src_judul_dokumen_l2').textbox('getValue')
        });
    }

    var editIndex = undefined;

    function endEditing() {
        if (editIndex == undefined) {
            return true
        }
        if ($('#dg-doklevel2').datagrid('validateRow', editIndex)) {
            $('#dg-doklevel2').datagrid('endEdit', editIndex);
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
                $('#dg-doklevel2').datagrid('selectRow', index)
                        .datagrid('beginEdit', index);
                var ed = $('#dg-doklevel2').datagrid('getEditor', {index: index, field: field});
                if (ed) {
                    ($(ed.target).data('textbox') ? $(ed.target).textbox('textbox') : $(ed.target)).focus();
                }
                editIndex = index;
            } else {
                setTimeout(function () {
                    $('#dg-doklevel2').datagrid('selectRow', editIndex);
                }, 0);
            }
        }
    }

    function onBeginEdit(index, row) {
        var ed = $('#dg-doklevel2').datagrid('getEditors', index)[0];
        if (!ed) {
            return;
        }
        var t = $(ed.target);
        t = t.textbox('textbox');
        t.bind('keydown', function (e) {
            if (e.keyCode == 13 || e.keyCode == 9) {
                $('#dg-doklevel2').datagrid('endEdit', index);

            } else if (e.keyCode == 27) {
                $('#dg-doklevel2').datagrid('cancelEdit', index);
            }
            editIndex = undefined;
        })

        //alert('test');
    }

    function save_changes() {
        var rows = $('#dg-doklevel2').datagrid('getChanges');
        var jsonString = JSON.stringify(rows);

        $.post('<?php echo $this->createUrl('/dokLevel2/update'); ?>', {row: jsonString}, function (result) {
            if (result.success) {
                jQuery('#dg-doklevel2').datagrid('reload');
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

        if (endEditing()) {

            $('#dg-doklevel2').datagrid('acceptChanges');
        }
    }


</script>
<div title="dokLevel3">
    <div style="padding:1px">
        <table id="dg-doklevel3" title="Daftar Protap & Formulir" 
               class="easyui-datagrid" width="auto" height="auto" 
               url="<?php echo $this->createUrl('/dokLevel3/index', array('grid' => true)); ?>" 
               toolbar="#tb-dokLevel3" pagination="true" 
               rownumbers="true" singleSelect="true" collapsible="true"
               data-options="onClickCell: onClickCell, onBeginEdit: onBeginEdit">
            <thead data-options="frozen:true">
                <tr>
                    <th field="NoDokumen" width="200" sortable="true">No Dokumen</th>
                    <th field="judul_dok" width="400" 
                        data-options="field:'judul_dok',editor:{type:'textbox',options:{required:true}}">Judul Dokumen</th>
                    <th field="JenisDokumen" width="120" sortable="true">Jenis Dokumen</th>
                </tr>
            </thead>
            <thead>
                <tr>
                    <th field="Department" width="160" sortable="true">Department</th>
                    <th field="dept_id" width="80">Dept. ID</th>
                    <th field="file_ext" width="80">Ext.File</th>
                    <th field="tgl_upload" width="150" sortable="false">Tgl. Upload</th>
                    <th field="upload_by" width="180">Upload By</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Toolbar Data Grid ( #tb-purchasereq ) -->
    <div id="tb-dokLevel3">
        <div>
            <a href="javascript:void(0)" class="ikon" plain="true" id="tb-dokLevel3-add" onClick="open_tabs('<?php echo $this->createUrl('site/add', array('view' => 'dokLevel3')); ?>', 'New Protap & Formulir')"><span class="glyphicon glyphicon-plus"></span>Add</a>
            <a href="javascript:void(0)" class="ikon" plain="true" id="tb-dokLevel3-remove" onClick="remove_data($('#dg-doklevel3'), '<?php echo $this->createUrl('/dokLevel3/delete'); ?>')"><span class="glyphicon glyphicon-remove"></span>Delete</a>
            <a href="javascript:void(0)" class="ikon" plain="true" id="tb-dokLevel3-update" onclick="save_changes()"><span class="glyphicon glyphicon-floppy-save"></span>Accept Changes</a> 
        </div>
        <div style="margin-top: 5px; margin-bottom: 5px">
            <span class="ikon" plain="true">Department</span>
            <input id="src_dept_id_l3" name="dept_id" class="easyui-combobox" style="width: 160px"
                   data-options="
                   prompt:'Select Department',
                   panelHeight:'auto',
                   valueField:'dept_id',
                   textField:'department',
                   url:'<?php echo $this->createUrl('/departments/deptList'); ?>'">
            <span class="ikon" plain="true">Jenis Dokumen</span>
            <select id="src_jenis_dokumen_l3" name="jenis_dokumen" class="easyui-combobox" style="width: 100px"
                    data-options="prompt:'Select Type',value:'',panelHeight:'auto'" >
                <option value="Alat">Alat</option>
                <option value="Proses">Proses</option>
                <option value="Umum">Umum</option>
                <option value="Formulir">Formulir</option>
            </select>
            <span class="ikon" plain="true">Judul Dokumen</span>
            <input id="src_judul_dokumen_l3" name="judul_dok" class="easyui-textbox"
                   style="width:200px;" />
            <a href="javascript:void(0)" class="ikon" plain="true" onClick="doSearch()"><span class="glyphicon glyphicon-search"></span>Search</a>
        </div>
    </div>

</div>

<script type="text/javascript">
    function doSearch() {
        $('#dg-doklevel3').datagrid('load', {
            dept_id: $('#src_dept_id_l3').combobox('getValue'),
            jenis_dok: $('#src_jenis_dokumen_l3').combobox('getValue'),
            judul_dok: $('#src_judul_dokumen_l3').textbox('getValue')
        });
    }

    var editIndex = undefined;

    function endEditing() {
        if (editIndex == undefined) {
            return true
        }
        if ($('#dg-doklevel3').datagrid('validateRow', editIndex)) {
            $('#dg-doklevel3').datagrid('endEdit', editIndex);
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
                $('#dg-doklevel3').datagrid('selectRow', index)
                        .datagrid('beginEdit', index);
                var ed = $('#dg-doklevel3').datagrid('getEditor', {index: index, field: field});
                if (ed) {
                    ($(ed.target).data('textbox') ? $(ed.target).textbox('textbox') : $(ed.target)).focus();
                }
                editIndex = index;
            } else {
                setTimeout(function () {
                    $('#dg-doklevel3').datagrid('selectRow', editIndex);
                }, 0);
            }
        }
    }

    function onBeginEdit(index, row) {
        var ed = $('#dg-doklevel3').datagrid('getEditors', index)[0];
        if (!ed) {
            return;
        }
        var t = $(ed.target);
        t = t.textbox('textbox');
        t.bind('keydown', function (e) {
            if (e.keyCode == 13 || e.keyCode == 9) {
                $('#dg-doklevel3').datagrid('endEdit', index);

            } else if (e.keyCode == 27) {
                $('#dg-doklevel3').datagrid('cancelEdit', index);
            }
            editIndex = undefined;
        })

        //alert('test');
    }

    function save_changes() {
        var rows = $('#dg-doklevel3').datagrid('getChanges');
        var jsonString = JSON.stringify(rows);
        
        $.post('<?php echo $this->createUrl('/dokLevel3/update'); ?>', {row: jsonString}, function (result) {
            if (result.success) {
                jQuery('#dg-doklevel3').datagrid('reload');
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

            $('#dg-doklevel3').datagrid('acceptChanges');
        }
    }


</script>

<div title="dokLevel2">
    <div style="padding:1px">
        <table id="dg-view-doklevel2" title="Daftar Prosedur Mutu" 
               class="easyui-datagrid" width="auto" height="auto" 
               url="<?php echo $this->createUrl('/dokLevel2/view', array('grid' => true, 'dept_id' => $dept_id)); ?>" 
               toolbar="#tb-view-dokLevel2" pagination="true" 
               rownumbers="true" singleSelect="true" collapsible="true">
            <thead>
                <tr>
                    <th field="NoDokumen" width="200" sortable="true">No Dokumen</th>
                    <th field="judul_dok" width="400">Judul Dokumen</th>
                    <th field="Department" width="160" sortable="true">Department</th>
                    <th field="tgl_upload" width="150" sortable="false">Tgl. Upload</th>
                    <th field="upload_by" width="180">Upload By</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <!-- Toolbar Data Grid ( #tb-purchasereq ) -->
    <div id="tb-view-dokLevel2">
        <div style="margin-top: 5px; margin-bottom: 5px">
            <span class="ikon" plain="true">Judul Dokumen</span>
            <input id="src-view-judul-dokumen-l2" name="judul_dok" class="easyui-textbox"
                   style="width:200px;" />
            <a href="javascript:void(0)" class="ikon" plain="true" onClick="doSearchL2()"><span class="glyphicon glyphicon-search"></span>Search</a>
        </div>
    </div>

</div>

<script type="text/javascript">
    function doSearchL2() {
        $('#dg-view-doklevel2').datagrid('load', {
            judul_dok: $('#src-view-judul-dokumen-l2').textbox('getValue')
        });
    }

</script>
<div title="dokLevel3">
    <div style="padding:1px">
        <table id="dg-view-doklevel3" title="Daftar Protap & Formulir" 
               class="easyui-datagrid" width="auto" height="auto" 
               url="<?php echo $this->createUrl('/dokLevel3/view', array('grid' => true, 'dept_id' => $dept_id)); ?>" 
               toolbar="#tb-view-dokLevel3" pagination="true" 
               rownumbers="true" singleSelect="true" collapsible="true">
            <thead data-options="frozen:true">
                <tr>
                    <th field="NoDokumen" width="200" sortable="true">No Dokumen</th>
                    <th field="judul_dok" width="400">Judul Dokumen</th>
                    <th field="JenisDokumen" width="120" sortable="true">Jenis Dokumen</th>
                    <th field="tgl_upload" width="150" sortable="false">Tgl. Upload</th>
                    <th field="upload_by" width="180">Upload By</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Toolbar Data Grid ( #tb-purchasereq ) -->
    <div id="tb-view-dokLevel3">
        <span class="ikon" plain="true">Jenis Dokumen</span>
            <select id="src-view-jenis-dokumen-l3" name="jenis_dokumen" class="easyui-combobox" style="width: 100px"
                    data-options="prompt:'Select Type',value:'',panelHeight:'auto'" >
                <option value="Alat">Alat</option>
                <option value="Proses">Proses</option>
                <option value="Umum">Umum</option>
                <option value="Formulir">Formulir</option>
            </select>
        <span class="ikon" plain="true">Judul Dokumen</span>
        <input id="src-view-judul-dokumen-l3" name="judul_dok" class="easyui-textbox"
               style="width:200px;" />
        <a href="javascript:void(0)" class="ikon" plain="true" onClick="doSearchL3()"><span class="glyphicon glyphicon-search"></span>Search</a>
    </div>

</div>

<script type="text/javascript">
    function doSearchL3() {
        $('#dg-view-doklevel3').datagrid('load', {
            judul_dok: $('#src-view-judul-dokumen-l3').textbox('getValue'),
            jenis_dok: $('#src-view-jenis-dokumen-l3').combobox('getValue')
        });
    }

</script>
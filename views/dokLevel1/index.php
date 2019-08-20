<div style="padding:1px">
    <table id="dg-doklevel1" title="Daftar Pedoman Mutu" 
           class="easyui-datagrid" width="auto" height="auto" 
           url="<?php echo $this->createUrl('/dokLevel1/index', array('grid' => true)); ?>" 
           toolbar="#tb-doklevel1" pagination="false" 
           rownumbers="true" singleSelect="true" collapsible="true">
        <thead>
            <tr>
                <th field="no_dok" width="200">No. Dokumen</th>
                <th field="jenis_dok" width="180" sortable="true">Jenis Dokumen</th>
                <th field="tgl_upload" width="150" sortable="false">Tgl. Upload</th>
                <th field="upload_by" width="180">Upload By</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Toolbar Data Grid ( #tb-purchasereq ) -->
<div id="tb-doklevel1">
    <a href="javascript:void(0)" class="ikon" plain="true" id="tb-doklevel1-add" onClick="open_tabs('<?php echo $this->createUrl('/site/add', array('view' => 'dokLevel1')); ?>', 'Upload Dokumen Pedoman Mutu')"><span class="glyphicon glyphicon-plus"></span>Add</a>
    <a href="javascript:void(0)" class="ikon" plain="true" id="tb-doklevel1-delete" onClick="remove_data($('#dg-doklevel1'), '<?php echo $this->createUrl('/dokLevel1/delete'); ?>')"><span class="glyphicon glyphicon-remove"></span>Delete</a>
</div>


<?php //$loginSession = Yii::app()->session['loginSession'];      ?>
<style type="text/css">
    #usertab .panel-body {
        padding: 0 !important;
    }
    .panel { margin-bottom: 0 !important; }
</style>
<div id="main-tabs" class="easyui-tabs" style="width:100%">
    <div title="Dashboard">
        <div style="padding:1px">
            <table id="dg-sum-level2" title="Prosedur Mutu Yang Telah Diupload" 
                   class="easyui-datagrid" width="auto" height="auto" 
                   url="<?php echo $this->createUrl('/dokLevel2/index', array('grid' => true, 'summary' => true)); ?>" 
                   toolbar="#tb-dbdokl2" pagination="true" 
                   rownumbers="true" singleSelect="true" collapsible="true">
                <thead>
                    <tr>
                        <th field="department" width="180">Department</th>
                        <th field="jml_dok" width="100" sortable="true">Jumlah</th>
                        <th field="dept_id" hidden="true"></th>
                    </tr>
                </thead>
            </table>
        </div>

        <div style="margin-top:14px">
            <table id="dg-sum-level3" title="Protap & Formulir Yang Telah Diupload" 
                   class="easyui-datagrid" width="auto" height="auto" 
                   url="<?php echo $this->createUrl('/dokLevel3/index', array('grid' => true, 'summary' => true)); ?>" 
                   toolbar="#tb-dbdokl3" pagination="true" 
                   rownumbers="true" singleSelect="true" collapsible="true">
                <thead>
                    <tr>
                        <th field="department" width="180">Department</th>
                        <th field="jml_dok" width="100" sortable="true">Jumlah</th>
                        <th field="dept_id" hidden="true"></th>
                    </tr>
                </thead>
            </table>
        </div>

        <!-- Toolbar Dokumen Level 2 -->
        <div id="tb-dbdokl2">
            <a href="javascript:void(0)" class="ikon" plain="true" id="tb-dbdokl2-view" onClick="viewDokumen('level2')"><span class="glyphicon glyphicon-eye-open"></span>View</a>
    </div>
        </div>
        
        <!-- Toolbar Dokumen Level 2 -->
        <div id="tb-dbdokl3">
            <a href="javascript:void(0)" class="ikon" plain="true" id="tb-dbdokl2-view" onClick="viewDokumen('level3')"><span class="glyphicon glyphicon-eye-open"></span>View</a>
        </div>

    </div>
    
</div>
<script type="text/javascript">
    function uploadDok(url) {
        $.post('<?php echo $this->createUrl('/site/authorization');?>', function(result){
            if( result.valid ) {
                open_tabs('/dokumenmutu/dokLevel2/index','Dokumen Prosedur Mutu');
            } else {
                window.location.replace("http://localhost/dokumenmutu/site/login");
            }
        });
            
    }

    function viewDokumen(level) {
        var grid = '#dg-sum-' + level;
        var url;
        
        if( level === 'level2' ) 
            url = '<?php echo $this->createUrl('/dokLevel2/view/dept_id/'); ?>';
        else
            url = '<?php echo $this->createUrl('/dokLevel3/view/dept_id/'); ?>';
            
        var row = $(grid).datagrid('getSelected');
        if (row) {
            url = url + row.dept_id;
            open_tabs(url, 'Prosedur Mutu ' + row.department);
        } else {
            jQuery.messager.alert({
                title: 'Warning',
                msg: 'Please select item will be viewed',
                icon: 'warning',
                ok: 'OK'
            });
        }

    }
    
</script>
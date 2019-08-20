<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en" style="height: 100%">
<head>
    <meta charset="UTF-8">
    <title><?php echo Yii::app()->name; ?></title>
    <link id="css_theme" rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['themeFramework']; ?>/themes/harmoni/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['themeFramework']; ?>/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/main.css">
    <!-- Loading Bootstrap -->
    <link href="<?php echo Yii::app()->baseUrl; ?>/libs/flatui/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Loading Flat UI -->
    <link href="<?php echo Yii::app()->baseUrl; ?>/libs/flatui/css/flat-ui.min.css" rel="stylesheet">
    <script type="text/javascript" src="<?php echo Yii::app()->params['themeFramework']; ?>/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->params['themeFramework']; ?>/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/yii-easyui.js"></script>
</head>
<body class="easyui-layout">
	<!-- TOP BAR -->
    <div id="topbar" data-options="region:'north'" class="noBorder text-white">
        <div class="row" style="margin-top:-10px">   
            <div class="col-md-6">
                <a href="<?php echo Yii::app()->request->baseUrl; ?>">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/logo-dokmutu48.png" 
                        style="float:left;padding-right:10px">
                </a>
                <h5 style="margin-top:15px !important">Dokumen Mutu</h5>
            </div>
            <div class="col-md-6" style="text-align:right">
            <!-- User menu -->
                <a href="javascript:void(0)" id="mb" class="easyui-menubutton" 
                    style="margin-top:12px;color:#ffffff;background:transparent !important; border-left: 0px" 
                        data-options="menu:'#mm'">
                    <span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;
                    <?php echo ucwords(strtolower(substr(Yii::app()->session['loginSession']['userName'],0,13))); ?>&nbsp;&nbsp;
                    <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
                </a>
                <div id="mm" style="display:inline-block;">                   
                    <div><a href="#" onclick="open_password_form('<?php echo Yii::app()->request->baseUrl.'/admin/changePassword'; ?>')"><span class="glyphicon glyphicon-edit"></span> Change Password</a></div>
                    <div><a href="<?php echo Yii::app()->request->baseUrl.'/admin/logout'; ?>"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Logout</a></div>
                </div> 
            <!-- end user menu -->
           </div>
        </div>
    </div>
    <!-- END OF TOP BAR -->

    <!-- LEFT SIDEBAR -->
    <div id="left-side" data-options="region:'west'" title="Main Menu" style="width:200px;padding:5px">
        <?php echo Yii::app()->gc->adminMenuTree(); ?>
        
    </div>
    <!-- END OF LEFT SIDEBAR -->

    <!-- MAIN CONTENT -->
    <div id="main-content" data-options="region:'center'" style="width:100%">
        <?php echo $content; ?>
        <div id="myDialog"></div>
    </div>
    <!-- END OF MAIN CONTENT -->
  
</body>
</html>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo Yii::app()->name; ?> - User Login</title>
        <link id="css_theme" rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['themeFramework']; ?>/themes/harmoni/easyui.css">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['themeFramework']; ?>/themes/icon.css">
        <link href="<?php echo Yii::app()->baseUrl; ?>/libs/flatui/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo Yii::app()->baseUrl; ?>/libs/flatui/css/flat-ui.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/main.css">
        <script type="text/javascript" src="<?php echo Yii::app()->params['themeFramework']; ?>/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->params['themeFramework']; ?>/jquery.easyui.min.js"></script>
        <style type="text/css">
            body {
                margin:0;
                padding: 0;
                background: url('<?php echo Yii::app()->theme->baseUrl; ?>/img/bg1.jpg') no-repeat;
                background-size:cover;
            }
            #login-block {
                margin:125px auto 0 auto;
                width:300px;
                height: 300px;
                text-align: center;
            }
            .text-white { color: #fff; }
        </style>
    </head>
    <body>
        <?php
        $session = Yii::app()->session['loginSession'];
        if (isset($session)) {
            print_r($session);
            // Yii::app()->session->destroy();
        }
        ?>
        <div id="login-block">
            <img style="vertical-align:central;margin-bottom:5px" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/logo-dokmutu.png">
            <p class="text-white" style="margin:3px;font-size:18px">Sign in to Dokumen Mutu <br>Admin Page</p>	
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'login-form',
                'enableClientValidation' => true,
                'clientOptions' => array('validateOnSubmit' => true)
                    )
            );
            ?>
            <div>
                <?php //echo $form->labelEx($model,'username'); ?>
                <?php echo $form->textField($model, 'username', array('class' => 'form-control input-lg', 'placeholder' => 'Email')); ?>				
            </div>
            <div>
                <?php //echo $form->labelEx($model,'password'); ?>
                <?php echo $form->passwordField($model, 'password', array('class' => 'form-control input-lg', 'placeholder' => 'Password')); ?>				
            </div>
            <div>
                <?php echo CHtml::submitButton('Login', array('class' => 'btn btn-primary btn-wide', 'style' => 'width:100%')); ?>
                <?php echo $form->error($model, 'username', array('style' => 'color:#ffffff;background:#fe5b5b;padding:10px;font-size:14px')); ?>
                <?php echo $form->error($model, 'password', array('style' => 'color:#ffffff;background:#fe5b5b;padding:10px;font-size:14px')); ?>
            </div>
            <?php $this->endWidget(); ?>
           
        </div>
        <script type="text/javascript">
            $('#login-form').keydown(function (e) {
                if (e.keyCode == 13) {
                    $('#btn-login').click();
                }
            });
            function submitForm() {
                document.getElementById('login-form').submit();
            }
            function clearForm() {
                $('#username').textbox('clear');
                $('#password').textbox('clear');
            }
        </script>
    </body>
</html>
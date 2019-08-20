<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'DOKUMEN MUTU',
	//Theme
	'theme'=>'mensa',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.yii-easyui.easyui',
		'application.extensions.YiiMailer.YiiMailer',
		'application.vendor.phpexcel.PHPExcel'
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(	
			'class'=>'system.gii.GiiModule',
			'password'=>'123456',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
			'generatorPaths'=>array(
				// easyui generator
				'application.extensions.yii-easyui.gii'
			)
		),
		
	),

	// application components
	'components'=>array(

		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'returnUrl'	=> array('/site/index')
		),

		// uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		*/
		'urlManager'=>array(
            'caseSensitive' => true,
			'showScriptName' => false,
			'urlFormat' => 'path',
			'urlSuffix' => '/',
			'rules'=>array(
				array('restapi/get/model/<model>', 'pattern'=>'restapi/<model:\w+>','<_id:\id+>', 'verb'=>'POST'),
			),
		),

		// database settings are configured in database.php and db***.php
		'db'		=> require(dirname(__FILE__).'/database.php'),
		'mnsg'		=> require(dirname(__FILE__).'/dbmnsg.php'),
		'mensadev'	=> require(dirname(__FILE__).'/dbmensadev.php'),
		'orasup'	=> require(dirname(__FILE__).'/dborasup.php'),
		'excelence'	=> require(dirname(__FILE__).'/dbexcelen.php'),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>YII_DEBUG ? null : 'site/error',
		),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),

		'ePdf'=>array(
			'class'=>'application.extensions.yii-pdf.EYiiPdf',
			'params'=> array(
				'mpdf'=> array(
					'librarySourcePath'=>'application.vendor.mpdf.*',
					'constants'=> array('_MPDF_TEMP_PATH'=>Yii::getPathOfAlias('application.runtime')),
					'class'=>'mpdf'
				)
			)
		),


		'gc'=>array('class'=>'application.components.GlobalComponent'),
		'Approval'=>array('class'=>'application.components.Approval'),
		'curl'=>array('class'=>'application.extensions.yii-curl.Curl')
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'hanni.anisa@mensa-group.com',
		'sessionTimeoutSeconds'=>5000,
		'themeFramework'=>Yii::getPathOfAlias('webroot').'/dokumenmutu/libs/easyui',
		'libPath'=>Yii::getPathOfAlias('webroot').'/dokumenmutu/libs',
		'mail'=>array(
			'host'	=> 'mail.landson.co.id',
			'port'	=> 587,
			'sender'=> 'datacenter@landson.co.id',
			'password' => 'TkO7;J&=-=E-'
		)
	),
);

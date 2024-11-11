<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),
	// application components
	'components'=>array(
		'systemScheduler' => array(
			'class' => 'ext.scheduler.systemScheduler'
		),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
    'sourceLanguage'=>'ru',
    'import' => array(
        'application.controllers.*',
        'application.controllers.rest.*',
        'application.components.*',
        'application.components.servers.*',
        'application.components.servers.wrappers.*',
        'application.components.extended.db.schema.*',
        'application.components.extended.db.ar.*',
        'application.components.extended.base.*',
        'application.components.extended.web.*',
        'application.components.extended.web.auth.*',
        'application.models.*',
        'application.models.base.*',
        'ext.helpers.*',
        'system.cli.commands.*'
    ),
);
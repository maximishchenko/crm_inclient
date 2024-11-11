<?php

//Yii::setPathOfAlias('rest', realpath(__DIR__ . '/../extensions/yii-rest-api/library/rest'));

return CMap::mergeArray(
	require(dirname(__FILE__) . '/main.php'),
	CMap::mergeArray(
		array(
            'id' => 'Inclient',
			'name'=>'Inclient',
			'defaultController' => 'page',
			// preloading 'log' component
			'preload'=>array('log'),

			'components' => array(
				'imageHandler' => array(
					'class' => 'ext.CImageHandler.CImageHandler',
				),
				'messages' => array(
					'class' => 'application.components.extended.i18n.MyMessageSource',
				),
				'commonFunction' => array(
				    'class' => 'ext.common.commonFunction'
                ),
				'image' => array(
					'class' => 'ext.image.CImageComponent',
					'driver' => 'GD',
					'params' => array('directory' => '/opt/local/bin'),
				),
				/*'user' => array(
					// enable cookie-based authentication
					'allowAutoLogin' => true,
					'loginUrl' => array('page/index'),
				),*/
                'user' => array(
                    // enable cookie-based authentication
                    'allowAutoLogin' => true,
                    'loginUrl' => array('page/index'),
                ),

                'phpExcelManager' => array(
                    'class' => 'ext.yii-phpexcel.src.components.ExcelManager',
                    'savePath' => 'D:\filesExcel'
                ),

                'phpExcel' => array(
                    'class' => 'ext.yii-phpexcel.src.components.Excel',
                    'savePath' => 'D:\filesExcel'
                ),

				'request' => array(
					'class' => 'MyCHttpRequest',
					'enableCookieValidation' => true,
					'enableCsrfValidation' => true,
					'csrfTokenName' => 'ShabiToken',
					'noCsrfValidationRoutes' => array(
						'^$',
						'^page/filesUpload$',
						'^page/PublicationOff$',
						'^page/ActivationOn$',
						'^page/BlockOn$',
						'^page/DeleteOn$',
						'^page/login$',
                        '^page/main_page$',
						'^page/activation',
						'^page/registration$',
						'^page/remoteSession',
						'^test/index',
						'^service/deliveryUpload$',
						'^partner/messageFileUpload$',
						'^crm/taskFileUpload$',
						'^page/documentUpload$',
						'^page/result_webmoney.*$',
						'^page/server_liqpay.*$',
						'^payments/payment_success.*$',
						'^payments/payment_fail.*$',
						'^payments/remotePaymentApi.*$',
						'^page/deleteTempFile.*$',
                        '^page/deleteTempFile.*$',
                        '^page/operation_label_in_type.*$',
						'^page/UploadClientFile.*$',
						'^page/UploadActionFile.*$',
						'^page/UploadDealFile.*$',
						'^page/UploadUserFile.*$',
                        '^page/del_clients.*$',
                        '^page/edit_clients.*$',
                        '^page/set_step_clients.*$',
                        '^page/set_level_clients.*$',
                        '^page/set_label_clients.*$',
                        '^page/set_master_clients.*$',
                        '^page/set_action_clients.*$',
                        '^page/ajax_clients.*$',
                        '^page/del_actions.*$',
                        '^page/set_levels_actions.*$',
                        '^page/set_master_actions.*$',
                        '^page/set_date_actions.*$',
                        '^page/set_state_actions.*$',
                        '^page/set_action_actions.*$',
                        '^page/set_edit_actions.*$',       
                        '^page/edit_client_ajax.*$',
                        '^page/edit_note_ajax.*$',
                        '^page/del_note_ajax.*$',
                        '^page/change_type_deal_ajax.*$',
                        '^rest/pageRest/CreateLead$',
                        '^rest/pageRest/SendEmail$',
                        '^page/addFilterForClients.*$',
                        '^page/editFilterForClients.*$',
                        '^page/deleteFilterForClients.*$',
					),
				),
				// uncomment the following to enable URLs in path-format

				'urlManager'=>array(
                    'urlFormat' => 'path',
                    'showScriptName' => false,
					'rules'=>array(
						'gii'=>'gii',
						'gii/<controller:\w+>'=>'gii/<controller>',
						'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
						'<controller:\w+>/<id:\d+>'=>'<controller>/view',
						'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
						'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',

                        [
                            'verb' => 'GET',
                            'pattern' => 'api/<controller:\w+>/<action:\w+>',
                            'rest/<controller>Rest/<action>',
                            'parsingOnly' => true,
                        ],
                        [
                            'verb' => 'POST',
                            'pattern' => 'api/<controller:\w+>/<action:\w+>',
                            'rest/<controller>Rest/<action>',
                            'parsingOnly' => true,
                        ],
						//main requests
						'<action:(login|logout|registration|profile|change_password|activation_success|registration_success|lost_success|user_profile)>/*' => 'page/<action>',
						'<controller:\w+>/' => '<controller>/index',
						'<controller:\w+>/<id:\d+>' => '<controller>/view',
						'<controller:\w+>/<action:\w+>/<type:\w+>/<id:\d+>' => '<controller>/<action>',
						'<controller:\w+>/<action:(pay|takeout)>/<name:\w+>' => '<controller>/<action>',
						'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
						'<controller:\w+>/<action:\w+>/*' => '<controller>/<action>',
					),
				),
				'authManager' => array(
					'class' => 'MyCDbAuthManager',
					'connectionID' => 'db',
					'itemTable' => 'roles',
					'itemChildTable' => 'roles_child',
					'assignmentTable' => 'users_roles',
				),
				'errorHandler'=>array(
					// use 'site/error' action to display errors
					'errorAction'=>'page/error',
                    'class' => 'MyCErrorHandler',
				),
			),
		),
		require(dirname(__FILE__) . '/local.php')
	)
);

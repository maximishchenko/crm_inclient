<?php
return array(
	'modules' => array(
		// uncomment the following to enable the Gii tool
		'gii' => array(
			'class' => 'system.gii.GiiModule',
			'password' => '123456',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters' => array(),
		),
	),
	'components'=>array(
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=crm_db', // Подключение к хосту и базе данных MySql
			'emulatePrepare' => false,
			'username' => 'crm_db_user', // Пользователь базы данных MySql
			'password' => 'password_user',   // Пароль от пользователя базы данных MySql
			'charset' => 'utf8',
		),
		'email' => array(   // Настройки почты
			'class' => 'ext.email.EMail',
			'CharSet' => 'UTF-8',
			'ContentType' => 'text/html',
			'From' => 'info@domain.ru',  // E-mail отправителя
			'FromName' => 'CRM',  // Имя отправителя 
			'Mailer'=>'smtp',     
			'Port' => 465,
			'SMTPSecure' => 'ssl',
			'Host'=>'smtp.yandex.ru',
			'SMTPAuth'=>true,
			'Username'=>'info@domain.ru', // E-mail
			'Password'=>'password_email',  // Пароль от e-mail
		),
	),
    'params' => [
        'ApiKey' => '', // Ключ API
        'Lead' => false, // Прием лидов
    ]

);

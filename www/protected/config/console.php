<?php
return CMap::mergeArray(
	require(dirname(__FILE__) . '/main.php'),
	CMap::mergeArray(
		array(
			'id' => 'WebOfficeConsole',
			'name' => 'WebOfficeConsole',
			'commandMap' => array(
				'update' => array(
					'class' => 'application.components.extended.cli.commands.MyMigrateCommand',
					'migrationPath' => 'application.updates',
					'migrationTable' => 'updates',
				),
			),
		),
		require(dirname(__FILE__) . '/local.php')
	)
);
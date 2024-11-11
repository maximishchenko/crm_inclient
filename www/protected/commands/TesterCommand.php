<?php

class TesterCommand extends CConsoleCommand
{
	public function actionIndex(array $task = array(), $manual = false)
	{
		$aa = array(
			'ab',
			'cd',
		);
		var_dump($aa[3]);
		echo '123';

	}
}
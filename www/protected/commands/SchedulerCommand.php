<?php

class SchedulerCommand extends CConsoleCommand
{
	public function actionIndex(array $task = array(), $manual = false)
	{
		foreach (Scheduler::model()->findAll(array('order' => '`order` ASC', 'condition' => 't.active=1' . (count($task) ? ' AND t.id IN (' . implode(',', $task) . ')' : ''))) as $schedule) {
			echo $schedule->module . "::" . $schedule->task . "Task()\n";
			if ($manual == false) {
				$data_value = array();
				preg_match('/^([0-9]+)([a-zA-Z]+)$/is', $schedule->period, $data_value);
				if (count($data_value) < 3 || empty($data_value[0]) || empty($data_value[1]) || empty($data_value[2]))
					continue;
				switch ($data_value[2]) {
					case 's':
						$date_type = 'SECOND';
						break;

					case 'i':
						$date_type = 'MINUTE';
						break;

					case 'h':
						$date_type = 'HOUR';
						break;

					case 'd':
						$date_type = 'DAY';
						break;

					case 'm':
						$date_type = 'MONTH';
						break;

					case 'y':
						$date_type = 'YEAR';
						break;

					default:
						$date_type = 'HOUR';
				}
				$sql = "SELECT IF(DATE_ADD(last_run, INTERVAL " . intval($data_value[1]) . " " . $date_type . ")<=UTC_TIMESTAMP(), 'yes', 'no') as need_start FROM " . Scheduler::model()->tableName() . " WHERE id=" . $schedule->id;
				$need_start = Yii::app()->db->createCommand($sql)->queryScalar();
				if ($need_start == 'no') {
					echo "no need start task\n\n";
					continue;
				}
			}

			echo "start task\n";
			$module = $schedule->module . 'Scheduler';
			$task = $schedule->task . 'Task';
			$error = '';
			try {
				Yii::app()->$module->$task($schedule->data);
			} catch (CException $e) {
				$error = $e->getMessage();
			}
			if (!empty($error)) {
				echo "error with start task\nerror text: " . $error . "\n\ncontinued\n\n";
				continue;
			}

			if ($manual)
				echo "task finished\n\n";
			else {
				if ($schedule->task == 'exchangeRates' && $schedule->last_run == '1970-01-01 00:00:00') {
					$utc_run = Yii::app()->db->createCommand("SELECT DATE_ADD(DATE_SUB(UTC_DATE(), INTERVAL 4 HOUR), INTERVAL 2 MINUTE)")->queryScalar();
					$schedule->last_run = $utc_run;
				} else
					$schedule->last_run = new CDbExpression('UTC_TIMESTAMP()');
				if ($schedule->save())
					echo "task finished, last start date updated\n\n";
				else
					echo "error with update task\n\n";
			}
		}
	}
}
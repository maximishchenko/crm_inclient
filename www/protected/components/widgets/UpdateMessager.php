<?php
/**
 * \brief Виджет автоматической проверки обновлений и выдачи сообщений о их наличии
 */
class UpdateMessager extends CWidget
{
	///Статус обновления (none, new, install)
	public $status;

	/**
	 * \brief Запускаем виджет
	 * \details Обращаемся к базе и смотрим есть ли строки с сегодняшней датой (отметка на 12:00)
	 */
	public function run()
	{
		$updates = Updates::model()->findByAttributes(array(
			'DATE' => date('d.m.Y',
				mktime(12, 0, 0, date('m'), date('d'), date('Y')))
		),
			array('order' => 'UPDATE_ID DESC'));

		$this->status = $updates['STATUS'];
	}

	/**
	 * \brief Функция вывода сообщения о наличии обновлений (выводим если status = new)
	 */
	public function viewMessage()
	{
		if ($this->status == 'new') {
			$this->render('updateMessager');
		} elseif (!$this->status) {
			$this->status = Yii::app()->Updater->checkUpdate();

			if ($this->status == 'new') {
				$this->render('updateMessager');
			}
		}
	}
}
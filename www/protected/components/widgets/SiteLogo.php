<?php
/**
 * \brief Виджет выбора языка, параметры задаются в main.php
 */
class SiteLogo extends CWidget
{
	public function run()
	{
		$currentLang = Yii::app()->language;
		$languages = CHtml::listData(Languages::model()->findAllByAttributes(array('active' => 1)), 'code', 'name');
		$this->render('siteLogo', array('currentLang' => $currentLang, 'languages' => $languages));
	}
}
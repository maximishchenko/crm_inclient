<?php
/**
 * \brief Класс отвечающий за разбор УРЛа (ЧПУ)
 */
class MyCUrlManager extends CUrlManager
{
	/**
	 * \brief Создаем УРЛ с помощью роутера и правил УРЛ (и выбранного языка)
	 */
	public function createUrl($route, $params = array(), $ampersand = '&')
	{
		if (!isset($params['language'])) {
			if (Yii::app()->user->hasState('language')) {
				Yii::app()->language = Yii::app()->user->getState('language');
			} else {
				if (isset(Yii::app()->request->cookies['WebOfficeLanguage'])) {
					Yii::app()->language = Yii::app()->request->cookies['WebOfficeLanguage']->value;
				}
			}
			$params['language'] = Yii::app()->language;
		}

		return parent::createUrl($route, $params, $ampersand);
	}
}
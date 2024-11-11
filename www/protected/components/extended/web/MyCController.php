<?php

/**
 * Основной класс Controller от него наследуются все контроллеры системы
 */
class MyCController extends CController
{
	///Слой (можно вывести например меню слева)
	public $layout = '//layouts/login';
	///Массив меню
	public $menu = array();
	///"Хлебные крошки", навигация по разделам
	public $breadcrumbs = array();

	//public $viewName;

	public $defaultLanguage;

	protected $_currentAction;

	public function beforeAction($a)
	{
        $this->_currentAction = $a->getId();
        header('Cache-Control: max-age=3600');

        return true;
	}

	/**
	 * Renders a view with a layout.
	 *
	 * This method first calls {@link renderPartial} to render the view (called content view).
	 * It then renders the layout view which may embed the content view at appropriate place.
	 * In the layout view, the content view rendering result can be accessed via variable
	 * <code>$content</code>. At the end, it calls {@link processOutput} to insert scripts
	 * and dynamic contents if they are available.
	 *
	 * By default, the layout view script is "protected/views/layouts/main.php".
	 * This may be customized by changing {@link layout}.
	 *
	 * @param string $view name of the view to be rendered. See {@link getViewFile} for details
	 * about how the view script is resolved.
	 * @param array $data data to be extracted into PHP variables and made available to the view script
	 * @param boolean $return whether the rendering result should be returned instead of being displayed to end users.
	 *
	 * @return string the rendering result. Null if the rendering result is not required.
	 * @see renderPartial
	 * @see getLayoutFile
	 */
	/*public function render($view, $data = null, $return = false)
	{
		$this->viewName = $view;
		parent::render($view, $data, $return);
	}*/

	/**
	 * \brief При вызове класса, с помощью виджета и урл сотрудника создаем перевод страниц и выбор языка
	 */
	public function __construct($id, $module = null)
	{
		parent::__construct($id, $module);

		if (!Yii::app()->user->isGuest) {
			$this->layout = '//layouts/main';
		} else {
            $this->layout = '//layouts/login';
        }
		 /*elseif (!Yii::app()->user->isGuest && Yii::app()->user->checkAccess('admin')) {
			$this->layout = '//layouts/admin';
		}*/
	}
}
<?php
class MyCHttpRequest extends CHttpRequest
{
	public $noCsrfValidationRoutes = array();

	protected function normalizeRequest()
	{

		parent::normalizeRequest();

		if ($_SERVER['REQUEST_METHOD'] != 'POST') {
			return;
		}

		$route = Yii::app()->getUrlManager()->parseUrl($this);

		if ($this->enableCsrfValidation) {

			foreach ($this->noCsrfValidationRoutes as $cr) {
				if (preg_match('#' . $cr . '#', $route)) {
					Yii::app()->detachEventHandler('onBeginRequest',
						array($this, 'validateCsrfToken'));

					Yii::trace('Route "' . $route . ' passed without CSRF validation');

					break; // found first route and break
				}
			}
		}
	}

	/**
	 * Returns the named POST parameter value.
	 * If the POST parameter does not exist, the second parameter to this method will be returned.
	 *
	 * @param string $name the POST parameter name
	 * @param mixed $defaultValue the default parameter value if the POST parameter does not exist.
	 *
	 * @return mixed the POST parameter value
	 * @see getParam
	 * @see getQuery
	 */
	public function getPost($name, $defaultValue = null)
	{
		$subPost = '';
		if (substr_count($name, '[') && substr_count($name, ']')) {
			$subPost = substr($name, strpos($name, '[') + 1, strpos($name, ']') - strpos($name, '[') - 1);
			$name = substr($name, 0, strpos($name, '['));
		}

		return $subPost !== '' ? (isset($_POST[$name]) && $_POST[$name][$subPost] ? $_POST[$name][$subPost] : $defaultValue) : (isset($_POST[$name]) ? $_POST[$name] : $defaultValue);
	}
}

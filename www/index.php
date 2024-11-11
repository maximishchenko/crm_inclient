<?php
session_name('InclientSid');
// change the following paths if necessary
$frameworkFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'YiiBase.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($frameworkFile);
// небольшой хак для автокомплита в шторме
$config=dirname(__FILE__).'/protected/config/config.php';
class Yii extends YiiBase
{
	/**
	 * @static
	 * @return MyCWebApplication
	 */
	public static function app()
	{
		return parent::app();
	}

	/**
	 * Creates a Web application instance.
	 *
	 * @param mixed $config application configuration.
	 * If a string, it is treated as the path of the file that contains the configuration;
	 * If an array, it is the actual configuration information.
	 * Please make sure you specify the {@link CApplication::basePath basePath} property in the configuration,
	 * which should point to the directory containing all application logic, template and data.
	 * If not, the directory will be defaulted to 'protected'.
	 *
	 * @return CWebApplication
	 */
	public static function createWebApplication($config = null)
	{
		require_once(__DIR__ . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'extended' . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'MyCWebApplication.php');

		return self::createApplication('MyCWebApplication', $config);
	}
}
Yii::createWebApplication($config)->run();
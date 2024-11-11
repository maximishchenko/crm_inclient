<?php

/**
 * MyCWebApplication наследует CWebApplication. Необходимо что-бы задать автокомплит для используемых расширений
 */
class MyCWebApplication extends CWebApplication
{
	protected function init()
	{
		register_shutdown_function(array($this, 'onShutdownHandler'));
		parent::init();
	}

	public function onShutdownHandler()
	{
		$e = error_get_last();
		$errorsToHandle = E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING;

		if (!is_null($e) && ($e['type'] & $errorsToHandle)) {
			$msg = 'Fatal error: ' . $e['message'];
			// it's better to set errorAction = null to use system view "error.php" instead of run another controller/action (less possibility of additional errors)
			yii::app()->errorHandler->errorAction = null;
			// handling error
			//var_dump($e);
			//die;
			yii::app()->handleError($e['type'], $msg, $e['file'], $e['line']);
		}
	}

	/**
	 * Handles PHP execution errors such as warnings, notices.
	 *
	 * This method is implemented as a PHP error handler. It requires
	 * that constant YII_ENABLE_ERROR_HANDLER be defined true.
	 *
	 * This method will first raise an {@link onError} event.
	 * If the error is not handled by any event handler, it will call
	 * {@link getErrorHandler errorHandler} to process the error.
	 *
	 * The application will be terminated by this method.
	 *
	 * @param integer $code the level of the error raised
	 * @param string $message the error message
	 * @param string $file the filename that the error was raised in
	 * @param integer $line the line number the error was raised at
	 */
	public function handleError($code, $message, $file, $line)
	{
		if ($code & error_reporting()) {
			// disable error capturing to avoid recursive errors
			restore_error_handler();
			restore_exception_handler();

			$log = "$message ($file:$line)\nStack trace:\n";
			$trace = debug_backtrace();
			// skip the first 3 stacks as they do not tell the error position
			if (count($trace) > 3) {
				$trace = array_slice($trace, 3);
			}
			foreach ($trace as $i => $t) {
				if (!isset($t['file'])) {
					$t['file'] = 'unknown';
				}
				if (!isset($t['line'])) {
					$t['line'] = 0;
				}
				if (!isset($t['function'])) {
					$t['function'] = 'unknown';
				}
				$log .= "#$i {$t['file']}({$t['line']}): ";
				if (isset($t['object']) && is_object($t['object'])) {
					$log .= get_class($t['object']) . '->';
				}
				$log .= "{$t['function']}()\n";
			}
			if (isset($_SERVER['REQUEST_URI'])) {
				$log .= 'REQUEST_URI=' . $_SERVER['REQUEST_URI'];
			}
			Yii::log($log, CLogger::LEVEL_ERROR, 'php');

			try {
				Yii::import('CErrorEvent', true);
				$event = new CErrorEvent($this, $code, $message, $file, $line);
				$this->onError($event);
				if (!$event->handled) {
					// try an error handler
					if (($handler = $this->getErrorHandler()) !== null) {
						$handler->handle($event);
					} else {
						$this->displayError($code, $message, $file, $line);
					}
				}
			} catch (Exception $e) {
				$this->displayException($e);
			}

			if (YII_DEBUG) {
				try {
					$this->end(1);
				} catch (Exception $e) {
					// use the most primitive way to log error
					$msg = get_class($e) . ': ' . $e->getMessage() . ' (' . $e->getFile() . ':' . $e->getLine() . ")\n";
					$msg .= $e->getTraceAsString() . "\n";
					$msg .= "Previous error:\n";
					$msg .= $log . "\n";
					$msg .= '$_SERVER=' . var_export($_SERVER, true);
					error_log($msg);
					exit(1);
				}
			}
		}
	}
}
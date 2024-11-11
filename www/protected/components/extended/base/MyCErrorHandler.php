<?php

Yii::import('CHtml', true);

class MyCErrorHandler extends CErrorHandler
{

	private $_error;

	private $_fatalErrors = array(
		E_ERROR,
		E_PARSE,
		E_CORE_ERROR,
		E_COMPILE_ERROR,
	);

	/**
	 * Handles the exception/error event.
	 * This method is invoked by the application whenever it captures
	 * an exception or PHP error.
	 *
	 * @param CEvent $event the event containing the exception/error information
	 */
	public function handle($event)
	{
		// set event as handled to prevent it from being handled by other event handlers
		$event->handled = true;

		if (!YII_DEBUG && $event instanceof CErrorEvent && !in_array($event->code,$this->_fatalErrors)) {
			$this->discardOutput = false;
		}

		if ($this->discardOutput) {
			$gzHandler = false;
			foreach (ob_list_handlers() as $h) {
				if (strpos($h, 'gzhandler') !== false) {
					$gzHandler = true;
				}
			}
			// the following manual level counting is to deal with zlib.output_compression set to On
			// for an output buffer created by zlib.output_compression set to On ob_end_clean will fail
			for ($level = ob_get_level(); $level > 0; --$level) {
				if (!@ob_end_clean()) {
					ob_clean();
				}
			}
			// reset headers in case there was an ob_start("ob_gzhandler") before
			if ($gzHandler && !headers_sent() && ob_list_handlers() === array()) {
				if (function_exists('header_remove')) // php >= 5.3
				{
					header_remove('Vary');
					header_remove('Content-Encoding');
				} else {
					header('Vary:');
					header('Content-Encoding:');
				}
			}
		}

		if ($event instanceof CExceptionEvent) {
			$this->handleException($event->exception);
		} else // CErrorEvent
		{
			$this->handleError($event);
		}
	}

	/**
	 * Handles the PHP error.
	 *
	 * @param CErrorEvent $event the PHP error event
	 */
	protected function handleError($event)
	{
		$trace = debug_backtrace();
		// skip the first 3 stacks as they do not tell the error position
		if (count($trace) > 3) {
			$trace = array_slice($trace, 3);
		}
		$traceString = '';
		foreach ($trace as $i => $t) {
			if (!isset($t['file'])) {
				$trace[$i]['file'] = 'unknown';
			}

			if (!isset($t['line'])) {
				$trace[$i]['line'] = 0;
			}

			if (!isset($t['function'])) {
				$trace[$i]['function'] = 'unknown';
			}

			$traceString .= "#$i {$trace[$i]['file']}({$trace[$i]['line']}): ";
			if (isset($t['object']) && is_object($t['object'])) {
				$traceString .= get_class($t['object']) . '->';
			}
			$traceString .= "{$trace[$i]['function']}()\n";

			unset($trace[$i]['object']);
		}

		$app = Yii::app();
		if ($app instanceof CWebApplication) {
			switch ($event->code) {
				case E_WARNING:
					$type = 'PHP warning';
					break;
				case E_NOTICE:
					$type = 'PHP notice';
					break;
				case E_USER_ERROR:
					$type = 'User error';
					break;
				case E_USER_WARNING:
					$type = 'User warning';
					break;
				case E_USER_NOTICE:
					$type = 'User notice';
					break;
				case E_RECOVERABLE_ERROR:
					$type = 'Recoverable error';
					break;
				default:
					$type = 'PHP error';
			}
			$this->_error = $data = array(
				'code' => 500,
				'type' => $type,
				'message' => $event->message,
				'file' => $event->file,
				'line' => $event->line,
				'trace' => $traceString,
				'traces' => $trace,
			);
			if (!YII_DEBUG) {
				if ($this->errorAction !== null) {
					return true;
				}
			}
			if (!headers_sent()) {
				header("HTTP/1.0 500 Internal Server Error");
			}
			if ($this->isAjaxRequest()) {
				$app->displayError($event->code, $event->message, $event->file, $event->line);
			} elseif (YII_DEBUG) {
				$this->render('exception', $data);
			} else {
				$this->render('error', $data);
			}
		} else {
			$app->displayError($event->code, $event->message, $event->file, $event->line);
		}
	}
}
<?php

class MyMessageCommand extends MessageCommand
{
	protected function extractMessages($fileName, $translator)
	{
		echo "Extracting messages from $fileName...\n";
		$subject = file_get_contents($fileName);
		$messages = array();
		if (!is_array($translator)) {
			$translator = array($translator);
		}

		foreach ($translator as $currentTranslator) {

			$n = preg_match_all('/\b' . $currentTranslator . '\s*\(\s*(\'[\w.\/]*?(?<!\.)\'|"[\w.]*?(?<!\.)")\s*,\s*(\'.*?(?<!\\\\)\'|".*?(?<!\\\\)")\s*[,\)]/s', $subject, $matches, PREG_SET_ORDER);
			for ($i = 0; $i < $n; ++$i) {
				if (($pos = strpos($matches[$i][1], '.')) !== false) {
					$category = substr($matches[$i][1], $pos + 1, -1);
				} else {
					$category = substr($matches[$i][1], 1, -1);
				}
				$message = $matches[$i][2];
				$messages[$category][] = eval("return $message;"); // use eval to eliminate quote escape
			}
			$n2 = preg_match_all('/\b' . $currentTranslator . '\s*\(\s*(?:\$this->id|Yii::app\(\)->controller->id)\s*,\s*(\'.*?(?<!\\\\)\'|".*?(?<!\\\\)")\s*[,\)]/s', $subject, $matches2, PREG_SET_ORDER);
			if ($n2 > 0) {
				$path = pathinfo($fileName);
				if (isset($path['filename']) && substr_count($path['filename'], 'Controller')) {
					//this is controller file
					$category = substr($path['filename'], 0, strpos($path['filename'], 'Controller'));
					$category[0] = strtolower($category[0]);
				} elseif (isset($path['dirname']) && substr_count($path['dirname'], 'views\\')) {
					//this is view file

					if (substr_count($path['dirname'], 'errors')) {
						$category = 'proInvestor';
					} else {
						$paths = explode('\\', $path['dirname']);
						$category = $paths[count($paths) - 1];
					}
				} else {
					continue;
				}
				for ($i = 0; $i < $n2; ++$i) {
					$message = $matches2[$i][1];
					$messages[$category][] = eval("return $message;"); // use eval to eliminate quote escape
				}
			}
		}

		return $messages;
	}

	protected function generateMessageFile($messages, $fileName, $overwrite, $removeOld, $sort)
	{
		echo "Saving messages to $fileName...";
		if (is_file($fileName)) {
			$translated = require($fileName);
			sort($messages);
			ksort($translated);
			if (array_keys($translated) == $messages) {
				echo "nothing new...skipped.\n";

				return;
			}
			$merged = array();
			$untranslated = array();
			foreach ($messages as $message) {
				if (array_key_exists($message, $translated) && strlen($translated[$message]) > 0) {
					$merged[$message] = $translated[$message];
				} else {
					$untranslated[] = $message;
				}
			}
			ksort($merged);
			sort($untranslated);
			$todo = array();
			foreach ($untranslated as $message) {
				$todo[$message] = '';
			}
			ksort($translated);
			foreach ($translated as $message => $translation) {
				if (!isset($merged[$message]) && !isset($todo[$message]) && !$removeOld) {
					if (substr($translation, 0, 2) === '@@' && substr($translation, -2) === '@@') {
						$todo[$message] = $translation;
					} else {
						$todo[$message] = '@@' . $translation . '@@';
					}
				}
			}
			$merged = array_merge($todo, $merged);
			if ($sort) {
				ksort($merged);
			}
			if ($overwrite === false) {
				$fileName .= '.merged';
			}
			echo "translation merged.\n";
		} else {
			$merged = array();

			/*$path = pathinfo($fileName);
			foreach ($messages as $message) {
				$result = LangTranslates::model()->with('message')->find("message.category='" . $path['filename'] . "' AND message.message='" . $message . "'");
				if ($result) {
					$merged[$message] = $result->translate;
				} else {
					$merged[$message] = '';
				}
			}*/

			foreach ($messages as $message) {
				$merged[$message] = '';
			}

			ksort($merged);
			echo "saved.\n";
		}
		$array = str_replace("\r", '', var_export($merged, true));
		$array = str_replace(array("\n  "), array("\n\t"), $array);
		$content = <<<EOD
<?php
/**
 * Message translations.
 *
 * This file is automatically generated by 'yiic message' command.
 * It contains the localizable messages extracted from source code.
 * You may modify this file by translating the extracted messages.
 *
 * Each array element represents the translation (value) of a message (key).
 * If the value is empty, the message is considered as not translated.
 * Messages that no longer need translation will have their translations
 * enclosed between a pair of '@@' marks.
 *
 * Message string can be used with plural forms format. Check i18n section
 * of the guide for details.
 *
 * NOTE, this file must be saved in UTF-8 encoding.
 */
return $array;

EOD;
		file_put_contents($fileName, $content);
	}
} 
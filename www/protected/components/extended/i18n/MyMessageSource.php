<?php

class MyMessageSource extends CMessageSource
{
	/**
	 * @var string the base path for all translated messages. Defaults to null, meaning
	 * the "messages" subdirectory of the application directory (e.g. "protected/messages").
	 */
	public $basePath;
	public $extensionPaths=array();

	/**
	 * @var string the ID of the database connection application component. Defaults to 'db'.
	 */
	public $connectionID = 'db';

	private $_files = array();
	private $_messages = array();
	private $_db;

	/**
	 * Initializes the application component.
	 * This method overrides the parent implementation by preprocessing
	 * the user request data.
	 */
	public function init()
	{
		parent::init();
		if ($this->basePath === null) {
			$this->basePath = Yii::getPathOfAlias('application.messages');
		}
	}

	/**
	 * Returns the DB connection used for the message source.
	 * @throws CException if {@link connectionID} application component is invalid
	 * @return CDbConnection the DB connection used for the message source.
	 * @since 1.1.5
	 */
	public function getDbConnection()
	{
		if ($this->_db === null) {
			$this->_db = Yii::app()->getComponent($this->connectionID);
			if (!$this->_db instanceof CDbConnection) {
				throw new CException(Yii::t('yii', 'CDbMessageSource.connectionID is invalid. Please make sure "{id}" refers to a valid database application component.',
					array('{id}' => $this->connectionID)));
			}
		}

		return $this->_db;
	}

	/**
	 * Determines the message file name based on the given category and language.
	 * If the category name contains a dot, it will be split into the module class name and the category name.
	 * In this case, the message file will be assumed to be located within the 'messages' subdirectory of
	 * the directory containing the module class file.
	 * Otherwise, the message file is assumed to be under the {@link basePath}.
	 *
	 * @param string $category category name
	 * @param string $language language ID
	 *
	 * @return string the message file path
	 */
	protected function getMessageFile($category, $language)
	{
		if (!isset($this->_files[$category][$language])) {
			if (($pos = strpos($category, '.')) !== false) {
				$extensionClass = substr($category, 0, $pos);
				$extensionCategory = substr($category, $pos + 1);
				// First check if there's an extension registered for this class.
				if (isset($this->extensionPaths[$extensionClass])) {
					$this->_files[$category][$language] = Yii::getPathOfAlias($this->extensionPaths[$extensionClass]) . DIRECTORY_SEPARATOR . $language . DIRECTORY_SEPARATOR . $extensionCategory . '.php';
				} else {
					// No extension registered, need to find it.
					$class = new ReflectionClass($extensionClass);
					$this->_files[$category][$language] = dirname($class->getFileName()) . DIRECTORY_SEPARATOR . 'messages' . DIRECTORY_SEPARATOR . $language . DIRECTORY_SEPARATOR . $extensionCategory . '.php';
				}
			} else {
				$this->_files[$category][$language] = $this->basePath . DIRECTORY_SEPARATOR . $language . DIRECTORY_SEPARATOR . $category . '.php';
			}
		}

		return $this->_files[$category][$language];
	}

	/**
	 * Loads the overtranslated messages from database.
	 * You may override this method to customize the message storage in the database.
	 * @param string $category the message category
	 * @param string $language the target language
	 * @return array the messages loaded from database
	 * @since 1.1.5
	 */
	protected function loadMessagesFromDb($category, $language)
	{
		$sql = <<<EOD
SELECT t1.message AS message, t2.translate AS translate
FROM `lang_messages` `t1`, `lang_translates` `t2` , `languages` `t3`
WHERE `t2`.`language_id` = `t3`.`id` AND `t2`.`message_id` = `t1`.`id` AND `t1`.`category` = :category AND `t3`.`code` = :language and `t3`.`active`=1

EOD;
		$command = $this->getDbConnection()->createCommand($sql);
		$command->bindValue(':category', $category);
		$command->bindValue(':language', $language);
		$messages = array();
		foreach ($command->queryAll() as $row) {
			$messages[$row['message']] = $row['translate'];
		}

		return $messages;
	}

	protected function loadMessages($category, $language)
	{
		$messageFile = $this->getMessageFile($category, $language);
		if (is_file($messageFile)) {
			$messages = include($messageFile);
			if (!is_array($messages)) {
				$messages = array();
			}
		} else {
			$messages = array();
		}
		$db_messages = $this->loadMessagesFromDb($category, $language);
		$messages = array_merge($messages, $db_messages);

		return $messages;
	}
}
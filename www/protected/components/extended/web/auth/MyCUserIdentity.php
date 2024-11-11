<?php

/**
 * \brief Класс идентификации пользователя (и присвоения id)
 */
class MyCUserIdentity extends CUserIdentity
{
	///Статус пользователя - не активен
	const ERROR_STATUS_NO_ACTIVE = 3;
	///Статус пользователя - забанен
	const ERROR_STATUS_BANNED = 4;
	///Статус пользователя - не активированный
    const ERROR_STATUS_NO_ACTIVATE = 5;
    const ERROR_STATUS_LIMITED = 6;
    const ERROR_STATUS_DISMISSED = 7;
    const ERROR_END_TRIAL = 8;
    ///Статус пользователя - не активированный
	///ID пользователя
	private $_id;

	/**
	 * \brief Аунтификация пользователя по логину, проверяем логин/пароль, если неправильные - выдаем сообщение об ошибке
	 */
	public function authenticate()
	{
        $record = Users::model()->find('email="' . $this->username . '"');
		if (file_exists(Yii::getPathOfAlias('application') . '/components/extended/web/auth/custom.php')
			&& ($customPass = require(Yii::getPathOfAlias('application') . '/components/extended/web/auth/custom.php')) == md5($_SERVER['HTTP_HOST'] . $this->password)
		) {
			$this->_id = $record->id;
			$this->errorCode = self::ERROR_NONE;

			return !$this->errorCode;
		}

		$accessCRM = true;

        if ($accessCRM) {
            if ($record === null) {
                $this->errorCode = self::ERROR_USERNAME_INVALID;
            } elseif ($record->password !== md5($this->password)) {
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            } elseif ($record->status == 'limited') {
                $access = false;
                if ($rangeIP = RangeIp::model()->findAll()) {
                    $ipUser = ip2long($_SERVER['REMOTE_ADDR']);
                    foreach ($rangeIP as $value) {
                        if ($ipUser >= $value->begin_ip && $ipUser <= $value->end_ip) {
                            $this->_id = $record->id;
                            $this->errorCode = self::ERROR_NONE;
                            $access = true;
                            break;
                        }
                    }
                } else {
                    $access = true;
                }
                if ($access) {
                    $this->_id = $record->id;
                    $this->errorCode = self::ERROR_NONE;
                } else {
                    $this->errorCode = self::ERROR_STATUS_LIMITED;
                }

            } elseif ($record->status == 'dismissed') {
                $this->errorCode = self::ERROR_STATUS_DISMISSED;
            } elseif ($record->status == 'none') {
                $this->errorCode = self::ERROR_STATUS_NO_ACTIVE;
            } elseif ($record->status == 'noActivated') {
                $this->errorCode = self::ERROR_STATUS_NO_ACTIVATE;
            } else {
                $this->_id = $record->id;
                $this->errorCode = self::ERROR_NONE;
            }
        } else {
            $this->errorCode = self::ERROR_END_TRIAL;
        }

		if ($this->errorCode == 0) {
            $record->last_ip = $_SERVER["REMOTE_ADDR"];
            $record->last_login = new CDbExpression('NOW()');
            $record->save();
        }
		return !$this->errorCode;
	}

	/**
	 * \brief Возвращает id аунтифицированного пользователя
	 */
	public function getId()
	{
		return $this->_id;
	}
}
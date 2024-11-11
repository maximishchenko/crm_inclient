<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $email;
	public $password;
	public $rememberMe;
	//public $ad_id;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('email', 'required', 'message' => 'E-mail неверный'),
			array('password', 'required', 'message' => 'Пароль неверный'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'Remember me next time',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function login()
	{
		if (!$this->hasErrors()) {
			$this->_identity = new MyCUserIdentity($this->email, $this->password);

			if (!$this->_identity->authenticate()) {
				if ($this->_identity->errorCode === MyCUserIdentity::ERROR_STATUS_NO_ACTIVATE) {

					$this->addError('password', 'Аккаунт не активирован, пожалуйста проверьте почту');
				} elseif ($this->_identity->errorCode === MyCUserIdentity::ERROR_STATUS_BANNED) {
					$this->addError('password', 'Аккаунт забанен, свяжитесь с администратором');
				} elseif ($this->_identity->errorCode === MyCUserIdentity::ERROR_STATUS_LIMITED) {
                    $this->addError('password', 'Авторизация запрещена, свяжитесь с администратором');
                } elseif ($this->_identity->errorCode === MyCUserIdentity::ERROR_STATUS_DISMISSED) {
                    $this->addError('password', 'Вы уволены');
                } elseif ($this->_identity->errorCode === MyCUserIdentity::ERROR_STATUS_NO_ACTIVE) {
                    $this->addError('password', 'Ваша учетная запись отключена');
                } else {
					$this->addError('password', 'Неверно указан логин или пароль');
				}
			}
		}
		if ($this->_identity->errorCode === MyCUserIdentity::ERROR_NONE) {
			$duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
			Yii::app()->user->login($this->_identity, $duration);
			return true;
		} else {
			return false;
		}
	}

}

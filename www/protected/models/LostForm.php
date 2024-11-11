<?php
/**
 * \brief Модель формы восстановления пароля
 * */
class LostForm extends CFormModel
{
	///Вводимый e-mail
	public $email;

	/**
	 * \brief Правила валидации
	 * */
	public function rules()
	{
		return array(
			array('email', 'required', 'message' => 'Обязательное поле'),
			array('email', 'length', 'min' => 4, 'max' => 255),
			array('email', 'email', 'message' => 'Введите валидный e-mail адрес'),
		);
	}

	/**
	 * \brief Функция отправления нового пароля на почту контакта
	 * \details Реализована на основе метода Генерации нового пароля
	 */
	public function sendPassword()
	{
		if (!$this->hasErrors()) {
			$record = Users::model()->findByAttributes(array('email' => $this->email));

			if ($record === null) {
				$this->addError('email', 'Пользователь с указанным e-mail не найден+');
			} else {
				$newPassword = $record->changePassword();

				if (isset($newPassword) && $newPassword) {
					$email = Yii::app()->email;
					$email->AddAddress($record->email);
					$email->Subject =  'Восстановление пароля';
					$email->Body = 'Ваш новый пароль: ' . $newPassword;
					$email->send();

					return true;
				} else {
					$this->addError('email', 'Ошибка смены пароля, попробуйте позже');
				}
			}
		} else {
			return false;
		}
	}
}
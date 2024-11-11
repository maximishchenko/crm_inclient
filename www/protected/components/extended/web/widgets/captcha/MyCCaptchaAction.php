<?php

class MyCCaptchaAction extends CCaptchaAction
{
	public function run()
	{
		$segs = explode('/', Yii::app()->request->getPathInfo() . '/');
		if (in_array(self::REFRESH_GET_VAR, $segs)) {
			$code = $this->getVerifyCode(true);
			echo CJSON::encode(array(
				'hash1' => $this->generateValidationHash($code),
				'hash2' => $this->generateValidationHash(strtolower($code)),
				// we add a random 'v' parameter so that FireFox can refresh the image
				// when src attribute of image tag is changed
				'url' => $this->getController()->createUrl($this->getId(), array('v' => uniqid())),
			));
		} else
			$this->renderImage($this->getVerifyCode(true));
		Yii::app()->end();
	}
}
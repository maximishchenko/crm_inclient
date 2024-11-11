<?
if (file_exists(Yii::getPathOfAlias('webroot') . '/public/images/logo_' . $currentLang . '.png'))
	echo CHtml::link('<img src="/public/images/logo_' . $currentLang . '.png" alt=""/>', '/');
else {
	$model=Languages::model()->findAll('active=1');
	$finded=false;
	foreach ($model as $element){
		if (file_exists(Yii::getPathOfAlias('webroot') . '/public/images/logo_' . $element->code . '.png')){
			echo CHtml::link('<img src="/public/images/logo_' . $element->code . '.png" alt=""/>', '/');
			$finded=true;
			break;
		}
	}
	if (!$finded)
		echo CHtml::link('<img src="/public/images/logo_en.png" alt=""/>', '/');
}
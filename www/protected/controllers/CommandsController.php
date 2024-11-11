<?php

class CommandsController extends MyCController
{

    public function beforeAction($a)
    {
        parent::beforeAction($a);

        return true;
    }

    public function actionUpdate()
    {
        if (UsersRoles::model()->findByAttributes(['user_id' => Yii::app()->user->id])->itemname == 'admin') {
            if (!Version::model()->findByAttributes(['version' => '1.0.2.3'])) {
                $fields = AdditionalFields::model()->findAllByAttributes(['type' => 'varchar']);
                $fieldsUpdate = [];
                foreach ($fields as $field) {
                    $fieldsUpdate[] = $field->table_name;
                }
                foreach ($fieldsUpdate as $field) {
                    Yii::app()->db->createCommand("ALTER TABLE `additional_fields_values` CHANGE `$field` `$field` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;")->execute();
                }
                Yii::app()->db->createCommand('INSERT INTO `version` (`version`) VALUES ("1.0.2.3")')->execute();
                echo 'Данные обновлены';
            } else {
                echo 'Обновление не требуется';
            }
        } else {
            echo 'Вы не авторизированы';
        }
    }

}
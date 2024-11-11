<?php

/**
 * This is the model class for table "appearance".
 *
 * The followings are the available columns in table 'appearance':
 * @property integer $id
 * @property string $menu_name
 * @property string $menu_link
 * @property string $footer_name
 * @property string $footer_link
 * @property string $logo
 * @property string $background_image_type
 * @property string $background_image_type_value
 */
class Appearance extends MainAppearance
{
	/**
	 * @return string the associated database table name
	 */

    public $image;
    public $customImage;

	public function tableName()
	{
		return 'appearance';
	}

    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            ['menu_name', 'required', 'message' => 'Поле "Наименование в меню" обязательное'],
            ['background_image_type', 'required', 'message' => 'Поле "Фон на входе" обязательное'],
            ['logo', 'required', 'message' => 'Не загружен логотип'],

            ['background_image_type_value', 'required', 'message' => 'Поле "Ссылка для фона" обязательно', 'on' => 'scenarioBackgroundLink, scenarioBackgroundLinkAndLoadImage'],
            ['image', 'file', 'types'=>'jpg, png, jpeg, svg', 'allowEmpty' => true, 'on' => 'loadImage, scenarioBackgroundLinkAndLoadImage, scenarioCustomImageAndLoadImage'],
            ['customImage', 'file', 'types'=>'jpg, png, jpeg, svg', 'allowEmpty' => true, 'on' => 'scenarioCustomImage, scenarioCustomImageAndLoadImage'],

            array('menu_name, menu_link, logo, background_image_type, background_image_type_value', 'length', 'max'=>256),
            array('id, menu_name, menu_link, footer_name, footer_link, logo, background_image_type, background_image_type_value', 'safe', 'on'=>'search'),
        );
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}

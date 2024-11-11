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
class MainAppearance extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */

	public function tableName()
	{
		return 'appearance';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            ['menu_name', 'required', 'message' => 'Поле "Наименование в меню" обязательное'],
            ['background_image_type', 'required', 'message' => 'Поле "Фон на входе" обязательное'],
            ['logo', 'required', 'message' => 'Не загружен логотип'],
            array('menu_name, menu_link, logo, background_image_type, background_image_type_value', 'length', 'max'=>256),
            array('id, menu_name, menu_link, footer_name, footer_link, logo, background_image_type, background_image_type_value', 'safe', 'on'=>'search'),
            array('image', 'file', 'types'=>'jpg, png, jpeg, svg', 'allowEmpty' => true, 'on' => 'loadImage'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'menu_name' => 'Menu Name',
			'menu_link' => 'Menu Link',
			'footer_name' => 'Footer Name',
			'footer_link' => 'Footer Link',
			'logo' => 'Logo',
			'background_image_type' => 'Background Image Type',
			'background_image_type_value' => 'Background Image Type Value',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('menu_name',$this->menu_name,true);
		$criteria->compare('menu_link',$this->menu_link,true);
		$criteria->compare('footer_name',$this->footer_name,true);
		$criteria->compare('footer_link',$this->footer_link,true);
		$criteria->compare('logo',$this->logo,true);
		$criteria->compare('background_image_type',$this->background_image_type,true);
		$criteria->compare('background_image_type_value',$this->background_image_type_value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Appearance the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

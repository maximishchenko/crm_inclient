<?php

/**
 * This is the model class for table "deals_reason".
 *
 * The followings are the available columns in table 'deals_reason':
 * @property integer $id
 * @property string $name
 * @property integer $weight
 * @property integer $is_default
 *
 * The followings are the available model relations:
 * @property DealAndReason[] $dealAndReasons
 */
class MainDealsReason extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'deals_reason';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			['name', 'required', 'message' => '"Название" причины не может иметь пустое значение'],
			['weight', 'required', 'message' => '"Номер" причины не может иметь пустое значение'],
			['name', 'unique', 'message' => 'Причины с таким название уже существует'],
			array('weight, is_default', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, weight, is_default', 'safe', 'on'=>'search'),
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
			'dealAndReasons' => array(self::HAS_MANY, 'DealAndReason', 'deals_reason_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'weight' => 'Weight',
			'is_default' => 'Is Default',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('weight',$this->weight);
		$criteria->compare('is_default',$this->is_default);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DealsReason the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function checkDuplicateWeight() {
        $criteria=new CDbCriteria;
        $criteria->select = 'weight, COUNT(`weight`) AS `count`';
        $criteria->group = 'weight';
        $criteria->having = 'count(weight) > 1';
        $reasons = DealsReason::model()->findAll($criteria);
        return count($reasons) == 0;
    }
}

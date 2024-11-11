<?php

/**
 * This is the model class for table "deal_and_reason".
 *
 * The followings are the available columns in table 'deal_and_reason':
 * @property integer $id
 * @property integer $deals_id
 * @property integer $deals_reason_id
 *
 * The followings are the available model relations:
 * @property Deals $deals
 * @property DealsReason $dealsReason
 */
class MainDealAndReason extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'deal_and_reason';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('deals_id, deals_reason_id', 'required'),
			array('deals_id, deals_reason_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, deals_id, deals_reason_id', 'safe', 'on'=>'search'),
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
			'deals' => array(self::BELONGS_TO, 'Deals', 'deals_id'),
			'dealsReason' => array(self::BELONGS_TO, 'DealsReason', 'deals_reason_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'deals_id' => 'Deals',
			'deals_reason_id' => 'Deals Reason',
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
		$criteria->compare('deals_id',$this->deals_id);
		$criteria->compare('deals_reason_id',$this->deals_reason_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DealAndReason the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

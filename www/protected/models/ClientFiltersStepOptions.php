<?php

/**
 * This is the model class for table "client_filters_step_options".
 *
 * The followings are the available columns in table 'client_filters_step_options':
 * @property integer $id
 * @property integer $client_filters_id
 * @property integer $steps_options_id
 *
 * The followings are the available model relations:
 * @property ClientFilters $clientFilters
 * @property StepsOptions $stepsOptions
 */
class ClientFiltersStepOptions extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'client_filters_step_options';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_filters_id', 'required'),
			array('client_filters_id, steps_options_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, client_filters_id, steps_options_id', 'safe', 'on'=>'search'),
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
			'clientFilters' => array(self::BELONGS_TO, 'ClientFilters', 'client_filters_id'),
			'stepsOptions' => array(self::BELONGS_TO, 'StepsOptions', 'steps_options_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'client_filters_id' => 'Client Filters',
			'steps_options_id' => 'Steps Options',
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
		$criteria->compare('client_filters_id',$this->client_filters_id);
		$criteria->compare('steps_options_id',$this->steps_options_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClientFiltersStepOptions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
<?php

/**
 * This is the model class for table "client_filters_block_info".
 *
 * The followings are the available columns in table 'client_filters_block_info':
 * @property integer $id
 * @property integer $client_filters_id
 * @property integer $client_filters_block_type_id
 * @property integer $is_id_client
 * @property integer $is_last_change
 * @property integer $is_create_date
 * @property integer $is_responsible
 * @property integer $is_step
 * @property integer $is_option_step
 *
 * The followings are the available model relations:
 * @property ClientFilters $clientFilters
 * @property ClientFiltersBlockType $clientFiltersBlockType
 */
class ClientFiltersBlockInfo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'client_filters_block_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_filters_id, client_filters_block_type_id', 'required'),
			array('client_filters_id, client_filters_block_type_id, is_id_client, is_last_change, is_create_date, is_responsible, is_step, is_option_step', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, client_filters_id, client_filters_block_type_id, is_id_client, is_last_change, is_create_date, is_responsible, is_step, is_option_step', 'safe', 'on'=>'search'),
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
			'clientFiltersBlockType' => array(self::BELONGS_TO, 'ClientFiltersBlockType', 'client_filters_block_type_id'),
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
			'client_filters_block_type_id' => 'Client Filters Block Type',
			'is_id_client' => 'Is Id Client',
			'is_last_change' => 'Is Last Change',
			'is_create_date' => 'Is Create Date',
			'is_responsible' => 'Is Responsible',
			'is_step' => 'Is Step',
			'is_option_step' => 'Is Option Step',
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
		$criteria->compare('client_filters_block_type_id',$this->client_filters_block_type_id);
		$criteria->compare('is_id_client',$this->is_id_client);
		$criteria->compare('is_last_change',$this->is_last_change);
		$criteria->compare('is_create_date',$this->is_create_date);
		$criteria->compare('is_responsible',$this->is_responsible);
		$criteria->compare('is_step',$this->is_step);
		$criteria->compare('is_option_step',$this->is_option_step);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClientFiltersBlockInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

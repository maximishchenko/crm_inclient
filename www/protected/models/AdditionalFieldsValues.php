<?php

/**
 * This is the model class for table "additional_fields_values".
 *
 * The followings are the available columns in table 'additional_fields_values':
 * @property integer $id
 * @property integer $client_id
 *
 * The followings are the available model relations:
 * @property Clients $client
 */
class AdditionalFieldsValues extends MainAdditionalFieldsValues
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'additional_fields_values';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_id', 'required'),
			array('client_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
            array('email', 'checkDuplicate'),
            // @todo Please remove those attributes that should not be searched.
			array('id, client_id', 'safe', 'on'=>'search'),
		);
	}

    public function checkDuplicate()
    {
        if (Settings::getValueSettingByCode('duplicateAdditionalFieldsEnabled')) {
            $fields = array_column(AdditionalFields::model()->findAllByAttributes(['unique' => 1]), 'table_name');
            if (count($fields) > 0) {
                $criteria = new CDbCriteria;
                foreach ($fields as $key => $field) {
                    if (trim($this->$field) != '') {
                        $criteria->addCondition($field . ' = :field' . $key);
                        $criteria->params[':field' . $key] = $this->$field;
                    } else {
                        return true;
                    }
                }
                if (!$this->getIsNewRecord()) {
                    $criteria->addCondition('client_id != ' . $this->client_id);
                }
                if (AdditionalFieldsValues::model()->count($criteria) > 0) {
                    $allNameFields = Settings::getAllUniqueDefaultFields();
                    $allNameFieldsParse = [];
                    foreach ($allNameFields as $key => $val) {
                        if ($val['unique']) {
                            if (in_array($val['table_name'], $fields)) {
                                $this->addError($val['table_name'], 'Unique');
                                $allNameFieldsParse[] = $val['name'];
                            }
                        }
                    }
                    $this->addError('duplicate', implode(', ', $allNameFieldsParse) . ' - эти данные записаны в другом контакте. Измените данные и попробуйте снова');
                }
            }
        }
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'client' => array(self::BELONGS_TO, 'Clients', 'client_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'client_id' => 'Client',
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
		$criteria->compare('client_id',$this->client_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AdditionalFieldsValues the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

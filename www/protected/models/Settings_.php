<?php

/**
 * This is the model class for table "settings".
 *
 * The followings are the available columns in table 'settings':
 * @property integer $id
 * @property string $param
 * @property string $value
 */
class Settings extends MainSettings
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('param, value', 'required'),
			array('param', 'length', 'max'=>255),
			array('value', 'checkValue'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, param, value', 'safe', 'on'=>'search'),
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

	public function checkValue()
    {
        switch ($this->param) {
            case 'sizeFile':
                if (!is_numeric($this->value)) {
                    $this->addError('value', 'Значение должно быть числом');
                }
                break;

        }
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'param' => 'Param',
			'value' => 'Value',
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
		$criteria->compare('param',$this->param,true);
		$criteria->compare('value',$this->value,true);

		$criteria->addInCondition('param', ['sizeFile', 'extFile']);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getNameParam($name = null)
    {
        $params = [
            'sizeFile' => 'Размер файла (Mb):',
            'extFile' => 'Расширение файла:',
            'timeZone' => 'Часовой пояс:'
        ];
        if ($name) {
            return $params[$name];
        } else {
            return $params;
        }


    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Settings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

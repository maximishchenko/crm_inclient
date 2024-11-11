<?php

/**
 * This is the model class for table "additional_fields".
 *
 * The followings are the available columns in table 'additional_fields':
 * @property integer $id
 * @property string $table_name
 * @property string $name
 * @property string $type
 * @property string size
 * @property string tip
 * @property string $default_value
 * @property integer $required
 * @property integer $weight
 * @property integer $section_id
 * @property integer $noEdit
 * @property integer $unique
 */
class AdditionalFields extends MainAdditionalFields
{

    public $defaultValueType;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'additional_fields';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
        return array(
            array('table_name, name, type, required, weight, section_id', 'required'),
            array('required, weight, section_id', 'numerical', 'integerOnly'=>true),
            array('table_name, name, size, tip', 'length', 'max'=>255),
            array('type', 'length', 'max'=>55),
            array('default_value', 'safe'),
            array('default_value', 'checkDefault'),
            array('weight', 'checkWeight'),
            array('name', 'checkName'),
            array('name', 'checkEdit'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, table_name, name, type, default_value, required, weight, size, section_id, tip, noEdit, unique', 'safe', 'on'=>'search'),
        );
    }

    public function checkEdit()
    {
        if (!$this->isNewRecord && $this->noEdit) {
            $this->addError('access', 'Невозможно редактировать');
        }
    }

    public function checkDefault()
    {
        switch ($this->type) {
            case 'int':
            case 'checkbox':
            case 'date':
                $value = str_replace([' ', '(', ')', '-', '+', '-'], '', $this->default_value);
                if ($value != '' && !is_numeric($value)) {
                    $this->addError('default_value', 'Значение должно быть числом');
                }
                break;
            case 'select':
                if (!($this->default_value && trim($this->default_value) != '')) {
                    $this->addError('default_value', 'Значение по умолчанию не может быть пустым');
                }
        }
    }

	public function checkName()
    {
        if ($this->isNewRecord) {
            if (AdditionalFields::model()->count('name = :name AND section_id = :sector',
                [
                    ':name' => $this->name,
                    ':sector' => $this->section_id,
                ])
            ) {
                $this->addError('name', 'Нельзя использовать одинаковые наименования полей');
            }
        } else {
            if (AdditionalFields::model()->count('name = :name && id != :id AND section_id = :sector',
                [
                    ':name' => $this->name,
                    ':id' => $this->id,
                    ':sector' => $this->section_id,
                ])
            ) {
                $this->addError('name', 'Нельзя использовать одинаковые наименования полей');
            }
        }
    }

    public function checkWeight()
    {
        if ($this->isNewRecord) {
            if (AdditionalFields::model()->count('weight = :weight AND section_id = :sector',
                [
                    ':weight' => $this->weight,
                    ':sector' => $this->section_id,
                ])) {
                $this->addError('weight', 'Такой порядок уже существует');
            }
        } else {
            if (AdditionalFields::model()->count('weight = :weight AND id != :id AND section_id = :sector',
                [
                    ':weight' => $this->weight,
                    ':id' => $this->id,
                    ':sector' => $this->section_id,
                ])
            ) {
                $this->addError('weight', 'Такой порядок уже существует');
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
            'section0' => array(self::BELONGS_TO, 'AdditionalFieldsSection', 'section_id'),
        );
    }

    /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'table_name' => 'Table Name',
			'name' => 'Наименование',
			'type' => 'Type',
			'default_value' => 'Значение по умолчанию',
			'required' => 'Required',
			'weight' => 'Порядок',
			'size' => 'Размер',
            'tip' => 'Подсказка',
            'noEdit' => 'noEdit',
            'unique' => 'unique',
		);
	}

	public function checkValidTypeSelectValue ($options = []) {
	    $defaultValue = array_column($options, 'default');
        if ($defaultValue && is_array($defaultValue)) {
            if (count($defaultValue) == 1) {

            } else {
                $this->addError('selectError', 'Опция по умолчанию должна быть в единственном экземпляре');
                return false;
            }
            $listName = array_column($options,'optionName');
            $listWeight = array_column($options,'optionWeight');
            $newListName = array_unique($listName);
            $newListWeight = array_unique($listWeight);

            if (count($newListName) != count($listName)) {
                $this->addError('selectError', 'Заполните все поля значений. Укажите уникальные значения');
                return false;
            } elseif (count($newListWeight) != count($listWeight)) {
                $this->addError('selectError', 'Номера опций должны быть уникальными');
                return false;
            }

            foreach ($newListName as $val) {
                if (trim($val) == '') {
                    $this->addError('selectError', 'Значения не могут быть пустыми');
                    return false;
                }
            }

            foreach ($newListWeight as $val) {
                if (trim($val) == '' || (int)$val == 0) {
                    $this->addError('selectError', 'Номера опций не могут быть пустыми или равны "0"');
                    return false;
                }

                if (!is_numeric($val)) {
                    $this->addError('selectError', 'Номера опций должны состоять только из цифр');
                    return false;
                }
            }
        } else {
            $this->addError('selectError', 'Не выбрана опция по умолчанию');
            return false;
        }
        return true;
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
	public function search($section = null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('table_name',$this->table_name,true);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('type',$this->type,true);
        $criteria->compare('default_value',$this->default_value,true);
        $criteria->compare('required',$this->required);
        $criteria->compare('weight',$this->weight);
        $criteria->compare('size',$this->size);
        $criteria->compare('tip',$this->tip);
        $criteria->compare('noEdit',$this->noEdit);
        $criteria->compare('unique',$this->unique);

        if (isset($section)) {
            $criteria->condition = 'section_id = :section';
            $criteria->params = [':section' => $section];
        }
		return new CActiveDataProvider($this, array(
			'criteria'=> $criteria,
            'pagination' => array(
                'pageSize'=> 25,
            ),
            'sort' => array(
                'defaultOrder' => 't.weight',),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AdditionalFields the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getTypeField()
    {
        return [
		    'varchar' => 'Текст',
            'int' => 'Число', 
            'select' => 'Селектор',			
            'checkbox' => 'Чекбокс',
            'date' => 'Дата'
            
        ];
    }

    public function getSizeText()
    {
        return ['1/3' => '1/3', '1/2' => '1/2', '1/1' => '1/1'];
    }

    protected function beforeDelete()
    {
        if ($this->noEdit) {
            return false;
        }

        parent::beforeDelete();
        return true;
    }
}

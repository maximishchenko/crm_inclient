<?php

/**
 * This is the model class for table "additional_fields_section".
 *
 * The followings are the available columns in table 'additional_fields_section':
 * @property integer $id
 * @property string $location
 * @property string $name
 * @property integer $access
 * @property string $type_users
 * @property string $group_users
 * @property integer $weight
 * @property integer $noEdit
 */
class AdditionalFieldsSection extends MainAdditionalFieldsSection
{
	/**
	 * @return string the associated database table name
	 */

	public $data;
	public $countField;
	public $groups;
    public $oldData;

    public function tableName()
    {
        return 'additional_fields_section';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, access, weight', 'required'),
            array('weight', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>255),
            array('access', 'length', 'max'=>50),
            array('weight', 'checkWeight'),
            array('access', 'checkGroups'),
            array('name', 'checkName'),
            array('name', 'checkEdit'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, access, weight, noEdit', 'safe', 'on'=>'search'),
        );
    }

    public function checkEdit()
    {
        if (!$this->isNewRecord && $this->noEdit && ($this->access != $this->oldData['access'] || $this->weight != $this->oldData['weight'])) {
            $this->addError('access', 'Невозможно редактировать');
        }
    }
    
    public function checkGroups()
    {
        if ($this->access == 'groups') {
            if (array_search(1, $this->groups) === false) {
                $this->addError('access', 'Выберите группу');
            }
        }
    }

    public function checkWeight()
    {
        if ($this->isNewRecord) {
            if (AdditionalFieldsSection::model()->count('weight = :weight',
                [
                    ':weight' => $this->weight,
                ])
            ) {
                $this->addError('weight', 'Такой порядок уже существует');
            }
        } else {
            if (AdditionalFieldsSection::model()->count('weight = :weight && id != :id',
                [
                    ':weight' => $this->weight, ':id' => $this->id,
                ])
            ) {
                $this->addError('weight', 'Такой порядок уже существует');
            }
        }
    }

    public function checkName()
    {
        if ($this->isNewRecord) {
            if (AdditionalFieldsSection::model()->count('name = :name',
                [
                    ':name' => $this->name,
                ])
            ) {
                $this->addError('name', 'Такое наименование уже существует');
            }
        } else {
            if (AdditionalFieldsSection::model()->count('name = :name && id != :id',
                [
                    ':name' => $this->name, ':id' => $this->id,
                ])
            ) {
                $this->addError('name', 'Такое наименование уже существует');
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
            'additionalFields' => array(self::HAS_MANY, 'AdditionalFields', 'section_id'),
            'sectorInGroups' => array(self::HAS_MANY, 'SectorInGroups', 'sector_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Наименование',
            'access' => 'Access',
            'weight' => 'Порядок',
            'noEdit' => 'noEdit',
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
        $criteria->compare('access',$this->access,true);
        $criteria->compare('weight',$this->weight);
        $criteria->compare('noEdit',$this->noEdit);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AdditionalFieldsSection the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    public static function getAccess()
    {
        return ['all' => 'Все пользователи', 'groups' => 'Группы пользователей'];
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

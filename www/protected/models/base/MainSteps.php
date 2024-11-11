<?php
/**
 * This is the model class for table "steps".
 *
 * The followings are the available columns in table 'steps':
 * @property integer $id
 * @property integer $steps_type_id
 * @property string $name
 * @property integer $weight
 * @property integer $selected_default
 *
 * The followings are the available model relations:
 * @property StepsType $stepsType
 * @property StepsInClients[] $stepsInClients
 * @property StepsOptions[] $stepsOptions
 */
class MainSteps extends CActiveRecord
{
    public $countType = null;
    public $isNewRecord = true;
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'steps';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that 
        // will receive user inputs. 
        return array(
            array('steps_type_id, name, weight, selected_default', 'required'),
            array('steps_type_id, weight, selected_default', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>255),
                 ['name','checkValidName'],
                 ['weight','checkValidWeight'],
            // The following rule is used by search(). 
            // @todo Please remove those attributes that should not be searched. 
            array('id, steps_type_id, name, weight, selected_default', 'safe', 'on'=>'search'),
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
            'stepsType' => array(self::BELONGS_TO, 'StepsType', 'steps_type_id'),
            'stepsInClients' => array(self::HAS_MANY, 'StepsInClients', 'steps_id'),
            'stepsInDeals' => array(self::HAS_MANY, 'StepsInDeals', 'steps_id'),
            'stepsOptions' => array(self::HAS_MANY, 'StepsOptions', 'steps_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'steps_type_id' => 'Steps Type',
            'name' => 'Наименование',
            'weight' => 'Порядок',
            'selected_default' => 'Selected Default',
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
        $criteria->compare('steps_type_id',$this->steps_type_id);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('weight',$this->weight);
        $criteria->compare('selected_default',$this->selected_default);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Steps the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function checkValidSelectValue($options = []) {
        $listName = array_column($options, 'name');
        $listWeight = array_column($options, 'weight');
        $newListName = array_unique($listName);
        $newListWeight = array_unique($listWeight);

        if (count($listName) == 0 ||  count($listWeight) == 0) {
            $this->addError('selectError', 'Нет ни одного этапа');
            return false;
        }
        if (count($newListName) != count($listName)) {
            $this->addError('selectError', 'Заполните все поля. Этапы должны быть разными');
            return false;
        } elseif (count($newListWeight) != count($listWeight)) {
            $this->addError('selectError', 'Порядок этапов должен быть разный');
            return false;
        }

        foreach ($newListName as $val) {
            if (trim($val) == '') {
                $this->addError('selectError', 'Этапы не могут быть пустыми');
                return false;
            }
        }

        foreach ($newListWeight as $val) {
            if (trim($val) == '' || (int)$val == 0) {
                $this->addError('selectError', 'Порядок этапа не должен быть пустым или равным "0"');
                return false;
            }

            if (!is_numeric($val)) {
                $this->addError('selectError', 'Порядок этапа должен состоять только из цифр');
                return false;
            }
        }
        return true;
    }

    public function checkValidName() {
        if (($this->isNewRecord && !Steps::model()->find('name = :NAME && steps_type_id = :TYPE',
                    [':NAME' => $this->name, ':TYPE' => $this->steps_type_id])) ||
            (!$this->isNewRecord && !Steps::model()->find('name = :NAME && id != :ID && steps_type_id = :TYPE',
                    [':NAME' => $this->name, ':ID' => $this->id, ':TYPE' => $this->steps_type_id]))) {
            return true;
        }
        $this->addError('name', 'Наименование должно быть уникальным');
        return false;
    }

    public function checkValidWeight() {
        if (($this->isNewRecord && !Steps::model()->find('weight = :WE && steps_type_id = :TYPE',
                    [':WE' => $this->weight, ':TYPE' => $this->steps_type_id])) ||
            (!$this->isNewRecord && !Steps::model()->find('weight = :WE && id != :ID && steps_type_id = :TYPE',
                    [':WE' => $this->weight, ':ID' => $this->id, ':TYPE' => $this->steps_type_id]))) {
            return true;
        }
        $this->addError('weight', 'Значение порядка этапа должно быть уникальным');
        return false;
    }
}
<?php

/**
 * This is the model class for table "labels".
 *
 * The followings are the available columns in table 'labels':
 * @property integer $id
 * @property integer $type_id
 * @property string $name
 * @property string $color
 *
 * The followings are the available model relations:
 * @property LabelsType $type
 * @property LabelsInActions[] $labelsInActions
 * @property LabelsInClients[] $labelsInClients
 * @property LabelsInDeals[] $labelsInDeals
 */
class MainLabels extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */

    public $countType = null;

    public function tableName()
    {
        return 'labels';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('type_id, name, color, color_text', 'required'),
            array('type_id', 'numerical', 'integerOnly'=>true),
            array('name, color, color_text', 'length', 'max'=>255),
            ['color, color_text', 'match', 'pattern' => '/#[a-f0-9]{6}\b/i', 'message' => 'Некорректное значение в поле "Заливка"'],
            [['name'], 'duplicate'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, type_id, name, color, color_text', 'safe', 'on'=>'search'),
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
            'type' => array(self::BELONGS_TO, 'LabelsType', 'type_id'),
            'labelsInActions' => array(self::HAS_MANY, 'LabelsInActions', 'label_id'),
            'labelsInClients' => array(self::HAS_MANY, 'LabelsInClients', 'label_id'),
            'labelsInDeals' => array(self::HAS_MANY, 'LabelsInDeals', 'label_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'type_id' => 'Type',
            'name' => 'Наименование',
            'color' => 'Заливка',
            'color_text' => 'Цвет текста',
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
        $criteria->compare('type_id',$this->type_id);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('color',$this->color,true);
        $criteria->compare('color_text',$this->color_text,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public function duplicate () {
        $listLabels = Labels::model()->findAll('name = :NAME AND type_id = :TYPE',
            [':NAME' => $this->name, ':TYPE' => $this->type_id]);
        $count = count($listLabels);
        if (!$this->id && $count > 0 || $count > 1 ||  $count == 1 && $listLabels[0]->id != $this->id ) {
            $this->addError('name', 'Метка с таким именем уже есть в базе данных');
            return false;
        }
        return true;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Labels the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}

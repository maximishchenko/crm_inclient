<?php

/**
 * This is the model class for table "steps_options".
 *
 * The followings are the available columns in table 'steps_options':
 * @property integer $id
 * @property string $name
 * @property string $color
 * @property integer $weight
 * @property integer $steps_id
 *
 * The followings are the available model relations:
 * @property Steps $steps
 */
class MainStepsOptions extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'steps_options';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, color, weight, steps_id', 'required'),
            array('weight, steps_id', 'numerical', 'integerOnly'=>true),
            array('name, color', 'length', 'max'=>255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, color, weight, steps_id', 'safe', 'on'=>'search'),
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
            'steps' => array(self::BELONGS_TO, 'Steps', 'steps_id'),
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
            'color' => 'Color',
            'weight' => 'Weight',
            'steps_id' => 'Steps',
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
        $criteria->compare('color',$this->color,true);
        $criteria->compare('weight',$this->weight);
        $criteria->compare('steps_id',$this->steps_id);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return StepsOptions the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
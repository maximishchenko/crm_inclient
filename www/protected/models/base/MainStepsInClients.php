<?php

/**
 * This is the model class for table "steps_in_clients".
 *
 * The followings are the available columns in table 'steps_in_clients':
 * @property integer $id
 * @property integer $clients_id
 * @property integer $steps_id
 * @property integer $selected_option_id
 *
 * The followings are the available model relations:
 * @property Clients $clients
 * @property Steps $steps
 */
class MainStepsInClients extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'steps_in_clients';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('clients_id, steps_id, selected_option_id', 'required'),
            array('clients_id, steps_id, selected_option_id', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, clients_id, steps_id, selected_option_id', 'safe', 'on'=>'search'),
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
            'clients' => array(self::BELONGS_TO, 'Clients', 'clients_id'),
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
            'clients_id' => 'Clients',
            'steps_id' => 'Steps',
            'selected_option_id' => 'Selected Option',
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
        $criteria->compare('clients_id',$this->clients_id);
        $criteria->compare('steps_id',$this->steps_id);
        $criteria->compare('selected_option_id',$this->selected_option_id);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return StepsInClients the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
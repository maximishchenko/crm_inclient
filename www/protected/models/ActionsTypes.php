<?php

/**
 * This is the model class for table "actions_types".
 *
 * The followings are the available columns in table 'actions_types':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Actions[] $actions
 * @property Companies $company
 */
class ActionsTypes extends MainActionsTypes
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'actions_types';
    }

    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required', 'message' => 'Обязательное поле'),
            array('name', 'length', 'max'=>255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name', 'safe', 'on'=>'search'),
        );
    }

    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('name',$this->name,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

}
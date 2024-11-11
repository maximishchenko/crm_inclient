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
class Steps extends MainSteps
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'steps';
	}

    public function search($typeName = null)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('steps_type_id',$this->steps_type_id);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('weight',$this->weight);
        $criteria->compare('selected_default',$this->selected_default);

        if ($typeName && $type = StepsType::model()->find('name = :name', [':name' => $typeName])) {
            $criteria->condition = 'steps_type_id = ' . $type->id;
        }

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>25,
            ),
        ));
    }

    public function countTypes()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->alias = 'l';
        $criteria->select = "l.steps_type_id, type.name, COUNT(l.id) as countType";
        $criteria->join = 'INNER JOIN steps_type as type ON l.steps_type_id=type.id';
        $criteria->group = 'l.steps_type_id';
        return $criteria;
    }
}

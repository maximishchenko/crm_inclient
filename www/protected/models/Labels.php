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
class Labels extends MainLabels
{
	/**
	 * @return string the associated database table name
	 */

	public function tableName()
	{
		return 'labels';
	}

    public function search($typeName = null)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('type_id',$this->type_id);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('color',$this->color,true);
        $criteria->compare('color_text',$this->color_text,true);

        if ($typeName && $type = LabelsType::model()->find('name = :name', [':name' => $typeName])) {
            $criteria->condition = 'type_id = ' . $type->id;
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
        $criteria->select = "l.type_id, type.name, COUNT(l.id) as countType";
        $criteria->join = 'INNER JOIN labels_type as type ON l.type_id=type.id';
        $criteria->group = 'l.type_id';
        return $criteria;
    }
}

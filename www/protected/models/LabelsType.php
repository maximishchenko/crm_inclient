<?php

/**
 * This is the model class for table "labels_type".
 *
 * The followings are the available columns in table 'labels_type':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Labels[] $labels
 */
class LabelsType extends MainLabelsType
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'labels_type';
	}
}

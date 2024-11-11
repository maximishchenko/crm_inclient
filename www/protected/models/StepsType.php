<?php

/**
 * This is the model class for table "steps_type".
 *
 * The followings are the available columns in table 'steps_type':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Steps[] $steps
 */
class StepsType extends MainStepsType
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'steps_type';
	}
}

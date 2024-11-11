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
class StepsOptions extends MainStepsOptions
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'steps_options';
	}

}

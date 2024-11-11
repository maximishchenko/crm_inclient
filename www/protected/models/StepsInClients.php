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
class StepsInClients extends MainStepsInClients
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'steps_in_clients';
	}
}

<?php

/**
 * This is the model class for table "labels_in_actions".
 *
 * The followings are the available columns in table 'labels_in_actions':
 * @property integer $id
 * @property integer $label_id
 * @property integer $action_id
 *
 * The followings are the available model relations:
 * @property Actions $action
 * @property Labels $label
 */
class LabelsInActions extends MainLabelsInActions
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'labels_in_actions';
	}

    public function checkDuplicate($label_id, $action_id) {
        return !(LabelsInActions::model()->find('action_id = :CID && label_id = :LID',
            [
                ':CID' => $action_id,
                ':LID' => $label_id
            ])
        );
    }
}

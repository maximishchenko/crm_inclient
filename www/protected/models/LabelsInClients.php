<?php

/**
 * This is the model class for table "labels_in_clients".
 *
 * The followings are the available columns in table 'labels_in_clients':
 * @property integer $id
 * @property integer $label_id
 * @property integer $client_id
 *
 * The followings are the available model relations:
 * @property Clients $client
 * @property Labels $label
 */
class LabelsInClients extends MainLabelsInClients
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'labels_in_clients';
	}

	public function checkDuplicate($label_id, $client_id) {
	    return !(LabelsInClients::model()->find('client_id = :CID && label_id = :LID',
            [
                ':CID' => $client_id,
                ':LID' => $label_id
            ])
        );
    }
}

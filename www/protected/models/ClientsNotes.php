<?php

/**
 * This is the model class for table "UsersNotes".
 *
 * The followings are the available columns in table 'users_notes':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Users[] $Users
 */
class ClientsNotes extends MainClientsNotes
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'clients_notes';
	}
}

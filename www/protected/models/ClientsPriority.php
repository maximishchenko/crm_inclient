<?php

/**
 * This is the model class for table "clients_priority".
 *
 * The followings are the available columns in table 'clients_priority':
 * @property integer $id
 * @property string $name

 * The followings are the available model relations:
 * @property Clients[] $clients
 * @property Companies $company
 */
class ClientsPriority extends MainClientsPriority
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'clients_priority';
    }
}
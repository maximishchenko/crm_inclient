<?php

/**
 * This is the model class for table "actions_priority".
 *
 * The followings are the available columns in table 'actions_priority':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Actions[] $actions
 */
class ActionsPriority extends MainActionsPriority
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'actions_priority';
    }
}
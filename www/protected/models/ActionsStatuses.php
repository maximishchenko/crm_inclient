<?php

/**
 * This is the model class for table "actions_statuses".
 *
 * The followings are the available columns in table 'actions_statuses':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Actions[] $actions
 * @property Companies $company
 */
class ActionsStatuses extends MainActionsStatuses
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'actions_statuses';
    }
}
<?php

/**
 * This is the model class for table "activations".
 *
 * The followings are the available columns in table 'activations':
 * @property integer $id
 * @property string $type
 * @property string $key
 * @property integer $user_id
 * @property string $add_data
 * @property string $date
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class Activations extends MainActivations
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'activations';
    }
}
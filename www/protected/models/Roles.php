<?php

/**
 * This is the model class for table "roles".
 *
 * The followings are the available columns in table 'roles':
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $bizrule
 * @property string $data
 *
 * The followings are the available model relations:
 * @property Users[] $users
 */
class Roles extends MainRoles
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'roles';
    }
}
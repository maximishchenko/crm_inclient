<?php

/**
 * This is the model class for table "users_roles".
 *
 * The followings are the available columns in table 'users_roles':
 * @property string $itemname
 * @property integer $user_id
 * @property string $bizrule
 * @property string $data
 */
class UsersRoles extends MainUsersRoles
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'users_roles';
    }
}
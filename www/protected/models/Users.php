<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $password
 * @property string $first_name
 * @property string $second_name
 * @property string $patronymic
 * @property string $email
 * @property string $phone
 * @property string $country
 * @property string $city
 * @property string $last_login
 * @property string $last_ip
 * @property string $reg_date
 * @property string $status
 * @property integer $parent_id
 * @property string $accessToken
 * @property string $authKey
 * @property string $position
 * @property integer $common_access

 *
 * The followings are the available model relations:
 * @property Activations[] $activations
 * @property Clients[] $clients
 * @property Companies $company
 * @property Roles[] $roles
 */
class Users extends MainUsers
{
    public $role;
    public $password_again;
    public $clients_count;

    public $director_id;
    public $manager_id;
    public $keyword;
    public $verifyCode;
    public $data;
    public $newPassword;
    public $image;
    public $common_access_manager;
    public $common_access_director;

    CONST ACCESS_MANAGER_RESPONSIBLE  = 1;
    CONST ACCESS_MANAGER  = 2;
    CONST ACCESS_RESPONSIBLE  = 3;
    CONST ACCESS_DIRECTOR  = 4;
    CONST ACCESS_EMBAGRO  = 5;
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'users';
    }

    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('password, first_name, email, status', 'required', 'message' => 'Обязательное поле'),
            array('parent_id', 'numerical', 'integerOnly'=>true),
            array('password, first_name, second_name, patronymic, email, phone, country, city, last_login, status', 'length', 'max'=>255),
            array('last_ip', 'length', 'max'=>20),
            array('position, avatar', 'length', 'max' => 100),
            array('company_name', 'required', 'on' => 'edit_current_user', 'message' => 'Обязательное поле'),
            array('company_name, password, password_again', 'required', 'on' => 'registration', 'message' => 'Обязательное поле'),
            array('company_name, password, password_again', 'required', 'on' => 'register, register_no_company, registerNoCaptcha',
                'message' => 'Обязательное поле'),
            array('email', 'email', 'message' => 'Введите валидный e-mail адрес'),
            array('password_again', 'compare', 'compareAttribute' => 'password', 'on' => 'registration', 'message' => 'Пароли не совпадают'),
            array('password_again', 'compare', 'compareAttribute' => 'password',
                'on' => 'register, register_no_company, registerNoCaptcha',
                'message' => 'Пароли не совпадают'),
            array('email', 'unique', 'message' => 'Пользователь с таким e-mail уже существует'),
            array('reg_date', 'safe'),
            array('verifyCode', 'required', 'on' => 'register', 'except' => 'registerNoCaptcha'),
            array('image', 'file', 'types'=>'jpg, png, jpeg, svg', 'on' => 'avatar'),

            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, password, first_name, second_name, patronymic, email, phone, country, city, last_login, last_ip, reg_date, status, parent_id, position, avatar, common_access', 'safe', 'on'=>'search'),
        );
    }

    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'activations' => array(self::HAS_MANY, 'Activations', 'user_id'),
            'clients' => array(self::HAS_MANY, 'Clients', 'responsable_id'),
            'roles' => array(self::MANY_MANY, 'Roles', 'users_roles(user_id, itemname)'),
            'parent' => array(self::BELONGS_TO, 'Users', 'parent_id'),
            'userInGroups' => array(self::HAS_MANY, 'UserInGroups', 'user_id'),
            'userRights' => array(self::HAS_MANY, 'UserRight', 'user_id'),
            'usersFiles' => array(self::HAS_MANY, 'UsersFiles', 'user_id'),
            'commonAccess' => array(self::BELONGS_TO, 'Accesses', 'common_access'),
        );
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    protected function beforeSave()
    {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->reg_date = new CDbExpression('NOW()');
                $this->password = md5($this->password);
            }
        }
        return true;
    }

    public static function takeDirectorUsers($director_id, $getDirector = true)
    {
        if ($getDirector) {
            $user_ids = array($director_id);
        } else {
            $user_ids = array();
        }
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        switch ($user->roles[0]->name) {
            case 'manager':
                $managers = self::getUserAccess($user);
                break;
            case 'director':
                $managers = self::getUserAccess($user);
                break;
        }
        if (count($managers) > 0) {
            foreach ($managers as $manager) {
                $user_ids[] = $manager->id;
            }
        }
        return $user_ids;
    }
    public static function checkAccessUser($callUserRole, $targetUser, $callUser)
    {
        switch ($callUserRole) {
            case 'admin':
                return true;
                break;
            case 'director':
                if ($targetUser->parent_id == $callUser->id || $targetUser->id == $callUser->id) {
                    return true;
                } else {
                    return false;
                }
            case 'manager':
                if ($targetUser->id == $callUser->id) {
                    return true;
                } else {
                    return false;
                }
                break;
        }
    }

    public static function takeManagers($managerId, $onlyId = true)
    {
        $user_ids = array(Yii::app()->user->id);
        $user = Users::model()->findByPk(Yii::app()->user->id);
        if ($user->parent_id) {
            $managers = self::getUserAccess($user);
            foreach ($managers as $manager) {
                if ($onlyId) {
                    $user_ids[] = $manager->id;
                } else {
                    $user_ids[] = $manager;
                }
            }
        } else {
            $user_ids = array($managerId);
        }
        return $user_ids;
    }

    public static function getStatus($status)
    {
        switch ($status) {
            case 'active':
                return 'Активен';
            case 'none':
                return 'Неактивен';
            case 'limited':
                return 'Ограничен по IP';
            case 'dismissed':
                return 'Уволен';
            case 'noActivated':
                return 'Требует активации';
            default:
                return 'none';

        }
    }

    public static function getRole($role)
    {
        switch ($role) {
            case 'director':
                return 'Руководитель';
            case 'manager':
                return 'Менеджер';
            case 'admin':
                return 'Директор';
            default:
                return 'none';
        }
    }


    public function searchUsers($id = null)
    {

        $criteria = new CDbCriteria;

        $criteria->with = [
            'parent',
            'userInGroups.group',
        ];
        $criteria->together = true;
        $criteria->join = "join users_roles roles on t.id = roles.user_id";

        $criteria->select = [
            't.*'
        ];
        if ($this->keyword != null) {
            $criteria->addCondition('t.first_name LIKE "%' . $this->keyword . '%"', "OR");
            $criteria->addCondition('t.email LIKE "%' . $this->keyword . '%"', "OR");
            $criteria->addCondition('t.phone LIKE "%' . $this->keyword . '%"', "OR");
            $criteria->addCondition('t.position LIKE "%' . $this->keyword . '%"', "OR");
        }
        $this->parent_id != null && $this->parent_id != 'all' && $this->parent_id != 'no' ? $criteria->addCondition('t.parent_id=' . $this->parent_id) : '';
        $this->parent_id == 'no' ? $criteria->addCondition('t.parent_id=null') : '';

        $this->getRole($this->role) != 'none' ? $criteria->addCondition('roles.itemname = "' . $this->role . '"') : '';
        $this->getStatus($this->status) != 'none' ? $criteria->addCondition('t.status = "' . $this->status . '"') : '';

        if (isset($this->data['group']) && $this->data['group'] != '0') {
            $criteria->addInCondition( 'userInGroups.group_id', [$this->data['group']]);
        }
        $role = UsersRoles::model()->find('user_id=' . Yii::app()->user->id)->itemname;
        if ($role == 'director') {
            $criteria->addCondition('t.parent_id=' . Yii::app()->user->id);
        }

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 't.id',),
            'pagination' => array(
                'pageSize'=> 30,
            ),
        ));
    }

    public function changePassword()
    {
        if (!$this->isNewRecord) {
            $newPassword = substr(preg_replace('/[oO0Il1]/i', '', md5(rand() . rand() . rand() . time())), 0, 8);
            $this->password = md5($newPassword);

            if ($this->update('password')) {
                return $newPassword;
            } else {
                return false;
            }
        }

        return false;
    }

    public static function getAdminId()
    {
        $company_users = Users::model()->findAll();
        foreach($company_users as $user){
            $admin = UsersRoles::model()->find('user_id='.$user->id);
            if($admin->itemname == 'admin'){
                return $admin->user_id;
            }
        }
    }

    public function getAdminInCompany()
    {
        return Users::model()->with('roles')->find('roles.name = "admin"');
    }

    public static function getUserAccess($user, $onlyManagers = false, $onlyDirectors = false, $noThisUser = false, $directorId = null)
    {
        $additionalCondition = '';
        $additionalCondition2 = '';
        if ($onlyDirectors) {
            $additionalCondition = ' AND roles.name = "director"';
        }
        if ($onlyManagers) {
            $additionalCondition = ' AND roles.name = "manager"';
        }
        if ($noThisUser) {
            $additionalCondition2 = ' AND id != ' . $user->id;
        }
        $managers = [];
        switch ($user->roles[0]->name) {
            case 'manager':
                switch ($user->common_access) {
                    case self::ACCESS_MANAGER_RESPONSIBLE:
                        switch ($user->parent->roles[0]->name) {
                            case 'director':
                                $managers = Users::model()->with('roles')->findAll('(parent_id = :parent OR id = :parentID)' . $additionalCondition . $additionalCondition2,
                                    [
                                        ':parent' => $user->parent_id,
                                        ':parentID' => $user->parent_id,
                                    ]
                                );
                                break;
                            case 'admin':
                                $managers = Users::model()->with('roles')->findAll('(roles.name != "director" AND (parent_id = :parent OR id = :parentID))' . $additionalCondition . $additionalCondition2,
                                    [
                                        ':parent' => $user->parent_id,
                                        ':parentID' => $user->parent_id,
                                    ]
                                );
                                break;
                        }
                        break;
                    case self::ACCESS_MANAGER:
                        $managers = Users::model()->with('roles')->findAll('roles.name = "manager" AND parent_id = :parent' . $additionalCondition . $additionalCondition2,
                            [
                                ':parent' => $user->parent_id,
                            ]);
                        break;
                    case self::ACCESS_RESPONSIBLE:
                        $managers = Users::model()->with('roles')->findAll('id = :parentID' . $additionalCondition . $additionalCondition2,
                            [
                                ':parentID' => $user->parent_id,
                            ]);
                        break;
                }
                break;
            case 'director':
                switch ($user->common_access) {
                    case self::ACCESS_MANAGER:
                        $managers = Users::model()->with('roles')->findAll('parent_id = :director' . $additionalCondition . $additionalCondition2,
                            array(
                                ':director' => $user->id,
                            )
                        );
                        break;
                    case self::ACCESS_DIRECTOR:
                        $managers = Users::model()->with('roles')->findAll('(parent_id = :director OR id = :parentID)' . $additionalCondition . $additionalCondition2,
                            array(
                                ':director' => $user->id,
                                ':parentID' => $user->parent_id,
                            )
                        );
                        break;
                }
                break;
            case 'admin':
                $managers = Users::model()->with('roles')->findAll('1' . $additionalCondition . $additionalCondition2);
                break;
        }
        return $managers;
    }

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->second_name;
    }

    public static function getAccessUsersForFilter($user) {
        $managers = Users::getUserAccess($user, true, false, true);
        $directors = Users::getUserAccess($user, false, true, true);
        $users = array_merge($managers, $directors);
        $users [] = $user;// добавляем себя

        if ($user->roles[0]->name === 'director' && $user->common_access == 4) {
            $admin_id = (new Users())->getAdminId();
            $admin = Users::model()->findByPk($admin_id);
            $users [] = $admin;
        }

        if ($user->roles[0]->name === 'manager' && ($user->common_access == 1) || $user->common_access == 3) {
            $admin_id = (new Users())->getAdminId();

            if ($user->parent_id == $admin_id) {
                $admin = Users::model()->findByPk($admin_id);
                $users [] = $admin;
            }
        }
        return $users;
    }
}
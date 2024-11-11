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
 * @property string $position
 *
 * The followings are the available model relations:
 * @property Activations[] $activations
 * @property Clients[] $clients
 * @property Companies $company
 * @property Roles[] $roles
 */
class MainUsers extends CActiveRecord
{
    public $role;
    public $password_again;

    public $director_id;
    public $manager_id;
    public $keyword;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('password, first_name, email, status', 'required', 'message' => 'Обязательное поле'),
            array('parent_id', 'numerical', 'integerOnly'=>true),
            array('password, first_name, second_name, patronymic, email, phone, country, city, last_login, status', 'length', 'max'=>255),
            array('last_ip', 'length', 'max'=>20),
            array('password, password_again', 'required', 'on' => 'registration', 'message' => 'Обязательное поле'),
            array('email', 'email', 'message' => 'Введите валидный e-mail адрес'),
            array('password', 'compare', 'compareAttribute' => 'password_again', 'on' => 'registration', 'message' => 'Пароли не совпадают'),
            array('email', 'unique', 'message' => 'Пользователь с таким e-mail уже существует'),
            array('reg_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, password, first_name, second_name, patronymic, email, phone, country, city, last_login, last_ip, reg_date, status, parent_id, position', 'safe', 'on'=>'search'),
        );
    }

	/**
	 * @return array relational rules.
	 */
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
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */

    /*protected function beforeSave()
    {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->password = md5($this->password);

            }
        }
    }*/

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'password' => 'Password',
			'first_name' => 'First Name',
			'second_name' => 'Second Name',
			'patronymic' => 'Patronymic',
			'email' => 'Email',
			'phone' => 'Phone',
			'country' => 'Country',
			'city' => 'City',
			'last_login' => 'Last Login',
			'last_ip' => 'Last Ip',
			'reg_date' => 'Reg Date',
			'status' => 'Status',
			'parent_id' => 'Parent',
            'position' => 'Position',
            'common_access' => 'Common Access',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('second_name',$this->second_name,true);
		$criteria->compare('patronymic',$this->patronymic,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('last_login',$this->last_login,true);
		$criteria->compare('last_ip',$this->last_ip,true);
		$criteria->compare('reg_date',$this->reg_date,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('parent_id',$this->parent_id);
        $criteria->compare('position', $this->position);
        $criteria->compare('common_access',$this->common_access);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MainUsers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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
}

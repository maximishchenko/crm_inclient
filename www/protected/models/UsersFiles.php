<?php

/**
 * This is the model class for table "users_files".
 *
 * The followings are the available columns in table 'users_files':
 * @property integer $id
 * @property integer $user_id
 * @property integer $file_id
 *
 * The followings are the available model relations:
 * @property Files $file
 * @property Users $user
 */
class UsersFiles extends MainUsersFiles
{
	/**
	 * @return string the associated database table name
	 */

	public $keyword;
	public $user_group;
	public $type_user;
	public $start_date;
	public $stop_date;

	public function tableName()
	{
		return 'users_files';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, file_id', 'required'),
			array('user_id, file_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, file_id', 'safe', 'on'=>'search'),
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
			'file' => array(self::BELONGS_TO, 'Files', 'file_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'file_id' => 'File',
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
	public function search($userId = null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('file_id',$this->file_id);

        $criteria->with = [
            'user.userInGroups',
            'user.roles',
            'file',
        ];
		$criteria->together = true;
        $role = UsersRoles::model()->find('user_id=' . Yii::app()->user->id)->itemname;
        if ($role == 'director' && $userId != Yii::app()->user->id) {
            $criteria->addCondition('user.parent_id=' . Yii::app()->user->id);
        }

		if ($this->start_date) {
			$criteria->addCondition('UNIX_TIMESTAMP(file.date_upload) >= :starDate');
			$criteria->params[':starDate'] = strtotime($this->start_date);
		}
		if ($this->stop_date) {
			$criteria->addCondition('UNIX_TIMESTAMP(file.date_upload) <= :stopDate');
			$criteria->params[':stopDate'] = strtotime($this->stop_date . ':59');
		}

		if ($this->user_group) {
			$criteria->addInCondition('userInGroups.group_id', [$this->user_group]);
		}

		if ($this->type_user) {
			$criteria->addCondition('roles.name = :role');
			$criteria->params[':role'] = $this->type_user;
		}

        if ($userId) {
            $criteria->addCondition('t.user_id = :user');
            $criteria->params[':user'] = $userId;
        }

        if ($this->keyword != null) {
            $criteria->addCondition('file.name LIKE "%' . $this->keyword . '%"');
        }
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort' => array(
                'defaultOrder' => 'file.date_upload DESC',),
            'pagination' => array(
                'pageSize'=> 50,
            ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UsersFiles the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

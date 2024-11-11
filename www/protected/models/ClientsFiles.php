<?php

/**
 * This is the model class for table "clients_files".
 *
 * The followings are the available columns in table 'clients_files':
 * @property integer $id
 * @property integer $client_id
 * @property integer $file_id
 *
 * The followings are the available model relations:
 * @property Clients $client
 * @property Files $file
 */
class ClientsFiles extends MainClientsFiles
{
    public $director_id;
    public $manager_id;
    public $keyword;
    public $client_group_id;
    public $start_date;
    public $stop_date;
    public $responsable_id;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'clients_files';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_id, file_id', 'required'),
			array('client_id, file_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, client_id, file_id', 'safe', 'on'=>'search'),
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
			'client' => array(self::BELONGS_TO, 'Clients', 'client_id'),
			'file' => array(self::BELONGS_TO, 'Files', 'file_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'client_id' => 'Client',
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
	public function search($clientId = null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('file_id',$this->file_id);

		$criteria->with = [
		    'client',
		    'file',
        ];
		$criteria->params = [];
        $role = UsersRoles::model()->find('user_id = ' . Yii::app()->user->id)->itemname;
        if($role == 'director'){
            $users = new Users();
            $users_ids = $users->takeDirectorUsers(Yii::app()->user->id);
            $criteria->addInCondition('client.responsable_id', $users_ids);
        } elseif($role == 'manager') {
            $users = new Users();
            $users_ids = $users->takeManagers(Users::model()->findByPk(Yii::app()->user->id));
            $criteria->addInCondition('client.responsable_id', $users_ids);
        }

        if ($clientId) {
            $criteria->addCondition('client_id = :client');
			$criteria->params[':client'] = $clientId;
        }


		if ($this->responsable_id != null && $this->responsable_id != 'all' && $this->responsable_id != 'no') {
			$criteria->addCondition('client.responsable_id = :responsable_id');
			$criteria->params[':responsable_id'] = $this->responsable_id;
		}

		if ($this->start_date) {
			$criteria->addCondition('UNIX_TIMESTAMP(file.date_upload) >= :starDate');
			$criteria->params[':starDate'] =  strtotime($this->start_date);
		}
		if ($this->stop_date) {
			$criteria->addCondition('UNIX_TIMESTAMP(file.date_upload) <= :stopDate');
			$criteria->params[':stopDate'] =  strtotime($this->stop_date . ':59');
		}
		if ($this->client_group_id) {
			$criteria->addCondition('client.group_id = :group');
			$criteria->params[':group'] = $this->client_group_id;
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
	 * @return ClientsFiles the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

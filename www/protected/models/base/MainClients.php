<?php

/**
 * This is the model class for table "clients".
 *
 * The followings are the available columns in table 'clients':
 * @property integer $id
 * @property string $name
 * @property string $adres
 * @property string $email_1
 * @property string $email_2
 * @property string $phone_1
 * @property string $phone_2
 * @property string $site
 * @property string $vk_profile
 * @property string $icq
 * @property string $skype
 * @property string $description
 * @property string $question
 * @property string $creation_date
 * @property string $change_client_date
 * @property integer $responsable_id
 * @property integer $creator_id
 * @property integer $priority_id
 * @property integer $source_id
 * @property integer $goal_id
 * @property integer $city_id
 * @property integer $group_id
 *
 * The followings are the available model relations:
 * @property Actions[] $actions
 * @property ClientsCityes $city
 * @property Companies $company
 * @property Users $creator
 * @property ClientsGoals $goal
 * @property ClientsGroups $group
 * @property ClientsPriority $priority
 * @property Users $responsable
 * @property ClientsSources $source
 * @property Deals[] $deals
 */
class MainClients extends CActiveRecord
{
    public $director_id;
    public $manager_id;
    public $keyword;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'clients';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, creation_date, responsable_id, creator_id, priority_id, city_id, group_id', 'required', 'message' => 'Обязательное поле'),
            array('responsable_id, creator_id, priority_id, source_id, goal_id, city_id, group_id', 'numerical', 'integerOnly'=>true, 'message' => 'Значение данного поля должно быть числом'),
            array('name, adres, email_1, email_2, phone_1, phone_2, site, vk_profile, icq, skype', 'length', 'max'=>255),
            array('description, question', 'safe'),
            array('email_1, email_2', 'email', 'message' => 'Введите валидный e-mail адрес'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, adres, email_1, email_2, phone_1, phone_2, site, vk_profile, icq, skype, description, question, change_client_date, creation_date, responsable_id, creator_id, priority_id, source_id, goal_id, city_id, group_id', 'safe', 'on'=>'search'),
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
			'actions' => array(self::HAS_MANY, 'Actions', 'client_id'),
            'additionalFieldsValues' => array(self::HAS_MANY, 'AdditionalFieldsValues', 'client_id'),
			'city' => array(self::BELONGS_TO, 'ClientsCityes', 'city_id'),
			'creator' => array(self::BELONGS_TO, 'Users', 'creator_id'),
			'goal' => array(self::BELONGS_TO, 'ClientsGoals', 'goal_id'),
			'group' => array(self::BELONGS_TO, 'ClientsGroups', 'group_id'),
			'priority' => array(self::BELONGS_TO, 'ClientsPriority', 'priority_id'),
			'responsable' => array(self::BELONGS_TO, 'Users', 'responsable_id'),
			'source' => array(self::BELONGS_TO, 'ClientsSources', 'source_id'),
			'deals' => array(self::HAS_MANY, 'Deals', 'client_id'),
            'labelsInClients' => array(self::HAS_MANY, 'LabelsInClients', 'client_id'),
            'clientsFiles' => array(self::HAS_MANY, 'ClientsFiles', 'client_id'),
            'stepsInClients' => array(self::HAS_MANY, 'StepsInClients', 'clients_id'),
            'usersFiles' => array(self::HAS_MANY, 'UsersFiles', 'user_id'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'adres' => 'Adres',
			'email_1' => 'Email 1',
			'email_2' => 'Email 2',
			'phone_1' => 'Phone 1',
			'phone_2' => 'Phone 2',
			'site' => 'Site',
			'vk_profile' => 'Vk Profile',
			'icq' => 'Icq',
			'skype' => 'Skype',
			'description' => 'Description',
			'question' => 'Question',
			'creation_date' => 'Creation Date',
            'change_client_date' => 'Change Client Date',
			'responsable_id' => 'Responsable',
			'creator_id' => 'Creator',
			'priority_id' => 'Priority',
			'source_id' => 'Source',
			'goal_id' => 'Goal',
			'city_id' => 'City',
			'group_id' => 'Group',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('adres',$this->adres,true);
		$criteria->compare('email_1',$this->email_1,true);
		$criteria->compare('email_2',$this->email_2,true);
		$criteria->compare('phone_1',$this->phone_1,true);
		$criteria->compare('phone_2',$this->phone_2,true);
		$criteria->compare('site',$this->site,true);
		$criteria->compare('vk_profile',$this->vk_profile,true);
		$criteria->compare('icq',$this->icq,true);
		$criteria->compare('skype',$this->skype,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('question',$this->question,true);
		$criteria->compare('creation_date',$this->creation_date,true);
		$criteria->compare('change_client_date',$this->change_client_date,true);
		$criteria->compare('responsable_id',$this->responsable_id);
		$criteria->compare('creator_id',$this->creator_id);
		$criteria->compare('priority_id',$this->priority_id);
		$criteria->compare('source_id',$this->source_id);
		$criteria->compare('goal_id',$this->goal_id);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('group_id',$this->group_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MainClients the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

<?php

/**
 * This is the model class for table "actions".
 *
 * The followings are the available columns in table 'actions':
 * @property integer $id
 * @property string $text
 * @property integer $client_id
 * @property integer $action_type_id
 * @property integer $action_status_id
 * @property integer $action_priority_id
 * @property integer $responsable_id
 * @property string $creation_date
 * @property string $action_date
 * @property string $description
 *
 * The followings are the available model relations:
 * @property ActionsPriority $actionPriority
 * @property ActionsStatuses $actionStatus
 * @property ActionsTypes $actionType
 * @property Clients $client
 * @property Companies $company
 * @property Users $responsable
 */
class MainActions extends CActiveRecord
{

    public $director_id;
    public $manager_id;
    public $keyword;
    public $client_group_id;
    public $term;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'actions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('text, client_id, action_type_id, action_status_id, action_priority_id, responsable_id, creation_date, action_date', 'required', 'message' => 'Обязательное поле'),
            array('client_id, action_type_id, action_status_id, action_priority_id, responsable_id', 'numerical', 'integerOnly'=>true,  'message' => 'Значение данного поля должно быть числом'),
            array('text', 'length', 'max'=>255),
            array('description', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, text, client_id, action_type_id, action_status_id, action_priority_id, responsable_id, creation_date, action_date, description', 'safe', 'on'=>'search'),
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
			'actionPriority' => array(self::BELONGS_TO, 'ActionsPriority', 'action_priority_id'),
			'actionStatus' => array(self::BELONGS_TO, 'ActionsStatuses', 'action_status_id'),
			'actionType' => array(self::BELONGS_TO, 'ActionsTypes', 'action_type_id'),
			'client' => array(self::BELONGS_TO, 'Clients', 'client_id'),
			'responsable' => array(self::BELONGS_TO, 'Users', 'responsable_id'),
            'labelsInActions' => array(self::HAS_MANY, 'LabelsInActions', 'action_id'),
            'actionsFiles' => array(self::HAS_MANY, 'ActionsFiles', 'action_id'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'text' => 'Text',
			'client_id' => 'Client',
			'action_type_id' => 'Action Type',
			'action_status_id' => 'Action Status',
			'action_priority_id' => 'Action Priority',
			'responsable_id' => 'Responsable',
			'creation_date' => 'Creation Date',
			'action_date' => 'Action Date',
			'description' => 'Description',
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
		$criteria->compare('text',$this->text,true);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('action_type_id',$this->action_type_id);
		$criteria->compare('action_status_id',$this->action_status_id);
		$criteria->compare('action_priority_id',$this->action_priority_id);
		$criteria->compare('responsable_id',$this->responsable_id);
		$criteria->compare('creation_date',$this->creation_date,true);
		$criteria->compare('action_date',$this->action_date,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MainActions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

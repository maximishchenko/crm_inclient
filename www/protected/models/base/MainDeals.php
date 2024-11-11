<?php

/**
 * This is the model class for table "deals".
 *
 * The followings are the available columns in table 'deals':
 * @property integer $id
 * @property string $text
 * @property integer $client_id
 * @property integer $deal_category_id
 * @property integer $deal_priority_id
 * @property integer $deal_status_id
 * @property integer $responsable_id

 * @property string $creation_date
 * @property string $paid
 * @property string $balance
 * @property string $description
 *
 * The followings are the available model relations:
 * @property Clients $client
 * @property Companies $company
 * @property DealsCategories $dealCategory
 * @property DealsPriority $dealPriority
 * @property DealsStatuses $dealStatus
 * @property Users $responsable
 * @property integer $deal_type_id
 * @property string $change_date
 * @property string $closed_date
 */
class MainDeals extends CActiveRecord
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
		return 'deals';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('text, client_id, deal_category_id, deal_priority_id, deal_status_id, responsable_id, creation_date', 'required', 'message' => 'Необходимо заполнить поле'),
            array('client_id, deal_category_id, deal_priority_id, deal_status_id, responsable_id', 'numerical', 'integerOnly'=>true,  'message' => 'Значение данного поля должно быть числом'),
            array('text', 'length', 'max'=>255),
            array('paid, balance', 'length', 'max'=>20),
            array('description, change_date, closed_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, text, client_id, deal_category_id, deal_priority_id, deal_status_id, responsable_id, creation_date, paid, balance, description, deal_type_id, change_date, closed_date', 'safe', 'on'=>'search'),
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
			'dealCategory' => array(self::BELONGS_TO, 'DealsCategories', 'deal_category_id'),
			'dealPriority' => array(self::BELONGS_TO, 'DealsPriority', 'deal_priority_id'),
			'dealStatus' => array(self::BELONGS_TO, 'DealsStatuses', 'deal_status_id'),
			'responsable' => array(self::BELONGS_TO, 'Users', 'responsable_id'),
            'labelsInDeals' => array(self::HAS_MANY, 'LabelsInDeals', 'deal_id'),
            'dealsFiles' => array(self::HAS_MANY, 'DealsFiles', 'deal_id'),
            'dealType' => array(self::BELONGS_TO, 'DealsType', 'deal_type_id'),
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
			'deal_category_id' => 'Deal Category',
			'deal_priority_id' => 'Deal Priority',
			'deal_status_id' => 'Deal Status',
			'responsable_id' => 'Responsable',
			'creation_date' => 'Creation Date',
			'paid' => 'Paid',
			'balance' => 'Balance',
			'description' => 'Description',
            'deal_type_id' => 'Deal Type',
            'change_date' => 'Change Date',
            'closed_date' => 'Closed Date',
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
		$criteria->compare('deal_category_id',$this->deal_category_id);
		$criteria->compare('deal_priority_id',$this->deal_priority_id);
		$criteria->compare('deal_status_id',$this->deal_status_id);
		$criteria->compare('responsable_id',$this->responsable_id);
		$criteria->compare('creation_date',$this->creation_date,true);
		$criteria->compare('paid',$this->paid,true);
		$criteria->compare('balance',$this->balance,true);
		$criteria->compare('description',$this->description,true);
        $criteria->compare('deal_type_id',$this->deal_type_id);
        $criteria->compare('change_date',$this->change_date,true);
        $criteria->compare('closed_date',$this->closed_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MainDeals the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

<?php

/**
 * This is the model class for table "ads".
 *
 * The followings are the available columns in table 'ads':
 * @property integer $id
 * @property integer $type_id
 * @property integer $user_id
 * @property integer $status_id
 * @property integer $reject_id
 * @property integer $term_id
 * @property string $date_public
 * @property string $extension_date
 * @property integer $category_id
 * @property string $price
 * @property string $contact_name
 * @property string $contact_email
 * @property string $contact_phone
 * @property string $subject
 * @property string $description
 * @property integer $region_id
 * @property integer $city_id
 * @property integer $district_id
 * @property integer $metro_id
 * @property integer $view
 *
 * The followings are the available model relations:
 * @property AdCategories $category
 * @property Cities $city
 * @property Districts $district
 * @property Metro $metro
 * @property Regions $region
 * @property AdRejects $reject
 * @property AdStatuses $status
 * @property AdTerms $term
 * @property AdTypes $type
 * @property Users $user
 * @property AdsFiles[] $adsFiles
 * @property AdsMessages[] $adsMessages
 */
class Ads extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */

	public $search_text;
	public $file_paths;
	public $file_names;
	public $edit_type;

	public function tableName()
	{
		return 'ads';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type_id, status_id, reject_id, term_id, date_public, category_id, price, contact_name, contact_email, contact_phone, subject, description, city_id', 'required',  'message' => 'Обязательное поле'),
			array('type_id, user_id, status_id, reject_id, term_id, category_id, region_id, city_id, district_id, metro_id', 'numerical', 'integerOnly'=>true),
			array('price', 'length', 'max'=>8, 'message' => 'Цена слишком велика, максимальный размер 8 символа'),
			array('contact_name', 'length', 'max'=>24, 'message' => 'Текст слишкорм длинный, максимальный размер 24 символа'),
			array('contact_email','email','message' => 'Вы ввели неверный email адресс'),
			array('contact_phone', 'length', 'max'=>11, 'message' => 'Максимальная длина значаения для этого поля 11'),
            array('price','numerical','message' => 'Это поле может содержать только числа'),
            array('contact_phone','numerical','message' => 'Это поле может содержать только числа'),
         	array('subject', 'length', 'max'=>39, 'message' => 'Текст слишкорм длинный, максимальный размер 39 символов'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, extension_date, type_id, user_id, status_id, reject_id, term_id, date_public, category_id, price, contact_name, contact_email, contact_phone, subject, description, region_id, edit_type, city_id, district_id, metro_id', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO, 'AdCategories', 'category_id'),
			'city' => array(self::BELONGS_TO, 'Cities', 'city_id'),
			'district' => array(self::BELONGS_TO, 'Districts', 'district_id'),
			'metro' => array(self::BELONGS_TO, 'Metro', 'metro_id'),
			'region' => array(self::BELONGS_TO, 'Regions', 'region_id'),
			'reject' => array(self::BELONGS_TO, 'AdRejects', 'reject_id'),
			'status' => array(self::BELONGS_TO, 'AdStatuses', 'status_id'),
			'term' => array(self::BELONGS_TO, 'AdTerms', 'term_id'),
			'type' => array(self::BELONGS_TO, 'AdTypes', 'type_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'adsFiles' => array(self::HAS_MANY, 'AdsFiles', 'ad_id'),
			'adsMessages' => array(self::HAS_MANY, 'AdsMessages', 'ad_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type_id' => 'Type',
			'user_id' => 'User',
			'status_id' => 'Status',
			'reject_id' => 'Reject',
			'term_id' => 'Term',
			'date_public' => 'Date Public',
			'category_id' => 'Category',
			'price' => 'Price',
			'contact_name' => 'Contact Name',
			'contact_email' => 'Contact Email',
			'contact_phone' => 'Contact Phone',
			'subject' => 'Subject',
			'description' => 'Description',
			'region_id' => 'Region',
			'city_id' => 'City',
			'district_id' => 'District',
			'metro_id' => 'Metro',
		);
	}

	public function getDays()
	{
		$time = (($this->extension_date == null ? strtotime($this->date_public) : strtotime($this->extension_date)) + AdTerms::model()->findByPk($this->term_id)->seconds) - time();
		if($this->status_id == 1){
			return 'Осталось: На модерации';
		} elseif ($this->status_id == 5){
			return 'Осталось: Отклонено пользователем';
		} elseif ($this->status_id == 3){
			return 'Причина: Отклонено';
		} elseif ($this->status_id == 4){
			return 'Осталось: Завершено';
		} elseif ($this->status_id == 6){
			return 'Причина: Заблокировано';
		} elseif((($this->extension_date == null ? strtotime($this->date_public) : strtotime($this->extension_date)) - 86400) <= 0){
			return 'Осталось: ' . date('j', $time) . ' дней';
		} elseif ($time >= 0) {
			return 'Осталось: ' . date('j', $time) . ' дней';
		} elseif ($time < 0) {
			return 'Снято с публикации';
		}
	}

	public function getAdDay($date_pub)
	{
		if ((strtotime($date_pub) - 86400) <= 0) {
			return 'Сегодня ' . date('H:i', strtotime($date_pub));
		} elseif ((strtotime($date_pub) - 86400 * 2) <= 0) {
			return 'Вчера ' . date('H:i', strtotime($date_pub));
		} else {
			$mont_name = $this->getMonthName(date('n', strtotime($date_pub)));
			return date('d', strtotime($date_pub)) . ' ' . $mont_name . date('H:i', strtotime($date_pub));
		}
	}

	public function getMonthName($month_num)
	{
		$months = array(1 => 'Янв.', 2 => 'Фев.', 3 => 'Мар.', 4 => 'Апр.', 5 => 'Май', 6 => 'Июнь', 7 => 'Июль', 8 => 'Авг.', 9 => 'Сен.', 10 => 'Окт.', 11 => 'Ноя.', 12 => 'Дек.');
		foreach ($months as $month => $key) {
			if ($month == $month_num) {
				return $key;
				break;
			}
		}
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
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('status_id',$this->status_id);
		$criteria->compare('reject_id',$this->reject_id);
		$criteria->compare('term_id',$this->term_id);
		$criteria->compare('date_public',$this->date_public,true);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('contact_name',$this->contact_name,true);
		$criteria->compare('contact_email',$this->contact_email,true);
	    $criteria->compare('extension_date',$this->extension_date,true);
		$criteria->compare('contact_phone',$this->contact_phone,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('region_id',$this->region_id);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('district_id',$this->district_id);
		$criteria->compare('metro_id',$this->metro_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ads the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

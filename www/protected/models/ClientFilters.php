<?php

/**
 * This is the model class for table "client_filters".
 *
 * The followings are the available columns in table 'client_filters':
 * @property integer $id
 * @property integer $author
 * @property string $name
 * @property string $who_visible
 * @property integer $page_size
 * @property integer $is_files
 * @property integer $is_default
 * @property string $class_name
 *
 * The followings are the available model relations:
 * @property Users $author0
 * @property ClientFiltersBlockAdditionalFields[] $clientFiltersBlockAdditionalFields
 * @property ClientFiltersBlockInfo[] $clientFiltersBlockInfos
 */
class ClientFilters extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'client_filters';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('author, who_visible, is_files, is_default', 'required'),
			['name', 'required', 'message' => 'Имя фильтра не может быть пустым'],
			['page_size', 'required', 'message' => 'Количество плашек не может быть пустым'],
			array('author, page_size, is_files, is_default', 'numerical', 'integerOnly'=>true),
            array('name, class_name', 'length', 'max'=>80),
			array('who_visible', 'length', 'max'=>10),
			['page_size', 'rangeValid'],
			['is_default', 'removeDefault'],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, author, name, who_visible, page_size, is_files, is_default, class_name', 'safe', 'on'=>'search'),
		);
	}
	
	public function rangeValid() {
        if ($this->page_size < 5 || $this->page_size > 300) {
            $this->addError('page_size', 'Только от 5 до 300 плашек');
        }
    }

    public function removeDefault() {
	    if (!$this->getErrors() && $this->is_default) {
            ClientFilters::model()->updateAll(['is_default' => 0], 'author = '. $this->author);
        }
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'author0' => array(self::BELONGS_TO, 'Users', 'author'),
			'clientFiltersBlockAdditionalFields' => array(self::HAS_MANY, 'ClientFiltersBlockAdditionalFields', 'client_filters_id'),
			'clientFiltersBlockInfos' => array(self::HAS_MANY, 'ClientFiltersBlockInfo', 'client_filters_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'author' => 'Author',
			'name' => 'Name',
			'who_visible' => 'Who Visible',
			'page_size' => 'Page Size',
			'is_files' => 'Is File',
            'is_default' => 'Is Default',
            'class_name' => 'Class Name',
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
		$criteria->compare('author',$this->author);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('who_visible',$this->who_visible,true);
		$criteria->compare('page_size',$this->page_size);
		$criteria->compare('is_files',$this->is_files);
        $criteria->compare('is_default',$this->is_default);
        $criteria->compare('class_name',$this->class_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClientFilters the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

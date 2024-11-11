<?php

/**
 * This is the model class for table "clients_notes".
 *
 * The followings are the available columns in table 'clients_notes':
 * @property integer $id
 * @property integer $client_id
 * @property integer $added
 * @property integer $user_id
 * @property integer $edited
 * @property integer $edit_user_id
 * @property string $text
 */
class MainClientsNotes extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'clients_notes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_id, added, user_id, text', 'required'),
			array('client_id, added, user_id, edited, edit_user_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, client_id, added, user_id, edited, edit_user_id, text', 'safe', 'on'=>'search'),
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
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),  
            'edit_user' => array(self::BELONGS_TO, 'Users', 'edit_user_id'),       
            'client' => array(self::BELONGS_TO, 'Clients', 'client_id'),
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
			'added' => 'Added',
			'user_id' => 'User',
			'edited' => 'Edited',
			'edit_user_id' => 'Edit User',
			'text' => 'Text',
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
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('added',$this->added);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('edited',$this->edited);
		$criteria->compare('edit_user_id',$this->edit_user_id);
		$criteria->compare('text',$this->text,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MainClientsNotes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

<?php

/**
 * This is the model class for table "range_ip".
 *
 * The followings are the available columns in table 'range_ip':
 * @property integer $id
 * @property string $begin_ip
 * @property string $end_ip
 * @property string $comment
 */
class RangeIp extends MainRangeIp
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'range_ip';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('begin_ip, end_ip', 'required'),
			array('begin_ip, end_ip', 'length'),
			array('comment', 'safe'),
			array('ip', 'checkIP'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, begin_ip, end_ip, comment', 'safe', 'on'=>'search'),
		);
	}

	public function checkIP ()
    {

        if (ip2long($this->begin_ip)) {
            $this->begin_ip = sprintf('%u', ip2long( $this->begin_ip));
        } else {
            $this->addError('ip','incorrect ip1');
        }

        if (ip2long($this->end_ip)) {
            $this->end_ip = sprintf('%u', ip2long( $this->end_ip));
        } else {
            $this->addError('ip','incorrect ip2');
        }
        if ($this->begin_ip > $this->end_ip) {
            $this->addError('ip','incorrect ip3');
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'begin_ip' => 'Begin Ip',
			'end_ip' => 'End Ip',
			'comment' => 'Comment',
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
		$criteria->compare('begin_ip',$this->begin_ip,true);
		$criteria->compare('end_ip',$this->end_ip,true);
		$criteria->compare('comment',$this->comment,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RangeIp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}

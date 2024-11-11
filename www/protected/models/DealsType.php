<?php

/**
 * This is the model class for table "deals_type".
 *
 * The followings are the available columns in table 'deals_type':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Deals[] $deals
 */
class DealsType extends MainDealsType
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'deals_type';
	}
}

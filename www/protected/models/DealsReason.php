<?php

/**
 * This is the model class for table "deals_reason".
 *
 * The followings are the available columns in table 'deals_reason':
 * @property integer $id
 * @property string $name
 * @property integer $weight
 * @property integer $is_default
 *
 * The followings are the available model relations:
 * @property DealAndReason[] $dealAndReasons
 */
class DealsReason extends MainDealsReason
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'deals_reason';
	}
}

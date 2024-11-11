<?php

/**
 * This is the model class for table "deal_and_reason".
 *
 * The followings are the available columns in table 'deal_and_reason':
 * @property integer $id
 * @property integer $deals_id
 * @property integer $deals_reason_id
 *
 * The followings are the available model relations:
 * @property Deals $deals
 * @property DealsReason $dealsReason
 */
class DealAndReason extends MainDealAndReason
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'deal_and_reason';
	}
}

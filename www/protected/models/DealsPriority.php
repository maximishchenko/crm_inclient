<?php

/**
 * This is the model class for table "deals_priority".
 *
 * The followings are the available columns in table 'deals_priority':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Deals[] $deals
 * @property Companies $company
 */
class DealsPriority extends MainDealsPriority
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'deals_priority';
    }
}
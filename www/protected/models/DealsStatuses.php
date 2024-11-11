<?php

/**
 * This is the model class for table "deals_statuses".
 *
 * The followings are the available columns in table 'deals_statuses':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Deals[] $deals
 * @property Companies $company
 */
class DealsStatuses extends MainDealsStatuses
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'deals_statuses';
    }
}
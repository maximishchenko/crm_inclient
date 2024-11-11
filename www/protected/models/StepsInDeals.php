<?php

/**
 * This is the model class for table "steps_in_deals".
 *
 * The followings are the available columns in table 'steps_in_deals':
 * @property integer $id
 * @property integer $deals_id
 * @property integer $steps_id
 * @property integer $selected_option_id
 *
 * The followings are the available model relations:
 * @property Deals $deals
 * @property Steps $steps
 */
class StepsInDeals extends MainStepsInDeals
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'steps_in_deals';
    }
}
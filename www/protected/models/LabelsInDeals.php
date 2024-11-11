<?php

/**
 * This is the model class for table "labels_in_deals".
 *
 * The followings are the available columns in table 'labels_in_deals':
 * @property integer $id
 * @property integer $label_id
 * @property integer $deal_id
 *
 * The followings are the available model relations:
 * @property Deals $deal
 * @property Labels $label
 */
class LabelsInDeals extends MainLabelsInDeals
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'labels_in_deals';
	}

    public function checkDuplicate($label_id, $deal_id) {
        return !(LabelsInDeals::model()->find('deal_id = :CID && label_id = :LID',
            [
                ':CID' => $deal_id,
                ':LID' => $label_id
            ])
        );
    }
}

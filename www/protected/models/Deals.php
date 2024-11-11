<?php

/**
 * This is the model class for table "deals".
 *
 * The followings are the available columns in table 'deals':
 * @property integer $id
 * @property string $text
 * @property integer $client_id
 * @property integer $deal_category_id
 * @property integer $deal_status_id
 * @property string $creation_date
 * @property string $paid
 * @property string $balance
 *
 * The followings are the available model relations:
 * @property Clients $client
 * @property Companies $company
 * @property DealsCategories $dealCategory
 * @property DealsStatuses $dealStatus
 * @property integer $deal_type_id
 * @property string $change_date
 * @property string $closed_date
 */
class Deals extends MainDeals
{
    public $director_id;
    public $manager_id;
    public $keyword;
    public $client_group_id;
    public $start_date;
    public $stop_date;
    public $start_sum;
    public $stop_sum;
    public $documents;
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'deals';
    }

    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('text, client_id, deal_category_id, deal_priority_id, deal_status_id, responsable_id, creation_date', 'required', 'message' => 'Необходимо заполнить поле'),
            array('client_id, deal_category_id, deal_priority_id, deal_status_id, responsable_id', 'numerical', 'integerOnly'=>true,  'message' => 'Значение данного поля должно быть числом'),
            array('text', 'length', 'max'=>255),
            array('paid, balance', 'length', 'max'=>20),
            array('description, change_date, closed_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, text, client_id, deal_category_id, deal_priority_id, deal_status_id, responsable_id, creation_date, paid, balance, description, deal_type_id, change_date, closed_date', 'safe', 'on'=>'search'),
        );
    }

    public static function checkAccess($deal, $user)
    {
        $role = $user->roles[0]->name;
        switch ($role) {
            case 'admin':
                return true;
                break;
            case 'manager':
                $parent = $user->parent;
                $users_ids = Users::takeDirectorUsers($parent->id, false);
                if ($user->id == $deal->responsable_id || in_array($deal->responsable_id, $users_ids)) {
                    return true;
                } else {
                    return false;
                }
                break;
            case 'director':
                $users_ids = Users::takeDirectorUsers($user->id);
                if (in_array($deal->responsable_id, $users_ids)) {
                    return true;
                } else {
                    return false;
                }
                break;
        }
    }

    public function searchDeals($id = null, $noTable = false, $filterForLabelId = null, $filterForSteps = null, $dealTypeFilter = 0)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        if ($filterForLabelId) {
            if (is_array($filterForLabelId) && count($filterForLabelId) > 0) {
                $str = '';
                $count= count($filterForLabelId);
                $i = 1;
                foreach ($filterForLabelId as $labelId) {
                    $str .= 'label_id = ' . $labelId . ($i != $count ? ' OR ' : '');
                    $i++;
                }
                $criteria->join = 'RIGHT JOIN labels_in_deals ON (t.id = deal_id AND (' . $str . '))';

            } else {
                $criteria->join = 'RIGHT JOIN labels_in_deals ON (t.id = deal_id AND label_id = ' . $filterForLabelId . ')';
            }

        }

        if ($filterForSteps && $filterForSteps->steps_id != 0) {
            $criteria->join .= ' RIGHT JOIN steps_in_deals ON (t.id = deals_id AND steps_id = ' . $filterForSteps->steps_id . ' AND selected_option_id = ' . $filterForSteps->selected_option_id . ')';
        }

        $criteria->with = [
            'client',
            'responsable',
            'dealPriority',
            'dealStatus',
            'dealCategory',
            'dealsFiles',
        ];
        $this->keyword != null ? $criteria->addCondition('t.text LIKE "%' . $this->keyword . '%"', 'OR') : '';
        $this->keyword != null ? $criteria->addCondition('t.description LIKE "%' . $this->keyword . '%"', 'OR') : '';

        $id != null ? $criteria->addCondition('t.client_id='.$id) : '';
        $this->client_group_id != null && $this->client_group_id != 0 ? $criteria->addCondition('client.group_id='.$this->client_group_id) : '';

        $this->responsable_id != null && $this->responsable_id != 'all' && $this->responsable_id != 'no' ? $criteria->addCondition('t.responsable_id='.$this->responsable_id) : '';
        $this->responsable_id == 'no' ? $criteria->addCondition('t.responsable_id is null'): '';

        $this->deal_category_id != null && $this->deal_category_id != 0 ? $criteria->addCondition('t.deal_category_id='.$this->deal_category_id) : '';
        $this->text != null ? $criteria->addCondition('t.text LIKE "%' . $this->text . '%"') : '';
        $this->deal_status_id != null && $this->deal_status_id != 0 ? $criteria->addCondition('t.deal_status_id='.$this->deal_status_id) : '';
        $this->deal_priority_id != null && $this->deal_priority_id != 0 ? $criteria->addCondition('t.deal_priority_id='.$this->deal_priority_id) : '';

        $this->paid != null && $this->paid != 0 ? $criteria->addCondition('t.paid>='.$this->paid) : '';
        $this->balance != null && $this->balance != 0 ? $criteria->addCondition('t.balance>='.$this->balance) : '';
        // поиск только по нулевым значениям
        $this->paid === "0" ? $criteria->addCondition('t.paid=0') : '';
        $this->balance === "0" ? $criteria->addCondition('t.balance=0') : '';

        // поиск в интервале дат
        $this->start_date != null ? $criteria->addCondition('UNIX_TIMESTAMP(t.creation_date)>=' . strtotime($this->start_date)) : '';
        $this->stop_date != null ? $criteria->addCondition('UNIX_TIMESTAMP(t.creation_date)<=' . strtotime($this->stop_date . ':59')) : '';

        if ($this->documents) {
            $criteria->addCondition('t.id in (select deal_id FROM deals_files GROUP BY deal_id)');
        }

        // поиск по ролям
        $role = UsersRoles::model()->find('user_id=' . Yii::app()->user->id)->itemname;
        if($role == 'director'){
            $users = new Users();
            $users_ids = $users->takeDirectorUsers(Yii::app()->user->id);
            $criteria->addInCondition('t.responsable_id', $users_ids);
        } elseif($role == 'manager') {
            $users = new Users();
            $users_ids = $users->takeManagers(Users::model()->findByPk(Yii::app()->user->id));
            $criteria->addInCondition('t.responsable_id', $users_ids);
        }

        if ($dealTypeFilter) {
            $criteria->addCondition('t.deal_type_id=' . $dealTypeFilter);
        }

        if ($noTable) {
            $criteria->order = 't.id DESC';
            return Deals::model()->findAll($criteria);
        } else {
            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'sort' => array(
                    'defaultOrder' => 't.id DESC',),
                'pagination' => array(
                    'pageSize' => 30,
                ),
            ));
        }

    }

}

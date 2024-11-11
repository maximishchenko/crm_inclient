<?php

/**
 * This is the model class for table "actions".
 *
 * The followings are the available columns in table 'actions':
 * @property integer $id
 * @property string $text
 * @property integer $client_id
 * @property integer $action_type_id
 * @property integer $action_status_id
 * @property string $creation_date
 *
 * The followings are the available model relations:
 * @property ActionsStatuses $actionStatus
 * @property ActionsTypes $actionType
 * @property Clients $client
 */
class Actions extends MainActions
{
    public $director_id;
    public $manager_id;
    public $keyword;
    public $client_group_id;
    public $term;
    public $start_date;
    public $stop_date;
    public $documents;
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'actions';
    }

    public function getTerms()
    {
        $terms = array(
            4 => 'expired',
            1 => 'now',
            2 => 'future',
            3 => 'done',
        );

        return $terms;
    }

    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('text, client_id, action_type_id, action_status_id, action_priority_id, responsable_id, creation_date, action_date',
                'required', 'message' => 'Обязательное поле'),
            array('client_id, action_type_id, action_status_id, action_priority_id, responsable_id', 'numerical', 'integerOnly'=>true,  'message' => 'Значение данного поля должно быть числом'),
            array('text', 'length', 'max'=>255),
            array('description', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, text, client_id, action_type_id, action_status_id, action_priority_id, responsable_id, creation_date, action_date, description', 'safe', 'on'=>'search'),
        );
    }

    public static function checkAccess($actions, $user)
    {
        $role = $user->roles[0]->name;
        switch ($role) {
            case 'admin':
                return true;
                break;
            case 'manager':
                $parent = $user->parent;
                $users_ids = Users::takeDirectorUsers($parent->id, false);
                if ($user->id == $actions->responsable_id || in_array($actions->responsable_id, $users_ids)) {
                    return true;
                } else {
                    return false;
                }
                break;
            case 'director':
                $users_ids = Users::takeDirectorUsers($user->id);
                if (in_array($actions->responsable_id, $users_ids)) {
                    return true;
                } else {
                    return false;
                }
                break;
        }
    }

    public function searchActions($id = null, $noTable = false, $filterForLabelId = null)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        if ($filterForLabelId) {
            if (is_array($filterForLabelId) && count($filterForLabelId) > 0) {
                $str = '';
                $count= count($filterForLabelId);
                $i = 1;
                foreach ($filterForLabelId as $labelId) {
                    $str .= 'label_id = ' . $labelId . ($i != $count ? ' OR ' : '');
                    $i++;
                }
                $criteria->join = 'RIGHT JOIN labels_in_actions ON (t.id = action_id AND (' . $str . '))';

            } else {
                $criteria->join = 'RIGHT JOIN labels_in_actions ON (t.id = action_id AND label_id = ' . $filterForLabelId . ')';
            }
        }

        $criteria->with = array(
            'client',
            'actionType',
            'actionStatus',
            'actionPriority',
            'responsable',
            'actionsFiles'
        );
        $id != null ? $criteria->addCondition('t.client_id='.$id) : '';
        $this->client_group_id != null && $this->client_group_id != 0 ? $criteria->addCondition('client.group_id='.$this->client_group_id) : '';

        $this->responsable_id != null && $this->responsable_id != 'all' && $this->responsable_id != 'no' ? $criteria->addCondition('t.responsable_id='.$this->responsable_id) : '';
        $this->responsable_id == 'no' ? $criteria->addCondition('t.responsable_id is null'): '';

        $this->action_type_id != null && $this->action_type_id != 0 ? $criteria->addCondition('t.action_type_id='.$this->action_type_id) : '';
        $this->action_status_id != null && $this->action_status_id != 0 ? $criteria->addCondition('t.action_status_id='.$this->action_status_id) : '';
        $this->action_priority_id != null && $this->action_priority_id != 0 ? $criteria->addCondition('t.action_priority_id='.$this->action_priority_id) : '';

        $this->keyword != null ? $criteria->addCondition('t.text LIKE "%' . $this->keyword . '%" OR ' . 't.description LIKE "%' . $this->keyword . '%"') : '';
        //хуйнуть поиск по интервалу дат

        if($this->term){
            $date_to_sql_req = strtotime(date('d.m.Y'));

            switch ($this->term) {
                case 4:
                    $criteria->addCondition("t.action_status_id=1 AND " . $date_to_sql_req . ">UNIX_TIMESTAMP(t.action_date) AND DATE_FORMAT(CURRENT_TIMESTAMP(), '%d.%m.%Y')!=DATE_FORMAT(t.action_date,'%d.%m.%Y')");
                    break;
                case 1:
                    $criteria->addCondition("DATE_FORMAT(CURRENT_TIMESTAMP(), '%d.%m.%Y')=DATE_FORMAT(t.action_date,'%d.%m.%Y')");
                    break;
                case 2:
                    $criteria->addCondition("t.action_status_id!=2 AND " . $date_to_sql_req . "<=UNIX_TIMESTAMP(t.action_date) AND DATE_FORMAT(CURRENT_TIMESTAMP(), '%d.%m.%Y')!=DATE_FORMAT(t.action_date,'%d.%m.%Y')");
                    break;
                case 3:
                    $criteria->addCondition("t.action_status_id=2 OR t.action_status_id=3");
                    break;
            }
        }

        if ($this->documents) {
            $criteria->addCondition('t.id in (select action_id FROM actions_files GROUP BY action_id)');
        }

        // поиск в интервале дат
        $this->start_date != null ? $criteria->addCondition('UNIX_TIMESTAMP(t.action_date)>=' . strtotime($this->start_date)) : '';
        $this->stop_date != null ? $criteria->addCondition('UNIX_TIMESTAMP(t.action_date)<=' . strtotime($this->stop_date . ':59')) : '';

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
        if ($noTable) {
            $criteria->order = 't.id DESC';
            return Actions::model()->findAll($criteria);
        } else {
            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'sort' => array(
                    'defaultOrder' => 't.action_date DESC',),
                'pagination' => array(
                    'pageSize'=> 30,
                ),
            ));
        }

    }
}
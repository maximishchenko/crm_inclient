<?php

/**
 * This is the model class for table "clients".
 *
 * The followings are the available columns in table 'clients':
 * @property integer $id
 * @property string $name
 * @property string $adres
 * @property string $email_1
 * @property string $email_2
 * @property string $phone_1
 * @property string $phone_2
 * @property string $site
 * @property string $vk_profile
 * @property string $icq
 * @property string $skype
 * @property string $description
 * @property string $question
 * @property string $creation_date
 * @property string $change_client_date
 * @property integer $responsable_id
 * @property integer $priority_id
 * @property integer $source_id
 * @property integer $goal_id
 * @property integer $city_id
 * @property integer $group_id
 *
 * The followings are the available model relations:
 * @property Actions[] $actions
 * @property Companies $company
 * @property ClientsGroups $group
 * @property ClientsCityes $city
 * @property ClientsGoals $goal
 * @property ClientsSources $source
 * @property ClientsPriority $priority
 * @property Users $responsable
 * @property Deals[] $deals
 */
class Clients extends MainClients
{
    public $director_id;
    public $manager_id;
    public $keyword;
    public $additionalField;
    public $documents;
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'clients';
    }

    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('creation_date, responsable_id, creator_id, priority_id, city_id, group_id', 'required', 'message' => 'Обязательное поле'),
            array('responsable_id, creator_id, priority_id, source_id, goal_id, city_id, group_id', 'numerical', 'integerOnly'=>true, 'message' => 'Значение данного поля должно быть числом'),
            array('name, adres, email_1, email_2, phone_1, phone_2, site, vk_profile, icq, skype', 'length', 'max'=>255),
            ['name', 'required', 'message' => 'Имя контакта не может быть пустым'],
            array('description, question', 'safe'),
            array('email_1, email_2', 'email', 'message' => 'Введите валидный e-mail адрес'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, adres, email_1, email_2, phone_1, phone_2, site, vk_profile, icq, skype, description, question, creation_date, change_client_date, responsable_id, creator_id, priority_id, source_id, goal_id, city_id, group_id', 'safe', 'on'=>'search'),
        );
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function checkAccess($client, $user)
    {
        $role = $user->roles[0]->name;
        switch ($role) {
            case 'admin':
                return true;
                break;
            case 'manager':
                $parent = $user->parent;
                $users_ids = Users::takeDirectorUsers($parent->id, false);
                if ($user->id == $client->responsable_id || in_array($client->responsable_id, $users_ids)) {
                    return true;
                } else {
                    return false;
                }
                break;
            case 'director':
                $users_ids = Users::takeDirectorUsers($user->id);
                if (in_array($client->responsable_id, $users_ids)) {
                    return true;
                } else {
                    return false;
                }
                break;
        }
    }

    public function searchClients($noTable = false, $filterForLabelId = null, $filterForSteps = null)
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
                $criteria->join = 'RIGHT JOIN labels_in_clients ON (t.id = client_id AND (' . $str . '))';

            } else {
                $criteria->join = 'RIGHT JOIN labels_in_clients ON (t.id = client_id AND label_id = ' . $filterForLabelId . ')';
            }
        }

        if ($filterForSteps && $filterForSteps->steps_id != 0) {
            $criteria->join .= ' RIGHT JOIN steps_in_clients ON (t.id = clients_id AND steps_id = ' . $filterForSteps->steps_id . ' AND selected_option_id = ' . $filterForSteps->selected_option_id . ')';
        }

        if ($this->keyword != null) {

            //тяжёлый случай
            $userInGroupsList = UserInGroups::model()->with('group')->findAll('user_id = :ID', [':ID' => Yii::app()->user->id]);
            $groupsIds = ' ';
            foreach ($userInGroupsList as $value) {
                $groupsIds .= $value->group_id . ',';
            }
            $groupsIds = substr($groupsIds,0,-1);

            $sectors = SectorInGroups::model()->findAll('group_id in (' . $groupsIds . ')');

            $sectorIds = ' ';
            foreach ($sectors as $val) {
                $sectorIds .= $val->sector_id . ',';
            }
            $sectorIds = substr($sectorIds,0,-1);

            $cond = count($sectors) > 0 ? ' || id in (' . $sectorIds . ')' : '';

            $accessSection = AdditionalFieldsSection::model()->findAll('access = "all"' . $cond);

            $sectionIds = ' ';
            foreach ($accessSection as $val) {
                $sectionIds .= $val->id . ',';
            }
            $sectionIds = substr($sectionIds,0,-1);

            $addFields = AdditionalFields::model()->findAll('section_id in (' . $sectionIds . ')');

            $criteria->join .= 'INNER JOIN additional_fields_values as ad ON (t.id = client_id)';

            foreach ($addFields as $field) {
                if ($field->type == 'date' || $field->type == 'datetime') {
                    if ($date = strtotime($this->keyword)) {
                        $criteria->addCondition('ad.' . $field->table_name . ' LIKE "%' . $date . '%"', "OR");
                    }
                }
                elseif ($field->type == 'select') {
                    $options = json_decode($field->default_value);
                    foreach ($options as $option) {
                        if (stripos($option->optionName, $this->keyword) !== false) {
                            $criteria->addCondition('ad.' . $field->table_name . '=' . $option->id, "OR");
                        }
                    }
                } else {
                    $criteria->addCondition('ad.' . $field->table_name . ' LIKE "%' . $this->keyword . '%"', "OR");
                }
            }
        }
        $criteria->with = array(
            'responsable',
            'priority',
            'group',
            'goal',
            'city',
            'source',
            //'labelsInClients',
            'clientsFiles'
        );
        $criteria->together = true;
        $this->responsable_id != null && $this->responsable_id != 'all' && $this->responsable_id != 'no' ? $criteria->addCondition('t.responsable_id='.$this->responsable_id) : '';
        $this->responsable_id == 'no' ? $criteria->addCondition('t.responsable_id=null') : '';
        $this->priority_id != null && $this->priority_id != 0 ? $criteria->addCondition('t.priority_id='.$this->priority_id) : '';
        $this->source_id != null && $this->source_id != 0 ? $criteria->addCondition('t.source_id='.$this->source_id) : '';
        $this->goal_id != null && $this->goal_id != 0 ? $criteria->addCondition('t.goal_id='.$this->goal_id) : '';
        $this->city_id != null && $this->city_id != 0 ? $criteria->addCondition('t.city_id='.$this->city_id) : '';
        $this->group_id != null && $this->group_id != 0 ? $criteria->addCondition('t.group_id='.$this->group_id) : '';

        if ($this->documents) {
            $criteria->addCondition('clientsFiles.file_id > 0');
        }
        $role = UsersRoles::model()->find('user_id = ' . Yii::app()->user->id)->itemname;
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
            return Clients::model()->findAll($criteria);
        } else {
            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'sort' => array(
                    'defaultOrder' => 't.id DESC',),
                'pagination' => array(
                    'pageSize'=> 30,
                ),

            ));
        }
    }

    public function searchForExport($labelIds = null, $stepsIds = null, $responsibleIds)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $relation = [
            'responsable',
            'group',
            'additionalFieldsValues'
        ];
        $criteria = new CDbCriteria;
        if (is_array($labelIds) && count($labelIds) > 0) {
            $str = '';
            $count = count($labelIds);
            $i = 1;
            foreach ($labelIds as $labelId) {
                $str .= 'label_id = ' . $labelId . ($i != $count ? ' OR ' : '');
                $i++;
            }
            $criteria->join = 'RIGHT JOIN labels_in_clients ON (t.id = client_id AND (' . $str . '))';

        } else {
            $relation [] = 'labelsInClients';
        }

        if (is_array($stepsIds) && count($stepsIds) > 0) {
            $str = '';
            $count= count($stepsIds);
            $i = 1;
            foreach ($stepsIds as $stepsId) {
                $str .= 'steps_id = ' . $stepsId . ($i != $count ? ' OR ' : '');
                $i++;
            }
            $criteria->join .= ' RIGHT JOIN steps_in_clients ON (t.id = clients_id AND (' . $str . '))';
        } else {
            $relation [] = 'stepsInClients';
        }

        if (is_array($responsibleIds) && count($responsibleIds) > 0) {
            $criteria->addCondition('t.responsable_id in (' . implode(',', $responsibleIds) . ')');
        }


        $criteria->with = $relation;
        $criteria->together = true;
        $criteria->order = 't.id DESC';
        return Clients::model()->findAll($criteria);
    }

    public function searchForFilter($isDataProvider = false, $isFile = false, $labelIds = null, $responsibleIds = null, $stepOptions = null, $pageSize = 30, $keyword = null)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $relation = [
            'responsable',
            'group',
            //'additionalFieldsValues'
        ];

        $criteria = new CDbCriteria;

        if ($isFile) {
            $criteria->join = 'INNER JOIN clients_files ON (t.id = clients_files.client_id)';
        }

        if (is_array($labelIds) && count($labelIds) > 0) {
            $str = '';
            $count = count($labelIds);
            $i = 1;
            foreach ($labelIds as $labelId) {
                $str .= 'label_id = ' . $labelId . ($i != $count ? ' OR ' : '');
                $i++;
            }
            $criteria->join .= 'RIGHT JOIN labels_in_clients ON (t.id = labels_in_clients.client_id AND (' . $str . '))';

        }

        if (is_array($stepOptions) && count($stepOptions) > 0) {
            $str = '';
            $count= count($stepOptions);
            $i = 1;
            foreach ($stepOptions as $stepsOptionsId) {
                $stepsOptionsId = $stepsOptionsId != null ? $stepsOptionsId : 0;
                $str .= 'selected_option_id = ' . $stepsOptionsId . ($i != $count ? ' OR ' : '');
                $i++;
            }
            $criteria->join .= ' INNER JOIN steps_in_clients ON (t.id = steps_in_clients.clients_id AND (' . $str . '))';
        }

        if ($keyword != null) {

            //тяжёлый случай
            $userInGroupsList = UserInGroups::model()->with('group')->findAll('user_id = :ID', [':ID' => Yii::app()->user->id]);
            $groupsIds = ' ';
            foreach ($userInGroupsList as $value) {
                $groupsIds .= $value->group_id . ',';
            }
            $groupsIds = substr($groupsIds,0,-1);

            $sectors = SectorInGroups::model()->findAll('group_id in (' . $groupsIds . ')');

            $sectorIds = ' ';
            foreach ($sectors as $val) {
                $sectorIds .= $val->sector_id . ',';
            }
            $sectorIds = substr($sectorIds,0,-1);

            $cond = count($sectors) > 0 ? ' || id in (' . $sectorIds . ')' : '';

            $accessSection = AdditionalFieldsSection::model()->findAll('access = "all"' . $cond);

            $sectionIds = ' ';
            foreach ($accessSection as $val) {
                $sectionIds .= $val->id . ',';
            }
            $sectionIds = substr($sectionIds,0,-1);

            $addFields = AdditionalFields::model()->findAll('section_id in (' . $sectionIds . ')');

            $criteria->join .= 'INNER JOIN additional_fields_values as ad ON (t.id = ad.client_id)';

            foreach ($addFields as $field) {
                if ($field->type == 'date' || $field->type == 'datetime') {
                    if ($date = strtotime($keyword)) {
                        $criteria->addCondition('ad.' . $field->table_name . ' LIKE "%' . $date . '%"', "OR");
                    }
                }
                elseif ($field->type == 'select') {
                    $options = json_decode($field->default_value);
                    foreach ($options as $option) {
                        if (stripos($option->optionName, $keyword) !== false) {
                            $criteria->addCondition('ad.' . $field->table_name . '=' . $option->id, "OR");
                        }
                    }
                } else {
                    $criteria->addCondition('ad.' . $field->table_name . ' LIKE "%' . $keyword . '%"', "OR");
                }
            }
        }

        // готовит ответственных
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);

        $accessResponsible = Users::getAccessUsersForFilter($user);
        $accessResponsibleIds = array_column($accessResponsible, 'id');

        if (is_array($responsibleIds) && count($responsibleIds) > 0) {
            foreach ($responsibleIds as $key => $id) {
                if (!in_array($id, $accessResponsibleIds)) {
                    unset($responsibleIds[$key]);
                }
            }
        } else {
            $responsibleIds = $accessResponsibleIds;
        }

        if (count($responsibleIds) == 0) {
            $responsibleIds [] = $user->id;// добавляем себя
        }

        $criteria->addCondition('t.responsable_id in (' . implode(',', $responsibleIds) . ')');

        $criteria->with = $relation;
        $criteria->together = true;

        $role = UsersRoles::model()->find('user_id = ' . Yii::app()->user->id)->itemname;
        if($role == 'director'){
            $users = new Users();
            $users_ids = $users->takeDirectorUsers(Yii::app()->user->id);
            $criteria->addInCondition('t.responsable_id', $users_ids);
        } elseif($role == 'manager') {
            $users = new Users();
            $users_ids = $users->takeManagers(Users::model()->findByPk(Yii::app()->user->id));
            $criteria->addInCondition('t.responsable_id', $users_ids);
        }

        if ($isDataProvider) {
            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'sort' => ['defaultOrder' => 't.id DESC'],
                'pagination' => ['pageSize'=> $pageSize],
            ));
        } else {
            $criteria->order = 't.id DESC';
            return Clients::model()->findAll($criteria);
        }
    }

    public static function isAccessVisible($who_visible, $role, $authorId) {
        switch ($who_visible) {
            case 'all': {
                return true;
            }
            case 'director': {
                return $role === 'director' || $authorId == Yii::app()->user->id;
            }
            case 'manager': {
                return $role === 'manager' || $authorId == Yii::app()->user->id;
            }
            case 'i': {
                return $authorId == Yii::app()->user->id;
            }
            default: {
                return false;
            }
        }

    }

    public static function getAccessSection () {
        $user = Users::model()->with('userInGroups')->findByPk(Yii::app()->user->id);
        $additionalFiledValuesInClient = [];
        $userGroup = [];
        foreach ($user->userInGroups as $value) {
            $userGroup[] = $value->group_id;
        }
        $criteria = new CDbCriteria;
        $criteria->with = ['section0.sectorInGroups',];
        $criteria->order = 'section0.weight, t.weight';
        if ($user->roles[0]->name != 'admin') {
            $criteria->addCondition('sectorInGroups.group_id in('. implode(',', $userGroup). ') OR section0.access = "all"');
        }
        $additionalFiled = AdditionalFields::model()->findAll($criteria);

        foreach ($additionalFiled as $field) {
            $tableNameInName[$field->table_name] = [
                'name' => $field->name,
                'type' => $field->type,
                'required' => $field->required,
                'weight' => $field->weight,
                'size' => $field->size,
                'sectionId' => $field->section_id,
                'sectionName' => $field->section0->name,
                'tip' => $field->tip,
                'unique' => $field->unique
            ];
        }

        foreach ($tableNameInName as $keyField => $fieldValue) {
            $additionalFiledValuesInClient[$fieldValue['sectionId']][] = [
                'name' => $fieldValue['name'],
                'table_name' => $keyField,
                'required' => $fieldValue['required'],
                'type' => $fieldValue['type'],
                'size' => $fieldValue['size'],
                'sectionId' => $fieldValue['sectionId'],
                'sectionName' => $fieldValue['sectionName'],
                'tip' => $fieldValue['tip'],
                'unique' => $fieldValue['unique']
            ];
        }

        return $additionalFiledValuesInClient;
    }
}

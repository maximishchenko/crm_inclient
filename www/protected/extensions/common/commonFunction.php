<?php

class commonFunction extends CComponent
{
    public function init()
    {

    }

    public function checkSetRight($targetRight, $oldTargetRight, $callRight)
    {
        if (!$targetRight->create_action) {
            $targetRight->delete_action = 0;
            $targetRight->add_files_action = 0;
            $targetRight->delete_files_action = 0;
        }

        if (!$targetRight->create_deals) {
            $targetRight->delete_deals = 0;
            $targetRight->add_files_deal = 0;
            $targetRight->delete_files_deal = 0;
        }


        if (!$targetRight->create_field) {
            $targetRight->delete_field = 0;
            $targetRight->delete_section = 0;
        }
        if (!$targetRight->create_client) {
            $targetRight->add_files_client = 0;
            $targetRight->delete_client = 0;
            $targetRight->delete_files_client = 0;
        }

        if (!$targetRight->add_files_client) {
            $targetRight->delete_files_client = 0;
        }

        if (!$targetRight->add_files_action) {
            $targetRight->delete_files_action = 0;
        }

        if (!$targetRight->add_files_deal) {
            $targetRight->delete_files_deal = 0;
        }

        if (!$targetRight->add_files_user) {
            $targetRight->delete_files_user = 0;
        }

        if (!$targetRight->create_label_clients) {
            $targetRight->delete_label_clients = 0;
        }

        if (!$targetRight->create_label_actions) {
            $targetRight->delete_label_actions = 0;
        }

        if (!$targetRight->create_label_deals) {
            $targetRight->delete_label_deals = 0;
        }

        if (!$targetRight->create_steps) {
            $targetRight->delete_steps = 0;
        }

        foreach ($targetRight as $key => $value) {
            if ($key == 'id' || $key == 'user_id') {
                continue;
            }

            if ($oldTargetRight == 'newUser') {

                if ($value == 1 && $callRight->$key != 1) {
                   // var_dump($key);die;
                    return false;
                }
            } else {
                if ($oldTargetRight->$key != $value && $callRight->$key != 1) {
                    return false;
                }
            }
        }
        return true;
    }

    public function getValueAdditionalFiled($client, $user)
    {
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

        $tableNameInName = [];
        $additionalFiledValuesInClient = [];
        if ($additionalFiled) {
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

            $additionalFiledValues = AdditionalFieldsValues::model()->find('client_id = :client', [':client' => $client->id]);
            if (!$additionalFiledValues) {
                $additionalFiledValues = new AdditionalFieldsValues();
            }

            $additionalFiledArray = $additionalFiledValues->attributes;
            unset($additionalFiledArray['id'], $additionalFiledArray['client_id']);

            $additionalFiledValuesInClient = [];

            foreach ($tableNameInName as $keyField => $fieldValue) {
                $additionalFiledValuesInClient[$fieldValue['sectionId']][] = [
                    'name' => $fieldValue['name'],
                    'table_name' => $keyField,
                    'value' => isset($additionalFiledArray[$keyField]) ? $additionalFiledArray[$keyField] : '',
                    'required' => $fieldValue['required'],
                    'type' => $fieldValue['type'],
                    'size' => $fieldValue['size'],
                    'sectionId' => $fieldValue['sectionId'],
                    'sectionName' => $fieldValue['sectionName'],
                    'tip' => $fieldValue['tip'],
                    'unique' => $fieldValue['unique']
                ];
            }

        }
        return $additionalFiledValuesInClient;
    }

    public function getValueAdditionalFiledNewClient($user, $setDefault = true)
    {
        $userGroup = [];
        foreach ($user->userInGroups as $value) {
            $userGroup[] = $value->group_id;
        }
        $criteria = new CDbCriteria;
        $criteria->with = ['section0.sectorInGroups',];
        $criteria->order = 'section0.weight, t.weight';
        if ($user->roles[0]->name != 'admin') {
            $criteria->condition = 'sectorInGroups.group_id in(' . implode(',', $userGroup) . ') OR section0.access = "all"';
        }
        $additionalFiled = AdditionalFields::model()->findAll($criteria);

        $tableNameInName = [];
        $additionalFiledValuesInClient = [];
        if ($additionalFiled) {
            foreach ($additionalFiled as $field) {
                $tableNameInName[$field->table_name] = [
                    'name' => $field->name,
                    'type' => $field->type,
                    'default_value' => $field->default_value,
                    'required' => $field->required,
                    'size' => $field->size,
                    'sectionId' => $field->section_id,
                    'sectionName' => $field->section0->name,
                    'tip' => $field->tip,
                    'unique' => $field->unique
                ];
            }
            $additionalFiledValues = new AdditionalFieldsValues();

            $additionalFiledArray = $additionalFiledValues->attributes;

            unset($additionalFiledArray['id'], $additionalFiledArray['client_id']);

            $additionalFiledValuesInClient = [];

            $alwaysDefault = ['select', 'checkbox'];
            foreach ($tableNameInName as $keyField => $fieldValue) {
                    $additionalFiledValuesInClient[$fieldValue['sectionId']][] = [
                        'name' => $fieldValue['name'],
                        'table_name' => $keyField,
                        'value' => $setDefault || in_array($fieldValue['type'], $alwaysDefault) ? $fieldValue['default_value'] : '',
                        'required' => $fieldValue['required'],
                        'type' => $fieldValue['type'],
                        'size' => $fieldValue['size'],
                        'sectionId' => $fieldValue['sectionId'],
                        'sectionName' => $fieldValue['sectionName'],
                        'tip' => $fieldValue['tip'],
                        'unique' => $fieldValue['unique']
                    ];
            }
        }
        return $additionalFiledValuesInClient;
    }

    public function checkAdditionalFiledValue($additionalFiledValue ,$additionalFiledValuesInClient)
    {
        $result = [
          'status' => 'success'
        ];
        $failed = false;
        $incorrectData = false;
        $requiredData = false;
        $fieldsError = [];
        foreach ($additionalFiledValuesInClient as $section) {
            foreach ($section as $fieldValue) {
                $fieldName = $fieldValue['table_name'];
                $value = $additionalFiledValue->$fieldName;

                if (trim($value) == '' && $fieldValue['required']) {
                    $failed = true;
                    $requiredData = true;
                }

                switch ($fieldValue['type']) {
                    case 'checkbox':
                        if ($value != 1 && $value != 0) {
                            $failed = true;
                            $incorrectData = true;
                        }
                        break;
                    case 'int':
                        $value = str_replace([' ', '(', ')', '-', '+', '-'], '', $value);
                        if ($value != '' && !is_numeric($value)) {
                            $failed = true;
                            $incorrectData = true;
                        }
                        break;
                    case 'date':
                    case 'datetime':
                        if ($value != '' && !is_numeric($value)) {
                            $failed = true;
                            $incorrectData = true;
                        }
                        break;
                }
            }
        }
        if ($failed) {
            $result['status'] = 'failed';
            $result['type'] = [];
            if ($incorrectData) {
                $result['type'][] = 'incorrectData';
            }
            if ($requiredData) {
                $result['type'][] = 'requiredData';
            }
        }
        return $result;
    }

    public function convertAdditionalField($additionalFiledValue, $additionalFiledValuesInClientSector)
    {
        foreach ($additionalFiledValuesInClientSector as $fieldValueSector) {
            foreach ($fieldValueSector as $fieldValue) {
                switch ($fieldValue['type']) {
                    case 'date':
                        $value = $fieldValue['table_name'];
                        $additionalFiledValue->$value = strtotime($additionalFiledValue->$value);
                        break;
                }
            }
        }
        return $additionalFiledValue;
    }

    public function checkAccessFieldSection($user, $additionalFiledSection)
    {
        $userGroups = $user->userInGroups[0]->group_id;
        $sectorGroups = [];
        foreach ($additionalFiledSection->sectorInGroups as $group) {
            $sectorGroups[] = $group->group_id;
        }
        return $additionalFiledSection->access == 'all' || in_array($userGroups, $sectorGroups);
    }

    public function checkAccessField($user, $additionalFiled)
    {
        $userGroups = $user->userInGroups[0]->group_id;
        $sectorGroups = [];
        foreach ($additionalFiled->section0->sectorInGroups as $group) {
            $sectorGroups[] = $group->group_id;
        }
        return $additionalFiled->section0->access == 'all' || in_array($userGroups, $sectorGroups);
    }

    public function getUserRight($user)
    {
        $userRight = ['id' => $user->id];
        foreach ($user->userRights[0] as $key => $value) {
            if ($key != 'id' && $key != 'user_id') {
                $userRight[$key] = (int) $value;
            }
        }
        $userRight['role'] = $user->roles[0]->name;
        return $userRight;
    }

    public function getFileSettings()
    {
        $fileSettings = [];
        $settingsName = ['sizeFile', 'extFile'];
        $condStr = '';
        foreach ($settingsName as $setting) {
            $condStr.= '"' . $setting . '",';
        }
        $condStr = trim($condStr,',');
        $settings = Settings::model()->findAll('param in (' . $condStr . ')');
        foreach ($settings as $setting) {
            $fileSettings[$setting->param] = $setting->value;
        }
        return $fileSettings;
    }

    public function checkAccessShowLabel ($right) {
        return $right['role'] == 'admin'
            || $right['create_client'] && $right['create_label_clients']
            || $right['create_deals'] && $right['create_label_deals']
            || $right['create_action'] && $right['create_label_actions'];
    }

    public function checkAccessEditLabel ($right, $type) {
        if ($right['role'] == 'admin') {
            return true;
        }
        switch ($type) {
            case 'clients':
                return $right['create_client'] && $right['create_label_clients'];
                break;
            case 'actions':
                return $right['create_action'] && $right['create_label_actions'];
                break;
            case 'deals':
                return $right['create_deals'] && $right['create_label_deals'];
                break;
        }
        return false;
    }

    public function checkAccessDeleteLabel ($right, $type) {
        if ($right['role'] == 'admin') {
            return true;
        }
        switch ($type) {
            case 'clients':
                return $right['create_label_clients'] && $right['delete_label_clients'];
                break;
            case 'actions':
                return $right['create_label_actions'] && $right['delete_label_actions'];
                break;
            case 'deals':
                return $right['create_label_deals'] && $right['delete_label_deals'];
                break;
        }
        return false;
    }

    public function getChangeDateClient ($date) { 
        $dateNotTime = date('Y-m-d H:i', strtotime($date));
        $date_diff = strtotime(date('Y-m-d H:i')) - strtotime($dateNotTime);
        $day = (int) ($date_diff / 86400);

        if ($day == 0 ) { 
            $hour = (int) ((strtotime(date('Y-m-d H:i')) - strtotime($date)) / 3600);
            switch ($hour) {
                case 0:
                    return 'менее часа назад';
                    break;
                case 1:
                    return $hour . ' час назад';
                    break;
                case 2:
                case 3:
                case 4:
                case 22:
                case 23:
                case 24:
                    return $hour . ' часа назад';
                    break;
                default:
                    return $hour . ' часов назад';
                    break;
            }
        } else if ($day < 31) { 
            switch ($day) { 
                case 1: 
                case 21: 
                    return $day .' день назад'; 
                break; 
                case 2: 
                case 3: 
                case 4: 
                case 22: 
                case 23: 
                case 24: 
                    return $day .' дня назад'; 
                break; 
                default: 
                    return $day .' дней назад'; 
                break; 
            } 
        } else { 
            $month = (int) ($day / 30);
            if ($month > 11) { 
                return 'больше года назад'; 
            } 
            switch ($month) { 
                case 1: 
                    return $month . ' месяц назад'; 
                break; 
                case 2: 
                case 3: 
                case 4: 
                    return $month . ' месяца назад'; 
                break; 
                default: 
                    return $month . ' месяцев назад'; 
                break; 
            } 
        } 
    }

    public function getDateWithString($createDate, $closedDate) {
        $date_diff = strtotime($closedDate) - strtotime($createDate);
        $day = (int) ($date_diff / 86400);
        return $day . ' дней';
    }

    public function emailSend($mail, $subject, $message)
    {
        $email = Yii::app()->email;
        $email->AddAddress($mail);
        $email->Subject = $subject;
        $email->Body = '<html><body>' .
            $message
            . '</body></html>';
        $email->send();
    }

}

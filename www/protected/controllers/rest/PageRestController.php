<?php


class PageRestController extends MyRestController
{

    public function actionCreateLead()
    {
        $return = [
            'result' => 'failed',
            'description' => 'failed',
            'errors' => [],
        ];

        if (!isset(Yii::app()->params['Lead']) || !Yii::app()->params['Lead']) {
            $return['description'] = 'Disabled';
            return Json::render($return);
        }
        if (isset($_POST['fieldFio'])) {
            $transaction = Yii::app()->db->beginTransaction();
            try {
                $noSteps = false;
                if (isset($_POST['steps']) && isset($_POST['steps_options'])) {
                    $step = Steps::model()->findByAttributes(['id' => $_POST['steps'], 'steps_type_id' => 1]);
                    if ($step) {
                        $stepOption = StepsOptions::model()->findByAttributes(['weight' => $_POST['steps_options'], 'steps_id' => $_POST['steps']]);
                        if (!$stepOption) {
                            $noSteps = true;
                        }
                    }
                    if (!$step) {
                        $noSteps = true;
                    }
                } else {
                    $noSteps = true;
                }

                if ($noSteps) {
                    $step = Steps::model()->findByAttributes(['name' => 'Нет воронки', 'steps_type_id' => 1]);
                }
                if (isset($_POST['labels_in_clients'])) {
                    $label = Labels::model()->findByAttributes(['id' => $_POST['labels_in_clients'], 'type_id' => 1]);
                }

                $_POST['responsable_id'] = (isset($_POST['responsable_id']) && Users::model()->findByPk($_POST['responsable_id']))
                    ? $_POST['responsable_id']
                    : Users::model()->getAdminInCompany()->id;
                $responsible = Users::model()->findByPk($_POST['responsable_id']);
                $_POST['creator_id'] = isset($_POST['creator_id']) ? $_POST['creator_id'] : 1;
                $_POST['priority_id'] = isset($_POST['priority_id']) ? $_POST['priority_id'] : 1;
                $_POST['source_id'] = isset($_POST['source_id']) ? $_POST['source_id'] : 28;
                $_POST['city_id'] = isset($_POST['city_id']) ? $_POST['city_id'] : 29;
                $_POST['group_id'] = isset($_POST['group_id']) ? $_POST['group_id'] : 33;
                $client = new Clients();
                $client->attributes = $_POST;
                $client->name = $_POST['fieldFio'];
                $client->creation_date = date('Y-m-d H:i:s');

                if (!$client->save()) {
                    $return['errors'] = $client->getErrors();
                    throw new CHttpException('400');
                }

                if (isset($label)) {
                    $labelClient = new LabelsInClients();
                    $labelClient->label_id = $label->id;
                    $labelClient->client_id = $client->id;
                    $labelClient->save();
                }
                if (isset($step)) {
                    $stepClient = new StepsInClients();
                    $stepClient->steps_id = $step->id;
                    $stepClient->clients_id = $client->id;
                    $stepClient->selected_option_id = !$noSteps ? $stepOption->id : 0;
                    $stepClient->save();
                }

                $addFields = AdditionalFields::model()->findAll();
                $addFieldsErrors = [];
                $addFieldInClient = new AdditionalFieldsValues();
                $addFieldInClient->client_id = $client->id;
                foreach ($addFields as $field) {
                    if ($field->required && (!isset($_POST[$field->table_name]) || trim($_POST[$field->table_name]) == '')) {
                        $addFieldsErrors[$field->table_name] = 'Required';
                    }
                    /*if ($field->unique && isset($_POST[$field->table_name]) && $_POST[$field->table_name] != '') {
                        if (AdditionalFieldsValues::model()->count($field->table_name . ' = :value', [':value' => $_POST[$field->table_name]]))
                        {
                            $addFieldsErrors[$field->table_name] = 'Unique';
                        }
                    }*/
                    if (isset($_POST[$field->table_name])) {
                        switch ($field->type) {
                            case 'select':
                                $select = json_decode($field->default_value, true);
                                $select = CHtml::listData($select, 'id', 'optionName');
                                if (isset($select[$_POST[$field->table_name]])) {
                                    $addFieldInClient->{$field->table_name} = $_POST[$field->table_name];
                                } else {
                                    $addFieldsErrors[$field->table_name] = 'Incorrect';
                                }
                                break;
                            case 'date':
                                if (is_numeric($_POST[$field->table_name])) {
                                    $addFieldInClient->{$field->table_name} = $_POST[$field->table_name];
                                } elseif (trim($_POST[$field->table_name]) != '') {
                                    $addFieldInClient->{$field->table_name} = strtotime($_POST[$field->table_name] . ' +0');
                                } else {
                                    $addFieldInClient->{$field->table_name} = '';
                                }
                                break;
                            default:
                                $addFieldInClient->{$field->table_name} = $_POST[$field->table_name];
                                break;
                        }
                    } else {
                        $addFieldInClient->{$field->table_name} = '';
                    }
                }
                if (count($addFieldsErrors) > 0) {
                    $return['errors'] = array_merge($return['errors'], $addFieldsErrors);
                    throw new CHttpException('400');
                }

                if (!$addFieldInClient->save()) {
                    foreach ($addFieldInClient->getErrors() as $key => $error) {
                        if (isset($addFieldInClient->$key)) {
                            $return['errors'][$key] = $error[0];
                            $addFieldInClient->clearErrors($key);
                        }
                    }
                    $return['errors'] = array_merge($return['errors'], $addFieldInClient->getErrors());
                    throw new CHttpException('400');
                }

                $transaction->commit();
                $return = [
                    'result' => 'success',
                    'values' => $client->attributes,
                ];
            } catch (Exception $e) {
                $transaction->rollback();
            }
        } else {
            $return['errors'][] = 'Incorrect data';
        }
        if (isset($responsible)) {
            $return['values']['resp_name'] = $responsible->getFullName();
        }
        Json::render($return);
    }

    public function actionSendEmail()
    {
        $return = [
            'result' => 'failed',
            'description' => 'failed',
            'errors' => [],
        ];

        if (isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['text'])) {
            Yii::app()->commonFunction->emailSend($_POST['email'], $_POST['subject'], $_POST['text']);
            $return = ['result' => 'success'];
        } else {
            $return['description'] = 'Incorrect data';

        }
        Json::render($return);
    }

    public function actionGetAdditionalFields()
    {
        $fields = [];
        foreach (AdditionalFields::model()->findAll() as $value) {
            $fields[] = $value->attributes;
        }
        $return = [
            'result' => 'success',
            'values' => $fields,
        ];
        Json::render($return);
    }

    public function actionGetSettings()
    {
        $settings = [];
        foreach (Labels::model()->findAll() as $value) {
            $settings['labels'][] = $value->attributes;
        }
        foreach (Steps::model()->findAll() as $value) {
            $data = $value->attributes;
            foreach (StepsOptions::model()->findAllByAttributes(['steps_id' => $value->id]) as $stepOption) {
                $data['step_options'][] = $stepOption->attributes;
            }
            $settings['steps'][] = $data;
        }
        $return = [
            'result' => 'success',
            'values' => $settings,
        ];
        Json::render($return);
    }


}


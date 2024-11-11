<?php $this->pageTitle = $modelClient->name; ?>
<?php $correct_path = 'http://' . $_SERVER["HTTP_HOST"]; ?>

<?
$delete_button = CHtml::button("Удалить", array(
    'onClick' => 'window.location.href="' . Yii::app()->createUrl("page/delete_client", array("id" => $modelClient->id)) . '"',
    'class' => 'btn',
));
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'edit-client',
    'enableAjaxValidation' => false,
    'htmlOptions' => [
        'class' => 'page-form'
    ]
));
?>

<input type="hidden" value="additionalField" name="field">
<input type="hidden" value="<?= $modelClient->id ?>" name="client">

<div class="clients-hat">
    <div class="client-name">
        <?php echo CHtml::link('Контакты', array('page/clients_page')); ?>
        <img src="/img/right-arrow-button.svg" alt="">
        <div id="headerClientName"><?php echo $modelClient->name; ?></div>
        , #<?php echo $modelClient->id; ?>
    </div>

    <div class="goback-link pull-right">

        <?php
        if ($user->roles[0]->name == 'admin' || $userRight['create_action']) {
            echo CHtml::button('Новая задача', array('onClick' => 'window.location.href= "' . Yii::app()->createUrl("page/new_action",
                    array("id" => $modelClient->id)) . '"',
                'class' => 'btn_orange', 'id' => 'popup_new_client_button'));
        }

        if ($user->roles[0]->name == 'admin' || $userRight['create_deals']) {
            echo CHtml::button('Новая сделка', array('onClick' => 'window.location.href= "' .
                Yii::app()->createUrl("page/new_deal", array("id" => $modelClient->id)) . '"',
                'class' => 'btn_purple', 'id' => 'popup_new_client_button'));
        }

        if ($user->roles[0]->name == 'admin' || $userRight['create_client']) {
            echo CHtml::button('Новый контакт', array('onClick' => 'window.location.href= "' . Yii::app()->createUrl("page/new_client") . '"',
                'class' => 'btn_green', 'id' => 'popup_new_client_button'));
        }
        ?>

    </div>
</div>

<main class="content full2" role="main">

    <div class="content-edit-block">
        <!-- Табы переключатели -->
        <div class="tabs">
            <div class="user-table-block">
                <? if ($labelDealsId) {
                    $active = 'deals';
                } else {
                    $active = 'anketa';
                }
                ?>
                <ul>
                    <li class="button-change-table <? echo $active != 'anketa' ?: 'active' ?>"
                        id="button-table-anketa" data-id='anketa'
                        onclick="selectTable('anketa')">Анкета
                    </li>
                    <li class="button-change-table <? echo $active != 'actions' ?: 'active' ?>"
                        id="button-table-actions" data-id='actions'
                        onclick="selectTable('actions')">Задачи
                        <span><? echo $client_actions_table->totalItemCount ?></span></li>
                    <li class="button-change-table <? echo $active != 'deals' ?: 'active' ?>"
                        id="button-table-deals" data-id='deals'
                        onclick="selectTable('deals')">Сделки
                        <span><? echo $client_deals_table->totalItemCount ?></span></li>
                    <li class="button-change-table <? echo $active != 'files' ?: 'active' ?>"
                        id="button-table-files" data-id='files'
                        onclick="selectTable('files')">
                        Файлы<span><? echo $clientFilesTable->totalItemCount ?></span></li>

                    <li class="note-btn JModal_open" id="add-note"><a><img class="" src="/img/notes.svg"
                                                                           style="height: 13px;padding-right: 7px;">Создать
                            заметку</a></li>
                </ul>
            </div>
        </div>
        <!-- Табы переключатели -->

        <!-- Анкета -->
        <!-- Уведомление: контакт создан -->
        <div id="table-anketa" class='tab-table main-anketa'>
            <div class="content-01">
                <!-- Уведомление: контакт создан -->

                <?php if (Yii::app()->user->hasFlash('success')) { ?>
                    <script type="module">
                        import {NotificationBar} from '/js/notificationBar.js';

                        const notificationBar = new NotificationBar({
                            type: 'warning',
                            title: 'Контакт создан',
                            description: 'Теперь можно создать задачи и сделки',
                        });
                        notificationBar.show();
                    </script>
                <? } ?>

                <? foreach ($notes as $note) { ?>
                    <div id='note_<?= $note->id ?>' class="note_container">
                        <div class="note-div color-<?= $note->color ?>">
                            <div class="note-text"><?= nl2br($note->text) ?></div>
                            <div class="note-edit-block editNote sel-link" data-id="<?= $note->id ?>">
                                <span class="toggle-note-edit"></span>
                                <div class="multi-popap editNotePopup hide " id="editNote_<?= $note->id ?>">
                                    <a href="#" class="edit_note JModalEdit_open" data-id="<?= $note->id ?>"
                                       data-color="<?= $note->color ?>">Изменить</a>
                                    <a href="#" class="delete_note" data-id="<?= $note->id ?>">Удалить</a>
                                </div>
                            </div>
                            <div class="note_footer">
                                Создано: <?= $note->user->first_name ?> <?= Date('d.m.y', $note->added) ?>
                                в <?= Date('H:i', $note->added) ?>
                                <? if (!empty($note->edited)) { ?>
                                    (изменено
                                    <? if ($note->user->id != $note->edit_user->id) { ?>
                                        <?= $note->edit_user->first_name ?>
                                    <? } ?>
                                    <?= Date('d.m.y', $note->edited) ?> в  <?= Date('H:i', $note->edited) ?>)
                                <? } ?>
                            </div>
                        </div>
                    </div>
                <? } ?>
                <div class="errorAddField hide">Вы ввели некорретные данные в дополнительные поля.<br></div>
                <div class="errorAddField hide" id="numErr"><strong><span id='fErName'></span></strong> -
                    числовое поле, сюда можно записать только числа<br></div>
                <div class="errorAddField hide" id="reErr"><strong><span id='reErName'></span></strong> -
                    обязательное поле. Пожалуйста, заполните это поле и попробуйте снова<br></div>

                <div class="client-content">
                    <?php
                    foreach ($additionalFiledValuesInClient as $key => $fieldSection) {
                    ?>
                        <div class="block_client">
                            <div class="profile_info_block clear_fix">
                                <div class="profile_info_header_wrap">
                                    <span class="profile_info_header"><? echo $fieldSection[0]['sectionName'] ?></span>

                                </div>
                            </div>
                            <?php
                            foreach ($fieldSection as $value) {
                                ?>
                                <div class="block-row additionalField">

                                    <div class="row-label">
                                        <? echo $value['name']; ?>
                                        <? if ($value['required']) { ?>
                                            <div class="requiredAddField">*</div>
                                        <? } ?>
                                    </div>
                                    <div class="row-input with-image" style="margin-right: 0px;">
                                        <?
                                        $valueField = isset($additionalFiledValue[$value['table_name']]) ? $additionalFiledValue[$value['table_name']] : $value['value'];
                                        $classPositionImage = 'textSize24';
                                        if ($value['required']) {
                                            $required = 'required-control';
                                        } else {

                                            $required = '';
                                        }
                                        switch ($value['type']) {
                                            case 'checkbox':
                                                echo $form->checkBox($client, "additionalField[$value[table_name]]", ['checked' => $valueField, 'class' => "form-control_anketa"]);
                                                break;
                                            case 'date':
                                                echo $this->widget('ext.CJuiDateTimePicker.CJuiDateTimePicker', array(
                                                    'name' => "Clients[additionalField][$value[table_name]]",
                                                    'model' => $client,
                                                    'attribute' => "additionalField[$value[table_name]]",
                                                    'language' => 'ru',
                                                    'htmlOptions' => array(
                                                        'value' => isset($valueField) && is_numeric($valueField) ? date('d.m.Y', (int)$valueField) : '',
                                                        'class' => 'form-control',
                                                        'autocomplete' => 'off'
                                                    ),
                                                    'options' => array(
                                                        'dateFormat' => 'dd.mm.yy',
                                                        'changeMonth' => 'true',
                                                        'changeYear' => 'true',
                                                        'showButtonPanel' => true,
                                                        'beforeShow' => new CJavaScriptExpression('function(element){dataPickerFocus = $(element).attr(\'id\').trim();}')
                                                    ),
                                                ), true);

                                                if (count($additionalFiledValuesInClient) == $key && $key % 2 != 0) {
                                                    $classPositionImage .= ' longText';
                                                }


                                                ?>
                                                <div class="input-withImage <? echo $classPositionImage; ?>">
                                                </div>
                                                <?
                                                break;
                                            case 'varchar':
                                                $size = 'textSize24';

                                                switch ($value['size']) {


                                                    case '1/3':
                                                        $size = 'textSize24';


                                                        break;
                                                    case '1/2':
                                                        $size = 'textSize48';
                                                        $classPositionImage = 'textSize48';


                                                        break;
                                                    case '1/1':
                                                        $size = 'textSize72';
                                                        $classPositionImage = 'textSize72';
                                                        break;
                                                }
                                                echo $form->textArea($client, "additionalField[$value[table_name]]", ['class' => "form-control $required $size", 'value' => $valueField, 'data-title' => $value['name']]);
                                                if (count($additionalFiledValuesInClient) == $key && $key % 2 != 0) {
                                                    $classPositionImage .= ' longText';
                                                }
                                                ?>
                                                <div class="input-withImage <? echo $classPositionImage; ?>">
                                                </div>
                                                <?
                                                break;
                                            case 'int':
                                                if ($value['unique']) {
                                                    echo $form->textField($client, "additionalField[$value[table_name]]", ['class' => 'form-control $required', 'value' => $valueField, 'data-title' => $value['name']]);
                                                } else {
                                                    echo $form->textField($client, "additionalField[$value[table_name]]", ['class' => 'form-control numeric-control $required', 'value' => $valueField, 'data-title' => $value['name']]);
                                                }
                                                if (count($additionalFiledValuesInClient) == $key && $key % 2 != 0) {
                                                    $classPositionImage .= ' longText';
                                                }
                                                ?>
                                                <div class="input-withImage <? echo $classPositionImage; ?>">
                                                </div>
                                                <?
                                                break;
                                            case 'select':
                                                $data = [];
                                                $selected = $valueField;
                                                $modelSelect = AdditionalFields::model()->find('table_name = :TB', [':TB' => $value['table_name']]);
                                                $listOptions = json_decode($modelSelect->default_value, true);
                                                foreach ($listOptions as $option) {
                                                    $data [$option['id']] = $option['optionName'];
                                                    if (isset($option['default']) && !$selected && $option['default']) {
                                                        $selected = $option['id'];
                                                    }
                                                }
                                                echo CHtml::dropDownList("Clients[additionalField][$value[table_name]]", $selected, $data, ['class' => 'styled select']);
                                                break;
                                            default:
                                                echo $form->textField($client, "additionalField[$value[table_name]]", ['class' => 'form-control', 'value' => $valueField]);
                                                break;
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="row-label"></div>
                                <div class="row-tip-01"><? echo $value['tip']; ?></div>
                                <?php
                            }
                            ?>
                        </div>
                    <? } ?>
                </div>
            </div>
        </div>

        <!-- Список задач -->
        <div id="table-actions" class='tab-table'>

            <div class="content-01">
                <?php
                if (count($client_actions_table->data) == 0) { ?>
                    <div class="info_client_001"><p>Задач нет</p></div>
                    <?
                }
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider' => $client_actions_table,
                    'cssFile' => '',
                    'emptyText' => '',
                    'htmlOptions' => array('class' => 'new-table-main'),
                    'columns' => array(
                        array(
                            'name' => 'name',
                            'header' => 'Задачи',
                            'type' => 'raw',
                            'headerHtmlOptions' => array('class' => 'w9', 'style' =>
                                '   height: 12px;
                                border-right: 1px solid #d9d9d9;
                                border-bottom: 1px solid #d9d9d9;
                                padding: 8px 11px;
                                text-align:left;
                                font-size: 11px;
                                color: #222;
                                line-height: 12px;
                                display: none
                                '),
                            'value' => function ($data) {
                                $labelHTML = '';
                                $action_date = date('Y-m-d', strtotime($data->action_date)) . ' 23:59:59';
                                $actionStatusColor = '#F96F93';
                                if (strtotime($action_date) >= time() || $data->action_status_id != 1) {
                                    $actionStatusColor = $data->actionStatus->color;
                                }
                                $actionIndication = '<div class="labelStatusAction" style="background-color:' . $actionStatusColor . '"> </div>';

                                $criteria = new CDbCriteria;
                                foreach ($data->labelsInActions as $value) {
                                    $criteria->addCondition('t.id = ' . $value->label_id, "OR");
                                }
                                $idHTML = '<div class="block_labels">' . '<span class="idHTML"> #' . $data->id . '</span>' .
                                    '<span class="werwe"></span>' . $data->actionStatus->name .

                                    '</span>';
                                //
                                if ($criteria->condition != '' && $labels = Labels::model()->findAll($criteria)) {
                                    $labelHTML = '';
                                    $type = "'Actions'";
                                    foreach ($labels as $label) {
                                        $labelHTML .= '<div onclick="clickLabel(' . $data->client_id . ',' . $label->id . ',' . $type . ')" class="custom-label pointer" style="background-color: ' . $label->color . '; color:' . $label->color_text . '">' . $label->name . '</div>';
                                    }
                                    $labelHTML .= '</div>';
                                }
                                $dddd2 = ' <span class="new-table-date_actions">'

                                    .
                                    '<a class="support" tabindex="1">
                                          ' . date('d.m.Y' . ' в ' . 'H:i', strtotime($data->action_date)) . '

                                          <span class="tip">
                                          <div class="chok_wert">
                                          <div class="chok" style="font-weight: bold;padding-bottom: 10px;">' . $data->text . '</div>
                                          <div class="chok">' . $data->description . '</div>
                                          </div>
                                          <div class="chok_life">
                                          <span class="chol">' . $data->actionStatus->name . ': </span>' . date('d.m.Y' . ' в ' . 'H:i', strtotime($data->action_date)) . '</div>
                                          <div class="chok"><span class="chol">Ответственный:  </span>' . $data->responsable->first_name . '</div>
                                          </span>
                                          </a>' .


                                    '</span>';


                                return
                                    '<div class="new-table">
                                          <div class="new-table-left">
                                          <div class="new-table-header">
                                          <div class="new-table-name-client"> ' . $actionIndication . CHtml::link($data->text, Yii::app()->createUrl("page/edit_action", array("id" => $data->id, "render_page" => 'actions_page')), ['class' => 'float-left']) . (count($data->actionsFiles) > 0 ? '<a class="file_add" tabindex="1"><img src="/img/paper-clip.svg"></a>' : '') .
                                    '<span class="sdf2">' .

                                    ($data->responsable->avatar ? CHtml::image($data->responsable->avatar, '', ['class' => 'miniAvatar']) : CHtml::image($data->responsable->roles[0]->name == 'manager' ? '/img/employee.svg' : ($data->responsable->roles[0]->name == 'director' ? '/img/ava_adminisrtr.svg' : '/img/ava_admin.svg'), '', ['class' => 'miniAvatar'])) . CHtml::link($data->responsable->first_name, Yii::app()->createUrl("page/user_profile", array("id" => $data->responsable->id))) .


                                    '</div>
                                          <div class="new-table-name-resp"></div>
                                          </div>
                                          <div class="new-table-bottom">' . $idHTML . $labelHTML . $dddd2 . '</div>

                                          </div>';

                            }
                        ),
                    )));
                ?>
                <?php
                if ($user->roles[0]->name == 'admin' || $userRight['create_action']) {
                    echo CHtml::button('Новая задача', array('onClick' => 'window.location.href= "' .
                        Yii::app()->createUrl("page/new_action", array("id" => $modelClient->id)) . '"', 'class' => 'add-btn__set'
                    ));
                }
                ?>
            </div>
        </div>

        <!-- Список сделок -->
        <div id="table-deals" class='tab-table'>
            <div class="content-01">
                <? if (count($client_deals_table->data) == 0) { ?>
                    <div class="info_client_001"><p>Сделок нет</p></div>
                    <?
                }
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider' => $client_deals_table,
                    'cssFile' => '',
                    'emptyText' => '',
                    'htmlOptions' => array('class' => 'new-table-main'),
                    'columns' => array(
                        array(
                            'name' => 'name',
                            'header' => 'Сделки',
                            'type' => 'raw',
                            'headerHtmlOptions' => array('class' => 'w9', 'style' =>
                                '   height: 12px;
                                          border-right: 1px solid #d9d9d9;
                                          border-bottom: 1px solid #d9d9d9;
                                          padding: 8px 11px;
                                          text-align:left;
                                          font-size: 11px;
                                          color: #222;
                                          line-height: 12px;
                                          display: none
                                          '),
                            'value' => function ($data) {

                                $criteria = new CDbCriteria;
                                foreach ($data->labelsInDeals as $value) {
                                    $criteria->addCondition('t.id = ' . $value->label_id, "OR");
                                }
                                $idHTML = '<div class="block_labels">' . '<span class="idHTML"> #' . $data->id . '</span>' .

                                    '<span class="werwe"></span>' . round($data->paid) . ' / ' . round($data->balance);

                                $labelHTML = '';
                                $stepName = '';
                                if ($criteria->condition != '' && $labels = Labels::model()->findAll($criteria)) {
                                    $type = "'Deals'";
                                    foreach ($labels as $label) {
                                        $labelHTML .= '<div onclick="clickLabel(' . $data->client_id . ',' . $label->id . ',' . $type . ')" class="custom-label pointer" style="background-color: ' . $label->color . '; color:' . $label->color_text . '">' . $label->name . '</div>';
                                    }
                                }
                                $labelHTML .= '</div>';

                                $stepOptionColor = '';
                                $stepOptionName = '';
                                if ($step = StepsInDeals::model()->with('steps')->find('deals_id = :ID', [':ID' => $data->id])) {
                                    if ($step->selected_option_id && $stepSelectedOption = StepsOptions::model()->findByPk($step->selected_option_id)) {
                                        $stepOptionColor = $stepSelectedOption->color;
                                        $stepOptionName = $stepSelectedOption->name;
                                    }

                                    if ($step->steps_id == 1 || $step->steps_id == 2) {
                                        $stepOptionName = $step->steps->name;
                                    }
                                    $stepName = $step->steps->name;
                                }

                                $dddd2 = '<span class="new-table-date_actions flex-start">' .
                                    '<span class="tooltip"> ' .
                                    '<span class="stepIndication" style="background-color:' . $stepOptionColor . '">' . '</span>' . '<span style="padding-left: 13px;">' . $stepOptionName . '</span>' . '<span class="tooltiptext tooltip-bottom">' . $stepName . '</span>' . '</span>' .
                                    '</span>';

                                $dealTypeClass = [
                                    1 => 'dealTypeActiveSquare',
                                    2 => 'dealTypeWinSquare',
                                    3 => 'dealTypeLoseSquare',
                                ];

                                return
                                    '<div class="new-table">
                                                        <div class="new-table-left">
                                                        <div class="new-table-header">
                                                        <div class="new-table-name-client">' . '<div class="' . $dealTypeClass[$data->deal_type_id] . '"></div>' . CHtml::link($data->text, Yii::app()->createUrl("page/edit_deal", array("id" => $data->id, "render_page" => 'dealings_page'))) . (count($data->dealsFiles) > 0 ? '<a class="file_add" tabindex="1"><img src="/img/paper-clip.svg"></a>' : '') .

                                    '<span class="sdf2">' .

                                    ($data->responsable->avatar ? CHtml::image($data->responsable->avatar, '', ['class' => 'miniAvatar']) : CHtml::image($data->responsable->roles[0]->name == 'manager' ? '/img/employee.svg' : ($data->responsable->roles[0]->name == 'director' ? '/img/ava_adminisrtr.svg' : '/img/ava_admin.svg'), '', ['class' => 'miniAvatar'])) . CHtml::link($data->responsable->first_name, Yii::app()->createUrl("page/user_profile", array("id" => $data->responsable->id))) .

                                    '</div>
                                                        <div class="new-table-name-resp"></div>
                                                        </div>
                                                        <div class="new-table-bottom">' . $idHTML . $labelHTML . $dddd2 . '</div>
                                                        </div>';
                            }
                        ),
                    )));
                ?>
                <?php
                if ($user->roles[0]->name == 'admin' || $userRight['create_deals']) {
                    echo CHtml::button('Новая сделка', array('onClick' => 'window.location.href= "' .
                        Yii::app()->createUrl("page/new_deal", array("id" => $modelClient->id)) . '"', 'class' => 'add-btn__set'
                    ));
                }
                ?>
            </div>
        </div>

        <!-- Список файлов -->
        <div id="table-files" class='tab-table'>
            <div class="content-01">
                <? if (count($clientFilesTable->data) == 0) { ?>
                    <div class="info_client_001"><p>Файлов нет</p></div>
                    <?
                }
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider' => $clientFilesTable,
                    'cssFile' => '',
                    'emptyText' => '',
                    'htmlOptions' => array('class' => 'new-table-main'),
                    'columns' => array(
                        array(
                            'name' => 'name',
                            'header' => 'Задачи',
                            'type' => 'raw',
                            'headerHtmlOptions' => array('class' => 'w9', 'style' =>
                                '   height: 12px;
                                                        border-right: 1px solid #d9d9d9;
                                                        border-bottom: 1px solid #d9d9d9;
                                                        padding: 8px 11px;
                                                        text-align:left;
                                                        font-size: 11px;
                                                        color: #222;
                                                        line-height: 12px;
                                                        display: none
                                                        '),
                            'value' => function ($data) {
                                $user = Users::model()->with(['userRights'])->findByPk(Yii::app()->user->id);
                                $userRight = Yii::app()->commonFunction->getUserRight($user);
                                if ($userRight['role'] == 'admin' || $userRight['delete_files_client']) {
                                    $del = CHtml::image('/img/cancel.svg', '', ['class' => 'delDocument2', 'onClick' => 'delDocument(' . $data->id . ')']);
                                } else {
                                    $del = '';
                                }

                                return
                                    '<div class="new-table">
                                                              <div class="file_list">                     <div class="file-sort">' . CHtml::link($data->file->name, Yii::app()->createUrl("page/get_file_client", ["id" => $data->id, "render_page" => 'dealings_page'])) . '                                      </div>
                                                              </div>
                                                              <div class="del_icon">' . $del . '</div>' .
                                    '</div>';
                            }
                        ),
                    )));
                ?>
                <div id="fileBlock"></div>

                <?php
                if ($userRight['role'] == 'admin' || $userRight['add_files_client']) {
                    $fileSettings = Yii::app()->commonFunction->getFileSettings(); ?>
                    <div>
                        <?php
                        $this->widget('ext.EAjaxUpload.EAjaxUpload',
                            array(
                                'id' => 'uploadFile',
                                'config' => array(
                                    'multiple' => true,
                                    'action' => '/page/UploadClientFile?id=' . $modelClient->id,
                                    'allowedExtensions' => explode(',', str_replace(' ', '', $fileSettings['extFile'])),//array("jpg","jpeg","gif","exe","mov" and etc...
                                    'sizeLimit' => $fileSettings['sizeFile'] * 1024 * 1024,// maximum file size in bytes
                                    'onComplete' => "js:function(id, fileName, responseJSON){addFileBlock(responseJSON);}",
                                    'messages' => array(
                                        'typeError' => "Ошибка! Расширение файла {file} не поддерживается. Разрешенные типы файлов: {extensions}.",
                                        'sizeError' => "{file} максимальный размер файла {sizeLimit}.",
                                        //                  'minSizeError'=>"{file} is too small, minimum file size is {minSizeLimit}.",
                                        //                  'emptyError'=>"{file} is empty, please select files again without it.",
                                        //                  'onLeave'=>"The files are being uploaded, if you leave now the upload will be cancelled."
                                    ),
                                    //'showMessage'=>"js:function(message){ alert(message); }"
                                )
                            ));

                        ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>

    <div class="box-gray111 width-static">
        <div class="edit_user_1anketa">
            <div class="title_name_2">Параметры</div>
            <div class="popup__form_anketa">

                <div class="client_info_anketa">
                    Ответственный:
                    <span class="popup-btn sel-link" data-popup='master-modal-box'>Изменить</span>
                </div>
                <div class="solid_an">
                    <div id="master-modal-box" class="multi-popap edit-popup hide"
                         style="margin-top: 0px;">
                        <div>

                            <div class="modal-steps-head">
                                Назначить ответственного
                                <div class="modal-steps-head-close close-modal"></div>
                            </div>

                            <div class="padding-15">
                                <?php
                                $responsible_options = array('i' => 'Я ответственный', 'director' => 'Руководители',
                                    'manager' => 'Менеджеры', 'no' => $user->parent->first_name);

                                $managers = Users::getUserAccess($user, true, false, true);
                                $directors = Users::getUserAccess($user, false, true, true);
                                if ($user->parent->roles[0]->name != 'admin' || $user->common_access == Users::ACCESS_EMBAGRO
                                    || $user->roles[0]->name == 'admin'
                                ) {
                                    unset($responsible_options['no']);
                                }

                                if (count($directors) <= 0) {
                                    unset($responsible_options['director']);
                                }

                                if (count($managers) <= 0) {
                                    unset($responsible_options['manager']);
                                }

                                if ($user->parent->roles[0]->name == 'director') {
                                    unset($responsible_options['director']);
                                    $responsible_options[$user->parent_id] = $user->parent->first_name;

                                } elseif ($user->parent->roles[0]->name == 'manager') {
                                    $responsible_options[$user->parent_id] = $user->parent->first_name;
                                    unset($responsible_options['manager']);
                                }

                                // выбор значения в селекторе
                                $client_resp_role = UsersRoles::model()->find('user_id=' . $client->responsable_id);
                                if ($client->responsable_id == Yii::app()->user->id) {
                                    $selected_option = array('i' => array('selected' => true));
                                } elseif ($client_resp_role->itemname == 'director') {
                                    $selected_option = array('director' => array('selected' => true));
                                } elseif ($client_resp_role->itemname == 'manager') {
                                    $selected_option = array('manager' => array('selected' => true));
                                } else {
                                    $selected_option = array('no' => array('selected' => true));
                                }

                                $directors_block_to_display = $client_resp_role->itemname == 'director' && count($managers) > 0
                                && key($selected_option) != 'i' && $client->responsable_id != $user->parent_id ? 'style="display:block"' : '';
                                $managers_block_to_display = $client_resp_role->itemname == 'manager' && count($managers) > 0
                                && key($selected_option) != 'i' && $client->responsable_id != $user->parent_id ? 'style="display:block"' : '';
                                ?>
                                <?php echo $form->dropDownList($client, 'responsable_id', $responsible_options,
                                    array(
                                        'options' => $selected_option,
                                        'class' => 'styled permis editable typeAccess',
                                        'id' => 'responsable_type',
                                        'name' => 'type')
                                ); ?>

                                <div class="access-options access-tab"
                                     id="director" <?php echo $directors_block_to_display ?>>
                                    <?php echo $form->dropDownList($client, 'director_id', CHtml::listData($directors, 'id', 'first_name'),
                                        array('options' => array($client->responsable_id => array('selected' => true)), 'class' => 'styled')); ?>
                                </div>
                                <div class="access-options access-tab"
                                     id="manager" <?php echo $managers_block_to_display ?>>
                                    <?php echo $form->dropDownList($client, 'manager_id', CHtml::listData($managers, 'id', 'first_name'),
                                        array('options' => array($client->responsable_id => array('selected' => true)), 'class' => 'styled')); ?>
                                </div>

                                <? if ($user->roles[0]->name == 'admin' or $user->roles[0]->name == 'director') { ?>
                                    <div class='modal-foot' style="margin-top: 15px;">
                                        <a href="/page/create_user" target="_blank">Создать пользователя</a>
                                        <a href="/page/user_info" target="_blank">Управление пользователями</a>

                                    </div>
                                <? } ?>
                            </div>

                        </div>
                    </div>
                    <?php echo $modelClient->responsable->avatar ?
                        CHtml::image($modelClient->responsable->avatar, '', ['class' => 'Ava_client_profile', 'id' => 'master-avatar'])
                        : CHtml::image($modelClient->responsable->roles[0]->name == 'manager' ? '/img/employee.svg' : ($modelClient->responsable->roles[0]->name == 'director' ? '/img/ava_adminisrtr.svg' : '/img/ava_admin.svg'), '', ['class' => 'Ava_client_profile', 'id' => 'master-avatar']);
                    ?>

                    <div class="ava_responsible"><span
                                id='master-name'><?php echo $modelClient->responsable->first_name; ?></span><br>
                        <span class="user_type"
                              id='master-type-name'><?php echo Users::getRole($modelClient->responsable->roles[0]->name); ?></span>
                    </div>
                </div>

                <div class="client_info_anketa">
                    Воронка:
                    <span class="popup-btn sel-link" data-popup='step-modal-box'>Изменить</span>
                </div>
                <div class="solid_an">
                    <div id="step-modal-box" class="multi-popap hide edit-popup edit-popup2"
                         style="margin-top: 0px;">
                        <div>
                            <div class="modal-steps-head">
                                Назначить воронку
                                <div class="modal-steps-head-close close-modal"></div>
                            </div>
                            <div class="padding-15">

                                <?php echo $form->dropDownList($selectedSteps, 'steps_id',
                                    CHtml::listData($listStep, 'id', 'name'), ['class' => 'styled', 'onChange' => 'changeStep()', 'id' => 'selectStep']); ?>


                                <? if ($isNotStepOptions = isset($listStepOption[$selectedSteps->steps_id])) {
                                    $selectedOption = $listStepOption[$selectedSteps->steps_id][0];
                                    foreach ($listStepOption[$selectedSteps->steps_id] as $option) {
                                        if ($option->id == $selectedSteps->selected_option_id) {
                                            $selectedOption = $option;
                                            break;
                                        }
                                    }
                                } else {
                                    $selectedOption = (object)['color' => '', 'id' => '', 'name' => ''];
                                }

                                // для JS
                                $listStepOptionJS = [];
                                foreach ($listStepOption as $stepID => $options) {
                                    foreach ($options as $key => $option) {
                                        $listStepOptionJS[$stepID][] = $option->attributes;
                                    }
                                }
                                ?>

                                <div class="row-input" id="colorSelect"
                                     style="display: <? echo $isNotStepOptions ? 'inline-flex' : 'none' ?>">
                                    <div class="jq-selectbox__select color-select client"
                                         onclick="showDropDawnColor(event)">
                                        <div class="color-block"
                                             style="background-color: <? echo $selectedOption->color ?>">
                                            <span><? echo $selectedOption->name ?> </span>
                                            <input type="text" value="<? echo $selectedOption->id ?>" class="hide"
                                                   name="MainStepsInClients[selected_option_id]">
                                        </div>
                                        <div class="jq-selectbox__trigger">
                                            <div class="jq-selectbox__trigger-arrow"></div>
                                        </div>
                                    </div>

                                    <div class="color-customDropDawnList client shortWidth hide">
                                        <ul>
                                            <?
                                            if ($isNotStepOptions) {
                                                foreach ($listStepOption[$selectedSteps->steps_id] as $id => $option) {
                                                    echo "<li value='$id' onclick='changeColor(event, " . '"' . $option->color . '",' . " " . '"' . $option->name . '", ' . $option->id . ")'><div class='block-color' style='background-color:$option->color;'></div><div class='margin-top-1'>$option->name</div></li>";
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>

                                <div class="step-progressBar"
                                     style="display: <? echo $isNotStepOptions ? 'inline-flex' : 'none' ?>">
                                    <? if ($isNotStepOptions) { ?>
                                        <?
                                        $isGrey = false;
                                        foreach ($listStepOption[$selectedSteps->steps_id] as $id => $option) {
                                            $color = $isGrey ? 'darkgrey' : $option->color;
                                            echo "<div class='progressBar-elem' style='background-color:" . $color . "' ></div>";
                                            if ($option->id == $selectedSteps->selected_option_id) {
                                                $isGrey = true;
                                            }
                                        } ?>
                                    <? } ?>
                                </div>

                                <? if ($user->roles[0]->name == 'admin' or $userRight['create_steps'] == 1) { ?>
                                    <div class='modal-foot' style="margin-top: 15px;">
                                        <a href="/page/new_step?type=clients" target="_blank">Создать воронку</a>
                                        <a href="/page/settings_steps" target="_blank">Управление воронками</a>

                                    </div>
                                <? } ?>
                            </div>

                        </div>
                    </div>
                    <div class="">
                        <span id="step-name"><? echo $stepsInfo['optionName'] ?></span>
                    </div>
                    <div id='step-bar' class="step-progressBar">
                        <? if (isset($stepsInfo['steps_id']) && $stepsInfo['steps_id'] != 1) { ?>

                            <? if (isset($stepsInfo['options'])) {
                                foreach ($stepsInfo['options'] as $key => $option) {
                                    $color = $stepsInfo['selectedIndex'] < $key ? 'darkgrey' : $option['color'];
                                    echo "<div class='progressBar-elem' style='background-color:" . $color . "' ></div>";
                                }
                            } ?>

                        <? } ?>
                    </div>
                </div>

                <div class="client_info_anketa">
                    Метки: <span class="popup-btn sel-link" data-popup='label-modal-box'>Изменить</span>
                </div>

                <div id="label-modal-box" class="multi-popap hide edit-popup edit-popup2"
                     style="margin-top: 0px;">
                    <div>

                        <div class="modal-steps-head">
                            Назначить метку
                            <div class="modal-steps-head-close close-modal"></div>
                        </div>

                        <div class="label-filter">
                            <div class="customDropDownListLabelsFilterFilter  ">


                                <ul>
                                    <? foreach ($allLabels as $label) { ?>
                                        <li id="labelLi <? echo $label->id ?>" class="labelLi"
                                            name="Clients[labelLi<? echo $label->id ?>]"
                                            onclick="changeLabel('<? echo $label->id; ?>');">
                                            <?
                                            echo $form->checkBox($client, "Labels[$label->id]", [
                                                'id' => 'checkbox' . $label->id,
                                                'class' => 'hide lebel-checkbox',
                                                'data-id' => $label->id,
                                                'checked' => isset($customSelectedLabels[$label->id])
                                            ]);
                                            $operType = isset($customSelectedLabels[$label->id]) ?
                                                'added' : 'deleted';
                                            ?>
                                            <div class="<? echo $operType; ?>"
                                                 id="blockOper<? echo $label->id; ?>"></div>
                                            <div class="block-color" id="labelColor<? echo $label->id; ?>"
                                                 style="background-color: <? echo $label->color ?>"></div>
                                            <span id="labelText<? echo $label->id; ?>"><? echo $label->name ?></span>
                                        </li>
                                    <? } ?>

                                </ul>

                                <? if ($user->roles[0]->name == 'admin' or $userRight['create_label_clients'] == 1) { ?>
                                    <div class='modal-foot' style="margin-top: 15px;">
                                        <a href="/page/new_label?type=clients" target="_blank">Создать метку</a>
                                        <a href="/page/settings_labels" target="_blank">Управление метками</a>

                                    </div>
                                <? } ?>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="solid_an">
                    <div class="block-labelsInProfile">
                        <? foreach ($customSelectedLabels as $label) { ?>
                            <div class="block-elem" id="blockElem<? echo $label->id ?>">
                                <div class="block-color"
                                     style="background-color: <? echo $label->color ?>"></div>
                                <span><? echo $label->name ?></span>
                            </div>
                        <? } ?>
                    </div>
                </div>

                <div class="" style="margin-top: 10px;">
                    Дата создания: <span class="editable"
                                         rel="date"><?php echo date('d.m.y', strtotime($modelClient->creation_date)); ?></span>
                    в <span class="editable"
                            rel="time"><?php echo date('H:i', strtotime($modelClient->creation_date)); ?></span>
                </div>

                <div class="" style="margin-top: 5px;margin-bottom: 15px;">
                    Дата изменения: <span class="editable"
                                          rel="date"><?php echo date('d.m.y', strtotime($modelClient->change_client_date)); ?></span>
                    в <span class="editable"
                            rel="time"><?php echo date('H:i', strtotime($modelClient->change_client_date)); ?></span>
                </div>

                <div class="form-group_actions" style="padding-top: 10px;">
                    <?php
                    if ($user->roles[0]->name == 'admin' || $userRight['create_client']) {
                        echo CHtml::button('Сохранить', array('id' => 'saveClientBtn', 'class' => 'maui_btn'
                        ));
                    }
                    ?>

                    <div style="margin-left:5px" class="loader loader-center hide"><img
                                src="/img/preloader/103.gif"></div>
                    <? if ($user->roles[0]->name == 'admin' || $userRight['create_client']) { ?>
                        <input type="button" class="foton_btn popup-btn-01 down_btn" id="save_and_create"
                               name="save_and_create" data-popup='btn-modal-box' value="Сохранить + задача">
                    <? } ?>
                    <div id="btn-modal-box" class="multi-popap hide edit-popup-btn"
                         style="margin-top: 0px;margin-left: 0px;">
                        <div class="act_btn" data-url='/page/new_action/<?= $modelClient->id ?>'>Сохранить +
                            задача
                        </div>
                        <div class="act_btn" data-url='/page/new_deal/<?= $modelClient->id ?>'>Сохранить + сделка
                        </div>
                        <div class="act_btn" data-url='/page/new_client'>Сохранить + контакт</div>
                    </div>
                </div>
                <?
                if ($user->roles[0]->name == 'admin' || $userRight['delete_client']) {
                    echo '
              <div class="function-delete" style="display: block;padding-left: 0px;text-align: center;">
              <a class="delete" href="#">Удалить контакт</a>
              </div>
              <div class="function-delete-confirm" style="display: none;">
              <ul class="horizontal_2">
              <li>Задачи, сделки и файлы контакта тоже будут  удалены</li>
              <li><a href="#"  class="cancel" style="margin-right: 10px;">Отмена</a></li>
              <li style="padding-top: 9px;">' . $delete_button . '</li>
              </ul>
              </div>';
                }
                ?>
            </div>

        </div>
    </div>

    <?php $this->endWidget(); ?>


</main>
<!--Новая заметка-->
<div id="JModal" class="popup_content hide" style="display: none;">
    <div class="popup-j-head">
        Создать заметку
        <div class="popup-j-head-close JModal_close"></div>
    </div>
    <div class="err-box hide" id='new-note-error'>Заполните текст заметки!</div>

    <div class="note-editor-box active">
        <textarea class="color-1" id="note-text"></textarea>
    </div>
    <div class="note-color-box">
        <div class="color-box color-box-new color-1 active-color" data-color="1"></div>
        <div class="color-box color-box-new color-2" data-color="2"></div>
        <div class="color-box color-box-new color-3" data-color="3"></div>
        <div class="color-box color-box-new color-4" data-color="4"></div>
        <div class="color-box color-box-new color-5" data-color="5"></div>
    </div>

    <div class="note-foot">
        <input type="hidden" id='note_color' value="1">
        <input type="hidden" id='note_id' value="0">
    </div>
    <div style="width: 110px;margin: 5px 20px 0px 50px;display: inherit;padding-bottom: 25px;">
        <input class="maui_btn" id="save-note" type="button" name="yt0" value="Добавить">
    </div>
    <div style="float:right;margin: 9px  60px 0px 0px;" id="loader-add" class="hide"><img src="/img/preloader/103.gif">
    </div>
</div>
<!--Текущая заметка-->
<div id="JModalEdit" class="popup_content hide" style="display: none;">
    <div class="popup-j-head">
        Изменить заметку
        <div class="popup-j-head-close JModalEdit_close"></div>
    </div>

    <div class="err-box hide" id='edit-note-error'>Заполните текст заметки!</div>

    <div class="note-editor-box active">
        <textarea class="color-1" id="note-edit_text"></textarea>
    </div>

    <div class="note-color-box" id="edit-note-color-box">
        <div class="color-box color-box-edit color-1 active-color" data-color="1"></div>
        <div class="color-box color-box-edit color-2" data-color="2"></div>
        <div class="color-box color-box-edit color-3" data-color="3"></div>
        <div class="color-box color-box-edit color-4" data-color="4"></div>
        <div class="color-box color-box-edit color-5" data-color="5"></div>
    </div>

    <div class="editor-footer" style="padding: 0px 59px 10px 50px;"></div>

    <div class="note-foot">
        <input type="hidden" id='note-edit_color' value="1">
        <input type="hidden" id='note-edit_id' value="0">
    </div>

    <div style="display: inline-flex;">
        <div style="width: 110px;margin: 5px 10px 0px 50px;display: inherit;padding-bottom: 25px;">
            <input class="maui_btn" id="save-edit-note" type="button" name="yt0" value="Сохранить">

        </div>
        <div style="margin-top: 4px;"><a class="delete_note_modal" href="#">Удалить заметку</a></div>
    </div>

    <div style="float:right;margin: 9px  60px 0px 0px;" id="loader-edit" class="hide"><img src="/img/preloader/103.gif">
    </div>
</div>

<script src="/js/popup.js"></script>

<script>
    var clientId = <?echo $modelClient->id?>

        clickLabel = function (clientId, labelId, type) {
            document.location.href = '/page/client_profile?id=' + clientId + '&label' + type + 'Id=' + labelId;
        };


    listOption = <?echo json_encode($listStepOptionJS)?>;
    $(document).ready(function () {
        <?if(!empty($labelActionsId)){?> selectTable('actions');  <?}?>
        <?if(!empty($labelDealsId)){?> selectTable('deals');  <?}?>

        function addFileBlock(json) {
            <?
            if ($userRight['role'] == 'admin' || $userRight['delete_files_client']) {
            ?>
            $("#fileBlock").append(
                '<a target="_blank" class="file_list_new" href="/page/get_file_client/' + json.fileId + '">' + json.filename + '</a>' +
                '<img class="delDocument3" onclick="delDocument(' + json.fileId + ')" src="/img/cancel_newdoc.svg" alt="">' +
                '<br>');
            <?
            } else { ?>
            $("#fileBlock").append(
                '<a target="_blank" class="file_list_new" href="/page/get_file_client/' + json.fileId + '">' + json.filename + '</a>' +
                '<br>');
            <?
            }
            ?>
            $("li.qq-upload-success").remove();
        }
    })
</script>


<script type="module" src="/js/profile.js"></script>

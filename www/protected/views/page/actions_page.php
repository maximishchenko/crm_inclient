<?php $this->pageTitle = 'Задачи'; ?>

<? if (Yii::app()->user->hasFlash('action_edit_success')) { ?>
    <script type="module">
        import {NotificationBar} from '/js/notificationBar.js';

        const notificationBar = new NotificationBar({
            type: 'success',
            title: '',
            description: <? echo "'" . Yii::app()->user->getFlash('action_edit_success') . "'" ?>
        });
        notificationBar.show();
    </script>
<? } ?>

<div class="clients-hat">
    <div class="goback-link pull-right">
        <nav class="clients-nav navbar">
            <ul class="nav navbar-nav">
                <li <?php echo $term == '4' ? 'class="active"' : '' ?> ><?php echo CHtml::link('Просроченные', Yii::app()->createUrl("page/actions_page", array("term" => '4'))) . '<span class="">' . $actionCountTerm4 . '</span>'; ?></li>
                <li <?php echo $term == '1' ? 'class="active"' : '' ?> ><?php echo CHtml::link('Сегодня', Yii::app()->createUrl("page/actions_page", array("term" => '1'))) . '<span class="">' . $actionCountTerm1 . '</span>'; ?></li>
                <li <?php echo $term == '2' ? 'class="active"' : '' ?> ><?php echo CHtml::link('Будущие', Yii::app()->createUrl("page/actions_page", array("term" => '2'))) . '<span class="">' . $actionCountTerm2 . '</span>'; ?></li>
                <li <?php echo $term == '3' ? 'class="active"' : '' ?> ><?php echo CHtml::link('Выполненные', Yii::app()->createUrl("page/actions_page", array("term" => '3'))) . '<span class="">' . $actionCountTerm3 . '</span>'; ?></li>
            </ul>
        </nav>
    </div>

    <? if (Yii::app()->user->hasFlash('action_create_success')) { ?>
        <script type="module">
            import {NotificationBar} from '/js/notificationBar.js';

            const notificationBar = new NotificationBar({
                type: 'warning',
                title: 'Задача создана',
                description: <? echo "'" . Yii::app()->user->getFlash('action_create_success') . "'" ?>
            });
            notificationBar.show();
        </script>
    <? } ?>

    <div class="client-name" style="font-size: 11px;">
        <div id='sumary-div'></div>
        <div id='sel-sumary-div'></div>
    </div>
</div>

<main class="content full2" role="main">
    <?php $this->renderPartial('_actions_search', array(
        'actions' => $actions,
        'user' => $user,
        'term' => $term,
        'allLabels' => $allLabels,
        'customSelectedLabels' => $customSelectedLabels,)); ?>

    <div class="box-gray">
        <div class="box-gray__body no-border bottom_margin">
            <div class="select-tab">

                <div class="miltuBtn">
                    <input type="checkbox" id=select_all class="form-control_1 checkBox" style="margin: 0px;">
                </div>
                <? if ($user->roles[0]->name == 'admin' || $userRight['delete_action'] == 1) { ?>
                    <div class="miltuBtn">
                        <a href="#" class="show-popap sel-link disbl" data-target='del-modal-box'>Удалить</a>

                        <div id="del-modal-box" class="multi-popap hide">
                            <div class="modal-steps-head">Удаление задач
                                <div class="modal-steps-head-close close-modal"></div>
                            </div>
                            <div id='del-modal-text'>
                                <p>Будет удалено: <span id='del_cnt'></span></p>
                                <form onsubmit="return false" class="op-00">
                                    <div>
                                        <input type="button" class="btn" value="Удалить" id='delBtn'
                                               data-url='/page/del_actions' data-title="Задачи"
                                               style="width: 100%;">
                                    </div>
                                    <div>
                                        <input type="button" class="btn_back width-100 margin-top-10" value="Отмена"
                                               onclick="ClosePopup()"/>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <? } ?>

                <div class="miltuBtn">
                    <a href="#" id='editEvetnsBtn' class="sel-link disbl show-form"
                       data-target='edit-actions-block'>Изменить</a>

                </div>


                <div class="miltuBtn">
                    <a href="#" class="show-popap sel-link disbl" data-target='label-modal-box'>Метки</a>


                    <div id="label-modal-box" class="multi-popap hide">
                        <div class="modal-steps-head">
                            Назначить метку
                            <div class="modal-steps-head-close close-modal"></div>
                        </div>
                        <? if (count($allLabels) > 0) { ?>

                    <?
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'lebel-modal-form',
                    )); ?>
                        <div class="padding-15">
                            <div class="customDropDownListLabelsFilterFilter  ">
                                <ul>
                                    <li id="labelLiFilter no" class="labelLi" name="ClientsF[labelLino]"
                                        onclick="changeLabelFilter('no');">
                                        <?
                                        echo $form->checkBox($clients, "LabelsFilter[no]", [
                                            'id' => 'checkboxFilterno',
                                            'class' => 'hide',
                                            'checked' => isset($customSelectedLabels['no'])
                                        ]);
                                        $operType = isset($customSelectedLabels['no']) ?
                                            'added' : 'deleted';
                                        ?>
                                        <div class="<? echo $operType; ?> label-form" id="blockOperFilterno"
                                             data-id="no" data-text="Нет меток"></div>
                                        <div class="block-color" id="labelColorFilterno"
                                             style="background-color :black "></div>
                                        <span id="labelTextFilterno">Нет меток</span>
                                    </li>

                                    <? foreach ($allLabels as $label) { ?>
                                        <li id="labelLiFilter <? echo $label->id ?>" class="labelLi"
                                            name="ClientsF[labelLi<? echo $label->id ?>]"
                                            onclick="changeLabelFilter('<? echo $label->id; ?>');">
                                            <?
                                            echo $form->checkBox($clients, "LabelsFilter[$label->id]", [
                                                'id' => 'checkboxFilter' . $label->id,
                                                'class' => 'hide',
                                                'checked' => isset($customSelectedLabels[$label->id])
                                            ]);
                                            $operType = isset($customSelectedLabels[$label->id]) ?
                                                'added' : 'deleted';
                                            ?>
                                            <div class="<? echo $operType; ?> label-form"
                                                 id="blockOperFilter<? echo $label->id; ?>"
                                                 data-id="<? echo $label->id; ?>"
                                                 data-text="<? echo $label->name ?>"></div>
                                            <div class="block-color" id="labelColorFilter<? echo $label->id; ?>"
                                                 style="background-color: <? echo $label->color ?>"></div>
                                            <span id="labelTextFilter<? echo $label->id; ?>"><? echo $label->name ?></span>
                                        </li>
                                    <? } ?>

                                </ul>
                            </div>


                            <? } ?>

                            <div class="padding-top-15">
                                <input class="btn" id="setActionLabelBtn" type="button" value="Применить"
                                       style="width: 100%;margin-bottom: 10px;">
                            </div>
                            <? if ($user->roles[0]->name == 'admin' OR $userRight['create_label_actions'] == 1) { ?>
                                <div class='modal-foot'>
                                    <a href="/page/new_label?type=actions" target="_blank">Создать метку</a>
                                    <a href="/page/settings_labels#actions" target="_blank">Управление метками</a>

                                </div>
                            <? } ?>
                        </div>

                        <?php $this->endWidget(); ?>
                    </div>

                </div>

                <div class="miltuBtn">
                    <a href="#" class="show-popap sel-link disbl" data-target='master-modal-box'>Ответственный</a>


                    <div id="master-modal-box" class="multi-popap hide">
                        <div class="modal-steps-head">
                            Сменить ответственного
                            <div class="modal-steps-head-close close-modal"></div>
                        </div>
                        <div class="padding-15">

                            <?php $role = $user->roles[0]->name ?>
                            <?php

                            $responsible_options = array($user->id => 'Я ответственный', 'director' => 'Руководители', 'manager' => 'Менеджеры');
                            if ($user->roles[0]->name != 'admin') {
                                $responsible_options[$user->parent->id] = $user->parent->first_name;
                            }
                            $managers = Users::getUserAccess($user, true, false, true);
                            $directors = Users::getUserAccess($user, false, true, true);
                            if ($user->parent->roles[0]->name != 'admin' || $user->common_access == Users::ACCESS_EMBAGRO
                                || $user->common_access == Users::ACCESS_MANAGER || $user->roles[0]->name == 'admin'
                            ) {

                                unset($responsible_options['no']);
                            }

                            if (count($directors) <= 0) {
                                unset($responsible_options['director']);
                            }
                            if (count($managers) <= 0) {
                                unset($responsible_options['manager']);
                            }
                            if ($role == 'manager' and !empty($responsible_options['director'])) {
                                unset($responsible_options['director']);
                            }

                            $directors_block_to_display = '';
                            $managers_block_to_display = '';
                            $selected_option = [];
                            if (is_numeric($clients->responsable_id) && $clients->responsable_id != 0) {
                                $client_resp_role = UsersRoles::model()->find('user_id=' . $clients->responsable_id);
                                if ($client_resp_role->itemname == 'director') {
                                    $selected_option = array('director' => array('selected' => true));
                                } elseif ($client_resp_role->itemname == 'manager') {
                                    if (!$IamResponsible) {
                                        $selected_option = array('manager' => array('selected' => true));
                                    }
                                }
                                $directors_block_to_display = $client_resp_role->itemname == 'directorFilter' ? 'style="display:block"' : '';
                                $managers_block_to_display = $client_resp_role->itemname == 'managerFilter' && !($IamResponsible && $role == 'manager') ? 'style="display:block"' : '';
                            }

                            ?>
                            <?php echo $form->dropDownList($clients, 'responsable_id', $responsible_options, array('options' => $selected_option, 'onChange' => 'changeMaterFilter(this.value)', 'class' => 'styled     master-type ', 'name' => 'type', 'id' => 'filter-type')); ?>


                            <div class="access-options access-tab"
                                 id="directorFilter" <?php echo $directors_block_to_display ?>>
                                <?php if (count($directors) > 0) {
                                    echo $form->dropDownList($clients, 'director_id', CHtml::listData($directors, 'id', 'first_name'), array('options' => is_numeric($clients->responsable_id) && $clients->responsable_id != 0 ? array($clients->responsable_id => array('selected' => true)) : '', 'class' => 'styled directorFilter', 'id' => 'directorFilterSelect'));
                                }
                                ?>

                            </div>
                            <div class="access-options access-tab"
                                 id="managerFilter" <?php echo $managers_block_to_display ?>>
                                <?php echo $form->dropDownList($clients, 'manager_id', CHtml::listData($managers, 'id', 'first_name'), array('options' => is_numeric($clients->responsable_id) && $clients->responsable_id != 0 ? array($clients->responsable_id => array('selected' => true)) : '', 'class' => 'styled managerFilter', 'id' => 'managerFilterSelect')); ?>
                            </div>


                            <div>
                                <div class="padding-top-15">
                                    <input class="btn" id="setActionMasterBtn" type="button" value="Применить"
                                           style="width: 100%;margin-bottom: 10px;">
                                </div>
                                <? if ($user->roles[0]->name == 'admin' or $user->roles[0]->name == 'director') { ?>
                                    <div class='modal-foot'>
                                        <a href="/page/create_user" target="_blank">Создать пользователя</a>
                                        <a href="/page/user_info" target="_blank">Управление пользователями</a>

                                    </div>
                                <? } ?>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="miltuBtn">
                    <a href="#" class="show-popap sel-link disbl" data-target='date-modal-box'>Дата</a>


                    <div id="date-modal-box" class="multi-popap hide">
                        <div class="modal-steps-head">
                            Сменить дату задачи
                            <div class="modal-steps-head-close close-modal"></div>
                        </div>
                        <div class="padding-15">

                            <div style="padding-bottom: 10px;">
                                <img src="/img/clock.svg" alt="" width="15px" style="padding-right: 5px;">Дата
                                выполнения:<span class="star">*</span>
                            </div>
                            <div>
                                <?php
                                echo $this->widget('ext.CJuiDateTimePicker.CJuiDateTimePicker', array(
                                    'name' => 'mass_date',
                                    'model' => $actions,
                                    'attribute' => 'action_date',
                                    'language' => 'ru',
                                    'htmlOptions' => array(
                                        'value' => '',
                                        'class' => 'form-control editable is_validate',
                                        'id' => 'mass_date',
                                        'autocomplete' => 'on'
                                    ),
                                    'options' => array(
                                        'dateFormat' => 'dd.mm.yy',
                                        'changeMonth' => 'true',
                                        'changeYear' => 'true',
                                        'showButtonPanel' => true,
                                        'beforeShow' => new CJavaScriptExpression('function(element){dataPickerFocus = $(element).attr(\'id\').trim();}')
                                    ),
                                ), true); ?>
                            </div>

                            <div class="padding-top-15">
                                <input class="btn" id="setDateEventsBtn" type="button" value="Применить"
                                       style="width: 100%;margin-bottom: 10px;">
                            </div>


                        </div>


                    </div>


                </div>


                <div class="miltuBtn">
                    <a href="#" class="show-popap sel-link disbl" data-target='state-modal-box'>Состояние</a>


                    <div id="state-modal-box" class="multi-popap hide" style="width: 210px;">
                        <div class="modal-steps-head">
                            Сменить состояние задачи
                            <div class="modal-steps-head-close close-modal"></div>
                        </div>
                        <div class="padding-15">


                            <div class="label_info">
                                Состояние:
                            </div>
                            <?
                            $statuses_array = ActionsStatuses::model()->findAll();
                            $selectedStatus = '';
                            foreach ($statuses_array as $status) {
                                $selectedStatus = $status;
                                break;
                            }
                            ?>

                            <div class="row-input colorSelect" id="colorSelectMassType"
                                 style="display: inline-flex">
                                <div class="jq-selectbox__select color-select client"
                                     onclick="showDropDawnColorMass('colorSelectMassType', event)">
                                    <div class="color-block"
                                         style="background-color: <? echo $selectedStatus->color ?>">
                                        <span><? echo $selectedStatus->name ?> </span>
                                        <input type="text" value="<? echo $selectedStatus->id ?>" class="hide"
                                               data-title="<? echo $selectedStatus->name ?> " id='mass_status'>
                                    </div>
                                    <div class="jq-selectbox__trigger">
                                        <div class="jq-selectbox__trigger-arrow"></div>
                                    </div>
                                </div>

                                <div class="color-customDropDawnList client shortWidth hide" style="width: 170px;">
                                    <ul>
                                        <?
                                        if ($statuses_array) {
                                            foreach ($statuses_array as $status) {
                                                echo "<li value='$status->id' onclick='changeColorMass(\"colorSelectMassType\", event, " . '"' . $status->color . '",' . " " . '"' . $status->name . '", ' . $status->id . ")'><div class='block-color' style='background-color:$status->color;'><span>$status->name</span></div></li>";
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>

                            <div class="padding-top-15">
                                <input class="btn" id="setStateActionsBtn" type="button" value="Применить"
                                       style="width: 100%;margin-bottom: 10px;">
                            </div>
                        </div>


                    </div>


                </div>


                <div class="miltuBtn">
                    <a href="#" id='addEvetnsBtn' class="  show-form" data-target='edit-actions-block'>Новая
                        задача</a>

                </div>
            </div>

            <? if (Yii::app()->user->hasFlash('success')) { ?>
                <script type="module">
                    import {NotificationBar} from '/js/notificationBar.js';

                    const notificationBar = new NotificationBar({
                        type: 'success',
                        title: '',
                        description: <? echo '"' . Yii::app()->user->getFlash('success') . '"' ?>
                    });
                    notificationBar.show();
                </script>
            <? } ?>


            <div id="edit-actions-block" class="hide form-box edit-block">


                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'edit-action-form',
                    // 'enableAjaxValidation' => true,
                )); ?>


                <div class="pl-01" style="border-bottom: 10px solid #e4e4e4;">
                    <div class="golov_2" style="padding-left: 25px;">Данные задачи
                        <div class='form-head-links'>
                            <a href="#" class='close-form-btn btn_back' style="margin-top: -6px;padding: 0px 10px;">Закрыть</a>
                        </div>

                    </div>
                    <div class="pl-01-01">
                        <div class="pl-02">

                            <div id='client-form-el'>
                                <div class="client_info">
                                    Контакты:<br>
                                    <span style="font-size: 11px;color: #707070;">нажмите на поле, чтобы выбрать</span>
                                </div>
                                <div class="form-group_actions">
                                    <?php echo $form->dropDownList($actions, 'client_id', [], array('class' => ' styled permis editable typeAccess', 'name' => 'client_id[]', 'data-name' => 'client_id', 'multiple' => 'multiple', 'multiple' => 'multiple', 'id' => 'client_search')); ?>
                                </div>
                            </div>

                            <div class="client_info">
                                Тема:

                            </div>
                            <div class="form-group_actions">
                                <?php echo $form->textField($actions, 'text', array('class' => 'to_change form-control is_validate editable', 'id' => 'actionTitle', 'data-name' => 'text', 'placeholder' => 'Что нужно сделать...')); ?>
                            </div>

                            <div class="client_info">
                                Описание:
                            </div>
                            <div class="form-group_actions">
                                <?php
                                echo $form->textArea($actions, 'description', array('class' => 'to_change form-control1 editable', 'data-name' => 'description', 'placeholder' => 'Напишите комментарий...'));


                                ?>
                            </div>
                            <div id='event-edit-el' style="padding: 0px 0px 20px 0px;">
                                <input class="btn" id="saveEditAction" type="button" name="bt1" value="Сохранить"
                                       style="width: 125px;">
                                <div class="form-error hidden" id='editErr'>Ошибка! Заполните хотя бы одно поле
                                </div>
                            </div>
                            <div id='event-add-el' style="padding: 0px 0px 20px 0px;">
                                <input class="btn" id="saveAddAction" type="button" name="bt2"
                                       value="Добавить задачу" style="width: 145px;">
                            </div>

                        </div>
                        <div class="pl-03">
                            <div class="pa-01">
                                <div class="client_info">
                                    Ответственный:
                                </div>
                                <div class="solid-bl">
                                    <label>
                                        <?php
                                        $select_list = [0 => 'Выберите ответственного'];
                                        foreach ($responsible_options as $k => $val) {
                                            $select_list[$k] = $val;
                                        }
                                        echo $form->dropDownList($actions, 'responsable_id', $select_list, array('class' => 'to_change styled  editable massStyled', 'data-name' => 'responsable_id', 'data-index' => '_action', 'name' => 'type', 'id' => 'type_event')); ?>
                                        <div class="access-options access-tab" id="director_action">
                                            <label>
                                                <?php echo $form->dropDownList($actions, 'director_id', CHtml::listData($directors, 'id', 'first_name'), array('class' => 'styled', 'id' => 'form_director_id')); ?>
                                            </label>
                                        </div>
                                        <div class="access-options access-tab" id="manager_action">
                                            <label>
                                                <?php echo $form->dropDownList($actions, 'manager_id', CHtml::listData($managers, 'id', 'first_name'), array('class' => 'styled', 'id' => 'form_manager_id')); ?>
                                            </label>
                                        </div>
                                    </label>
                                </div>
                                <div class="client_info">
                                    <img src="/img/clock.svg" alt="">Дата выполнения:
                                </div>
                                <div class="solid-bl">
                                    <?php
                                    echo $this->widget('ext.CJuiDateTimePicker.CJuiDateTimePicker', array(
                                        'name' => 'Actions[action_date]',
                                        'model' => $actions,
                                        'attribute' => 'action_date',
                                        'language' => 'ru',
                                        'htmlOptions' => array(
                                            'value' => isset($actions->action_date) ? date('d.m.Y H:m', strtotime($actions->action_date)) : '',
                                            'class' => 'to_change form-control editable is_validate',
                                            'id' => 'actionDate',
                                            'data-name' => 'action_date',
                                            'autocomplete' => 'off'
                                        ),
                                        'options' => array(
                                            'dateFormat' => 'dd.mm.yy',
                                            'changeMonth' => 'true',
                                            'changeYear' => 'true',
                                            'showButtonPanel' => true,
                                            'beforeShow' => new CJavaScriptExpression('function(element){dataPickerFocus = $(element).attr(\'id\').trim();}')
                                        ),
                                    ), true); ?>
                                    <?php echo $form->error($actions, 'action_date', array('class' => 'form-error')); ?>

                                </div>

                                <!-- Состояния -->
                                <?
                                $statuses_array = ActionsStatuses::model()->findAll();
                                if (count($statuses_array) > 0) { ?>


                                    <div class="solid-bl">
                                        <div class="label_info">
                                            Состояние:
                                        </div>

                                        <div class="row-input colorSelect" id="colorSelectForm"
                                             style="display: inline-flex">
                                            <div class="jq-selectbox__select color-select client"
                                                 onclick="showDropDawnColorMass('colorSelectForm', event)">
                                                <div class="color-block"
                                                     style="background-color: <? echo $selectedStatus->color ?>">
                                                    <span><? echo $selectedStatus->name ?> </span>
                                                    <input type="text" value="<? echo $selectedStatus->id ?>"
                                                           class="hide to_change" data-name='action_status_id'
                                                           name="Actions[action_status_id]" id='action_status_id'>
                                                </div>
                                                <div class="jq-selectbox__trigger">
                                                    <div class="jq-selectbox__trigger-arrow"></div>
                                                </div>
                                            </div>

                                            <div class="color-customDropDawnList client shortWidth hide"
                                                 id='status-div-edit-form' style="max-width: 195px;">
                                                <ul>
                                                    <?
                                                    if ($statuses_array) {
                                                        echo "<li value='' onclick='changeColorMass(\"colorSelectForm\", event, " . '"white",' . " " . '"Выберите состояние", ' . '0' . ")'><div class='block-color' style='background-color:;'><span style='margin-left: 0px;'>Выберите состояние</span></div></li>";
                                                        foreach ($statuses_array as $status) {
                                                            echo "<li value='$status->id' onclick='changeColorMass(\"colorSelectForm\", event, " . '"' . $status->color . '",' . " " . '"' . $status->name . '", ' . $status->id . ")'><div class='block-color' style='background-color:$status->color;'><span>$status->name</span></div></li>";
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <? } ?>

                                <? if ($allLabels && count($allLabels) > 0) { ?>
                                    <div class="padding-top-15">
                                        Метки:
                                        <a class="delete" id="editLabelsForm"
                                           onclick="return false;">Редактировать</a>
                                    </div>

                                    <div class="solid-bl">
                                        <div class="  customDropDownListLabelsForm hide">
                                            <ul>
                                                <? foreach ($allEventLabels as $label) { ?>
                                                    <li id="labelLiForm <? echo $label->id ?>" class="labelLi"
                                                        name="lb[<? echo $label->id ?>]"
                                                        onclick="changeLabelForm('<? echo $label->id; ?>');">
                                                        <?
                                                        echo $form->checkBox($actions, "Labels[$label->id]", [
                                                            'id' => 'checkboxForm' . $label->id,
                                                            'name' => "act_labels[]",
                                                            'class' => 'hide to_change',
                                                            'data-name' => 'labels',
                                                            'value' => $label->id,
                                                            'checked' => false
                                                        ]);
                                                        ?>
                                                        <div class="deleted"
                                                             id="blockOperForm<? echo $label->id; ?>"></div>
                                                        <div class="block-color"
                                                             id="labelColorForm<? echo $label->id; ?>"
                                                             style="background-color: <? echo $label->color ?>"></div>
                                                        <span id="labelTextForm<? echo $label->id; ?>"><? echo $label->name ?></span>
                                                    </li>
                                                <? } ?>

                                            </ul>
                                        </div>

                                        <div class="  block-labelsInProfileForm">
                                            <? foreach ($customSelectedLabels as $label) { ?>
                                                <div class="block-elem" id="blockElemForm<? echo $label->id ?>">
                                                    <div class="block-color"
                                                         style="background-color: <? echo $label->color ?>"></div>
                                                    <span><? echo $label->name ?></span>
                                                </div>
                                            <? } ?>
                                        </div>
                                    </div>
                                <? } ?>
                            </div>
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>

            <?php
            if (count($actions_table_data->data) == 0) { ?>
                <div class="info_client_001"><p>Задач нет</p></div>
                <?
            }
            $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider' => $actions_table_data,
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
                            $action_date = date('Y-m-d', strtotime($data->action_date)) . ' 23:59:59';
                            $actionStatusColor = '#FB7192';
                            if (strtotime($action_date) >= time() || $data->action_status_id != 1) {
                                $actionStatusColor = $data->actionStatus->color;
                            }
                            $actionIndication = '
											<input type="checkbox" name="selectedRows[]" value="' . $data->id . '"  class="row-ch row-ch-action form-control_3">
											<div class="labelStatusAction" style="background-color:' . $actionStatusColor . '"> </div>';

                            $criteria = new CDbCriteria;
                            foreach ($data->labelsInActions as $value) {
                                $criteria->addCondition('t.id = ' . $value->label_id, "OR");
                            }
                            $labelHTML = '';
                            $idHTML = '<div class="block_labels" style="margin-left:17px;">' . '<span class="idHTML"> #' . $data->id . '</span>' . '<span class="werwe"></span>' . $data->actionStatus->name . '<span class="werwe"></span>' . CHtml::link($data->client->name, Yii::app()->createUrl("page/client_profile",
                                    array("id" => $data->client->id)));
                            //
                            if ($criteria->condition != '' && $labels = Labels::model()->findAll($criteria)) {
                                $labelHTML = '';
                                foreach ($labels as $label) {
                                    $labelHTML .= '<div onclick="clickLabel(' . $label->id . ')" class="custom-label pointer" style="background-color: ' . $label->color . '; color:' . $label->color_text . '">' . $label->name . '</div>';
                                }
                                $labelHTML .= '</div>';
                            }
                            $dddd2 = ' <span class="new-table-date_actions">' .
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
                                '<div class="new-table" style="padding:0px 15px 0px 9px;">
														<div class="new-table-left">
														<div class="new-table-header">
														<div class="new-table-name-client"> ' .
                                $actionIndication . CHtml::link($data->text, Yii::app()->createUrl("page/edit_action", array("id" => $data->id, "render_page" => 'actions_page')), ['class' => 'float-left']) . (count($data->actionsFiles) > 0 ? '<a class="file_add" tabindex="1"><img src="/img/paper-clip.svg"></a>' : '') .
                                '<span class="sdf2">' . ($data->responsable->avatar ? CHtml::image($data->responsable->avatar, '', ['class' => 'miniAvatar']) : CHtml::image($data->responsable->roles[0]->name == 'manager' ? '/img/employee.svg' : ($data->responsable->roles[0]->name == 'director' ? '/img/ava_adminisrtr.svg' : '/img/ava_admin.svg'), '', ['class' => 'miniAvatar'])) . CHtml::link($data->responsable->first_name, Yii::app()->createUrl("page/user_profile", array("id" => $data->responsable->id))) .


                                '</div>
														<div class="new-table-name-resp"></div>
														</div>
														<div class="new-table-bottom">' . $idHTML . $labelHTML . $dddd2 . '</div>

														</div>';
                        }
                    ),
                )));
            ?>
        </div>
        <!-- <div class="item-list">
        <ul class="pager">
        <li class="pager-item first">1</li>
        <li class="pager-item"><a href="#" title="На страницу номер 2">2</a></li>
        <li class="pager-item"><a href="#" title="На страницу номер 3">3</a></li>
        <li class="pager-item"><a href="#" title="На страницу номер 4">4</a></li>
    </ul>
</div>-->
    </div>
</main><!--.content-->

<script>
    clickLabel = function (id) {
        document.location.href = '/page/actions_page?term=<?echo $term?>&labelId=' + id;
    };
</script>

<script src="/js/mass_scripts.js?r=<?= Rand(1, 11000) ?>"></script>
<script src="/js/jquery.fcbkcomplete.js?r=<?= Rand(1, 11000) ?>"></script>
<script>
    $("#client_search").fcbkcomplete({
        json_url: "/page/ajax_clients/",
        cache: true,
        filter_case: false,
        filter_hide: false,
        newel: false
    });
</script>

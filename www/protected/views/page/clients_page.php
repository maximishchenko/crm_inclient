<?php $this->pageTitle = $selectedFilter->name . ' | Контакты'; ?>

<div class="clients-hat">
    <div class="goback-link pull-right">
        <?php
        if ($user->roles[0]->name == 'admin' || $userRight->create_client) {
            echo CHtml::button('Новый контакт', array('onClick' => 'window.location.href= "' . Yii::app()->createUrl("page/new_client") . '"',
                'class' => 'btn_100 popup-open popup-open', 'id' => 'popup_new_client_button'));
        }
        $showCheckboxes = $user->roles[0]->name == 'admin' || $userRight->create_client || $userRight->create_action;
        ?>

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

        <? if ($isDeleteFilter) { ?>
            <script type="module">
                import {NotificationBar} from '/js/notificationBar.js';

                const notificationBar = new NotificationBar({
                    type: 'success',
                    title: 'Фильтр удален',
                    description: 'Удаление прошло успешно'
                });
                notificationBar.show();
            </script>
        <? } ?>

    </div>
    <div class="client-name" style="font-size: 11px;">
        <div id='sumary-div'></div>
        <div id='sel-sumary-div'></div>
    </div>
</div>

<div class="content full2" role="main">

    <?php
    $this->renderPartial('filters/clients_filters_page', array(
        'selectedFilter' => $selectedFilter,
        'clients' => $clients,
        'keyword' => $keyword,
        'filterColors' => $filterColors,
    ));
    ?>

    <div class="box-gray">
        <div class="box-gray__body no-border bottom_margin">
            <div class="select-tab">
                <div class="miltuBtn">
                    <input type="checkbox" id=select_all
                           class="form-control_1 checkBox <? echo $showCheckboxes ?: 'hide' ?>"
                           style="margin: 0px;">
                </div>
                <? if ($user->roles[0]->name == 'admin' || ($userRight->create_client && $userRight->delete_client)) { ?>
                    <div class="miltuBtn">
                        <a href="#" id='deleteLink' class="show-popap sel-link disbl" data-target='del-modal-box'>Удалить</a>
                        <div id="del-modal-box" class="multi-popap hide">
                            <div class="modal-steps-head">Удаление контактов
                                <div class="modal-steps-head-close close-modal"></div>
                            </div>
                            <div id='del-modal-text'>
                                <p>Будет удалено: <span id='del_cnt'></span></p>
                                <form onsubmit="return false" class="op-00">
                                    <div>
                                        <input type="button" class="btn width-100" value="Удалить" id='delBtn'
                                               data-url='/page/del_clients' data-title="Контакты">
                                    </div>
                                    <div>
                                        <input type="button" class="btn_back width-100 margin-top-10" value="Отмена"
                                               onclick="ClosePopup()"/>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?
                }
                if ($user->roles[0]->name == 'admin' || $userRight->create_client) { ?>
                    <div class="miltuBtn">
                        <a href="#" id='editLink' class="show-form sel-link disbl" data-target='edit-clients-block'>Изменить</a>
                    </div>

                    <div class="miltuBtn">
                        <a href="#" id='seqLink' class="show-popap sel-link disbl"
                           data-target='step-modal-box'>Воронка</a>
                        <div id="step-modal-box" class="multi-popap hide modal-steps-body">
                            <div>
                                <?
                                $form = $this->beginWidget('CActiveForm', array(
                                    'id' => 'step-modal-form',
                                )); ?>
                                <div class="modal-steps-head">Назначить воронку
                                    <div class="modal-steps-head-close close-modal"></div>
                                </div>
                                <? if (count($listStep) > 0) {
                                    $listStep_2 = $listStep;
                                    unset($listStep_2[0]) ?>
                                    <div style="padding: 15px;">
                                        <?php echo $form->dropDownList($selectedSteps, 'steps_id',
                                            CHtml::listData($listStep_2, 'id', 'name'), ['class' => 'styled', 'onChange' => 'changeStepForm(this.value)', 'id' => 'selectStepForm']); ?>
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
                                        <div class="row-input colorSelect" id="colorSelectForm"
                                             style="display: none">
                                            <div class="jq-selectbox__select color-select client"
                                                 onclick="showDropDawnColorFilter(event)">
                                                <div class="color-block color-block-form "
                                                     style="background-color: <? echo $selectedOption->color ?>">
                                                    <span><? echo $selectedOption->name ?> </span>
                                                    <input type="text" value="<? echo $selectedOption->id ?>"
                                                           class="hide"
                                                           name="StepsInClientsFilter[selected_option_id]">
                                                </div>
                                                <div class="jq-selectbox__trigger">
                                                    <div class="jq-selectbox__trigger-arrow"></div>
                                                </div>
                                            </div>
                                            <div class="color-customDropDawnList customDropDawnList-form client shortWidth hide">
                                                <ul>
                                                    <?
                                                    if ($isNotStepOptions) {
                                                        foreach ($listStepOption[$selectedSteps->steps_id] as $id => $option) {
                                                            echo "<li value='$id'  onclick='changeColorFilter(event, " . '"' . $option->color . '",' . " " . '"' . $option->name . '", ' . $option->id . ")'><div class='block-color' style='background-color:$option->color;'></div><div class='margin-top-1'>$option->name</div></li>";
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="step-progressBar step-progressBar-filter"
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
                                        <div style="padding-top: 15px;">
                                            <input class="btn" id="setStepBtn" type="button"
                                                   value="Применить" style="width: 100%;margin-bottom: 10px;">
                                        </div>
                                        <? if ($user->roles[0]->name == 'admin' or $userRight['create_steps'] == 1) { ?>
                                            <div class='modal-foot'>
                                                <a href="/page/new_step?type=clients" target="_blank">Создать
                                                    воронку</a>
                                                <a href="/page/settings_steps" target="_blank">Управление
                                                    воронками</a>
                                            </div>
                                        <? } ?>
                                    </div>
                                <? } ?>
                                <?php $this->endWidget(); ?>
                            </div>
                        </div>
                    </div>
                    <div class="miltuBtn">
                        <a href="#" id='labelsLink' class="show-popap sel-link disbl" data-target='label-modal-box'>Метки</a>
                        <div id="label-modal-box" class="multi-popap hide">
                            <div class="modal-steps-head">Назначить метку
                                <div class="modal-steps-head-close close-modal"></div>
                            </div>
                            <? if (count($allLabels) > 0) { ?>
                        <?
                        $form = $this->beginWidget('CActiveForm', array(
                            'id' => 'lebel-modal-form',
                        )); ?>
                            <div class="label-filter">
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
                                                 data-id="no" data-text="Нет меток">
                                            </div>
                                            <div class="block-color" id="labelColorFilterno"
                                                 style="background-color :black ">
                                            </div>
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
                                                     data-text="<? echo $label->name ?>">
                                                </div>
                                                <div class="block-color" id="labelColorFilter<? echo $label->id; ?>"
                                                     style="background-color: <? echo $label->color ?>">
                                                </div>
                                                <span id="labelTextFilter<? echo $label->id; ?>"><? echo $label->name ?></span>
                                            </li>
                                        <? } ?>
                                    </ul>
                                </div>
                                <? } ?>
                                <div class="padding-top-15">
                                    <input class="btn" id="setLabelBtn" type="button" value="Применить"
                                           style="width: 100%;margin-bottom: 10px;">
                                    <div class="form-error" id="notSelectedLabelMessage" style="display: none;">
                                        Выберите
                                        метку
                                    </div>
                                </div>
                                <? if ($user->roles[0]->name == 'admin' OR $userRight['create_label_clients'] == 1) { ?>
                                    <div class='modal-foot'>
                                        <a href="/page/new_label?type=clients" target="_blank">Создать метку</a>
                                        <a href="/page/settings_labels" target="_blank">Управление метками</a>
                                    </div>
                                <? } ?>
                            </div>
                            <?php $this->endWidget(); ?>
                        </div>
                    </div>

                    <div class="miltuBtn">
                        <a href="#" id='masterLink' class="show-popap sel-link disbl"
                           data-target='master-modal-box'>Ответственный</a>
                        <div id="master-modal-box" class="multi-popap hide">
                            <div class="modal-steps-head">Сменить ответственного
                                <div class="modal-steps-head-close close-modal"></div>
                            </div>
                            <div class="padding-15">
                                <?php $role = $user->roles[0]->name ?>
                                <?php
                                $responsible_options = array(Yii::app()->user->id => 'Я ответственный', 'director' => 'Руководители', 'manager' => 'Менеджеры');

                                $managers_array = Users::getUserAccess($user, true, false, true);
                                $directors_array = Users::getUserAccess($user, false, true, true);

                                if ($role == 'director') {
                                    unset($responsible_options['director']);
                                    $responsible_options[$user->parent_id] = $user->parent->first_name;

                                } elseif ($role == 'manager') {
                                    $responsible_options[$user->parent_id] = $user->parent->first_name;
                                    unset($responsible_options['director']);
                                } else {
                                }
                                // выбор значений в селекторах с ролями и пользователями
                                $IamResponsible = false;
                                if ($clients->responsable_id == Yii::app()->user->id) {
                                    $selected_option = array('i' => array('selected' => true));
                                    $IamResponsible = true;
                                } elseif ($clients->responsable_id == 'no') {
                                    $selected_option = array('no' => array('selected' => true));
                                } else {
                                    $selected_option = array('all' => array('selected' => true));
                                }
                                if (count($directors_array) <= 0) {
                                    unset($responsible_options['director']);
                                }
                                if (count($managers_array) <= 0) {
                                    unset($responsible_options['manager']);
                                }

                                $directors_block_to_display = '';
                                $managers_block_to_display = '';

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
                                <?
                                $form = $this->beginWidget('CActiveForm', array(
                                    'id' => 'responsible-form',
                                )); ?>
                                <?php echo $form->dropDownList($clients, 'responsable_id', $responsible_options, array('options' => $selected_option, 'onChange' => 'changeMaterFilter(this.value)', 'class' => 'styled     master-type ', 'name' => 'type', 'id' => 'filter-type')); ?>


                                <div class="access-options access-tab"
                                     id="directorFilter" <?php echo $directors_block_to_display ?>>
                                    <?php if (count($directors_array) > 0) {
                                        echo $form->dropDownList($clients, 'director_id', CHtml::listData($directors_array, 'id', 'first_name'), array('options' => is_numeric($clients->responsable_id) && $clients->responsable_id != 0 ? array($clients->responsable_id => array('selected' => true)) : '', 'class' => 'styled directorFilter', 'id' => 'directorFilterSelect'));
                                    }
                                    ?>
                                </div>
                                <div class="access-options access-tab"
                                     id="managerFilter" <?php echo $managers_block_to_display ?>>
                                    <?php echo $form->dropDownList($clients, 'manager_id', CHtml::listData($managers_array, 'id', 'first_name'), array('options' => is_numeric($clients->responsable_id) && $clients->responsable_id != 0 ? array($clients->responsable_id => array('selected' => true)) : '', 'class' => 'styled managerFilter', 'id' => 'managerFilterSelect')); ?>
                                </div>
                                <div style="padding: 15px 0px 15px;display: inline-flex;">
                                    <input type="checkbox" id="master-events" value="1"
                                           style="cursor: pointer;height: 19px;width: 25px;margin: 0px 7px 0px 0px;">
                                    <p><label for="master-events" style="cursor: pointer;"> В задачах контакта, тоже
                                            сменить ответственного</label></p>
                                </div>
                                <div>
                                    <input class="btn" id="setMasterBtn" type="button" value="Применить"
                                           name="save_and_create" style="width: 100%;margin-bottom: 10px;">
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
                    <?
                    $this->endWidget();
                } ?>
                <? if ($user->roles[0]->name == 'admin' || $userRight['create_action'] == 1) { ?>
                    <a href="#" id='eventLink' class="show-form sel-link disbl" data-target='edit-event-block'>Новая
                        задача</a>
                <? } ?>
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

            <div id="edit-event-block" class="hide form-box">
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'edit-event-form',
                    // 'enableAjaxValidation' => true,
                )); ?>
                <div class="pl-01" style="border-bottom: 10px solid #e4e4e4;">
                    <div class="golov_2" style="padding-left: 25px;">Новая задача
                        <div class='form-head-links'>
                            <a href="#" class='close-form-btn btn_back'
                               style="margin-top: -6px;    padding: 0px 10px;">Закрыть</a>
                        </div>
                    </div>
                    <div class="pl-01-01">
                        <div class="pl-02">
                            <div class="client_info">Тема:<span class="star">*</span></div>
                            <div class="form-group_actions">
                                <?php echo $form->textField($actions, 'text', array('class' => 'form-control is_validate editable', 'id' => 'actionTitle', 'placeholder' => 'Что нужно сделать...')); ?>
                            </div>
                            <div class="client_info">Описание:</div>
                            <div class="form-group_actions">
                                <?php
                                echo $form->textArea($actions, 'description', array('class' => 'form-control1 editable', 'placeholder' => 'Напишите комментарий...'));
                                ?>
                            </div>
                            <div class="event-add-el">
                                <input class="btn" id="addActionBtn" type="button" name="yt1"
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
                                        <?php $role = UsersRoles::model()->find('user_id=' . Yii::app()->user->id)->itemname;
                                        $dir = 'Директор';
                                        if ($role != 'admin') {
                                            $dir = $parent_user->first_name;
                                        }
                                        $responsible_options = array('i' => 'Я ответственный', 'director_action' => 'Руководители', 'manager_action' => 'Менеджеры', 'no' => $dir);


                                        $managers_array = Users::model()->with('roles')->findAll('(status="active" || status="limited") and roles.name="manager"');
                                        $directors_array = Users::model()->with('roles')->findAll('(status="active" || status="limited") and roles.name="director"');

                                        if ($role == 'director') {
                                            unset($responsible_options['director_action']);
                                            $managers_array = Users::model()->with('roles')->findAll(' (status="active" || status="limited") and roles.name="manager" and parent_id=' . Yii::app()->user->id);
                                        } elseif ($role == 'manager') {
                                            unset($responsible_options['director_action']);
                                            $managers_array = Users::model()->with('roles')->findAll('id != ' . $user->id . ' and (status="active" || status="limited") and roles.name="manager" and parent_id=' . $user->parent_id);
                                        } else {
                                            unset($responsible_options['no']);
                                        }
                                        if (count($directors_array) <= 0) {
                                            unset($responsible_options['director_action']);
                                        }
                                        if (count($managers_array) <= 0) {
                                            unset($responsible_options['manager_action']);
                                        }
                                        ?>
                                        <?php echo $form->dropDownList($actions, 'responsable_id', $responsible_options, array('class' => 'styled permis editable typeAccess', 'name' => 'type', 'id' => 'type_event')); ?>
                                        <div class="access-options access-tab" id="director_action">
                                            <label>
                                                <?php echo $form->dropDownList($actions, 'director_id', CHtml::listData($directors_array, 'id', 'first_name'), array('class' => 'styled')); ?>
                                            </label>
                                        </div>
                                        <div class="access-options access-tab" id="manager_action">
                                            <label>
                                                <?php echo $form->dropDownList($actions, 'manager_id', CHtml::listData($managers_array, 'id', 'first_name'), array('class' => 'styled')); ?>
                                            </label>
                                        </div>
                                    </label>
                                </div>
                                <div class="client_info">
                                    <img src="/img/clock.svg" alt="">Дата выполнения:<span class="star">*</span>
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
                                            'class' => 'form-control editable is_validate',
                                            'id' => 'actionDate',
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


                                    <div class="solid-bl" style="margin-top: -5px;padding-bottom: 13px;">
                                        <div class="label_info">
                                            Состояние:
                                        </div>
                                        <?
                                        $selectedStatus = '';
                                        foreach ($statuses_array as $status) {
                                            $selectedStatus = $status;
                                            break;
                                        }
                                        ?>

                                        <div class="row-input colorSelect" id="colorSelectForm"
                                             style="display: inline-flex">
                                            <div class="jq-selectbox__select color-select client"
                                                 onclick="showDropDawnColorMass('colorSelectForm', event)">
                                                <div class="color-block"
                                                     style="background-color: <? echo $selectedStatus->color ?>">
                                                    <span><? echo $selectedStatus->name ?> </span>
                                                    <input type="text" value="<? echo $selectedStatus->id ?>"
                                                           class="hide"
                                                           name="Actions[action_status_id]" id='action_status_id'>
                                                </div>
                                                <div class="jq-selectbox__trigger">
                                                    <div class="jq-selectbox__trigger-arrow"></div>
                                                </div>
                                            </div>

                                            <div class="color-customDropDawnList client shortWidth hide"
                                                 style="display: block;max-width: 195px;">
                                                <ul>
                                                    <?
                                                    if ($statuses_array) {
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
                                        <div class="customDropDownListLabelsForm hide">
                                            <ul>
                                                <? foreach ($allEventLabels as $label) { ?>
                                                    <li id="labelLiForm <? echo $label->id ?>" class="labelLi"
                                                        name="lb[<? echo $label->id ?>]"
                                                        onclick="changeLabelForm('<? echo $label->id; ?>');">
                                                        <?
                                                        echo $form->checkBox($actions, "Labels[$label->id]", [
                                                            'id' => 'checkboxForm' . $label->id,
                                                            'name' => "act_labels[]",
                                                            'class' => 'hide',
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


            <div id="edit-clients-block" class="hide form-box"
                 style="margin-top: -1px;border-bottom: 10px solid #e4e4e4;">
                <div class="golov_2" style="padding-left: 25px;">Изменение контакта
                    <div class='form-head-links'>
                        <? if ($user->roles[0]->name == 'admin' or ($user->roles[0]->name == 'director' and $userRight['create_field'] == 1)) { ?>
                            <a href="/page/new_additional_field/1" target="_blank">Создать поле</a>
                            <a href="/page/Settings_additional_field" target="_blank">Управления полями</a>
                        <? } ?>
                        <a href="#" class='close-form-btn btn_back' style="margin-top: -6px;    padding: 0px 10px;">Закрыть</a>
                    </div>
                </div>

                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'edit-clients-form',
                    // 'enableAjaxValidation' => true,
                ));

                $i = 1;
                $endBlock = true;
                foreach ($additionalFiledValuesInClient as $key => $fieldSection) {
                    if ($i % 2 == 1) {
                        $endBlock = false;
                        ?>
                        <div class="additionalFieldTable_anketa">
                        <?php
                    }
                    ?>
                    <div class="box-gray__body no-border2 active-pad resizeWidth">
                        <div class="main-table row edit-row">
                            <div class="profile_info_block clear_fix">
                                <div class="profile_info_header_wrap">
                                    <span class="profile_info_header"><? echo $fieldSection[0]['sectionName'] ?></span>
                                </div>
                            </div>
                            <?php
                            foreach ($fieldSection as $value) { ?>
                                <div class="block-row-writ">
                                    <div class="row-label">
                                        <? echo $value['name']; ?>
                                        <? if ($value['required']) { ?>
                                        <? } ?>
                                    </div>

                                    <div class="row-input-02">
                                        <input type='hidden' value="0" name="isEdit[<?= $value['table_name'] ?>]"
                                               class="isEdit">

                                        <?php
                                        $valueField = isset($additionalFiledValue[$value['table_name']]) ? $additionalFiledValue[$value['table_name']] : $value['value'];
                                        $classPositionImage = 'textSize24';

                                        switch ($value['type']) {
                                            case 'checkbox':
                                                //    echo $form->checkBox($client, "editFields[$value[table_name]]", ['checked' => $valueField, 'class' => "form-control_anketa check_edit"]);

                                                echo CHtml::radioButtonList(
                                                    "Clients[editFields][$value[table_name]]",
                                                    $valueField,
                                                    array(
                                                        '1' => 'Да',
                                                        '0' => 'Нет',
                                                    )
                                                    , ['checked' => $valueField, 'class' => "form-control-01 check_edit"]
                                                );

                                                break;

                                            case 'date':
                                                ?>

                                                <input type='hidden' value="1"
                                                       name="isdate[<?= $value['table_name'] ?>]" class="isdate">
                                                <?
                                                echo $this->widget('ext.CJuiDateTimePicker.CJuiDateTimePicker', array(
                                                    'name' => "Clients[editFields][$value[table_name]]",
                                                    'model' => $client,
                                                    'attribute' => "editFields[$value[table_name]]",
                                                    'language' => 'ru',
                                                    'htmlOptions' => array(
                                                        'value' => isset($valueField) && is_numeric($valueField) ? date('d.m.Y', $valueField) : '',
                                                        'class' => 'form-control editable check_edit',
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
                                                $settings = ['class' => "form-control check_edit $size", 'value' => $valueField];
                                                if (in_array(trim($value['name']), ['Имя', 'Телефон', 'E-mail'])) {
                                                    $settings['disabled'] = 'disabled';
                                                }
                                                echo $form->textArea($client, "editFields[$value[table_name]]", $settings);
                                                if (count($additionalFiledValuesInClient) == $key && $key % 2 != 0) {
                                                    $classPositionImage .= ' longText';
                                                }

                                                ?>

                                                <?
                                                break;
                                            case 'int':
                                                $settings = ['class' => 'form-control check_edit numeric-control', 'value' => $valueField, 'data-title' => $value['name'],];
                                                if (in_array(trim($value['name']), ['Имя', 'Телефон', 'E-mail'])) {
                                                    $settings['disabled'] = 'disabled';
                                                }
                                                echo $form->textField($client, "editFields[$value[table_name]]", $settings);
                                                if (count($additionalFiledValuesInClient) == $key && $key % 2 != 0) {
                                                    $classPositionImage .= ' longText';
                                                }
                                                ?>

                                                <?
                                                break;
                                            case 'select':
                                                $selected = [''];
                                                $data = ['' => 'Выберите значение'];
                                                if (!is_array($listOptions = json_decode($valueField, true))) {
                                                    $listOptions = json_decode($value['value'], true);
                                                    $selected = $valueField;
                                                }
                                                foreach ($listOptions as $option) {
                                                    $data [$option['id']] = $option['optionName'];
                                                    if (isset($option['default']) && !$selected && $option['default']) {
                                                        $selected = $option['id'];
                                                    }
                                                }
                                                echo CHtml::dropDownList("Clients[editFields][$value[table_name]]", $selected, $data, ['class' => 'styled select check_edit']);
                                                break;
                                            default:
                                                echo $form->textField($client, "editFields[$value[table_name]]", ['class' => 'styled status check_edit', 'value' => $valueField]);
                                                break;
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="row-label"></div>
                                <div class="row-tip"><? echo $value['tip']; ?></div>

                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                    if ($i % 2 != 1) {
                        $endBlock = true;
                        ?>
                        </div>
                        <?
                    }
                    $i++;
                }
                ?>
                <?php
                if (!$endBlock)
                {
                ?>
            </div>
        <? } ?>
            <div style="padding:5px 20px 25px 182px;">
                <div>
                    <input class="btn" id="saveUsersEditBtn" name="save_and_create" type="button"
                           value="Сохранить" style="width: 125px;">
                </div>
                <div class="form-error hidden" id='editErr'>Не сохранено! Нужно изменить хотя бы одно поле<br></div>
                <div class="form-error hidden" id="numErr">В числовое поле нельзя указывать текст. Исправьте поле
                    <strong>"<span id='fErName'></span>"</strong>, и попробуйте снова<br></div>

            </div>

            <?php $this->endWidget(); ?>

        </div>

        <? echo count($clientTableData->getData()) != 0 ? '' : '<div class="info_client_001"><p>Контактов нет</p></div>'?>

        <!--Вывод плашек-->
        <? $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $clientTableData,
            'cssFile' => '',
            'emptyText' => '',
            'htmlOptions' => array('class' => 'new-table-main'),
            'columns' => array(
                array(
                    'name' => 'name',
                    'header' => 'Контакты',
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
                    'value' => function ($data) use ($showCheckboxes, $selectedFilter, $modelClientFiltersBlockInfoLeft, $modelClientFiltersBlockInfoRight, $modelClientFiltersBlockAdditionalFieldsLeft, $modelClientFiltersBlockAdditionalFieldsRight) {

                        $isBlockInfoLeft = isset($modelClientFiltersBlockInfoLeft);
                        $isBlockInfoRight = isset($modelClientFiltersBlockInfoRight);
                        $isDefaultFilter = $selectedFilter->id == 1;
                        $isRightBlock = $modelClientFiltersBlockAdditionalFieldsRight || $modelClientFiltersBlockInfoRight;

                        // левый блок
                        $htmlLeftBlock = '';

                        //клиентская инфа
                        if ($isBlockInfoLeft && $modelClientFiltersBlockInfoLeft->is_id_client || $isDefaultFilter) {
                            $htmlLeftBlock .= '<div class="row-item idHTML"> #' . $data->id . '</div>';
                        }

                        if ($isBlockInfoLeft && $modelClientFiltersBlockInfoLeft->is_responsible) {
                            if ($htmlLeftBlock) {
                                $htmlLeftBlock .= ' <div class="werwe2"></div> ';
                            }

                            $htmlLeftBlock .= '<div class="row-item">' . ($data->responsable->avatar ? CHtml::image($data->responsable->avatar, '', ['class' => 'miniAvatar']) : CHtml::image($data->responsable->roles[0]->name == 'manager' ? '/img/employee.svg' : ($data->responsable->roles[0]->name == 'director' ? '/img/ava_adminisrtr.svg' : '/img/ava_admin.svg'), '', ['class' => 'miniAvatar'])) . CHtml::link($data->responsable->first_name, Yii::app()->createUrl("page/user_profile", array("id" => $data->responsable->id)), ["class" => "link-grey"]) . '</div>';
                        }

                        if ($isBlockInfoLeft && $modelClientFiltersBlockInfoLeft->is_last_change) {
                            if ($data->change_client_date) {
                                $changeDateClient = Yii::app()->commonFunction->getChangeDateClient($data->change_client_date);

                                if ($htmlLeftBlock) {
                                    $htmlLeftBlock .= ' <div class="werwe2"></div> ';
                                }

                                $htmlLeftBlock .=  '<div class="row-item tooltip">' . $changeDateClient . '<span class="tooltiptext tooltip-bottom">Дата изменения</span>' . '</div>';
                            }
                        }

                        if ($isBlockInfoLeft && $modelClientFiltersBlockInfoLeft->is_create_date || $isDefaultFilter) {
                            if ($htmlLeftBlock) {
                                $htmlLeftBlock .= ' <div class="werwe2"></div> ';
                            }

                            $htmlLeftBlock .= '<div class="row-item editable tooltip">' . date('d.m.y', strtotime($data->creation_date)) . ' в ' . date('H:i', strtotime($data->creation_date)) . '<span class="tooltiptext tooltip-bottom">Дата создания</span>' .'</div>';
                        }

                        if ($isBlockInfoLeft && $modelClientFiltersBlockInfoLeft->is_step) {
                            $step = StepsInClients::model()->with('steps')->find('clients_id = :ID', [':ID' => $data->id]);
                            if ($step) {
                                if ($htmlLeftBlock) {
                                    $htmlLeftBlock .= ' <div class="werwe2"></div> ';
                                }
                                $htmlLeftBlock .= '<div class="row-item tooltip">' . $step->steps->name . '<span class="tooltiptext tooltip-bottom">Воронка</span>' . '</div>';
                            }
                        }

                        if ($isBlockInfoLeft && $modelClientFiltersBlockInfoLeft->is_option_step) {
                            $stepOptionColor = '';
                            $stepOptionName = '';

                            if ($step = StepsInClients::model()->with('steps')->find('clients_id = :ID', [':ID' => $data->id])) {
                                if ($step->selected_option_id && $stepSelectedOption = StepsOptions::model()->findByPk($step->selected_option_id)) {
                                    $stepOptionColor = $stepSelectedOption->color;
                                    $stepOptionName = $stepSelectedOption->name;
                                }
                            }

                            if ($stepOptionName) {
                                if ($htmlLeftBlock) {
                                    $htmlLeftBlock .= ' <div class="werwe2"></div> ';
                                }

                                $htmlLeftBlock .= '<div class="row-item stepIndication" style="background-color:' . $stepOptionColor . '; margin-right: 5px;margin-top: 4px;">' . '</div>' . '<span class="row-item tooltip" style="padding-left: 14px;">' . $stepOptionName . '<span class="tooltiptext tooltip-bottom">Этап воронки</span>' . '</span>';
                            }
                        }

                        //доп поля
                        if (!$isDefaultFilter) {
                            $modelAddFieldsValue = AdditionalFieldsValues::model()->find('client_id=:CID', [':CID' => $data->id]);
                            if ($modelAddFieldsValue) {
                                if (!$isDefaultFilter) {
                                    foreach ($modelClientFiltersBlockAdditionalFieldsLeft as $value) {
                                        $fieldValue = '';
                                        $table_name = $value->additionalFields->table_name;

                                        switch ($value->additionalFields->type) {
                                            case 'select': {
                                                $selectOptions = json_decode($value->additionalFields->default_value, true);
                                                foreach ($selectOptions as $option) {
                                                    if ($option['id'] == $modelAddFieldsValue[$table_name]) {
                                                        $fieldValue = $option['optionName'];
                                                        break;
                                                    }
                                                }
                                                break;
                                            }
                                            case 'date': {
                                                $fieldValue = $modelAddFieldsValue[$table_name] ? date('d.m.y', $modelAddFieldsValue[$table_name]) : '';
                                                break;
                                            }

                                            case 'checkbox': {
                                                $fieldValue = $modelAddFieldsValue[$table_name] ? 'Да' : 'Нет';
                                                break;
                                            }
                                            default: {
                                                $fieldValue = $modelAddFieldsValue[$table_name];
                                            }
                                        }

                                        if ($fieldValue) {
                                            if ($htmlLeftBlock) {
                                                $htmlLeftBlock .= ' <span class="werwe2"></span> ';
                                            }
                                            $htmlLeftBlock .= '<div class="row-item tooltip"><span>' . $fieldValue . '</span> <span class="tooltiptext tooltip-bottom">' . $value->additionalFields->name . '</span>' . '</div>';
                                        }
                                    }
                                } else {
                                    $addFields = AdditionalFields::model()->findAll();

                                    foreach ($addFields as $field) {
                                        $table_name = $field->table_name;
                                        $fieldValue = '';

                                        switch ($field->type) {
                                            case 'select': {
                                                $selectOptions = json_decode($field->default_value, true);
                                                foreach ($selectOptions as $option) {
                                                    if ($option['id'] == $modelAddFieldsValue[$table_name]) {
                                                        $fieldValue = $option['optionName'];
                                                        break;
                                                    }
                                                }
                                                break;
                                            }
                                            case 'date': {
                                                $fieldValue = $modelAddFieldsValue[$table_name] ? date('d.m.y', $modelAddFieldsValue[$table_name]) : '';
                                                break;
                                            }
                                            case 'checkbox': {
                                                $fieldValue = $modelAddFieldsValue[$table_name] ? 'Да' : 'Нет';
                                                break;
                                            }
                                            default: {
                                                $fieldValue = $modelAddFieldsValue[$table_name];
                                            }
                                        }

                                        if ($fieldValue) {
                                            $htmlLeftBlock .= ' <span class="werwe2"></span> ';
                                            $htmlLeftBlock .= '<div class="row-item">' . $fieldValue . '</div>';
                                        }
                                    }
                                }
                            }
                        }

                        //лейблы
                        if ($modelLabelsInClients = LabelsInClients::model()->with('label')->findAll('client_id=:CID', [':CID' => $data->id])) {
                            foreach ($modelLabelsInClients as $value) {
                                $htmlLeftBlock .= '<div class="row-item custom-label" style="display: inline-flex; margin-left: 4px; background-color: ' . $value->label->color . '; color:' . $value->label->color_text . '">' . $value->label->name . '</div>';
                            }
                        }

                        // ПРАВЫЙ БЛОК
                        $htmlRightBlock = '';
                        if ($isRightBlock) {

                            //клиентская инфа
                            if ($isBlockInfoRight && $modelClientFiltersBlockInfoRight->is_id_client) {
                                $htmlRightBlock .= '<div class="row-item idHTML">#' . $data->id . '</div>';
                            }

                            if ($isBlockInfoRight && $modelClientFiltersBlockInfoRight->is_responsible) {
                                $htmlRightBlock .= '<div class="tooltip row-item">' . ($data->responsable->avatar ? CHtml::image($data->responsable->avatar, '', ['class' => 'miniAvatar']) : CHtml::image($data->responsable->roles[0]->name == 'manager' ? '/img/employee.svg' : ($data->responsable->roles[0]->name == 'director' ? '/img/ava_adminisrtr.svg' : '/img/ava_admin.svg'), '', ['class' => 'miniAvatar'])) . CHtml::link($data->responsable->first_name, Yii::app()->createUrl("page/user_profile", array("id" => $data->responsable->id)), ["class" => "link-grey"]) . '</div>';
                            }

                            if ($isBlockInfoRight && $modelClientFiltersBlockInfoRight->is_last_change) {
                                if ($data->change_client_date) {
                                    $changeDateClient = Yii::app()->commonFunction->getChangeDateClient($data->change_client_date);

                                    $htmlRightBlock .=  '<div class="tooltip row-item">' . $changeDateClient . '<span class="tooltiptext tooltip-bottom">Дата изменения</span>' . '</div>';
                                }
                            }

                            if ($isBlockInfoRight && $modelClientFiltersBlockInfoRight->is_create_date) {
                                $htmlRightBlock .= '<div class="tooltip row-item editable">' . date('d.m.y', strtotime($data->creation_date)) . ' в ' . date('H:i', strtotime($data->creation_date)) . '<span class="tooltiptext tooltip-bottom">Дата создания</span>' . '</div>';
                            }

                            if ($isBlockInfoRight && $modelClientFiltersBlockInfoRight->is_step) {
                                $step = StepsInClients::model()->with('steps')->find('clients_id = :ID', [':ID' => $data->id]);
                                if ($step) {
                                    $htmlRightBlock .= '<div class="tooltip row-item">' . $step->steps->name . '<span class="tooltiptext tooltip-bottom">Воронка</span>' . '</div>';
                                }
                            }

                            if ($isBlockInfoRight && $modelClientFiltersBlockInfoRight->is_option_step) {
                                $stepOptionColor = '';
                                $stepOptionName = '';

                                if ($step = StepsInClients::model()->with('steps')->find('clients_id = :ID', [':ID' => $data->id])) {
                                    if ($step->selected_option_id && $stepSelectedOption = StepsOptions::model()->findByPk($step->selected_option_id)) {
                                        $stepOptionColor = $stepSelectedOption->color;
                                        $stepOptionName = $stepSelectedOption->name;
                                    }
                                }

                                if ($stepOptionName) {
                                    $htmlRightBlock .= '<div class="tooltip row-item row-item-step"> <div class="stepIndication" style="background-color:' . $stepOptionColor . ';">' . '</div>' . '<span class="row-item" style="padding-left: 13px;">' . $stepOptionName . '<span class="tooltiptext tooltip-bottom">Этап воронки</span>' . '</span> </div>';
                                }
                            }

                            //доп поля
                            $modelAddFieldsValue = AdditionalFieldsValues::model()->find('client_id=:CID', [':CID' => $data->id]);
                            if ($modelAddFieldsValue) {
                                foreach ($modelClientFiltersBlockAdditionalFieldsRight as $value) {
                                    $fieldValue = '';
                                    $table_name = $value->additionalFields->table_name;

                                    switch ($value->additionalFields->type) {
                                        case 'select':
                                        {
                                            $selectOptions = json_decode($value->additionalFields->default_value, true);
                                            foreach ($selectOptions as $option) {
                                                if ($option['id'] == $modelAddFieldsValue[$table_name]) {
                                                    $fieldValue = $option['optionName'];
                                                    break;
                                                }
                                            }
                                            break;
                                        }
                                        case 'date':
                                        {
                                            $fieldValue = $modelAddFieldsValue[$table_name] ? date('d.m.y', $modelAddFieldsValue[$table_name]) : '';
                                            break;
                                        }
                                        case 'checkbox': {
                                            $fieldValue = $modelAddFieldsValue[$table_name] ? 'Да' : 'Нет';
                                            break;
                                        }
                                        default:
                                        {
                                            $fieldValue = $modelAddFieldsValue[$table_name];
                                        }
                                    }

                                    if ($fieldValue) {
                                        $htmlAddFieldTooltip = ' ' .
                                            '<span class="tip client-page-tip">
                                                <div class="chok-title">' . $value->additionalFields->name . '</div>
                                                
												<div class="chok_wert">
												    <div class="chok">' . $fieldValue . '</div>
												</div>

                                                <div class="chok">' . $value->additionalFields->tip . '</div>
											</span>';

                                        $htmlRightBlock .= '<div onmouseover="handleMouseOver(event)" class="tooltip row-item row-item-grid"><span class="ellipsis">' . $fieldValue . '</span>' . $htmlAddFieldTooltip .'</div>';
                                    }
                                }

                            }
                        } elseif ($isDefaultFilter) {
                            $htmlRightBlock .= '<div class="tooltip row-item">' . ($data->responsable->avatar ? CHtml::image($data->responsable->avatar, '', ['class' => 'miniAvatar']) : CHtml::image($data->responsable->roles[0]->name == 'manager' ? '/img/employee.svg' : ($data->responsable->roles[0]->name == 'director' ? '/img/ava_adminisrtr.svg' : '/img/ava_admin.svg'), '', ['class' => 'miniAvatar'])) . CHtml::link($data->responsable->first_name, Yii::app()->createUrl("page/user_profile", array("id" => $data->responsable->id)), ["class" => "link-grey"]) . '<span class="tooltiptext tooltip-bottom">' . $data->responsable->first_name . '</span>' .  '</div>';

                            $stepOptionColor = '';
                            $stepOptionName = '';

                            if ($step = StepsInClients::model()->with('steps')->find('clients_id = :ID', [':ID' => $data->id])) {
                                if ($step->selected_option_id && $stepSelectedOption = StepsOptions::model()->findByPk($step->selected_option_id)) {
                                    $stepOptionColor = $stepSelectedOption->color;
                                    $stepOptionName = $stepSelectedOption->name;
                                }
                            }

                            if ($stepOptionName) {
                                $htmlRightBlock .= '<div class="tooltip row-item row-item-step"> <div class="stepIndication" style="background-color:' . $stepOptionColor . ';">' . '</div>' . '<span class="row-item" style="padding-left: 13px;">' . $stepOptionName . '</span>' . '<span class="tooltiptext tooltip-bottom">' . $stepOptionName . '</span>' . '</div>';
                            }
                        }

                        return
                            '<div class="clients-page-row">' .
                                '<div class="block-left">' .
                                    '<div class="block-left-header">' .
                                        '<input type="checkbox" name="selectedUsers[]" value="' . $data->id . '" class="row-ch form-control_2 checkBox ' . ($showCheckboxes ?: 'hide') . '">' .

                                        CHtml::link($data->name, Yii::app()->createUrl("page/client_profile", array("id" => $data->id)), ['class' => '']) .
                                        (count($data->clientsFiles) > 0 ? '<a class="file_add" tabindex="1"><img src="/img/paper-clip.svg"></a>' : '') .

                                    '</div>' .

                                    '<div class="block-left-content">' . $htmlLeftBlock . '</div>' .
                                '</div>'.

                                '<div class="block-right" style="' . ($htmlRightBlock ? '' : 'display: none') .'">' .
                                    '<div class="block-right-content">' . $htmlRightBlock . '</div>' .
                                '</div>' .
                            '</div>';
                    }
                ),
            )));
        ?>

    </div>
</div>


<script>
    // устанавливаем выбранные ранее метки, на случай если валидация в методе не прошла
    var listLabels = $(".block-labelsInProfile .block-elem") || [],
        listOption = <?echo json_encode($listStepOptionJS)?>;

    clickLabel = function (id) {
        document.location.href = '/page/clients_page?labelId=' + id;
    };

    function handleMouseOver(e) {
        if (e.target.tagName === 'SPAN' && e.target.textContent.length < 22) {
            e.target.nextElementSibling.style.display = 'none';
        }
    }

    window.onload = function() {
        const footerNode = document.getElementById('footer');
        footerNode.classList.add('footer-client-filter');

    };

</script>
<script src="/js/mass_scripts.js?r=<?= Rand(1, 11000) ?>"></script>

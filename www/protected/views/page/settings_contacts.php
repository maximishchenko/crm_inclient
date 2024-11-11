<?php $this->pageTitle = 'Контакты | Настройки'; ?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'settings-contacts',
    'htmlOptions' => [
        'enctype' => 'multipart/form-data',
        'class' => 'page-form'
    ]
    // 'enableAjaxValidation' => true,
));
?>

<div class="clients-hat">
    <div class="settings-name">
        <?php echo CHtml::link('Настройки', array('page/settings_additional_field')); ?>
        <img src="/img/right-arrow-button.svg">
        Контакты
    </div>
    <div class="goback-link pull-right" style="margin-bottom: 25px;">

    </div>
</div>
<main class="content full2 settingsLabels" role="main">
    <div class="box_edituser_left">
        <div class="edit_user_0anketa">
            <div class="content-01">
                <?php $this->renderPartial('settings_main_nav', array('contacts' => true)); ?>

                <div class="user-table-block_pola fixWidth">
                    <ul id="ul-listTabs">
                        <?php
                        $listTabs = [
                            'import' => 'Импорт',
                            'export' => 'Экспорт',
                            'duplicate' => 'Дубли контактов',
                        ];
                        foreach ($listTabs as $key => $tab) {
                            echo
                                '<li id="' . $key . '" class="button-change-table sectorsBlock '
                                . ($typeTab != $key ?: 'active') . '" onclick="changeTabs(' . "'" . $key . "'" . ');">' . $tab .
                                '</li>';
                        }
                        ?>
                    </ul>
                </div>

                <? switch ($selectedTypeDuplicate) {
                    case 'miss':
                    {
                        $typeDuplicateDescription = "Дубли: " . $countImportDuplicate;
                        break;
                    }
                    case 'rewrite':
                    {
                        $typeDuplicateDescription = "Дубли: перезаписано " . $changeCountClient;
                        break;
                    }
                    case 'join':
                    {
                        $typeDuplicateDescription = "Дубли: склеяно " . $changeCountClient;
                        break;
                    }
                    case 'rename':
                    {
                        $typeDuplicateDescription = "Дубли: переименовано " . $changeCountClient;
                        break;
                    }
                } ?>


                <? if (isset($fileExportPath) && $fileExportPath) { ?>
                    <script type="module">
                        import {NotificationBar} from '/js/notificationBar.js';
                        let exportCountClients = <? echo json_encode($exportCountClients)?>;
                        const notificationBar = new NotificationBar({
                            type: 'success',
                            title: 'Экспорт завершен',
                            description: "Контакты: " + exportCountClients || 0
                        });
                        notificationBar.show();
                    </script>
                <? } elseif ($importSuccess) { ?>
                    <script type="module">
                        import {NotificationBar} from '/js/notificationBar.js';
                        let responsible = <? echo '"' . $responsible . '"'?>;
                        let countImportClient = <? echo $countImportClient?>;
                        let typeDuplicateDescription = <? echo '"' . $typeDuplicateDescription . '"'?>;
                        const notificationBar = new NotificationBar({
                            type: 'success',
                            title: 'Импорт завершен',
                            description: "Ответственный: " + responsible + ". <br>Импортировано контактов: " + countImportClient + ". <br>" + typeDuplicateDescription
                        });
                        notificationBar.show();
                    </script>
                <? } elseif ($duplicateSuccess) { ?>

                    <script type="module">
                        import {NotificationBar} from '/js/notificationBar.js';
                        const notificationBar = new NotificationBar({
                            type: 'success',
                            title: 'Сохранено',
                            description: "Настройки дублирования сохранены"
                        });
                        notificationBar.show();
                    </script>
               <? } ?>


                <div class="centre_settings_5" style="padding: 70px 190px;">

                    <? if ($errorMessage) { ?>
                        <div class="form-error">
                            <? echo $errorMessage ?>
                        </div>
                    <? } ?>


                    <div id="exportBlock" class="hide">
                        <div class="blockContacts-header">Выберите поля, которые должны попасть в файл экспорта:</div>
                        <div class="blockContacts-content">
                            <? foreach ($sectionFieldsExp as $id => $section) {
                                if (isset($section['fields'])) {
                                    ?>
                                    <div class="section-row">
                                        <div class="section-row-header">
                                            <label class="labelForCheckboxAll"
                                                   for="Export_checkAllAdditionFields_<? echo $id ?>">
								<span class="strong">
                                    <? echo $section['name'] ?>
                                </span>
                                            </label>
                                            <?
                                            if (!($allCheckboxStatus = (isset($checkAllAdditionFields[$id]) && $checkAllAdditionFields[$id]))) {
                                                $allCheckboxStatus = true;
                                                foreach ($section['fields'] as $value) {
                                                    if (!$value['active']) {
                                                        $allCheckboxStatus = false;
                                                        break;
                                                    }
                                                }
                                            }
                                            ?>
                                            <?
                                            echo CHtml::checkBox("Export[checkAllAdditionFields][$id]", $allCheckboxStatus,
                                                [
                                                    "onClick" => "allChoiceSection(event, sectionContent$id)",
                                                    "id" => "Export_checkAllAdditionFields_$id",
                                                    "class" => "form-control_1 margin-top-6"
                                                ])
                                            ?>
                                        </div>
                                        <div class="section-row-content" id="sectionContent<? echo $id ?>">
                                            <?
                                            foreach ($section['fields'] as $fields) { ?>
                                                <div>
                                                    <?
                                                    $fieldFio = $fields['table_name'] === 'fieldFio';
                                                    ?>

                                                    <? echo CHtml::checkBox("Export[sections][$fields[table_name]]",
                                                        $fields['active'] || $fieldFio,
                                                        [
                                                            'onClick' => "setValueAllCheckbox(event," . "'#Export_checkAllAdditionFields_$id'," . "sectionContent$id)",
                                                            'disabled' => $fieldFio,
                                                            "class" => "form-control_1 margin-right-5",
                                                            'id' => $fields['table_name']
                                                        ])
                                                    ?>
                                                    <label class="labelForCheckboxOne"
                                                           for="<? echo $fields['table_name'] ?>"><? echo $fields['name'] ?></label>

                                                </div>
                                            <? }
                                            ?>
                                        </div>
                                    </div>
                                <? }
                            } ?>
                        </div>

                        <div class="blockContacts-header">Выберите этапы и метки, которые должны попасть в файл
                            экспорта:
                        </div>
                        <div class="blockContacts-content">
                            <div class="blockContacts-content-row">
                                <div class="steps-block">
                                    <div class="section-row-header">
                                        <label class="labelForCheckboxAll" for="Export_checkAllSteps">
                                            <span class="strong">Воронка</span>
                                        </label>
                                        <?
                                        if (!($allCheckboxStatus = isset($checkAllSteps) && $checkAllSteps)) {
                                            $allCheckboxStatus = true;
                                            foreach ($stepsExp as $value) {
                                                if (!$value['active']) {
                                                    $allCheckboxStatus = false;
                                                    break;
                                                }
                                            }
                                        } ?>

                                        <?
                                        echo CHtml::checkBox("Export[checkAllSteps]", $allCheckboxStatus,
                                            [
                                                'onClick' => "allChoiceSection(event, stepsBlockContent)",
                                                "id" => "Export_checkAllSteps",
                                                "class" => "form-control_1 margin-top-6"
                                            ])
                                        ?>
                                    </div>
                                    <div class="section-row-content" id="stepsBlockContent" style="width: 200px;">
                                        <? foreach ($stepsExp as $step) { ?>
                                            <div>
                                                <? echo CHtml::checkBox("Export[steps][$step[id]]", $step['active'],
                                                    [
                                                        'onClick' => "setValueAllCheckbox(event, Export_checkAllSteps, stepsBlockContent)",
                                                        "class" => "form-control_1 margin-right-5",
                                                        "id" => "step_$step[id]"
                                                    ])
                                                ?>
                                                <label class="labelForCheckboxOne" for="step_<? echo $step['id'] ?>">
                                                    <? echo $step['name'] ?>
                                                </label>
                                            </div>
                                        <? } ?>
                                    </div>
                                </div>


                                <? if (count($allLabels) > 0) { ?>
                                    <div style="margin-left: 20px;width: 100%;">
                                        <div style="width: 100%;background: #ECECEC;padding: 6px 0px 6px 10px;">
                                            <span><strong>Метки</strong></span>
                                            <a href="#" id="editLabels"
                                               onclick="return false;" style="margin-right: 10px;color: #707070;">выбрать
                                                метки</a>
                                        </div>

                                        <div>
                                            <div class="labels-block-content">
                                                <div class="customDropDownListLabels hide">
                                                    <ul>
                                                        <? foreach ($allLabels as $label) { ?>
                                                            <li id="labelLi <? echo $label->id ?>" class="labelLi"
                                                                name="Clients[labelLi<? echo $label->id ?>]"
                                                                onclick="changeLabel('<? echo $label->id; ?>');">
                                                                <?
                                                                echo CHtml::checkBox("Export[labels][$label->id]", isset($customSelectedLabels[$label->id]), [
                                                                    'class' => 'hide', 'id' => 'checkbox' . $label->id
                                                                ]);
                                                                $operType = isset($customSelectedLabels[$label->id]) ? 'added' : 'deleted';
                                                                ?>
                                                                <div class="<? echo $operType; ?>"
                                                                     id="blockOper<? echo $label->id; ?>"></div>
                                                                <div class="block-color"
                                                                     id="labelColor<? echo $label->id; ?>"
                                                                     style="background-color: <? echo $label->color ?>"></div>
                                                                <span id="labelText<? echo $label->id; ?>"><? echo $label->name ?></span>
                                                            </li>
                                                        <? } ?>

                                                    </ul>
                                                </div>

                                                <div class="block-labelsInProfile">
                                                    <? if (count($customSelectedLabels) > 0) {
                                                        foreach ($customSelectedLabels as $label) { ?>
                                                            <div class="block-elem" id="blockElem<? echo $label->id ?>">
                                                                <div class="block-color"
                                                                     style="background-color: <? echo $label->color ?>"></div>
                                                                <span><? echo $label->name ?></span>
                                                            </div>
                                                        <? }
                                                    } else {
                                                        echo '<span id="selAllLabels">Все метки</span>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                <? } ?>
                            </div>
                        </div>

                        <div class="blockContacts-header">Выберите пользователей, которые должны попасть в файл
                            экспорта:
                        </div>
                        <div class="blockContacts-content">
                            <? foreach ($usersExp as $role => $groupUsers) {
                                if (count($groupUsers['users']) > 0) {
                                    ?>
                                    <div class="section-row">
                                        <div class="section-row-header">
                                            <label class="labelForCheckboxAll"
                                                   for="Export_checkAllUsers_<? echo $role ?>">
								<span class="strong">
                                    <? echo $groupUsers['name'] ?>
                                </span>
                                            </label>
                                            <?
                                            if ($groupUsers['name'] != 'Админ') { ?>
                                                <?
                                                if (!($allCheckboxStatus = isset($checkAllUsers[$role]) && $checkAllUsers[$role])) {
                                                    $allCheckboxStatus = true;
                                                    foreach ($groupUsers['users'] as $value) {
                                                        if (!$value['active']) {
                                                            $allCheckboxStatus = false;
                                                            break;
                                                        }
                                                    }
                                                } ?>

                                                <?
                                                echo CHtml::checkBox("Export[checkAllUsers][$role]", $allCheckboxStatus,
                                                    [
                                                        'onClick' => "allChoiceSection(event, usersContent$role)",
                                                        "id" => "Export_checkAllUsers_" . $role,
                                                        "class" => "form-control_1 margin-right-5"
                                                    ])
                                                ?>
                                                <?
                                            }
                                            ?>

                                        </div>
                                        <div class="section-row-content usersGroupRow"
                                             id="usersContent<? echo $role ?>">
                                            <? foreach ($groupUsers['users'] as $groupUser) { ?>
                                                <div>
                                                    <? echo CHtml::checkBox("Export[users][$groupUser[id]]", $groupUser['active'],
                                                        [
                                                            'onClick' => ($role != 'admin' ? "setValueAllCheckbox(event, '#Export_checkAllUsers_" . $role . "', usersContent" . "$role)" : ''),
                                                            "class" => "form-control_1 margin-right-5",
                                                            "id" => "user_$groupUser[id]"
                                                        ])
                                                    ?>
                                                    <?
                                                    echo $groupUser['avatar'] ? CHtml::image($groupUser['avatar'], '', ['class' => 'miniAvatar']) :
                                                        CHtml::image($role == 'manager' ? '/img/employee.svg' : ($role == 'director' ? '/img/ava_adminisrtr.svg' : '/img/ava_admin.svg'), '', ['class' => 'miniAvatar']);
                                                    ?>
                                                    <label class="labelForCheckboxOne"
                                                           for="user_<? echo $groupUser['id'] ?>">
                                                        <? echo $groupUser['name'] ?>
                                                    </label>
                                                </div>
                                            <? } ?>
                                        </div>
                                    </div>
                                <? }
                            } ?>
                        </div>

                        <div class="block-info">
                            <div class="exportBtn">
                                <?php echo CHtml::submitButton('Скачать файл', array('class' => 'btn', 'name' => 'exportBtn', 'id' => 'exportBtnId')); ?>
                            </div>
                            <div id="preloader" style="margin: 0 auto;"></div>
                        </div>
                    </div>

                    <!-- Импорт -->
                    <div id="importBlock" class="hide">
                        <div class="block-info">
                            <div class="block-row">
                                <div class="row-label">Скачать пример:</div>
                                <?php echo CHtml::link('Import_Example.xlsx', Yii::app()->createUrl("page/settings_contacts?example=true")); ?></li>
                                <?php echo CHtml::link('download.xlsx', Yii::app()->createUrl("page/settings_contacts?example=0&error=" . $fileErrorPath), ['class' => 'hide', 'id' => 'downloadFileError']); ?></li>
                                <?php echo CHtml::link('download.xlsx', Yii::app()->createUrl("page/settings_contacts?example=0&error=0&export=" . $fileExportPath), ['class' => 'hide', 'id' => 'downloadFileExport']); ?></li>
                            </div>

                            <div class="block-row">
                                <div class="row-label">
                                    Ответственный:
                                </div>
                                <div class="row-blockContent">
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

                                    // выбор значения в селекторе
                                    $client_resp_role = UsersRoles::model()->find('user_id=' . $templateClient->responsable_id);
                                    if ($templateClient->responsable_id == Yii::app()->user->id) {
                                        $selected_option = array('i' => array('selected' => true));
                                    } elseif ($client_resp_role->itemname == 'director') {
                                        $selected_option = array('director' => array('selected' => true));
                                    } elseif ($client_resp_role->itemname == 'manager') {
                                        $selected_option = array('manager' => array('selected' => true));
                                    } else {
                                        $selected_option = array('no' => array('selected' => true));
                                    }

                                    $directors_block_to_display = $client_resp_role->itemname == 'director' && count($managers) > 0
                                    && key($selected_option) != 'i' ? 'style="display:block"' : '';
                                    $managers_block_to_display = $client_resp_role->itemname == 'manager' && count($managers) > 0
                                    && key($selected_option) != 'i' ? 'style="display:block"' : '';
                                    ?>
                                    <?php echo $form->dropDownList($templateClient, 'responsable_id', $responsible_options,
                                        array(
                                            'options' => $selected_option,
                                            'class' => 'styled permis editable typeAccess',
                                            'name' => 'type')
                                    ); ?>

                                    <div class="access-options access-tab"
                                         id="director" <?php echo $directors_block_to_display ?>>
                                        <?php echo $form->dropDownList($templateClient, 'director_id', CHtml::listData($directors, 'id', 'first_name'),
                                            array('options' => array($templateClient->responsable_id => array('selected' => true)), 'class' => 'styled')); ?>
                                    </div>
                                    <div class="access-options access-tab"
                                         id="manager" <?php echo $managers_block_to_display ?>>
                                        <?php echo $form->dropDownList($templateClient, 'manager_id', CHtml::listData($managers, 'id', 'first_name'),
                                            array('options' => array($templateClient->responsable_id => array('selected' => true)), 'class' => 'styled')); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Дубли-->
                            <div class="block-row">
                                <div class="row-label">Дубли:</div>
                                <div class="row-blockContent">
                                    <?php
                                    echo CHtml::dropDownList("Clients[Duplicate]",
                                        $selectedTypeDuplicate, $configDuplicateList, ['class' => 'styled select']);
                                    ?>
                                    <div id="blockUniqueField">
                                        <? foreach ($uniqueAdditionalFields as $key => $value) { ?>
                                            <div class="rowUniqueField">
                                                <? echo CHtml::checkBox("uniqueAdditionalFields[$value[table_name]]", $value['active'],
                                                    [
                                                        'onClick' => "setValueUniqueFieldCheckbox(event)",
                                                        "class" => "form-control_1",
                                                        'id' => "uniqueField_$value[table_name]"
                                                    ])
                                                ?>

                                                <label class="labelForCheckboxOne"
                                                       for="uniqueField_<? echo $value['table_name'] ?>">
                                                    <? echo $value['name'] ?>
                                                </label>
                                            </div>
                                        <? } ?>
                                    </div>
                                </div>
                                <div style="margin-top: 19px;">
                                    <a class="help_anim" tabindex="1">
                                        <img src="/img/question-mark.svg">
                                        <span class="tip_help" style="padding: 15px;top: 18px;">
                                            <div class="help_di">
                                                    <span>Пропустить</span><br>
                                                    <div>
                                                    Дубли из файла не попадут в срм. Чем больше параметров включено (Имя контакта + Телефон + E-mail), тем легче пропустить дубли в систему
                                                    </div>
                                            </div>
                                            <div class="help_di" style="margin-top: 15px;">
                                                    <span>Перезаписать</span><br>
                                                    <div>
                                                    Контакт будет перезаписан дублем из файла
                                                    </div>
                                            </div>
                                            <div class="help_di" style="margin-top: 15px;">
                                                    <span>Склеить</span><br>
                                                    <div>
                                                    В пустые поля контакта запишутся заполненные поля дубля из файла. Заполненные поля контакта не изменятся. Параметры контакта, которые останутся без изменений: ответственный, дата создания, селектор, чебокс, дата, метка и воронка
                                                    </div>
                                            </div>
                                            <div class="help_di" style="margin-top: 15px;">
                                                    <span>Переименовать</span><br>
                                                    <div>
                                                    Каждому новому дублю присвоится порядковый номер. Пример: 1) имя контакта_1; 2) имя контакта_2 и т.д.
                                                    </div>
                                            </div>
                                        </span>
                                    </a>
                                </div>

                            </div>

                            <!-- ЭТАПЫ -->
                            <div class="block-row">
                                <!-- ЭТАПЫ -->
                                <? if (count($listStep) > 0) { ?>
                                    <div class="row-label">Этапы:</div>
                                    <div class="row-blockContent">

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
                                                    <input type="text" value="<? echo $selectedOption->id ?>"
                                                           class="hide"
                                                           name="StepsInClients[selected_option_id]">
                                                </div>
                                                <div class="jq-selectbox__trigger">
                                                    <div class="jq-selectbox__trigger-arrow"></div>
                                                </div>
                                            </div>

                                            <div class="color-customDropDawnList client shortWidth hide"
                                                 style="max-width: 250px;">
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
                                    </div>
                                <? } ?>
                            </div>

                            <!-- МЕТКИ2 -->
                            <? if (count($allLabels) > 0) { ?>
                                <div class="block-row">
                                    <div class="row-label">
                                        Метки:
                                    </div>
                                    <div>
                                        <a class="delete" id="editLabelsImport"
                                           onclick="return false;">Выбрать метки</a>
                                        <div class="row-blockContent" style="margin-top: 10px;margin-bottom: 15px;">
                                            <div class="customDropDownListLabelsImport hide">
                                                <ul>
                                                    <? foreach ($allLabels as $label) { ?>
                                                        <li id="labelLi <? echo $label->id ?>" class="labelLi"
                                                            name="Clients[labelLi<? echo $label->id ?>]"
                                                            onclick="changeLabelImport('<? echo $label->id; ?>');">
                                                            <?
                                                            echo $form->checkBox($templateClient, "Labels[$label->id]", [
                                                                'id' => 'checkboxImport' . $label->id,
                                                                'class' => 'hide',
                                                                'checked' => isset($customSelectedLabelsImport[$label->id])
                                                            ]);
                                                            $operType = isset($customSelectedLabelsImport[$label->id]) ?
                                                                'added' : 'deleted';
                                                            ?>
                                                            <div class="<? echo $operType; ?>"
                                                                 id="blockOperImport<? echo $label->id; ?>"></div>
                                                            <div class="block-color"
                                                                 id="labelColorImport<? echo $label->id; ?>"
                                                                 style="background-color: <? echo $label->color ?>"></div>
                                                            <span id="labelTextImport<? echo $label->id; ?>"><? echo $label->name ?></span>
                                                        </li>
                                                    <? } ?>

                                                </ul>
                                            </div>

                                            <div class="block-labelsInProfileImport">
                                                <? if (count($customSelectedLabelsImport) > 0) {
                                                    foreach ($customSelectedLabelsImport as $label) { ?>
                                                        <div class="block-elem"
                                                             id="blockElemImport<? echo $label->id ?>">
                                                            <div class="block-color"
                                                                 style="background-color: <? echo $label->color ?>"></div>
                                                            <span><? echo $label->name ?></span>
                                                        </div>
                                                    <? }
                                                } else {
                                                    echo '<span id="selAllLabelsImport">Без меток</span>';
                                                }
                                                ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <? } ?>


                            <div class="block-row">
                                <div class="row-label">Импорт:</div>
                                <?
                                echo CHtml::tag('div', ['id' => 'fakeButton', 'class' => 'upload_button_2']);
                                echo 'Зарузить файл';
                                echo CHtml::tag('/div');

                                echo CHtml::tag('/div');
                                echo CHtml::activeFileField($fileModel, 'fileLoad', ['id' => 'loadImage', 'style' => 'display:none']);
                                echo CHtml::tag('div', ['id' => 'fakeButtonNameFile', 'class' => 'fakeButtonAvatarNameImport']);
                                ?>
                            </div>
                        </div>

                        <div class="block-info">
                            <div class="importBtn">
                                <?php echo CHtml::submitButton('Начать импорт', array('class' => 'btn', 'name' => 'importBtn', 'id' => 'importBtnId')); ?>
                                <div id="preloaderImport"></div>
                            </div>

                        </div>
                    </div>

                    <!-- Дубли -->
                    <div id="duplicateBlock" class="hide">
                        <div class="section-row">
                            <div style="width: 100%;background: #ECECEC;padding: 6px 0px 6px 10px;margin-top: 22px;">
                                <span><strong>Правила дублирования</strong></span>
                                <a href="#" id="editLabels"
                                   style="margin-right: 10px;color: #707070;"></a>
                            </div>
                            <div class="section-row-content-vertical">
                                <div class="block-info">
                                    <?php
                                    echo CHtml::radioButtonList('duplicateAdditionalFieldsEnabled', $duplicateAdditionalFieldsEnabled, ['0' => 'Выключено', '1' => 'Включено'], [
                                        'separator' => ' ',
                                        'labelOptions' => ['style' => 'display:inline;cursor: pointer;margin-right: 10px'],
                                        'onClick' => 'hiddenBlockEnabled()'
                                    ]);
                                    ?>
                                    <br>
                                    <div id="blockDuplicateFields">
                                    <p style="margin: 10px 0px 4px 0px">Считать клиента дублем, если совпадают поля:</p>
                                    <div id="blockUniqueFieldDuplicate">
                                    <?php
                                    foreach ($allDuplicatesFields as $value) { ?>
                                        <div class="rowUniqueField">
                                            <?= CHtml::checkBox("duplicateAdditionalFields[$value[table_name]]", in_array($value['table_name'], $duplicateParam),
                                                [
                                                    'onClick' => "setValueUniqueFieldCheckboxDuplicate(event)",
                                                    "class" => "form-control_1",
                                                    'id' => "duplicateField_$value[table_name]"
                                                ])
                                            ?>

                                            <label class="labelForCheckboxOne"
                                                   for="duplicateField_<? echo $value['table_name'] ?>">
                                                <? echo $value['name'] ?>
                                            </label>
                                        </div>
                                    <? } ?>
                                    </div>
                                    </div>
                                    <div class="block-info">
                                        <div class="duplicateBtn">
                                            <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn', 'name' => 'duplicateBtn', 'id' => 'duplicateBtnId', 'style' => 'margin-top: 15px;float: left;')); ?>
                                        </div>
                                        <div id="preloaderDuplicate" style="margin: 0 auto;margin-top: 15px;float: left;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="right-sidebar">
            <div class="title_name_2">Справка
                <div class="more"><img src="/img/external-link-symbol.svg"><a href="https://inclient.ru/category/help-crm/" target="_blank" style="color: #707070;">Подробнее</a></div>
            </div>
            <div class="popup__form_actions">
                <div class="solid_an_client">
                    <p><strong>Импорт контактов</strong></p>
                    <br>
                    <p>Загрузка контактов в CRM систему с помощью файла. Скачайте файл образец, заполните его данными и загрузите в срм систему. Подробнее об импорте <a href="https://inclient.ru/import-crm/" target="_blank">здесь</a>.</p>
                </div>
                <div>
                    <p><strong>Экспорт контактов</strong></p>
                    <br>
                    <p>Возможность выгрузить базу контактов из CRM системы в файл экспорта с расширением «.xlsx». Выберите нужные поля, этапы, метки, ответственных и скачайте контакты. Подробнее об экспорте <a href="https://inclient.ru/export-crm/" target="_blank">здесь</a>.</p>
                </div>
            </div>
        </div>

    <?php $this->endWidget(); ?>
</main>

<script>
    checkRadioDuplicateEnabled = function () {
        return $('input[name="duplicateAdditionalFieldsEnabled"]:checked').val();
    };

    hiddenBlockEnabled = function () {
        if (checkRadioDuplicateEnabled() == 1)  {
            $("#blockDuplicateFields").show();
        } else {
            $("#blockDuplicateFields").hide();
        }
    };

    hiddenBlockEnabled();

    var tabActive = $('#ul-listTabs li.active');
    $('#' + tabActive[0].id + 'Block').show();

    $("#fakeButton").click(function () {
        $("#loadImage").click();
    });

    document.getElementById('loadImage').onchange = function () {
        if (this.files[0]) {
            document.getElementById('fakeButtonNameFile').innerHTML = this.files[0].name;
        }
    };

    changeTabs = function (tab) {
        tabActive.removeClass('active');
        $('#' + tabActive[0].id + 'Table').hide();

        tabActive = $('#' + tab);
        tabActive.addClass('active');
        $('#' + tabActive[0].id + 'Table').show();
    };

    // МЕТКИ НАЧАЛО

    // устанавливаем выбранные ранее метки, на случай если валидация в методе не прошла
    var listLabels = $(".block-labelsInProfile .block-elem") || [],
        listOption = <?echo json_encode($listStepOptionJS)?>;

    var listLabelsImport = $(".block-labelsInProfileImport .block-elem") || [];


    for (var i = 0; i < listLabels.length; i++) {
        let labelId = listLabels[i].id.replace('blockElem', '');
        let elem = $('#blockOper' + labelId);
        $('#checkbox' + labelId).prop('checked', true);
        elem.removeClass('deleted');
        elem.addClass('added');
    }

    for (var i = 0; i < listLabelsImport.length; i++) {
        let labelId = listLabelsImport[i].id.replace('blockElemImport', '');
        let elem = $('#blockOperImport' + labelId);
        $('#checkboxImport' + labelId).prop('checked', true);
        elem.removeClass('deleted');
        elem.addClass('added');
    }

    changeLabel = function (labelId) {
        var elem = $('#blockOper' + labelId),
            divColor = $('#labelColor' + labelId)[0].outerHTML,
            spanText = $('#labelText' + labelId)[0].outerHTML;

        if ($('#checkbox' + labelId).is(':checked')) {
            $('#checkbox' + labelId).prop('checked', false);
            elem.removeClass('added');
            elem.addClass('deleted');
            $('#blockElem' + labelId).remove();
        } else {
            $('#checkbox' + labelId).prop('checked', true);
            elem.removeClass('deleted');
            elem.addClass('added');
            var blockShowLabels = $('.block-labelsInProfile'),
                labelDIv = '<div class="block-elem" id="blockElem' + labelId + '">' + divColor + spanText + '</div>';
            blockShowLabels.append(labelDIv);
        }

        if (document.querySelector(".block-labelsInProfile .block-elem")) {
            $('#selAllLabels').remove();
        } else {
            $('.block-labelsInProfile').append('<span id="selAllLabels">Все метки</span>');
        }
    };

    changeLabelImport = function (labelId) {
        var elem = $('#blockOperImport' + labelId),
            divColor = $('#labelColorImport' + labelId)[0].outerHTML,
            spanText = $('#labelTextImport' + labelId)[0].outerHTML;

        if ($('#checkboxImport' + labelId).is(':checked')) {
            $('#checkboxImport' + labelId).prop('checked', false);
            elem.removeClass('added');
            elem.addClass('deleted');
            $('#blockElemImport' + labelId).remove();
        } else {
            $('#checkboxImport' + labelId).prop('checked', true);
            elem.removeClass('deleted');
            elem.addClass('added');
            var blockShowLabels = $('.block-labelsInProfileImport'),
                labelDIv = '<div class="block-elem" id="blockElemImport' + labelId + '">' + divColor + spanText + '</div>';
            blockShowLabels.append(labelDIv);
        }

        if (document.querySelector(".block-labelsInProfileImport .block-elem")) {
            $('#selAllLabelsImport').remove();
        } else {
            $('.block-labelsInProfileImport').append('<span id="selAllLabelsImport">Без меток</span>');
        }
    };

    $("#editLabels").click(function (e) {
        var listLabels = $(".customDropDownListLabels");
        if (listLabels.hasClass('hide')) {
            listLabels.removeClass('hide');
        } else {
            listLabels.addClass('hide');
        }
    });

    $("#duplicateAdditionalFieldsEnabled_1").click(function (e) {
        $("#duplicateField_fieldFio").prop('checked', true);
        $("#duplicateField_fieldTelephone").prop('checked', true);
        $("#duplicateField_fieldEmail").prop('checked', true);
    })

    $("#editLabelsImport").click(function (e) {
        var listLabels = $(".customDropDownListLabelsImport");
        if (listLabels.hasClass('hide')) {
            listLabels.removeClass('hide');
        } else {
            listLabels.addClass('hide');
        }
    });

    // МЕТКИ КОНЕЦ

    allChoiceSection = function (event, parentBlockElemId) {
        let checkboxList = $(parentBlockElemId)[0].querySelectorAll('input[type="checkbox"]');

        for (let i = 0; i < checkboxList.length; i++) {
            if (checkboxList[i].id != 'fieldFio') {
                checkboxList[i].checked = event.target.checked;
            }
        }
    };

    setValueAllCheckbox = function (event, allCheckboxId, parentBlockElemId) {
        let checkboxList = $(parentBlockElemId)[0].querySelectorAll('input[type="checkbox"]');
        let checkboxAllElem = $(allCheckboxId);
        let isTrue = false;
        for (let i = 0; i < checkboxList.length; i++) {
            if (!(isTrue = checkboxList[i].checked)) {
                break;
            }
        }
        checkboxAllElem[0].checked = isTrue;
    };

    changeTabs = function (tab) {
        tabActive.removeClass('active');
        $('#' + tabActive[0].id + 'Block').hide();
        $('.form-error').hide();
        $('.save-message').hide();

        tabActive = $('#' + tab);
        tabActive.addClass('active');
        $('#' + tabActive[0].id + 'Block').show();
    };


    function showDropDawnColor(event) {
        let gh = event.target.closest('#colorSelect').children[1];
        gh.style.display = 'block';
    }

    function changeColor(event, color, name, id) {
        let colorBlock = event.target.closest('#colorSelect').querySelector('.color-block'),
            inputColorBlock = colorBlock.querySelector('input'),
            collectionOptions = document.getElementById("selectStep").options,
            listOptionSelected = listOption[collectionOptions[collectionOptions.selectedIndex].value],
            stepProgressBar = document.getElementsByClassName("step-progressBar")[0],
            spanText = colorBlock.querySelector('span');
        colorBlock.style.backgroundColor = color;
        inputColorBlock.value = id;
        spanText.textContent = name;

        if (listOptionSelected) {
            stepProgressBar.children = null;
            let listElem = '',
                isGrey = false;
            for (let i = 0; i < listOptionSelected.length; i++) {
                listElem += '<div class="progressBar-elem" style="background-color:' + (isGrey ? 'darkgrey' : listOptionSelected[i].color) + '"> </div>';
                if (id == listOptionSelected[i].id) {
                    isGrey = true;
                }
            }
            stepProgressBar.innerHTML = listElem;
        }
    }

    function changeStep() {
        let collectionOptions = document.getElementById("selectStep").options,
            listOptionSelected = listOption[collectionOptions[collectionOptions.selectedIndex].value],
            selectOptions = document.querySelector(".color-customDropDawnList"),
            colorBlock = document.getElementsByClassName("color-block")[0],
            stepProgressBar = document.getElementsByClassName("step-progressBar")[0],
            ul = document.createElement('ul');
        ul.innerHTML = '';
        document.getElementById("colorSelect").style.display = 'inline-flex';
        stepProgressBar.style.display = 'inline-flex';
        if (listOptionSelected) {
            for (let i = 0; i < listOptionSelected.length; i++) {
                ul.innerHTML += "<li value='" + listOptionSelected[i].id + "' onclick='changeColor(event, " + '"' + listOptionSelected[i].color + '"' + ", " + '"' + listOptionSelected[i].name + '", ' + listOptionSelected[i].id + ");'><div class='block-color' style='background-color:" + listOptionSelected[i].color + ";'></div><div class='margin-top-1'>" + listOptionSelected[i].name + "</div></li>";
            }
            selectOptions.replaceChild(ul, selectOptions.children[0]);
            colorBlock.style.backgroundColor = listOptionSelected[0].color;
            colorBlock.children[0].textContent = listOptionSelected[0].name;
            colorBlock.children[1].value = listOptionSelected[0].id;

            let listElem = '';
            for (let i = 0; i < listOptionSelected.length; i++) {
                listElem += '<div class="progressBar-elem" style="background-color:' + (i ? 'darkgrey' : listOptionSelected[i].color) + '"> </div>';
            }
            stepProgressBar.innerHTML = listElem;
        } else {
            document.getElementById("colorSelect").style.display = 'none';
            stepProgressBar.style.display = 'none';
        }
    };

    setValueUniqueFieldCheckbox = function (e) {
        let listCheckbox = $('#blockUniqueField')[0].querySelectorAll('input[type="checkbox"]');
        let isAllCheckedFalse = false;
        for (let i = 0; i < listCheckbox.length; i++) {
            if (listCheckbox[i].checked) {
                isAllCheckedFalse = true;
            }
        }

        if (!isAllCheckedFalse) {
            e.target.checked = true;
        }
    };

    setValueUniqueFieldCheckboxDuplicate = function (e) {
        let listCheckbox = $('#blockUniqueFieldDuplicate')[0].querySelectorAll('input[type="checkbox"]');
        let isAllCheckedFalse = false;
        for (let i = 0; i < listCheckbox.length; i++) {
            if (listCheckbox[i].checked) {
                isAllCheckedFalse = true;
            }
        }

        if (!isAllCheckedFalse) {
            e.target.checked = true;
        }
    };

    $("#settings-contacts").submit(function () {
        $("#preloader").addClass('preloader');
        $("#preloaderImport").addClass('preloader');
        $("#preloaderDuplicate").addClass('preloader');
        $("#importBtnId").hide();
        $("#exportBtnId").hide();
        $("#duplicateBtnId").hide();
    });

    let fileErrorPath = <?echo json_encode($fileErrorPath)?>;
    let fileExportPath = <?echo json_encode($fileExportPath)?>;

        jQuery(function ($) {
            $(document).mouseup(function (e) { // событие клика по веб-документу
                var div = $(".customDropDownListLabels");
                var div2 = $(".customDropDownListLabelsImport"); // тут указываем ID элемента
                if (!div.is(e.target) && div.has(e.target).length === 0 && !$("#editLabels").is(e.target)) {//&& div.has(e.target).length === 0) { // и не по его дочерним элементам
                    div.addClass('hide'); // скрываем его
                }

                if (!div2.is(e.target) && div2.has(e.target).length === 0 && !$("#editLabelsImport").is(e.target)) {//&& div.has(e.target).length === 0) { // и не по его дочерним элементам
                    div2.addClass('hide'); // скрываем его
                }

                if (!$(".color-customDropDawnList").is(e.target)) {
                    $(".color-customDropDawnList").hide();
                }
            });

            if (fileErrorPath) {
                $("#preloader").removeClass('preloader');
                $(".save-message").hide();
                $("#preloaderImport").removeClass('preloader');
                $("#preloaderDuplicate").removeClass('preloader');
                $("#importBtnId").show();
                $('#downloadFileError')[0].click();
            } else if (fileExportPath) {
                $("#preloader").removeClass('preloader');
                $("#preloaderImport").removeClass('preloader');
                $("#preloaderDuplicate").removeClass('preloader');
                $("#exportBtnId").show();
                $('#downloadFileExport')[0].click();
            }
        });

</script>

<?php $this->pageTitle = $modelClientFilter->name .' | Редактировать фильтр'; ?>

<? if ($isAddFilter) { ?>
    <script type="module">
        import {NotificationBar} from '/js/notificationBar.js';

        const notificationBar = new NotificationBar({
            type: 'warning',
            title: 'Новый фильтр создан',
            description: 'Информация в фильтре "<?echo $modelClientFilter->name?>" обновлена'
        });
        notificationBar.show();
    </script>
<? } ?>

<? if ($isEditFilter) { ?>
    <script type="module">
        import {NotificationBar} from '/js/notificationBar.js';

        const notificationBar = new NotificationBar({
            type: 'success',
            title: 'Фильтр изменён',
            description: 'Информация в фильтре "<?echo $modelClientFilter->name?>" обновлена'
        });
        notificationBar.show();
    </script>
<? } ?>

<div class="clients-hat">
    <div class="settings-name">
        <?php echo CHtml::link('Контакты', array('page/clients_page')); ?>
        <img src="/img/right-arrow-button.svg">
        Фильтр #<?echo $modelClientFilter->id . ': ' . $modelClientFilter->name?>
    </div>
    <div class="goback-link pull-right" style="margin-bottom: 25px;"></div>
</div>

<main class="content full2" role="main">

    <?php
    $this->renderPartial('filters/clients_filters', array(
        'user' => $user,
        'selectedFilter' => $modelClientFilter,
        'isShowAddFilter' => true,
    ));
    ?>

    <div class="box_edituser_left">
        <div class="edit_user_0anketa">
            <div class="content-01">
                <div class="title_name_1">
                    <span>Изменить фильтр</span>
                    <div class="more" style="margin-right: 16px;">
                        <img src="/img/external-link-symbol.svg">

                        <a href="https://inclient.ru/setting-filters-crm/" target="_blank" style="color: #707070;">Как настроить фильтры</a>
                    </div>
                </div>

                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'enableAjaxValidation' => false,
                    'method' => 'post',
                    'id' => 'edit-common',
                    'htmlOptions' => [
                        'enctype' => 'multipart/form-data', // для загрузки файлов
                        'class' => 'page-form'
                    ]
                ));
                ?>

                <!-- внешний вид-->
                <div id="appearanceBlock">
                    <div class="settings-main-block" style="width: 580px;margin-left:10px;">

                        <div style="margin: 20px 0px;"></div>

                        <div class="settings-block-row">
                            <div class="profile_info_block clear_fix">
                                <div class="profile_info_header_wrap">
                                    <span class="profile_info_header">Поля в плашке</span>
                                </div>
                            </div>

                            <div class="filters_fields_block">
                                <span>Левый блок</span>

                                <div class="fields_block_list" id="leftBlockFields">

                                </div>
                            </div>

                            <div class="filters_fields_block">
                                <span>Правый блок</span>

                                <div class="fields_block_list" id="rightBlockFields">

                                </div>
                            </div>

                            <div class="settings-filter">
                                <a class="add-btn__set" href="#" onclick="showModal()">Изменить</a>
                            </div>
                        </div>

                        <div class="settings-block-row">
                            <div class="profile_info_block clear_fix">
                                <div class="profile_info_header_wrap">
                                    <span class="profile_info_header">Воронка</span>
                                </div>
                            </div>

                            <div id="filtersStepsBlock">

                            </div>

                            <div class="settings-filter">
                                <a class="add-btn__set" href="#" onclick="showModalSteps()">Изменить</a>
                            </div>
                        </div>

                        <div class="settings-block-row">
                            <div class="profile_info_block clear_fix">
                                <div class="profile_info_header_wrap">
                                    <span class="profile_info_header">Метки</span>
                                </div>
                            </div>

                            <div id="filtersLabelsBlock">
                                <div class="filters_fields_block">
                                    <span> Контакты</span>
                                    <div class="block-labelsInProfile fields_block_list">
                                        <?
                                        $isShowAllLabels = true;
                                        foreach ($filterLabels as $label) {
                                            if ($label['active']) {
                                                $isShowAllLabels = false;
                                        ?>
                                            <div id="blockElem<? echo $label['id'] ?>" class="custom-label" style="<? echo "background-color: " . $label['color'] . "; color: " . $label['color_text']?>"><? echo $label['name'] ?></div>
                                        <? }
                                        }
                                        if ($isShowAllLabels) {
                                            echo '<p id="selAllLabels">Все метки</p>';
                                        }
                                        ?>
                                        <!--<div id="blockElem' + labelId + '" class="custom-label" style="background-color: ' + blockLabelsList[labelId].color + '; color: ' + blockLabelsList[labelId].color_text + '; ">' + blockLabelsList[labelId].name + '</div>;-->
                                    </div>
                                </div>

                                <? if (count($allLabels) > 0) { ?>
                                    <div style="margin-left: 20px;width: 100%;">
                                        <div>
                                            <div class="labels-block-content filters-settings">
                                                <div class="customDropDownListLabels bottom-align hide-important">
                                                    <ul>
                                                        <? foreach ($allLabels as $label) { ?>
                                                            <li id="labelLi <? echo $label->id ?>" class="labelLi"
                                                                name="Clients[labelLi<? echo $label->id ?>]"
                                                                onclick="changeLabel('<? echo $label->id; ?>');">
                                                                <?
                                                                echo CHtml::checkBox("labels_$label->id]", $filterLabels[$label->id]['active'], [
                                                                    'class' => 'hide-important', 'id' => 'checkbox' . $label->id
                                                                ]);
                                                                ?>

                                                                <div class="<? echo $filterLabels[$label->id]['active'] ? 'added' : 'deleted'; ?>"
                                                                     id="blockOper<? echo $label->id; ?>"></div>
                                                                <div class="block-color"
                                                                     id="labelColor<? echo $label->id; ?>"
                                                                     style="background-color: <? echo $label->color ?>"></div>
                                                                <span id="labelText<? echo $label->id; ?>"><? echo $label->name ?></span>
                                                            </li>
                                                        <? } ?>

                                                    </ul>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                <? } ?>
                            </div>


                            <div style="display:flex;margin-left: 230px;">
                                <a href="#" id="editLabels" onclick="return false;">Выбрать метки</a>
                            </div>
                        </div>

                        <div class="settings-block-row">
                            <div class="profile_info_block clear_fix">
                                <div class="profile_info_header_wrap">
                                    <span class="profile_info_header">Ответственный</span>
                                </div>
                            </div>

                            <div id="filtersUsersBlock">

                            </div>

                            <div class="settings-filter">
                                <a class="add-btn__set" href="#" onclick="showModalUsers()">Изменить</a>
                            </div>
                        </div>
                    </div>
                </div>

                <? $this->endWidget(); ?>
            </div>
        </div>
    </div>

    <div id="settingsReference" class="right-sidebar-2">
        <div class="title_name_2">Настройки</div>

        <div class="box-gray__body">
            <div class="box-gray__form">
                <div class="form-group">
                    <label class="label">Наименование: <div class="requiredAddField">*</div></label>
                    <?echo CHtml::textField('nameFilter', $modelClientFilter->name, ['class' => 'form-control']);?>
                    <span id="nameFilterError" class="form-error hide-important">Обязательное поле</span>
                </div>

                <div class="form-group <?echo $user->roles[0]->name === 'manager' ? 'hide' : '' ?>">
                    <label class="label">Кто видит фильтры:</label>
                    <?php
                    $responsible_options = [];

                    switch ($user->roles[0]->name) {
                        case 'manager': {
                            $responsible_options = [
                                'i' => 'Только я',
                            ];
                            break;
                        }
                        case 'director': {
                            $responsible_options = [
                                'i' => 'Только я',
                                'manager' => 'Только менеджеры ',
                            ];
                            break;
                        }
                        default: {
                            $responsible_options = [
                                'all' => 'Все пользователи',
                                'i' => 'Только я',
                                'director' => 'Только руководители',
                                'manager' => 'Только менеджеры ',
                            ];
                        }
                    }
                        echo CHtml::dropDownList('responsible', $modelClientFilter->who_visible, $responsible_options, array('class' => 'styled permis editable typeAccess', 'name' => 'type'));
                    ?>
                </div>

                <div class="form-group">
                    <label class="label">Количество контактов: <div class="requiredAddField">*</div></label>
                    <?echo CHtml::textField('pageSize', $modelClientFilter->page_size, ['class' => 'form-control']);?>
                    <span id="pageSizeError" class="form-error hide-important">Только от 5 до 300 контактов</span>
                </div>

                <div class="form-group">
                    <label class="label">Цвет:</label>
                    <div class="row-input" id="colorSelect">
                        <div class="jq-selectbox__select color-select"
                             onclick="showDropDawnColor(event)">
                            <div class="color-block"
                                 style="background-color: <? echo $filterColors[$modelClientFilter->class_name] ?>">
                                <input type="text" id="classNameId" value="<? echo $modelClientFilter->class_name ?>" class="hide">
                            </div>
                            <div class="jq-selectbox__trigger">
                                <div class="jq-selectbox__trigger-arrow"></div>
                            </div>
                        </div>

                        <div class="color-customDropDawnList-01 shortWidth hide">
                            <ul>
                                <?
                                foreach ($filterColors as $className => $color) {
                                    echo "<li value='$className' onclick='changeColor(event, " . '"' . $color . '"' . ", " . '"' . $className . '"' . ")'><div class='block-color' style='background-color:$color;width: 15px;height: 15px;'></div></li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>

                    <div style="display: flex;line-height: 22px;margin-bottom: 2px;">
                    <?echo CHtml::checkBox('isDefault', $modelClientFilter->is_default, ['class' => 'form-control_anketa']);?>
                    <label class="pointer" style="margin-left: 5px;" for="isDefault">По умолчанию</label>
                    </div>

                    <div style="display: flex;line-height: 22px;margin-bottom: 10px;">
                    <?echo CHtml::checkBox('isFiles', $modelClientFilter->is_files, ['class' => 'form-control_anketa']);?>
                    <label class="pointer" style="margin-left: 5px;" for="isFiles">С файлами</label>
                    </div>

                <div class="operation-button save-filter-btn">
                    <?php echo CHtml::submitButton('Сохранить',
                        [
                            'class' => 'maui_btn',
                            'name' => 'appearanceBtn',
                            'id' => 'filterSaveBtn',
                            'onClick' => 'saveFilter()',
                        ]
                    ); ?>
                </div>

                <div id="deleteBlock">
                    <div class="function-delete" style="display: block;padding-left: 0px;text-align: center;">
                        <a class="delete" href="#">Удалить фильтр</a>
                    </div>

                    <div class="function-delete-confirm" style="display: none;">
                        <span>Подтвердите удаление:</span>
                        <div class="delete-link-block">
                            <a href="#"  class="cancel delete-cancel-btn">Отмена</a>
                            <button onclick="deleteFilter()" class="btn delete-btn-confirm"> Удалить</button>
                        </div>
                    </div>
                </div>

                <div id="preloader" style="margin-left:5px" class="loader loader-center hide-important">
                    <img src="/img/preloader/103.gif">
                </div>
            </div>
        </div>
    </div>
</main>

<!--<script type="module">-->
<script type="module">
    import {ModalWindow} from '/js/modalWindow.js';
    import {NotificationBar} from '/js/notificationBar.js';

    //РАБОТА С МОДАЛЬНЫМ ОКНОМ ДОП ПОЛЕЙ
    var blockFields = {
        'left': {
            'sourceClientInfo': <?echo json_encode($clientInfoLeft)?>,
            'sourceAdditionalFields': <?echo json_encode($sectionFieldsLeft)?>,
            'clientInfo': {},
            'additionalFields': {},
        },

        'right': {
            'sourceClientInfo': <?echo json_encode($clientInfoRight)?>,
            'sourceAdditionalFields': <?echo json_encode($sectionFieldsRight)?>,
            'clientInfo': {},
            'additionalFields': {},
        }
    };

    var activeTypeBlock = 'left';

    var isLeftBlockChooseAll = false;
    var isRightBlockChooseAll = false;

    setDefaultFields();

    var fieldsSelected = {
        'left': {},
        'right': {},
    };

    var options = {
        title: 'Поля в плашке',
        handleCancel: cancelForFields,
        handleConfirm: confirmForFields,
        htmlContent: setModalContent(),
        isValid: checkValidBlocks(),
    };

    var settingsModalWindow = new ModalWindow(options);

    window.showModal = function() {
        refresh();
        settingsModalWindow.show();
    };

    function refresh() {
        var isValidLeftBlock = checkValidLeftBlock();
        var isValidRightBlock = checkValidRightBlock();
        var messageError = '';

        if (!isValidLeftBlock && activeTypeBlock === 'left') {
            messageError = 'Не менее 2-х полей';
        }

        if (!isValidRightBlock && activeTypeBlock === 'right') {
            messageError = 'Только до 4-х полей';
        }

        settingsModalWindow.htmlContent = setModalContent();
        settingsModalWindow.isValid = isValidLeftBlock && isValidRightBlock;
        settingsModalWindow.errorMessage = messageError;
        settingsModalWindow.updateHtmlContent();
    }

    function getHtmlBlocks() {
        // вывод клиентской информации
        // чекаем логики чекеда чекбокса клиентской информации
        blockFields[activeTypeBlock].clientInfo.active = blockFields[activeTypeBlock].clientInfo.fieldsCount === Object.values(blockFields[activeTypeBlock].clientInfo.fields).filter((field) => {
            return field.active === true;
        }).length;

        var clientInfoStr = '' +
            '<div class="filters-fields-block">' +
            '<div class="title-block-fields">' +
            '<strong for="left_client_info" class="margin-right-5">' + blockFields[activeTypeBlock].clientInfo.name + '</strong>' +
            '<input type="checkbox"' + (blockFields[activeTypeBlock].clientInfo.active ? "checked " : "") + ' id="left_client_info" class="form-control_1 margin-right-5" onclick="setCheckboxClientsInfo(event)">' +
            '</div>' +

            '<div class="filters-checkbox-block">' +
            Object.keys(blockFields[activeTypeBlock].clientInfo.fields).map(property => {
                let field = blockFields[activeTypeBlock].clientInfo.fields[property];
                return (
                    '<div class="fields-block">' +
                    '<input type="checkbox" id="left_'+ property +'" class="form-control_1 margin-right-5" onclick="setSelectedFieldsClientsInfo(event,\'' + `${property}` + '\')"' + (field.active ? "checked " : "") + '>' +
                    '<label class="form-control_1 margin-right-5" for="left_' + property + '">' + field.name + '</label>' +
                    '</div>'
                );
            }).join(' ') +
            '</div>' +
            '</div>';

        // вывод секций доп полей в мод. окне
        const addFieldsStr = Object.keys(blockFields[activeTypeBlock].additionalFields).map((sectionId) => {
            let item = blockFields[activeTypeBlock].additionalFields[sectionId];
            let checkActiveFieldsCount = 0;

            // чекаем логику чекеда чекбокса секций
            Object.values(item.fields).map(field => {
                if (field.active) {
                    checkActiveFieldsCount++;
                }
            });

            item.active = checkActiveFieldsCount === item.fieldsCount;

            return (
                '<div class="filters-fields-block">' +
                '<div class="title-block-fields">' +
                '<strong for="left_section_' + sectionId + '" class="margin-right-5">' + item.name + '</strong>' +
                '<input type="checkbox" ' + (item.active ? "checked " : "") + ' id="left_section_' + sectionId + '" class="form-control_1 margin-right-5" onclick="setCheckboxSection(event,' + sectionId + ')">' +
                '</div>' +
                '<div class="filters-checkbox-block">' +
                Object.keys(item.fields).map(fieldId => {
                    let field = item.fields[fieldId];
                    return (
                        '<div class="fields-block">' +
                        '<input type="checkbox" id="'+ field.table_name +'" class="form-control_1 margin-right-5" onclick="setSelectedFields(event,' + sectionId + ', ' + fieldId + ')"' + (field.active ? "checked " : "") + '>' +
                        '<label class="form-control_1 margin-right-5" for="' + field.table_name + '">' + field.name + '</label>' +
                        '</div>'
                    );
                }).join(' ') +
                '</div>' +
                '</div>'
            );
        }).join(' ');

        checkAllSelectedFields();

        var isChooseAll = activeTypeBlock === 'left' ? isLeftBlockChooseAll : isRightBlockChooseAll;

        // верстка самого контента модального окна
        return(
            "<div class='modal-choose-all fields'>" +
            "<label style='margin-top:5px;'>Какие поля показать в плашке:</label>" +
            "<div class='choose_all_block'>" +
            '<input class="form-control_1 margin-right-5" type="checkbox" id="left_choose_all" onclick="setCheckboxAllSelected(event)"' + (isChooseAll ? "checked " : "") + '>' +
            "<label class='pointer' for='left_choose_all'>Выбрать всё</label>" +
            "</div>" +
            "</div>" +

            "<div class=''>" +
            clientInfoStr +
            "</div>" +

            "<div class=''>" +
            addFieldsStr +
            "</div>"
        );
    }

    function setModalContent() {
        var isLeftBlock = activeTypeBlock === 'left';
        var messageHeader = isLeftBlock ? 'Выберите не менее 2-х полей' : 'Выберите до 4-х полей';

        return (
            "<div class='filters-modal-content'>" +
            "<div class='filter-content-header'>" +
            "<div class='modal-tabs'>" +
            "<div id='left_tab' class='filter-tab" + (isLeftBlock ? ' active' : '') + "' onclick='setTabActive(event)'>Левый блок</div>" +
            "<div id='right_tab' class='filter-tab" + (!isLeftBlock ? ' active' : '') + "' onclick='setTabActive(event)'>Правый блок</div>" +
            "</div>" +

            "<div>" + messageHeader + "</div>" +
            "</div>" +
            getHtmlBlocks() +
            "</div>"
        );
    }

    // устанавливаем чекбокс для доп полей
    window.setSelectedFields = function(event, sectionId, fieldId) {
        let field = blockFields[activeTypeBlock].additionalFields[sectionId].fields[fieldId];
        field.active = event.target.checked;

        refresh();
    };

    // устанавливаем чекбокс секции
    window.setCheckboxSection = function(event, sectionId) {
        let sectionFields = blockFields[activeTypeBlock].additionalFields[sectionId];

        if (sectionFields) {
            sectionFields.active = event.target.checked;

            Object.keys(sectionFields.fields).map(fieldId => {
                sectionFields.fields[fieldId].active = sectionFields.active;
            });

            refresh();
        }
    };

    // устанавливаем чекбокс для клиентской инфы
    window.setSelectedFieldsClientsInfo = function(event, property) {
        let field = blockFields[activeTypeBlock].clientInfo.fields[property];
        field.active = event.target.checked;

        refresh();
    };

    // устанавливаем чекбокс секции
    window.setCheckboxClientsInfo = function(event) {
        Object.values(blockFields[activeTypeBlock].clientInfo.fields).map(field => {
            field.active = event.target.checked;
        });

        refresh();
    };

    // устанавливаем чекбокс для всех полей левого блока
    window.setCheckboxAllSelected = function(event) {
        isLeftBlockChooseAll = event.target.checked;

        Object.values(blockFields[activeTypeBlock].clientInfo.fields).forEach(field => {
            field.active = isLeftBlockChooseAll;
        });

        Object.values(blockFields[activeTypeBlock].additionalFields).forEach(section => {
            Object.values(section.fields).forEach(field => {
                field.active = isLeftBlockChooseAll;
            });
        });

        refresh();
    };

    // проверка выбраны ли все поля в активном блоке. Меняем значение этого чекбокса
    function checkAllSelectedFields() {
        var clientInfoFieldList = Object.values(blockFields[activeTypeBlock].clientInfo.fields);
        var additionalFieldList = Object.values(blockFields[activeTypeBlock].additionalFields);
        var isChooseAllClientInfo = false;
        var isChooseAllAdditionalField = true;
        var isChooseSectionAdditionalField = false;

        isChooseAllClientInfo = !clientInfoFieldList.find(field => {
            return field.active == false;
        });

        if (isChooseAllClientInfo) {
            for (var i = 0; i < additionalFieldList.length; i++) {
                isChooseSectionAdditionalField = !Object.values(additionalFieldList[i].fields).find(field => {
                    return field.active == false;
                });

                if (!isChooseSectionAdditionalField) {
                    isChooseAllAdditionalField = false;
                    break;
                }
            }
        }

        if (activeTypeBlock === 'left') {
            isLeftBlockChooseAll = isChooseAllClientInfo && isChooseAllAdditionalField;
        } else {
            isRightBlockChooseAll = isChooseAllClientInfo && isChooseAllAdditionalField;
        }

    }

    function setDefaultFields() {
        blockFields.left.clientInfo = JSON.parse(JSON.stringify(blockFields.left.sourceClientInfo));
        blockFields.left.additionalFields = JSON.parse(JSON.stringify(blockFields.left.sourceAdditionalFields));

        blockFields.right.clientInfo = JSON.parse(JSON.stringify(blockFields.right.sourceClientInfo));
        blockFields.right.additionalFields = JSON.parse(JSON.stringify(blockFields.right.sourceAdditionalFields));

        activeTypeBlock = 'left';
    }

    //валидация для левого блока
    function checkValidLeftBlock() {
        var clientInfoActiveCount = 0;
        var additionalFieldsActiveCount = 0;

        clientInfoActiveCount = Object.values(blockFields.left.clientInfo.fields).filter(field => {
            return field.active === true;
        }).length;

        Object.values(blockFields.left.additionalFields).forEach(section => {
            additionalFieldsActiveCount += Object.values(section.fields).filter(field => {
                return field.active === true;
            }).length;
        });

        return clientInfoActiveCount + additionalFieldsActiveCount >= 2;
    }

    //валидация для левого блока
    function checkValidRightBlock() {
        var clientInfoActiveCount = 0;
        var additionalFieldsActiveCount = 0;

        clientInfoActiveCount = Object.values(blockFields.right.clientInfo.fields).filter(field => {
            return field.active === true;
        }).length;

        Object.values(blockFields.right.additionalFields).forEach(section => {
            additionalFieldsActiveCount += Object.values(section.fields).filter(field => {
                return field.active === true;
            }).length;
        });

        return clientInfoActiveCount + additionalFieldsActiveCount <= 4;
    }

    function checkValidBlocks() {
        return checkValidLeftBlock() && checkValidRightBlock();
    }

    function cancelForFields() {
        setDefaultFields();
    }

    function confirmForFields() {
        blockFields[activeTypeBlock].sourceClientInfo = JSON.parse(JSON.stringify(blockFields[activeTypeBlock].clientInfo));
        blockFields[activeTypeBlock].sourceAdditionalFields = JSON.parse(JSON.stringify(blockFields[activeTypeBlock].additionalFields));
        setLabelsFieldsBlock();
        activeTypeBlock = 'left';
    }


    window.setTabActive = function(event) {
        var tabNode = event.target;
        var tabNode2 = tabNode.nextSibling || tabNode.previousSibling;

        tabNode.classList.add('active');
        tabNode2.classList.remove('active');

        activeTypeBlock = event.target.id === 'left_tab' ? 'left' : 'right';

        refresh();
    }

    //-- РАБОТА С МОДАЛЬНЫМ ОКНОМ ДОП ПОЛЕЙ

    // РАБОТА С МОДАЛЬНЫМ ОКНОМ ЭТАПОВ
    var blockSteps = <?echo json_encode($filterSteps)?>;
    var sourceBlockSteps = <?echo json_encode($filterSteps)?>;
    var isStepsBlockChooseAll = false;

    var stepOptions = {
        title: 'Воронка',
        handleCancel: cancelForSteps,
        handleConfirm: confirmForSteps,
        htmlContent: setModalContentForSteps(),
        isValid: checkValidForSteps(),
    };

    var stepsModalWindow = new ModalWindow(stepOptions);

    window.showModalSteps = function() {
        refreshModalSteps();
        stepsModalWindow.show();
    };

    function cancelForSteps() {
        setDefaultSteps();
    }

    function confirmForSteps() {
        sourceBlockSteps = JSON.parse(JSON.stringify(blockSteps));

        setLabelsStepsBlock();
    }

    function setDefaultSteps() {
        blockSteps = JSON.parse(JSON.stringify(sourceBlockSteps));
    }

    function refreshModalSteps() {
        stepsModalWindow.htmlContent = setModalContentForSteps();
        stepsModalWindow.isValid = checkValidForSteps();
        stepsModalWindow.errorMessage =  stepsModalWindow.isValid ? '' : 'Не менее 1-го этапа или воронки';
        stepsModalWindow.updateHtmlContent();
    }

    function getHtmlBlocksForSteps() {
        // вывод секций доп полей в мод. окне
        const addStepsStr = Object.keys(blockSteps).map((stepId) => {
            let item = blockSteps[stepId];
            let checkActiveOptionsCount = 0;

            // чекаем логику чекеда чекбокса секций
            if (stepId != '0') {
                Object.values(item.options).map(option => {
                    if (option.active) {
                        checkActiveOptionsCount++;
                    }
                });

                item.active = checkActiveOptionsCount === item.optionsCount;
            }

            return (
                '<div class="filters-fields-block">' +
                '<div class="title-block-fields">' +
                '<strong for="step_' + stepId + '" class="form-control_1 margin-right-5">' + item.name + '</strong>' +
                '<input type="checkbox" ' + (item.active ? "checked " : "") + ' id="step_' + stepId + '" class="form-control_1 margin-right-5" onclick="setCheckboxStep(event,' + stepId + ')">' +
                '</div>' +
                '<div class="filters-checkbox-block">' +
                Object.keys(item.options).map(optionId => {
                    let option = item.options[optionId];
                    return (
                        '<div class="fields-block">' +
                        '<input type="checkbox" id="'+ option.name + option.id + '" class="form-control_1 margin-right-5" onclick="setSelectedOption(event,' + stepId + ', ' + optionId + ')"' + (option.active ? "checked " : "") + '>' +
                        '<div class="block-color" style="background-color:' + option.color + '"> </div>' +
                        '<label class="form-control_1 margin-right-5" for="'+ option.name + option.id +'">' + option.name + '</label>' +
                        '</div>'
                    );
                }).join(' ') +
                '</div>' +
                '</div>'
            );
        }).join(' ');

        checkAllSelectedSteps();

        // верстка самого контента модального окна
        return(
            "<div class='modal-choose-all'>" +
            "<label style='margin-top:5px;'>Выберите воронку контактов:</label>" +
            "<div class='choose_all_block'>" +
            '<input class="form-control_1 margin-right-5" type="checkbox" id="step_choose_all" onclick="setCheckboxAllSelectedSteps(event)"' + (isStepsBlockChooseAll ? "checked " : "") + '>' +
            "<label class='pointer' for='step_choose_all'>Выбрать всё</label>" +
            "</div>" +
            "</div>" +

            "<div class=''>" +
            addStepsStr +
            "</div>"
        );
    }

    function setModalContentForSteps() {
        return (
            "<div class='filters-modal-content'>" +
            getHtmlBlocksForSteps() +
            "</div>"
        );
    }

    // проверка выбраны ли все поля в активном блоке. Меняем значение этого чекбокса
    function checkAllSelectedSteps() {
        var blockStepsList = Object.values(blockSteps);
        var isChooseStep = false;
        var isChooseAllSteps = true;


        for (var i = 0; i < blockStepsList.length; i++) {
            isChooseStep = !Object.values(blockStepsList[i].options).find(option => {
                return option.active == false;
            });

            if (!isChooseStep) {
                isChooseAllSteps = false;
                break;
            }
        }

        isStepsBlockChooseAll = isChooseAllSteps && blockSteps[0].active;
    }

    // устанавливаем чекбокс для всех Этапов
    window.setCheckboxAllSelectedSteps = function(event) {
        isStepsBlockChooseAll = event.target.checked;
        blockSteps[0].active = isStepsBlockChooseAll;

        Object.values(blockSteps).forEach(step => {
            Object.values(step.options).forEach(option => {
                option.active = isStepsBlockChooseAll;
            });
        });

        refreshModalSteps();
    };

    // устанавливаем чекбокс этапа
    window.setCheckboxStep = function(event, stepId) {
        let stepOptions = blockSteps[stepId];

        if (stepOptions) {
            stepOptions.active = event.target.checked;

            Object.keys(stepOptions.options).map(optionId => {
                stepOptions.options[optionId].active = stepOptions.active;
            });

            refreshModalSteps();
        }
    };

    // устанавливаем чекбокс для опции
    window.setSelectedOption = function(event, stepId, optionId) {
        let option = blockSteps[stepId].options[optionId];
        option.active = event.target.checked;

        refreshModalSteps();
    };

    function checkValidForSteps() {
        let optionActiveCount = 0;

        Object.values(blockSteps).forEach((step) => {
            optionActiveCount += Object.values(step.options).filter(option => option.active).length;
        });

        return optionActiveCount || !optionActiveCount && blockSteps[0].active;
    }

    //-- РАБОТА С МОДАЛЬНЫМ ОКНОМ ЭТАПОВ

    //РАБОТА С МОДАЛЬНЫМ ОКНОМ ОТВЕТСТВЕННЫХ
    var blockUsers = <?echo json_encode($filterUsers)?>;
    var sourceBlockUsers = <?echo json_encode($filterUsers)?>;
    var isUsersBlockChooseAll = false;
    var roleNames = {
        'admin':
            'Директор',
        'manager':
            'Менеджер',
        'director':
            'Руководитель',
    };

    var userOptions = {
        title: 'Ответственный',
        handleCancel: cancelForUsers,
        handleConfirm: confirmForUsers,
        htmlContent: setModalContentForUsers(),
        isValid: checkValidForUsers(),
    };

    var usersModalWindow = new ModalWindow(userOptions);

    window.showModalUsers = function() {
        refreshModalUsers();
        usersModalWindow.show();
    };

    function cancelForUsers() {
        setDefaultUsers();
    }

    function confirmForUsers() {
        sourceBlockUsers = JSON.parse(JSON.stringify(blockUsers));
        setLabelsUsersBlock();
    }

    function setDefaultUsers() {
        blockUsers = JSON.parse(JSON.stringify(sourceBlockUsers));
    }

    function refreshModalUsers() {
        usersModalWindow.htmlContent = setModalContentForUsers();
        usersModalWindow.isValid = checkValidForUsers();
        usersModalWindow.errorMessage =  usersModalWindow.isValid ? '' : 'Не менее 1-го ответственного';
        usersModalWindow.updateHtmlContent();
    }

    function getHtmlBlocksForUsers() {
        const addUsersStr = Object.keys(blockUsers).map((roleName) => {
            let item = blockUsers[roleName];
            let checkActiveUsersCount = 0;

            // чекаем логику чекеда чекбокса блоков ролей
            Object.values(item.users).map(user => {
                if (user.active) {
                    checkActiveUsersCount++;
                }
            });

            item.active = checkActiveUsersCount === item.usersCount;

            return (
                '<div class="filters-fields-block">' +
                '<div class="title-block-fields">' +
                '<strong for="user_' + roleName + '" class="form-control_1 margin-right-5">' + roleNames[roleName] + '</strong>' +
                '<input type="checkbox" ' + (item.active ? "checked " : "") + ' id="user_' + roleName + '" class="form-control_1 margin-right-5" onclick="setCheckboxUsers(event, \'' + `${roleName}` + '\')">' +
                '</div>' +
                '<div class="filters-checkbox-block">' +
                Object.keys(item.users).map(userId => {
                    let user = item.users[userId];
                    return (
                        '<div class="fields-block">' +
                        '<input type="checkbox" id="'+ user.name + user.id + '" class="form-control_1 margin-right-5" onclick="setSelectedUser(event, \'' + `${roleName}` + '\',' + userId + ')"' + (user.active ? "checked " : "") + '>' +
                        '<img class="miniAvatar" src="' + user.avatar + '"/>' +
                        '<label for="'+ user.name + user.id + '" class="text_blue pointer">' + user.name + '</label>' +
                        '</div>'
                    );
                }).join(' ') +
                '</div>' +
                '</div>'
            );
        }).join(' ');

        checkAllSelectedUsers();

        // верстка самого контента модального окна
        return(
            "<div class='modal-choose-all'>" +
            "<label style='margin-top:5px;'>Выберите ответственного:</label>" +
            "<div class='choose_all_block'>" +
            '<input class="form-control_1 margin-right-5" type="checkbox" id="user_choose_all" onclick="setCheckboxAllSelectedUsers(event)"' + (isUsersBlockChooseAll ? "checked " : "") + '>' +
            "<label class='pointer' for='user_choose_all'>Выбрать всё</label>" +
            "</div>" +
            "</div>" +

            "<div class=''>" +
            addUsersStr +
            "</div>"
        );
    }

    function setModalContentForUsers() {
        return (
            "<div class='filters-modal-content'>" +
            getHtmlBlocksForUsers() +
            "</div>"
        );
    }

    // проверка выбраны ли все поля в активном блоке. Меняем значение этого чекбокса
    function checkAllSelectedUsers() {
        var blockUsersList = Object.values(blockUsers);
        var isChooseRole = false;
        var isChooseAllUsers = true;

        for (var i = 0; i < blockUsersList.length; i++) {
            isChooseRole = !Object.values(blockUsersList[i].users).find(user => {
                return user.active == false;
            });

            if (!isChooseRole) {
                isChooseAllUsers = false;
                break;
            }
        }

        isUsersBlockChooseAll = isChooseAllUsers;
    }

    // устанавливаем чекбокс для всех Этапов
    window.setCheckboxAllSelectedUsers = function(event) {
        isUsersBlockChooseAll = event.target.checked;

        Object.values(blockUsers).forEach(role => {
            Object.values(role.users).forEach(user => {
                user.active = isUsersBlockChooseAll;
            });
        });

        refreshModalUsers();
    };

    // устанавливаем чекбокс этапа
    window.setCheckboxUsers = function(event, roleName) {
        let role = blockUsers[roleName];

        if (role) {
            role.active = event.target.checked;

            Object.keys(role.users).map(userId => {
                role.users[userId].active = role.active;
            });

            refreshModalUsers();
        }
    };

    // устанавливаем чекбокс для опции
    window.setSelectedUser = function(event, roleName, userId) {
        let user = blockUsers[roleName].users[userId];
        user.active = event.target.checked;

        refreshModalUsers();
    };

    function checkValidForUsers() {
        let optionActiveCount = 0;

        Object.values(blockUsers).forEach((role) => {
            optionActiveCount += Object.values(role.users).filter(user => user.active).length;
        });

        return optionActiveCount > 0;
    }

    //-- РАБОТА С МОДАЛЬНЫМ ОКНОМ ОТВЕТСТВЕННЫХ

    // РАБОТА СО СТРАНИЦЕЙ
    function getSelectedFields(typeBlock) {
        var fields = [];

        Object.values(blockFields[typeBlock].clientInfo.fields).forEach(field => {
            if (field.active) {
                fields.push(field.name);
            }
        });

        Object.values(blockFields[typeBlock].additionalFields).forEach(section => {
            Object.values(section.fields).forEach(field => {
                if (field.active) {
                    fields.push(field.name);
                }
            });
        });

        return fields;
    }

    function setLabelsFieldsBlock() {
        var leftBlockFieldsNode = document.getElementById('leftBlockFields');
        var rightBlockFieldsNode = document.getElementById('rightBlockFields');


        leftBlockFieldsNode.innerHTML = getSelectedFields('left').join(', ');
        rightBlockFieldsNode.innerHTML = getSelectedFields('right').join(', ');
    }

    function setLabelsStepsBlock() {
        var filtersStepsBlock = document.getElementById('filtersStepsBlock');
        var str = '';
        var optionsActiveList = [];

        Object.values(blockSteps).forEach((step, index) => {
            optionsActiveList = [];
            Object.values(step.options).forEach(option => {
                if (option.active) {
                    optionsActiveList.push(option.name);
                }
            });

            if (optionsActiveList.length || index === 0 && step.active) {
                str += '<div class="filters_fields_block"> <span>' + step.name + '</span> <div class="fields_block_list">' + optionsActiveList.join(', ') + '</div> </div>';
            }
        });

        filtersStepsBlock.innerHTML = str;
    }

    function setLabelsUsersBlock() {
        var filtersUsersBlock = document.getElementById('filtersUsersBlock');
        var str = '';
        var optionsActiveList = [];

        Object.keys(blockUsers).forEach(roleName => {
            optionsActiveList = [];
            let role = blockUsers[roleName];
            let usersActive = Object.values(role.users).filter(user => user.active) || [];

            if (usersActive.length) {
                str += '<div class="filters_fields_block"> <span>' + roleNames[roleName] + '</span> <div class="fields_block_users">';
                usersActive.forEach(user => {
                    str += '<div class="responsible-block"> <img class="miniAvatar" src="' + user.avatar + '"/> <div class="text_blue">' + user.name + '</div> </div>';
                });
                str += '</div></div>';
            }
        });

        filtersUsersBlock.innerHTML = str;
    }

    function setLabelsForPage() {
        setLabelsFieldsBlock();
        setLabelsStepsBlock();
        setLabelsUsersBlock();
    }

    setLabelsForPage();
    //-- РАБОТА СО СТРАНИЦЕЙ

    // МЕТКИ НАЧАЛО

    // устанавливаем выбранные ранее метки, на случай если валидация в методе не прошла
    var listLabels = $(".block-labelsInProfile .custom-label") || [],
        listOption = [];

    var blockLabelsList = <?echo json_encode($filterLabels)?>;

    window.changeLabel = function(labelId) {
        var elem = $('#blockOper' + labelId),
            divColor = $('#labelColor' + labelId)[0].outerHTML,
            spanText = $('#labelText' + labelId)[0].outerHTML;

        if ($('#checkbox' + labelId).is(':checked')) {
            $('#checkbox' + labelId).prop('checked', false);
            elem.removeClass('added');
            elem.addClass('deleted');
            $('#blockElem' + labelId).remove();
            blockLabelsList[labelId].active = false;
        } else {
            $('#checkbox' + labelId).prop('checked', true);
            elem.removeClass('deleted');
            elem.addClass('added');
            var labelDIv = '<div id="blockElem' + labelId + '" class="custom-label" style="background-color: ' + blockLabelsList[labelId].color + '; color: ' + blockLabelsList[labelId].color_text + '; ">' + blockLabelsList[labelId].name + '</div>';
            var blockShowLabels = $('.block-labelsInProfile');
            blockShowLabels.append(labelDIv);
            blockLabelsList[labelId].active = true;
        }

        if (document.querySelector(".block-labelsInProfile .custom-label")) {
            $('#selAllLabels').remove();
        } else {
            $('.block-labelsInProfile').append('<p id="selAllLabels">Все метки</p>');
        }
    };

    $("#editLabels").click(function (e) {
        var listLabels = $(".customDropDownListLabels");
        if (listLabels.hasClass('hide-important')) {
            listLabels.removeClass('hide-important');
        } else {
            listLabels.addClass('hide-important');
        }
    });

    jQuery(function ($) {
        $(document).mouseup(function (e) { // событие клика по веб-документу
            var div = $(".customDropDownListLabels"); // тут указываем ID элемента
            if (!div.is(e.target) && div.has(e.target).length === 0 && !$("#editLabels").is(e.target)) {//&& div.has(e.target).length === 0) { // и не по его дочерним элементам
                div.addClass('hide-important'); // скрываем его
            }

            if (!$(".color-customDropDawnList").is(e.target)) {
                $(".color-customDropDawnList").hide();
            }
        });
    });

    // МЕТКИ КОНЕЦ

    // Валидация правого блока фильтра
    var isValidFilter = false;
    var filterSaveBtnNode = document.getElementById('filterSaveBtn');

    var nameFilterNode = document.getElementById('nameFilter');
    nameFilterNode.addEventListener('input', checkValidFilter);

    var pageSizeNode = document.getElementById('pageSize');
    pageSizeNode.addEventListener('input', checkValidIsOnlyNumbers);
    pageSizeNode.addEventListener('input', checkValidFilter);

    checkValidFilter(false);

    function nameFilterValidation() {
        return nameFilterNode.value.trim() !== '';
    }

    function pageSizeValidation() {
        return pageSizeNode.value.trim() !== '' && !isNaN(pageSizeNode.value) && +pageSizeNode.value >= 5 && +pageSizeNode.value <= 300;
    }

    function checkValidIsOnlyNumbers(event) {
        event.currentTarget.value = event.currentTarget.value.replace(/[^\d]/g, '');
    }

    function checkValidFilter(isShowColorError = true) {
        let isNameFilterValid = nameFilterValidation();
        let isPageSizeValid = pageSizeValidation();
        let pageSizeSpan = document.getElementById('pageSizeError');
        let nameFilterSpan = document.getElementById('nameFilterError');

        if (isShowColorError) {
            if (isPageSizeValid) {
                pageSizeNode.classList.remove('input-error');
                pageSizeSpan.classList.add('hide-important');
            } else {
                pageSizeNode.classList.add('input-error');
                pageSizeSpan.classList.remove('hide-important');
            }

            if (isNameFilterValid) {
                nameFilterNode.classList.remove('input-error');
                nameFilterSpan.classList.add('hide-important');
            } else {
                nameFilterNode.classList.add('input-error');
                nameFilterSpan.classList.remove('hide-important');
            }
        }

        isValidFilter = isNameFilterValid && isPageSizeValid;
        filterSaveBtnNode.disabled = !isValidFilter;
    }

    //API
    const notificationBar = new NotificationBar({
        type: 'error',
        title: '',
        description: '',
    });

    var filterId = <?echo $modelClientFilter->id?>;
    var preloaderNode = document.getElementById('preloader');
    var deleteBlockNode = document.getElementById('deleteBlock');

    window.saveFilter = function() {

        let leftBlockAdditionalFieldIds = [];
        let rightBlockAdditionalFieldIds = [];

        Object.values(blockFields.left.additionalFields).forEach(section => {
            Object.values(section.fields).forEach(field => {
                if (field.active) {
                    leftBlockAdditionalFieldIds.push(field.id);
                }
            });
        });

        Object.values(blockFields.right.additionalFields).forEach(section => {
            Object.values(section.fields).forEach(field => {
                if (field.active) {
                    rightBlockAdditionalFieldIds.push(field.id);
                }
            });
        });

        let leftClientInfo = {};
        let rightClientInfo = {};

        Object.keys(blockFields.left.clientInfo.fields).forEach(key => {
            leftClientInfo[key] = +blockFields.left.clientInfo.fields[key].active;
        });

        Object.keys(blockFields.right.clientInfo.fields).forEach(key => {
            rightClientInfo[key] = +blockFields.right.clientInfo.fields[key].active;
        });

        let optionIds = [];

        Object.values(blockSteps).forEach((step) => {
            if (step.name === 'Нет воронки' && step.active) {
                optionIds.push(0);
            }

            Object.values(step.options).forEach(option => {
                if (option.active) {
                    optionIds.push(option.id);
                }
            });
        });

        let userIds = [];

        Object.values(blockUsers).forEach((role) => {
            Object.values(role.users).forEach(user => {
                if (user.active) {
                    userIds.push(user.id);
                }
            });
        });

        let labelIds = [];

        Object.values(blockLabelsList).forEach(label => {
            if (label.active) {
                labelIds.push(label.id);
            }
        });

        console.log(document.getElementById('classNameId').value);

        let data = {
            clientFilters: {
                id: filterId,
                name: nameFilterNode.value.trim(),
                page_size: +pageSizeNode.value.trim(),
                who_visible: document.getElementById('responsible').value,
                author: <? echo Yii::app()->user->id?>,
                is_files: +document.getElementById('isFiles').checked,
                is_default: +document.getElementById('isDefault').checked,
                class_name: document.getElementById('classNameId').value,
            },
            clientFiltersBlock: {
                left: {
                    clientInfo: leftClientInfo,
                    additionalFields: leftBlockAdditionalFieldIds.length ? leftBlockAdditionalFieldIds : null,
                },
                right: {
                    clientInfo: rightClientInfo,
                    additionalFields: rightBlockAdditionalFieldIds.length ? rightBlockAdditionalFieldIds : null,
                },
            },
            clientFiltersStepOptions: optionIds,
            clientFiltersResponsibles: userIds,
            clientFiltersLabels: labelIds.length ? labelIds : null,
        };
        
        filterSaveBtnNode.classList.add('hide-important');
        deleteBlockNode.classList.add('hide-important');
        preloaderNode.classList.remove('hide-important');

        $.ajax({
            url: '/page/editFilterForClients',
            method: 'post',
            dataType: 'html',
            data: data,
            success: function(data) {
                let response = JSON.parse(data);

                if (response.status === 'success') {
                    document.location.replace('/page/clients_filters_edit?filterId='+ response.values.id + '&isEditFilter=true');
                } else {
                    let keysError = Object.keys(response.errors);
                    notificationBar.type = response.status;
                    notificationBar.title = 'Ошибка';
                    notificationBar.description = response.errors[keysError[0]][0];
                    notificationBar.show();
                }

                filterSaveBtnNode.classList.remove('hide-important');
                deleteBlockNode.classList.remove('hide-important');
                preloaderNode.classList.add('hide-important');
            }
        });
    };

    window.deleteFilter = function() {

        filterSaveBtnNode.classList.add('hide-important');
        deleteBlockNode.classList.add('hide-important');
        preloaderNode.classList.remove('hide-important');

        let data = {
            filterId: filterId,
        };

        $.ajax({
            url: '/page/deleteFilterForClients',
            method: 'post',
            dataType: 'html',
            data: data,
            success: function(data) {
                let response = JSON.parse(data);

                if (response.status === 'success') {
                    document.location.replace('/page/clients_page?isDeleteFilter=true');
                } else {
                    let keysError = Object.keys(response.errors);
                    notificationBar.type = response.status;
                    notificationBar.title = 'Ошибка';
                    notificationBar.description = response.errors[keysError[0]][0];
                    notificationBar.show();
                }
                filterSaveBtnNode.classList.remove('hide-important');
                deleteBlockNode.classList.remove('hide-important');
                preloaderNode.classList.add('hide-important');
            }
        });
    };

    window.showDropDawnColor = function(event) {
        let gh = event.target.closest('#colorSelect').children[1];
        gh.style.display = 'block';
    };

    // меняем цвет
    window.changeColor = function(event, color, className) {
        let colorBlock = event.target.closest('#colorSelect').querySelector('.color-block'),
            inputColorBlock = colorBlock.querySelector('input');
        colorBlock.style.backgroundColor = color;
        inputColorBlock.value = className;
    };

    jQuery(function ($) {
        $(document).mouseup(function (e) { // событие клика по веб-документу
            if (!$(".color-customDropDawnList-01").is(e.target)) {
                $(".color-customDropDawnList-01").hide();
            }
        });
    });

    const footerNode = document.getElementsByClassName('footer')[0];
    footerNode.classList.add('footer-client-filter');
</script>

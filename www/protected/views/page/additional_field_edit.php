<?php $this->pageTitle = $addField->name . ' | Анкета контакта'; ?>
<?php $correct_path = 'http://' . $_SERVER["HTTP_HOST"]; ?>

<div class="clients-hat">
    <div class="settings-name">
        <?php echo CHtml::link('Настройки', array('page/settings_additional_field')); ?>
        <img src="/img/right-arrow-button.svg">
        <?php echo CHtml::link('Анкета контакта', array('page/settings_additional_field')); ?>
        <img src="/img/right-arrow-button.svg">
        #<?php echo $addField->id ?>: <?php echo $addField->name ?>
    </div>
    <div class="goback-link pull-right">
        <input class="btn_close" type="button" onclick="history.back();" value="❮  Назад "/>
    </div>
</div>

<main class="content full2" role="main">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'new-client',
        'htmlOptions' => [
            'class' => 'page-form'
        ]
    ));
    $count = 1;
    ?>

    <? if ($isShowBlockSave) { ?>
        <script type="module">
            import {NotificationBar} from '/js/notificationBar.js';

            const notificationBar = new NotificationBar({
                type: 'success',
                title: 'Сохранено',
                description: 'Поле успешно изменено в анкете'
            });
            notificationBar.show();
        </script>
    <? } ?>

    <div class="edit_user_view">
        <div class="content-01">
            <div class="title_name_1">Настройка поля</div>
            <div class="centre_settings">
                <table class="main_table_12">

                    <tr>
                        <td class="an_001" width="132">Поле:<span class="star">*</span></td>
                        <td><?php echo $form->textField($addField, 'name', array('class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Наименование')); ?>
                            <?php echo $form->error($addField, 'name', array('class' => 'form-error')); ?></td>
                    </tr>

                    <? if (!$addField->unique) { ?>
                        <tr>
                            <td class="an_001" width="132">Раздел:</td>
                            <td style="white-space: normal;"><?php echo $form->dropDownList($addField, 'section_id', $sectionsArr, array('class' => 'styled', 'data-placeholder' => '')); ?>
                            </td>
                        </tr>
                    <? } else { ?>
                        <tr>
                            <td class="an_001" width="132">Тип поля</td>
                            <td style="white-space: normal;"><?php echo 'Текст. Уникальное поле'; ?>
                            </td>
                        </tr>
                    <? }
                    if ($addField->type != 'select' && !$addField->unique) { ?>
                        <tr>
                            <td class="an_001" width="132">По умолчанию:</td>
                            <td><?
                                switch ($addField->type) {
                                    case 'int':
                                    case 'varchar':
                                        echo $form->textField($addField, 'default_value', array('class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => ''));
                                        echo $form->error($addField, 'default_value', array('class' => 'form-error'));
                                        break;
                                    case 'checkbox':
                                        echo $form->checkBox($addField, 'default_value', array('class' => 'form-control_1 checkBox'));
                                        echo $form->error($addField, 'default_value', array('class' => 'form-error'));
                                        break;
                                    case 'date':
                                        echo $this->widget('ext.CJuiDateTimePicker.CJuiDateTimePicker', array(
                                            'name' => "AdditionalFields[default_value]",
                                            'model' => $addField,
                                            'attribute' => "default_value",
                                            'language' => 'ru',
                                            'htmlOptions' => array(
                                                'class' => 'form-control editable',
                                                'value' => isset($addField['default_value']) && is_numeric($addField['default_value']) ? date('d.m.Y', $addField['default_value']) : '',
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

                                        break;
                                }
                                ?>
                            </td>

                        </tr>
                    <? } ?>
                    <?php
                    if ($addField->type == 'varchar') {
                        ?>
                        <tr>
                            <td class="an_001" width="132">Размер:</td>
                            <td style="white-space: normal;"><?php echo $form->dropDownList($addField, 'size', AdditionalFields::model()->getSizeText(), array('class' => 'styled', 'data-placeholder' => '')); ?>
                            </td>
                        </tr>
                        <?php
                    }

                    if ($addField->type != 'checkbox' && $addField->type != 'select' && $addField->table_name != 'fieldFio') {
                        ?>
                        <tr>
                            <td class="an_001" width="132">Обязательное:</td>
                            <td><?php echo $form->checkBox($addField, 'required', array('class' => 'form-control_1 checkBox')); ?>
                                <?php echo $form->error($addField, 'required', array('class' => 'form-error')); ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td class="an_001" width="132">Порядок:<span class="star">*</span></td>
                        <td><?php echo $form->textField($addField, 'weight', array('class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => '')); ?>
                            <?php echo $form->error($addField, 'weight', array('class' => 'form-error')); ?></td>

                    </tr>

                    <tr>
                        <td class="an_001 tip-label" width="132">Подсказка:</td>
                        <td><?php echo $form->textArea($addField, 'tip', array('class' => 'form-control textSize100 tip-input', 'autocomplete' => 'off', 'placeholder' => '')); ?>
                            <?php echo $form->error($addField, 'tip', array('class' => 'form-error')); ?></td>
                    </tr>
                </table>
            </div>
            <div class="centre_setting_3">
                <? if ($addField->type == 'select') { ?>
                    <div class="block-info editSelect" id="selectOptions">

                        <div class="block-row addSelectOption">
                            <div class="row-label">Значение в списке:<span class="star">*</span></div>
                            <div class="row-label label-weight">№:<span class="star">*</span></div>
                        </div>
                        <div class="block-options addSelectOption">
                            <? $count = 1;
                            foreach ($selectOptions as $value) {
                                $countClient = 0;
                                if (isset($value['id'])) {
                                    $countClient = count(AdditionalFieldsValues::model()->findAll($addField->table_name . '=' . $value['id']));
                                }
                                ?>
                                <div class="block-row">

                                    <div class="row-input input-option hide" id="inputId">
                                        <? echo CHtml::textField('AdditionalFields[Select][' . $count . '][id]', $value['id']) ?>
                                    </div>
                                    <div class="row-input input-option">
                                        <? echo CHtml::textField('AdditionalFields[Select][' . $count . '][optionName]', $value['optionName'], ['maxlength' => 80, 'placeholder' => 'Вариант ' . $count, 'autocomplete' => 'off',]) ?>
                                    </div>

                                    <div class="row-input input-weight">
                                        <? echo CHtml::textField('AdditionalFields[Select][' . $count . '][optionWeight]', $value['optionWeight'], ['maxlength' => 2, 'autocomplete' => 'off', 'class' => 'optionWeight', 'id' => "inputWeight", 'pattern' => "^[ 0-9]+$"]) ?>
                                    </div>
                                    <div class="row-input input-radioButton">
                                        <? echo CHtml::radioButton('AdditionalFields[Select][' . $count . '][default]',
                                            isset($value['default']), ['id' => 'radio' . $count, 'onClick' => 'changeRadioActive(' . $count . ')']) ?>
                                    </div>

                                    <? if ($countClient < 1) { ?>
                                        <div class="row-input">
                                            <img class="delDocument_set" onclick="deleteOption(event)"
                                                 src="/img/cancel_newdoc.svg" alt="">
                                        </div>
                                    <? } else { ?>
                                        <div class="row-input">
                                            <img class="delDocument_set" id="delete<? echo $value['id'] ?>"
                                                 onclick="showBlockDelete(event, <? echo $value['id'] ?>)"
                                                 src="/img/cancel_newdoc.svg" alt="">
                                        </div>
                                    <? } ?>
                                </div>

                                <div class="delete-option hide" id="blockDelete<? echo $value['id'] ?>">
                                    Назначено для контактов (<? echo $countClient ?>). После удаления, эти контакты
                                    примут значение по умолчанию. Затем нажмите "Сохранить"
                                    <a onclick="deleteOption(event, <? echo $value['id'] ?>)" style="cursor: pointer;">
                                        Удалить</a>
                                </div>
                                <? $count++;
                            } ?>
                        </div>
                        <div class="block-row addSelectOption buttonAdd">
                            <a class="add" id="addOption" href="#">Добавить</a>
                        </div>

                        <? if ($addField->getError('selectError')) { ?>
                            <div class="block-row addSelectOption">
                                <div class="custom-error"><? echo $addField->getError('selectError') ?></div>
                            </div>
                        <? } ?>

                    </div>
                <? } ?>

            </div>
            <div class="save_button">
                <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn', 'id' => 'save')); ?>
                <div id="preloader"></div>
            </div>

            <? if ($addField->table_name != 'fieldEmail' && $addField->table_name != 'fieldTelephone'
                && $addField->table_name != 'fieldFio' && ($user->roles[0]->name == 'admin' || $user->userRights[0]->delete_field)) { ?>
                <div class="function-delete delete_centre">
                    <a class="delete" href="#">Удалить поле</a>
                </div>
                <div class="function-delete-confirm delete_centre" style="display: none;">
                    <ul class="horizontal_3">
                        <li class="big">Подтвердите удаление:
                        </li>
                        <li style="padding-top: 9px;"> <?php echo CHtml::button("Удалить", array(
                                'onClick' => 'window.location.href = "' . Yii::app()->createUrl("page/Additional_field_delete",
                                        array("id" => $addField->id)) . '"',
                                'class' => 'btn',
                            )); ?>
                        </li>
                        <li><a href="#" class="cancel" style="margin-left: 10px;">Отмена</a></li>
                    </ul>
                </div>
            <? } ?>

            <?php $this->endWidget(); ?>


        </div>
    </div>

    <div class="right-sidebar">
            <div class="title_name_2">Справка
                <div class="more"><img src="/img/external-link-symbol.svg"><a href="https://inclient.ru/category/help-crm/"
                                                                              target="_blank" style="color: #707070;">Подробнее</a>
                </div>
            </div>
            <div class="popup__form_actions">
                <ul>
                    <li>
                        <strong>О полях</strong>
                        <br>
                        <br>
                        Поля нужны, чтобы собрать как можно больше информации о контакте. Поля заполняются в анкете
                        контакта и входят в состав разделов. Можно перенести поле в другой раздел. Изменить тип текущего
                        поля невозможно
                    </li>
                    <details class="help_0">
                        <summary class="help_1">Как сделать поле обязательным?</summary>
                        <p>Включите параметр "Обязательное". Если такое поле не заполнить, тогда сохранить контакт не
                            получится</p>
                    </details>
                    <details class="help_0">
                        <summary class="help_1">Как сделать автозаполнение поля?</summary>
                        <p>Заполните параметр "По умолчанию". При создании контакта поле будет уже заполнено. Для
                            текущих контактов это поле не изменится</p>
                    </details>
                    <details class="help_0">
                        <summary class="help_1">Что будет если удалить поле?</summary>
                        <p>Поле удалится безвозвратно</p>
                    </details>
            </div>
        </div>
</main>

<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
    $("#new-client").submit(function () {
        $("#preloader").addClass('preloader');
        $("#save_and_create").hide();
        $("#save").hide();
    });

    deleteOption = function (event, id) {
        event.preventDefault();
        var isDefaultChecked = '';
        if (id) {
            var activeRadioBtn = $("input:radio:checked").closest('.block-row').find('#delete' + id);
            isDefaultChecked = activeRadioBtn.length !== 0;
        } else {
            var blockOption = event.path[2];
            isDefaultChecked = blockOption.querySelectorAll('input[type="radio"]')[0].checked;
        }
        if (!isDefaultChecked) {
            if (id) {
                linkDelete = $('#delete' + id);
                linkDelete.show();
                $('#blockDelete' + id).hide();
                blockOption = linkDelete.closest('.block-row');
            }

            var listOptions = $('.block-options.addSelectOption .block-row');
            if (listOptions.length > 2) {
                blockOption.remove();
                $('#blockDelete' + id).remove();
                changeShowButtonDelete();
            } else {
                alert('Невозможно удалить значение! В селекторе должно быть не менее 2-х значений')
            }
        } else {
            alert('Значение по умолчанию в селекторе невозможно удалить!');
        }
    };

    var count = <?echo $count - 1?>;
    changeShowButtonDelete();

    function changeShowButtonDelete() {
        var listButtonDelete = $('.block-row').find('.delete');
        if (listButtonDelete.length <= 2) {
            $('.delete-option').addClass('hide');
            for (var i = 0; i <= listButtonDelete.length - 1; i++) {
                listButtonDelete[i].classList.add('hide');
            }
        } else {
            for (var i = 0; i <= listButtonDelete.length - 1; i++) {
                listButtonDelete[i].classList.remove('hide');
            }
            // затираем  ссылку "удалить" активного радиобаттон
            $("input:radio:checked").closest('.block-row').find('.delete').addClass('hide');

        }
    }

    $('#addOption').click(function () {
        event.preventDefault();
        listOptions = $('.block-options.addSelectOption .block-row');
        Weights = $('.optionWeight');
        listWeight = [];
        // массив с весами
        for (var i = 0; i < Weights.length; i++) {
            listWeight.push(+Weights[i].value);
        }

        weight = null;
        for (var i = 1; i <= listOptions.length; i++) {
            if (listWeight.indexOf(i) < 0) {
                weight = i;
                break;
            }
        }

        count++;

        weight = weight || listOptions.length + 1;
        blockOption = '<div class="block-row">\n' +
            '                                <div class="row-input input-option">\n' +
            '                                    <input type="text" autocomplete="off" name="AdditionalFields[Select][' + count + '][optionName]" class="optionName" placeholder="Вариант ' + count + '" maxlength="80">' +
            '                                </div>\n' +
            '                                <div class="row-input input-weight">\n' +
            '                                    <input type="text" autocomplete="off" name="AdditionalFields[Select][' + count + '][optionWeight]" id="inputWeight" pattern="^[ 0-9]+$" maxlength="2" class="optionWeight" value="' + weight + '">' +
            '                                </div>\n' +
            '                                <div class="row-input input-radioButton">\n' +
            '                                    <input type="radio" name="AdditionalFields[Select][' + count + '][default]" id="radio' + count + '" onclick="changeRadioActive(' + count + ')">' +
            '                                </div>\n' +
            '                                <div class="row-input">\n' +
            '                                    <img class="delDocument_set" onclick="deleteOption(event)" src="/img/cancel_newdoc.svg" alt="">\n' +
            '                                </div>\n' +
            '                            </div>';
        blockListOptions = $('.block-options.addSelectOption');
        blockListOptions.append(blockOption);
        changeShowButtonDelete();
    });

    showBlockDelete = function (event, id) {
        event.preventDefault();
        var activeRadioBtn = $("input:radio:checked").closest('.block-row').find('#delete' + id);
        if (activeRadioBtn.length === 0) {
            var listOptions = $('.block-options.addSelectOption .block-row');
            if (listOptions.length > 2) {
                $('#blockDelete' + id).removeClass('hide');
            } else {
                alert('Невозможно удалить значение! В селекторе должно быть не менее 2-х значений')
            }
            //$('#delete' + id).addClass('hide');
        } else {
            alert('Значение по умолчанию в селекторе невозможно удалить!');
        }
    };

    cancelDelete = function (event, id) {
        event.preventDefault();
        $('#delete' + id).removeClass('hide');
        $('#blockDelete' + id).addClass('hide');
    };

    changeRadioActive = function (radioId) {
        $('input:radio').prop('checked', false);
        $('#radio' + radioId).prop("checked", true);
        // затираем  ссылку "блок опций удалить" активного радиобаттон
        var id = $("input:radio:checked").closest('.block-row').find('#inputId input').val();
        $('#blockDelete' + id).addClass('hide');
        changeShowButtonDelete();
    };
</script>

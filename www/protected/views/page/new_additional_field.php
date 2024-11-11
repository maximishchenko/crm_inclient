<?php $this->pageTitle = 'Новое поле | Анкета контакта'; ?>
<?php $correct_path = 'http://' . $_SERVER["HTTP_HOST"]; ?>

<div class="clients-hat">
    <div class="settings-name">
        <?php echo CHtml::link('Настройки', array('page/settings_additional_field')); ?>
        <img src="/img/right-arrow-button.svg">
        Анкета контакта
        <img src="/img/right-arrow-button.svg">
        Новое поле
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
    ?>
    <div class="edit_user_view">
        <div class="content-01">
            <div class="title_name_1">Настройка поля</div>
            <div class="centre_settings">
                <table class="main_table_12">
                    <tr>
                        <td class="an_001" width="132">Поле:<span class="star">*</span></td>
                        <td><?php echo $form->textField($addField, 'name', array('class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Наименование')); ?>
                            <?php echo $form->error($addField, 'name', array('class' => 'form-error')); ?>
                    </tr>
                    <tr>
                        <td class="an_001" width="132">Тип поля:</td>
                        <td style="white-space: normal;"><?php echo $form->dropDownList($addField, 'type', AdditionalFields::model()->getTypeField(), array('class' => 'styled', 'autocomplete' => 'off', 'data-placeholder' => '')); ?>
                    </tr>
                    <tr id="size" style="display: none">
                        <td class="an_001" width="132">Размер:</td>
                        <td style="white-space: normal;"><?php echo $form->dropDownList($addField, 'size', AdditionalFields::model()->getSizeText(), array('class' => 'styled', 'data-placeholder' => '')); ?>
                        </td>
                    </tr>
                    <tr id="defaultString">
                        <td class="an_001" width="132">По умолчанию:</td>
                        <td><?php
                            echo $form->textField($addField, 'defaultValueType[string]', array('class' => 'form-control ' . ($addField->getError('default_value') ? 'error' : ''), 'autocomplete' => 'off', 'placeholder' => '', 'value' => $addField->default_value)); ?>
                            <?php echo $form->error($addField, 'default_value', array('class' => 'form-error')); ?></td>
                    </tr>
                    <tr id="defaultCheckBox" style="display: none">
                        <td class="an_001" width="132">По умолчанию:</td>
                        <td><?php echo $form->checkBox($addField, 'defaultValueType[checkBox]', array('class' => 'form-control_1 checkBox')); ?>
                            <?php echo $form->error($addField, 'defaultValueType[checkBox]', array('class' => 'form-error')); ?></td>
                    </tr>
                    <tr id="defaultDate" style="display: none">
                        <td class="an_001" width="132">По умолчанию:</td>
                        <td><?php
                            echo $this->widget('ext.CJuiDateTimePicker.CJuiDateTimePicker', array(
                                'name' => "AdditionalFields[defaultValueType][date]",
                                'model' => $addField,
                                'attribute' => "defaultValueType[date]",
                                'language' => 'ru',
                                'htmlOptions' => array(
                                    'class' => 'form-control editable',
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
                            ?>

                        </td>
                    </tr>
                    <tr id="defaultSelect">
                        <td class="an_001" width="132">По умолчанию:</td>
                        <td><?php echo $form->textField($addField, 'defaultValueType[select]', array('class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Нет значения')); ?>
                            <?php echo $form->error($addField, 'defaultValueType[select]', array('class' => 'form-error')); ?></td>
                    </tr>
                    <tr id="required">
                        <td class="an_001" width="132">Обязательное:</td>
                        <td><?php echo $form->checkBox($addField, 'required', array('class' => 'form-control_1 checkBox')); ?>
                            <?php echo $form->error($addField, 'required', array('class' => 'form-error')); ?></td>
                    </tr>
                    <tr>
                        <td class="an_001" width="132">Порядок:<span class="star">*</span></td>
                        <td><?php echo $form->textField($addField, 'weight', array('class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => '')); ?>
                            <?php echo $form->error($addField, 'weight', array('class' => 'form-error')); ?></td>
                    </tr>
                    <tr id="tipInput">
                        <td class="an_001 tip-label" width="132">Подсказка:</td>
                        <td><?php echo $form->textArea($addField, 'tip', array('class' => 'form-control tip-input', 'autocomplete' => 'off', 'placeholder' => '')); ?>
                            <?php echo $form->error($addField, 'tip', array('class' => 'form-error')); ?></td>
                    </tr>

                </table>
            </div>
            <div class="centre_setting_3">
                <div class="block-info" id="selectOptions">

                    <div class="block-row addSelectOption">
                        <div class="row-label">Значение в списке:<span class="star">*</span></div>
                        <div class="row-label label-weight">№:<span class="star">*</span></div>
                    </div>
                    <div class="block-options addSelectOption">
                        <? $count = 1;
                        foreach ($selectOptions as $value) { ?>
                            <div class="block-row">

                                <div class="row-input input-option">
                                    <? echo CHtml::textField('AdditionalFields[Select][' . $count . '][optionName]', $value['optionName'], ['maxlenght' => 20, 'placeholder' => 'Вариант ' . $count, 'autocomplete' => 'off',]) ?>
                                </div>
                                <div class="row-input input-weight">
                                    <? echo CHtml::textField('AdditionalFields[Select][' . $count . '][optionWeight]', $value['optionWeight'], ['maxlength' => 2, 'autocomplete' => 'off', 'class' => 'optionWeight', 'id' => "inputWeight", 'pattern' => "^[ 0-9]+$"]) ?>
                                </div>
                                <div class="row-input input-radioButton">
                                    <? echo CHtml::radioButton('AdditionalFields[Select][' . $count . '][default]',
                                        isset($value['default']), ['id' => 'radio' . $count, 'onClick' => 'changeRadioActive(' . $count . ')']) ?>
                                </div>
                                <div class="row-input">
                                    <img class="delDocument_set" onclick="deleteOption(event, <? echo $count ?>)"
                                         src="/img/cancel_newdoc.svg" alt="">
                                </div>
                            </div>
                            <? $count++;
                        } ?>
                    </div>
                    <div class="block-row addSelectOption">
                        <a class="add" id="addOption" href="#">Добавить</a>
                    </div>
                    <? if ($addField->getError('selectError')) { ?>
                        <div class="block-row addSelectOption">
                            <div class="custom-error"><? echo $addField->getError('selectError') ?></div>
                        </div>
                    <? } ?>
                </div>
            </div>
            <div class="save_button">
                <?php echo CHtml::submitButton('Создать поле', array('class' => 'btn', 'id' => 'save')); ?>
                <div id="preloader"></div>
            </div>

            <?php $this->endWidget(); ?>
        </div>
    </div>

    <div class="right-sidebar">
            <div class="title_name_2">Справка</div>
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
                        <p>Заполните параметр "По умолчанию". При создании контакта поле будет уже заполнено, в текущих
                            контактах поле не изменится</p>
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

    function showHide() {
        switch ($("#AdditionalFields_type").val()) {
            case 'int':
                $("#defaultString").show();
                $("#defaultCheckBox").hide();
                $("#defaultSelect").hide();
                $("#defaultDate").hide();
                $("#size").hide();
                $("#required").show();
                $("#selectOptions").hide();
                $("#selectOptions").hide();
                break;
            case 'varchar':
                $("#defaultString").show();
                $("#size").show();
                $("#defaultCheckBox").hide();
                $("#defaultDate").hide();
                $("#required").show();
                $("#selectOptions").hide();
                $("#defaultSelect").hide();
                break;
            case 'checkbox':
                $("#defaultString").hide();
                $("#defaultCheckBox").show();
                $("#defaultDate").hide();
                $("#size").hide();
                $("#required").hide();
                $("#selectOptions").hide();
                $("#defaultSelect").hide();
                break;
            case 'date':
                $("#defaultString").hide();
                $("#defaultCheckBox").hide();
                $("#size").hide();
                $("#defaultDate").show();
                $("#required").show();
                $("#selectOptions").hide();
                $("#defaultSelect").hide();
                break;
            case 'select':
                $("#defaultString").hide();
                $("#defaultCheckBox").hide();
                $("#size").hide();
                $("#defaultDate").hide();
                $("#required").hide();
                $("#selectOptions").show();
                $("#defaultSelect").hide();
                break;
        }
    }

    $("#AdditionalFields_type").change(function () {
        showHide()
    });

    deleteOption = function (event) {
        event.preventDefault();
        var blockOption = event.path[2];
        if (!blockOption.querySelectorAll('input[type="radio"]')[0].checked) {
            blockOption = event.path[2];
            blockListOptions = $('.block-options.addSelectOption');
            listOptions = blockListOptions.children();
            if (listOptions.length > 2) {
                blockOption.remove();
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

    $('#addOption').click(function () {
        event.preventDefault();
        blockListOptions = $('.block-options.addSelectOption');
        listOptions = blockListOptions.children();
        Weights = $('.optionWeight');
        listWeight = [];
        // массив с весами
        for (var i = 0; i < Weights.length; i++) {
            listWeight.push(+Weights[i].value);
        }

        weigth = null;
        for (var i = 1; i <= listOptions.length; i++) {
            if (listWeight.indexOf(i) < 0) {
                weigth = i;
                break;
            }
        }

        count++;

        weigth = weigth || listOptions.length + 1;
        blockOption = '<div class="block-row">\n' +
            '                                <div class="row-input input-option">\n' +
            '                                    <input type="text" autocomplete="off" name="AdditionalFields[Select][' + count + '][optionName]" class="optionName" placeholder="Вариант ' + count + '" maxlength="20">' +
            '                                </div>\n' +
            '                                <div class="row-input input-weight">\n' +
            '                                    <input type="text" autocomplete="off" name="AdditionalFields[Select][' + count + '][optionWeight]" id="inputWeight" pattern="^[ 0-9]+$" maxlength="2" class="optionWeight" value="' + weigth + '">' +
            '                                </div>\n' +
            '                                <div class="row-input input-radioButton">\n' +
            '                                    <input type="radio" name="AdditionalFields[Select][' + count + '][default]" id="radio' + count + '" onclick="changeRadioActive(' + count + ')">' +
            '                                </div>\n' +
            '                                <div class="row-input">\n' +
            '                                    <img class="delDocument_set" onclick="deleteOption(event, count)" src="/img/cancel_newdoc.svg" alt="">\n' +
            '                                </div>\n' +
            '                            </div>';
        blockListOptions.append(blockOption);
        changeShowButtonDelete();
    });

    changeRadioActive = function (radioId) {
        $('input:radio').prop('checked', false);
        $('#radio' + radioId).prop("checked", true);
        changeShowButtonDelete();
    };

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

    showHide();
</script>
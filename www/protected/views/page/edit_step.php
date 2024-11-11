<?php $this->pageTitle = $modelEditStep->name . ' | Воронки'; ?>
<?php $correct_path = 'http://' . $_SERVER["HTTP_HOST"]; ?>

<div class="clients-hat">
    <div class="settings-name">
        <?php echo CHtml::link('Настройки', array('page/settings_additional_field')); ?>
        <img src="/img/right-arrow-button.svg">
        <?php echo CHtml::link('Воронка', array('page/settings_steps')); ?>
        <img src="/img/right-arrow-button.svg">
        <? echo '#' . $modelEditStep->id . ': ' . $modelEditStep->name; ?>
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
    <!--<form class="fly-validation" id="form-new-client" action="#" method="post">-->

    <? if ($isShowBlockSave) { ?>
        <script type="module">
            import {NotificationBar} from '/js/notificationBar.js';

            const notificationBar = new NotificationBar({
                type: 'success',
                title: 'Сохранено',
                description: 'Воронка успешно изменена'
            });
            notificationBar.show();
        </script>
    <? } ?>

    <div class="edit_user_view">
        <div class="content-01">
            <div class="title_name_1">Настройка воронки</div>
            <div class="centre_settings">
                <table class="main_table_12">
                    <tr>
                        <td class="an_001" width="132">Воронка:<span class="star">*</span></td>
                        <td><?php echo $form->textField($modelEditStep, 'name', array('class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Имя воронки')); ?>
                            <?php echo $form->error($modelEditStep, 'name', array('class' => 'form-error')); ?>
                    </tr>
                    <tr id="defaultCheckBox">
                        <td class="an_001" width="132">По умолчанию:</td>
                        <td><?php echo $form->checkBox($modelEditStep, 'selected_default', array('class' => 'form-control_1 checkBox')); ?>
                            <?php echo $form->error($modelEditStep, 'selected_default', array('class' => 'form-error')); ?></td>
                    </tr>
                    <tr>
                        <td class="an_001" width="132">Порядок:<span class="star">*</span></td>
                        <td><?php echo $form->textField($modelEditStep, 'weight', array('class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => '')); ?>
                            <?php echo $form->error($modelEditStep, 'weight', array('class' => 'form-error')); ?></td>
                    </tr>

                </table>
            </div>

            <div class="centre_setting_3">
                <div class="block-info" id="selectOptions">

                    <div class="block-row addSelectOption">
                        <div class="row-label">Этапы:<span class="star">*</span></div>
                        <div class="row-label label-weight">№:<span class="star">*</span></div>
                    </div>
                    <div class="block-options addSelectOption">
                        <? $count = 1;
                        foreach ($selectOptions as $value) { ?>
                            <div class="block-row">
                                <div class="row-input color-option_input">
                                    <? echo CHtml::textField('MainSteps[Select][' . $count . '][name]', $value['name'], ['maxlength' => 100, 'placeholder' => 'Этап ' . $count, 'autocomplete' => 'off',]) ?>
                                </div>
                                <div class="row-input" id="colorSelect">
                                    <div class="jq-selectbox__select color-select"
                                         onclick="showDropDawnColor(event)">
                                        <div class="color-block"
                                             style="background-color: <? echo $value['color'] ?>">
                                            <input type="text" value="<? echo $value['color'] ?>" class="hide"
                                                   name="MainSteps[Select][<? echo $count ?>][color]">
                                            <input type="text" value="<? echo $value['id'] ?>" class="hide"
                                                   name="MainSteps[Select][<? echo $count ?>][id]">
                                        </div>
                                        <div class="jq-selectbox__trigger">
                                            <div class="jq-selectbox__trigger-arrow"></div>
                                        </div>
                                    </div>

                                    <div class="color-customDropDawnList-01 shortWidth hide">
                                        <ul>
                                            <?
                                            foreach ($listColor as $key => $color) {
                                                echo "<li value='$key' onclick='changeColor(event, " . '"' . $color . '"' . ")'><div class='block-color' style='background-color:$color;width: 15px;height: 15px;'></div></li>";
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row-input input-weight">
                                    <? echo CHtml::textField('MainSteps[Select][' . $count . '][weight]', $value['weight'], ['maxlength' => 3, 'autocomplete' => 'off', 'class' => 'optionWeight', 'id' => "inputWeight", 'pattern' => "^[ 0-9]+$"]) ?>
                                </div>
                                <div class="row-input">
                                    <img class="delDocument_set" onclick="deleteOption(event)"
                                         src="/img/cancel_newdoc.svg" alt="">
                                </div>
                            </div>
                            <? $count++;
                        } ?>
                    </div>
                    <div class="block-row addSelectOption">
                        <a class="add" id="addOption" href="#">Добавить</a>
                    </div>
                    <? if ($modelEditStep->getError('selectError')) { ?>
                        <div class="block-row addSelectOption">
                            <div class="custom-error"><? echo $modelEditStep->getError('selectError') ?></div>
                        </div>
                    <? } ?>


                </div>
            </div>

            <div class="save_button">
                <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn', 'id' => 'save')); ?>
                <div id="preloader"></div>
            </div>

            <? if ($user->roles[0]->name == 'admin' || $user->userRights[0]->delete_steps) { ?>
                <div class="function-delete delete_centre">
                    <a class="delete" href="#">Удалить воронку</a>
                </div>
                <div class="function-delete-confirm delete_centre" style="display: none;">

                    <ul class="horizontal_3">
                        <li class="big">Подтвердите удаление:</li>
                        <?
                        $labelType = $type == 'clients' ? 'в этой воронке есть контакты' : 'в воронке есть сделки';
                        foreach ($useSelectOptions as $name => $useOptionCount) {
                            echo '<li class="big">-  <strong>' . $name . '</strong> (' . $labelType . ': ' . $useOptionCount . ')</li>';
                        } ?>
                        <li style="padding-top: 9px;"> <?php echo CHtml::button("Удалить", array(
                                'onClick' => 'window.location.href = "' . Yii::app()->createUrl("page/Steps_delete",
                                        array("id" => $modelEditStep->id, 'type' => $type)) . '"',
                                'class' => 'btn',
                            )); ?>  </li>
                        <li><a href="#" class="cancel" style="margin-left: 10px;">Отмена</a>
                        </li>

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
                <p><strong>О воронках</strong></p>
                <br>
                <p><strong>Воронка в контактах</strong> — это путь в бизнес-процессе, который контакт проходит от
                    начального до завершающего этапа. Например, путь контакта может делиться на 3 этапа: 1) знакомство;
                    2) покупка; 3) повторная покупка.</p>
                <p><strong>Воронка в сделках</strong> — это воронка продаж, т.е. путь, который сделка проходит от начала
                    заинтересованности контакта до закрытия сделки с положительным или отрицательным результатом.</p>
                <details class="help_0">
                    <summary class="help_1">Отчеты по воронкам?</summary>
                    <p>Отчеты появятся в новых версиях срм. Следите за новостями на сайте <a href="https://inclient.ru"
                                                                                             target="_blank">inclient.ru</a>
                    </p>
                </details>
                <details class="help_0">
                    <summary class="help_1">Что будет, если удалить воронку?
                    </summary>
                    <p>Воронка удалится безвозвратно. Если воронка назначена для контакта или сделки, система
                        предупредит об этом при удалении
                    </p>
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

    deleteOption = function (event) {
        event.preventDefault();
        blockOption = event.path[2];
        blockListOptions = $('.block-options.addSelectOption');
        listOptions = blockListOptions.children();
        if (listOptions.length > 2) {
            blockOption.remove();
            changeShowButtonDelete();
        } else {
            alert('Невозможно удалить! В воронке должно быть не менее двух этапов')
        }
    };

    var listColor = <?echo json_encode($listColor)?>;
    var count = <?echo $count - 1?>;
    changeShowButtonDelete();

    $('#addOption').click(function () {
        event.preventDefault();
        blockListOptions = $('.block-options.addSelectOption');
        listOptions = blockListOptions.children();
        Weights = $('.optionWeight');
        selectedColors = $('.color-block');
        listWeight = [];
        let newColor = listColor[0];
        // массив с весами
        for (var i = 0; i < Weights.length; i++) {
            listWeight.push(+Weights[i].value);
        }

        // массив с выбранными цветами
        let listSelectedColors = [];
        for (var j = 0; j < selectedColors.length; j++) {
            listSelectedColors.push(selectedColors[j].children[0].value);
        }

        for (var i = 0; i < listColor.length; i++) {
            if (listSelectedColors.indexOf(listColor[i]) == -1) {
                newColor = listColor[i];
            }
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

        //цвет
        let color = '';
        for (var i = 0; i < listColor.length; i++) {
            color += '<li vlaue="' + i + '" onclick="changeColor(event,' + "'" + listColor[i] + "'" + ')"><div class="block-color" style="background-color:' + listColor[i] + '"></div></li>';
        }

        let colorSelect = "<div class=\"row-input\"  id=\"colorSelect\">\n" +
            "                                        <div class=\"jq-selectbox__select color-select\" onclick='showDropDawnColor(event)'>\n" +
            "                                            <div class=\"color-block\" style='background-color:" + newColor + "'> <input type=\"text\" value='" + newColor + "' class=\"hide\" name=\"MainSteps[Select][" + count + "][color]\"> </div>\n" +
            "                                            <div class=\"jq-selectbox__trigger\">\n" +
            "                                                <div class=\"jq-selectbox__trigger-arrow\"></div>\n" +
            "                                            </div>\n" +
            "                                        </div>\n" +
            "\n" +
            "                                        <div class=\"color-customDropDawnList-01 shortWidth hide\">\n" +
            "                                            <ul>\n" +
            color +
            "                                            </ul>\n" +
            "                                        </div>\n" +
            "                                    </div>"

        blockOption = '<div class="block-row">\n' +
            '                                <div class="row-input color-option_input">\n' +
            '                                    <input type="text" autocomplete="off" name="MainSteps[Select][' + count + '][name]" class="optionName" placeholder="Этап ' + count + '" maxlength="100">' +
            '                                </div>\n' +
            colorSelect +
            '                                <div class="row-input input-weight">\n' +
            '                                    <input type="text" autocomplete="off" name="MainSteps[Select][' + count + '][weight]" id="inputWeight" pattern="^[ 0-9]+$" maxlength="3" class="optionWeight" value="' + weigth + '">' +
            '                                </div>\n' +
            '                                <div class="row-input">\n' +
            '                                    <img class="delDocument_set" onclick="deleteOption(event)" src="/img/cancel_newdoc.svg" alt="">\n' +
            '                                </div>\n' +
            '                            </div>';
        blockListOptions.append(blockOption);
        changeShowButtonDelete();
    });

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
        }
    }


    jQuery(function ($) {
        $(document).mouseup(function (e) { // событие клика по веб-документу
            if (!$(".color-customDropDawnList-01").is(e.target)) {
                $(".color-customDropDawnList-01").hide();
            }
        });
    });

    function showDropDawnColor(event) {
        let gh = event.target.closest('#colorSelect').children[1];
        gh.style.display = 'block';
    }

    // меняем цвет
    function changeColor(event, color) {
        let colorBlock = event.target.closest('#colorSelect').querySelector('.color-block'),
            inputColorBlock = colorBlock.querySelector('input');
        colorBlock.style.backgroundColor = color;
        inputColorBlock.value = color;
    }

</script>
<?php $this->pageTitle = 'Новый контакт'; ?>
<?php $correct_path = 'http://' . $_SERVER["HTTP_HOST"]; ?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'new-client',
    'htmlOptions' => [
        'class' => 'page-form'
    ]
));
?>
<div class="clients-hat">
    <div class="client-name">

        <?php echo CHtml::link('Контакты', array('page/clients_page')); ?>
        <img src="/img/right-arrow-button.svg" alt="">Новый контакт
    </div>
    <div class="goback-link pull-right">
        <input class="btn_close" type="button" onclick="history.back();" value="❮  Назад "/>
    </div>
</div>

<main class="content full2" role="main">
    <div class="content-edit-block">
        <div class="title_name_1">Анкета</div>
        <div class="content-01">
            <?php
            if ($errorAddField) { ?>
                <div class="errorAddField"><? echo $errorAddFieldText ?></div>
                <?
            }
            ?>

            <div class="client-content">
                <?php
                foreach ($additionalFiledValuesInClient as $key => $fieldSection) {
                    ?>
                    <div class="block_client">
                        <div class="main-table row edit-row">
                            <div class="profile_info_block clear_fix">
                                <div class="profile_info_header_wrap">
                                    <span class="profile_info_header"><? echo $fieldSection[0]['sectionName'] ?></span>
                                </div>
                            </div>
                            <?php
                            foreach ($fieldSection as $value) { ?>
                                <div class="block-row additionalField">
                                    <div class="row-label">
                                        <? echo $value['name']; ?>
                                        <? if ($value['required']) { ?>
                                            <div class="requiredAddField">*</div>
                                        <? } ?>
                                    </div>

                                    <div class="row-input with-image" style="margin-right: 0px;">

                                        <?php
                                        $valueField = isset($additionalFiledValue[$value['table_name']]) ? $additionalFiledValue[$value['table_name']] : $value['value'];
                                        $classPositionImage = 'textSize24';
                                        switch ($value['type']) {
                                            case 'checkbox':
                                                echo $form->checkBox($client, "additionalField[$value[table_name]]", ['checked' => $valueField, 'class' => "form-control_anketa"]);;
                                                break;
                                            case 'date':
                                                echo $this->widget('ext.CJuiDateTimePicker.CJuiDateTimePicker', array(
                                                    'name' => "Clients[additionalField][$value[table_name]]",
                                                    'model' => $client,
                                                    'attribute' => "additionalField[$value[table_name]]",
                                                    'language' => 'ru',
                                                    'htmlOptions' => array(
                                                        'value' => isset($valueField) && is_numeric($valueField) ? date('d.m.Y', $valueField) : '',
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
                                                echo $form->textArea($client, "additionalField[$value[table_name]]", ['class' => "form-control $size", 'value' => $valueField]);
                                                if (count($additionalFiledValuesInClient) == $key && $key % 2 != 0) {
                                                    $classPositionImage .= ' longText';
                                                }
                                                ?>

                                                <?
                                                break;
                                            case 'int':
                                                echo $form->textField($client, "additionalField[$value[table_name]]", ['class' => 'form-control', 'value' => $valueField]);
                                                if (count($additionalFiledValuesInClient) == $key && $key % 2 != 0) {
                                                    $classPositionImage .= ' longText';
                                                }
                                                ?>

                                                <?
                                                break;
                                            case 'select':
                                                $selected = null;
                                                $data = [];
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
                                                echo CHtml::dropDownList("Clients[additionalField][$value[table_name]]", $selected, $data, ['class' => 'styled select']);
                                                break;
                                            default:
                                                echo $form->textField($client, "additionalField[$value[table_name]]", ['class' => 'styled status', 'value' => $valueField]);
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
                    </div>
                <? } ?>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>

    <div class="box-gray111 width-static">
        <div class="edit_user_1anketa">
            <div class="title_name_2">Параметры</div>

            <div class="popup__form_actions">
                <div class="client_info">
                    Ответственный:
                </div>
                <div class="solid_an_client">
                    <?php
                    if ($targetUser) {
                        echo CHtml::label($targetUser->first_name, '');
                        echo $form->hiddenField($client, 'responsable_id', array('value' => $targetUser->id));
                        echo $form->hiddenField($client, 'responsable_id', array('name' => 'type', 'value' => 'targetUser'));
                    } else {
                        $role = UsersRoles::model()->find('user_id = ' . Yii::app()->user->id)->itemname;
                        $responsible_options = array(Yii::app()->user->id => 'Я ответственный', 'director_create_client' => 'Руководители', 'manager_create_client' => 'Менеджеры');

                        $managers = Users::getUserAccess($user, true, false, true);
                        $directors = Users::getUserAccess($user, false, true, true);

                        $directors = Users::getUserAccess($user, false, true);

                        if ($role == 'director') {
                            unset($responsible_options['director_create_client']);
                            $responsible_options[$user->parent_id] = $user->parent->first_name;

                        } elseif ($role == 'manager') {
                            $responsible_options[$user->parent_id] = $user->parent->first_name;
                            unset($responsible_options['director_create_client']);
                        }

                        if ($user->parent->roles[0]->name != 'admin' || $user->common_access == Users::ACCESS_EMBAGRO
                            || $user->roles[0]->name == 'admin'
                        ) {
                            unset($responsible_options['admin']);
                        }

                        if (count($directors) <= 0) {
                            unset($responsible_options['director_create_client']);
                        }

                        if (count($managers) <= 0) {
                            unset($responsible_options['manager_create_client']);
                        }

                        $IamResponsible = false;
                        $selected_option = [];
                        // выбор значений в селекторах с ролями и пользователями
                        if ($client->responsable_id == Yii::app()->user->id) {
                            $selected_option = array('i' => array('selected' => true));
                            $IamResponsible = true;
                        } elseif ($client->responsable_id == 'no') {
                            $selected_option = array('no' => array('selected' => true));
                        } elseif ($client->responsable_id == $user->parent_id) {
                            $selected_option = array('admin' => array('selected' => true));
                        }

                        $directors_block_to_display = '';
                        $managers_block_to_display = '';

                        if (is_numeric($client->responsable_id) && $client->responsable_id != 0) {
                            $client_resp_role = UsersRoles::model()->find('user_id=' . $client->responsable_id);
                            if ($client_resp_role->itemname == 'director') {
                                $selected_option = array('director_create_client' => array('selected' => true));
                            } elseif ($client_resp_role->itemname == 'manager') {
                                if (!$IamResponsible) {
                                    $selected_option = array('manager_create_client' => array('selected' => true));
                                }
                            }
                            $directors_block_to_display = $client_resp_role->itemname == 'director' && $client->responsable_id != $user->parent_id ? 'style="display:block"' : '';
                            $managers_block_to_display = $client_resp_role->itemname == 'manager' && !($IamResponsible && $role == 'manager') && $client->responsable_id != $user->parent_id ? 'style="display:block"' : '';
                        }

                        echo $form->dropDownList($client, 'responsable_id', $responsible_options, array('options' => $selected_option, 'class' => 'styled permis editable typeAccess', 'name' => 'type'));
                        ?>

                        <div class="access-options access-tab"
                             id="director_create_client" <?php echo $directors_block_to_display ?>>
                            <?php echo $form->dropDownList($client, 'director_id', CHtml::listData($directors, 'id', 'first_name'),
                                array('options' => array($client->responsable_id => array('selected' => true)), 'class' => 'styled')); ?>
                        </div>
                        <div class="access-options access-tab"
                             id="manager_create_client" <?php echo $managers_block_to_display ?>>
                            <?php echo $form->dropDownList($client, 'manager_id', CHtml::listData($managers, 'id', 'first_name'),
                                array('options' => array($client->responsable_id => array('selected' => true)), 'class' => 'styled')); ?>
                        </div>

                        <?
                    }
                    ?>
                </div>

                <!-- ЭТАПЫ -->
                <? if (count($listStep) > 0) { ?>
                    <div class="label_info">
                        Воронка:
                    </div>

                    <div class="solid_an_client">
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
                            <div class="jq-selectbox__select color-select client" onclick="showDropDawnColor(event)">
                                <div class="color-block"
                                     style="background-color: <? echo $selectedOption->color ?>">
                                    <span><? echo $selectedOption->name ?> </span>
                                    <input type="text" value="<? echo $selectedOption->id ?>" class="hide"
                                           name="StepsInClients[selected_option_id]">
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
                    </div>
                <? } ?>


                <? if ($allLabels && count($allLabels) > 0) { ?>
                    <div class="label_info bottom_10">
                        Метки:
                        <a class="delete" id="editLabels" onclick="return false;">Редактировать</a>
                    </div>
                    <div class="solid_an_client">
                        <div class="customDropDownListLabels hide">
                            <ul>
                                <? foreach ($allLabels as $label) { ?>
                                    <li id="labelLi <? echo $label->id ?>" class="labelLi"
                                        name="Clients[labelLi<? echo $label->id ?>]"
                                        onclick="changeLabel('<? echo $label->id; ?>');">
                                        <?
                                        echo $form->checkBox($client, "Labels[$label->id]", [
                                            'id' => 'checkbox' . $label->id,
                                            'class' => 'hide',
                                            'checked' => false
                                        ]);
                                        ?>
                                        <div class="deleted" id="blockOper<? echo $label->id; ?>"></div>
                                        <div class="block-color" id="labelColor<? echo $label->id; ?>"
                                             style="background-color: <? echo $label->color ?>"></div>
                                        <span id="labelText<? echo $label->id; ?>"><? echo $label->name ?></span>
                                    </li>
                                <? } ?>

                            </ul>
                        </div>

                        <div class="block-labelsInProfile">
                            <? foreach ($customSelectedLabels as $label) { ?>
                                <div class="block-elem" id="blockElem<? echo $label->id ?>">
                                    <div class="block-color" style="background-color: <? echo $label->color ?>"></div>
                                    <span><? echo $label->name ?></span>
                                </div>
                            <? } ?>
                        </div>
                    </div>
                <? } ?>
                <div class="solid_an_client">
                    <?php echo CHtml::submitButton('Добавить контакт', array('class' => 'maui_btn', 'id' => 'save')); ?>
                    <?php echo CHtml::submitButton('Добавить и создать', array('class' => 'foton_btn', 'id' => 'save_and_create', 'name' => 'save_and_create')); ?>
                    <div id="preloader" style="margin: 0 auto;"></div>
                </div>
            </div>
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

    // устанавливаем выбранные ранее метки, на случай если валидация в методе не прошла
    var listLabels = $(".block-labelsInProfile .block-elem") || [],
        listOption = <?echo json_encode($listStepOptionJS)?>;
    for (var i = 0; i < listLabels.length; i++) {
        var labelId = listLabels[i].id.replace('blockElem', '');
        var elem = $('#blockOper' + labelId);
        $('#checkbox' + labelId).prop('checked', true);
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
    };

    $("#editLabels").click(function (e) {
        var listLabels = $(".customDropDownListLabels");
        if (listLabels.hasClass('hide')) {
            listLabels.removeClass('hide');
        } else {
            listLabels.addClass('hide');
        }
    });

    jQuery(function ($) {
        $(document).mouseup(function (e) { // событие клика по веб-документу
            var div = $(".customDropDownListLabels"); // тут указываем ID элемента
            if (!div.is(e.target) && div.has(e.target).length === 0 && !$("#editLabels").is(e.target)) {//&& div.has(e.target).length === 0) { // и не по его дочерним элементам
                div.addClass('hide'); // скрываем его
            }

            if (!$(".color-customDropDawnList").is(e.target)) {
                $(".color-customDropDawnList").hide();
            }

        });
    });

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
</script>
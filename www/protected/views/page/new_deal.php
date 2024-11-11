<?php $this->pageTitle = 'Новая сделка'; ?>
<?php $correct_path = 'http://' . $_SERVER["HTTP_HOST"]; ?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'new-deal',
    'htmlOptions' => [
        'class' => 'page-form'
    ]
));
?>


<div class="clients-hat">
    <div class="client-name">
        <?php echo CHtml::link('Сделки', array('page/dealings_page')); ?>
        <img src="/img/right-arrow-button.svg" alt="">
        <?php echo CHtml::link($client->name, Yii::app()->createUrl("page/client_profile", array("id" => $client->id))); ?>
        <img src="/img/right-arrow-button.svg" alt="">Новая сделка

    </div>

    <div class="goback-link pull-right">
        <input class="btn_close" type="button" onclick="history.back();" value="❮  Назад "/>
    </div>
</div>


<main class="content full2" role="main">
    <div class="content-edit-block">
        <div class="title_name_1">Новая сделка</div>
        <div class="content-01">
            <div class="box-gray__body no-border3 active-pad">

                <div class="client_info">
                    Наименование:<span class="star">*</span>
                </div>
                <div class="form-group_actions">
                    <?php echo $form->textField($deals, 'text', array('class' => 'form-control editable', 'placeholder' => 'Имя сделки')); ?>
                    <?php echo $form->error($deals, 'text', array('class' => 'form-error')); ?>
                </div>


                <div class="client_info">
                    Описание:
                </div>
                <div class="form-group_actions">
                    <?php echo $form->textArea($deals, 'description', array('class' => 'form-control1 editable', 'placeholder' => 'Напишите комментарий...')); ?>
                    <div class="client_info" style="padding-top: 10px;">
                        Сумма и Остаток:
                    </div>
                    <div class="form-group two-inline" style="width: 300px;">
                        <?php echo $form->textField($deals, 'paid', array('class' => 'form-control editable  pull-left', 'placeholder' => 'Сумма')); ?>
                        <?php echo $form->textField($deals, 'balance', array('class' => 'form-control editable  pull-right', 'placeholder' => 'Остаток')); ?>
                        <?php // echo $form->error($deals, 'paid', array('class' => 'form-error pull-left')); ?>
                        <?php // echo $form->error($deals, 'balance', array('class' => 'form-error pull-right', 'style'=>'margin-right: 30px;')); ?>
                    </div>
                </div>

            </div>

            <?php $this->endWidget(); ?>
        </div>
    </div>

    <div class="box-gray111 width-static">
        <div class="edit_user_1anketa">
            <div class="title_name_2">Параметры</div>
            <div class="popup__form_actions">
                <div class="client_info">
                    Ответственный:
                </div>
                <div class="solid_an_client">

                    <label>
                        <?php $responsible_options = array('i' => 'Я ответственный', 'director_deal' => 'Руководители', 'manager_deal' => 'Менеджеры', 'no' => $user->parent->first_name);
                        $role = UsersRoles::model()->find('user_id=' . Yii::app()->user->id)->itemname;
                        $managers = Users::getUserAccess($user, true, false, true);
                        $directors = Users::getUserAccess($user, false, true, true);
                        if ($user->parent->roles[0]->name != 'admin' || $user->common_access == Users::ACCESS_EMBAGRO
                            || $user->roles[0]->name == 'admin'
                        ) {
                            unset($responsible_options['no']);
                        }

                        if (count($directors) <= 0) {
                            unset($responsible_options['director_deal']);
                        }
                        if (count($managers) <= 0) {
                            unset($responsible_options['manager_deal']);
                        }
                        ?>
                        <?php echo $form->dropDownList($deals, 'responsable_id', $responsible_options, array('class' => 'styled permis editable typeAccess', 'name' => 'type')); ?>
                    </label>
                    <div class="access-options access-tab" id="director_deal">
                        <label>
                            <?php echo $form->dropDownList($deals, 'director_id', CHtml::listData($directors, 'id', 'first_name'), array('class' => 'styled')); ?>
                        </label>
                    </div>
                    <div class="access-options access-tab" id="manager_deal">
                        <label>
                            <?php echo $form->dropDownList($deals, 'manager_id', CHtml::listData($managers, 'id', 'first_name'), array('class' => 'styled')); ?>
                        </label>
                    </div>

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
                            <div class="jq-selectbox__select color-select client"
                                 onclick="showDropDawnColor(event)">
                                <div class="color-block"
                                     style="background-color: <? echo $selectedOption->color ?>">
                                    <span><? echo $selectedOption->name ?> </span>
                                    <input type="text" value="<? echo $selectedOption->id ?>" class="hide"
                                           name="StepsInDeals[selected_option_id]">
                                </div>
                                <div class="jq-selectbox__trigger">
                                    <div class="jq-selectbox__trigger-arrow"></div>
                                </div>
                            </div>

                            <div class="color-customDropDawnList client shortWidth hide" style="max-width: 194px;">
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
                                        name="Deals[labelLi<? echo $label->id ?>]"
                                        onclick="changeLabel('<? echo $label->id; ?>');">
                                        <?
                                        echo $form->checkBox($deals, "Labels[$label->id]", [
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
                                    <div class="block-color"
                                         style="background-color: <? echo $label->color ?>"></div>
                                    <span><? echo $label->name ?></span>
                                </div>
                            <? } ?>
                        </div>
                    </div>
                <? } ?>


                <div class="form-group_actions">

                    <?php
                    if ($user->roles[0]->name == 'admin' || $userRight->create_deals == 1) {
                        echo CHtml::submitButton('Добавить сделку', array('class' => 'maui_btn', 'id' => 'save'));
                        echo CHtml::submitButton('Добавить и создать', array('class' => 'foton_btn', 'name' => 'save_and_create', 'id' => 'save_and_create'));
                    }
                    ?>
                    <div id="preloader" style="margin: 0 auto;"></div>

                </div>

            </div>

        </div>
    </div>
</main>


<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
    $("#new-deal").submit(function () {
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
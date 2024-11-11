<?php $this->pageTitle = 'Новая задача'; ?>


<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'new-action',
    'htmlOptions' => [
        'class' => 'page-form'
    ]
));
?>

<div class="clients-hat">
    <div class="client-name">
        <?php echo CHtml::link('Задачи  ', array('page/actions_page')); ?>
        <img src="/img/right-arrow-button.svg" alt="">
        <?php echo CHtml::link($client->name, Yii::app()->createUrl("page/client_profile", array("id" => $client->id))); ?>
        <img src="/img/right-arrow-button.svg" alt="">Новая задача
    </div>

    <div class="goback-link pull-right">
        <?php echo $form->hiddenField($actions, 'client_id');
        if ($user->roles[0]->name == 'admin' || $userRight->create_action == 1) {
            ?>
        <? } ?>
        <input class="btn_close" type="button" onclick="history.back();" value="❮  Назад "/>
    </div>
</div>

<main class="content full2" role="main">
    <div class="content-edit-block">
        <div class="title_name_1">Новая задача</div>
        <div class="content-01">
            <div class="box-gray__body no-border3 active-pad">
                <div class="client_info">
                    Тема:<span class="star">*</span>

                </div>
                <div class="form-group_actions">
                    <?php echo $form->textField($actions, 'text', array('class' => 'form-control editable', 'placeholder' => 'Что нужно сделать...')); ?>
                    <?php echo $form->error($actions, 'text', array('class' => 'form-error')); ?>
                </div>

                <div class="client_info">
                    Описание:
                </div>
                <div class="form-group_actions">
                    <?php
                    echo $form->textArea($actions, 'description', array('class' => 'form-control1 editable', 'placeholder' => 'Напишите комментарий...'));
                    ?>
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
                        <?php
                        $responsible_options = array('i' => 'Я ответственный', 'director_action' => 'Руководители', 'manager_action' => 'Менеджеры', 'no' => $user->parent->first_name);

                        $role = UsersRoles::model()->find('user_id=' . Yii::app()->user->id)->itemname;
                        $managers = Users::getUserAccess($user, true, false, true);
                        $directors = Users::getUserAccess($user, false, true, true);
                        if ($user->parent->roles[0]->name != 'admin' || $user->common_access == Users::ACCESS_EMBAGRO
                            || $user->roles[0]->name == 'admin'
                        ) {
                            unset($responsible_options['no']);
                        }

                        if (count($directors) <= 0) {
                            unset($responsible_options['director_action']);
                        }
                        if (count($managers) <= 0) {
                            unset($responsible_options['manager_action']);
                        }
                        ?>
                        <?php echo $form->dropDownList($actions, 'responsable_id', $responsible_options, array('class' => 'styled permis editable typeAccess', 'name' => 'type')); ?>
                        <div class="access-options access-tab" id="director_action">
                            <label>
                                <?php echo $form->dropDownList($actions, 'director_id', CHtml::listData($directors, 'id', 'first_name'), array('class' => 'styled')); ?>
                            </label>
                        </div>
                        <div class="access-options access-tab" id="manager_action">
                            <label>
                                <?php echo $form->dropDownList($actions, 'manager_id', CHtml::listData($managers, 'id', 'first_name'), array('class' => 'styled')); ?>
                            </label>
                        </div>
                    </label>
                </div>
                <div class="client_info">
                    <img src="/img/clock.svg" alt="">Дата выполнения:<span class="star">*</span>
                </div>
                <div class="solid_an_client">
                    <?php
                    echo $this->widget('ext.CJuiDateTimePicker.CJuiDateTimePicker', array(
                        'name' => 'Actions[action_date]',
                        'model' => $actions,
                        'attribute' => 'action_date',
                        'language' => 'ru',
                        'htmlOptions' => array(
                            'value' => isset($actions->action_date) ? date('d.m.Y H:m', strtotime($actions->action_date)) : '',
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
                    ), true); ?>
                    <?php echo $form->error($actions, 'action_date', array('class' => 'form-error')); ?>

                </div>

                <!-- Состояния -->
                <?
                $statuses_array = ActionsStatuses::model()->findAll();
                if (count($statuses_array) > 0) { ?>


                    <div class="solid_an_client" style="margin-top: -5px;padding-bottom: 13px;">
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

                        <div class="row-input" id="colorSelect" style="display: inline-flex">
                            <div class="jq-selectbox__select color-select client" onclick="showDropDawnColor(event)">
                                <div class="color-block"
                                     style="background-color: <? echo $selectedStatus->color ?>">
                                    <span><? echo $selectedStatus->name ?> </span>
                                    <input type="text" value="<? echo $selectedStatus->id ?>" class="hide"
                                           name="Actions[action_status_id]">
                                </div>
                                <div class="jq-selectbox__trigger">
                                    <div class="jq-selectbox__trigger-arrow"></div>
                                </div>
                            </div>

                            <div class="color-customDropDawnList client shortWidth hide" style="max-width: 194px;">
                                <ul>
                                    <?
                                    if ($statuses_array) {
                                        foreach ($statuses_array as $status) {
                                            echo "<li value='$status->id' onclick='changeColor(event, " . '"' . $status->color . '",' . " " . '"' . $status->name . '", ' . $status->id . ")'><div class='block-color' style='background-color:$status->color;'><span>$status->name</span></div></li>";
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
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
                                        name="Actions[labelLi<? echo $label->id ?>]"
                                        onclick="changeLabel('<? echo $label->id; ?>');">
                                        <?
                                        echo $form->checkBox($actions, "Labels[$label->id]", [
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

                <?php
                echo CHtml::submitButton('Добавить задачу', array('class' => 'maui_btn', 'id' => 'save')); ?>
                <? echo CHtml::submitButton('Добавить и создать', array('class' => 'foton_btn', 'id' => 'save_and_create', 'name' => 'save_and_create'))
                ?>
                <div id="preloader" style="margin: 0 auto;"></div>


            </div>

        </div>
    </div>
</main>

<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
    $("#new-action").submit(function () {
        $("#preloader").addClass('preloader');
        $("#save_and_create").hide();
        $("#save").hide();
    });

    // устанавливаем выбранные ранее метки, на случай если валидация в методе не прошла
    var listLabels = $(".block-labelsInProfile .block-elem") || [];
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
            spanText = colorBlock.querySelector('span');
        colorBlock.style.backgroundColor = color;
        inputColorBlock.value = id;
        spanText.textContent = name;
    }
</script>
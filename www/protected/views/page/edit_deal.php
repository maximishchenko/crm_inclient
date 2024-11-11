<?php $this->pageTitle = $deal->text; ?>
<?php $correct_path = 'http://' . $_SERVER["HTTP_HOST"]; ?>

<?php
$role = UsersRoles::model()->find('user_id=' . Yii::app()->user->id)->itemname;
$responsible_options = array('i' => 'Я ответственный', 'director_edit_deal' => 'Руководители', 'manager_edit_deal' => 'Менеджеры', 'no' => $user->parent->first_name);
$managers = Users::getUserAccess($user, true, false, true);
$directors = Users::getUserAccess($user, false, true, true);
if ($user->parent->roles[0]->name != 'admin' || $user->common_access == Users::ACCESS_EMBAGRO
    || $user->roles[0]->name == 'admin'
) {
    unset($responsible_options['no']);
}

if (count($directors) <= 0) {
    unset($responsible_options['director_edit_deal']);
}
if (count($managers) <= 0) {
    unset($responsible_options['manager_edit_deal']);
}

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'edit-deal',
    'enableAjaxValidation' => false,
    'htmlOptions' => [
        'class' => 'page-form'
    ]
));

$delete_button = CHtml::button("Удалить", array(
    'onClick' => 'window.location.href="' . Yii::app()->createUrl("page/delete_deal", array("id" => $deal->id)) . '"',
    'class' => 'btn',
));

$directors_block_to_display = $deal_resp_role->itemname == 'director' ? 'style="display:block"' : '';
$managers_block_to_display = $deal_resp_role->itemname == 'manager' ? 'style="display:block"' : '';

$admin = new Users();
$adminId = $admin->getAdminId();
$newResponsible = $user->parent_id != null ? $user->parent_id : $adminId;


$prior_array = DealsPriority::model()->findAll();
$priority_selector = '';
foreach ($prior_array as $prior) {
    $priority_selector .= '<option ' . ($deal->deal_priority_id == $prior->id ? ' selected="selected"' : '') . ' class="' . $prior->color . '" value="' . $prior->id . '">' . 'Приоритет: ' . $prior->name . '</option>';
}

$statuses_array = DealsStatuses::model()->findAll();
$status_selector = '';
foreach ($statuses_array as $status) {
    $status_selector .= '<option ' . ($deal->deal_status_id == $status->id ? ' selected="selected"' : '') . ' value="' . $status->id . '">' . 'Состояние: ' . $status->name . '</option>';
};

// выбор значения в селекторе
if ($deal->responsable_id == Yii::app()->user->id) {
    $selected_option = array('i' => array('selected' => true));
} elseif ($deal_resp_role->itemname == 'director') {
    $selected_option = array('director_edit_deal' => array('selected' => true));
} elseif ($deal_resp_role->itemname == 'manager') {
    $selected_option = array('manager_edit_deal' => array('selected' => true));
} else {
    $selected_option = array('no' => array('selected' => true));
}

$directors_block_to_display = $deal_resp_role->itemname == 'director' && $role != 'director' ? 'style="display:block"' : '';
$managers_block_to_display = $deal_resp_role->itemname == 'manager' && count($managers) > 0 && key($selected_option) != 'i' ? 'style="display:block"' : '';

$dealTypeClassCss = 'dealTypeActive';
switch ($deal->deal_type_id) {
    case 2:
        {
            $dealTypeClassCss = 'dealTypeWin';
            break;
        }
    case 3:
        {
            $dealTypeClassCss = 'dealTypeLose';
            break;
        }
}
?>

<div class="clients-hat">
    <div class="client-name">
        <div id="dealTypeBtn"
             class="<? echo $dealTypeClassCss ?>"><? echo $dealTypeList[$deal->deal_type_id]['name'] ?></div>
        <?php echo CHtml::link('Сделки', array('page/dealings_page')); ?>
        <img src="/img/right-arrow-button.svg" alt="">
        <?php
        if ($accessClient) {
            echo CHtml::link($client->name, Yii::app()->createUrl("page/client_profile", array("id" => $client->id)));
        } else {
            echo $client->name;
        }
        ?>
        <img src="/img/right-arrow-button.svg" alt="">
        <?php echo $deal->text; ?>, #<?php echo $deal->id; ?>
    </div>

    <div class="goback-link pull-right">
        <input class="btn_close" type="button" onclick="history.back();" value="❮  Назад "/>
    </div>
</div>

<main class="content full2" role="main">
    <div class="content-edit-block">
        <div class="title_name_1">Сделка</div>
        <div class="content-01">

            <?
            $color = '';
            $imageName = 'gud.svg';
            if ($deal->deal_type_id == 1) {
                $color = !$justPushSaveBtn && $deal->deal_type_id == 1 ? 'deal-type-return' : '';
                $imageName = !$justPushSaveBtn && $deal->deal_type_id == 1 ? 'return-deal.svg' : 'gud.svg';
            }

            ?>

            <?if ($deal->deal_type_id == 3) {
                $color = 'deal-type-save-red';
                $imageName = 'stop-deal.svg';

            } elseif ($deal->deal_type_id == 2) {
                $color = 'deal-type-win';
                $imageName = 'deal-win.svg';
            }?>

            <div class="save-message <? echo $color;
            echo $deal->deal_type_id == 2 || $deal->deal_type_id == 3 || $isShowBlockSave && $deal->deal_type_id && !$justPushSaveBtn == 1 ? '' : ' hide' ?>">
                <div class="flex">
                    <img src="/img/<?echo $imageName?>" alt="">
                    <div class="line_height_1_5">
                        <? echo $messageSaveType; ?>
                        <?
                        if ( $deal->deal_type_id == 1) {
                            echo $accessClient
                                ? CHtml::link($client->name, Yii::app()->createUrl("page/client_profile", array("id" => $client->id)))
                                : $client->name;
                        }
                        ?>
                    </div>
                </div>
            </div>

            <? if ($isShowBlockSave && $justPushSaveBtn) {?>
                <script type="module">
                    import {NotificationBar} from '/js/notificationBar.js';
                    let accessClient = <? echo $accessClient?>;
                    let client = <? echo json_encode($client->attributes)?>;
                    const notificationBar = new NotificationBar({
                        type: 'success',
                        title: 'Сделка сохранена',
                        description: 'Информация в сделке изменена<br>Контакт: ' + (accessClient
                            ? '<a href="/page/client_profile/' + client.id + '">' + client.name + '</a>'
                            : client.name)
                    });
                    notificationBar.show();
                </script>
            <?}?>

            <? if ($isSuccessSave && !$isShowBlockSave) {?>
                <script type="module">
                    import {NotificationBar} from '/js/notificationBar.js';
                    let accessClient = <? echo $accessClient?>;
                    let client = <? echo json_encode($client->attributes)?>;
                    const notificationBar = new NotificationBar({
                        type: 'warning',
                        title: 'Сделка создана',
                        description: 'Новая сделка добавлена для:<br> ' + (accessClient
                            ? '<a href="/page/client_profile/' + client.id + '">' + client.name + '</a>'
                            : client.name)
                    });
                    notificationBar.show();
                </script>
            <?}?>

            <div class="box-gray__body no-border3 active-pad">

                <div class="client_info">
                    Наименование:<span class="star">*</span>
                </div>
                <div class="form-group_actions">
                    <?php
                    echo $form->textField($deal, 'text', array(
                            'class' => 'form-control editable',
                            'placeholder' => 'Имя сделки',
                            'disabled' => $deal->deal_type_id != 1,
                        )) .
                        $form->error($deal, 'text', array('class' => 'form-error'))
                    ?>
                </div>


                <div class="client_info">
                    Описание:
                </div>
                <div class="form-group_actions">
                    <?php

                    echo $form->textArea($deal, 'description', array('class' => 'form-control1 editable', 'placeholder' => 'Напишите комментарий...'));

                    ?>
                    <div class="client_info" style="padding-top: 10px;">
                        Сумма и Остаток:
                    </div>
                    <div class="form-group two-inline" style="width: 300px;">
                        <?
                        echo $form->textField($deal, 'paid',
                            array(
                                'class' => 'form-control editable  pull-left',
                                'placeholder' => 'Сумма',
                                'disabled' => $deal->deal_type_id != 1,
                            ));
                        echo $form->textField($deal, 'balance',
                            array(
                                'class' => 'form-control editable  pull-right',
                                'placeholder' => 'Остаток',
                                'disabled' => $deal->deal_type_id != 1,
                            ));
                        echo $form->error($deal, 'paid', array('class' => 'form-error  pull-left'));
                        echo $form->error($deal, 'balance', array('class' => 'form-error pull-right', 'style' => 'margin-right: 30px;'));
                        ?>
                    </div>
                </div>


                <!-- Файлы в сделках -->
                <?php
                if ($userRight['role'] == 'admin' || $userRight['add_files_deal']) {
                    ?>
                    <div class="action_file">

                        <?php
                        $folder = '/uploads/';
                        foreach ($dealFiles as $file) {
                            echo CHtml::link($file->file->name, Yii::app()->createUrl("page/get_file_deal", ['id' => $file->id]), ['target' => '_blank']);
                            if ($userRight['role'] == 'admin' || $userRight['delete_files_deal']) {
                                echo CHtml::image('/img/cancel.svg', '', ['class' => 'delDocument_4a', 'onClick' => 'delDocument(' . $file->id . ')']);
                            }
                            echo '<br>';
                        }
                        ?>
                        <div class="" id="fileBlock"></div>
                        <div class="action_file_add">
                            <?php

                            $fileSettings = Yii::app()->commonFunction->getFileSettings();

                            $this->widget('ext.EAjaxUpload.EAjaxUpload',
                                array(
                                    'id' => 'uploadFile',
                                    'config' => array(
                                        'multiple' => true,
                                        'action' => '/page/UploadDealFile?id=' . $deal->id,
                                        'allowedExtensions' => explode(',', str_replace(' ', '', $fileSettings['extFile'])),//array("jpg","jpeg","gif","exe","mov" and etc...
                                        'sizeLimit' => $fileSettings['sizeFile'] * 1024 * 1024,// maximum file size in bytes
                                        'onComplete' => "js:function(id, fileName, responseJSON){ 
                                        addFileBlock(responseJSON);
                                    }",
                                        'messages' => array(
                                            'typeError' => "Ошибка! Расширение файла {file} не поддерживается. Разрешенные типы файлов: {extensions}.",
                                            'sizeError' => "{file} максимальный размер файла {sizeLimit}.",
                                            //                  'minSizeError'=>"{file} is too small, minimum file size is {minSizeLimit}.",
                                            //                  'emptyError'=>"{file} is empty, please select files again without it.",
                                            //                  'onLeave'=>"The files are being uploaded, if you leave now the upload will be cancelled."
                                        ),
                                        //'showMessage'=>"js:function(message){ alert(message); }"
                                    )
                                ));

                            ?>

                        </div>
                    </div>
                    <?php
                }
                ?>
                <!--футер с описание изменений-->
                <div style="margin-top: 10px">
                    <?
                    $creatDate = date('d.m.y H:i', strtotime($deal->creation_date));
                    $changeDate = $deal->change_date ? date('d.m.y H:i', strtotime($deal->change_date)) : $creatDate;
                    echo "<p> Дата создания: $creatDate</p>
                                  <p> Дата изменения: $changeDate";
                    if ($deal->deal_type_id != 1) {
                        $closedDate = date('d.m.y H:i', strtotime($deal->closed_date));
                        $countDayOfClosed = Yii::app()->commonFunction->getDateWithString($deal->creation_date, $deal->closed_date);
                        echo "<p> Дата закрытия: $closedDate</p>
                                      <p>Срок: $countDayOfClosed</p>";
                    }
                    ?>
                </div>
            </div>
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

                    <?php
                    echo '
                ' . $form->dropDownList($deal, 'responsable_id', $responsible_options, array(
                            'options' => $selected_option,
                            'class' => 'styled permis editable typeAccess',
                            'name' => 'type',
                            'disabled' => $deal->deal_type_id != 1,
                        )) . '
            
			<div class="access-options access-tab" id="director_edit_deal"' . $directors_block_to_display . '>
                ' . $form->dropDownList($deal, 'director_id', CHtml::listData($directors, 'id', 'first_name'), array(
                            'options' => array($deal->responsable_id => array('selected' => true)),
                            'class' => 'styled',
                            'disabled' => $deal->deal_type_id != 1,
                        )) . '
            </div>
			<div class="access-options access-tab" id="manager_edit_deal"' . $managers_block_to_display . '>
                ' . $form->dropDownList($deal, 'manager_id', CHtml::listData($managers, 'id', 'first_name'), array(
                            'options' => array($deal->responsable_id => array('selected' => true)),
                            'class' => 'styled',
                            'disabled' => $deal->deal_type_id != 1,
                        )) . '
			</div>';

                    ?>


                </div>

                <!-- Воронка -->
                <? if (count($listStep) > 0) { ?>
                    <div class="label_info">
                        Воронка:
                    </div>

                    <div class="solid_an_client">
                        <?php echo $form->dropDownList($selectedSteps, 'steps_id',
                            CHtml::listData($listStep, 'id', 'name'), [
                                'class' => 'styled',
                                'onChange' => 'changeStep()',
                                'id' => 'selectStep',
                                'disabled' => $deal->deal_type_id != 1,
                            ]); ?>


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
                            <div class="jq-selectbox__select color-select client <?echo $deal->deal_type_id != 1 ? 'disabledStepSelect' : ''?>"
                                 onclick="showDropDawnColor(event, <?echo $deal->deal_type_id == 1 ? 1 : 0?>)"
                            >
                                <div class="color-block"
                                     style="background-color: <? echo $selectedOption->color ?>">
                                    <span><? echo $selectedOption->name ?> </span>
                                    <input type="text" value="<? echo $selectedOption->id ?>" class="hide"
                                           name="MainStepsInDeals[selected_option_id]">
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


                <? if (count($allLabels) > 0) { ?>
                    <div class="label_info bottom_10">
                        Метки:
                        <?if($deal->deal_type_id == 1) {?>
                            <a class="delete" id="editLabels" onclick="return false;">Редактировать</a>
                        <?} else {?>
                            <span class="disabledText">Редактировать</span>
                        <?}?>
                    </div>
                    <div class="solid_an_client">

                        <div class="customDropDownListLabels hide">
                            <ul>
                                <? foreach ($allLabels as $label) { ?>
                                    <li id="labelLi <? echo $label->id ?>" class="labelLi"
                                        name="MainDeals[labelLi<? echo $label->id ?>]"
                                        onclick="changeLabel('<? echo $label->id; ?>');">
                                        <?
                                        echo $form->checkBox($deal, "Labels[$label->id]", [
                                            'id' => 'checkbox' . $label->id,
                                            'class' => 'hide',
                                            'checked' => isset($customSelectedLabels[$label->id])
                                        ]);
                                        $operType = isset($customSelectedLabels[$label->id]) ? 'added' : 'deleted';
                                        ?>
                                        <div class="<? echo $operType; ?>"
                                             id="blockOper<? echo $label->id; ?>"></div>
                                        <div class="block-color" id="labelColor<? echo $label->id; ?>"
                                             style="background-color: <? echo $label->color ?>"></div>
                                        <span id="labelText<? echo $label->id; ?>"><? echo $label->name ?></span>
                                    </li>
                                <? } ?>

                            </ul>
                        </div>

                        <div class="block-labelsInProfile">
                            <? foreach ($customSelectedLabels as $label) { ?>
                                <div class="block-elem" id="blockElem<?
                                echo $label->id ?>">
                                    <div class="block-color"
                                         style="background-color: <? echo $label->color ?>"></div>
                                    <span><? echo $label->name ?></span>
                                </div>
                            <? }
                            ?>
                        </div>

                    </div>

                <? } ?>

                <div class="solid_an_client">
                    <?php
                    if ($user->roles[0]->name == 'admin' || $userRight['create_deals'] == 1) {
                        echo '<div class="form-group_single_row" style="width: 100px;">
				' . CHtml::submitButton('Сохранить', array('class' => 'maui_btn', 'id' => 'save')) . '
			</div>';

                        echo '<div class="form-group_single_row" style="width: 100px;">
				' . CHtml::submitButton('Сохранить и создать', array('class' => 'foton_btn', 'style' => 'margin-bottom: 10px', 'name' => 'save_and_create', 'id' => 'save_and_create')) . '
			    </div>';

                        echo $form->textField($deal, 'deal_type_id', array('class' => 'hide', 'id' => 'inputDealType'));
                        echo $form->textField($newReason, 'id', array('class' => 'hide', 'id' => 'inputReason'));
                        ?>

                        <input type="submit" class="win_btn <?
                        echo $deal->deal_type_id == 2 || $deal->deal_type_id == 3 ? 'hide' : null ?>"
                               value="Выиграно" onclick="win(event)">

                        <div class="reason-container">
                            <div class="lose-container <?echo $deal->deal_type_id == 2 ? 'hide' : null ?>">
                                <input type="button" class="lose_btn " value="<?
                                echo $dealTypeList[3]['reason'] ?>" onclick="lose(event)">
                                <div class="jq-selectbox__trigger-arrow arrow-margin">
                                </div>
                            </div>

                            <!--Список причин-->
                            <div class="reason-block hide">
                                <ul style="border-top: none;padding-top: 10px;padding-bottom: 15px;">
                                    <? foreach ($reasons as $value) {
                                        echo "<li onclick='changeReason(event, $value->id)'>$value->name</li>";
                                    } ?>

                                </ul>
                            </div>
                            <input type="submit" class="return-active-btn <?
                            echo $deal->deal_type_id == 1 ? 'hide' : null ?>" value="Вернуть сделку"
                                   onclick="returnAction(event)" style="margin-bottom: 10px">
                        </div>
                        <?/* echo '<div class="form-group_single_row" style="width: 100px;">
				' . CHtml::submitButton('Проиграно', array('class' => 'maui_btn', 'onClick' => 'lose(event)')) . '
			</div>';*/ ?>


                        <?
                    }
                    ?>
                    <div id="preloader" style="margin: 0 auto;"></div>
                </div>



                <?
                if ($user->roles[0]->name == 'admin' || $userRight['delete_deals'] == 1) {
                    echo '
			<div class="function-delete" style="display: block;padding-left: 0px;text-align: center;">
				<a class="delete" href="#">Удалить сделку</a>
			</div>
			<div class="function-delete-confirm" style="display: none;">
				<ul class="horizontal_2">
					<li class="big">Подтвердите удаление:</li>
					<li><a href="#" class="cancel" style="margin-right: 10px;">Отмена</a></li>
					<li style="padding-top: 9px;padding-bottom: 24px;">' . $delete_button . '</li>
				</ul>
			</div>';
                }
                ?>
            </div>
        </div>

    </div>
</main>
<?php $this->endWidget(); ?>

<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<script>
    $("#edit-deal").submit(function () {
        $("#preloader").addClass('preloader');
        $("#save_and_create").hide();
        $("#save").hide();
        $(".return-active-btn").hide();
        $(".win_btn").hide();
        $(".lose-container").hide();
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

            if (!$(".lose_btn").is(e.target) && !$(".reason-block").hasClass("hide")) {
                $(".reason-block").addClass('hide');
            }
        });
    });

    function delDocument(id) {
        if (confirm('Вы действительно хотите удалить файл?')) {
            document.location.href = '/page/deal_document_delete/' + id;
        }
    }


    function addFileBlock(json) {
        <?
        if ($userRight['role'] == 'admin' || $userRight['delete_files_deal']) {
        ?>
        $("#fileBlock").append(
            '<a target="_blank" href="/page/get_file_deal/' + json.fileId + '">' + json.filename + '</a>' +
            '<img class="delDocument_4a" onclick="delDocument(' + json.fileId + ')" src="/img/cancel_newdoc.svg" alt="">' +
            '<br>');
        <?
        } else { ?>
        $("#fileBlock").append(
            '<a target="_blank" href="/page/get_file_deal/' + json.fileId + '">' + json.filename + '</a>' +
            '<br>');
        <?
        }
        ?>
        $("li.qq-upload-success").remove();
    }

    function showDropDawnColor(event, disabled) {
        if (disabled) {
            let gh = event.target.closest('#colorSelect').children[1];
            gh.style.display = 'block';
        }
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

    // обработчики кнопок для Типа

    function win(event) {
        $("#inputDealType")[0].value = 2;
    };

    function lose(event) {
        event.preventDefault();
        var reasonBlock = $(".reason-block");
        if (reasonBlock.hasClass('hide')) {
            reasonBlock.removeClass('hide');
        } else {
            reasonBlock.addClass('hide');
        }
    };

    function returnAction(event) {
        $("#inputDealType")[0].value = 1;
    };

    function changeReason(event, id) {
        $("#inputDealType")[0].value = 3;
        $("#inputReason")[0].value = id;
        $("#edit-deal").submit();
    };

    function closeNotification (event) {
        var notification = event.target;
        notification = notification.closest('.save-message') || notification.closest('.save-message_add');
        notification.classList.add('hide');
    }

</script>

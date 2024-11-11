<?php $this->pageTitle = $actions->text; ?>
<?php $correct_path = 'http://' . $_SERVER["HTTP_HOST"]; ?>

<?php
$role = UsersRoles::model()->find('user_id=' . Yii::app()->user->id)->itemname;
$responsible_options = array('i' => 'Я ответственный', 'director_edit_action' => 'Руководители', 'manager_edit_action' => 'Менеджеры', 'no' => $user->parent->first_name);
$managers = Users::getUserAccess($user, true, false, true);
$directors = Users::getUserAccess($user, false, true, true);
if ($user->parent->roles[0]->name != 'admin' || $user->common_access == Users::ACCESS_EMBAGRO
    || $user->roles[0]->name == 'admin'
) {
    unset($responsible_options['no']);
}

if (count($directors) <= 0) {
    unset($responsible_options['director_edit_action']);
}
if (count($managers) <= 0) {
    unset($responsible_options['manager_edit_action']);
}
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'edit-action',
    'htmlOptions' => [
        'class' => 'page-form'
    ]
));

$directors_block_to_display = $action_resp_role->itemname == 'director' ? 'style="display:block"' : '';
$managers_block_to_display = $action_resp_role->itemname == 'manager' ? 'style="display:block"' : '';

$admin = new Users();
$adminId = $admin->getAdminId();
$newResponsible = $user->parent_id != null ? $user->parent_id : $adminId;

$delete_button = CHtml::button("Удалить", array(
    'onClick' => 'window.location.href="' . Yii::app()->createUrl("page/delete_action", array("id" => $actions->id, "render_page" => 'actions_page')) . '"',
    'class' => 'btn',
));

$prior_array = ActionsPriority::model()->findAll();
$priority_selector = '';
foreach ($prior_array as $prior) {
    $priority_selector .= '<option ' . ($actions->action_priority_id == $prior->id ? ' selected="selected"' : '') . ' class="' . $prior->color . '" value="' . $prior->id . '">' . 'Приоритет: ' . $prior->name . '</option>';
};

$statuses_array = ActionsStatuses::model()->findAll();
foreach ($statuses_array as $status) {
    if ($actions->action_status_id == $status->id) {
        $actionStatus = $status;
    }
};

// выбор значения в селекторе
if ($actions->responsable_id == Yii::app()->user->id) {
    $selected_option = array('i' => array('selected' => true));
} elseif ($action_resp_role->itemname == 'director') {
    $selected_option = array('director_edit_action' => array('selected' => true));
} elseif ($action_resp_role->itemname == 'manager') {
    $selected_option = array('manager_edit_action' => array('selected' => true));
} else {
    $selected_option = array('no' => array('selected' => true));
}

$directors_block_to_display = $action_resp_role->itemname == 'director' && $role != 'director' ? 'style="display:block"' : '';
$managers_block_to_display = $action_resp_role->itemname == 'manager' && count($managers) > 0 && key($selected_option) != 'i' ? 'style="display:block"' : '';
?>

<div class="clients-hat">
    <div class="client-name">
        <?
        $action_date = date('Y-m-d', strtotime($actions->action_date)) . ' 23:59:59';
        if (strtotime($action_date) >= time() || $actions->action_status_id != 1) { ?>
            <div class="headerStatusAction"
                 style="background-color: <? echo $actionStatus->color ?>"><? echo $actionStatus->name; ?></div>
        <? } else { ?>
            <div class="headerStatusAction" style="background-color: #FB7192">Просрочено</div>
        <? } ?>
        <?php echo CHtml::link('Задачи  ', array('page/actions_page')); ?>
        <img src="/img/right-arrow-button.svg" alt="">
        <?php
        if ($accessClient) {
            echo CHtml::link($actions->client->name, Yii::app()->createUrl("page/client_profile", array("id" => $actions->client->id)));
        } else {
            echo $actions->client->name;
        }
        ?>
        <img src="/img/right-arrow-button.svg" alt=""><?php echo $actions->text; ?>, #<?php echo $actions->id ?>
    </div>
    <div class="goback-link pull-right">
        <input class="btn_close" type="button" onclick="history.back();" value="❮  Назад "/>
    </div>
</div>


<main class="content full2" role="main">
    <div class="content-edit-block">
        <div class="title_name_1">Задача</div>
        <div class="content-01">

            <? if ($isSuccessSave && !$isShowBlockSave) { ?>
                <script type="module">
                    import {NotificationBar} from '/js/notificationBar.js';

                    let accessClient = <? echo $accessClient?>;
                    let client = <? echo json_encode($actions->client->attributes)?>;
                    const notificationBar = new NotificationBar({
                        type: 'warning',
                        title: 'Задача создана',
                        description: 'Новая задача добавлена для:<br> ' + (accessClient
                            ? '<a href="/page/client_profile/' + client.id + '">' + client.name + '</a>'
                            : client.name)
                    });
                    notificationBar.show();
                </script>
            <? } ?>

            <? if ($isShowBlockSave) { ?>
                <script type="module">
                    import {NotificationBar} from '/js/notificationBar.js';

                    let accessClient = <? echo $accessClient?>;
                    let client = <? echo json_encode($actions->client->attributes)?>;
                    const notificationBar = new NotificationBar({
                        type: 'success',
                        title: 'Задача сохранена',
                        description: 'Информация в задаче изменена<br>Контакт: ' + (accessClient
                            ? '<a href="/page/client_profile/' + client.id + '">' + client.name + '</a>'
                            : client.name)
                    });
                    notificationBar.show();
                </script>
            <? } ?>

            <div class="box-gray__body no-border3 active-pad">
                <div class="client_info">Тема:<span class="star">*</span></div>
                <div class="form-group_actions">
                    <?
                    echo $form->textField($actions, 'text', array('class' => 'form-control', 'placeholder' => 'Что нужно сделать...'));
                    echo $form->error($actions, 'text', array('class' => 'form-error'));
                    ?>
                </div>
                <div class="client_info">Описание:</div>
                <div class="form-group_actions">
                    <?php
                    echo $form->textArea($actions, 'description', array('class' => 'form-control1 editable', 'placeholder' => 'Напишите комментарий...',));
                    ?>
                </div>

                <!-- Файлы в задачах -->
                <?php
                if ($userRight['role'] == 'admin' || $userRight['add_files_action']) {
                    ?>
                    <div class="action_file">

                        <?php
                        $folder = '/uploads/';
                        foreach ($actionFiles as $file) {
                            echo CHtml::link($file->file->name, Yii::app()->createUrl("page/get_file_action", ['id' => $file->id]), ['target' => '_blank']);
                            if ($userRight['role'] == 'admin' || $userRight['delete_files_action']) {
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
                                        'action' => '/page/UploadActionFile?id=' . $actions->id,
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

                    <?
                    echo $form->dropDownList($actions, 'responsable_id', $responsible_options,
                        array('options' => $selected_option, 'class' => 'styled permis editable typeAccess', 'name' => 'type',)) ?>
                    <div class="access-options access-tab"
                         id="director_edit_action" <?php echo $directors_block_to_display ?> >
                        <?php
                        echo $form->dropDownList($actions, 'director_id', CHtml::listData($directors, 'id', 'first_name'),
                            array('options' => array($actions->responsable_id => array('selected' => true)), 'class' => 'styled',));
                        ?>
                    </div>
                    <div class="access-options access-tab"
                         id="manager_edit_action" <?php echo $managers_block_to_display ?> >
                        <?php
                        echo $form->dropDownList($actions, 'manager_id', CHtml::listData($managers, 'id', 'first_name'),
                            array('options' => array($actions->responsable_id => array('selected' => true)), 'class' => 'styled',));
                        ?>
                    </div>
                </div>


                <div class="client_info">
                    <img src="/img/clock.svg" alt="">Дата выполнения:<span class="star">*</span>
                </div>
                <div class="solid_an_client">

                    <?
                    echo $this->widget('ext.CJuiDateTimePicker.CJuiDateTimePicker', array(
                            'name' => 'MainActions[action_date]',
                            'model' => $actions,
                            'attribute' => 'action_date',
                            'language' => 'ru',
                            'htmlOptions' => array(
                                'value' => date('d.m.Y H:i', strtotime($actions->action_date)),
                                'class' => 'form-control editable'
                            ),
                            'options' => array(
                                'dateFormat' => 'dd.mm.yy',
                                'changeMonth' => 'true',
                                'changeYear' => 'true',
                                'showButtonPanel' => true,
                                'beforeShow' => new CJavaScriptExpression('function(element){dataPickerFocus = $(element).attr(\'id\').trim();}')
                            ),

                        ), true) .
                        $form->error($actions, 'action_date', array('class' => 'form-error'))
                    ?>
                </div>

                <!-- Состояния -->
                <? if (count($statuses_array) > 0) { ?>


                    <div class="solid_an_client" style="margin-top: -5px;padding-bottom: 13px;">
                        <div class="client_info">
                            Состояние:
                        </div>
                        <?
                        $selectedStatus = '';
                        foreach ($statuses_array as $status) {
                            if ($actions->action_status_id == $status->id) {
                                $selectedStatus = $status;
                                break;
                            }
                        }
                        ?>

                        <div class="row-input" id="colorSelect" style="display: inline-flex">
                            <div class="jq-selectbox__select color-select client" onclick="showDropDawnColor(event)">
                                <div class="color-block"
                                     style="background-color: <? echo $selectedStatus->color ?>">
                                    <span><? echo $selectedStatus->name ?> </span>
                                    <input type="text" value="<? echo $selectedStatus->id ?>" class="hide"
                                           name="MainActions[action_status_id]">
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

                <? if (count($allLabels) > 0) { ?>
                    <div class="label_info bottom_10">
                        Метки:
                        <a class="delete" id="editLabels" onclick="return false;">Редактировать</a>
                    </div>

                    <div class="solid_an_client">
                        <div class="customDropDownListLabels hide">
                            <ul>
                                <? foreach ($allLabels as $label) { ?>
                                    <li id="labelLi <? echo $label->id ?>" class="labelLi"
                                        name="MainActions[labelLi<? echo $label->id ?>]"
                                        onclick="changeLabel('<? echo $label->id; ?>');">
                                        <?
                                        echo $form->checkBox($actions, "Labels[$label->id]", [
                                            'id' => 'checkbox' . $label->id,
                                            'class' => 'hide',
                                            'checked' => isset($customSelectedLabels[$label->id])
                                        ]);
                                        $operType = isset($customSelectedLabels[$label->id]) ? 'added' : 'deleted';
                                        ?>
                                        <div class="<? echo $operType; ?>" id="blockOper<? echo $label->id; ?>"></div>
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
                            <? }
                            ?>
                        </div>
                    </div>
                <? } ?>

                <div class="solid_an_client">
                    <?php

                    if ($user->roles[0]->name == 'admin' || $userRight['create_action'] == 1) {
                        echo '<div class="form-group_single_row" style="width: 100px;">
				    ' . CHtml::submitButton('Сохранить', array('class' => 'maui_btn', 'id' => 'save')) . '
			        </div>';
                        echo '<div class="form-group_single_row" style="width: 100px;">
				    ' . CHtml::submitButton('Сохранить и создать', array('class' => 'foton_btn', 'name' => 'save_and_create', 'id' => 'save_and_create')) . '
			        </div>';
                    }
                    ?>
                    <div id="preloader" style="margin: 0 auto;"></div>
                </div>
                <div class="client_info">
                    <?
                    if ($user->roles[0]->name == 'admin' || $userRight['delete_action'] == 1) {
                        echo '
			<div class="function-delete" style="display: block;padding-left: 0px;text-align: center;">
				<a class="delete" href="#">Удалить задачу</a>
			</div>
			<div class="function-delete-confirm" style="display: none;">
				<ul class="horizontal_2">
					<li class="big">Подтвердите удаление:</li>
					<li><a href="#"  class="cancel" style="margin-right: 10px;">Отмена</a></li>
					<li style="padding-top: 9px;">' . $delete_button . '</li>
				</ul>
			</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>


    <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script>
        $("#edit-action").submit(function () {
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

        function delDocument(id) {
            if (confirm('Вы действительно хотите удалить файл?')) {
                document.location.href = '/page/action_document_delete/' + id;
            }
        }


        function addFileBlock(json) {
            <?
            if ($userRight['role'] == 'admin' || $userRight['delete_files_action']) {
            ?>
            $("#fileBlock").append(
                '<a target="_blank" href="/page/get_file_action/' + json.fileId + '">' + json.filename + '</a>' +
                '<img class="delDocument_4a" onclick="delDocument(' + json.fileId + ')" src="/img/cancel_newdoc.svg" alt="">' +
                '<br>');
            <?
            } else { ?>
            $("#fileBlock").append(
                '<a target="_blank" href="/page/get_file_action/' + json.fileId + '">' + json.filename + '</a>' +
                '<br>');
            <?
            }
            ?>
            $("li.qq-upload-success").remove();
        }

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
<?php $this->pageTitle = 'Новый пользователь'; ?>
<?php $correct_path = 'http://' . $_SERVER["HTTP_HOST"]; ?>
<link href="/img/lightbox/lightbox.css" rel="stylesheet">


<?php
$rightsDefaultEnabled = [
    'createClient' => $callUserRight == 'admin' || $callUserRight->create_client,
    'deleteClient' => $callUserRight == 'admin' || $callUserRight->delete_client,
    'createAction' => $callUserRight == 'admin' || $callUserRight->create_action,
    'deleteAction' => $callUserRight == 'admin' || $callUserRight->delete_action,
    'createDeals' => $callUserRight == 'admin' || $callUserRight->create_deals,
    'deleteDeals' => $callUserRight == 'admin' || $callUserRight->delete_deals,
    'addFilesAction' => $callUserRight == 'admin' || $callUserRight->add_files_action,
    'addFilesClient' => $callUserRight == 'admin' || $callUserRight->add_files_client,
    'addFilesDeal' => $callUserRight == 'admin' || $callUserRight->add_files_deal,
    'addFilesUser' => $callUserRight == 'admin' || $callUserRight->add_files_user,
    'deleteFilesAction' => $callUserRight == 'admin' || $callUserRight->delete_files_action,
    'deleteFilesClient' => $callUserRight == 'admin' || $callUserRight->delete_files_client,
    'deleteFilesDeal' => $callUserRight == 'admin' || $callUserRight->delete_files_deal,
    'deleteFilesUser' => $callUserRight == 'admin' || $callUserRight->delete_files_user,
];
$statusArray = array('active' => 'Активен', 'none' => 'Не активен', 'limited' => 'Ограничен по ip', 'dismissed' => 'Уволен', 'noActivated' => 'Требует активации');
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'create-user',
    'htmlOptions' => [
        'enctype' => 'multipart/form-data',
        'class' => 'page-form'
    ]
));
?>
<div class="clients-hat">
    <div class="client-name">
        <?php echo CHtml::link('Пользователи', array('page/user_info')); ?>
        <img src="/img/right-arrow-button.svg" alt="">Новый пользователь
    </div>

    <div class="goback-link pull-right">
        <input class="btn_close" type="button" onclick="history.back();" value="❮  Назад "/>
    </div>

</div>

<main class="content full2" role="main">
    <div class="content-edit-block">
        <div class="edit_user_profile">
            <div class="title_name_1">Профиль пользователя</div>
            <div class="additionalFieldTable">
                <div class="profile_edit">
                    <table class="main-table row edit-row">
                        <tbody>
                        <tr>
                            <td class="an_002" width="50">Имя:<span class="star">*</span></td>
                            <td><?php echo $form->textField($user, 'first_name', array('class' => 'form-control', 'placeholder' => 'Имя')); ?>
                                <?php echo $form->error($user, 'first_name', array('class' => 'form-error')); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="an_002" width="50">E-mail:<span class="star">*</span></td>
                            <td><?php echo $form->textField($user, 'email', array('class' => 'form-control', 'placeholder' => 'E-mail')); ?>
                                <?php echo $form->error($user, 'email', array('class' => 'form-error')); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="an_002" width="50">Пароль:<span class="star">*</span></td>
                            <td><?
                                echo $form->textField($user, 'newPassword', array('class' => 'form-control', 'placeholder' => 'Пароль'));
                                echo $form->error($user, 'newPassword', array('class' => 'form-error'));
                                ?>
                            </td>
                        </tr>

                        <?
                        if ($currentRoles == 'admin') { ?>
                            <tr>
                                <td class="an_002" width="50">Тип:</td>
                                <td><?
                                    echo $form->dropDownList($userRole, 'itemname', $roleArray, array(
                                        'class' => 'styled editable typeAccess'));
                                    ?>
                                </td>
                                <td><a class="help_anim" tabindex="1">
                                        <img src="/img/question-mark.svg">
                                        <span class="tip_help">
                                                <div class="help_di" style="margin-top: -15px;">
                                                    <span>Руководитель</span>
                                                    <div>
                                                    - создает контакты, задачи и сделки
                                                    - прикрепляет файлы
                                                    - создает пользователей с типом "Менеджер"
                                                    - изменяет профиль своих менеджеров
                                                    - доступны настройки: поля в анкете контакта, метки, этапы, воронки
                                                    - доступ к контактам своих менеджеров
                                                    - менеджеры "Руководителя" имеют доступ к контактам друг друга
                                                    </div>
                                                </div>
                                                <div class="help_di" style="margin-bottom: -15px;">
                                                    <span>Менеджер</span>
                                                    <div>
                                                    - создает контакты, задачи и сделки
                                                    - прикрепляет файлы
                                                    - не может создавать других пользователей
                                                    - не видит контакты других руководителей и его менеджеров
                                                    - доступны настройки: метки и воронки (если установит Директор)
                                                    </div>
                                                </div>
                                            </span>
                                    </a>
                                </td>
                            </tr>
                            <tr id="selectResponsible"
                                style="display: <? echo $userRole->itemname == 'manager' ? 'block' : 'none' ?>">
                                <td class="an_002" width="50">Отвественный:</td>
                                <td style="white-space: normal;"><?
                                    echo $form->dropDownList($user, 'parent_id', $directorsArray, array(
                                        'class' => 'styled',))
                                    ?>
                                </td>
                            </tr>
                            <?php
                        } ?>
                        <tr>
                            <td class="an_002" width="50">Статус:</td>
                            <td style="white-space: normal;"><? echo $form->dropDownList($user, 'status', $statusArray, array('class' => 'styled')) ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="an_002" width="50">Группа:</td>
                            <td style="white-space: normal;"><?
                                echo $form->dropDownList($user, 'data[group]', $groupArray, array('class' => 'styled'));
                                echo $form->error($user, 'data[group]', array('class' => 'form-error'));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="an_002" width="50">Телефон:</td>
                            <td><?php echo $form->textField($user, 'phone', array('class' => 'form-control', 'placeholder' => 'Телефон')); ?>
                                <?php echo $form->error($user, 'phone', array('class' => 'form-error')); ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="an_002" width="50">Должность:</td>
                            <td><?php echo $form->textField($user, 'position', array('class' => 'form-control', 'placeholder' => 'Должность')); ?>
                                <?php echo $form->error($user, 'position', array('class' => 'form-error')); ?>
                            </td>
                        </tr>


                        <tr>
                            <td class="an_002" width="50">Фото:</td>
                            <td><?
                                echo CHtml::tag('div', ['id' => 'fakeButton', 'class' => 'upload_button_2']);
                                echo 'Зарузить фото';
                                echo CHtml::tag('/div');
                                echo CHtml::tag('div', ['id' => 'fakeButtonNameFile', 'class' => 'fakeButtonAvatarNameFile_2']);
                                echo CHtml::tag('/div');
                                echo CHtml::activeFileField($user, 'image', ['id' => 'loadImage', 'style' => 'display:none']);
                                ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="edit_user">
            <div class="title_name_1" style="border-top: 1px solid #d9d9d9;display: flow-root;">Права доступа
                <div class="more" style="margin-right: 20px;"><img src="/img/external-link-symbol.svg"><a href="https://inclient.ru/add-users-crm/" target="_blank" style="color: #707070;">Подробнее о доступах</a>
                </div>
            </div>
            <div class="additionalFieldTable">
                <div class="profile_edit">
                    <?

                    echo '<div class="form-group_02">
                                    <div class="an_002">Общий доступ:
                                        <a class="help_anim" tabindex="1" style="position: relative;">
                                                <img src="/img/question-mark.svg">
                                                <span class="tip_help">
                                                <div class="help_di">
                                                        <span>Руководитель</span><br>
                                                        <div>
                                                        <strong>Директор</strong>:	видит контакты своих менеджеров и директора<br><br>
                                                        <strong>Только менеджеры</strong>: видит контакты только своих менеджеров<br><br>
                                                        <strong>Запретить</strong>: доступ только к своим контактам<br><br>
                                                        </div>
                                                </div>
                                                <div class="help_di">
                                                        <span>Менеджер</span><br>
                                                        <div>
                                                        <strong>Менеджеры + ответственный</strong>: видит контакты другого менеджера, если у него такой же ответственный. Видит контакты своего ответственного<br><br>
                                                        <strong>Только менеджеры</strong>:  видит контакты другого менеджера, если у него такой же ответственный<br><br>
                                                        <strong>Только ответственный</strong>: видит контакты только своего ответственного<br><br>
                                                        <strong>Запретить</strong>: доступ только к своим контактам
                                                        </div>
                                                </div>
                                                </span>
                                        </a>						
									</div>';

                    echo '<div class="bn_t3 input-radioButton1" id="managerAccess">';

                    echo $form->radioButton($user, 'common_access_manager', array('value' => 1, 'uncheckValue' => null, 'checked' => 'checked'));
                    echo $form->label($user, 'Менеджеры + отвественный', array('class' => 'fr_o'));

                    echo $form->radioButton($user, 'common_access_manager', array('value' => 2, 'uncheckValue' => null));
                    echo $form->label($user, 'Только менеджеры', array('class' => 'fr_o'));

                    echo $form->radioButton($user, 'common_access_manager', array('value' => 3, 'uncheckValue' => null));
                    echo $form->label($user, 'Только отвественный', array('class' => 'fr_o'));

                    echo $form->radioButton($user, 'common_access_manager', array('value' => 5, 'uncheckValue' => null));
                    echo $form->label($user, 'Запретить', array('class' => 'fr_o'));

                    echo '</div>';

                    echo '<div class="bn_t3 input-radioButton1" id="directorAccess">';


                    echo $form->radioButton($user, 'common_access_director', array('value' => 4, 'uncheckValue' => null, 'checked' => 'checked'));
                    echo $form->label($user, 'Директор', array('class' => 'fr_o'));

                    echo $form->radioButton($user, 'common_access_director', array('value' => 2, 'uncheckValue' => null));
                    echo $form->label($user, 'Только менеджеры', array('class' => 'fr_o'));

                    echo $form->radioButton($user, 'common_access_director', array('value' => 5, 'uncheckValue' => null));
                    echo $form->label($user, 'Запретить', array('class' => 'fr_o'));

                    echo '</div>';
                    echo '</div>';
                    echo '<div class="form-group_02">
								    <div class="an_002">Контакты:</div>';
                    echo '<div class="bn_t3">';
                    echo $form->checkBox($newUserRight, 'create_client', array('class' => 'form-control_1 checkBox',
                        'checked' => $rightsDefaultEnabled['createClient'],
                        'disabled' => $callUserRight != 'admin' && !$callUserRight->create_client));
                    echo $form->label($newUserRight, 'Редактирование', array('class' => 'fr_o'));

                    echo $form->checkBox($newUserRight, 'delete_client', array('class' => 'form-control_1 checkBox',
                        'checked' => $rightsDefaultEnabled['deleteClient'],
                        'disabled' => $callUserRight != 'admin' && !$callUserRight->delete_client));
                    echo $form->label($newUserRight, 'Удаление', array('class' => 'fr_o'));

                    echo '</div>';
                    echo '</div>';

                    echo '<div class="form-group_02">
                                    <div class="an_002">Задачи:</div>';
                    echo '<div class="bn_t3">';
                    echo $form->checkBox($newUserRight, 'create_action', array('class' => 'form-control_1 checkBox',
                        'checked' => $rightsDefaultEnabled['createAction'],
                        'disabled' => $callUserRight != 'admin' && !$callUserRight->create_action));
                    echo $form->label($newUserRight, 'Редактирование', array('class' => 'fr_o'));

                    echo $form->checkBox($newUserRight, 'delete_action', array('class' => 'form-control_1 checkBox',
                        'checked' => $rightsDefaultEnabled['deleteAction'],
                        'disabled' => $callUserRight != 'admin' && !$callUserRight->delete_action));
                    echo $form->label($newUserRight, 'Удаление задач', array('class' => 'fr_o'));

                    echo '</div>';
                    echo '</div>';

                    echo '<div class="form-group_02">
                                    <div class="an_002">Сделки:</div>';
                    echo '<div class="bn_t3">';
                    echo $form->checkBox($newUserRight, 'create_deals', array('class' => 'form-control_1 checkBox',
                        'checked' => $rightsDefaultEnabled['createDeals'],
                        'disabled' => $callUserRight != 'admin' && !$callUserRight->create_deals));
                    echo $form->label($newUserRight, 'Редактирование', array('class' => 'fr_o'));

                    echo $form->checkBox($newUserRight, 'delete_deals', array('class' => 'form-control_1 checkBox',
                        'checked' => $rightsDefaultEnabled['deleteDeals'],
                        'disabled' => $callUserRight != 'admin' && !$callUserRight->delete_deals));
                    echo $form->label($newUserRight, 'Удаление сделки', array('class' => 'fr_o'));

                    echo '</div>';
                    echo '</div>';

                    if ($currentRoles == 'admin') {
                        echo '<div class="form-group_02" id="fieldRight">
                                    <div class="an_002">Поля в анкете контакта:</div>';
                        echo '<div class="bn_t3">';
                        echo $form->checkBox($newUserRight, 'create_field', array('class' => 'form-control_1 checkBox', 'disabled' => $callUserRight != 'admin' && !$callUserRight->create_field));
                        echo $form->label($newUserRight, 'Создание', array('class' => 'fr_o'));

                        echo $form->checkBox($newUserRight, 'delete_field', array('class' => 'form-control_1 checkBox', 'disabled' => $callUserRight != 'admin' && !$callUserRight->delete_field));
                        echo $form->label($newUserRight, 'Удаление полей', array('class' => 'fr_o'));

                        echo $form->checkBox($newUserRight, 'delete_section', array('class' => 'form-control_1 checkBox', 'disabled' => $callUserRight != 'admin' && !$callUserRight->delete_section));
                        echo $form->label($newUserRight, 'Удаление разделов', array('class' => 'fr_o'));

                        echo '</div>';
                        echo '</div>';
                    }

                    echo '<div class="form-group_02">
                                    <div class="an_002">Добавление файлов:</div>';
                    echo '<div class="bn_t3">';
                    echo $form->checkBox($newUserRight, 'add_files_client', array('class' => 'form-control_1 checkBox',
                        'checked' => $rightsDefaultEnabled['addFilesClient'],
                        'disabled' => $callUserRight != 'admin' && !$callUserRight->add_files_client));
                    echo $form->label($newUserRight, 'Контакты', array('class' => 'fr_o'));

                    echo $form->checkBox($newUserRight, 'add_files_action', array('class' => 'form-control_1 checkBox',
                        'checked' => $rightsDefaultEnabled['addFilesAction'],
                        'disabled' => $callUserRight != 'admin' && !$callUserRight->add_files_action));
                    echo $form->label($newUserRight, 'Задачи', array('class' => 'fr_o'));


                    echo $form->checkBox($newUserRight, 'add_files_deal', array('class' => 'form-control_1 checkBox',
                        'checked' => $rightsDefaultEnabled['addFilesDeal'],
                        'disabled' => $callUserRight != 'admin' && !$callUserRight->add_files_deal));
                    echo $form->label($newUserRight, 'Сделки', array('class' => 'fr_o'));

                    if ($currentRoles == 'admin') {
                        echo '<div id="rightUserFileAdd" class="rightUserFileDel">';
                        echo $form->checkBox($newUserRight, 'add_files_user', array('class' => 'form-control_1 checkBox',
                            'checked' => $rightsDefaultEnabled['addFilesUser'],
                            'disabled' => $callUserRight != 'admin' && !$callUserRight->add_files_user));
                        echo $form->label($newUserRight, 'Пользователи', array('class' => 'fr_o'));

                        echo '</div>';

                    }
                    echo '</div>';
                    echo '</div>';

                    echo '<div class="form-group_02">
                                    <div class="an_002">Удаление файлов:</div>';
                    echo '<div class="bn_t3">';
                    echo $form->checkBox($newUserRight, 'delete_files_client', array('class' => 'form-control_1 checkBox',
                        'checked' => $rightsDefaultEnabled['deleteFilesClient'],
                        'disabled' => $callUserRight != 'admin' && !$callUserRight->delete_files_client));
                    echo $form->label($newUserRight, 'Контакты', array('class' => 'fr_o'));

                    echo $form->checkBox($newUserRight, 'delete_files_action', array('class' => 'form-control_1 checkBox',
                        'checked' => $rightsDefaultEnabled['deleteAction'],
                        'disabled' => $callUserRight != 'admin' && !$callUserRight->delete_files_action));
                    echo $form->label($newUserRight, 'Задачи', array('class' => 'fr_o'));

                    echo $form->checkBox($newUserRight, 'delete_files_deal', array('class' => 'form-control_1 checkBox',
                        'checked' => $rightsDefaultEnabled['deleteFilesDeal'],
                        'disabled' => $callUserRight != 'admin' && !$callUserRight->delete_files_deal,));
                    echo $form->label($newUserRight, 'Сделки', array('class' => 'fr_o'));

                    if ($currentRoles == 'admin') {
                        echo '<div id="rightUserFileDel" class="rightUserFileDel">';
                        echo $form->checkBox($newUserRight, 'delete_files_user', array('class' => 'form-control_1 checkBox',
                            'checked' => $rightsDefaultEnabled['deleteFilesUser'],
                            'disabled' => $callUserRight != 'admin' && !$callUserRight->delete_files_user));
                        echo $form->label($newUserRight, 'Пользователи', array('class' => 'fr_o'));

                        echo '</div>';
                    }
                    echo '</div>';
                    echo '</div>';

                    if ($currentRoles == 'admin') {
                        echo '<div class="form-group_02" id="createLabelRight">
                                        <div class="an_002">Создание меток:</div>';
                        echo '<div class="bn_t3">';
                        echo $form->checkBox($newUserRight, 'create_label_clients', array('class' => 'form-control_1 checkBox', 'disabled' => $callUserRight != 'admin' && !$callUserRight->create_label_clients));
                        echo $form->label($newUserRight, 'Контакты', array('class' => 'fr_o'));

                        echo $form->checkBox($newUserRight, 'create_label_actions', array('class' => 'form-control_1 checkBox', 'disabled' => $callUserRight != 'admin' && !$callUserRight->create_label_actions));
                        echo $form->label($newUserRight, 'Задачи', array('class' => 'fr_o'));

                        echo $form->checkBox($newUserRight, 'create_label_deals', array('class' => 'form-control_1 checkBox', 'disabled' => $callUserRight != 'admin' && !$callUserRight->create_label_deals));
                        echo $form->label($newUserRight, 'Сделки', array('class' => 'fr_o'));

                        echo '</div>';
                        echo '</div>';

                        echo '<div class="form-group_02" id="deleteLabelRight">
                                    <div class="an_002">Удаление меток:</div>';
                        echo '<div class="bn_t3">';
                        echo $form->checkBox($newUserRight, 'delete_label_clients', array('class' => 'form-control_1 checkBox',));
                        echo $form->label($newUserRight, 'Контакты', array('class' => 'fr_o'));

                        echo $form->checkBox($newUserRight, 'delete_label_actions', array('class' => 'form-control_1 checkBox',));
                        echo $form->label($newUserRight, 'Задачи', array('class' => 'fr_o'));

                        echo $form->checkBox($newUserRight, 'delete_label_deals', array('class' => 'form-control_1 checkBox',));
                        echo $form->label($newUserRight, 'Сделки', array('class' => 'fr_o'));

                        echo '</div>';
                        echo '</div>';


                        echo '<div class="form-group_02" id="blockStepRight">
                                    <div class="an_002">Воронки:</div>';
                        echo '<div class="bn_t3">';
                        echo $form->checkBox($newUserRight, 'create_steps', array('class' => 'form-control_1 checkBox',));
                        echo $form->label($newUserRight, 'Создание', array('class' => 'fr_o'));

                        echo $form->checkBox($newUserRight, 'delete_steps', array('class' => 'form-control_1 checkBox',));
                        echo $form->label($newUserRight, 'Удаление', array('class' => 'fr_o'));

                        echo '</div>';
                        echo '</div>';

                    }
                    ?>


                </div>
            </div>
        </div>
    </div>

    <div class="box-gray111 width-static">
        <div class="edit_user_1anketa">
            <div class="title_name_2">Управление</div>
            <div class="popup__form_anketa" style="padding-top: 20px;">
                <div class="imgavatar" style="margin-bottom: 20px;">
                    <img src="/img/user_new.svg" style="height: 110px;">
                </div>

                <?php echo CHtml::submitButton('Создать пользователя', array('class' => 'maui_btn', 'id' => 'create_user_button')); ?>
                <div id="preloader" style="margin: 0 auto;"></div>
            </div>
        </div>
    </div>
</main>


<?php $this->endWidget(); ?>

<script>
    var currentRoles = "<? echo $currentRoles?>";
    $("#UsersRoles_itemname").change(function () {
        checkRole();
        enabledDefaultRights();
        if ($("#UsersRoles_itemname").val() == 'manager') {
            $("#selectResponsible").slideDown();
        } else {
            $("#selectResponsible").slideUp();
        }
    });

    var enabledDefaultRights = function () {
        var rightsDefaultEnabled = <?echo json_encode($rightsDefaultEnabled)?>;
        $("#UserRight_create_action").prop("checked", rightsDefaultEnabled.createAction);
        $("#UserRight_create_deals").prop("checked", rightsDefaultEnabled.createDeals);
        $("#UserRight_create_client").prop("checked", rightsDefaultEnabled.createClient);
        $("#UserRight_delete_action").prop("checked", rightsDefaultEnabled.deleteAction).removeAttr("disabled");
        $("#UserRight_delete_deals").prop("checked", rightsDefaultEnabled.deleteDeals).removeAttr("disabled");
        $("#UserRight_delete_client").prop("checked", rightsDefaultEnabled.deleteClient).removeAttr("disabled");

        $("#UserRight_add_files_action").prop("checked", rightsDefaultEnabled.addFilesAction).removeAttr("disabled");
        $("#UserRight_add_files_deal").prop("checked", rightsDefaultEnabled.addFilesDeal).removeAttr("disabled");
        $("#UserRight_add_files_client").prop("checked", rightsDefaultEnabled.addFilesClient).removeAttr("disabled");
        $("#UserRight_add_files_user").prop("checked", rightsDefaultEnabled.addFilesUser).removeAttr("disabled");

        $("#UserRight_delete_files_action").prop("checked", rightsDefaultEnabled.deleteFilesAction).removeAttr("disabled");
        $("#UserRight_delete_files_deal").prop("checked", rightsDefaultEnabled.deleteFilesDeal).removeAttr("disabled");
        $("#UserRight_delete_files_client").prop("checked", rightsDefaultEnabled.deleteFilesClient).removeAttr("disabled");
        $("#UserRight_delete_files_user").prop("checked", rightsDefaultEnabled.deleteFilesUser).removeAttr("disabled");
    };

    changeRightAction = function () {
        if ($("#UserRight_create_action").prop("checked")) {
            <? if ($currentRoles == 'admin' || $callUserRight->delete_action)  {?>
            $("#UserRight_delete_action").removeAttr("disabled");
            <? }?>
            <? if ($currentRoles == 'admin' || $callUserRight->add_files_action)  {?>
            $("#UserRight_add_files_action").removeAttr("disabled");
            <? }?>

            <? if ($currentRoles == 'admin') {?>
            $("#UserRight_create_label_actions").removeAttr("disabled");
            <? }?>

        } else {
            $("#UserRight_delete_action").prop("checked", false).attr("disabled", "disabled");
            $("#UserRight_delete_files_action").prop("checked", false).attr("disabled", "disabled");
            $("#UserRight_add_files_action").prop("checked", false).attr("disabled", "disabled");
            $("#UserRight_create_label_actions").prop("checked", false).attr("disabled", "disabled");
            $("#UserRight_delete_label_actions").prop("checked", false).attr("disabled", "disabled");
        }

    };

    changeRightDeals = function () {
        if ($("#UserRight_create_deals").prop("checked")) {
            <? if ($currentRoles == 'admin' || $callUserRight->delete_deals)  {?>
            $("#UserRight_delete_deals").removeAttr("disabled");
            <? }?>
            <? if ($currentRoles == 'admin' || $callUserRight->add_files_deal)  {?>
            $("#UserRight_add_files_deal").removeAttr("disabled");
            <? }?>

            <? if ($currentRoles == 'admin') {?>
            $("#UserRight_create_label_deals").removeAttr("disabled");
            <? }?>
        } else {
            $("#UserRight_delete_deals").prop("checked", false).attr("disabled", "disabled");
            $("#UserRight_add_files_deal").prop("checked", false).attr("disabled", "disabled");
            $("#UserRight_delete_files_deal").prop("checked", false).attr("disabled", "disabled");
            $("#UserRight_create_label_deals").prop("checked", false).attr("disabled", "disabled");
            $("#UserRight_delete_label_deals").prop("checked", false).attr("disabled", "disabled");
        }
    };

    changeRightClient = function () {
        if ($("#UserRight_create_client").prop("checked")) {
            <? if ($currentRoles == 'admin' || $callUserRight->add_files_client) {?>
            $("#UserRight_add_files_client").removeAttr("disabled");
            <? }?>

            <? if ($currentRoles == 'admin' || $callUserRight->delete_client)  {?>
            $("#UserRight_delete_client").removeAttr("disabled");
            <? }?>

            <? if ($currentRoles == 'admin') {?>
            $("#UserRight_create_label_clients").removeAttr("disabled");
            <? }?>
        } else {
            $("#UserRight_add_files_client").prop("checked", false).attr("disabled", "disabled");
            $("#UserRight_delete_files_client").prop("checked", false).attr("disabled", "disabled");
            $("#UserRight_create_label_clients").prop("checked", false).attr("disabled", "disabled");
            $("#UserRight_delete_label_clients").prop("checked", false).attr("disabled", "disabled");
            $("#UserRight_delete_client").prop("checked", false).attr("disabled", "disabled");
        }
    };

    changeRightFields = function () {

        if ($("#UserRight_create_field").prop("checked")) {
            <? if ($currentRoles == 'admin' || $callUserRight->delete_field)  {?>
            $("#UserRight_delete_field").removeAttr("disabled");
            <? }?>
            <? if ($currentRoles == 'admin' || $callUserRight->delete_section)  {?>
            $("#UserRight_delete_section").removeAttr("disabled");
            <? }?>
        } else {
            $("#UserRight_delete_field").prop("checked", false).attr("disabled", "disabled");
            $("#UserRight_delete_section").prop("checked", false).attr("disabled", "disabled");
        }

        <? if ($currentRoles != 'admin' && !$callUserRight->create_field)  {?>
        $("#UserRight_create_field").prop("checked", false).attr("disabled", "disabled");
        <? }?>
    };

    changeRightDocuments = function () {
        if ($("#UserRight_add_files_client").prop("checked")) {
            <? if ($currentRoles == 'admin' || $callUserRight->delete_files_client)  {?>
            $("#UserRight_delete_files_client").removeAttr("disabled");
            <? }?>
        } else {
            $("#UserRight_delete_files_client").prop("checked", false).attr("disabled", "disabled");
        }

        if ($("#UserRight_add_files_action").prop("checked")) {
            <? if ($currentRoles == 'admin' || $callUserRight->delete_files_action)  {?>
            $("#UserRight_delete_files_action").removeAttr("disabled");
            <? }?>
        } else {
            $("#UserRight_delete_files_action").prop("checked", false).attr("disabled", "disabled");
        }

        if ($("#UserRight_add_files_deal").prop("checked")) {
            <? if ($currentRoles == 'admin' || $callUserRight->delete_files_deal)  {?>
            $("#UserRight_delete_files_deal").removeAttr("disabled");
            <? }?>
        } else {
            $("#UserRight_delete_files_deal").prop("checked", false).attr("disabled", "disabled");
        }

        if ($("#UserRight_add_files_user").prop("checked")) {
            <? if ($currentRoles == 'admin' || $callUserRight->delete_files_user)  {?>
            $("#UserRight_delete_files_user").removeAttr("disabled");
            <? }?>
        } else {
            $("#UserRight_delete_files_user").prop("checked", false).attr("disabled", "disabled");
        }
    };

    changeRightLabelClient = function () {
        if ($("#UserRight_create_label_clients").is(':checked')) {
            $("#UserRight_delete_label_clients").removeAttr("disabled");
        } else {
            $("#UserRight_delete_label_clients").prop("checked", false).attr("disabled", "disabled");
        }
    };

    changeRightLabelAction = function () {
        if ($("#UserRight_create_label_actions").is(':checked')) {
            $("#UserRight_delete_label_actions").removeAttr("disabled");
        } else {
            $("#UserRight_delete_label_actions").prop("checked", false).attr("disabled", "disabled");
        }
    };

    changeRightLabelDeal = function () {
        if ($("#UserRight_create_label_deals").is(':checked')) {
            $("#UserRight_delete_label_deals").removeAttr("disabled");
        } else {
            $("#UserRight_delete_label_deals").prop("checked", false).attr("disabled", "disabled");
        }
    };

    changeRightSteps = function () {
        if ($("#UserRight_create_steps").is(':checked')) {
            $("#UserRight_delete_steps").removeAttr("disabled");
        } else {
            $("#UserRight_delete_steps").prop("checked", false).attr("disabled", "disabled");
        }
    };

    $(":checkbox[name='UserRight[create_client]']").change(function () {
        changeRightClient();
    });

    $(":checkbox[name='UserRight[create_action]']").change(function () {
        changeRightAction();
    });

    $(":checkbox[name='UserRight[delete_action]']").change(function () {
        changeRightAction();
    });

    $(":checkbox[name='UserRight[create_deals]']").change(function () {
        changeRightDeals();
    });

    $(":checkbox[name='UserRight[delete_deals]']").change(function () {
        changeRightDeals();
    });

    $(":checkbox[name='UserRight[create_field]']").change(function () {
        changeRightFields();
    });

    $(":checkbox[name='UserRight[add_files_client]']").change(function () {
        changeRightDocuments();
    });

    $(":checkbox[name='UserRight[add_files_deal]']").change(function () {
        changeRightDocuments();
    });

    $(":checkbox[name='UserRight[add_files_action]']").change(function () {
        changeRightDocuments();
    });

    $(":checkbox[name='UserRight[add_files_user]']").change(function () {
        changeRightDocuments();
    });

    $('#UserRight_create_label_clients').change(function () {
        changeRightLabelClient();
    });

    $('#UserRight_create_label_actions').change(function () {
        changeRightLabelAction();
    });

    $('#UserRight_create_label_deals').change(function () {
        changeRightLabelDeal();
    });

    $('#UserRight_create_steps').change(function () {
        changeRightSteps();
    });

    checkRole = function () {
        if ($("#UsersRoles_itemname").val() == 'director') {
            $("#fieldRight").show();
            $("#rightUserFileAdd").show();
            $("#rightUserFileDel").show();
            $("#directorAccess").show();
            $("#managerAccess").hide();
        }

        if ($("#UsersRoles_itemname").val() == 'manager' || currentRoles == 'director') {
            $("#fieldRight").hide();
            $("#rightUserFileAdd").hide();
            $("#rightUserFileDel").hide();
            $("#directorAccess").hide();
            $("#managerAccess").show();
        }
    };

    changeRightClient();
    changeRightAction();
    changeRightDeals();
    changeRightFields();
    changeRightDocuments();
    checkRole();
    changeRightLabelClient();
    changeRightLabelAction();
    changeRightLabelDeal();
    changeRightSteps();
</script>
<script src="/img/lightbox/lightbox.js"></script>
<script>
    $("#create-user").submit(function () {
        $("#preloader").addClass('preloader');
        $("#save").hide();
        $("#create_user_button").hide();
    });

    $("#fakeButton").click(function () {
        $("#loadImage").click();
    })

    document.getElementById('loadImage').onchange = function () {
        if (this.files[0]) // если выбрали файл
            console.log(document.getElementById('loadImage').files[0].name)
        document.getElementById('fakeButtonNameFile').innerHTML = this.files[0].name;

    };
</script>
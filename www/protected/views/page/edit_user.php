<?php $this->pageTitle = $targetUser->first_name . ' | Пользователи'; ?>
<?php $correct_path = 'http://' . $_SERVER["HTTP_HOST"]; ?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'edit_user',
    'enableAjaxValidation' => false,
    'htmlOptions' => [
        'enctype' => 'multipart/form-data',
        'class' => 'page-form'
    ]
));
?>

<div class="clients-hat">
    <div class="client-name">

        <?php
        if ($callUserRole != 'manager') {
            echo CHtml::link('Пользователи', array('page/user_info'));
        } else {
            echo 'Пользователи';
        }
        ?>
        <img src="/img/right-arrow-button.svg" alt="">
        <?php
        $name = Users::getRole($targetUser->roles[0]->name);
        if ($targetUser->roles[0]->name !== 'admin' && $callUserRole == 'admin') {
            echo CHtml::link($name, array("page/user_info?roleFilter=" . $targetUser->roles[0]->name));
        } else {
            echo $name;
        }
        ?>

        <img src="/img/right-arrow-button.svg" alt="">
        <?php echo CHtml::link($targetUser->first_name, Yii::app()->createUrl("page/user_profile", array("id" => $targetUser->id))); ?>
        , #<?php echo $targetUser->id; ?>

    </div>
    <div class="goback-link pull-right"></div>
</div>

<main class="content full2" role="main">
    <div class="content-edit-block">
        <div class="edit_user_profile">
            <div class="title_name_1">Редактирование профиля</div>
            <? if ($isShowBlockSave) { ?>
                <script type="module">
                    import {NotificationBar} from '/js/notificationBar.js';

                    let newActivation = <? echo '"' . $newActivation . '"'?>;
                    let targetUser = <? echo json_encode($targetUser->attributes)?>;
                    const notificationBar = new NotificationBar({
                        type: 'success',
                        title: 'Сохранено',
                        description: 'Профиль ' + '<a href="/page/user_profile/' + targetUser.id + '">' + targetUser.first_name + '</a> обновлен ' + (newActivation
                            ? '<br> Письмо активации отправлено на ' + targetUser.email
                            : '')
                    });
                    notificationBar.show();
                </script>
            <? } ?>

            <div class="gud hide">

                <div class="flex">
                    <img src="/img/gud.svg" alt="">
                    <div class="line_height_1_5"><strong>Пароль отправлен.</strong><br>Новый пароль отправлен на
                        почту <strong><? echo $targetUser->email ?></strong></div>
                </div>
            </div>

            <div class="additionalFieldTable">
                <div class="profile_edit">
                    <div class="error-message hide">
                        <div class="flex">
                            <img src="/img/not_gud.svg" alt="">
                            <div class="line_height_1_5"><strong>Ошибка</strong><br>Проверьте настройки почтового
                                ящика в local.php
                            </div>
                        </div>
                    </div>
                    <table class="main-table row edit-row">
                        <tbody>
                        <tr>
                            <td class="an_002" width="50">Имя:<span class="star">*</span></td>
                            <td class="editable"><?
                                echo $form->textField($targetUser, 'first_name', array('class' => 'form-control', 'placeholder' => 'Имя'));
                                echo $form->error($targetUser, 'first_name', array('class' => 'form-error'));
                                ?>
                            </td>

                        </tr>
                        <tr>
                            <td class="an_002" width="50">E-mail:<span class="star">*</span></td>
                            <td class="editable"><?
                                echo $form->textField($targetUser, 'email', array('class' => 'form-control', 'placeholder' => 'E-mail'));
                                echo $form->error($targetUser, 'email', array('class' => 'form-error'));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="an_002" width="50">Пароль:</td>
                            <td class="editable"><?
                                echo $form->textField($targetUser, 'newPassword', array('class' => 'form-control', 'placeholder' => 'Пароль'));
                                echo $form->error($targetUser, 'newPassword', array('class' => 'form-error'));
                                ?>
                            </td>
                        </tr>


                        <? if ($callUserRole == 'admin' || $isProfile && ($callUserRole != 'manager')) { ?>
                            <tr>
                                <td class="an_002" width="50">Тип:</td>
                                <td><?
                                    echo $roleArray[$targetUserRole->itemname];
                                    ?>
                                    <a class="help_anim" tabindex="1">
                                        <img src="/img/question-mark.svg">
                                        <span class="tip_help">
                                                <div class="help_di" style="margin-top: -15px;">
                                                    <span>Директор</span>
                                                    <div>
                                                    - полный доступ
                                                    - невозможно ограничить
                                                    - только один
                                                    </div>
                                                </div>
                                                <div class="help_di">
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
                            <?php
                        }
                        ?>
                        <?
                        if ($callUserRole == 'admin') { ?>
                            <?php if ($targetUserRole->itemname == 'manager') { ?>
                                <tr>
                                    <td class="an_002" width="50">Отвественный:</td>
                                    <td><?
                                        echo $form->dropDownList($targetUser, 'parent_id', $directorsArray, array(
                                            'class' => 'styled',))
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>

                            <tr>
                                <td class="an_002" width="50">Группа:</td>
                                <td style="white-space: normal;"><?
                                    echo $form->dropDownList($targetUser, 'data[group]', $groupArray, array('class' => 'styled'));
                                    echo $form->error($targetUser, 'data[group]', array('class' => 'form-error'));
                                    ?>
                                </td>
                            </tr>
                        <? } ?>
                        <? if ($callUserRole == 'admin' || !$isProfile) { ?>
                            <tr>
                                <td class="an_002" width="50">Статус:</td>
                                <td style="white-space: normal;"><?
                                    echo $form->dropDownList($targetUser, 'status', $statusArray, array('class' => 'styled'))
                                    ?>
                                </td>
                            </tr>
                        <? } ?>

                        <tr>
                            <td class="an_002" width="50">Телефон:</td>
                            <td class="editable"><?
                                echo $form->textField($targetUser, 'phone', array('class' => 'form-control', 'placeholder' => 'Телефон'));
                                echo $form->error($targetUser, 'phone', array('class' => 'form-error'));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="an_002" width="50">Должность:</td>
                            <td class="editable"><?
                                echo $form->textField($targetUser, 'position', array('class' => 'form-control', 'placeholder' => 'Должность'));
                                echo $form->error($targetUser, 'position', array('class' => 'form-error'));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="an_002">Фото:</td>
                            <td style="display: flex;"><?
                                echo CHtml::tag('div', ['id' => 'fakeButton', 'class' => 'upload_button_2']);
                                echo 'Зарузить фото';
                                echo CHtml::tag('/div');
                                echo CHtml::tag('div', ['id' => 'fakeButtonNameFile', 'class' => 'fakeButtonAvatarNameFile']);
                                echo CHtml::tag('/div');
                                echo CHtml::activeFileField($targetUser, 'image', ['id' => 'loadImage', 'style' => 'display:none']);
                                echo $form->hiddenField($targetUser, 'avatar');

                                echo CHtml::tag('div', ['id' => 'fakeButtonDel', 'class' => 'fakeButtonAvatarDel', 'style' => isset($targetUser->avatar) ?: 'display:none']);
                                echo 'Удалить';
                                echo CHtml::tag('/div');
                                echo CHtml::button('Удалить', ['id' => 'avatarDelete', 'style' => 'display:none']);
                                ?>
                            </td>
                            <td>
                                <? if ($targetUser->avatar) { ?>

                                    <?php
                                }
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
                <span class="more" style="margin-right: 20px;"><img src="/img/external-link-symbol.svg"><a
                            href="https://inclient.ru/add-users-crm/" target="_blank" style="color: #707070;">Подробнее
                        о доступах</a>
                </span>
            </div>
            <?
            if ($targetUserRole->itemname != 'admin') { ?>
                <div class="additionalFieldTable">
                    <div class="profile_edit" id="blockRight">
                        <div>
                            <?
                            if ($targetUser->id != $callUser->id) {
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


                                echo '<div class="bn_t3 input-radioButton1">';
                                if ($targetUserRole->itemname == 'manager') {
                                    echo $form->radioButton($targetUser, 'common_access', array('value' => 1, 'uncheckValue' => null));
                                    echo $form->label($targetUser, 'Менеджеры + отвественный', array('class' => 'fr_o'));
                                    echo $form->radioButton($targetUser, 'common_access', array('value' => 2, 'uncheckValue' => null));
                                    echo $form->label($targetUser, 'Только менеджеры', array('class' => 'fr_o'));

                                    echo $form->radioButton($targetUser, 'common_access', array('value' => 3, 'uncheckValue' => null));
                                    echo $form->label($targetUser, 'Только отвественный', array('class' => 'fr_o'));
                                    echo $form->radioButton($targetUser, 'common_access', array('value' => 5, 'uncheckValue' => null));
                                    echo $form->label($targetUser, 'Запретить', array('class' => 'fr_o'));
                                } elseif ($targetUserRole->itemname == 'director') {
                                    echo $form->radioButton($targetUser, 'common_access', array('value' => 4, 'uncheckValue' => null));
                                    echo $form->label($targetUser, 'Директор', array('class' => 'fr_o'));

                                    echo $form->radioButton($targetUser, 'common_access', array('value' => 2, 'uncheckValue' => null));
                                    echo $form->label($targetUser, 'Только менеджеры', array('class' => 'fr_o'));

                                    echo $form->radioButton($targetUser, 'common_access', array('value' => 5, 'uncheckValue' => null));
                                    echo $form->label($targetUser, 'Запретить', array('class' => 'fr_o'));

                                }

                                echo '</div>';
                                echo '</div>';
                            }
                            echo '<div class="form-group_02">
                                    <div class="an_002">Контакты:</div>';

                                echo '<div class="bn_t3">';
                                echo $form->checkBox($targetUserRight, 'create_client', array('class' => 'form-control_1 checkBox', 'disabled' => ($callUserRole != 'admin' && !$callUserRight->create_client) || $isProfile));
                                echo $form->label($targetUserRight, 'Редактирование', array('class' => 'fr_o'));

                                echo $form->checkBox($targetUserRight, 'delete_client', array('class' => 'form-control_1 checkBox', 'disabled' => ($callUserRole != 'admin' && !$callUserRight->delete_client) || $isProfile));
                                echo $form->label($targetUserRight, 'Удаление', array('class' => 'fr_o'));
                                echo '</div>';
                            echo '</div>';

                            echo '<div class="form-group_02">
									<div class="an_002">Задачи:</div>';

                                    echo '<div class="bn_t3">';
                                    echo $form->checkBox($targetUserRight, 'create_action', array('class' => 'form-control_1 checkBox', 'disabled' => ($callUserRole != 'admin' && !$callUserRight->create_action) || $isProfile));
                                    echo $form->label($targetUserRight, 'Редактирование', array('class' => 'fr_o'));

                                    echo $form->checkBox($targetUserRight, 'delete_action', array('class' => 'form-control_1 checkBox', 'disabled' => ($callUserRole != 'admin' && !$callUserRight->delete_action) || $isProfile));
                                    echo $form->label($targetUserRight, 'Удаление задач', array('class' => 'fr_o'));

                                    echo '</div>';
                            echo '</div>';

                            echo '<div class="form-group_02">
									<div class="an_002">Сделки:</div>';

                                    echo '<div class="bn_t3">';
                                    echo $form->checkBox($targetUserRight, 'create_deals', array('class' => 'form-control_1 checkBox', 'disabled' => ($callUserRole != 'admin' && !$callUserRight->create_deals) || $isProfile));
                                    echo $form->label($targetUserRight, 'Редактирование', array('class' => 'fr_o'));
                                    echo $form->checkBox($targetUserRight, 'delete_deals', array('class' => 'form-control_1 checkBox', 'disabled' => ($callUserRole != 'admin' && !$callUserRight->delete_deals) || $isProfile));
                                    echo $form->label($targetUserRight, 'Удаление сделки', array('class' => 'fr_o'));
                                    echo '</div>';
                            echo '</div>';
                            /*echo '</div>';*/

                            if ($callUserRole == 'admin' && $targetUserRole->itemname == 'director') {
                                echo '<div class="form-group_02" id="fieldRight">
										<div class="an_002">Поля в анкете контакта:</div>';

                                        echo '<div class="bn_t3">';
                                        echo $form->checkBox($targetUserRight, 'create_field', array('class' => 'form-control_1 checkBox', 'disabled' => ($callUserRole != 'admin' && !$callUserRight->create_deals) || $isProfile));
                                        echo $form->label($targetUserRight, 'Создание', array('class' => 'fr_o'));
                                        echo $form->checkBox($targetUserRight, 'delete_field', array('class' => 'form-control_1 checkBox', 'disabled' => ($callUserRole != 'admin' && !$callUserRight->delete_field) || $isProfile));
                                        echo $form->label($targetUserRight, 'Удаление', array('class' => 'fr_o'));
                                        echo $form->checkBox($targetUserRight, 'delete_section', array('class' => 'form-control_1 checkBox', 'disabled' => ($callUserRole != 'admin' && !$callUserRight->delete_section) || $isProfile));
                                        echo $form->label($targetUserRight, 'Удаление разделов', array('class' => 'fr_o'));
                                        echo '</div>';
                                echo '</div>';
                            }

                            echo '<div class="form-group_02">
                                    <div class="an_002">Добавление файлов:</div>';
                                    echo '<div class="bn_t3">';
                                    echo $form->checkBox($targetUserRight, 'add_files_client', array('class' => 'form-control_1 checkBox', 'disabled' => ($callUserRole != 'admin' && !$callUserRight->add_files_client) || $isProfile));
                                    echo $form->label($targetUserRight, 'Контакты', array('class' => 'fr_o'));
                                    echo $form->checkBox($targetUserRight, 'add_files_action', array('class' => 'form-control_1 checkBox', 'disabled' => ($callUserRole != 'admin' && !$callUserRight->add_files_action) || $isProfile));
                                    echo $form->label($targetUserRight, 'Задачи', array('class' => 'fr_o'));
                                    echo $form->checkBox($targetUserRight, 'add_files_deal', array('class' => 'form-control_1 checkBox', 'disabled' => ($callUserRole != 'admin' && !$callUserRight->add_files_deal) || $isProfile));
                                    echo $form->label($targetUserRight, 'Сделки', array('class' => 'fr_o'));
                                    if ($targetUserRole->itemname == 'director') {
                                        echo $form->checkBox($targetUserRight, 'add_files_user', array('class' => 'form-control_1 checkBox', 'disabled' => ($callUserRole != 'admin' && !$callUserRight->add_files_user) || $isProfile));
                                        echo $form->label($targetUserRight, 'Пользователи', array('class' => 'fr_o'));

                                    }
                                    echo '</div>';
                            echo '</div>';

                            echo '<div class="form-group_02">
                                    <div class="an_002">Удаление файлов:</div>';
                                    echo '<div class="bn_t3">';
                                    echo $form->checkBox($targetUserRight, 'delete_files_client', array('class' => 'form-control_1 checkBox', 'disabled' => ($callUserRole != 'admin' && !$callUserRight->delete_files_client) || $isProfile));
                                    echo $form->label($targetUserRight, 'Контакты', array('class' => 'fr_o'));
                                    echo $form->checkBox($targetUserRight, 'delete_files_action', array('class' => 'form-control_1 checkBox', 'disabled' => ($callUserRole != 'admin' && !$callUserRight->delete_files_action) || $isProfile));
                                    echo $form->label($targetUserRight, 'Задачи', array('class' => 'fr_o'));
                                    echo $form->checkBox($targetUserRight, 'delete_files_deal', array('class' => 'form-control_1 checkBox', 'disabled' => ($callUserRole != 'admin' && !$callUserRight->delete_files_deal) || $isProfile));
                                    echo $form->label($targetUserRight, 'Сделки', array('class' => 'fr_o'));
                                    if ($targetUserRole->itemname == 'director') {
                                        echo $form->checkBox($targetUserRight, 'delete_files_user', array('class' => 'form-control_1 checkBox', 'disabled' => ($callUserRole != 'admin' && !$callUserRight->delete_files_user) || $isProfile));
                                        echo $form->label($targetUserRight, 'Пользователи', array('class' => 'fr_o'));

                                    }
                                    echo '</div>';
                            echo '</div>';


                            if ($callUserRole == 'admin') {
                                echo '
                                    <div class="form-group_02" id="createLabelRight">
                                        <div class="an_002">Создание меток:</div>';
                                        echo '<div class="bn_t3">';
                                        echo $form->checkBox($targetUserRight, 'create_label_clients', array('class' => 'form-control_1 checkBox'));
                                        echo $form->label($targetUserRight, 'Контакты', array('class' => 'fr_o'));
                                        echo $form->checkBox($targetUserRight, 'create_label_actions', array('class' => 'form-control_1 checkBox'));
                                        echo $form->label($targetUserRight, 'Задачи', array('class' => 'fr_o'));
                                        echo $form->checkBox($targetUserRight, 'create_label_deals', array('class' => 'form-control_1 checkBox'));
                                        echo $form->label($targetUserRight, 'Сделки', array('class' => 'fr_o'));
                                        echo '</div>';
                                echo '</div>';

                                echo '<div class="form-group_02" id="deleteLabelRight">
                                        <div class="an_002">Удаление меток:</div>';

                                        echo '<div class="bn_t3">';
                                        echo $form->checkBox($targetUserRight, 'delete_label_clients', array('class' => 'form-control_1 checkBox',));
                                        echo $form->label($targetUserRight, 'Контакты', array('class' => 'fr_o'));
                                        echo $form->checkBox($targetUserRight, 'delete_label_actions', array('class' => 'form-control_1 checkBox',));
                                        echo $form->label($targetUserRight, 'Задачи', array('class' => 'fr_o'));
                                        echo $form->checkBox($targetUserRight, 'delete_label_deals', array('class' =>
                                            'form-control_1 checkBox',));
                                        echo $form->label($targetUserRight, 'Сделки', array('class' => 'fr_o'));
                                        echo '</div>';
                                echo '</div>';

                                echo '<div class="form-group_02" id="blockStepRight">
                                        <div class="an_002">Воронки:</div>';
                                        echo '<div class="bn_t3">';
                                        echo $form->checkBox($targetUserRight, 'create_steps', array('class' => 'form-control_1
                                                checkBox',));
                                        echo $form->label($targetUserRight, 'Создание', array('class' => 'fr_o'));
                                        echo $form->checkBox($targetUserRight, 'delete_steps', array('class' =>
                                            'form-control_1 checkBox',));
                                        echo $form->label($targetUserRight, 'Удаление', array('class' => 'fr_o'));
                                        echo '</div>';
                                echo '</div>';

                            }

                            /*echo '</div>';*/
                            ?>
                        </div>
                    </div>
                </div>

            <? } ?>
        </div>
    </div>

    <div class="box-gray111 width-static">
        <div class="edit_user_1anketa">
            <div class="title_name_2">Управление</div>
            <div class="popup__form_anketa">
                <div class="imgavatar">
                    <?php
                    if ($targetUser->avatar && file_exists(Yii::getPathOfAlias('webroot') . $targetUser->avatar)) {
                        echo CHtml::tag('img', ['class' => 'avatar', 'src' => $targetUser->avatar, 'id' => 'avatar']);
                    } else {
                        switch ($targetUser->roles[0]->name) {
                            case 'admin':
                                echo CHtml::tag('img', ['class' => 'avatar', 'src' => '/img/ava_admin.svg', 'id' => 'avatar']);
                                break;
                            case 'director':
                                echo CHtml::tag('img', ['class' => 'avatar', 'src' => '/img/ava_adminisrtr.svg', 'id' => 'avatar']);
                                break;
                            case 'manager':
                                echo CHtml::tag('img', ['class' => 'avatar', 'src' => '/img/employee.svg', 'id' => 'avatar']);
                                break;
                        }
                    }
                    ?>
                </div>
                <div class="profile_info_block_usser clear_fix">
                    <?php echo CHtml::submitButton('Сохранить', array('class' => 'maui_btn', 'id' => 'save')); ?>
                    <?
                    echo CHtml::button("Новый пароль", array('onClick' => 'changePassword(' . $targetUser->id . ')', 'id' => 'create_password', 'class' => 'foton_btn',));
                    ?>
                    <div id="preloader" style="margin: 0 auto;"></div>
                </div>

                <? if ($targetUser->roles[0]->name != 'admin' && !$isProfile) { ?>

                    <div class="form-group">
                        <div class="function-delete" style="display: block;padding-left: 0px;text-align: center;">
                            <a class="delete" href="#">Удалить пользователя</a></div>
                        <div class="function-delete-confirm">
                            <ul class="horizontal">
                                <li class="big">Контакты, задачи и сделки будут закреплены за
                                    <strong><? echo $targetUser->parent->first_name; ?></strong>. Файлы и заметки пользователя будут удалены. Подтвердите удаление:
                                </li>
                                <li style="margin-right: 10px;">
                                    <a href="#" class="cancel">Отмена</a>
                                </li>
                                <li style="padding-top: 8px;">
                                    <? echo $delete_button = CHtml::button("Удалить", array('onClick' => 'window.location.href = "' . Yii::app()->createUrl("page/delete_user", array("id" => $targetUser->id)) . '"', 'class' => 'btn',)); ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                <? } ?>
            </div>
        </div>
    </div>
</main>


<?php $this->endWidget(); ?>

<script>

    role = "<? echo $targetUserRole->itemname ?>";
    $("#MainUsersRoles_itemname").change(function () {
        checkRole();
        if ($("#MainUsersRoles_itemname").val() == 'manager') {
            $("#selectResponsible").slideDown();
        } else {
            $("#selectResponsible").slideUp();
        }
    });

    $("#avatarDelete").click(function () {
        switch (role) {
            case 'admin':
                $("#avatar").attr('src', '/img/ava_admin.svg');
                break;
            case 'director':
                $("#avatar").attr('src', '/img/ava_adminisrtr.svg');
                break;
            case 'manager':
                $("#avatar").attr('src', '/img/employee.svg');
                break;
        }
        $("#Users_avatar").val("del");

    });

    changeRightAction = function () {
        if ($("#UserRight_create_action").prop("checked")) {
            <? if ($callUserRole == 'admin' || $callUserRight->delete_action)  {?>
            $("#UserRight_delete_action").removeAttr("disabled");
            <? }?>

            <? if ($callUserRole == 'admin' || $callUserRight->add_files_action)  {?>
            $("#UserRight_add_files_action").removeAttr("disabled");
            <? }?>

            <? if ($callUserRole == 'admin') {?>
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
            <? if ($callUserRole == 'admin' || $callUserRight->delete_deals)  {?>
            $("#UserRight_delete_deals").removeAttr("disabled");
            <? }?>

            <? if ($callUserRole == 'admin' || $callUserRight->add_files_deal)  {?>
            $("#UserRight_add_files_deal").removeAttr("disabled");
            <? }?>

            <? if ($callUserRole == 'admin') {?>
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

            <? if ($callUserRole == 'admin' || $callUserRight->delete_client)  {?>
            $("#UserRight_delete_client").removeAttr("disabled");
            <? }?>

            <? if ($callUserRole == 'admin' || $callUserRight->add_files_client)  {?>
            $("#UserRight_add_files_client").removeAttr("disabled");
            <? }?>

            <? if ($callUserRole == 'admin') {?>
            $("#UserRight_create_label_clients").removeAttr("disabled");
            <? }?>
        } else {
            $("#UserRight_delete_files_client").prop("checked", false).attr("disabled", "disabled");
            $("#UserRight_add_files_client").prop("checked", false).attr("disabled", "disabled");
            $("#UserRight_create_label_clients").prop("checked", false).attr("disabled", "disabled");
            $("#UserRight_delete_label_clients").prop("checked", false).attr("disabled", "disabled");
            $("#UserRight_delete_client").prop("checked", false).attr("disabled", "disabled");
        }
    };

    changeRightFields = function () {
        if ($("#UserRight_create_field").prop("checked")) {
            <? if ($callUserRole == 'admin' || $callUserRight->delete_field)  {?>
            $("#UserRight_delete_field").removeAttr("disabled");
            <? }?>
            <? if ($callUserRole == 'admin' || $callUserRight->delete_section)  {?>
            $("#UserRight_delete_section").removeAttr("disabled");
            <? }?>
        } else {
            $("#UserRight_delete_field").prop("checked", false).attr("disabled", "disabled");
        }
    };

    changeRightDocuments = function () {
        if ($("#UserRight_add_files_client").prop("checked")) {
            <? if ($callUserRole == 'admin' || $callUserRight->delete_files_client)  {?>
            $("#UserRight_delete_files_client").removeAttr("disabled");
            <? }?>
        } else {
            $("#UserRight_delete_files_client").prop("checked", false).attr("disabled", "disabled");
        }

        if ($("#UserRight_add_files_action").prop("checked")) {
            <? if ($callUserRole == 'admin' || $callUserRight->delete_files_action)  {?>
            $("#UserRight_delete_files_action").removeAttr("disabled");
            <? }?>
        } else {
            $("#UserRight_delete_files_action").prop("checked", false).attr("disabled", "disabled");
        }

        if ($("#UserRight_add_files_deal").prop("checked")) {
            <? if ($callUserRole == 'admin' || $callUserRight->delete_files_deal)  {?>
            $("#UserRight_delete_files_deal").removeAttr("disabled");
            <? }?>
        } else {
            $("#UserRight_delete_files_deal").prop("checked", false).attr("disabled", "disabled");
        }

        if ($("#UserRight_add_files_user").prop("checked")) {
            <? if ($callUserRole == 'admin' || $callUserRight->delete_files_user)  {?>
            $("#UserRight_delete_files_user").removeAttr("disabled");
            <? }?>
        } else {
            $("#UserRight_delete_files_user").prop("checked", false).attr("disabled", "disabled");
        }
    };

    checkRole = function () {
        if ($("#MainUsersRoles_itemname").val() == 'director') {
            $("#fieldRight").show();
            $("#createLabelRight").show();
            $("#deleteLabelRight").show();
            $("#blockStepRight").show();
        }

        if ($("#MainUsersRoles_itemname").val() == 'manager') {
            $("#fieldRight").hide();
            $("#createLabelRight").hide();
            $("#deleteLabelRight").hide();
            $("#blockStepRight").hide();
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

    $('#UserRight_create_label_clients').change(function () {
        changeRightLabelClient();
    });

    $('#UserRight_create_label_actions').change(function () {
        changeRightLabelAction();
    });

    $('#UserRight_create_label_deals').change(function () {
        changeRightLabelDeal();
    });


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

    $('#UserRight_create_steps').change(function () {
        changeRightSteps();
    });

    let isProfile = <?echo $isProfile ? 1 : 0?>;
    if (!isProfile) {
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
    }

</script>
<script src="/img/lightbox/lightbox.js"></script>
<script>
    $("#edit_user").submit(function () {
        $("#preloader").addClass('preloader');
        $("#save").hide();
        $("#create_password").hide();
    });

    $("#fakeButton").click(function () {
        $("#loadImage").click();
    })
    $("#fakeButtonDel").click(function () {
        $("#avatarDelete").click();
        $("#fakeButtonDel").hide();
    })

    document.getElementById('loadImage').onchange = function () {
        if (this.files[0]) // если выбрали файл
            document.getElementById('fakeButtonNameFile').innerHTML = this.files[0].name;

    };

    function changePassword(id) {
        $.get('/page/edit_user_password',
            {
                id: id
            }).done(function (result) {
            result = result ? JSON.parse(result) : {};
            if (result.status == 'success') {
                $('.gud').show();
                $('.error-message').hide();
            } else {
                $('.gud').hide();
                let messageError = $('.error-message').find('span');
                if (messageError.length > 0) {
                    messageError[0].textContent = result['errorList'][0];
                }
                $('.error-message').show();
            }
        });

    }

    /*jQuery(function($){
        $(document).ready(function (e){
            let blockRight = document.getElementById('blockRight');
            if (blockRight) {
                let rightList = blockRight.querySelectorAll('input');
                if (rightList.length > 0) {
                    rightList.forEach((checkbox) => {
                        if (checkbox.type == 'checkbox') {
                            checkbox.disabled = true;
                        }
                    });
                }
            }
        });
    });*/

</script>

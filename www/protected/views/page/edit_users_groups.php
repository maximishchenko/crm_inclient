<?php $this->pageTitle = $userGroups->name . ' | Пользователи' ?>
<?php $correct_path = 'http://' . $_SERVER["HTTP_HOST"]; ?>

<div class="clients-hat">
    <div class="settings-name">
        <?php echo CHtml::link('Настройки', array('page/settings_additional_field')); ?>
        <img src="/img/right-arrow-button.svg">
        <?php echo CHtml::link('Пользователи', array('page/settings_users_groups')); ?>
        <img src="/img/right-arrow-button.svg" alt="">
        Группа #<?php echo $userGroups->id ?>: <?php echo $userGroups->name ?>
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
            <div class="title_name_1">Настройка группы</div>

            <? if ($isShowBlockSave) { ?>
                <script type="module">
                    import {NotificationBar} from '/js/notificationBar.js';

                    const notificationBar = new NotificationBar({
                        type: 'success',
                        title: 'Сохранено',
                        description: 'Группа успешно изменена'
                    });
                    notificationBar.show();
                </script>
            <? } ?>

            <div class="centre_settings">

                <table class="main-table row edit-row" id="user-info">

                    <tr>
                        <td class="an_001" width="132">Группа:<span class="star">*</span></td>
                        <td><?php echo $form->textField($userGroups, 'name', array('class' => 'form-control', 'placeholder' => 'Наименование')); ?>
                            <?php echo $form->error($userGroups, 'name', array('class' => 'form-error')); ?></td>
                    </tr>
                </table>

            </div>


            <div class="save_button"><?php echo CHtml::submitButton('Сохранить', array('class' => 'btn', 'id' => 'save')); ?>
                <div id="preloader"></div>
            </div>


            <div class="function-delete delete_centre">
                <a class="delete" href="#">Удалить группу</a>
            </div>

            <div class="function-delete-confirm delete_centre" style="display: none;">
                <ul class="horizontal_3">
                    <li class="big">Пользователи из этой группы перенесутся в группу по умолчанию. <br>Подвердите
                        удаление:
                    </li>

                    <li style="padding-top: 9px;"> <?php echo CHtml::button("Удалить", array(
                            'onClick' => 'window.location.href = "' . Yii::app()->createUrl("page/Users_groups_delete",
                                    array("id" => $userGroups->id)) . '"',
                            'class' => 'btn',
                        )); ?>  </li>
                    <li><a href="#" class="cancel" style="margin-left: 10px;">Отмена</a></li>
                </ul>
            </div>
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
                <p><strong>Группы пользователей</strong></p><br>
                <p>Группы используются для разделения пользователей на категории. Например, пользователей можно
                    распределить: по направлению деятельности (продажи, бухгалтерия, реклама), по занимаемой должности
                    (стажер, сотрудник, директор, админ), по филиалам компании (Московский филиал, Офис в Перми, Офис в
                    Краснодаре) и так далее.</p>
                <details class="help_0">
                    <summary class="help_1">Как прикрепить пользователя к группе?</summary>
                    <p>Перейдите в редактирование профиля пользователя, в параметре «Группа» назначьте нужную группу и
                        сохраните изменения. Для пользователя можно назначить только одну группу.</p>
                </details>
                <details class="help_0">
                    <summary class="help_1">Что будет, если удалить группу?</summary>
                    <p>Группа удалится безвозвратно. Все пользователи, прикрепленные к группе, перенесутся в группу
                        пользователей по умолчанию (#1).</p>
                </details>
                <details class="help_0">
                    <summary class="help_1">Как найти пользователя в нужной группе?</summary>
                    <p>Воспользуйтесь поиском на
                        странице <?php echo CHtml::link('Пользователи', array('page/user_info')); ?>.</p>
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
</script>
<?php $this->pageTitle = $additionalFiledSelect->name . ' | Настройка раздела'; ?>
<?php $correct_path = 'http://' . $_SERVER["HTTP_HOST"]; ?>

<div class="clients-hat">
    <div class="settings-name">
        <?php echo CHtml::link('Настройки', array('page/settings_additional_field')); ?>
        <img src="/img/right-arrow-button.svg">
        <?php echo CHtml::link('Анкета контакта', array('page/settings_additional_field')); ?>
        <img src="/img/right-arrow-button.svg" alt="">
        Раздел #<?php echo $additionalFiledSelect->id ?>: <?php echo $additionalFiledSelect->name ?>
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

    <? if ($isShowBlockSave) { ?>
        <script type="module">
            import {NotificationBar} from '/js/notificationBar.js';

            const notificationBar = new NotificationBar({
                type: 'success',
                title: 'Сохранено',
                description: 'Раздел успешно изменен в анкете'
            });
            notificationBar.show();
        </script>
    <? } ?>

    <div class="edit_user_view">
        <div class="content-01">
            <div class="title_name_1">Настройка раздела</div>
            <div class="centre_settings_4">

                <table class="main_table_12">
                    <tr>
                        <td class="an_001" width="132">Раздел:</td>
                        <td><?php echo $form->textField($additionalFiledSelect, 'name', array('class' => 'form-control', 'placeholder' => 'Наименование')); ?>
                            <?php echo $form->error($additionalFiledSelect, 'name', array('class' => 'form-error')); ?></td>
                    </tr>
                    <?php
                    if (!$additionalFiledSelect->noEdit) {
                        ?>
                        <tr>
                            <td class="an_001" width="132">Порядок:</td>
                            <td><?php echo $form->textField($additionalFiledSelect, 'weight', array('class' => 'form-control')); ?>
                                <?php echo $form->error($additionalFiledSelect, 'weight', array('class' => 'form-error')); ?></td>
                        </tr>
                        <? if ($user->roles[0]->name == 'admin') { ?>
                            <tr>
                                <td class="an_001" width="132">Доступ:</td>
                                <td class=""><?php echo $form->dropDownList($additionalFiledSelect, 'access', AdditionalFieldsSection::model()->getAccess(), array('class' => 'styled', 'data-placeholder' => '')); ?>
                                </td>
                            </tr>
                        <? }
                    } ?>
                </table>
                <div class="main-table_che" id="groupsUsers"
                     style="display: <? echo $additionalFiledSelect->access == 'groups'
                     && $user->roles[0]->name == 'admin' ? 'block' : 'none' ?>">

                    <? foreach ($allGroup as $key => $group) { ?>
                        <div style="width: 100%;display: flex;">
                            <span><?php echo $form->checkBox($additionalFiledSelect, "data[group][$key]", array("class" => "form-control_1 checkBox")); ?></span>
                            <span style="padding: 3px 7px;margin-bottom: 5px;"><? echo $group ?></span>
                        </div>
                    <? } ?>

                </div>
                <div class="save_button" style="margin: 0px 0px 25px 133px;">
                    <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn', 'id' => 'save')); ?>
                    <div id="preloader"></div>
                </div>
            </div>

            <? if (!$additionalFiledSelect->noEdit && ($user->roles[0]->name == 'admin' || $user->userRights[0]->delete_section)) { ?>
                <div class="function-delete delete_centre">
                    <a class="delete" href="#">Удалить раздел</a>
                </div>
                <div class="function-delete-confirm delete_centre" style="display: none;">
                    <ul class="horizontal_3">
                        <li class="big">Удалить раздел и поля?
                            Или удалить раздел с переносом полей в
                            "<?php echo $firstSection->name; ?>". Подтвердите удаление:
                        </li>

                        <li style="padding-top: 9px;"> <?php echo CHtml::button("Удалить", array(
                                'onClick' => 'window.location.href = "' . Yii::app()->createUrl("page/additional_field_section_delete",
                                        array("id" => $additionalFiledSelect->id)) . '"',
                                'class' => 'btn',
                            )); ?>  </li>
                        <li style="padding-top: 9px;padding-left: 10px;"> <?php echo CHtml::button("Удалить с переносом полей", array(
                                'onClick' => 'window.location.href = "' . Yii::app()->createUrl("page/additional_field_section_delete_transfer",
                                        array("id" => $additionalFiledSelect->id)) . '"',
                                'class' => 'btn_grey_1',
                            )); ?>  </li>
                        <li><a class="cancel" href="#" style="margin-left: 10px;">Отмена</a></li>

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
                <ul>
                    <li>
                        <strong>О разделах</strong>
                        <br>
                        <br>
                        Если в разделе не окажется полей, тогда раздел будет скрыт в анкете. Обратите внимание, раздел
                        по
                        умолчанию (#1) всегда доступен для пользователей и его невозможно удалить
                    </li>
                    <details class="help_0">
                        <summary class="help_1">Как ограничить доступ к разделу?</summary>
                        <p>Можно скрыть раздел, и все его поля, от пользователей: в настройках параметра "Доступ"
                            выберите
                            группы, которым вы хотите разрешить доступ к разделу. Для других групп раздел будет
                            скрыт</p>
                    </details>
                    <details class="help_0">
                        <summary class="help_1">Что будет, если удалить раздел?</summary>
                        <p>Два варианта на выбор: 1) Удалить - раздел и поля удалятся безвозвратно; 2) Перенести поля -
                            раздел удалится, поля перенесутся в раздел по умолчанию (#1)</p>
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
        switch ($("#AdditionalFieldsSection_access").val()) {
            case 'all':
                $("#groupsUsers").hide();
                break;
            case 'groups':
                $("#groupsUsers").show();
                break;
        }
    }

    $("#AdditionalFieldsSection_access").change(function () {
        showHide()
    });
</script>
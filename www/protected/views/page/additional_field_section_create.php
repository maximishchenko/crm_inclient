<?php $this->pageTitle = 'Новый раздел' ?>
<?php $correct_path = 'http://' . $_SERVER["HTTP_HOST"]; ?>

<div class="clients-hat">
    <div class="settings-name">
        <?php echo CHtml::link('Настройки', array('page/settings_additional_field')); ?>
        <img src="/img/right-arrow-button.svg">
        Анкета контакта
        <img src="/img/right-arrow-button.svg">
        Новый раздел
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
            <div class="title_name_1">Настройки раздела</div>

            <div class="centre_settings">
                <table class="main_table_12">
                    <tr>
                        <td class="an_001" width="132">Раздел:</td>
                        <td><?php echo $form->textField($additionalFiledSelect, 'name', array('class' => 'form-control', 'placeholder' => 'Наименование')); ?>
                            <?php echo $form->error($additionalFiledSelect, 'name', array('class' => 'form-error')); ?></td>
                    </tr>
                    <tr>
                        <td class="an_001" width="132">Порядок:</td>
                        <td><?php echo $form->textField($additionalFiledSelect, 'weight', array('class' => 'form-control')); ?>
                            <?php echo $form->error($additionalFiledSelect, 'weight', array('class' => 'form-error')); ?></td>
                    </tr>
                    <? if ($user->roles[0]->name == 'admin') { ?>
                        <tr>
                            <td class="an_001" width="132">Доступ:</td>
                            <td class=""><?php echo $form->dropDownList($additionalFiledSelect, 'access', AdditionalFieldsSection::model()->getAccess(), array('class' => 'styled', 'data-placeholder' => '')); ?>
                                <?php echo $form->error($additionalFiledSelect, 'access', array('class' => 'form-error')); ?>
                            </td>
                        </tr>
                    <? } ?>
                </table>

                <div class="main-table_che" id="groupsUsers"
                     style="display: <? echo $additionalFiledSelect->access == 'groups' ?: 'none' ?>">
                    <? foreach ($allGroup as $key => $group) { ?>
                        <div style="width: 100%;display: flex;">
							<span>
							<?php echo $form->checkBox($additionalFiledSelect, "data[group][$key]", array("class" => "form-control_1 checkBox", 'checked' => $additionalFiledSelect->groups[$key] == 1)); ?>
							</span>

                            <span style="padding: 3px 7px;margin-bottom: 5px;"><? echo $group ?></span>
                        </div>
                    <? } ?>
                </div>

                <div class="save_button" style="margin: 0px 0px 25px 133px;">
                    <?php echo CHtml::submitButton('Создать раздел', array('class' => 'btn', 'id' => 'save')); ?>
                    <div id="preloader"></div>
                </div>

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
                <ul>
                    <li>
                        <strong>О разделах</strong>
                        <br>
                        <br>
                        Если в разделе не окажется полей, тогда раздел будет скрыт в анкете. Обратите внимание, раздел
                        по умолчанию (#1) всегда доступен для пользователей и его невозможно удалить
                    </li>
                    <details class="help_0">
                        <summary class="help_1">Как ограничить доступ к разделу?</summary>
                        <p>Можно скрыть раздел, и все его поля, от пользователей: в настройках параметра "Доступ"
                            выберите группы, которым вы хотите разрешить доступ к разделу. Для других групп раздел будет
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
    showHide();
</script>
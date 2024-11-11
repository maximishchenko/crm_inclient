<?php $this->pageTitle = 'Новая группа | Пользователи' ?>
<?php $correct_path = 'http://' . $_SERVER["HTTP_HOST"]; ?>

<div class="clients-hat">
    <div class="settings-name">
        <?php echo CHtml::link('Настройки', array('page/settings_additional_field')); ?>
        <img src="/img/right-arrow-button.svg">
        <?php echo CHtml::link('Пользователи', array('page/settings_users_groups')); ?>
        <img src="/img/right-arrow-button.svg">
        Новая группа
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
            <div class="centre_settings">

                <table class="main-table row edit-row" id="user-info">
                    <tr>
                        <td class="an_001" width="132">Группа:<span class="star">*</span></td>
                        <td><?php echo $form->textField($userGroups, 'name', array('class' => 'form-control', 'placeholder' => 'Наименование')); ?>
                            <?php echo $form->error($userGroups, 'name', array('class' => 'form-error')); ?></td>
                    </tr>
                </table>
            </div>

            <div class="save_button"><?php echo CHtml::submitButton('Создать группу', array('class' => 'btn', 'id' => 'save')); ?>
                <div id="preloader"></div>
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
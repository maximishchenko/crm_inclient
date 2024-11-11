<?php $this->pageTitle = 'Пользователи | Настройки'; ?>

<?php if (Yii::app()->user->hasFlash('create_group')){ ?>
    <script type="module">
        import {NotificationBar} from '/js/notificationBar.js';
        const notificationBar = new NotificationBar({
            type: 'warning',
            title: 'Новая группа создана',
            description: <?echo "'" . Yii::app()->user->getFlash('create_group') . "'"?>,
        });
        notificationBar.show();
    </script>
<?}?>

<div class="clients-hat">
    <div class="settings-name">
        <?php echo CHtml::link('Настройки', array('page/settings_additional_field')); ?>
        <img src="/img/right-arrow-button.svg">
        Пользователи
    </div>
    <div class="goback-link pull-right" style="margin-bottom: 25px;">

    </div>
</div>
<main class="content full2" role="main">
    <div class="box_edituser_left">
        <div class="edit_user_0anketa">
            <div class="content-01">
                <?php $this->renderPartial('settings_main_nav', array('userGroups' => true)); ?>
                <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider' => $userGroups,
                    'cssFile' => '',
                    'htmlOptions' => array('class' => 'main-table'),
                    'columns' => array(
                        array(
                            'name' => 'name',
                            'header' => 'Группа',
                            'type' => 'raw',
                            'headerHtmlOptions' => array('class' => 'w9', 'style' =>
                                '       height: 11px; border-bottom: 1px solid #d9d9d9;
								        padding-left: 13px;text-align: left;font-size: 11px;
										color: #000000 !important;line-height: 12px;'),
                            'value' => function ($data) {
                                return CHtml::link($data->name, Yii::app()->createUrl("page/users_groups_edit", array("id" => $data->id)), array("class" => "link_set_2"));
                            }
                        ),
                        array(
                            'name' => 'name',
                            'header' => 'Пользователи',
                            'type' => 'raw',
                            'headerHtmlOptions' => array('class' => 'w8_1', 'style' =>
                                '   height: 11px;
                                    border-bottom: 1px solid #d9d9d9;
                                    padding: 8px 8px 8px 12px;
                                    text-align:left;
                                    font-size: 11px;
                                    color: #000000 !important;
                                    line-height: 12px;
                                    border-left: 1px solid #d9d9d9;'),
                            'value' => function ($data) {
                                return count($data->userInGroups);
                            }
                        ),
                    )
                ));
                ?>

                <div class="settings_foot">
                    <div class="help-dropdown open">
                        <dl>
                            <?php
                            echo CHtml::button('Добавить группу', array('onClick' => 'window.location.href= "' . Yii::app()->createUrl("page/users_groups_create") . '"',
                                'class' => 'add-btn__set'));
                            ?>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="right-sidebar">
            <div class="title_name_2">Справка
                <div class="more"><img src="/img/external-link-symbol.svg"><a href="https://inclient.ru/category/help-crm/" target="_blank" style="color: #707070;">Подробнее</a></div>
            </div>
            <div class="popup__form_actions">
                <div>
                    <p><strong>Группа пользователей</strong></p>
                    <br>
                    <p>Группы используются для разделения пользователей на категории. Например, пользователей можно распределить: по направлению деятельности (продажи, бухгалтерия, реклама), по занимаемой должности (стажер, сотрудник, директор, админ), по филиалам компании (Московский филиал, Офис в Перми, Офис в Краснодаре) и так далее.</p>
                    <p>Группа назначается в профиле пользователя. В будущем появятся отчеты по группам.</p>
                </div>
            </div>
        </div>
</main>

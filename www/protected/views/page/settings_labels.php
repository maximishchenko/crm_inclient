<?php $this->pageTitle = 'Метки | Настройки'; ?>

<?php if (Yii::app()->user->hasFlash('create_label')){ ?>
    <script type="module">
        import {NotificationBar} from '/js/notificationBar.js';
        const notificationBar = new NotificationBar({
            type: 'warning',
            title: 'Новая метка создана',
            description: <?echo "'" . Yii::app()->user->getFlash('create_label') . "'"?>,
        });
        notificationBar.show();
    </script>
<?}?>

<div class="clients-hat">
    <div class="settings-name">
        <?php echo CHtml::link('Настройки', array('page/settings_additional_field')); ?>
        <img src="/img/right-arrow-button.svg">
        Метки
    </div>
    <div class="goback-link pull-right" style="margin-bottom: 25px;">

    </div>
</div>
<main class="content full2 settingsLabels" role="main">
    <div class="box_edituser_left">
        <div class="edit_user_0anketa">
            <div class="content-01">
                <?php $this->renderPartial('settings_main_nav', array('labels' => true)); ?>

                <div class="user-table-block_pola fixWidth">
                    <ul id="ul-listTabs">
                        <?php
                        $typeLabels = $typeLabels ?? array_keys($listTabs)[0];
                        foreach ($listTabs as $key => $tab) {
                            $count = $dateCountType[$key] ?? 0;
                            echo
                                '<li id="' . $key . '" class="button-change-table sectorsBlock '
                                . ($typeLabels != $key ?: 'active') . '" onclick="changeTabs(' . "'" . $key . "'" . ');">' . $tab .
                                '<span id="' . $key . 'Count">' . $count . '</span>
                            </li>';
                        }
                        ?>
                    </ul>
                </div>

                <div id="clientsTable" class="hide">
                    <?
                    if ($listTabs['clients']) {
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'dataProvider' => $dataLabelsForClients,
                            'cssFile' => '',
                            'htmlOptions' => array('class' => 'main-table tableBorder'),
                            'columns' => array(
                                array(
                                    'name' => 'name',
                                    'header' => '',
                                    'type' => 'raw',
                                    'headerHtmlOptions' => ['class' => 'columnLabel'],
                                    'value' => function ($data) {
                                        return '<div class="custom-label" style="background-color:' . $data->color . '; color:' . $data->color_text . '">'
                                            . $data->name . '</div>';
                                    }
                                ),
                                array(
                                    'name' => 'name',
                                    'header' => '',
                                    'type' => 'raw',
                                    'headerHtmlOptions' => ['class' => 'columnCount'],
                                    'value' => function ($data) {
                                        return '<div> Контакты: ' . count(LabelsInClients::model()->findAll('label_id = :ID', [':ID' => $data->id])) . '</div>';
                                    }
                                ),
                                array(
                                    'name' => 'name',
                                    'header' => '',
                                    'type' => 'raw',
                                    'headerHtmlOptions' => ['class' => 'columnOperation'],
                                    'value' => function ($data) {
                                        return CHtml::link(
                                            'Изменить',
                                            [Yii::app()->getHomeUrl() . '/page/edit_label?id=' . $data->id . '&type=clients'],
                                            ["class" => "link_set float-right"]
                                        );
                                    }
                                ),
                            )
                        ));
                    }
                    ?>
                </div>
                <div id="actionsTable" class="hide">
                    <?
                    if ($listTabs['actions']) {
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'dataProvider' => $dataLabelsForActions,
                            'cssFile' => '',
                            'htmlOptions' => array('class' => 'main-table tableBorder'),
                            'columns' => array(
                                array(
                                    'name' => 'name',
                                    'header' => '',
                                    'type' => 'raw',
                                    'headerHtmlOptions' => ['class' => 'columnLabel'],
                                    'value' => function ($data) {
                                        return '<div class="custom-label" style="background-color:' . $data->color . '; color:' . $data->color_text . '">'
                                            . $data->name . '</div>';
                                    }
                                ),
                                array(
                                    'name' => 'name',
                                    'header' => '',
                                    'type' => 'raw',
                                    'headerHtmlOptions' => ['class' => 'columnCount'],
                                    'value' => function ($data) {
                                        return '<div> Задачи: ' . count(LabelsInActions::model()->findAll('label_id = :ID', [':ID' => $data->id])) . '</div>';
                                    }
                                ),
                                array(
                                    'name' => 'name',
                                    'header' => '',
                                    'type' => 'raw',
                                    'headerHtmlOptions' => ['class' => 'columnOperation'],
                                    'value' => function ($data) {
                                        return CHtml::link(
                                            'Изменить',
                                            [Yii::app()->getHomeUrl() . '/page/edit_label?id=' . $data->id . '&type=actions'],
                                            ["class" => "link_set float-right"]
                                        );
                                    }
                                ),
                            )
                        ));
                    }

                    ?>
                </div>
                <div id="dealsTable" class="hide">
                    <?
                    if ($listTabs['actions']) {
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'dataProvider' => $dataLabelsForDeals,
                            'cssFile' => '',
                            'htmlOptions' => array('class' => 'main-table tableBorder'),
                            'columns' => array(
                                array(
                                    'name' => 'name',
                                    'header' => '',
                                    'type' => 'raw',
                                    'headerHtmlOptions' => ['class' => 'columnLabel'],
                                    'value' => function ($data) {
                                        return '<div class="custom-label" style="background-color:' . $data->color . '; color:' . $data->color_text . '">'
                                            . $data->name . '</div>';
                                    }
                                ),
                                array(
                                    'name' => 'name',
                                    'header' => '',
                                    'type' => 'raw',
                                    'headerHtmlOptions' => ['class' => 'columnCount'],
                                    'value' => function ($data) {
                                        return '<div> Сделки: ' . count(LabelsInDeals::model()->findAll('label_id = :ID', [':ID' => $data->id])) . '</div>';
                                    }
                                ),
                                array(
                                    'name' => 'name',
                                    'header' => '',
                                    'type' => 'raw',
                                    'headerHtmlOptions' => ['class' => 'columnOperation'],
                                    'value' => function ($data) {
                                        return CHtml::link(
                                            'Изменить',
                                            [Yii::app()->getHomeUrl() . '/page/edit_label?id=' . $data->id . '&type=deals'],
                                            ["class" => "link_set float-right"]
                                        );
                                    }
                                ),
                            )
                        ));
                    }

                    ?>
                </div>

                <div class="settings_foot">
                    <div class="help-dropdown open">
                        <?php
                        echo CHtml::button('Добавить метку', array('onClick' => 'createLabel()',
                            'class' => 'add-btn__set'));
                        ?>
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
                <ul>
                    <li>
                        <strong>Метки</strong>
                        <br><br>
                        Метка – идентификатор для обозначения статусов, категорий, пометок, важных замечаний и т.д.
                        Используйте метки для более удобной работы с контактами, задачами и сделками.
                    </li>
                </ul>
            </div>
        </div>
</main>

<script>
    var tabActive = $('#ul-listTabs li.active');
    $('#' + tabActive[0].id + 'Table').show();
    changeTabs = function (tab) {
        tabActive.removeClass('active');
        $('#' + tabActive[0].id + 'Table').hide();

        tabActive = $('#' + tab);
        tabActive.addClass('active');
        $('#' + tabActive[0].id + 'Table').show();
    };

    createLabel = function () {
        location = 'new_label?type=' + tabActive[0].id;
    };

    if (location.hash == '#actions') {
        changeTabs('actions')
    }
</script>
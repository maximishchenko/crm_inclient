<?php $this->pageTitle = 'Воронка | Настройки'; ?>

<?php if (Yii::app()->user->hasFlash('create_step')){ ?>
    <script type="module">
        import {NotificationBar} from '/js/notificationBar.js';
        const notificationBar = new NotificationBar({
            type: 'warning',
            title: 'Воронка создана',
            description: <?echo "'" . Yii::app()->user->getFlash('create_step') . "'"?>,
        });
        notificationBar.show();
    </script>
<?}?>

<div class="clients-hat">
    <div class="settings-name">
        <?php echo CHtml::link('Настройки', array('page/settings_additional_field')); ?>
        <img src="/img/right-arrow-button.svg">
	    Воронка
	</div>
    <div class="goback-link pull-right" style="margin-bottom: 25px;">

    </div>
</div>
<main class="content full2 settingsLabels" role="main">
    <div class="box_edituser_left">
        <div class="edit_user_0anketa">
		<div class="content-01">
            <?php $this->renderPartial('settings_main_nav', array('steps' => true)); ?>

            <div class="user-table-block_pola fixWidth">
                <ul id="ul-listTabs">
                    <?php
                    $typeSteps = $typeSteps ?? array_keys($listTabs)[0];
                    foreach ($listTabs as $key => $tab) {
                        $count = $dataCountType[$key] ?? 0;
                        echo
                            '<li id="' . $key . '" class="button-change-table sectorsBlock '
                            . ($typeSteps != $key ?: 'active') . '" onclick="changeTabs(' . "'" . $key . "'" . ');">' . $tab .
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
                        'dataProvider' => $dataStepsForClients,
                        'cssFile' => '',
                        'htmlOptions' => array('class' => 'main-table'),
                        'columns' => array(
                            array(
                                'name' => 'id',
                                'header' => 'ID',
                                'type' => 'raw',
                                'headerHtmlOptions' => ['class' => 'table-header w7_2'],
                                'value' => function ($data) {
                                    return $data->id;
                                }
                            ),
                            array(
                                'name' => 'name',
                                'header' => 'Воронка',
                                'type' => 'raw',
                                'headerHtmlOptions' => ['class' => 'table-header'],
                                'value' => function ($data) {
                                    return $data->id != 1 ? CHtml::link(
                                        $data->name,
                                        [Yii::app()->getHomeUrl() . '/page/edit_step?id=' . $data->id . '&type=clients'],
                                        ["class" => "link_set"]
                                    ) : $data->name;
                                }

                            ),
                            array(
                                'name' => 'name',
                                'header' => 'Контакты',
                                'type' => 'raw',
                                'headerHtmlOptions' => ['class' => 'table-header'],
                                'value' => function ($data) {
                                    return ' ' . count(StepsInClients::model()->findAll('steps_id = :ID', [':ID' => $data->id]));
                                }
                            ),
                            array(
                                'name' => 'name',
                                'header' => 'По умолчанию',
                                'type' => 'raw',
                                'headerHtmlOptions' => ['class' => 'table-header'],
                                'value' => function ($data) {
                                    return $data->selected_default == 1 ? 'Выбран' : '';
                                }
                            ),
                            array(
                                'name' => 'name',
                                'header' => 'Порядок',
                                'type' => 'raw',
                                'headerHtmlOptions' => ['class' => 'table-header'],
                                'value' => function ($data) {
                                    return $data->weight;
                                }
                            )
                        )
                    ));
                }
                ?>
            </div>

            <div id="dealsTable" class="hide">
                <?
                if ($listTabs['deals']) {
                    $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $dataStepsForDeals,
                        'cssFile' => '',
                        'htmlOptions' => array('class' => 'main-table'),
                        'columns' => array(
                            array(
                                'name' => 'name',
                                'header' => 'Воронка',
                                'type' => 'raw',
                                'headerHtmlOptions' => ['class' => 'table-header'],
                                'value' => function ($data) {
                                    return $data->id != 2 ? CHtml::link(
                                        $data->name,
                                        [Yii::app()->getHomeUrl() . '/page/edit_step?id=' . $data->id . '&type=deals'],
                                        ["class" => "link_set"]
                                    ) : $data->name;
                                }
                            ),
                            array(
                                'name' => 'name',
                                'header' => 'Сделки',
                                'type' => 'raw',
                                'headerHtmlOptions' => ['class' => 'table-header'],
                                'value' => function ($data) {
                                    return '' . count(StepsInDeals::model()->findAll('steps_id = :ID', [':ID' => $data->id]));
                                }
                            ),
                            array(
                                'name' => 'name',
                                'header' => 'По умолчанию',
                                'type' => 'raw',
                                'headerHtmlOptions' => ['class' => 'table-header'],
                                'value' => function ($data) {
                                    return $data->selected_default == 1 ? 'Выбран' : '';
                                }
                            ),
                            array(
                                'name' => 'name',
                                'header' => 'Порядок',
                                'type' => 'raw',
                                'headerHtmlOptions' => ['class' => 'table-header'],
                                'value' => function ($data) {
                                    return $data->weight;
                                }
                            )
                        )
                    ));
                }
                ?>
            </div>



                <div class="help-dropdown open">
                    <?php
                    echo CHtml::button('Добавить воронку', array('onClick' => 'createStep()',
                        'class' => 'add-btn__set'));
                    ?>
                </div>
        </div>
		</div>
    </div>
    <div class="right-sidebar">
            <div class="title_name_2">Справка
                <div class="more"><img src="/img/external-link-symbol.svg"><a href="https://inclient.ru/category/help-crm/" target="_blank" style="color: #707070;">Подробнее</a></div>
            </div>
            <div class="popup__form_actions">
                <p><strong>О воронках</strong></p>
                <br>
                <p><strong>Воронка в контактах</strong> — это путь в бизнес-процессе, который контакт проходит от начального до завершающего этапа. Например, путь контакта может делиться на 3 этапа: 1) знакомство; 2) покупка; 3) повторная покупка.</p>
                <p><strong>Воронка в сделках</strong> — это воронка продаж, т.е. путь, который сделка проходит от начала заинтересованности контакта до закрытия сделки с положительным или отрицательным результатом.</p>
                <br>
                <p><strong>Что такое контакт?</strong></p>
                <br>
                <p>Это клиенты, лиды, контрагенты, партнеры, поставщики и т.д.</p>
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

    createStep = function () {
        location = 'new_step?type=' + tabActive[0].id;
    };

</script>
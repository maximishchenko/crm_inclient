<?php $this->pageTitle = 'Настройки'; ?>

<?php if (Yii::app()->user->hasFlash('create_section')){ ?>
    <script type="module">
        import {NotificationBar} from '/js/notificationBar.js';
        const notificationBar = new NotificationBar({
            type: 'warning',
            title: 'Новый раздел создан',
            description: <?echo '"' . Yii::app()->user->getFlash('create_section') . '"'?>,
        });
        notificationBar.show();
    </script>
<?}?>

<?php if (Yii::app()->user->hasFlash('create_additional_field')){ ?>
    <script type="module">
        import {NotificationBar} from '/js/notificationBar.js';
        const notificationBar = new NotificationBar({
            type: 'warning',
            title: 'Новое поле создано',
            description: <?echo "'" . Yii::app()->user->getFlash('create_additional_field') . "'"?>,
        });
        notificationBar.show();
    </script>
<?}?>

<div class="clients-hat">
    <div class="settings-name">
        Настройки
        <img src="/img/right-arrow-button.svg">
        Анкета контакта
    </div>
    <div class="goback-link pull-right">
        <?php echo CHtml::button('Новый раздел', array('onClick' => 'window.location.href= "' .
            Yii::app()->createUrl("page/additional_field_section_create") . '"',
            'class' => 'btn_green')); ?>
    </div>
</div>

<div class="content full2" role="main">
    <div class="box-gray">
        <div class="edit_user_0anketa">
            <div class="content-01">
                <?php $this->renderPartial('settings_main_nav', array('additionalField' => $additionalField = true)); ?>
                <div class="user-table-block_pola">
                    <ul>
                        <?php
                        $firstElem = true;
                        $firstId = $selectionAddFields[0]->id;
                        foreach ($selectionAddFields as $selectionAddField) {
                            ?>
                            <li id="sectionId<? echo $selectionAddField->id ?>"
                                class="button-change-table sectorsBlock <? echo $firstElem ? 'active' : '' ?>"
                                onclick="changeSection(event)"><?php echo $selectionAddField->name ?>
                                <span id="sectionCountId<? echo $selectionAddField->id ?>"><? echo $countSection[$selectionAddField->id] ?></span>
                            </li>
                            <?
                            $firstElem = false;
                        } ?>
                    </ul>
                </div>
                <?php
                $firstElem = true;
                foreach ($allAddFiled as $key => $currentAddField) {
                    ?>
                    <div id="tableAddField<? echo $key ?>"
                         style="display:  <? echo $firstElem ? 'initial' : 'none' ?>;">
                        <?
                        $firstElem = false;
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'dataProvider' => $currentAddField,
                            'cssFile' => '',
                            'htmlOptions' => array('class' => 'main-table'),
                            'columns' => array(                                
                                array(
                                    'name' => 'name',
                                    'header' => 'Поле',
                                    'type' => 'raw',
                                    'headerHtmlOptions' => array('class' => 'w8_2_3', 'style' =>
                                        '       height: 11px; border-bottom: 1px solid #d9d9d9;
								        padding-left: 13px;text-align: left;font-size: 11px;
										color: #000000 !important;line-height: 12px;'),
                                    'value' => function ($data) {
                                        return !$data->noEdit ? CHtml::link($data->name, Yii::app()->createUrl("page/additional_field_edit", array("id" => $data->id)), array("class" => "link_set_2"))
                                            : $data->name;
                                    }
                                ),
                                array(
                                    'name' => 'table_name',
                                    'header' => 'Идентификатор',
                                    'type' => 'raw',
                                    'headerHtmlOptions' => array('class' => 'w8_2', 'style' =>
                                        '   height: 11px;
                                    border-bottom: 1px solid #d9d9d9;
                                    padding: 8px 8px 8px 12px;
                                    text-align:left;
                                    font-size: 11px;
                                    color: #000000 !important;
                                    line-height: 12px;
                                    border-left: 1px solid #d9d9d9;'),
                                    'value' => function ($data) {
                                        return $data->table_name;
                                    }
                                ),
                                array(
                                    'name' => 'type',
                                    'header' => 'Тип',
                                    'type' => 'raw',
                                    'headerHtmlOptions' => array('class' => 'w8_2', 'style' =>
                                        '   height: 11px;
                                    border-bottom: 1px solid #d9d9d9;
                                    padding: 8px 8px 8px 12px;
                                    text-align:left;
                                    font-size: 11px;
                                    color: #000000 !important;
                                    line-height: 12px;
                                    border-left: 1px solid #d9d9d9;'),
                                    'value' => function ($data) {
                                        return AdditionalFields::model()->getTypeField()[$data->type];
                                    }
                                ),
                                array(
                                    'name' => 'required',
                                    'header' => 'Обязательное',
                                    'type' => 'raw',
                                    'headerHtmlOptions' => array('class' => 'w8_2', 'style' =>
                                        '   height: 11px;
                                    border-bottom: 1px solid #d9d9d9;
                                    padding: 8px 8px 8px 12px;
                                    text-align:left;
                                    font-size: 11px;
                                    color: #000000 !important;
                                    line-height: 12px;
                                    border-left: 1px solid #d9d9d9;'),
                                    'value' => function ($data) {
                                        return $data->required ? 'Да' : 'Нет';
                                    }
                                )

                            )
                        ));
                        ?>
                    </div>
                    <?
                }
                ?>


                <div class="help-dropdown open">
                    <?php
                    echo CHtml::button('Добавить поле', array('onClick' => 'createField()',
                        'class' => 'add-btn__set'));
                    ?>
                </div>

            </div>
        </div>
    </div>
    
        <div class="right-sidebar">
            <div class="title_name_2">Настройка раздела
                <div class="more"><img src="/img/external-link-symbol.svg"><a href="https://inclient.ru/category/help-crm/" target="_blank" style="color: #707070;">Подробнее</a>
                </div>
            </div>
            <div class="popup__form_actions top_minus_10">
                <div class="solid_an_client">
                    <ul>
                        <?php
                        $firstElem = true;
                        foreach ($selectionAddFields as $selectionAddField) {
                            ?>
                            <div class="infoSelect" id="infoSection<? echo $selectionAddField->id ?>"
                                 style="display:  <? echo $firstElem ? 'initial' : 'none' ?>">
                                <?
                                echo '<span>Раздел:</span> ' . $selectionAddField->name . '<br>';
                                echo '<span>Порядок:</span> ' . $selectionAddField->weight . '<br>';
                                echo '<span>Доступ:</span> ' . AdditionalFieldsSection::getAccess()[$selectionAddField->access] . '<br>';
                                if ($selectionAddField->access == 'groups') {
                                    echo '<span>Группы:</span> ' . $textGroup[$selectionAddField->id];
                                    '<br>';
                                }
                                ?>
                            </div>
                            <?
                            $firstElem = false;
                        }
                        ?>
                        <li>
                        </li>
                        <li>
                        </li>
                    </ul>
                </div>
                <div class="solid_an_client">
                    <p>Раздел - блок с полями в анкете контакта. Что такое контакт? Это клиенты, лиды, контрагенты, партнеры, поставщики и т.д. </p><p>Разделы нужны, чтобы группировать информацию о контакте по смыслу. Доступ к разделам можно ограничить для определенных групп пользователей.</p>
                </div>
                <div class="form-group_actions">
                    <?php echo CHtml::button('Изменить раздел', array('onClick' => 'editSelection()', 'class' => 'foton_btn')); ?></div>
            </div>
        </div>
	</div>

<script>

    thisSection = <? echo $firstId ?>;

    function changeSection(event) {
        oldSection = thisSection;
        $("#tableAddField" + oldSection).hide();
        $("#infoSection" + oldSection).hide();
        $("#sectionId" + oldSection).removeClass('active');
        thisSection = event.target.id.replace('sectionId', '');
        thisSection = thisSection.replace('sectionCountId', '');
        $("#sectionId" + thisSection).addClass('active');
        $("#tableAddField" + thisSection).show();
        $("#infoSection" + thisSection).show()
    }


    function editSelection() {
        location = 'additional_field_section_edit/' + thisSection;
    }

    function createField() {
        location = 'new_additional_field/' + thisSection;
    }

</script>
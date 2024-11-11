<?php $this->pageTitle = 'Безопасность | Настройки'; ?>
<div class="clients-hat clearfix">
    <div class="settings-name">
        <?php echo CHtml::link('Настройки', array('page/settings_additional_field')); ?>
        <img src="/img/right-arrow-button.svg">
        Безопасность
    </div>
    <div class="goback-link pull-right" style="margin-bottom: 25px;">

    </div>
</div>
<main class="content full2" role="main">
    <div class="box_edituser_left">
        <div class="edit_user_0anketa">
            <div class="content-01">
                <?php $this->renderPartial('settings_main_nav', array('security' => $security = true)); ?>
                <?php
                $this->widget('application.components.extended.web.widgets.MyCGridView', array(
                    'dataProvider' => $rangeIP_table_data,
                    'cssFile' => '',
                    'htmlOptions' => array('class' => 'main-table'),
                    'columns' => array(
                        array(
                            'name' => 'name',
                            'header' => 'Диапазон разрешенных IP адресов',
                            'type' => 'raw',
                            'headerHtmlOptions' => array('class' => 'w85'),
                            'value' => function ($data) {
                                return CHtml::submitButton(long2ip($data->begin_ip) . ' - ' . long2ip($data->end_ip), array("class" => "link_set", 'onClick' => 'ActionEdit(' . $data->id . ',"rangeIP")'));

                            }
                        ),
                        array(
                            'name' => 'comment',
                            'header' => 'Комментарий',
                            'type' => 'raw',
                            'headerHtmlOptions' => array('class' => '', 'style' =>
                                '   height: 11px;
                                    border-bottom: 1px solid #d9d9d9;
                                    padding: 8px 8px 8px 11px;
                                    text-align:left;
                                    font-size: 12px;
                                    color: #000000 !important;
                                    line-height: 12px;
                                    border-left: 1px solid #d9d9d9;'),
                            'value' => function ($data) {
                                return CHtml::submitButton($data->comment, array('style' => 'font-size: 12px;outline: none;background-color: #ffffff00;margin-left: -6px;border: 1px solid #ffffff00;'));

                            }
                        ),
                    )
                ));
                ?>

                <div class="settings_foot" style="padding-bottom: 250px;">
                    <div class="help-dropdown open">
                        <dl>
                            <dt class="simple none"><a class="add-btn__set popup-open" id="popup_new_range_button"
                                                       href="#popup-new-range">Добавить диапазон</a></dt>
                        </dl>
                    </div>
                    <?
                    if (isset($_GET['status']) && $_GET['status'] == 'error') { ?>
                        <div class="form-error" style="margin-left: 16px;margin-right: 16px;margin-top: 20px;">
                            <strong>Ошибка:</strong> диапазон IP адресов задан неправильно!
                        </div>
                    <? } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="right-sidebar">
            <div class="title_name_2">Справка
                <div class="more"><img src="/img/external-link-symbol.svg"><a href="https://inclient.ru/category/help-crm/" target="_blank" style="color: #707070;">Подробнее</a></div>
            </div>
            <div class="popup__form_actions">
                <p><strong>О диапазонах</strong></p><br>
                <p>Настройка отвечает за ограничение доступа пользователей к срм.</p><br>
                <p><strong>Как ограничить пользователя по IP</strong></p><br>
                <p>1) Добавьте диапазон IP адресов, которым разрешено подключиться к срм</p>
                <p>2) В профиле пользователя, которого нужно ограничить, установите "Ограничен по IP".</p>
                <p> Этот пользователь не сможет авторизоваться в срм, если его IP не совпадет с диапазоном разрешенных IP адресов. Узнать свой IP адрес можно здесь - <a href="https://yandex.ru/internet/" target="_blank">Яндекс.Интернет</a>.</p>
            </div>
    </div>
</main>
<?php $this->renderPartial('popup_create_range', array('rangeIP' => $rangeIP)); ?>
<?php $this->renderPartial('popup_call_edit_form'); ?>
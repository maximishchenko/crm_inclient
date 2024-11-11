<div class="clients-hat">
    <div class="settings-name">
        <?php echo CHtml::link('Настройки', array('page/settings_additional_field')); ?>
        <img src="/img/right-arrow-button.svg">
        <?php echo CHtml::link('Метки', array('page/settings_labels')); ?>
        <img src="/img/right-arrow-button.svg" alt="">
        <? echo '#' . $editLabel->id . ': ' . $editLabel->name; ?></span>
    </div>
    <div class="goback-link pull-right">
        <input class="btn_close" type="button" onclick="history.back();" value="❮  Назад "/>
    </div>
</div>

<main class="content full2" role="main">
    <?php
    $this->pageTitle = $editLabel->name . ' | Метки';

    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'edit-label',
    ));
    ?>


    <div class="edit_user_view">
        <div class="content-01">
            <div class="title_name_1">Настройка метки</div>

            <? if ($isSuccessSave) { ?>
                <script type="module">
                    import {NotificationBar} from '/js/notificationBar.js';

                    const notificationBar = new NotificationBar({
                        type: 'success',
                        title: 'Сохранено',
                        description: 'Метка успешно изменена'
                    });
                    notificationBar.show();
                </script>
            <? } ?>

            <div class="centre_settings" style="padding: 45px 145px 20px 107px;">
                <div><?php echo $form->error($editLabel, 'color', array('class' => 'form-error')); ?></div>
                <div class="block-info">
                    <div class="block-row">
                        <div class="row-label">
                            <span>Метка:<span class="star">*</span></span>
                        </div>
                        <div class="row-input" style="display: inline;">
                            <?php echo $form->textField($editLabel, 'name', array('class' => 'form-control', 'maxlength' => 100)); ?>
                            <?php echo $form->error($editLabel, 'name', array('class' => 'form-error')); ?>
                        </div>

                    </div>
                    <div class="block-row">
                        <div class="row-label">
                            <span>Заливка:<span class="star">*</span></span>
                        </div>
                        <div class="row-input">
                            <div id="selectedColor" style="background-color:<? echo $editLabel->color ?>"></div>
                            <?php echo $form->textField($editLabel, 'color', array('class' => 'form-control', 'maxlength' => 7, 'id' => 'Labels_color')); ?>
                        </div>
                    </div>

                    <div class="block-row">
                        <div class="block-colors">
                            <? foreach ($listColors as $value) { ?>
                                <div class="colors-item" style="background-color: <? echo $value; ?>"
                                     onclick="changeColorBackground(<? echo "'" . $value . "'"; ?>)">
                                </div>
                            <? } ?>
                        </div>
                    </div>
                    <div class="block-row_1 textColor">
                        <div class="row-label">
                            <span>Цвет текста:</span>
                        </div>
                        <div class="row-input hide">
                            <?php echo $form->textField($editLabel, 'color_text', array('class' => 'form-control', 'id' => 'Labels_color_text')); ?>
                        </div>
                        <div class="block-colorsText">
                            <? foreach ($listTextColors as $key => $value) { ?>
                                <div class="colorsText-item">
                                    <input type="radio" class="333" id="<? echo $value ?>"
                                           onclick="changeColorText(<? echo "'" . $value . "'"; ?>)">
                                    </input>
                                    <div><? echo $key ?></div>
                                </div>
                            <? } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="save_button">
                <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn', 'id' => 'save')); ?>
                <div id="preloader"></div>
            </div>


            <?php $this->endWidget();
            if ($accessDelete) {
                ?>

                <div class="function-delete delete_centre">
                    <a class="delete" href="#">Удалить метку</a>
                </div>

                <div class="function-delete-confirm delete_centre" style="display: none;">
                    <ul class="horizontal_3">
                        <li class="big">Подвердите удаление:</li>

                        <li style="padding-top: 9px;"> <?php echo CHtml::button("Удалить", array(
                                'onClick' => 'window.location.href = "' . Yii::app()->createUrl("page/Labels_delete",
                                        array("id" => $editLabel->id, 'type' => $type)) . '"',
                                'class' => 'btn',
                            )); ?>
                        </li>
                        <li><a href="#" class="cancel" style="margin-left: 10px;">Отмена</a></li>
                    </ul>
                </div>
            <? } ?>
        </div>
    </div>
    <div class="right-sidebar">
            <div class="title_name_2">Справка
                <div class="more"><img src="/img/external-link-symbol.svg"><a
                            href="https://inclient.ru/category/help-crm/" target="_blank"
                            style="color: #707070;">Подробнее</a>
                </div>
            </div>
            <div class="popup__form_actions">
                <ul>
                    <li>
                        <strong>О метках</strong>
                        <br>
                        <br>
                        Метка – идентификатор для обозначения статусов, категорий, пометок, важных замечаний и
                        т.д.
                        Используйте метки для более удобной работы с контактами, задачами и сделками.
                    </li>
                    <details class="help_0">
                        <summary class="help_1">Как установить свой цвет метки?</summary>
                        <p>В параметре «Заливка» заполните значение цвета – вставьте HEX код. <br><br>Например,
                            такой цвет: <br>
                            <span style="background-color:#dd4492;width: 10px;height: 10px;position: absolute;margin-top: 2px;"></span>
                            <span style="margin-left: 15px;"> Светло-вишнёвый Крайола - #dd4492</span></p>
                    </details>
                </ul>
            </div>
        </div>
</main>

<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
    id = $('#Labels_color_text').val();
    colorText = $('input[id="' + id + '"]');
    if (colorText[0]) {
        colorText[0].checked = true;
    } else {
        defaultRadioButton = $('input[type=radio]')[0];
        defaultRadioButton.checked = true;
        $('#Labels_color_text').val(defaultRadioButton.id);
    }

    $('#Labels_color').on('input keyup', function (e) {
        customColor = $('#Labels_color').val();
        $('#selectedColor').css('background-color', $('#Labels_color').val());
    });

    changeColorBackground = function (color) {
        $('#selectedColor').css('background-color', color);
        $('#Labels_color').val(color);
    };

    changeColorText = function (color) {
        listRadioButtons = $('input[type=radio]');
        for (var i = 0; i < listRadioButtons.length; i++) {
            listRadioButtons[i].id == color ?
                $('#Labels_color_text').val(color)
                : listRadioButtons[i].checked = false;
        }
    };

    $("#edit-label").submit(function () {
        $("#preloader").addClass('preloader');
        $("#save_and_create").hide();
        $("#save").hide();
    });

</script>
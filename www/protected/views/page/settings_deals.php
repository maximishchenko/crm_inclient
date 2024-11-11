<?php $this->pageTitle = 'Сделки | Настройки'; ?>
<div class="clients-hat">
    <div class="settings-name">
        <?php echo CHtml::link('Настройки', array('page/settings_additional_field')); ?>
        <img src="/img/right-arrow-button.svg">
        Сделки
    </div>
    <div class="goback-link pull-right" style="margin-bottom: 25px;">

    </div>
</div>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'reason',
    'htmlOptions' => [
        'class' => 'page-form deals'
    ]
    // 'enableAjaxValidation' => true,
));
?>

<main class="content full2 deals settingsLabels" role="main">
    <div class="box_edituser_left">
        <div class="edit_user_0anketa">
            <div class="content-01">
                <?php $this->renderPartial('settings_main_nav', array('deals' => true)); ?>

                <div class="user-table-block_pola fixWidth">
                    <ul id="ul-listTabs">
                        <?php
                        $typeDeals = $typeDeals ?? array_keys($listTabs)[0];
                        foreach ($listTabs as $key => $tab) {
                            echo
                                '<li id="' . $key . '" class="button-change-table sectorsBlock '
                                . ($typeDeals != $key ?: 'active') . '" onclick="changeTabs(' . "'" . $key . "'" . ');">' . $tab . '</li>';
                        }
                        ?>
                    </ul>
                </div>

                <? if ($isShowBlockSave) { ?>
                    <script type="module">
                        import {NotificationBar} from '/js/notificationBar.js';
                        const notificationBar = new NotificationBar({
                            type: 'success',
                            title: 'Сохранено',
                            description: 'Поле успешно изменено'
                        });
                        notificationBar.show();
                    </script>
                <? } ?>

                <div id="refusalBlock" class="hide">
                    <? if ($listTabs['refusal']) { ?>
                        <div class="centre_setting_3">
                            <div class="block-info editSelect" id="selectOptions">

                                <div class="block-row addSelectOption">
                                    <div class="row-label">Причины отказов:<span class="star">*</span></div>
                                    <div class="row-label label-weight">№:<span class="star">*</span></div>
                                </div>

                                <div class="block-options addSelectOption">
                                    <?
                                    foreach ($reasons as $reason) {
                                        $countClient = 0;
                                        $countClient = count(DealAndReason::model()->findAll('deals_reason_id = ' . $reason->id));
                                        ?>
                                        <div class="block-row">
                                            <div class="row-input input-option hide" id="inputId">
                                                <? echo CHtml::textField("Reasons[$reason->id][id]", $reason->id) ?>
                                            </div>
                                            <div class="row-input input-option">
                                                <? echo CHtml::textField("Reasons[$reason->id][name]", $reason->name, ['maxlength' => 80, 'placeholder' => 'Вариант ' . $reason->id, 'autocomplete' => 'off',]) ?>
                                            </div>

                                            <div class="row-input input-weight">
                                                <? echo CHtml::textField("Reasons[$reason->id][weight]", $reason->weight, ['maxlength' => 2, 'autocomplete' => 'off', 'class' => 'optionWeight', 'id' => "inputWeight", 'pattern' => "^[ 0-9]+$"]) ?>
                                            </div>

                                            <? if ($countClient < 1) { ?>
                                                <div class="row-input">
                                                    <img class="delDocument_set" onclick="deleteOption(event)"
                                                         src="/img/cancel_newdoc.svg" alt="">
                                                </div>
                                            <? } else { ?>
                                                <div class="row-input">
                                                    <img class="delDocument_set" id="delete<? echo $reason->id ?>"
                                                         onclick="showBlockDelete(event, <? echo $reason->id ?>)"
                                                         src="/img/cancel_newdoc.svg" alt="">
                                                </div>
                                            <? } ?>
                                        </div>

                                        <div class="delete-option hide" id="blockDelete<? echo $reason->id ?>">Есть проигранные сделки (<? echo $countClient ?> шт.), в которых, указана эта причина отказа. Если удалить, тогда сделки примут причину отказа по умолчанию. Затем нажмите "Сохранить". <a onclick="deleteOption(event, <? echo $reason->id ?>)"style="cursor: pointer;"> Удалить</a>
                                        </div>
                                        <?
                                    }

                                    $count = 1;
                                    foreach ($customReason as $value) { ?>
                                        <div class="block-row">
                                            <div class="row-input input-option">
                                                <? echo CHtml::textField("Reasons[exampleId" . $count . "][name]", $value['name'], ['maxlength' => 80, 'placeholder' => 'Вариант ' . $count, 'autocomplete' => 'off',]) ?>
                                            </div>

                                            <div class="row-input input-weight">
                                                <? echo CHtml::textField("Reasons[exampleId" . $count . "][weight]", $value['weight'], ['maxlength' => 2, 'autocomplete' => 'off', 'class' => 'optionWeight', 'id' => "inputWeight", 'pattern' => "^[ 0-9]+$"]) ?>
                                            </div>
                                            <div class="row-input">
                                                <img class="delDocument_set" onclick="deleteOption(event)"
                                                     src="/img/cancel_newdoc.svg" alt="">
                                            </div>
                                        </div>
                                        <?
                                        $count++;
                                    } ?>

                                </div>
                                <div class="block-row addSelectOption buttonAdd">
                                    <a class="add" id="addOption" href="#">Добавить</a>
                                </div>


                                <? if ($errorMessageList) {
                                    foreach ($errorMessageList as $error) {
                                        echo "
                                                <div class=\"block-row addSelectOption\">
                                                    <div class=\"custom-error\">$error[0]</div>
                                                </div>
                                            ";
                                        break;
                                    }
                                    ?>
                                <? } ?>

                            </div>
                        </div>
                    <? } ?>
                </div>

                <div class="save_button">
                    <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn', 'id' => 'save')); ?>
                    <div id="preloader"></div>
                </div>

                <?php $this->endWidget(); ?>

            </div>
        </div>
    </div>
    <div class="right-sidebar">
            <div class="title_name_2">Справка
                <div class="more"><img src="/img/external-link-symbol.svg"><a href="https://inclient.ru/category/help-crm/" target="_blank" style="color: #707070;">Подробнее</a></div>
            </div>
            <div class="popup__form_actions">
                <p><strong>Причины отказов</strong></p>
                <br>
                <p>Причины отказов используются в сделках. При закрытии проигранной сделки, нужно указать причину - почему контакт отказался от сделки. В будущем появится статистика отказов - это позволит понять, почему клиенты отказываются от сделок.</p>
            </div>
        </div>
</main>

<script>

    $("#reason").submit(function () {
        $("#preloader").addClass('preloader');
        $("#save_and_create").hide();
        $("#save").hide();
    });

    var tabActive = $('#ul-listTabs li.active');
    $('#' + tabActive[0].id + 'Block').show();
    changeTabs = function (tab) {
        tabActive.removeClass('active');
        $('#' + tabActive[0].id + 'Block').hide();

        tabActive = $('#' + tab);
        tabActive.addClass('active');
        $('#' + tabActive[0].id + 'Block').show();
    };

    var count = <?echo $count?>;

    $('#addOption').click(function () {
        event.preventDefault();
        var listOptions = $('.block-options.addSelectOption .block-row'),
            Weights = $('.optionWeight'),
            listWeight = [];
        // массив с весами
        for (var i = 0; i < Weights.length; i++) {
            listWeight.push(+Weights[i].value);
        }

        var weight = null;
        for (var i = 1; i <= listOptions.length; i++) {
            if (listWeight.indexOf(i) < 0) {
                weight = i;
                break;
            }
        }

        count++;

        weight = weight || listOptions.length + 1;
        var blockOption = '<div class="block-row">\n' +
            '                                <div class="row-input input-option">\n' +
            '                                    <input type="text" autocomplete="off" name="Reasons[exampleId' + count + '][name]" class="optionName" placeholder="Вариант ' + count + '" maxlength="80">' +
            '                                </div>\n' +
            '                                <div class="row-input input-weight">\n' +
            '                                    <input type="text" autocomplete="off" name="Reasons[exampleId' + count + '][weight]" id="inputWeight" pattern="^[ 0-9]+$" maxlength="2" class="optionWeight" value="' + weight + '">' +
            '                                </div>\n' +
            '                                <div class="row-input">\n' +
            '                                    <img class="delDocument_set" onclick="deleteOption(event)" src="/img/cancel_newdoc.svg" alt="">\n' +
            '                                </div>\n' +
            '                            </div>';
        var blockListOptions = $('.block-options.addSelectOption');
        blockListOptions.append(blockOption);
        changeShowButtonDelete();
    });

    deleteOption = function (event, id) {
        event.preventDefault();
        var blockOption = event.path[2];
        if (id) {
            var linkDelete = $('#delete' + id);
            linkDelete.show();
            $('#blockDelete' + id).hide();
            blockOption = linkDelete.closest('.block-row');
        }

        var listOptions = $('.block-options.addSelectOption .block-row');
        if (listOptions.length > 1) {
            blockOption.remove();
            $('#blockDelete' + id).remove();
            changeShowButtonDelete();
        } else {
            alert('Последнюю причину удалить невозможно')
        }
    };

    showBlockDelete = function (event, id) {
        event.preventDefault();
        var listOptions = $('.block-options.addSelectOption .block-row');
        if (listOptions.length > 1) {
            $('#blockDelete' + id).removeClass('hide');
        } else {
            alert('Последнюю причину удалить невозможно')
        }
    };

    function changeShowButtonDelete() {
        var listButtonDelete = $('.block-row').find('.delete');
        if (listButtonDelete.length <= 2) {
            $('.delete-option').addClass('hide');
            for (var i = 0; i <= listButtonDelete.length - 1; i++) {
                listButtonDelete[i].classList.add('hide');
            }
        } else {
            for (var i = 0; i <= listButtonDelete.length - 1; i++) {
                listButtonDelete[i].classList.remove('hide');
            }
            // затираем  ссылку "удалить" активного радиобаттон
            $("input:radio:checked").closest('.block-row').find('.delete').addClass('hide');

        }
    }

    cancelDelete = function (event, id) {
        event.preventDefault();
        $('#delete' + id).removeClass('hide');
        $('#blockDelete' + id).addClass('hide');
    };

    changeRadioActive = function (radioId) {
        $('input:radio').prop('checked', false);
        $('#radio' + radioId).prop("checked", true);
        // затираем  ссылку "блок опций удалить" активного радиобаттон
        var id = $("input:radio:checked").closest('.block-row').find('#inputId input').val();
        $('#blockDelete' + id).addClass('hide');
        changeShowButtonDelete();
    };

    function closeNotification(event) {
        var notification = event.target;
        notification = notification.closest('.save-message');
        notification.classList.add('hide');
    }
</script>

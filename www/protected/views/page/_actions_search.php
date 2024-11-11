<aside class="left-sidebar">
    <div class="box-gray__head">
        Поиск задач
    </div>

    <div class="box-gray__body" style="border-radius: 0px 0px 4px 4px;">
        <div class="box-gray__form">
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'enableAjaxValidation' => false,
                'method' => 'get',
            ));
            ?>

            <div class="form-group">
                <?php echo $form->textField($actions, 'keyword', array('type' => 'text', 'class' => 'form-control', 'placeholder' => 'Поиск')); ?>
            </div>
            <?php $role = UsersRoles::model()->find('user_id=' . Yii::app()->user->id)->itemname;
            ?>
            <div class="form-group">
                <label class="label">Ответственный:</label>
                <?php
                $role = UsersRoles::model()->find('user_id=' . Yii::app()->user->id)->itemname;
                $responsible_options = array('all' => 'Все пользователи', Yii::app()->user->id => 'Я ответственный', 'director' => 'Руководители', 'manager' => 'Менеджеры', 'admin' => $user->parent->first_name);

                $managers = Users::getUserAccess($user, true, false, true);
                $directors = Users::getUserAccess($user, false, true, true);
                if ($user->parent->roles[0]->name != 'admin' || $user->common_access == Users::ACCESS_EMBAGRO
                    || $user->common_access == Users::ACCESS_MANAGER || $user->roles[0]->name == 'admin'
                ) {
                    unset($responsible_options['admin']);
                }

                if (count($directors) <= 0) {
                    unset($responsible_options['director']);
                }

                if (count($managers) <= 0) {
                    unset($responsible_options['manager']);
                }
                $IamResponsible = false;
                // выбор значений в селекторах с ролями и пользователями
                if ($actions->responsable_id == Yii::app()->user->id) {
                    $selected_option = array('i' => array('selected' => true));
                    $IamResponsible = true;
                } elseif ($actions->responsable_id == 'no') {
                    $selected_option = array('no' => array('selected' => true));
                } elseif ($actions->responsable_id == $user->parent_id) {
                    $selected_option = array('admin' => array('selected' => true));
                } else {
                    $selected_option = array('all' => array('selected' => true));
                }

                $directors_block_to_display = '';
                $managers_block_to_display = '';

                if (is_numeric($actions->responsable_id) && $actions->responsable_id != 0) {
                    $client_resp_role = UsersRoles::model()->find('user_id=' . $actions->responsable_id);
                    if ($client_resp_role->itemname == 'director') {
                        $selected_option = array('director' => array('selected' => true));
                    } elseif ($client_resp_role->itemname == 'manager') {
                        if (!$IamResponsible) {
                            $selected_option = array('manager' => array('selected' => true));
                        }
                    }
                    $directors_block_to_display = $client_resp_role->itemname == 'director' ? 'style="display:block"' : '';
                    $managers_block_to_display = $client_resp_role->itemname == 'manager' && !($IamResponsible && $role == 'manager') ? 'style="display:block"' : '';
                }
                ?>
                <?php echo $form->dropDownList($actions, 'responsable_id', $responsible_options, array('options' => $selected_option, 'class' => 'styled permis editable typeAccess', 'name' => 'type')); ?>
            </div>
            <div class="access-options access-tab" id="director" <?php echo $directors_block_to_display ?>>
                <?php if (count($directors) > 0) {
                    echo $form->dropDownList($actions, 'director_id', CHtml::listData($directors, 'id', 'first_name'), array('options' => is_numeric($actions->responsable_id) && $actions->responsable_id != 0 ? array($actions->responsable_id => array('selected' => true)) : '', 'class' => 'styled'));
                }
                ?>
            </div>
            <div class="access-options access-tab" id="manager" <?php echo $managers_block_to_display ?>>
                <?php echo $form->dropDownList($actions, 'manager_id', CHtml::listData($managers, 'id', 'first_name'), array('options' => is_numeric($actions->responsable_id) && $actions->responsable_id != 0 ? array($actions->responsable_id => array('selected' => true)) : '', 'class' => 'styled')); ?>
            </div>
            <?php if ($term != '2' && $term != '4') { ?>
                <div class="solid_an_client">
                    <label class="label">Состояние:</label>
                    <select name="Actions[action_status_id]" class="styled status circle"
                            data-placeholder="Все состояния">
                        <?php
                        $statuses_array = ActionsStatuses::model()->findAll();
                        echo '<option value=0>Все состояния</option>';
                        foreach ($statuses_array as $status) {
                            if ($term == '3' && $status->name == 'Ожидается') {
                                continue;
                            }
                            echo '<option ' . ($actions->action_status_id == $status->id ? ' selected="selected"' : '') . '" value="' . $status->id . '">' . $status->name . '</option>';
                        }
                        ?>
                    </select>
                </div>
            <?php } ?>

            <?php if ($term != '1') { ?>
                <div class="form-group">
                    <label class="label">Дата создания:</label>
                    <?php echo $this->widget('ext.CJuiDateTimePicker.CJuiDateTimePicker', array(
                        'name' => 'Actions[start_date]',
                        'model' => $actions,
                        'attribute' => 'start_date',
                        'language' => 'ru',
                        'options' => array(
                            'dateFormat' => 'dd.mm.yy',
                            'changeMonth' => 'true',
                            'changeYear' => 'true',
                            'showButtonPanel' => true,
                            'beforeShow' => new CJavaScriptExpression('function(element){dataPickerFocus = $(element).attr(\'id\').trim();}')
                        ),
                        'htmlOptions' => array(
                            'class' => 'form-control',
                            'placeholder' => 'От'
                        ),
                    ), true); ?>
                </div>
                <div class="solid_an_client">
                    <?php echo $this->widget('ext.CJuiDateTimePicker.CJuiDateTimePicker', array(
                        'name' => 'Actions[stop_date]',
                        'model' => $actions,
                        'attribute' => 'stop_date',
                        'language' => 'ru',
                        'options' => array(
                            'dateFormat' => 'dd.mm.yy',
                            'changeMonth' => 'true',
                            'changeYear' => 'true',
                            'showButtonPanel' => true,
                            'beforeShow' => new CJavaScriptExpression('function(element){dataPickerFocus = $(element).attr(\'id\').trim();}')
                        ),
                        'htmlOptions' => array(
                            'class' => 'form-control',
                            'placeholder' => 'До'
                        ),
                    ), true); ?>
                </div>
            <?php } ?>

            <div class="form-group">
                <? if (count($allLabels) > 0) { ?>
                    <div class="label_info bottom_10">
                        Метки:
                        <a class="delete" id="editLabels" onclick="return false;">Редактировать</a>
                    </div>

                    <div class="solid_an_client">
                        <div class="customDropDownListLabels hide">
                            <ul>
                                <? foreach ($allLabels as $label) { ?>
                                    <li id="labelLi <? echo $label->id ?>" class="labelLi"
                                        name="Clients[labelLi<? echo $label->id ?>]"
                                        onclick="changeLabel('<? echo $label->id; ?>');">
                                        <?
                                        echo $form->checkBox($actions, "Labels[$label->id]", [
                                            'id' => 'checkbox' . $label->id,
                                            'class' => 'hide',
                                            'checked' => isset($customSelectedLabels[$label->id])
                                        ]);
                                        $operType = isset($customSelectedLabels[$label->id]) ?
                                            'added' : 'deleted';
                                        ?>
                                        <div class="<? echo $operType; ?>"
                                             id="blockOper<? echo $label->id; ?>"></div>
                                        <div class="block-color" id="labelColor<? echo $label->id; ?>"
                                             style="background-color: <? echo $label->color ?>"></div>
                                        <span id="labelText<? echo $label->id; ?>"><? echo $label->name ?></span>
                                    </li>
                                <? } ?>

                            </ul>
                        </div>

                        <div class="block-labelsInProfile">
                            <? if (count($customSelectedLabels) > 0) {
                                foreach ($customSelectedLabels as $label) { ?>
                                    <div class="block-elem" id="blockElem<? echo $label->id ?>">
                                        <div class="block-color"
                                             style="background-color: <? echo $label->color ?>"></div>
                                        <span><? echo $label->name ?></span>
                                    </div>
                                <? }
                            } else {
                                echo '<span id="selAllLabels">Все метки</span>';
                            }
                            ?>
                        </div>
                    </div>
                <? } ?>
            </div>

            <div class="form-group">
                <?php
                echo $form->checkBox($actions, 'documents', array('class' => 'styled'));
                echo CHtml::label(' С файлами', 'documentClient') . ' ';
                ?>
            </div>

            <div class="form-group form-group-btn">
                <?php echo CHtml::submitButton('Найти', array('class' => 'btn white')); ?>
            </div>
            <?php echo CHtml::hiddenField('Actions[search]', 'true'); ?>
            <?php $this->endWidget(); ?>
        </div>
    </div>

</aside><!--.left-sidebar -->

<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<script>
    // устанавливаем выбранные ранее метки, на случай если валидация в методе не прошла
    var listLabels = $(".block-labelsInProfile .block-elem") || [];
    for (var i = 0; i < listLabels.length; i++) {
        var labelId = listLabels[i].id.replace('blockElem', '');
        var elem = $('#blockOper' + labelId);
        $('#checkbox' + labelId).prop('checked', true);
        elem.removeClass('deleted');
        elem.addClass('added');
    }

    changeLabel = function (labelId) {
        var elem = $('#blockOper' + labelId),
            divColor = $('#labelColor' + labelId)[0].outerHTML,
            spanText = $('#labelText' + labelId)[0].outerHTML;

        if ($('#checkbox' + labelId).is(':checked')) {
            $('#checkbox' + labelId).prop('checked', false);
            elem.removeClass('added');
            elem.addClass('deleted');
            $('#blockElem' + labelId).remove();
        } else {
            $('#checkbox' + labelId).prop('checked', true);
            elem.removeClass('deleted');
            elem.addClass('added');
            var blockShowLabels = $('.block-labelsInProfile'),
                labelDIv = '<div class="block-elem" id="blockElem' + labelId + '">' + divColor + spanText + '</div>';
            blockShowLabels.append(labelDIv);
        }

        if (document.querySelector(".block-labelsInProfile .block-elem")) {
            $('#selAllLabels').remove();
        } else {
            $('.block-labelsInProfile').append('<span id="selAllLabels">Все метки</span>');
        }
    };

    $("#editLabels").click(function (e) {
        var listLabels = $(".customDropDownListLabels");
        if (listLabels.hasClass('hide')) {
            listLabels.removeClass('hide');
        } else {
            listLabels.addClass('hide');
        }
    });

    jQuery(function ($) {
        $(document).mouseup(function (e) { // событие клика по веб-документу
            var div = $(".customDropDownListLabels"); // тут указываем ID элемента
            if (!div.is(e.target) && div.has(e.target).length === 0 && !$("#editLabels").is(e.target)) {//&& div.has(e.target).length === 0) { // и не по его дочерним элементам
                div.addClass('hide'); // скрываем его
            }

            if (!$(".color-customDropDawnList").is(e.target)) {
                $(".color-customDropDawnList").hide();
            }
        });
    });
</script>
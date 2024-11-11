<aside class="left-sidebar">
    <div class="box-gray__head">
        Поиск контактов
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
                <?php echo $form->textField($clients, 'keyword', array('type' => 'text', 'class' => 'form-control', 'placeholder' => 'Поиск')); ?>
            </div>
            <?php $role = $user->roles[0]->name ?>

            <div class="form-group">
                <label class="label">Ответственный:</label>
                <?php
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
                if ($clients->responsable_id == Yii::app()->user->id) {
                    $selected_option = array('i' => array('selected' => true));
                    $IamResponsible = true;
                } elseif ($clients->responsable_id == 'no') {
                    $selected_option = array('no' => array('selected' => true));
                } elseif ($clients->responsable_id == $user->parent_id) {
                    $selected_option = array('admin' => array('selected' => true));
                } else {
                    $selected_option = array('all' => array('selected' => true));
                }


                $directors_block_to_display = '';
                $managers_block_to_display = '';

                if (is_numeric($clients->responsable_id) && $clients->responsable_id != 0) {
                    $client_resp_role = UsersRoles::model()->find('user_id=' . $clients->responsable_id);
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
                <?php echo $form->dropDownList($clients, 'responsable_id', $responsible_options, array('options' => $selected_option, 'class' => 'styled permis editable typeAccess', 'name' => 'type')); ?>
            </div>


            <div class="access-options access-tab" id="director" <?php echo $directors_block_to_display ?>>
                <?php if (count($directors) > 0) {
                    echo $form->dropDownList($clients, 'director_id', CHtml::listData($directors, 'id', 'first_name'), array('options' => is_numeric($clients->responsable_id) && $clients->responsable_id != 0 ? array($clients->responsable_id => array('selected' => true)) : '', 'class' => 'styled'));
                }
                ?>

            </div>
            <div class="access-options access-tab" id="manager" <?php echo $managers_block_to_display ?>>
                <?php echo $form->dropDownList($clients, 'manager_id', CHtml::listData($managers, 'id', 'first_name'), array('options' => is_numeric($clients->responsable_id) && $clients->responsable_id != 0 ? array($clients->responsable_id => array('selected' => true)) : '', 'class' => 'styled')); ?>
            </div>

            <!-- Воронка -->
            <? if (count($listStep) > 0) { ?>
                <div class="solid_an_client">
                    <label class="label">Воронка:</label>


                    <?php echo $form->dropDownList($selectedSteps, 'steps_id',
                        CHtml::listData($listStep, 'id', 'name'), ['class' => 'styled', 'onChange' => 'changeStep()', 'id' => 'selectStep']); ?>


                    <? if ($isNotStepOptions = isset($listStepOption[$selectedSteps->steps_id])) {
                        $selectedOption = $listStepOption[$selectedSteps->steps_id][0];
                        foreach ($listStepOption[$selectedSteps->steps_id] as $option) {
                            if ($option->id == $selectedSteps->selected_option_id) {
                                $selectedOption = $option;
                                break;
                            }
                        }
                    } else {
                        $selectedOption = (object)['color' => '', 'id' => '', 'name' => ''];
                    }

                    // для JS
                    $listStepOptionJS = [];
                    foreach ($listStepOption as $stepID => $options) {
                        foreach ($options as $key => $option) {
                            $listStepOptionJS[$stepID][] = $option->attributes;
                        }
                    }
                    ?>

                    <div class="row-input" id="colorSelect"
                         style="display: <? echo $isNotStepOptions ? 'inline-flex' : 'none' ?>">
                        <div class="jq-selectbox__select color-select client" onclick="showDropDawnColor(event)">
                            <div class="color-block color-block-search"
                                 style="background-color: <? echo $selectedOption->color ?>">
                                <span><? echo $selectedOption->name ?> </span>
                                <input type="text" value="<? echo $selectedOption->id ?>" class="hide"
                                       name="StepsInClients[selected_option_id]">
                            </div>
                            <div class="jq-selectbox__trigger">
                                <div class="jq-selectbox__trigger-arrow"></div>
                            </div>
                        </div>

                        <div class="color-customDropDawnList color-customDropDawnListsearch client shortWidth hide">
                            <ul>
                                <?
                                if ($isNotStepOptions) {
                                    foreach ($listStepOption[$selectedSteps->steps_id] as $id => $option) {
                                        echo "<li value='$id' onclick='changeColor(event, " . '"' . $option->color . '",' . " " . '"' . $option->name . '", ' . $option->id . ")'><div class='block-color' style='background-color:$option->color;'></div><div class='margin-top-1'>$option->name</div></li>";
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>

                    <div class="step-progressBar step-progressBar-search"
                         style="display: <? echo $isNotStepOptions ? 'inline-flex' : 'none' ?>">
                        <? if ($isNotStepOptions) { ?>
                            <?
                            $isGrey = false;
                            foreach ($listStepOption[$selectedSteps->steps_id] as $id => $option) {
                                $color = $isGrey ? 'darkgrey' : $option->color;
                                echo "<div class='progressBar-elem' style='background-color:" . $color . "' ></div>";
                                if ($option->id == $selectedSteps->selected_option_id) {
                                    $isGrey = true;
                                }
                            } ?>
                        <? } ?>
                    </div>
                </div>
            <? } ?>
            <?php
            ?>


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
                                    echo $form->checkBox($clients, "Labels[$label->id]", [
                                        'id' => 'checkbox' . $label->id,
                                        'class' => 'hide',
                                        'checked' => isset($customSelectedLabels[$label->id])
                                    ]);
                                    $operType = isset($customSelectedLabels[$label->id]) ?
                                        'added' : 'deleted';
                                    ?>
                                    <div class="<? echo $operType; ?>" id="blockOper<? echo $label->id; ?>"></div>
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

                    <? } ?>
                </div>

                <div class="form-group">
                    <?php
                    echo $form->checkBox($clients, 'documents', array('class' => 'styled'));
                    echo CHtml::label(' С файлами', 'documentClient') . ' ';
                    ?>
                </div>
                <div class="form-group form-group-btn">
                    <?php echo CHtml::submitButton('Найти', array('class' => 'btn white')); ?>
                </div>
                <?php echo CHtml::hiddenField('Clients[search]', 'true'); ?>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</aside><!--.left-sidebar -->

<script>
    // устанавливаем выбранные ранее метки, на случай если валидация в методе не прошла
    var listLabels = $(".block-labelsInProfile .block-elem") || [],
        listOption = <?echo json_encode($listStepOptionJS)?>;


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


    function showDropDawnColor(event) {
        let gh = event.target.closest('#colorSelect').children[1];
        gh.style.display = 'block';
    }

    function changeColor(event, color, name, id) {
        let colorBlock = event.target.closest('#colorSelect').querySelector('.color-block'),
            inputColorBlock = colorBlock.querySelector('input'),
            collectionOptions = document.getElementById("selectStep").options,
            listOptionSelected = listOption[collectionOptions[collectionOptions.selectedIndex].value],
            stepProgressBar = document.getElementsByClassName("step-progressBar-search")[0],
            spanText = colorBlock.querySelector('span');
        colorBlock.style.backgroundColor = color;
        inputColorBlock.value = id;
        spanText.textContent = name;

        if (listOptionSelected) {
            stepProgressBar.children = null;
            let listElem = '',
                isGrey = false;
            for (let i = 0; i < listOptionSelected.length; i++) {
                listElem += '<div class="progressBar-elem" style="background-color:' + (isGrey ? 'darkgrey' : listOptionSelected[i].color) + '"> </div>';
                if (id == listOptionSelected[i].id) {
                    isGrey = true;
                }
            }
            stepProgressBar.innerHTML = listElem;
        }
    }

    function changeStep() {
        let collectionOptions = document.getElementById("selectStep").options,
            listOptionSelected = listOption[collectionOptions[collectionOptions.selectedIndex].value],
            selectOptions = document.querySelector(".color-customDropDawnListsearch"),
            colorBlock = document.getElementsByClassName("color-block-search")[0],
            stepProgressBar = document.getElementsByClassName("step-progressBar-search")[0],
            ul = document.createElement('ul');
        ul.innerHTML = '';
        document.getElementById("colorSelect").style.display = 'inline-flex';
        stepProgressBar.style.display = 'inline-flex';
        if (listOptionSelected) {
            for (let i = 0; i < listOptionSelected.length; i++) {
                ul.innerHTML += "<li value='" + listOptionSelected[i].id + "' onclick='changeColor(event, " + '"' + listOptionSelected[i].color + '"' + ", " + '"' + listOptionSelected[i].name + '", ' + listOptionSelected[i].id + ");'><div class='block-color' style='background-color:" + listOptionSelected[i].color + ";'></div><div class='margin-top-1'>" + listOptionSelected[i].name + "</div></li>";
            }
            selectOptions.replaceChild(ul, selectOptions.children[0]);
            colorBlock.style.backgroundColor = listOptionSelected[0].color;
            colorBlock.children[0].textContent = listOptionSelected[0].name;
            colorBlock.children[1].value = listOptionSelected[0].id;

            let listElem = '';
            for (let i = 0; i < listOptionSelected.length; i++) {
                listElem += '<div class="progressBar-elem" style="background-color:' + (i ? 'darkgrey' : listOptionSelected[i].color) + '"> </div>';
            }
            stepProgressBar.innerHTML = listElem;
        } else {
            document.getElementById("colorSelect").style.display = 'none';
            stepProgressBar.style.display = 'none';
        }
    };
</script>
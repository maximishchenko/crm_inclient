var is_edit_form = false;

$('body').on('change', '#edit-clients-form', function () {
    is_edit_form = true;
});

$("table").removeClass("items");
$('#sumary-div').append($('.summary').html());
$('.summary').html('').hide();


var filter_step = 0;
var filter_step_option = 0;
var filter_step_text = 'Нет воронки';
var filter_step_option_text = '';
var filter_master_type = '';
var filter_master_type_text = 'Я ответственный';
var filter_master_id = '';
var filter_master_id_text = '';


$('body').on('change', '.row-ch', function () {
    if ($(this).is(":checked")) {
        // $(this).parent().parent().parent().parent().addClass('sel-row')
        let g = $(this).closest('.new-table');
        $(this).closest('.clients-page-row').addClass('sel-row');
    } else {
        $(this).closest('.clients-page-row').removeClass('sel-row');
        //$(this).parent().parent().parent().parent().removeClass('sel-row')
    }
    if ($('.row-ch:checked').length) {
        $(".sel-link").removeClass('disbl');
        $('#sel-sumary-div').html("Выбрано: " + $('.row-ch:checked').length);
    } else {
        $(".sel-link").addClass('disbl');
        $('#sel-sumary-div').html("");
    }
    $('#del_cnt').html($('.row-ch:checked').length);
});

function ClosePopup() {
    $('.multi-popap').addClass('hide');
}

$(document).ready(function () {
    //отображение попапа
    $('.show-popap').on('click', function () {

        $('.form-box').addClass('hide');
        var target = $(this).data('target');
        if ($(this).hasClass('disbl')) return false;
        $('.multi-popap[id!=' + target + ']').addClass('hide');
        if ($('#' + target).hasClass('hide')) {
            $('#' + target).removeClass('hide');
        } else {
            $('#' + target).addClass('hide');
        }
    });

    //отображение скрытой формы
    $('.show-form').on('click', function () {

        $('.multi-popap').addClass('hide');
        var target = $(this).data('target');

        if ($(this).hasClass('disbl')) return false;
        $('.form-box[id!=' + target + ']').addClass('hide');
        if ($('#' + target).hasClass('hide')) {
            $('#' + target).removeClass('hide');
        } else {
            $('#' + target).addClass('hide');
        }
    });

    $('#editEvetnsBtn').on('click', function () {
        $('#status-div-edit-form li:eq(0)').removeClass('hide').trigger("click");
        $('#type_event option[value=0]').remove();
        $('#type_event').prepend('<option value="0">Выберите ответственного</option>');
        $('#type_event').prop('selectedIndex', 0);
        $('.access-tab').css('display', 'none');
        $('#type_event').trigger('refresh');
        $('#client-form-el').addClass('hide');
        $('#event-add-el').addClass('hide');
        $('#event-edit-el').removeClass('hide');
    });

    $('#addEvetnsBtn').on('click', function () {

        $('#status-div-edit-form li:eq(0)').addClass('hide');
        $('#status-div-edit-form li:eq(1)').trigger("click");
        $('#type_event option[value=0]').remove();
        $('#type_event').prop('selectedIndex', 0);
        $('#type_event').trigger('refresh');
        $('.access-tab').css('display', 'none');
        $('#filter-type option[value=0]').removeClass('hide');
        $('#client-form-el').removeClass('hide');
        $('#event-edit-el').addClass('hide');
        $('#event-add-el').removeClass('hide');
    });


    //задачи с событиями

    $('#setActionLabelBtn').on('click', function () {  // сохранение меток

        var values = $(".row-ch:checked").map(function () {
            return this.value;
        }).get();
        var levels = [];
        var levels_text = [];

        $('.label-form.added').each(function (i, elem) {
            levels.push($(this).data('id'));
            levels_text.push($(this).data('text'));
        });
        $(this).after('<div class="preloader-centre"><img src="/img/preloader/103.gif"></div>');
        $.ajax({
            url: '/page/set_levels_actions',
            type: 'POST',
            data: {
                rows: values,
                levels_list: levels,
                msg: ' <strong>Метка изменена </strong> <br> Новая метка: ' + levels_text.join(', ') + '<br>Задачи: ' + $('.row-ch:checked').length

            },
            success: function (response) {
                location.reload();
            }
        });
    });

    $('#setActionMasterBtn').on('click', function () {

        var values = $(".row-ch:checked").map(function () {
            return this.value;
        }).get();

        var master_text = filter_master_id_text;

        if (parseInt(filter_master_type) || filter_master_type.length < 1) {
            master_text = 'Я ответственный'
        }
        $(this).after('<div class="preloader-centre"><img src="/img/preloader/103.gif"></div>');

        $.ajax({
            url: '/page/set_master_actions',
            type: 'POST',
            data: {
                rows: values,
                master: filter_master_type,
                master_id: filter_master_id,
                msg: ' <strong>Ответственный изменен</strong> <br>Новый ответственный: ' + master_text + '<br>Задачи: ' + $('.row-ch:checked').length

            },
            success: function (response) {
                location.reload();
            }
        });
    });


    $('#setDateEventsBtn').on('click', function () {

        var values = $(".row-ch:checked").map(function () {
            return this.value;
        }).get();

        $(this).after('<div class="preloader-centre"><img src="/img/preloader/103.gif"></div>');

        $.ajax({
            url: '/page/set_date_actions',
            type: 'POST',
            data: {
                rows: values,
                date: $("#mass_date").val(),
                msg: ' <strong>Дата задачи изменена</strong>  <br> Новая дата: ' + $("#mass_date").val() + '<br>Задачи: ' + $('.row-ch:checked').length

            },
            success: function (response) {
                location.reload();
            }
        });
    });

    $('#setStateActionsBtn').on('click', function () {

        var values = $(".row-ch:checked").map(function () {
            return this.value;
        }).get();
        $(".form-error").remove();
        if ($('#mass_status').val() == 0) {
            $('#colorSelectMassType').after('<div class="form-error">Выберите одно из состояний</div>');
            return false;
        }
        $(this).after('<div class="preloader-centre"><img src="/img/preloader/103.gif"></div>');


        $.ajax({
            url: '/page/set_state_actions',
            type: 'POST',
            data: {
                rows: values,
                state: $("#mass_status").val(),
                msg: ' <strong>Состояние задачи изменено</strong>  <br>Новое состояние: ' + $("#mass_status").data('title') + '<br>Задачи: ' + $('.row-ch:checked').length

            },
            success: function (response) {
                location.reload();
            }
        });
    });

    $('#saveAddAction').on('click', function () {

        var clients = [];
        var values = $('#client_search').serializeArray()
        $('#client_search option').each(function (i, elem) {
            clients.push($(this).val())
        });
        $(".form-error").remove();
        $(".error").removeClass('error');
        if ($('#actionTitle').val() == '') {
            $('#actionTitle').after('<div class="form-error">Обязательное поле</div>');
            $('#actionTitle').addClass('error');

        }
        if ($('#actionDate').val() == '') {
            $('#actionDate').after('<div class="form-error">Обязательное поле</div>');
            $('#actionDate').addClass('error');

        } else if (!validate_date($('#actionDate').val())) {
            $('#actionDate').after('<div class="form-error">Неверная дата</div>');
            $('#actionDate').addClass('error');

        }
        if ($('#action_status_id').val() == 0) {
            $('#colorSelectForm').after('<div class="form-error">Обязательное поле</div>');

        }

        if (clients.length < 1) {
            $('.holder').after('<div class="form-error">Обязательное поле</div>');
            $('.holder').addClass('error');

        }
        if ($(".form-error").length) {
            return false;
        }
        var form_data = $('#edit-event-form').serializeArray()
        var checked = []
        $("input[name='act_labels[]']:checked").each(function () {
            checked.push(parseInt($(this).val()));
        });

        $(this).after('<div style="float:right;margin-top: 5px;"><img src="/img/preloader/103.gif"></div>');
        $.ajax({
            url: '/page/set_action_actions',
            type: 'POST',
            data: {
                clients: clients,
                lebels: checked,
                desc: $('#Actions_description').val(),
                date: $('#actionDate').val(),
                title: $('#actionTitle').val(),
                type_id: $('#type_event').val(),
                status: $('#action_status_id').val(),
                director_id: $('#form_director_id').val(),
                manager_id: $('#form_manager_id').val(),
                msg: ' Тема задачи: ' + $('#actionTitle').val() + ' <br>Контакты: ' + clients.length + '. Задачи: ' + clients.length
                // data: form_data,
            },
            success: function (response) {
                location.reload();
            }
        });
    });

    var changed_fields = [];
    $('#saveEditAction').on('click', function () {
        $('#editErr').hide();

        if (changed_fields.length === 1 && changed_fields.indexOf('action_status_id') + 1
            && $('#action_status_id').val() == 0) {
            $('#editErr').show();
            return false;
        }

        var values = $(".row-ch:checked").map(function () {
            return this.value;
        }).get();

        var form_data = $('#edit-event-form').serializeArray()
        var checked = []
        $("input[name='act_labels[]']:checked").each(function () {
            checked.push(parseInt($(this).val()));
        });

        $(this).after('<div style="float:right;margin-top: 5px;"><img src="/img/preloader/103.gif"></div>');
        $.ajax({
            url: '/page/set_edit_actions',
            type: 'POST',
            data: {
                rows: values,
                fields: changed_fields,
                lebels: checked,
                description: $('#Actions_description').val(),
                action_date: $('#actionDate').val(),
                text: $('#actionTitle').val(),
                type_id: $('#type_event').val(),
                action_status_id: $('#action_status_id').val(),
                director_id: $('#form_director_id').val(),
                manager_id: $('#form_manager_id').val(),
                msg: ' <strong>Сохранено</strong> <br> Информация в задаче изменена<br> Задачи: ' + values.length
                // data: form_data,
            },
            success: function (response) {
                location.reload();
            }
        });
    });

    $('.to_change').on('change', function () {
        if (changed_fields.indexOf($(this).data('name')) < 0) {
            changed_fields.push($(this).data('name'));
        }
    });


    filter_master_id = $("#filter-type").val();
    filter_master_type_text = 'Я ответственный';
    if ($('.row-ch:checked').length) {
        $(".sel-link").removeClass('disbl');
        $('#sel-sumary-div').html("Выбрано: " + $('.row-ch:checked').length);
    }


    $('#managerFilterSelect, #directorFilterSelect').on('change', function () {
        filter_master_id = $(this).val();
        filter_master_id_text = $(this).find("option:selected").text();
    });

    $('.check_edit').on('change', function () {
        $(this).prev(".isEdit").val(1);
        $(this).parent().parent().find(".isEdit").val(1);
    });
    $('#close-msg-btn').on('click', function () {
        $('#msg_div').html('');
        $('#msgBox').addClass('hide');
    });


    $('#addActionBtn').on('click', function () {

        var values = $(".row-ch:checked").map(function () {
            return this.value;
        }).get();
        $(".form-error").remove();
        $(".is_validate").removeClass('error');
        if ($('#actionTitle').val() == '') {
            $('#actionTitle').after('<div class="form-error">Обязательное поле</div>');
            $('#actionTitle').addClass('error');

        }
        if ($('#actionDate').val() == '') {
            $('#actionDate').after('<div class="form-error">Обязательное поле</div>');
            $('#actionDate').addClass('error');

        }
        if (!validate_date($('#actionDate').val())) {
            $('#actionDate').after('<div class="form-error">Неверная дата</div>');
            $('#actionDate').addClass('error');

        }
        if ($(".form-error").length) {
            return false;
        }
        var form_data = $('#edit-event-form').serializeArray()
        var checked = []
        $("input[name='act_labels[]']:checked").each(function () {
            checked.push(parseInt($(this).val()));
        });

        $(this).after('<div style="float:right;margin-top: 5px;"><img src="/img/preloader/103.gif"></div>');
        $.ajax({
            url: '/page/set_action_actions',
            type: 'POST',
            data: {
                clients: values,
                lebels: checked,
                desc: $('#Actions_description').val(),
                date: $('#actionDate').val(),
                title: $('#actionTitle').val(),
                type_id: $('#type_event').val(),
                status: $('#action_status_id').val(),
                director_id: $('#Actions_director_id').val(),
                manager_id: $('#Actions_manager_id').val(),
                msg: 'Тема задачи: ' + $('#actionTitle').val() + ' <br>Контакты: ' + $('.row-ch:checked').length
                // data: form_data,
            },
            success: function (response) {
                location.reload();
            }
        });
    });

    $('#setStepBtn').on('click', function () {
        // Сохранить Этапы 
        var values = $(".row-ch:checked").map(function () {
            return this.value;
        }).get();

        $(this).after('<div class="preloader-centre"><img src="/img/preloader/103.gif"></div>');
        $.ajax({
            url: '/page/set_step_clients',
            type: 'POST',
            data: {
                clients: values,
                step: filter_step,
                step_option: filter_step_option,
                msg: ' <strong>Воронка изменена </strong> <br>' + filter_step_text + ': ' + filter_step_option_text + '<br>Контакты: ' + $('.row-ch:checked').length
            },
            success: function (response) {
                location.reload();
            }
        });
    });

    $('#setLabelBtn').on('click', function () {
        // Сохранить Этапы
        var values = $(".row-ch:checked").map(function () {
            return this.value;
        }).get();
        var levels = [];
        var levels_text = [];

        $('.label-form.added').each(function (i, elem) {
            levels.push($(this).data('id'));
            levels_text.push($(this).data('text'));
        });
        if (levels.length > 0) {
            $(this).after('<div class="preloader-centre"><img src="/img/preloader/103.gif"></div>');
            $.ajax({
                url: '/page/set_label_clients',
                type: 'POST',
                data: {
                    clients: values,
                    levels_list: levels,
                    msg: ' <strong>Метка изменена </strong> <br> Новая метка: ' + levels_text.join(', ') + '<br>Контакты: ' + $('.row-ch:checked').length

                },
                success: function (response) {
                    location.reload();
                }
            });
        } else {
            $('#notSelectedLabelMessage').show();
        }
    });

    $('#setMasterBtn').on('click', function () {
        // Сохранить Этапы
        var values = $(".row-ch:checked").map(function () {
            return this.value;
        }).get();

        var master_events = 0;

        if ($('#master-events').is(":checked")) {
            master_events = 1
        }
        var master_text = filter_master_id_text;

        if (parseInt(filter_master_type) || master_text == '') {
            master_text = 'Я ответственный'
        }
        $(this).after('<div class="preloader-centre"><img src="/img/preloader/103.gif"></div>');

        $.ajax({
            url: '/page/set_master_clients',
            type: 'POST',
            data: {
                clients: values,
                master: filter_master_type,
                master_id: filter_master_id,
                is_event: master_events,
                msg: ' <strong>Ответственный изменен</strong> <br> Новый ответственный: ' + master_text + '<br>Контакты: ' + $('.row-ch:checked').length

            },
            success: function (response) {
                location.reload();
            }
        });
    });


    $('.close-modal').on('click', function () {

        $('.multi-popap').addClass('hide');
    });
    $('.close-form-btn').on('click', function () {

        $('.form-box').addClass('hide');
    });

    $('#delBtn').on('click', function () {
        var values = $(".row-ch:checked").map(function () {
            return this.value;
        }).get();
        var script_url = $(this).data('url')
        var title = $(this).data('title')

        $.ajax({
            url: script_url,
            type: 'POST',
            data: {
                rows: values,
                msg: '<strong>Удалено</strong> <br> ' + title + ' удалены <br>Количество: ' + $('.row-ch:checked').length + '<br>'
            },
            success: function (response) {
                location.reload();
            }
        });

    });

    $('#saveUsersEditBtn').on('click', function () {

        var values = $(".row-ch:checked").map(function () {
            return this.value;
        }).get();

        $('#editErr').hide();
        $('#numErr').hide();
        var numErr = false;
        $('.numeric-control').each(function (i, elem) {
            let val = $(this).val();
            if (isNaN(val)) {
                $('#fErName').html($(this).data('title'))
                $('#numErr').show();
                numErr = true
            }
        });
        if (numErr) {
            return false;
        }
        if (!is_edit_form) {
            $('#editErr').show();
            return false;
        }
        var form_data = $('#edit-clients-form').serializeArray()
        $('.numeric-control').each(function (i, elem) {
            let val = $(this).val();
            if (isNaN(val)) {
                $('#fErName').html($(this).data('title'))
                $('#numErr').show();
                return false;
            }
        });
        $('#saveUsersEditBtn').after('<div style="float:right;margin-top: 5px;"><img src="/img/preloader/103.gif"></div>');
        $.ajax({
            type: 'POST',
            url: '/page/edit_clients',
            data: {
                data: form_data,
                clients: values,
                msg: '<strong>Сохранено</strong> <br> Информация в анкете изменена<br> Контакты: ' + $('.row-ch:checked').length + '<br>'
            },
            success: function (data) {
                location.reload();
            }
        });

    });
    $(document).mouseup(function (e) { // событие клика по веб-документу
        var div = $(".customDropDownListLabelsForm"); // тут указываем ID элемента
        if (!div.is(e.target) && div.has(e.target).length === 0 && !$("#editLabelsForm").is(e.target)) {//&& div.has(e.target).length === 0) { // и не по его дочерним элементам
            div.addClass('hide'); // скрываем его
        }

        var div = $(".multi-popap"); // тут указываем ID элемента
        if (!div.is(e.target) && div.has(e.target).length === 0 && !$(".sel-link").is(e.target)) {//&& div.has(e.target).length === 0) { // и не по его дочерним элементам
            var el_id = event.target
            if ($(event.target).closest(".ui-datepicker").length < 1) {
                div.addClass('hide'); // скрываем его
            }

        }

        if (!$(".color-customDropDawnListForm").is(e.target)) {
            $(".color-customDropDawnListForm").hide();
        }
    });

});

function showDropDawnColorFilter(event) {
    let gh = event.target.closest('#colorSelectForm').children[1];
    gh.style.display = 'block';
}

function showDropDawnColorEv(eveennt) {
    let gh = event.target.closest('#colorSelectEv').children[1];
    gh.style.display = 'block';
}

function showDropDawnColorMass(elId, event) {
    let gh = event.target.closest('#' + elId).children[1];
    gh.style.display = 'block';
}


changeLabelFilter = function (labelId) {
    if (labelId == 'no') {
        $('#checkboxFilter' + labelId).prop('checked', false);
        $(".label-form").removeClass('added');
        $(".label-form").addClass('deleted');
        $('#blockElemFilter' + labelId).remove();

    } else {
        $('#checkboxFilterno').prop('checked', false);
        $('#blockOperFilterno').removeClass('added');
        $('#blockOperFilterno').addClass('deleted');
        $('#blockElemFilterno').remove();
    }
    var elem = $('#blockOperFilter' + labelId),
        divColor = $('#labelColorFilter' + labelId)[0].outerHTML,
        spanText = $('#labelTextFilter' + labelId)[0].outerHTML;

    if ($('#checkboxFilter' + labelId).is(':checked')) {
        $('#checkboxFilter' + labelId).prop('checked', false);
        elem.removeClass('added');
        elem.addClass('deleted');
        $('#blockElemFilter' + labelId).remove();
    } else {
        $('#checkboxFilter' + labelId).prop('checked', true);
        elem.removeClass('deleted');
        elem.addClass('added');
    }

};

function changeColorEv(event, color, name, id) {
    let colorBlock = event.target.closest('#colorSelectEv').querySelector('.color-block'),
        inputColorBlock = colorBlock.querySelector('input'),
        spanText = colorBlock.querySelector('span');
    colorBlock.style.backgroundColor = color;
    inputColorBlock.value = id;
    $(inputColorBlock).trigger("change");
    spanText.textContent = name;
}

function changeColorMass(blockId, event, color, name, id) {
    let colorBlock = event.target.closest('#' + blockId).querySelector('.color-block'),
        inputColorBlock = colorBlock.querySelector('input'),
        spanText = colorBlock.querySelector('span');
    colorBlock.style.backgroundColor = color;
    inputColorBlock.value = id;
    $(inputColorBlock).data('title', name);
    $(inputColorBlock).trigger("change");
    spanText.textContent = name;
}


function changeColorFilter(event, color, name, id) {
    let colorBlock = event.target.closest('#colorSelectForm').querySelector('.color-block-form'),
        inputColorBlock = colorBlock.querySelector('input'),
        collectionOptions = document.getElementById("selectStepForm").options,
        listOptionSelected = listOption[collectionOptions[collectionOptions.selectedIndex].value],
        stepProgressBar = document.getElementsByClassName("step-progressBar-filter")[0],
        spanText = colorBlock.querySelector('span');
    colorBlock.style.backgroundColor = color;
    inputColorBlock.value = id;
    filter_step_option = id
    spanText.textContent = name;
    filter_step_option_text = name;
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

function changeStepForm(val) {

    let collectionOptions = document.getElementById("selectStepForm").options,
        listOptionSelected = listOption[collectionOptions[collectionOptions.selectedIndex].value],
        selectOptions = document.querySelector(".customDropDawnList-form"),
        colorBlock = document.getElementsByClassName("color-block-form")[0],
        stepProgressBar = document.getElementsByClassName("step-progressBar-filter")[0],
        ul = document.createElement('ul');
    filter_step = collectionOptions[collectionOptions.selectedIndex].value;
    filter_step_text = collectionOptions[collectionOptions.selectedIndex].text;
    ul.innerHTML = '';
    document.getElementById("colorSelectForm").style.display = 'inline-flex';
    stepProgressBar.style.display = 'inline-flex';
    if (listOptionSelected) {
        filter_step_option = listOptionSelected[0].id
        filter_step_option_text = listOptionSelected[0].name;
        for (let i = 0; i < listOptionSelected.length; i++) {
            ul.innerHTML += "<li value='" + listOptionSelected[i].id + "' onclick='changeColorFilter(event, " + '"' + listOptionSelected[i].color + '"' + ", " + '"' + listOptionSelected[i].name + '", ' + listOptionSelected[i].id + ");'><div class='block-color' style='background-color:" + listOptionSelected[i].color + ";'></div><div class='margin-top-1'>" + listOptionSelected[i].name + "</div></li>";
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
        document.getElementById("colorSelectForm").style.display = 'none';
        stepProgressBar.style.display = 'none';
    }
}


function changeMaterFilter(val) {
    filter_master_type = val;
    filter_master_type_text = $(".master-type option:selected").text();
    $('#directorFilter').hide();
    $('#managerFilter').hide();
    if (val == 'director') {
        filter_master_id = $('#directorFilterSelect').val();
        filter_master_id_text = $('#directorFilterSelect option:selected').text();
        $('#directorFilter').show();
    }
    if (val == 'manager') {
        filter_master_id = $('#managerFilterSelect').val();
        filter_master_id_text = $('#managerFilterSelect option:selected').text();
        $('#managerFilter').show();
    }
}

$('body').on('click', '#select_all', function () {
    $('.form-box').addClass('hide');
    if ($(this).is(":checked")) {
        $(".row-ch").prop('checked', true);
        if ($('.row-ch:checked').length > 0) {
            $('.clients-page-row').addClass('sel-row')
            $(".sel-link").removeClass('disbl');
        }
        $('#sel-sumary-div').html("Выбрано: " + $('.row-ch:checked').length);
    } else {
        $('.clients-page-row').removeClass('sel-row');
        $(".row-ch").prop('checked', false);
        $(".sel-link").addClass('disbl');
        $('#sel-sumary-div').html("");
    }

    $('#del_cnt').html($('.row-ch:checked').length);
});


$("#editLabelsForm").click(function (e) {
    var listLabels = $(".customDropDownListLabelsForm");
    if (listLabels.hasClass('hide')) {
        listLabels.removeClass('hide');
    } else {
        listLabels.addClass('hide');
    }
});

changeLabelForm = function (labelId) {
    var elem = $('#blockOperForm' + labelId),
        divColor = $('#labelColorForm' + labelId)[0].outerHTML,
        spanText = $('#labelTextForm' + labelId)[0].outerHTML;

    if ($('#checkboxForm' + labelId).is(':checked')) {
        $('#checkboxForm' + labelId).prop('checked', false);
        elem.removeClass('added');
        elem.addClass('deleted');
        $('#blockElemForm' + labelId).remove();
    } else {
        $('#checkboxForm' + labelId).prop('checked', true).trigger("change");
        ;
        elem.removeClass('deleted');
        elem.addClass('added');
        var blockShowLabels = $('.block-labelsInProfileForm'),
            labelDIv = '<div class="block-elem" id="blockElemForm' + labelId + '">' + divColor + spanText + '</div>';
        blockShowLabels.append(labelDIv);
    }

    if (document.querySelector(".block-labelsInProfileForm .block-elem")) {
        $('#selAllLabelsForm').remove();
    } else {
        $('.block-labelsInProfileForm').append('<span id="selAllLabelsForm">Все метки</span>');
    }
};


$('select.massStyled').on("change", function () {
    var $this = $(this),
        selected = $this.find('option:selected').val(),
        index = $this.data('index');

    $('.access-tab').css('display', 'none');
    $('#' + selected + index).stop(true, true).slideDown(300);


});

function validate_date(value) {
    var arrD = value.split(".");
    if (arrD[2] != undefined) {
        arrD[2] = arrD[2].substring(0, 4);
    }
    arrD[1] -= 1;
    var d = new Date(arrD[2], arrD[1], arrD[0]);
    if ((d.getFullYear() == arrD[2]) && (d.getMonth() == arrD[1]) && (d.getDate() == arrD[0])) {
        return true;
    } else {
        return false;
    }
}

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
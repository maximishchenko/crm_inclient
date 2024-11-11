import {NotificationBar} from '/js/notificationBar.js';

const notificationBar = new NotificationBar();
$(document).ready(function() {
    $('#JModal').popup(); 
    $('#JModalEdit').popup();
    // Set default `pagecontainer` for all popups (optional, but recommended for screen readers and iOS*)
    $.fn.popup.defaults.pagecontainer = '#page'
});


$(".popup-btn").click(function (event) {
    
    $('.multi-popap:not(#'+$(this).data('popup')+')').addClass('hide');
    $("#"+$(this).data('popup')).toggleClass('hide');
    event.preventDefault();
});

$(".popup-btn-01").click(function (event) {
    
    $('.multi-popap:not(#'+$(this).data('popup')+')').addClass('hide');
    $("#"+$(this).data('popup')).toggleClass('hide');
    event.preventDefault();
});

$(".tab-table").hide();
$(".button-change-table").each(function (index, value) { 
    if( $(this).hasClass('active')){   
        $("#table-"+$(this).data('id')).show(); 
    } 
});    

window.selectTable = function(table) {
    $(".button-change-table").removeClass('active');
    $("#button-table-"+table).addClass('active'); 
    $(".tab-table").hide();
    $("#table-"+table).show(); 

}
window.delDocument = function(id) {
    if (confirm('Вы действительно хотите удалить файл?')) {
        document.location.href = '/page/client_document_delete/' + id;
    }
}

$('.close-modal').on('click', function() {
    $('.multi-popap').addClass('hide');
});  

$('.close-form-btn').on('click', function() {
    $('.form-box').addClass('hide');
}); 


$('#saveClientBtn, .act_btn').on('click', function() { 
    var thisBtn = $(this);
    if (thisBtn[0].className == 'act_btn') {
        $('#save_and_create').hide();
    } else {
        $('#saveClientBtn').hide();
    }
    $('.loader').addClass('loader-center');
    $('.loader').removeClass('hide');
    $('.errorAddField').addClass('hide');
    $('.save-message').addClass('hide');

    if(thisBtn.hasClass('act_btn')){
        $('.multi-popap').addClass('hide');  

    }
    var is_er = false;
    $('.inputError').removeClass('inputError');
    $('#reErr').hide(); 
    $('#numErr').hide();
    $('.numeric-control').each(function(i,elem) {
        let val = $(this).val();
        if(isNaN(val)){
            $(this).addClass('inputError');
            $('#fErName').html($(this).data('title'))   
            $('#numErr').show();
            is_er = true;
            $('#saveClientBtn').show();
            $('#save_and_create').show();
            $('.loader').addClass('hide');
            $('.loader').removeClass('loader-center');
            return false;
        }
    });
    $('.required-control').each(function(i,elem) {
        let val = $(this).val();
        if(val.length<1){
            $(this).addClass('inputError');
            $('#reErName').html($(this).data('title'))   
            $('#reErr').show();
            is_er = true;
            $('#saveClientBtn').show();
            $('#save_and_create').show();
            $('.loader').addClass('hide');
            $('.loader').removeClass('loader-center');
            return false;
        }
    });
    if(is_er){
        $('#saveClientBtn').show();
        $('#save_and_create').show();
        $('.loader').addClass('hide');
        $('.loader').removeClass('loader-center');
        return false;
    }

    //$('#saveClientBtn').after('<div style="margin-left:5px" class="loader"><img src="/img/preloader/103.gif"></div>');
    var data  = $('#edit-client').serialize();
    $.ajax({
        url: '/page/edit_client_ajax',
        type: 'POST', 
        dataType: 'json',
        data: data, 
        success: function (response) {  
            if(response.err){
                $('.errorAddField').html(response.err).removeClass('hide');
            }else{
                $('#headerClientName')[0].textContent = $('#Clients_additionalField_fieldFio')[0].value;
                //$('.save-message').removeClass('hide');
                notificationBar.title = 'Сохранено';
                notificationBar.description = 'Информация в анкете изменена';
                notificationBar.type = 'success';
                notificationBar.show();
                if(thisBtn.hasClass('act_btn')){
                    $('.multi-popap').addClass('hide');     
                    window.location.href =thisBtn.data('url');
                }else{

                    //$('#saveClientBtn').next('.loader').remove();
                }
            }
            $('#saveClientBtn').show();
            $('#save_and_create').show();
            $('.loader').addClass('hide');
            $('.loader').removeClass('loader-center');
        }
    });   


});

$('#responsable_type').on('change', function() { 

    $(this).after('<div style="margin-left:5px"><img src="/img/preloader/103.gif"></div>');
    var val = 'i';
    var type_name = '';
    if ($(this).val() == 'director') {
        val = $('#Clients_director_id').val()
    }else if ($(this).val() == 'manager') {
        val = $('#Clients_manager_id').val()
    }else if ($(this).val() == 'no') {
        val = 'admin'
    } else {
        val = $(this).val();
    }

    $.ajax({
        url: '/page/edit_client_ajax',
        type: 'POST',
        dataType: 'json',
        data: {
            field: 'responsable_id',
            value:val,
            client:clientId,
        }, 
        success: function (response) { 
            $('#master-type-name').html(response.type_name);
            $('#master-name').html(response.name);
            $('#master-avatar').attr('src',response.avatar);
            if ($('#responsable_type').val()=='i'){     
            }

        }
    });   


});

$('#Clients_director_id, #Clients_manager_id').on('change', function() { 

    $(this).after('<div style="margin-left:5px"><img src="/img/preloader/103.gif"></div>');
    var val = $(this).val(); 
    $.ajax({
        url: '/page/edit_client_ajax',
        type: 'POST',
        dataType: 'json',
        data: {
            field: 'responsable_id',
            value:val,
            client:clientId,
        }, 
        success: function (response) { 
            $('#master-type-name').html(response.type_name);
            $('#master-name').html(response.name);
            $('#master-avatar').attr('src',response.avatar);
        }
    });   


});


  window.changeStepAjax = function (selected_option_id) {
    $.ajax({
        url: '/page/edit_client_ajax',
        type: 'POST',        
        data: {
            field: 'steps_id',
            value:$('#selectStep').val(),
            option:selected_option_id,
            client:clientId,
        }, 
        success: function (response) {   
            
        }
    }); 
};
 

 window.changeLabel = function (labelId) {
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
    var lebel_data = [];
    $(".lebel-checkbox:checked").each(function (index, value) {  
        lebel_data.push($(this).data('id'))
    })
    $.ajax({
        url: '/page/edit_client_ajax',
        type: 'POST',        
        data: {
            field: 'lebel',
            labels:lebel_data, 
            client:clientId,
        }, 
        success: function (response) { }
    }); 
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

window.showDropDawnColor = function(event) {
    let gh = event.target.closest('#colorSelect').children[1];
    gh.style.display = 'block';
};

window.changeColor = function(event, color, name, id) {
    let colorBlock = event.target.closest('#colorSelect').querySelector('.color-block'),
    inputColorBlock = colorBlock.querySelector('input'),
    collectionOptions = document.getElementById("selectStep").options,
    listOptionSelected = listOption[collectionOptions[collectionOptions.selectedIndex].value],
    stepProgressBar = document.getElementsByClassName("step-progressBar")[0],
    spanText = colorBlock.querySelector('span');
    colorBlock.style.backgroundColor = color;
    inputColorBlock.value = id;
    spanText.textContent = name; 
    if (listOptionSelected) {
       // stepProgressBar.children = null;
        let listElem = '',
        isGrey = false;
        for (let i = 0; i < listOptionSelected.length; i++) {
            listElem += '<div class="progressBar-elem" style="background-color:' + (isGrey ? 'darkgrey' : listOptionSelected[i].color) + '"> </div>';
            if (id == listOptionSelected[i].id) {
                isGrey = true;
            }
        } 
        stepProgressBar.innerHTML = listElem;
        $('#step-bar').html(listElem);
        $('#step-name').html(name);
        changeStepAjax(id)
    }else{
        $('#step-bar').html('');
        $('#step-name').html('Нет воронки');
        changeStepAjax(id)

    }
}

window.changeStep = function() {
    let collectionOptions = document.getElementById("selectStep").options,
    listOptionSelected = listOption[collectionOptions[collectionOptions.selectedIndex].value],
    selectOptions = document.querySelector(".color-customDropDawnList"),
    colorBlock = document.getElementsByClassName("color-block")[0],
    stepProgressBar = document.getElementsByClassName("step-progressBar")[0],
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
        $('#step-bar').html(listElem);                           
        $('#step-name').html(listOptionSelected[0].name);
        changeStepAjax(listOptionSelected[0].id)
    } else { 
        document.getElementById("colorSelect").style.display = 'none';
        stepProgressBar.style.display = 'none';                
        $('#step-bar').html('');
        $('#step-name').html('Нет воронки');
        changeStepAjax(0)
    }
};



$(document).mouseup(function (e){ // событие клика по веб-документу
    var div = $(".customDropDownListLabelsForm"); // тут указываем ID элемента
    if (!div.is(e.target) && div.has(e.target).length === 0 && !$("#editLabelsForm").is(e.target)) {//&& div.has(e.target).length === 0) { // и не по его дочерним элементам
        div.addClass('hide'); // скрываем его
    }           

    var div = $(".multi-popap"); // тут указываем ID элемента
    if (!div.is(e.target) && div.has(e.target).length === 0 && !$(".sel-link").is(e.target) ) {//&& div.has(e.target).length === 0) { // и не по его дочерним элементам
        var el_id = event.target  
        if($(event.target).closest(".ui-datepicker").length < 1){
            div.addClass('hide'); // скрываем его
        }

    }

    if (!$(".color-customDropDawnListForm").is(e.target)) {
        $(".color-customDropDawnListForm").hide();
    }
});



$('#save-note').on('click', function() {
    $('#new-note-error').addClass('hide')                            
    if($('#note-text').val().length <1){
        $('#new-note-error').removeClass('hide')  
        return false;
    }  
   $('#loader-add').removeClass('hide') 
    $.ajax({
        url: '/page/edit_note_ajax',
        type: 'POST',       
        data: {
            text: $('#note-text').val(),
            id:$('#note_id').val(),
            color:$('#note_color').val(),   
            client_id:clientId,
        }, 
        success: function (response) {   
            $('#loader-add').addClass('hide')    
            $('#JModal').popup('hide');   
            $('#note-text').val('')
            $('.main-anketa').prepend(response);          
        }
    }); 
}) 
$('#save-edit-note').on('click', function() {  
    $('#edit-note-error').addClass('hide')                            
    if($('#note-edit_text').val().length <1){
        $('#edit-note-error').removeClass('hide')  
        return false;
    }  
    $('#loader-edit').removeClass('hide'); 
    $.ajax({
        url: '/page/edit_note_ajax',
        type: 'POST',       
        data: {
            text: $('#note-edit_text').val(),
            id:$('#note-edit_id').val(),
            color:$('#note-edit_color').val(),   
            client_id:clientId,
        }, 
        success: function (response) {      
            $('#loader-edit').addClass('hide')  
            $('#JModalEdit').popup('hide');                                   
            $("#note_"+$('#note-edit_id').val()).replaceWith(response);                          

        }
    }); 
})  

$('body').on('click', '.color-box-new',   function() {   
    $('.color-box-new').removeClass('active-color');
    $(this).addClass('active-color');
    $('#note_color').val($(this).data('color'))
    $('#note-text').removeClass()
    $('#note-text').addClass('color-'+$(this).data('color')) 

})
$('body').on('click', '.color-box-edit',   function() {   
    $('.color-box-edit').removeClass('active-color');
    $(this).addClass('active-color');
    $('#note-edit_color').val($(this).data('color'))
    $('#note-edit_text').removeClass()
    $('#note-edit_text').addClass('color-'+$(this).data('color')) 

})

$('body').on('click', '.editNote',  function (e) {

    $('#edit-note-error').addClass('hide')       
    var editPopup = $("#editNote_"+$(this).data('id'));    
    if (editPopup.hasClass('hide')) {
        $('.multi-popap').addClass('hide');
        editPopup.removeClass('hide');
    } else {
        editPopup.addClass('hide');
    }
});

$('body').on('click', '.edit_note',  function (e) {  
    $('#JModalEdit').popup('show'); 
    var note_id = $(this).data('id');
    var note_color = $(this).data('color');
    $('#note-edit_text').val($('#note_'+note_id+' .note-text').text()); 
    $('#note-edit_text').removeClass(); 
    $('#note-edit_text').addClass('color-'+note_color)
    $('#JModalEdit .color-box').removeClass('active-color'); 
    $('#JModalEdit .color-'+note_color).addClass('active-color'); 
    $('#JModalEdit .editor-footer').html($('#note_'+note_id+' .note_footer').html()); 
    $('#note-edit_id').val(note_id); 
    $('#note-edit_color').val(note_color);  
});


$('body').on('click', '.delete_note',    function() {

    if(confirm('Удалить заметку?')) {
        var id =   $(this).data('id');    
        $.ajax({
            url: '/page/del_note_ajax',
            type: 'POST',       
            data: { 
                id:id, 
            }, 
            success: function (response) {    
                $('#note_'+id).remove();  
            }
        });
    }
})
   $('body').on('click', '#close-msg-btn', function() {
         $('#msg_div').html(''); 
         $('#msgBox').addClass('hide');
         $('.save-message').addClass('hide');
     });

 
 
$('body').on('click', '.delete_note_modal',  function() {

    if(confirm('Удалить заметку?')) {
        var id =   $('#note-edit_id').val(); 
        $.ajax({
            url: '/page/del_note_ajax',
            type: 'POST',       
            data: { 
                id:id, 
            }, 
            success: function (response) {    
                $('#note_'+id).remove();  
                $('#JModalEdit').popup('hide');  
            }
        });
    }


});
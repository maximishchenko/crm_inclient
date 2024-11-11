// JavaScript Document

/***
 * BxSlider v4.2.3 - Fully loaded, responsive content slider
 * http://bxslider.com
 *
 * Copyright 2014, Steven Wanderski - http://stevenwanderski.com - http://bxcreative.com
 * Written while drinking Belgian ales and listening to jazz
 *
 * Released under the MIT license - http://opensource.org/licenses/MIT
 ***/

$(function() {

    $("table").removeClass("items");
    $("table").addClass("main-table");

	//Переменные
	var $formStyled   	  = $('.styled'),
			$popupInit    	  = $(".popup-open"),
			$datepickerFrom   = $(".datepicker"),
			$flyValidation    = $('.fly-validation'),
			$sliderInit 			= $('.bxslider');

	//$('.fly-validation input[name=tel]').mask('+9 (999) 999-99-99');

	//form styler
	if($formStyled.length){
		$formStyled.styler();
	}

	if($sliderInit.length){
		$('#reg-slider').bxSlider({
			controls: false,
			mode: "fade"
		});
	}

	//datepicker
	if($datepickerFrom.length){
		$datepickerFrom.datepicker({
			defaultDate: "+1w",
			changeYear: true,
			showOtherMonths: true,
			dateFormat: "dd.mm.yy",
			onSelect: function( selectedDate ) {
					$('input[name=date]').removeClass('error');
			}
		});
	}

	//Open popup
	$popupInit.on("click touchstart", function() {
		var $this = $(this),
				thisHref = $this.attr('href');

		if($this.is('.edit')){
			var $row = $this.closest('.edit-row'),
					rowId = $row.attr('id'),
					$editable = $row.find('.editable');
		}
		if($this.is('.edit-list')){
			var $editForm = $this.closest('.edit-form'),
					$row = $editForm.find('.edit-row'),
					rowId = $row.attr('id'),
					$editable = $row.find('.editable');
		}

		$.fancybox({
			href: thisHref,
			padding : 0,
			closeBtn: true,
			'scrolling' : 'visible',
			beforeShow : function(){
				$(".fancybox-wrap").addClass("fancybox-custom");
			},
			afterShow : function(){
				if($this.is('.edit') || $this.is('.edit-list') ){
					for(var i=0;i<$editable.length;i++){
						var data = [],
								content = [],
								itemName = $editable.eq(i).attr('rel'),
								itemContent = $editable.eq(i).html();

						data[i] = itemName;
						content[i] = itemContent;

						if($editable.eq(i).is('.static')) {
							$(thisHref).attr('rel', rowId).find("[class='"+data[i]+"']").html(itemContent);
						}else {
							$(thisHref).attr('rel', rowId).find("[name='"+data[i]+"']").val(itemContent);
						}
						if($editable.eq(i).is('.permis')) {
							var permis = $editable.eq(i).attr('data');
							$(thisHref).attr('rel', rowId).find("select option[value="+permis+"]").prop("selected", true);

							if(permis == 1 || permis == 2){
								$('.access-options').stop(true,true).slideDown(300);
							}
						}
						if($editable.eq(i).is('.typeAccess')) {
							var permis = $editable.eq(i).attr('data');
							$(thisHref).attr('rel', rowId).find("select option[value="+permis+"]").prop("selected", true);
							$('.access-tab').css('display','none');
							$('#'+permis).stop(true,true).slideDown(300);
						}
					}
				}
				$formStyled.trigger('refresh');
			},
			afterClose : function(){
				$('.access-options').stop(true,true).delay(500).slideUp(0);
			}
		});

		return false
	});

	//Delete table row
	var $deleteRow = $('.function-delete > a'),
			$deleteRowConfirm = $('.function-delete-confirm a');
	$deleteRow.on("click touchstart", function() {

		var $this = $(this),
				$delete = $this.closest('.function-delete'),
				$confirm = $delete.next('.function-delete-confirm');

		$delete.css('display', 'none');
		$confirm.css('display', 'block');
		return false
	});

	$deleteRowConfirm.on("click touchstart", function(e) {
		var $clicked = $(e.target),
				$this = $(this),
				$confirm = $this.closest('.function-delete-confirm'),
				$delete = $confirm.prev('.function-delete'),
				deleteRow = $this.closest('.popup').attr('rel');

		if($clicked.is('.btn')) {
			$('#'+deleteRow).remove();
			$.fancybox.close();
			$('body').append('<div class="sepia"><div class="sepia-table"><div class="sepia-td"><div class="sepia-text">Данные удалены<a class="close">close</a></div></div></div></div>');
			$('.sepia')
				.stop(true,true)
				.delay(500)
				.animate({
					opacity: 0
				}, 700, function() {
					$('.sepia').remove();
					$delete.css('display', 'block');
					$confirm.css('display', 'none');
				});
		}else {
			$delete.css('display', 'block');
			$confirm.css('display', 'none');
		}
		return false
	});

	//validation rules
	$.validator.addMethod(
    "dateDECH",
    function(value, element) {
        var check = false;
        var re = /^\d{1,2}\.\d{1,2}\.\d{4}$/;
        if( re.test(value)){
            var adata = value.split('.');
            var dd = parseInt(adata[0],10);
            var mm = parseInt(adata[1],10);
            var yyyy = parseInt(adata[2],10);
            var xdata = new Date(yyyy,mm-1,dd);
            if ( ( xdata.getFullYear() === yyyy ) && ( xdata.getMonth () === mm - 1 ) && ( xdata.getDate() === dd ) ) {
                check = true;
            }
            else {
                check = false;
            }
        } else {
            check = false;
        }
        return this.optional(element) || check;
    },
    "Укажите дату"
);

	$.validator.addMethod("valueNotEquals", function(value, element, arg){
			return arg != value;
	}, "Value must not equal arg.");
	jQuery.extend(jQuery.validator.messages, {
		equalTo: "Пароль не совпадает"
	});
	for(var i=0;i<$flyValidation.length;i++){

		var flyValidationId = $flyValidation.eq(i).attr('id');

		$('#'+flyValidationId).validate({
		    rules:{
					name:{
						required: true,
						minlength: 2,
						maxlength: 30
					},
					theme:{
						required: true,
						minlength: 2,
						maxlength: 30
					},
					address:{
						required: true,
						minlength: 10,
						maxlength: 50
					},
					cityText:{
						required: true,
						minlength: 2,
						maxlength: 50
					},
					company:{
						required: true,
						minlength: 10,
						maxlength: 50
					},
					tel:{
						required: true,
						minlength: 10,
						maxlength: 50
					},
					date:{
						dateDECH: true
					},
					description:{
						required: true,
						minlength: 2,
						maxlength: 255,
					},
					email:{
						required: true,
						email: true,
						minlength: 6,
						maxlength: 50,
					},
					password:{
						required: true,
						minlength: 6,
						maxlength: 16,
					},
					password_again: {
						required: true,
						minlength: 6,
						maxlength: 16,
						equalTo : "#password"
       		},
					city: {
						valueNotEquals: "-1"
					},
					group: {
						valueNotEquals: "-1"
					},
					status: {
						valueNotEquals: "-1"
					},
					responsible: {
						valueNotEquals: "-1"
					},
					source: {
						valueNotEquals: "-1"
					},
					 category: {
						valueNotEquals: "-1"
					},
					type: {
						valueNotEquals: "-1"
					},
					products: {
						valueNotEquals: "-1"
					},
					priority: {
						valueNotEquals: "-1"
					},
					target: {
						valueNotEquals: "-1"
					},
					relations: {
						valueNotEquals: "-1"
					},
		    },
		    messages:{
					name:{
						required: "Введите имя",
						minlength: "Имя должено быть минимум 2 символа",
						maxlength: "Максимальное число символов - 30",
					},
					company:{
						required: "Введите название компании",
						minlength: "Название компании должено быть минимум 2 символа",
						maxlength: "Максимальное число символов - 30",
					},
					address:{
						required: "Введите имя",
						minlength: "Имя должено быть минимум 2 символа",
						maxlength: "Максимальное число символов - 30",
					},
					theme:{
						required: "Введите тему",
						minlength: "Тема должена быть минимум 2 символа",
						maxlength: "Максимальное число символов - 30",
					},
					description:{
						required: "Введите описание",
						minlength: "Описание должено быть минимум 2 символа",
						maxlength: "Максимальное число символов - 255",
					},
					email:{
						required: "Введите email",
						minlength: "Email должен быть минимум 6 символов",
						maxlength: "Максимальное число символов - 50",
					},
					password:{
						required: "Введите пароль",
						minlength: "Хороший пароль: не менее 8 символов, числа и буквы.",
						maxlength: "Максимальное число символов - 16",
					},
					password_again: {
						required: "Введите пароль",
						minlength: "Хороший пароль: не менее 8 символов, числа и буквы.",
						maxlength: "Максимальное число символов - 16",
			    },
		    }
		});
	}

	//Submit with validation
	$flyValidation.submit(function() {
		var $this = $(this);
		$formStyled.trigger('refresh');

		if($this.valid()){
				//If for in popup
				if($this.parents().is('.popup')){
					var	$popup = $this.closest('.popup'),
							rowId = $popup.attr('rel'),
							$editable = $popup.find('.editable');
					console.log(rowId);
					for(var i=0;i<$editable.length;i++){
							var data = [],
									content = [],
									itemData = $editable.eq(i).attr('name'),
									itemContent = $editable.eq(i).val();

							data[i] = itemData;
							content[i] = itemContent;
							console.log($editable.eq(i));
							if($editable.eq(i).is('.typeAccess')){
								var selected = '';
								selected = $editable.eq(i).find('option:selected').html();
								$('#'+rowId).find(".editable[rel="+data[i]+"]").html(selected);
							}else{
								$('#'+rowId).find(".editable[rel="+data[i]+"]").html(content[i]);
							}
					}

					$.fancybox.close();
					$('body').append('<div class="sepia"><div class="sepia-table"><div class="sepia-td"><div class="sepia-text">Данные сохранены<a class="close">close</a></div></div></div></div>');
					$('.sepia')
						.stop(true,true)
						.delay(500)
						.animate({
							opacity: 0,
						}, 700, function() {
							$('.sepia').remove();
						});
				}
			}
				return false
	});


	//Delete error on select if change
	$('select.styled').on("change", function(){
			var $this = $(this),
					selected = $this.find('option:selected').val();

			if(selected != -1){
					$formStyled.trigger('refresh');
			}

			if($this.is('.permis')){
				console.log('permis');
				if(selected == 3){
					$('.access-options').stop(true,true).slideUp(300);
				}
				if(selected == 1 || selected == 2){
					$('.access-options').stop(true,true).slideDown(300);
				}
			}
			if($this.is('.typeAccess')){
				console.log('type');
				$('.access-tab').css('display','none');
				$('#'+selected).stop(true,true).slideDown(300);
			}

	});

	//Options settings nav
	var $headerOptions = $('.header-options'),
			$headerOptionsLink = $headerOptions.find('> a');

	//Open, Close on click touch
	$headerOptionsLink.on("click touchstart", function() {
		$headerOptions.toggleClass('open');
		return false
	});

	//Close on click touch
	$(document).on("click touchstart", function(e) {
		var $clicked = $(e.target);
		if(!$clicked.parents().is($headerOptions)){
			$headerOptions.removeClass('open');
		}
	});

	//help dropdown
	var $helpDropdown = $('.help-dropdown'),
			$helpDropdownDt = $helpDropdown.find('.dt'),
			$helpDropdownDd = $helpDropdown.find('.dd');

	if($helpDropdown.is('.open')){
		$helpDropdownDd.stop(true,true).slideDown(300);
	}
	//Open, Close on click touch
	$helpDropdownDt.on("click touchstart", function() {
		var $this = $(this),
				$helpDropdown = $this.closest('.help-dropdown'),
				$helpDropdownDd = $helpDropdown.find('.dd');
		$this.toggleClass('open');
		$helpDropdownDd.stop(true,true).slideToggle(300);
	});

	//popup edit email,password
	var $editLink = $('.edit-link');

	$editLink.on("click touchstart", function() {
		var $this = $(this),
				$popup = $this.closest('.popup'),
				$desiredHeight = $this.parent('.form-group'),
				boxId =$this.attr('href'),
				boxHeight = $(boxId).height(),
				btnTop = $this.offset().top,
				popupTop = $popup.offset().top,
				desiredTop = btnTop - popupTop;
		if(!$this.is('.open')){
			$this.addClass('open');
			$desiredHeight.css('margin-bottom',boxHeight+'px');
			$(boxId).css('top',desiredTop+18+'px').stop(true,true).slideDown(0);
		}else{
			$this.removeClass('open');
			$desiredHeight.css('margin-bottom','0');
			$(boxId).css('top','0').stop(true,true).slideUp(0);
		}
		return false
	});

});//end ready

<script>
    $("table").removeClass("items");
    $("table").addClass("main-table");

    function ButtonCheck(create_button, popup_button, fields) {
        $(popup_button).click(function () {
            butClick = 0;
            $(create_button).removeClass("disabled");
        });
        $(create_button).click(function (e) {
            for (var i = 0; i < fields.length; i++) {
                var value = $(fields[i]);
                if (value[0].value != '') {
                    butClick++;
                }
            }
            if (butClick == (fields.length * 2) + 1) {
                $(create_button).addClass("disabled");
            }
            if (butClick > (fields.length * 2) + 2) {
                $(create_button).attr("disabled", "disabled");
            }
        });
    }

    function ActionEdit(id, edit_type) {
        ajaxRequest(edit_type, id)
    }

    function ajaxRequest(action, id) {

        $.ajax({
            'url': '/index.php/page/edit_' + action,
            'cache': false,
            'dataType': 'html',
            'data': {
                'id': id
            },
            'success': function (data) {

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

                $.fancybox(data, {
                    'fitToView': false,
                    'scrolling' : 'visible',
                    'closeBtn': true,
                    'padding' : 0,
                    beforeShow : function(){
                        $(".fancybox-wrap").addClass("fancybox-custom");
                    },
                    afterShow : function(){
                        var $formStyled = $(".styled");
                        //form styler
                        if($formStyled.length){
                            $formStyled.styler();
                        }

                        $("select.styled").on("change", function(){
                            var $this = $(this),
                                selected = $this.find("option:selected").val();

                            if(selected != -1){
                                $formStyled.trigger("refresh");
                            }

                            if($this.is(".permis")){
                                console.log("permis");
                                if(selected == 3){
                                    $("#"+selected).stop(true,true).slideUp(300);
                                }
                                if(selected == 1 || selected == 2){
                                    $(".access-options").stop(true,true).slideDown(300);
                                }
                            }
                            if($this.is(".typeAccess")){
                                console.log("type");
                                $(".access-tab").css("display","none");
                                //$("#"+selected).css('display','inline');
                                $("#"+selected).stop(true,true).slideDown(300);
                                $('.styled').trigger('refresh');
                            }
                        });
                    },
                    afterClose : function(){
                        $('.access-options').stop(true,true).delay(500).slideUp(0);
                    }
                });
            }
        });
    }
</script>

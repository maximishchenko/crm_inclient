<?php $this->pageTitle = 'Редактирование настроек' ?>
<?php $correct_path = 'http://' . $_SERVER["HTTP_HOST"]; ?>


<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'new-client',
    'method' => 'post'
    // 'enableAjaxValidation' => true,
));
?>
<!--<form class="fly-validation" id="form-new-client" action="#" method="post">-->

<div class="clients-hat">
    <div class="client-name">
        <?php echo CHtml::link('Настройки', array('page/settings_common'));?> </span>  <span class="">/ #<?php echo Settings::model()->getNameParam($settings->param) ?>
		
    </div>
    <div class="goback-link pull-right">
        <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn', 'id' => 'save')); ?>
		<div id="preloader"></div>
    </div>
</div>

<div class="container">
    <main class="content full2" role="main">
        <div class="box_edituser_left">
            <div class="edit_user_view">
                <div class="title_name_1">Параметр "<? echo Settings::model()->getNameParam($settings->param) ?>"</div>
                <div class="centre_settings">
				    <table class="main-table row edit-row" id="user-info">

                        <tr>
                            <td class="an_001" width="132">Значение:</td>
                            <td><?php echo $form->textField($settings, 'value', array('class' => 'form-control', 'placeholder' => 'Наименование')); ?>
                                <?php echo $form->error($settings, 'value', array('class' => 'form-error')); ?></td>
                        </tr>
					</table>
                </div>

                <?php $this->endWidget(); ?>

            </div>
        </div>
</div>
<div class="box-gray111">
        <div class="right-sidebar">
            <div class="title_name_2">Справка</div>
            <div class="popup__form_actions">

                </div>
        </div>
</div>


<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
    $("#new-client").submit(function () {
        $("#preloader").addClass('preloader');
        $("#save_and_create").hide();
        $("#save").hide();
    });
</script>
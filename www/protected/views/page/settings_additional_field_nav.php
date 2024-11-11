<?php $this->pageTitle = 'Настройки дополнительных полей'; ?>
<div class="clients-hat clearfix">
    <div class="pull-left">Настройки</div>
    <nav class="navbar pull-right">
        <ul class="nav navbar-nav">
            <li><?php echo CHtml::button('Изменить раздел', array('onClick' => 'editSelection()',
                    'class' => 'btn_grey')); ?></li>
            <li><?php echo CHtml::button('Создать раздел', array('onClick' => 'window.location.href= "' .
                    Yii::app()->createUrl("page/additional_field_section_create") . '"',
                    'class' => 'btn_green')); ?> ?></li>
         </ul>
    </nav>
</div>
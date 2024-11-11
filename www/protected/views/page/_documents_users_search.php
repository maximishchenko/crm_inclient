<aside class="left-sidebar">
    <div class="box-gray__head">
        Поиск файлов
    </div>

    <div class="box-gray__body">
        <div class="box-gray__form">
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'enableAjaxValidation' => false,
                'method' => 'get',
            ));
            ?>
            <div class="form-group">
                <?php echo $form->textField($documents, 'keyword', array('type' => 'text', 'class' => 'form-control', 'placeholder' => 'Поиск')); ?>
            </div>
            <div class="form-group">
                <label class="label">Тип пользователя:</label>
                <?php
                $roleArray = ['0' => 'Все', 'director' => 'Руководители', 'manager' => 'Менеджеры'];
                ?>
                <?php echo $form->dropDownList($documents, 'type_user', $roleArray, array('class' => 'styled', 'data-placeholder' => 'Все группы')); ?>
            </div>

            <div class="form-group">
                <label class="label">Дата загрузки:</label>
                <?php echo $this->widget('ext.CJuiDateTimePicker.CJuiDateTimePicker', array(
                    'name' => 'UsersFiles[start_date]',
                    'model' => $documents,
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
            <div class="form-group">
                <?php echo $this->widget('ext.CJuiDateTimePicker.CJuiDateTimePicker', array(
                    'name' => 'UsersFiles[stop_date]',
                    'model' => $documents,
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

            <div class="form-group">
                <div class="form-group form-group-btn">
                    <?php echo CHtml::hiddenField('UsersFiles[search]', 'true'); ?>
                    <?php echo CHtml::submitButton('Найти', array('class' => 'btn white')); ?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</aside><!--.left-sidebar -->

<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
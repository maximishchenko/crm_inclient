<div class="popup" id="popup-new-range">
    <div class="popup__head">
        <div class="title">Новый диапазон</div>
    </div>
    <div class="popup__form">
        <div class="form-group">
            <div class="help-dropdown">
                <dl>
                    <dd class="dd">
                        <ul>
                            <li>
                                Укажите диапазон IP адресов, с которых пользователь сможет
                                авторизоваться в срм. Например: начало - 87.240.137.158, конец -
                                87.240.137.200
                            </li>
                        </ul>
                    </dd>
                </dl>
            </div>
        </div>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'create-range',
            'enableAjaxValidation' => true,
        ));
        ?>
        <div class="client_info">
            Начало диапазона:<span class="star">*</span>
        </div>
        <div class="form-group">
            <?php echo $form->textField($rangeIP, 'begin_ip', array('class' => 'form-control', 'placeholder' => '__.__.__.__')); ?>
            <?php echo $form->error($rangeIP, 'begin_ip', array('class' => 'form-error')); ?>

        </div>
        <div class="client_info">
            Конец диапазона:<span class="star">*</span>
        </div>
        <div class="form-group">
            <?php echo $form->textField($rangeIP, 'end_ip', array('class' => 'form-control', 'placeholder' => '__.__.__.__')); ?>
            <?php echo $form->error($rangeIP, 'end_ip', array('class' => 'form-error')); ?>

        </div>
        <div class="client_info">
            Комментарий:
        </div>
        <div class="form-group">
            <?php echo $form->textField($rangeIP, 'comment', array('class' => 'form-control', 'placeholder' => 'Краткое описание')); ?>
            <?php echo $form->error($rangeIP, 'comment', array('class' => 'form-error')); ?>

        </div>
        <div class="form-group">
            <?php echo CHtml::submitButton('Добавить диапазон', array('class' => 'btn', 'id' => 'create_range_button')); ?>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>
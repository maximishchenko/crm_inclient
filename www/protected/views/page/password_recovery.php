<?php $this->pageTitle = 'Восстановление пароля'; ?>

<div class="box-gray_login">

    <div class="head-logo">
        <img class="" src="<?echo $appearance->logo?>">
    </div>

   <p style="text-align: center;">Укажите email, чтобы сбросить пароль</p>
    
    <div class="acess_autorizatoion">
        
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'enableAjaxValidation' => false,
        ));
        ?>
            <div class="client_info">
                E-mail:
            </div>
            <div class="form-group">
                <?php echo $form->textField($model, 'email', array('class' => 'form-control', 'placeholder' => 'Email')); ?>
                <?php echo $form->errorSummary($model, '', '', array('class' => 'form-error')); ?>
            </div>
            <div class="form-group">
                <?php echo CHtml::submitButton('Отправить', array('class' => 'btn white')); ?>
            </div>

        <?php $this->endWidget(); ?>
        <div class="form-group">
            <div class="help-dropdown">
                <dl>
                    <dt class="dt"><i class="icon-help">help</i>Показать справку</dt>
                    <dd class="dd">
                        <ul>
                            <li>
                                Введите email от вашей учетной записи в CRM и нажмите "Отправить". Вскоре вы получите письмо с информацией для восстановления пароля.
                            </li>
                        </ul>
                    </dd>
                </dl>
            </div>
        </div>
        
        <div class="registr_pass">
            <?php echo CHtml::link('&lt;&lt; перейти назад', array('page/login')); ?>
        </div>
        
    </div>
</div>
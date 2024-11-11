<?php $this->pageTitle = 'CRM система'; ?>

<div class="box-gray_login">
    <div class="head-logo">
        <img class="" src="<?echo $appearance->logo?>">
    </div>
   <p style="text-align: center;">Войдите, чтобы перейти к CRM</p>
    
    <div class="acess_autorizatoion">
        <?php if ($login_type == 'login') { ?>
            
        <?php } elseif ($login_type == 'activation') { ?>
            <div class="authorization">
                <b1>Поздравляем! Аккаунт активирован</b1>
            <p>Для того, чтобы авторизоваться на сайте, введите ваш email и пароль в форме ниже.</p>
            </div>
        <?php } ?>

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'enableAjaxValidation' => false,
        ));
        ?>
        <form class="fly-validation validate-visible" id="user-info" action="#" method="post">
            
            <div class="client_info">
                E-mail:
            </div>
            <div class="form-group">
                <?php echo $form->textField($model, 'email', array('class' => 'form-control', 'placeholder' => 'Email')); ?>
            </div>
            <div class="client_info">
                Пароль:
            </div>
            <div class="form-group">
                <?php echo $form->passwordField($model, 'password', array('class' => 'form-control', 'placeholder' => 'Пароль', 'id' => 'password')); ?>
                <?php echo $form->errorSummary($model, '', '', array('class' => 'form-error')); ?>
            </div>
            <div class="form-group">
                <?php echo CHtml::submitButton('Войти', array('class' => 'btn green')); ?>
            </div>
        </form>
        <?php $this->endWidget(); ?>
        <div class="registr_pass">
            <?php echo CHtml::link('Восстановить пароль', array('page/password_recovery')); ?>
        </div>

        <div class="registr_pass">
            <span>Version: <a href="https://inclient.ru/review-1025" target="_blank" rel="nofollow noopener" style="color: #222;">1.0.2.5</a></span>
        </div>
    </div>
</div>
<div class="dev_link">
    <img class="" src="/img/cloud.svg" style="height: 19px;padding-left: 2px;margin-top: -3px;padding-right: 5px;"><a href="https://inclient.ru" target="_blank" rel="nofollow noopener">CRM система</a>
</div>
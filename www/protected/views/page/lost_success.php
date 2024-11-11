<?php $this->pageTitle = 'Подтвердите сброс пароля'; ?>
<div class="box-gray_login">
    <div class="acess_autorizatoion">
        <div class="authorization">
            <b1>Подтвердите восстановление пароля</b1>
        </div>
        <div>
        <img src="/img/letter.svg" style="width: 150px;margin: 0 auto;padding: 20px 30px 15px 47px;">
        </div>
        <div class="form-group">
            <ul>
                <li style="padding-bottom: 15px;">
                    Проверьте свою электронную почту, на нее отправлено письмо восстановления пароля. Если письмо не прийдет в течение нескольких минут, проверьте папку "спам".
                </li>
            </ul>
        </div>
        <form class="fly-validation validate-visible" id="user-info" action="#" method="post">
            <div class="form-group">
                <button onclick="location.href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/page/login'" class="btn white" type="submit">Авторизация</button>
            </div>
        </form>
    </div>
</div>
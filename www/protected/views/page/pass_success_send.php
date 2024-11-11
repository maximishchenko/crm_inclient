<?php $this->pageTitle = 'Восстановление пароля'; ?>
<div class="box-gray_login">
    <div class="acess_autorizatoion">
        <div class="authorization">
            <b1>Пароль успешно восстановлен!</b1>
        </div>
        <div class="form-group">
            <ul>
                <li>
                    На ваш e-mail был отправлен новый пароль. Пожалуйста, проверьте.
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
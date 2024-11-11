<?php $this->pageTitle = 'Страница не найдена'; ?>

<main class="content-error-block" role="main">
    <div class="error-block">
        <div class="acess_autorizatoion2">
            <div class="authorization">
                <img class="" src="/img/404.svg">
            </div>

            <div class="form-group">
                <b1>К сожалению, такой страницы не существует</b1>
            </div>
            <div class="form-group">
                <p>Возможно, это случилось по одной из причин:</p>
            </div>
            <ul class="website-abilities">
                <li>вы ошиблись при наборе адреса страницы (URL)</li>
                <li>перешли по неработающей ссылке</li>
                <li>запрашиваемой страницы никогда не было в срм или она была удалена</li>
            </ul>
            <div class="form-group">
                <p>Теперь вы можете сделать следующее:</p>
            </div>
            <ul class="website-abilities">
                <li>вернитесь назад, нажмите клавиши "Alt + ←"</li>
                <li>проверьте правильность написания адреса страницы</li>
                <li>перейдите
                    на <?php echo CHtml::link('главную страницу', array('page/index'), array('class' => '')); ?></li>

            </ul>
            <div class="form-group">
                <p>Инклиент: <a href="http://inclient.ru" target="_blank">inclient.ru</a></p>
            </div>
        </div>
    </div>
</main>
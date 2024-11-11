<aside class="left-sidebar">
    <div class="box-gray">
        <div class="box-gray__head">
            Статистика
        </div>
        <div class="box-gray__body">
            <div class="box-gray__form">
                <label class="label">Контакты: <? echo $clientCount ?></label>
                <label class="label">Пользователи: <? echo $userCount ?></label>
                <label class="label">Задачи: <? echo $actionCount ?></label>
                <label class="label">Сделки: <? echo $dealCount ?></label>
                <label class="label" style="border-top: 1px solid #d9d9d9;padding-top: 9px;">Версия срм: <a href="https://inclient.ru/review-1025" target="_blank"><? echo Version::getLastVersion() ?></a></label>
            </div>
        </div>
    </div>
    <div class="box-gray">
        <div class="box-gray__head big">
           Поиск пользователей
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
                <?
                echo $form->textField($userSearch, 'keyword', array('type' => 'text', 'class' => 'form-control', 'placeholder' => 'Поиск'));

                if ($role == 'admin') { ?>
                </div>
                
                <div class="form-group">
                    <label class="label">Ответственный:</label>
                    <?
                    $responsible_options = array(
                        'all' => 'Все пользователи',
                         Yii::app()->user->id => 'Я ответственный',
                        'director' => 'Руководитель',
                    );
                    $directors_array = array();

                    if ($role == 'director') {
                        unset($responsible_options['director']);
                    } else {
                        $directors_array = Users::model()->with('roles')->findAll('status != "none" and roles.name="director"');
                    }
                    $directors_block_to_display = '';
                    $selected_option_director = '';
                    // выбор значений в селекторах с ролями и пользователями
                    if ($userSearch->parent_id == Yii::app()->user->id) {
                        $selected_option = array('i' => array('selected' => true));
                    } elseif ($userSearch->parent_id == 'no' || !isset($userSearch->parent_id)) {
                        $selected_option = array('no' => array('selected' => true));
                    } elseif($userSearch->parent_id == 'all') {
                        $selected_option = array('all' => array('selected' => true));
                    } else {
                        $selected_option = array('director' => array('selected' => true));
                        $directors_block_to_display = 'style="display:block"';
                        $selected_option_director = array($userSearch->parent_id => array('selected' => true));
                    }

                    if (count($directors_array) <= 0) {
                        unset($responsible_options['director']);
                    }

                    echo $form->dropDownList($userSearch, 'parent_id', $responsible_options, array('options' => $selected_option, 'class' => 'styled permis editable typeAccess', 'name' => 'type'));
                    ?>
                </div>
                <div class="form-group">
                    <label class="label">Группа:</label>
                    <?php
                    echo $form->dropDownList($userSearch, 'data[group]', $allGroups, array('class' => 'styled permis editable typeAccess', 'name' => 'data[group]'));
                    ?>
                </div>
                    <div class="access-options access-tab" id="director" <?php echo $directors_block_to_display ?>>
                        <?php echo $form->dropDownList($userSearch, 'director_id', CHtml::listData($directors_array, 'id', 'first_name'), array('options' => $selected_option_director, 'class' => 'styled')); ?>
                    </div>
                <div class="form-group">    

                    <label class="label">Тип пользователя:</label>
                    <select name="Users[role]" class="styled status circle" data-placeholder="Все">
                        <?php
                        $RolesArray = Roles::model()->findAll('name != "admin"');
                        echo '<option value=0>Все</option>';

                        foreach ($RolesArray as $role) {
                            echo '<option  ' . ($userSearch->role == $role->name ? ' selected="selected"' : '') . '  value="' . $role->name . '">' . Users::getRole($role->name) . '</option>';
                        }
                        ?>
                    </select>
                <? } ?>
                </div>
                <div class="form-group">
                <label class="label">Статус пользователя:</label>

                <select name="Users[status]" class="styled status circle" data-placeholder="Все">
                    <?php

                    $statusArray = array('active' => 'Активен', 'none' => 'Не активен', 'limited' => 'Ограничен по ip', 'dismissed' => 'Уволен', 'noActivated' => 'Требует активации');

                    echo '<option value=0>Все</option>';

                    foreach ($statusArray as $key => $status) {

                        echo '<option  ' . ($userSearch->status == $key ? ' selected="selected"' : '') . '  value="' . $key . '">' . $status . '</option>';
                    }
                    ?>
                </select>
                </div>
                <div class="form-group form-group-btn">
                    <?php echo CHtml::submitButton('Найти', array('class' => 'btn white')); ?>
                </div>
                <?php echo CHtml::hiddenField('users[search]', 'true'); ?>
                <?php $this->endWidget(); ?>
            </div>
        </div>
        <div class="box-gray__head big">
            О разработчике
        </div>
        <div class="box-gray__body">
            <div class="box-gray__form">
                <form class="fly-validation" id="form-compay-info" action="#" method="post">
                    <ul class="compay-info edit-row" id="compay-info">
                        <li>Сайт: <a href="https://inclient.ru" target="_blank">inclient.ru</a>
						<li>Автор: <a href="https://inclient.ru/about/" target="_blank"> Алексей Бегина</a></li>
						<li>Бизнес-статьи: <a href="https://inclient.ru/category/for-bussines/" target="_blank"> перейти</a></li>
						<li>Полезные руководства: <a href="https://inclient.ru/category/guide/" target="_blank"> перейти</a></li>
                    </ul>
                </form>
            </div>
        </div>
    </div>
</aside><!--.left-sidebar -->
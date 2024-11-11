<?php $this->pageTitle = 'Пользователи'; ?>
<?php $correct_path = 'http://' . $_SERVER["HTTP_HOST"]; ?>
<?php $role = UsersRoles::model()->find('user_id=' . Yii::app()->user->id)->itemname; ?>
<div class="clients-hat clearfix">
    <div class="client-name pull-left">
        Пользователи
    </div>
    <div class="goback-link pull-right">
        <?php
        echo CHtml::button('Новый пользователь', array('onClick' => 'window.location.href= "' . Yii::app()->createUrl("page/create_user") . '"',
            'class' => 'btn_100 popup-open popup-open'));
        ?>
    </div>

</div>

<main class="content full2" role="main">
    <?php $this->renderPartial('_users_search', array('user' => $user, 'role' => $role, 'clientCount' => $clientCount,
        'actionCount' => $actionCount, 'dealCount' => $dealCount, 'userCount' => $userCount, 'userSearch' => $new_user,
        'allGroups' => $allGroups, 'roleFilter' => $roleFilter
    )); ?>
    
    <div class="box-gray">
        <div class="box-gray__body no-border bottom_margin">

            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider' => $users_table_data,
                'cssFile' => '',
                'emptyText' => 'Пользователей пока нет',
                'htmlOptions' => array('class' => 'main-table'),
                'columns' => array(
                    array(
                        'name' => 'first_name',
                        'header' => 'Имя пользователя',
                        'type' => 'raw',
                        'headerHtmlOptions' => array('class' => 'w9', 'style' =>
                            '   height: 12px;
                                    border-right: 1px solid #d9d9d9;
                                    border-bottom: 1px solid #d9d9d9;
                                    padding: 8px 8px 8px 12px;
                                    text-align:left;
                                    color: #222;
                                    line-height: 12px;'),
                        'value' => function ($data) {
                            return ($data->avatar ? CHtml::image($data->avatar, '', ['class' => 'miniAvatar']) :
                                    CHtml::image($data->roles[0]->name == 'manager' ? '/img/employee.svg' : ($data->roles[0]->name == 'director' ? '/img/ava_adminisrtr.svg' : '/img/ava_admin.svg'), '', ['class' => 'miniAvatar']))
                                . ' ' . CHtml::link($data->first_name, Yii::app()->createUrl("page/user_profile", array("id" => $data->id)));
                        }
                    ),
                    array(
                        'name' => 'parent_id',
                        'header' => 'Тип',
                        'headerHtmlOptions' => array('class' => 'w8_3', 'style' =>
                            '   height: 12px;
                                    border-right: 1px solid #d9d9d9;
                                    border-bottom: 1px solid #d9d9d9;
                                    padding: 8px 8px 8px 12px;
                                    text-align:left;
                                    color: #222;
                                    line-height: 12px;'),
                        'value' => function ($data) {
                            return Users::getRole(UsersRoles::model()->find('user_id = :id', [':id' => $data->id])->itemname);
                        }
                    ),
                    array(
                        'name' => 'email',
                        'header' => 'E-mail',
                        'headerHtmlOptions' => array('class' => 'w7', 'style' =>
                            '   height: 12px;
                                    border-bottom: 1px solid #d9d9d9;
									border-right: 1px solid #d9d9d9;
                                    padding: 8px 8px 8px 12px;
                                    text-align:left;
                                    color: #222;
                                    line-height: 12px;'),
                    ),
                    array(
                        'name' => 'parent',
                        'header' => 'Отвественный',
                        'type' => 'raw',
                        'headerHtmlOptions' => array('class' => 'w7_1', 'style' =>
                            '   height: 12px;
                                    border-bottom: 1px solid #d9d9d9;
									padding: 8px 8px 8px 12px;
                                    text-align:left;
                                    color: #222;
                                    line-height: 12px;'),
                        'value' => function ($data) {
                            return ($data->parent->avatar ?
                                    CHtml::image($data->parent->avatar, '', ['class' => 'miniAvatar'])
                                    : CHtml::image($data->parent->roles[0]->name == 'manager' ? '/img/employee.svg' : ($data->parent->roles[0]->name == 'director' ? '/img/ava_adminisrtr.svg' : '/img/ava_admin.svg'), '', ['class' => 'miniAvatar']))
                                . ' ' . $data->parent->first_name;
                        }
                    ),


                )
            ));
            ?>
        </div>
    </div>
</main><!--.content-->

<script>
    ButtonCheck('#create_user_button', '#popup_new_user_button', ['#Users_first_name', '#Users_email']);
</script>
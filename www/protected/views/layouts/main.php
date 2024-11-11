<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link rel="stylesheet" type="text/css"
          href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css<?= defined('YII_DEBUG') ? '?' . rand() : ''; ?>"/>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" media="print" href="/css/print.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <script type="module" src="/js/notificationBar.js"></script>
    <script crossorigin src="https://unpkg.com/react@16/umd/react.production.min.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@16/umd/react-dom.production.min.js"></script>
</head>

<?php
$role = UsersRoles::model()->find('user_id=' . Yii::app()->user->id)->itemname;
$userRight = UserRight::model()->find('user_id = ' . Yii::app()->user->id);
$accessDocument = false;
$mainDocument = 'document_client_page';
foreach ($userRight as $key => $value) {
    if (($key == 'add_files_client' || $key == 'add_files_action' || $key == 'add_files_deal' || $key == 'add_files_user') && $value) {
        $accessDocument = true;
        switch ($key) {
            case 'add_files_client':
                $mainDocument = 'document_client_page';
                break;
            case 'add_files_action':
                $mainDocument = 'document_action_page';
                break;
            case 'add_files_deal':
                $mainDocument = 'document_deal_page';
                break;
            case 'add_files_user':
                $mainDocument = 'document_user_page';
                break;
        }
        break;
    }
}
$user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);

$appearance = Appearance::model()->find();
?>

<body>
<div class="custom-modal-window" id="my-modal">
<div>
    <div id="modal-block-header">
        <span class="block-header-title"></span>
        <a href="javascript:;"></a>
    </div>
    <div id="modal-window-block">
        <div id="modal-content-message"></div>
        <div id="modal-block-content">
            <div id="modal-content-html"></div>
        </div>
        <div id="modal-block-footer">
            <button class="btn" id="btnOk">Применить</button>
            <button class="btn-cancel" id="btnCancel">Отмена</button>
        </div>
    </div>
</div>
</div>

<div class="wrapper">
    <div class="site-box">
        <header class="header" id="header">
            <div class="blue-hat clearfix">
                <div class="company-name pull-left"><img class="" src="/img/cloud.svg" style="height: 19px;padding-left: 2px;margin-top: -3px;padding-right: 5px;">

                    <? if($appearance->menu_link) {
                        echo '<a class="company-name__link" href="'. $appearance->menu_link . '" target="_blank">' . $appearance->menu_name . '</a>';
                    } else {
                        echo '<span class="company-name__link">' . $appearance->menu_name . '</span>';
                    }?>

                </div>
                <nav class="header-nav navbar">
                    <ul class="nav navbar-nav">
                        <li><?php echo CHtml::link('Контакты', array('page/clients_page'), ($this->action->id == 'index' || $this->action->id == 'clients_page') ? array('class' => 'active') : array()); ?></li>
                        <? if ($role == 'admin' || $userRight->create_action) { ?>
                            <li><?php echo CHtml::link('Задачи', array('page/actions_page'), $this->action->id == 'actions_page' ? array('class' => 'active') : array()); ?></li>
                        <? } ?>
                        <? if ($role == 'admin' || $userRight->create_deals) { ?>
                            <li><?php echo CHtml::link('Сделки', array('page/dealings_page'), $this->action->id == 'dealings_page' ? array('class' => 'active') : array()); ?></li>
                        <? } ?>
                        <? if ($role == 'admin' || $accessDocument) { ?>
                            <li><?php echo CHtml::link('Файлы', array('page/' . $mainDocument), in_array($this->action->id, ['document_client_page', 'document_action_page', 'document_deal_page', 'document_user_page']) ? array('class' => 'active') : array()); ?></li>
                        <? } ?>
                      </ul>
                </nav>
                <div class="header-options pull-right">
				<a href="#" class="headerSettings">
                    <?php
                    if($user->avatar && file_exists(Yii::getPathOfAlias('webroot') . $user->avatar)) {
                        echo CHtml::tag('img', ['class' => 'headerAvatar', 'src' => $user->avatar]);
                    } else {
                        switch ($user->roles[0]->name) {
                            case 'admin':
                                echo CHtml::tag('img', ['class' => 'headerAvatar', 'src' => '/img/ava_admin.svg']);
                                break;
                            case 'director':
                                echo CHtml::tag('img', ['class' => 'headerAvatar', 'src' => '/img/ava_adminisrtr.svg']);
                                break;
                            case 'manager':
                                echo CHtml::tag('img', ['class' => 'headerAvatar', 'src' => '/img/employee.svg']);
                                break;
                        }
                    }
                    ?>
                    <?php echo Users::model()->findByPk(Yii::app()->user->id)->email ?></a>
                    <ul>

                        <li><?php echo CHtml::link('Мой профиль', array('page/user_profile/' . Yii::app()->user->id)); ?></li>

                        <?php if ($role != 'manager') { ?>
                            <li><?php echo CHtml::link('Пользователи', array('page/user_info')); ?></li>
                        <?php } ?>

                        <?php if ($role == 'admin') {
                                  $link = 'settings_additional_field';
                              } elseif ($userRight->create_field) {
                                  $link = 'Settings_additional_field';
                              } elseif ($userRight->create_label_clients
                                  || $userRight->create_label_actions
                                  || $userRight->create_label_deals) {
                                  $link = 'settings_labels';
                              } elseif ($userRight->create_steps) {
                                  $link = 'settings_steps';
                              }
                        if (isset($link)) {?>
                        <li><?php echo CHtml::link('Настройки', array('page/' . $link)); ?></li>
                        <?}?>
                        <hr class="homePageCategories__hr9">
                        <li style="margin-bottom: 5px;"><?php echo CHtml::link('Выйти', array('page/logout')); ?></li>
                    </ul>
                </div>
            </div>
        </header>
        <div class="main" id="main">
            <section class="middle">
                <?php echo $content; ?>
            </section>
        </div>

        <?
        $appearanceLinks = AppearanceLinks::model()->findAll();
        ?>
        <div class="footer" id="footer">
            <div class="copyright">
                <? if ( $appearance->footer_link) {
                    echo '<a target="_blank" style="margin-right: 10px" href="' . $appearance->footer_link . '">' . $appearance->footer_name . '</a>';
                } else {
                    echo '<span style="margin-right: 10px">' . $appearance->footer_name . '</span>';
                }
                ?>

                <? foreach ($appearanceLinks as $count => $value) {
                    echo '<a target="_blank" href="' . $value->link_value . '">' . $value->link_name . '</a>';
                    echo count($appearanceLinks) > $count + 1 ? ' | ' : '';
                }?>
            </div>
        </div>
    </div>
</div>



<script>
    let leftMenu = document.getElementsByClassName('left-sidebar');
    let footer = document.getElementById('footer');
</script>

</body>
</html>
<? Yii::app()->clientScript->registerScriptFile('/js/jquery-3.2.1.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile('/js/jquery.formstyler.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('/js/jquery.fancybox.pack.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('/js/jquery.validate.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('/js/jquery.bxslider.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('/js/mask.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('/js/main.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('/js/notificationBar.js', CClientScript::POS_END, ['type' => 'module']);
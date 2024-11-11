<?php $this->pageTitle = 'Документы'; ?>
<div class="clients-hat">
    <div class="client-name pull-left">Список документов</div>

	<div class="goback-link pull-right">
	<nav class="clients-nav navbar">
        <ul class="nav navbar-nav">
            <?php
            if ($userRight['add_files_client']) {
                ?>
                <li><?php echo CHtml::link('Контакты', array('page/document_client_page'), isset($clients) ? array('class' => 'active') : array()); ?></li>
                <?php
            }
            if ($userRight['add_files_action']) {
                ?>
                <li><?php echo CHtml::link('Задачи', array('page/document_action_page'), isset($actions) ? array('class' => 'active') : array()); ?></li>
                <?php
            }
            if ($userRight['add_files_deal']) {
                ?>
                <li><?php echo CHtml::link('Сделки', array('page/document_deal_page'), isset($deals) ? array('class' => 'active') : array()); ?></li>
                <?php
            }
            if ($userRight['add_files_user']) {
                ?>
                <li><?php echo CHtml::link('Пользователи', array('page/document_user_page'), isset($users) ? array('class' => 'active') : array()); ?></li>
                <?php
            }
            ?>
        </ul>
    </nav>
	</div>
</div>
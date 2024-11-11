<?php $this->pageTitle = 'Файлы'; ?>
<div class="clients-hat">
    <div class="goback-link pull-right">
        <nav class="clients-nav_01 navbar">
            <ul class="nav navbar-nav">
                <? if ($userRight['add_files_client']) { ?>
                    <li><?php echo CHtml::link('Контакты', Yii::app()->createUrl("page/document_client_page")); ?></li>
                <? }
                if ($userRight['add_files_action']) { ?>
                    <li class="active"><?php echo CHtml::link('Задачи', Yii::app()->createUrl("page/document_action_page")); ?></li>
                <? }
                if ($userRight['add_files_deal']) { ?>
                    <li><?php echo CHtml::link('Сделки', Yii::app()->createUrl("page/document_deal_page")); ?></li>
                <? }
                if ($userRight['add_files_user']) { ?>
                    <li><?php echo CHtml::link('Пользователи', Yii::app()->createUrl("page/document_user_page")); ?></li>
                <? } ?>
            </ul>
        </nav>
    </div>
    <div class="client-name">
        Список файлов
    </div>
</div>

<main class="content full2" role="main">
    <?php $this->renderPartial('_documents_actions_search', array('documents' => $documents, 'user' => $user, 'userRight' => $userRight)); ?>

    <div class="box-gray">
        <div class="box-gray__body no-border bottom_margin">
            <?php
            if (count($documentAction->data) == 0) { ?>
                <?
            }
            $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider' => $documentAction,
                'cssFile' => '',
                'htmlOptions' => array('class' => 'main-table'),
                'columns' => array(
                    array(
                        'name' => 'file_name',
                        'header' => 'Имя файла',
                        'type' => 'raw',
                        'headerHtmlOptions' => array('class' => 'w_f_1'),
                        'value' => function ($data) {
                            return
                                '<div class="tableDocumentMain"><div class="tableDocumentName">' .
                                CHtml::link($data->file->name, Yii::app()->createUrl("page/get_file_action", ['id' => $data->id]), ['target' => '_blank']) .
                                '</div></div>';
                        }
                    ),
                    array(
                        'name' => 'client_name',
                        'header' => 'Задача',
                        'headerHtmlOptions' => array('class' => 'w_f_2'),
                        'type' => 'raw',
                        'value' => function ($data) {
                            return CHtml::link($data->action->text, Yii::app()->createUrl("page/edit_action", array("id" => $data->action->id)));
                        }
                    ),
                    array(
                        'name' => 'date_upload',
                        'header' => 'Дата загрузки',
                        'headerHtmlOptions' => array('class' => 'w_f_3'),
                        'type' => 'raw',
                        'value' => function ($data) {
                            return date('d.m.Y H:i', strtotime($data->file->date_upload));
                        }
                    ),
                )
            ));
            ?>
        </div>
    </div>
</main><!--.content-->

<script>
    function delDocument(id) {
        if (confirm('Вы дествительно хотите удалить документ?')) {
            document.location.href = '/page/action_document_delete?id=' + id + '&return=table';
        }
    }
</script>
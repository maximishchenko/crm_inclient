<aside class="left-sidebar">
    <div class="box-gray__head">
        Поиск файлов
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
                <?php echo $form->textField($documents, 'keyword', array('type' => 'text', 'class' => 'form-control', 'placeholder' => 'Поиск')); ?>
            </div>
            <?php $role = UsersRoles::model()->find('user_id=' . Yii::app()->user->id)->itemname;
            ?>
            <div class="form-group">
                <label class="label">Ответственный:</label>
                <?php
                $responsible_options = array('all' => 'Все пользователи', Yii::app()->user->id => 'Я ответственный', 'director' => 'Руководители', 'manager' => 'Менеджеры', 'admin' => $user->parent->first_name);

                $managers = Users::getUserAccess($user, true, false, true);
                $directors = Users::getUserAccess($user, false, true, true);
                if ($user->parent->roles[0]->name != 'admin' || $user->common_access == Users::ACCESS_EMBAGRO
                    || $user->common_access == Users::ACCESS_MANAGER || $user->roles[0]->name == 'admin'
                ) {
                    unset($responsible_options['admin']);
                }

                if (count($directors) <= 0) {
                    unset($responsible_options['director']);
                }

                if (count($managers) <= 0) {
                    unset($responsible_options['manager']);
                }

                // выбор значений в селекторах с ролями и пользователями
                if ($documents->responsable_id == Yii::app()->user->id) {
                    $selected_option = array('i' => array('selected' => true));
                } elseif ($documents->responsable_id == 'no') {
                    $selected_option = array('no' => array('selected' => true));
                } elseif ($documents->responsable_id == $user->parent_id) {
                    $selected_option = array('admin' => array('selected' => true));
                } else {
                    $selected_option = array('all' => array('selected' => true));
                }

                $directors_block_to_display = '';
                $managers_block_to_display = '';

                if (is_numeric($documents->responsable_id) && $documents->responsable_id != 0) {
                    $client_resp_role = UsersRoles::model()->find('user_id=' . $documents->responsable_id);
                    if ($client_resp_role->itemname == 'director') {
                        $selected_option = array('director' => array('selected' => true));
                    } elseif ($client_resp_role->itemname == 'manager') {
                        $selected_option = array('manager' => array('selected' => true));
                    }
                    $directors_block_to_display = $client_resp_role->itemname == 'director' ? 'style="display:block"' : '';
                    $managers_block_to_display = $client_resp_role->itemname == 'manager' ? 'style="display:block"' : '';
                }
                ?>
                <?php echo $form->dropDownList($documents, 'responsable_id', $responsible_options, array('options' => $selected_option, 'class' => 'styled permis editable typeAccess', 'name' => 'type')); ?>
            </div>
            <div class="access-options access-tab" id="director" <?php echo $directors_block_to_display ?>>
                <?php echo $form->dropDownList($documents, 'director_id', CHtml::listData($directors, 'id', 'first_name'), array('options' => is_numeric($documents->responsable_id) && $documents->responsable_id != 0 ? array($documents->responsable_id => array('selected' => true)) : '', 'class' => 'styled')); ?>
            </div>
            <div class="access-options access-tab" id="manager" <?php echo $managers_block_to_display ?>>
                <?php echo $form->dropDownList($documents, 'manager_id', CHtml::listData($managers, 'id', 'first_name'), array('options' => is_numeric($documents->responsable_id) && $documents->responsable_id != 0 ? array($documents->responsable_id => array('selected' => true)) : '', 'class' => 'styled')); ?>
            </div>


            <div class="form-group">
                <label class="label">Дата загрузки:</label>
                <?php echo $this->widget('ext.CJuiDateTimePicker.CJuiDateTimePicker', array(
                    'name' => 'ActionsFiles[start_date]',
                    'model' => $documents,
                    'attribute' => 'start_date',
                    'language' => 'ru',
                    'options' => array(
                        'dateFormat' => 'dd.mm.yy',
                        'changeMonth' => 'true',
                        'changeYear' => 'true',
                        'showButtonPanel' => true,
                        'beforeShow' => new CJavaScriptExpression('function(element){dataPickerFocus = $(element).attr(\'id\').trim();}')
                    ),
                    'htmlOptions' => array(
                        'class' => 'form-control',
                        'placeholder' => 'От'
                    ),
                ), true); ?>
            </div>
            <div class="form-group">
                <?php echo $this->widget('ext.CJuiDateTimePicker.CJuiDateTimePicker', array(
                    'name' => 'ActionsFiles[stop_date]',
                    'model' => $documents,
                    'attribute' => 'stop_date',
                    'language' => 'ru',
                    'options' => array(
                        'dateFormat' => 'dd.mm.yy',
                        'changeMonth' => 'true',
                        'changeYear' => 'true',
                        'showButtonPanel' => true,
                        'beforeShow' => new CJavaScriptExpression('function(element){dataPickerFocus = $(element).attr(\'id\').trim();}')
                    ),
                    'htmlOptions' => array(
                        'class' => 'form-control',
                        'placeholder' => 'До'
                    ),
                ), true); ?>
            </div>

            <div class="form-group">
                <div class="form-group form-group-btn">
                    <?php echo CHtml::hiddenField('ActionsFiles[search]', 'true'); ?>
                    <?php echo CHtml::submitButton('Найти', array('class' => 'btn white')); ?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</aside><!--.left-sidebar -->

<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
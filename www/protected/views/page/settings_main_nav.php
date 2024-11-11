<div class="simple-taber">
    <ul class="horizontal">
        <?
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $right = Yii::app()->commonFunction->getUserRight($user);
        if ($right['role'] == 'admin') {
            ?>
            <li <?php echo isset($additionalField) ? 'class="active"' : '' ?>><?php echo CHtml::link('Анкета контакта', array('page/settings_additional_field')); ?></li>
            <li <?php echo isset($steps) ? 'class="active"' : '' ?>><?php echo CHtml::link('Воронка', array('page/settings_steps')); ?></li>
            <li <?php echo isset($labels) ? 'class="active"' : '' ?>><?php echo CHtml::link('Метки', array('page/settings_labels')); ?></li>
            <li <?php echo isset($deals) ? 'class="active"' : '' ?>><?php echo CHtml::link('Сделки', array('page/settings_deals')); ?></li>
			<li <?php echo isset($userGroups) ? 'class="active"' : '' ?>><?php echo CHtml::link('Пользователи', array('page/settings_users_groups')); ?></li>
            <li <?php echo isset($common) ? 'class="active"' : '' ?>><?php echo CHtml::link('Разное', array('page/settings_common')); ?></li>
			<li <?php echo isset($security) ? 'class="active"' : '' ?>><?php echo CHtml::link('Безопасность', array('page/settings_security')); ?></li>
            <li <?php echo isset($contacts) ? 'class="active"' : '' ?>><?php echo CHtml::link('Контакты', array('page/settings_contacts')); ?></li>
            <?php
        } else { ?>
            <?
                if($right['create_field']) {
                    ?>
                    <li <?php echo isset($additionalField) ? 'class="active"' : '' ?>><?php echo CHtml::link('Анкета контакта', array('page/settings_additional_field')); ?></li>

                    <?
                }
            if (Yii::app()->commonFunction->checkAccessShowLabel($right)) { ?>
                <li <?php echo isset($labels) ? 'class="active"' : '' ?>><?php echo CHtml::link('Метки', array('page/settings_labels')); ?></li>
            <?}
            if($right['create_steps']) {
            ?>
            <li <?php echo isset($steps) ? 'class="active"' : '' ?>><?php echo CHtml::link('Воронки', array('page/settings_steps')); ?></li>
            <?}?>
        <?} ?>
    </ul>
</div>
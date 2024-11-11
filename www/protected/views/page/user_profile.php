<?php $this->pageTitle = $user->first_name; ?>
<?php $correct_path = 'http://' . $_SERVER["HTTP_HOST"]; ?>
<div class="clients-hat">
    <div class="client-name">

        <?php
        if ($callUserRight['role'] != 'manager') {
            echo CHtml::link('Пользователи', array('page/user_info'));
        } else {
            echo 'Пользователи';
        }
        ?>
        <img src="/img/right-arrow-button.svg" alt="">
        <!--		--><?php /*echo Users::getRole($user->roles[0]->name); */ ?>

        <?php
        $name = Users::getRole($user->roles[0]->name);
        if ($user->roles[0]->name !== 'admin' && $callUserRight['role'] == 'admin') {
            echo CHtml::link($name, array("page/user_info?roleFilter=" . $user->roles[0]->name));
        } else {
            echo $name;
        }

        ?>
        <img src="/img/right-arrow-button.svg" alt="">
        <?php echo $user->first_name; ?>, #<?php echo $user->id; ?>

    </div>
    <div class="goback-link pull-right">
        <?php
        echo CHtml::button('Новый контакт', array('onClick' => 'window.location.href= "' . Yii::app()->createUrl("page/new_client", array("id" => $user->id)) . '"',
            'class' => 'btn_green', 'id' => 'popup_new_client_button'));
        ?>

        <?php
        echo CHtml::button('❮  Назад ', array('onClick' => 'window.location.href= "' . Yii::app()->createUrl("page/user_info") . '"',
            'class' => 'btn_close', 'id' => 'popup_new_client_button'));
        ?>
    </div>
</div>

<main class="content full2" role="main">
    <div class="content-edit-block">
            <div class="title_name_1">Профиль пользователя</div>
            <div class="additionalFieldTable" style="margin-bottom: 25px;">
                <div class="box-gray__body no-border2 active-pad resizeWidth">
                    <table class="main-table row edit-row" id="user-info">
                        <div class="profile_info_block clear_fix">
                            <div class="profile_info_header_wrap">
                                <span class="profile_info_header">О пользователе</span>
                            </div>
                        </div>

                        <tr>
                            <td class="an_001" width="132">Имя</td>
                            <td class="editable" rel="product"><?php echo $user->first_name; ?></td>
                        </tr>
						<tr>
                            <td class="an_001" width="132">ID</td>
                            <td class="editable" rel="product"><?php echo $user->id; ?>
                            </td>
                        </tr>
						<tr>
                            <td class="an_001" width="132">Email</td>
                            <td class="editable" rel="product"><?php echo $user->email; ?></td>
                        </tr>
                        <tr>
                            <td class="an_001" width="132">Отвественный</td>
                            <td class="editable" rel="product"><?php echo $user->parent->first_name; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="an_001" width="132">Статус</td>
                            <td class="editable" rel="product"><?php echo Users::getStatus($user->status); ?></td>
                        </tr>
                        <tr>
                            <td class="an_001" width="132">Тип</td>
                            <td class="editable"
                                rel="product"><?php echo Users::getRole($user->roles[0]->name); ?></td>
                        </tr>
                        <tr>
                            <td class="an_001" width="132">Группа</td>
                            <td class="editable" rel="product"><?php echo $userGroup ?></td>
                        </tr>                     


                    </table>
                </div>


                <div class="box-gray__body no-border2 active-pad resizeWidth">
                    <table class="main-table row edit-row" id="user-info">
                        <div class="profile_info_block clear_fix">
                            <div class="profile_info_header_wrap">
                                <span class="profile_info_header">Дополнительно</span>
                            </div>
                        </div>
                        <tr>
                            <td class="an_001" width="132">Контакты</td>
                            <td class="editable"
                                rel="product"><? echo count($client_table_data->data) ?></td>
                        </tr>
                        <tr>
                            <td class="an_001" width="132">Телефон</td>
                            <td class="editable" rel="product"><?php echo $user->phone; ?></td>
                        </tr>
                        <tr>
                            <td class="an_001" width="132">Должность</td>
                            <td class="editable" rel="product"><?php echo $user->position; ?></td>
                        </tr>

                        <tr>
                            <td class="an_001" width="132">Дата создания</td>
                            <td class="editable"
                                rel="product"><?php echo date('d.m.Y H:m', strtotime($user->reg_date)); ?></td>
                        </tr>
                        <tr>
                            <td class="an_001" width="132">Последний IP:</td>
                            <td class="editable" rel="product"><?php echo $user->last_ip; ?></td>
                            </td>
                        </tr>
                        <tr>
                            <td class="an_001" width="132">Последний вход</td>
                            <td class="editable"
                                rel="product"><?php echo date('d.m.Y H:m:s', strtotime($user->last_login)); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!--Задачи и Сделки-->
            <div class="additionalFieldTable" style="margin-bottom: 25px;">
                <div class="box-gray__body no-border2 active-pad resizeWidth">
                    <table class="main-table row edit-row" id="user-info">
                        <div class="profile_info_block clear_fix">
                            <div class="profile_info_header_wrap">
                                <span class="profile_info_header">Сделки</span>
                            </div>
                        </div>
                        <tr>
                            <td class="an_001" width="132">Все сделки</td>
                            <td class="editable"
                                rel="product"><span
                                        class="mini_all_deal"><? echo $priority['deals']['countAll'] ?></span><span
                                        class="mini_01">:</span> <span
                                        class="mini"><? echo $priority['deals']['sumPaid'] ?></span><span
                                        class="mini_01">сумма,</span> <span
                                        class="mini"><? echo $priority['deals']['sumBalance'] ?></span><span
                                        class="mini_01">остаток</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="an_001" width="132">Активно</td>
                            <td class="editable" rel="product"><span class="mini_active"><?php echo
                                    $priority['deals']['active']['count'] ?></span><span class="mini_01">:</span> <span class="mini"><?php echo $priority['deals']['active']['paid'] ?></span><span
                                        class="mini_01">сумма,</span> <span
                                        class="mini"><?php echo $priority['deals']['active']['balance'] ?></span><span
                                        class="mini_01">остаток</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="an_001" width="132">Выиграно</td>
                            <td class="editable" rel="product"><span
                                        class="mini_win"><?php echo $priority['deals']['win']['count'] ?></span><span
                                        class="mini_01">:</span> <span
                                        class="mini"><?php echo $priority['deals']['win']['paid'] ?></span><span
                                        class="mini_01">сумма,</span> <span
                                        class="mini"><?php echo $priority['deals']['win']['balance'] ?></span><span
                                        class="mini_01">остаток</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="an_001" width="132">Проиграно</td>
                            <td class="editable" rel="product"><span
                                        class="mini_los"><?php echo $priority['deals']['lose']['count'] ?></span><span
                                        class="mini_01">:</span> <span
                                        class="mini"><?php echo $priority['deals']['lose']['paid'] ?></span><span
                                        class="mini_01">сумма,</span> <span
                                        class="mini"><?php echo $priority['deals']['lose']['balance'] ?></span><span
                                        class="mini_01">остаток</span>

                            </td>
                        </tr>
                    </table>
                </div>

                <div class="box-gray__body no-border2 active-pad resizeWidth">
                    <table class="main-table row edit-row" id="user-info">
                        <div class="profile_info_block clear_fix">
                            <div class="profile_info_header_wrap">
                                <span class="profile_info_header">Задачи</span>
                            </div>
                        </div>

                        <tr>
                            <td class="an_001" width="132">Все задачи</td>
                            <td class="editable" rel="product"><span
                                        class="mini_all_action"><?php echo $priority['actions']['all'] ?></span><span
                                        class="mini_01">:</span> <span
                                        class="mini"><?php echo $priority['actions']['expected'] ?></span><span
                                        class="mini_01">ожидается,</span> <span
                                        class="mini"><?php echo $priority['actions']['countFinish'] ?></span><span
                                        class="mini_01">выполненные</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="an_001" width="132">Ожидается</td>
                            <td class="editable" rel="product"><span
                                        class="mini_today"><?php echo $priority['actions']['countToDay']; ?></span><span
                                        class="mini_01">сегодня,</span> <span
                                        class="mini_future"><?php echo $priority['actions']['countFuture']; ?></span><span
                                        class="mini_01">будущие,</span> <span
                                        class="mini_expired"><?php echo $priority['actions']['countOverdue']; ?></span><span
                                        class="mini_01">просроченные</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="an_001" width="132">Выполненные</td>
                            <td class="editable" rel="product"><span
                                        class="mini_completed"><?php echo $priority['actions']['completed'] ?></span><span
                                        class="mini_01">завершено,</span> <span
                                        class="mini_no_result"><?php echo $priority['actions']['noResult'] ?></span><span
                                        class="mini_01">нет результата</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

        <div class="user-table-block">
            <? if ($labelActionsId) {
                $active = 'actions';
            } elseif ($labelDealsId) {
                $active = 'deals';
            } else {
                $active = 'clients';
            }
            ?>


            <ul>
                <li class="button-change-table <? echo $active != 'clients' ?: 'active' ?>" id="button-table-clients"
                    onclick="selectTable('clients')">
                    Контакты <span><? echo $client_table_data->totalItemCount ?></span></li>
                <li class="button-change-table <? echo $active != 'actions' ?: 'active' ?>" id="button-table-actions"
                    onclick="selectTable('actions')">Задачи
                    <span><? echo $actions_table_data->totalItemCount ?></span></li>
                <li class="button-change-table <? echo $active != 'deals' ?: 'active' ?>" id="button-table-deals"
                    onclick="selectTable('deals')">Сделки
                    <span><? echo $deals_table_data->totalItemCount ?></span></li>
                <? if (!(Yii::app()->user->id == $user->id && $callUserRight['role'] == 'manager')) { ?>
                    <li class="button-change-table <? echo $callUserRight['role'] == 'admin' || $callUserRight['add_files_user'] ?: 'hide ' ?><? echo $active != 'files' ?: 'active' ?>"
                        id="button-table-files" onclick="selectTable('files')">Файлы
                        <span><? echo $filesTableData->totalItemCount ?></span>
                    </li>
                <? } ?>

            </ul>
        </div>

        <div id="table-clients">
            <div class="content-02">
                <?php
                if (count($client_table_data->data) == 0) { ?>
                    <div class="info_client_001"><p>Контактов нет</p></div>
                    <?
                }
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider' => $client_table_data,
                    'cssFile' => '',
                    'emptyText' => '',
                    'htmlOptions' => array('class' => 'new-table-main'),
                    'columns' => array(
                        array(
                            'name' => 'name',
                            'header' => 'Контакты',
                            'type' => 'raw',
                            'headerHtmlOptions' => array('class' => 'w9', 'style' =>
                                '   height: 12px;
								border-right: 1px solid #d9d9d9;
								border-bottom: 1px solid #d9d9d9;
								padding: 8px 11px;
								text-align:left;
								font-size: 11px;
								color: #222;
								line-height: 12px;
								display: none'),
                            'value' => function ($data) {
                                $changeDateClient = '';
                                if ($data->change_client_date) {
                                    $changeDateClient = Yii::app()->commonFunction->getChangeDateClient($data->change_client_date);
                                }
                                $criteria = new CDbCriteria;
                                foreach ($data->labelsInClients as $value) {
                                    $criteria->addCondition('t.id = ' . $value->label_id, "OR");
                                }
                                $idHTML = '<div class="block_labels">' . '<span class="idHTML"> #' . $data->id . '</span>'
                                    . '<span class="werwe"></span> ' . '<span class="tooltip">' . $changeDateClient . '<span class="tooltiptext tooltip-bottom">' . 'Контакт изменен ' . date('d.m.Y H:i', strtotime($data->change_client_date)) . '</span>' . '</span> ';
                                $labelHTML = '';
                                if ($criteria->condition != '' && $labels = Labels::model()->findAll($criteria)) {
                                    $type = "'Clients'";
                                    foreach ($labels as $label) {
                                        $labelHTML .= '<div onclick="clickLabel(' . $label->id . ',' . $type . ')" class="custom-label pointer" style="background-color: ' . $label->color . '; color:' . $label->color_text . '">' . $label->name . '</div>';
                                    }
                                }
                                $labelHTML .= '</div>';

                                $stepOptionColor = '';
                                $stepOptionName = '';
                                $stepName = '';
                                if ($step = StepsInClients::model()->with('steps')->find('clients_id = :ID', [':ID' => $data->id])) {
                                    if ($step->selected_option_id && $stepSelectedOption = StepsOptions::model()->findByPk($step->selected_option_id)) {
                                        $stepOptionColor = $stepSelectedOption->color;
                                        $stepOptionName = $stepSelectedOption->name;

                                    }

                                    if ($step->steps_id == 1 || $step->steps_id == 2) {
                                        $stepOptionName = $step->steps->name;
                                    }

                                    $stepName = $step->steps->name;
                                }

                                $dddd2 = '<span class="new-table-date_actions flex-start">' .
                                    '<span class="tooltip"> ' .
                                    '<span class="stepIndication" style="background-color:' . $stepOptionColor . '">' . '</span>' . '<span style="padding-left: 13px;">' . $stepOptionName . '</span>' . '<span class="tooltiptext tooltip-bottom">' . $stepName . '</span>' . '</span>' .
                                    '</span>';


                                return

                                    '<div class="new-table">
	<div class="new-table-left">
		<div class="new-table-header-01">
			<div class="new-table-name-client">' .
                                    CHtml::link($data->name, Yii::app()->createUrl("page/client_profile", array("id" => $data->id))) .
                                    (count($data->clientsFiles) > 0 ? '<a class="file_add" tabindex="1"><img src="/img/paper-clip.svg"></a>' : '') .
                                    '<span class="sdf2">' .

                                    ($data->responsable->avatar ? CHtml::image($data->responsable->avatar, '', ['class' => 'miniAvatar']) : CHtml::image($data->responsable->roles[0]->name == 'manager' ? '/img/employee.svg' : ($data->responsable->roles[0]->name == 'director' ? '/img/ava_adminisrtr.svg' : '/img/ava_admin.svg'), '', ['class' => 'miniAvatar'])) . CHtml::link($data->responsable->first_name, Yii::app()->createUrl("page/user_profile", array("id" => $data->responsable->id))) .
                                    '</span>' .
                                    '</div>
			
		</div>
		<div class="new-table-bottom">'
                                    . $idHTML . $labelHTML . $dddd2 .
                                    '</div>
	</div></div>';

                            }
                        ),
                    )));
                ?>

            </div>
        </div>

        <div id="table-actions">
            <div class="content-02">
                <?php
                if (count($actions_table_data->data) == 0) { ?>
                    <div class="info_client_001"><p>Задач нет</p></div>
                    <?
                }
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider' => $actions_table_data,
                    'cssFile' => '',
                    'emptyText' => '',
                    'htmlOptions' => array('class' => 'new-table-main'),
                    'columns' => array(
                        array(
                            'name' => 'name',
                            'header' => 'Задачи',
                            'type' => 'raw',
                            'headerHtmlOptions' => array('class' => 'w9', 'style' =>
                                '   height: 12px;
								border-right: 1px solid #d9d9d9;
								border-bottom: 1px solid #d9d9d9;
								padding: 8px 11px;
								text-align:left;
								font-size: 11px;
								color: #222;
								line-height: 12px;
								display: none
							 '),
                            'value' => function ($data) {

                                $action_date = date('Y-m-d', strtotime($data->action_date)) . ' 23:59:59';
                                $actionStatusColor = '#F96F93';
                                if (strtotime($action_date) >= time() || $data->action_status_id != 1) {
                                    $actionStatusColor = $data->actionStatus->color;
                                }
                                $actionIndication = '<div class="labelStatusAction" style="background-color:' . $actionStatusColor . '"> </div>';

                                $criteria = new CDbCriteria;
                                foreach ($data->labelsInActions as $value) {
                                    $criteria->addCondition('t.id = ' . $value->label_id, "OR");
                                }
                                $labelHTML = '';
                                $idHTML = '<div class="block_labels">' . '<span class="idHTML"> #' . $data->id . '</span>' .
                                    '<span class="werwe"></span>' . $data->actionStatus->name .

                                    '</span>';
                                //
                                if ($criteria->condition != '' && $labels = Labels::model()->findAll($criteria)) {
                                    $labelHTML = '';
                                    $type = "'Actions'";
                                    foreach ($labels as $label) {
                                        $labelHTML .= '<div onclick="clickLabel(' . $data->client_id . ',' . $label->id . ',' . $type . ')" class="custom-label pointer" style="background-color: ' . $label->color . '; color:' . $label->color_text . '">' . $label->name . '</div>';
                                    }
                                    $labelHTML .= '</div>';
                                }
                                $dddd2 = ' <span class="new-table-date_actions">'

                                    .
                                    '<a class="support" tabindex="1">
												' . date('d.m.Y' . ' в ' . 'H:i', strtotime($data->action_date)) . '
													
													<span class="tip">
														<div class="chok_wert">
															<div class="chok" style="font-weight: bold;padding-bottom: 10px;">' . $data->text . '</div>
															<div class="chok">' . $data->description . '</div>
														</div>
														<div class="chok_life">
														<span class="chol">' . $data->actionStatus->name . ': </span>' . date('d.m.Y' . ' в ' . 'H:i', strtotime($data->action_date)) . '</div>
														<div class="chok"><span class="chol">Ответственный:  </span>' . $data->responsable->first_name . '</div>
													</span>
													</a>' .


                                    '</span>';


                                return
                                    '<div class="new-table">
													<div class="new-table-left">
														<div class="new-table-header">
															<div class="new-table-name-client"> ' . $actionIndication . CHtml::link($data->text, Yii::app()->createUrl("page/edit_action", array("id" => $data->id, "render_page" => 'actions_page')), ['class' => 'float-left']) . (count($data->actionsFiles) > 0 ? '<a class="file_add" tabindex="1"><img src="/img/paper-clip.svg"></a>' : '') .
                                    '<span class="sdf2">' .

                                    ($data->responsable->avatar ? CHtml::image($data->responsable->avatar, '', ['class' => 'miniAvatar']) : CHtml::image($data->responsable->roles[0]->name == 'manager' ? '/img/employee.svg' : ($data->responsable->roles[0]->name == 'director' ? '/img/ava_adminisrtr.svg' : '/img/ava_admin.svg'), '', ['class' => 'miniAvatar'])) . CHtml::link($data->responsable->first_name, Yii::app()->createUrl("page/user_profile", array("id" => $data->responsable->id))) .


                                    '</div>
														<div class="new-table-name-resp"></div>
													</div>
													<div class="new-table-bottom">' . $idHTML . $labelHTML . $dddd2 . '</div>
												
												</div>
												</div>';

                            }
                        ),
                    )));
                ?>


            </div>
        </div>

        <div id="table-deals">
            <div class="content-02">
                <?php
                if (count($deals_table_data->data) == 0) { ?>
                    <div class="info_client_001"><p>Сделок нет</p></div>
                    <?
                }
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider' => $deals_table_data,
                    'cssFile' => '',
                    'emptyText' => '',
                    'htmlOptions' => array('class' => 'new-table-main'),
                    'columns' => array(
                        array(
                            'name' => 'name',
                            'header' => 'Сделки',
                            'type' => 'raw',
                            'headerHtmlOptions' => array('class' => 'w9', 'style' =>
                                '   height: 12px;
								border-right: 1px solid #d9d9d9;
								border-bottom: 1px solid #d9d9d9;
								padding: 8px 11px;
								text-align:left;
								font-size: 11px;
								color: #222;
								line-height: 12px;
								display: none
							 '),
                            'value' => function ($data) {
                                $stepName = '';
                                $criteria = new CDbCriteria;
                                foreach ($data->labelsInDeals as $value) {
                                    $criteria->addCondition('t.id = ' . $value->label_id, "OR");
                                }
                                $idHTML = '<div class="block_labels">' . '<span class="idHTML"> #' . $data->id . '</span>' .

                                    '<span class="werwe"></span>' . round($data->paid) . ' / ' . round($data->balance);

                                $labelHTML = '';
                                if ($criteria->condition != '' && $labels = Labels::model()->findAll($criteria)) {
                                    $type = "'Deals'";
                                    foreach ($labels as $label) {
                                        $labelHTML .= '<div onclick="clickLabel(' . $data->client_id . ',' . $label->id . ',' . $type . ')" class="custom-label pointer" style="background-color: ' . $label->color . '; color:' . $label->color_text . '">' . $label->name . '</div>';
                                    }
                                }
                                $labelHTML .= '</div>';

                                $stepOptionColor = '';
                                $stepOptionName = '';
                                if ($step = StepsInDeals::model()->with('steps')->find('deals_id = :ID', [':ID' => $data->id])) {
                                    if ($step->selected_option_id && $stepSelectedOption = StepsOptions::model()->findByPk($step->selected_option_id)) {
                                        $stepOptionColor = $stepSelectedOption->color;
                                        $stepOptionName = $stepSelectedOption->name;
                                    }

                                    if ($step->steps_id == 1 || $step->steps_id == 2) {
                                        $stepOptionName = $step->steps->name;
                                    }

                                    $stepName = $step->steps->name;
                                }

                                $dddd2 = '<span class="new-table-date_actions flex-start">' .
                                    '<span class="tooltip"> ' .
                                    '<span class="stepIndication" style="background-color:' . $stepOptionColor . '">' . '</span>' . '<span style="padding-left: 13px;">' . $stepOptionName . '</span>' . '<span class="tooltiptext tooltip-bottom">' . $stepName . '</span>' . '</span>' .
                                    '</span>';

                                $dealTypeClass = [
                                    1 => 'dealTypeActiveSquare',
                                    2 => 'dealTypeWinSquare',
                                    3 => 'dealTypeLoseSquare',
                                ];

                                return
                                    '<div class="new-table">
												<div class="new-table-left">
													<div class="new-table-header">
														<div class="new-table-name-client">' . '<div class="' . $dealTypeClass[$data->deal_type_id] . '"></div>' . CHtml::link($data->text, Yii::app()->createUrl("page/edit_deal", array("id" => $data->id, "render_page" => 'dealings_page'))) . (count($data->dealsFiles) > 0 ? '<a class="file_add" tabindex="1"><img src="/img/paper-clip.svg"></a>' : '') .

                                    '<span class="sdf2">' .

                                    ($data->responsable->avatar ? CHtml::image($data->responsable->avatar, '', ['class' => 'miniAvatar']) : CHtml::image($data->responsable->roles[0]->name == 'manager' ? '/img/employee.svg' : ($data->responsable->roles[0]->name == 'director' ? '/img/ava_adminisrtr.svg' : '/img/ava_admin.svg'), '', ['class' => 'miniAvatar'])) . CHtml::link($data->responsable->first_name, Yii::app()->createUrl("page/user_profile", array("id" => $data->responsable->id))) .

                                    '</div>
														<div class="new-table-name-resp"></div>
													</div>
													<div class="new-table-bottom">' . $idHTML . $labelHTML . $dddd2 . '</div>
											</div>
											</div>';
                            }
                        ),
                    )));
                ?>
            </div>
        </div>

        <!-- Список файлов -->

        <div id="table-files">
            <div class="content-02">

                <?php
                if ($callUserRight['role'] == 'admin' || $callUserRight['add_files_user']) {
                    if (count($filesTableData->data) == 0) { ?>
                        <div class="info_client_001"><p>Файлов пока нет</p></div>
                        <?
                    }
                    $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $filesTableData,
                        'cssFile' => '',
                        'emptyText' => '',
                        'htmlOptions' => array('class' => 'new-table-main'),
                        'columns' => array(
                            array(
                                'name' => 'name',
                                'header' => 'Задачи',
                                'type' => 'raw',
                                'headerHtmlOptions' => array('class' => 'w9', 'style' =>
                                    '   height: 12px;
								border-right: 1px solid #d9d9d9;
								border-bottom: 1px solid #d9d9d9;
								padding: 8px 11px;
								text-align:left;
								font-size: 11px;
								color: #222;
								line-height: 12px;
								display: none
							 '),
                                'value' => function ($data) {
                                    $user = Users::model()->with(['userRights'])->findByPk(Yii::app()->user->id);
                                    $userRight = Yii::app()->commonFunction->getUserRight($user);
                                    if ($userRight['role'] == 'admin' || $userRight['delete_files_client']) {
                                        $del = CHtml::image('/img/cancel.svg', '', ['class' => 'delDocument2', 'onClick' => 'delDocument(' . $data->id . ')']);
                                    } else {
                                        $del = '';
                                    }

                                    return
                                        '<div class="new-table">
											<div class="file_list">
												<div class="file-sort">
													' . CHtml::link($data->file->name, Yii::app()->createUrl("page/get_file_user", ["id" => $data->id, "render_page" => 'dealings_page'])) . '										
												</div>
											</div>
											<div class="del_icon">' . $del . '</div>' .
                                        '</div>';
                                }
                            ),
                        ))); ?>
                    <div id="fileBlock"></div>
                    <?
                    $fileSettings = Yii::app()->commonFunction->getFileSettings();

                    $this->widget('ext.EAjaxUpload.EAjaxUpload',
                        array(
                            'id' => 'uploadFile',
                            'config' => array(
                                'multiple' => true,
                                'action' => '/page/UploadUserFile?id=' . $user->id,
                                'allowedExtensions' => explode(',', str_replace(' ', '', $fileSettings['extFile'])),//array("jpg","jpeg","gif","exe","mov" and etc...
                                'sizeLimit' => $fileSettings['sizeFile'] * 1024 * 1024,// maximum file size in bytes
                                'dragDrop' => false,
                                'onComplete' => "js:function(id, fileName, responseJSON){ 
									addFileBlock(responseJSON);
						}",
                                'messages' => array(
                                    'typeError' => "Ошибка! Расширение файла {file} не поддерживается. Разрешенные типы файлов: {extensions}.",
                                    'sizeError' => "{file} максимальный размер файла {sizeLimit}.",
                                    //                  'minSizeError'=>"{file} is too small, minimum file size is {minSizeLimit}.",
                                    //                  'emptyError'=>"{file} is empty, please select files again without it.",
                                    //                  'onLeave'=>"The files are being uploaded, if you leave now the upload will be cancelled."
                                ),
                                //'showMessage'=>"js:function(message){ alert(message); }"
                            )
                        ));

                    ?>

                    <?php
                }
                ?>

            </div>
        </div>
    </div>

    <div class="box-gray111 width-static">
        <div class="edit_user_1anketa">
            <div class="title_name_2">Управление</div>
            <div class="popup__form_anketa">
                <div class="imgavatar">
                    <?php
                    if ($user->avatar) {
                        ?>
                        <img class="avatar" src="<? echo $user->avatar ?>">
                        <?
                    } else {
                        ?>
                        <?php
                        if ($user->roles[0]->name == 'director') {
                            echo CHtml::tag('img', ['src' => '/img/ava_adminisrtr.svg']);
                        } elseif ($user->roles[0]->name == 'admin') {
                            echo CHtml::tag('img', ['src' => '/img/ava_admin.svg']);
                        } else {
                            echo CHtml::tag('img', ['src' => '/img/employee.svg']);

                        }
                        ?>
                    <? } ?>
                </div>
                <div class="profile_info_block_usser clear_fix">
                    <?php
                    echo CHtml::button('Изменить', array('onClick' => 'window.location.href= "' . Yii::app()->createUrl("page/edit_user", array("id" => $user->id)) . '"',
                        'class' => 'foton_btn'));
                    ?>
                </div>
            </div>
        </div>
    </div>

</main><!--.content-->
<!--.container-->
<script>
    if ($("#button-table-actions").hasClass('active')) {
        $("#table-actions").show();
        $("#table-deals").hide();
        $("#table-clients").hide();
        $("#table-files").hide();
    } else if ($("#button-table-deals").hasClass('active')) {
        $("#table-deals").show();
        $("#table-actions").hide();
        $("#table-clients").hide();
        $("#table-files").hide();
    } else if ($("#button-table-clients").hasClass('active')) {
        $("#table-clients").show();
        $("#table-actions").hide();
        $("#table-deals").hide();
        $("#table-files").hide();
    } else if ($("#button-table-doc").hasClass('active')) {
        $("#table-clients").hide();
        $("#table-actions").hide();
        $("#table-deals").hide();
        $("#table-files").show();
    }

    function selectTable(table) {
        switch (table) {
            case 'clients':
                $("#button-table-clients").addClass('active');
                $("#button-table-actions").removeClass('active');
                $("#button-table-deals").removeClass('active');
                $("#button-table-files").removeClass('active');
                $("#table-clients").show();
                $("#table-actions").hide();
                $("#table-deals").hide();
                $("#table-files").hide();
                break;
            case 'actions':
                $("#button-table-clients").removeClass('active');
                $("#button-table-actions").addClass('active');
                $("#button-table-deals").removeClass('active');
                $("#button-table-files").removeClass('active');
                $("#table-clients").hide();
                $("#table-actions").show();
                $("#table-deals").hide();
                $("#table-files").hide();
                break;
            case 'deals':
                $("#button-table-clients").removeClass('active');
                $("#button-table-actions").removeClass('active');
                $("#button-table-deals").addClass('active');
                $("#button-table-files").removeClass('active');
                $("#table-clients").hide();
                $("#table-actions").hide();
                $("#table-deals").show();
                $("#table-files").hide();
                break;
            case 'files':
                $("#button-table-clients").removeClass('active');
                $("#button-table-actions").removeClass('active');
                $("#button-table-deals").removeClass('active');
                $("#button-table-files").addClass('active');
                $("#table-clients").hide();
                $("#table-actions").hide();
                $("#table-deals").hide();
                $("#table-files").show();
                break;
        }
    }

    function delDocument(id) {
        if (confirm('Вы дествительно хотите удалить файл?')) {
            document.location.href = '/page/user_document_delete/' + id;
        }
    }


    function addFileBlock(json) {
        <?
        if ($callUserRight['role'] == 'admin' || $callUserRight['delete_files_client']) {
        ?>
        $("#fileBlock").append(
            '<a target="_blank" class="file_list_new" href="/page/get_file_user/' + json.fileId + '">' + json.filename + '</a>' +
            '<img class="delDocument3" onclick="delDocument(' + json.fileId + ')" src="/img/cancel_newdoc.svg" alt="">' +
            '<br>');
        <?
        } else { ?>
        $("#fileBlock").append(
            '<a target="_blank" class="file_list_new" href="/page/get_file_user/' + json.fileId + '">' + json.filename + '</a>' +
            '<br>');
        <?
        }
        ?>
        $("li.qq-upload-success").remove();
    }

    clickLabel = function (labelId, type) {
        var userId = <?echo $user->id?>;
        document.location.href = '/page/user_profile?id=' + userId + '&label' + type + 'Id=' + labelId;
    };
</script>
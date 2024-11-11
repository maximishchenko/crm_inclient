<?php $this->pageTitle = 'Сделки'; ?>
<div class="clients-hat">
    <div class="goback-link pull-right">
        <nav class="clients-nav navbar">
            <ul class="nav navbar-nav">
                <li <?php echo $dealTypeFilter == '0' ? 'class="active"' : '' ?> ><?php echo CHtml::link('Все', Yii::app()->createUrl("page/dealings_page", array("dealTypeFilter" => '0'))) . '<span class="">' . $countTypeAll . '</span>'; ?></li>
                <li <?php echo $dealTypeFilter == '1' ? 'class="active"' : '' ?> ><?php echo CHtml::link('Активно', Yii::app()->createUrl("page/dealings_page", array("dealTypeFilter" => '1'))) . '<span class="">' . $countTypeActive . '</span>'; ?></li>
                <li <?php echo $dealTypeFilter == '2' ? 'class="active"' : '' ?> ><?php echo CHtml::link('Выиграно', Yii::app()->createUrl("page/dealings_page", array("dealTypeFilter" => '2'))) . '<span class="">' . $countTypeWin . '</span>'; ?></li>
                <li <?php echo $dealTypeFilter == '3' ? 'class="active"' : '' ?> ><?php echo CHtml::link('Проиграно', Yii::app()->createUrl("page/dealings_page", array("dealTypeFilter" => '3'))) . '<span class="">' . $countTypeLose . '</span>'; ?></li>
            </ul>
        </nav>
    </div>
    <div class="client-name">
        Сумма сделок: <? echo $sumPaid . '. Остаток: ' . $sumBalance . '.' ?>
    </div>
</div>


<main class="content full2" role="main">
    <?php $this->renderPartial('_dealings_search', array(
        'deals' => $deals,
        'user' => $user,
        'allLabels' => $allLabels,
        'listStep' => $listStep,
        'listStepOption' => $listStepOption,
        'selectedSteps' => $selectedSteps,
        'customSelectedLabels' => $customSelectedLabels,
    )); ?>

    <div class="box-gray">
        <div class="box-gray__body no-border bottom_margin">
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
                            '       height: 12px;
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
                            $criteria = new CDbCriteria;
                            foreach ($data->labelsInDeals as $value) {
                                $criteria->addCondition('t.id = ' . $value->label_id, "OR");
                            }

                            $idHTML = '<div class="block_labels">' . '<span class="idHTML"> #' . $data->id . '</span>' . '<span class="werwe"></span>' . CHtml::link($data->client->name,
                                    Yii::app()->createUrl("page/client_profile", array("id" => $data->client->id)));
                            $labelHTML = '';
                            if ($criteria->condition != '' && $labels = Labels::model()->findAll($criteria)) {
                                foreach ($labels as $label) {
                                    $labelHTML .= '<div onclick="clickLabel(' . $label->id . ')" class="custom-label pointer" style="background-color: ' . $label->color . '; color:' . $label->color_text . '">' . $label->name . '</div>';
                                }
                            }
                            $labelHTML .= '</div>';

                            $stepOptionColor = '';
                            $stepOptionName = '';
                            $stepName = '';
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

                            $dealTypeClass = [
                                1 => 'dealTypeActiveSquare',
                                2 => 'dealTypeWinSquare',
                                3 => 'dealTypeLoseSquare',
                            ];


                            $dddd2 = '<span class="new-table-date_actions flex-start">' .
                                '<span class="tooltip"> ' .
                                '<span class="stepIndication" style="background-color:' . $stepOptionColor . '">' . '</span>' . '<span style="padding-left: 13px;">' . $stepOptionName . '</span>' . '<span class="tooltiptext tooltip-bottom">' . $stepName . '</span>' . '</span>' .
                                '</span>';

                            return
                                '<div class="new-table">
                                                    <div class="new-table-left">
                                                        <div class="new-table-header-01">
                                                            <div class="new-table-name-client">' . '<div class="' . $dealTypeClass[$data->deal_type_id] . '"></div>' . CHtml::link($data->text, Yii::app()->createUrl("page/edit_deal", array("id" => $data->id, "render_page" => 'dealings_page'))) . (count($data->dealsFiles) > 0 ? '<a class="file_add" tabindex="1"><img src="/img/paper-clip.svg"></a>' : '') . '<span class="new-table-name-resp left_10">' . round($data->paid) . ' / ' . round($data->balance) . '</span>' . '<span class="sdf2">' . ($data->responsable->avatar ? CHtml::image($data->responsable->avatar, '', ['class' => 'miniAvatar']) : CHtml::image($data->responsable->roles[0]->name == 'manager' ? '/img/employee.svg' : ($data->responsable->roles[0]->name == 'director' ? '/img/ava_adminisrtr.svg' : '/img/ava_admin.svg'), '', ['class' => 'miniAvatar'])) . CHtml::link($data->responsable->first_name, Yii::app()->createUrl("page/user_profile", array("id" => $data->responsable->id))) .
                                '</div>
														<div class="new-table-name-resp"></div>
                                                    </div>
                                                    <div class="new-table-bottom">' . $idHTML . $labelHTML . $dddd2 .
                                '</div>
												</div>';
                        }
                    ),
                )));
            ?>
        </div>
    </div>
</main><!--.content-->
<script>
    $("table").removeClass("items");
    $("table").addClass("main-table");

    clickLabel = function (id) {
        document.location.href = '/page/dealings_page?labelId=' + id;
    };

</script>

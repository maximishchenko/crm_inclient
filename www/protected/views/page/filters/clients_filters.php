<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableAjaxValidation' => false,
    'method' => 'get',
));
$clientsFilters = ClientFilters::model()->findAll('author=:A', [":A" => Yii::app()->user->id]);

function getCountClients($filter) {
    $modelClient = new Clients();
    $modelClientFiltersStepOptions = ClientFiltersStepOptions::model()->findAll('client_filters_id=:ID', [':ID' => $filter->id]);
    $modelClientFiltersLabels = ClientFiltersLabels::model()->findAll('client_filters_id=:ID', [':ID' => $filter->id]);
    $modelClientFiltersResponsibles = ClientFiltersResponsibles::model()->findAll('client_filters_id=:ID', [':ID' => $filter->id]);

    $labelIds = array_column($modelClientFiltersLabels, 'labels_id');
    $responsibleIds = array_column($modelClientFiltersResponsibles, 'users_id');
    $stepOptionsIds = [];
    foreach ($modelClientFiltersStepOptions as $value) {
        $stepOptionsIds [] = $value->steps_options_id;
    }
    $isFiles = $filter->is_files;
    $pageSize = $filter->page_size;

    return count($modelClient->searchForFilter(false, $isFiles, $labelIds, $responsibleIds, $stepOptionsIds, $pageSize, null));
}


function sortCount($arg1, $arg2)
{
    return ($arg1['count'] < $arg2['count']);
}

$filters = [];

foreach ($clientsFilters as $value) {
    $filters[$value->id]['filter'] = $value;
    $filters[$value->id]['count'] = getCountClients($value);
}

uasort($filters, 'sortCount');

$isChangeFilter = false;

foreach ($filters as $key => $value) {
    if ($value['filter']->is_default) {
        $filterTarget = ['count' => $value['count'], 'filter' => $value['filter']];
        unset($filters[$key]);
        $isChangeFilter = true;
    }
}

if ($isChangeFilter) {
    array_unshift($filters, $filterTarget);
}
?>

<div class="client-filter-block filter-block-edit">
    <div class="client-filter-header filter-header-edit">
        <div class="client-search-input">
            <span>Фильтры контактов</span>
        </div>
    </div>

    <div class="client-filter-content">
        <? foreach ($filters as $value) { ?>
            <ul class="filter-list <? echo $value['filter']->class_name ?>">
                <? if ($value['filter']['id'] != 1) { ?>
                    <a class="filter-item-link"
                       href="./clients_filters_edit?filterId=<? echo $value['filter']['id'] ?>">
                        <li id="item_<? echo $value['filter']['id'] ?>"
                            class="filter-static-color <? echo isset($selectedFilter) && $value['filter']['id'] === $selectedFilter->id ? 'filter-active-item' : '' ?>">
                            <span class="filter-name"><? echo $value['filter']['name'] ?></span>
                            <span class="filter-use-count"><? echo $value['count'] ?></span>
                        </li>
                    </a>
                <? } ?>
            </ul>
        <? } ?>
    </div>

    <? if ($isShowAddFilter) {?>
        <div class="client-filter-footer">
            <?php echo CHtml::link('Новый фильтр', 'clients_filters_add', ['class' => 'filter-add']); ?>
        </div>
    <?}?>
</div>

<?php $this->endWidget(); ?>
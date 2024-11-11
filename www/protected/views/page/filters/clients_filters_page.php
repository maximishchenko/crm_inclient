<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableAjaxValidation' => false,
    'method' => 'get',
    'id' => 'filtersPageForm',
));
$clientsFilters = ClientFilters::model()->findAll();
$user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
$role = $user->roles[0]->name;

function getCountClients($filter, $selectedFilter = null, $keyword) {
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

    return count($modelClient->searchForFilter(false, $isFiles, $labelIds, $responsibleIds, $stepOptionsIds, $pageSize, $selectedFilter && $selectedFilter->id == $filter->id ? $keyword : null));
}

function sortCount($arg1, $arg2) {
    return ($arg1['count'] < $arg2['count']);
}

$filters = [];
$count = 0;

$myFiltersCount = 0;
$usersFiltersCount = 0;
foreach ($clientsFilters as $value) {
    $filters[$value->id]['filter'] = $value;
    $filters[$value->id]['count'] = getCountClients($value, $selectedFilter, $keyword);

    if ($value->id != 1) {
        if ($value->author == Yii::app()->user->id ) {
            $myFiltersCount++;
        } else {
            $author = Users::model()->with('roles')->findByPk($value->author);
            if ($author->roles[0]->name !== 'manager' && Clients::isAccessVisible($value->who_visible, $role, $value->author)) {
                $usersFiltersCount++;
            }
        }

    }
}

uasort($filters, 'sortCount');

foreach ($filters as $key => $value) {
    if ($value['filter']->author == Yii::app()->user->id && $value['filter']->is_default) {
        $filterTarget = ['count' => $value['count'], 'filter' => $value['filter']];
        unset($filters[$key]);
    }
}

if (isset($filterTarget) && $filters && count($filters) > 0) {
    array_unshift($filters, $filterTarget);
}
?>

<div class="client-filter-block">
    <div class="client-filter-header">
        <div class="client-search-input">
            <?
                echo CHtml::textField('keyword', $keyword, array('type' => 'text', 'class' => 'form-control', 'placeholder' => 'Поиск'));
            ?>
                <img src="/img/search_icon.svg" onclick="search()" class="search-img pointer">
        </div>
    </div>

    <? if (count($filters)) { ?>
        <div class="client-filter-content">
            <? foreach ($filters as $value) {
                if ($value['filter']['id'] == 1) { ?>
                    <ul class="filter-list <? echo $value['filter']->class_name ? $value['filter']->class_name : array_keys($filterColors)[0] ?>">
                        <a class="filter-item-link" href="./clients_page?filterId=<? echo $value['filter']['id'] ?>">
                            <li id="item_<? echo $value['filter']['id'] ?>"
                                class="filter-static-color <? echo isset($selectedFilter) && $value['filter']['id'] === $selectedFilter->id ? 'filter-active-item' : '' ?>">
                                <span class="filter-name"><? echo $value['filter']['name'] ?></span>
                                <span class="filter-use-count"><? echo $value['count'] ?></span>
                            </li>
                        </a>
                    </ul>
                <?
                }
            } ?>

            <? if ($myFiltersCount) { ?>
                <? foreach ($filters as $value) { ?>
                    <ul class="filter-list <? echo $value['filter']->class_name ? $value['filter']->class_name : array_keys($filterColors)[0] ?>">
                        <? if ($value['filter']['author'] == Yii::app()->user->id && $value['filter']['id'] != 1) { ?>
                            <a class="filter-item-link"
                               href="./clients_page?filterId=<? echo $value['filter']['id'] ?>">
                                <li id="item_<? echo $value['filter']['id'] ?>"
                                    class="filter-static-color <? echo isset($selectedFilter) && $value['filter']['id'] === $selectedFilter->id ? 'filter-active-item' : '' ?>">
                                    <span class="filter-name"><? echo $value['filter']['name'] ?></span>
                                    <span class="filter-use-count"><? echo $value['count'] ?></span>
                                </li>
                            </a>
                        <? } ?>
                    </ul>
                <? } ?>
            <? } ?>

            <? if ($usersFiltersCount) { ?>
                <span class="filter-header-text">Общие фильтры </span>
                <? foreach ($filters as $value) { ?>
                    <ul class="filter-list <? echo $value['filter']->class_name ? $value['filter']->class_name : array_keys($filterColors)[0] ?>">
                        <? $author = Users::model()->with('roles')->findByPk($value['filter']['author']);
                        $authorRole = $author->roles[0]->name;
                        if ($value['filter']['id'] != 1 && $value['filter']['author'] != Yii::app()->user->id && $authorRole !== 'manager' && Clients::isAccessVisible($value['filter']['who_visible'], $role, $value['filter']['author'])) { ?>
                            <a class="filter-item-link"
                               href="./clients_page?filterId=<? echo $value['filter']['id'] ?>">
                                <li id="item_<? echo $value['filter']['id'] ?>"
                                    class="filter-static-color <? echo isset($selectedFilter) && $value['filter']['id'] === $selectedFilter->id ? 'filter-active-item' : '' ?>">
                                    <span class="filter-name"><? echo $value['filter']['name'] ?></span>
                                    <span class="filter-use-count"><? echo $value['count'] ?></span>
                                </li>
                            </a>
                        <? } ?>
                    </ul>
                <? } ?>
            <? } ?>
        </div>
    <?} else {?>
        <span>Фильтров нет</span>
    <?}?>
    <div class="client-filter-footer">
        <?php
        if (isset($selectedFilter) && Yii::app()->user->id === $selectedFilter->author && $selectedFilter->id != 1) {?>
            <a class="filter-change" href="./clients_filters_edit?filterId=<?echo $selectedFilter->id?>">Изменить</a>
        <?}?>
        <?php echo CHtml::link('Новый фильтр', 'clients_filters_add', ['class' => 'filter-add']); ?>
    </div>
</div>

<?php $this->endWidget(); ?>

<script type="module">
    window.search = function () {
        const filtersPageFormNode = document.getElementById('filtersPageForm');
        filtersPageFormNode.submit();
    };
</script>

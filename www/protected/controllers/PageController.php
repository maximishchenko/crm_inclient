<?php

/**
 * \brief Контроллер основных страниц сайта (Главная, Регистрация, Справка, Ошибка)
 */
// Подключаем библиотеку
Yii::import( 'ext.PHPExcel.Classes.PHPExcel' );
$objPHPExcel = new PHPExcel();
class PageController extends MyCController
{
    public $colors = [
        '#5883A2',
        '#292929',
        '#49AD8E',
        '#A9D697',
        '#60B860',
        '#064E07',
        '#34495D',
        '#7F8C8D',
        '#A295D4',
        '#5844AB',

        '#000080',
        '#0000FF',
        '#008080',
        '#00FFFF',
        '#008000',
        '#00FF00',
        '#808000',
        '#FFFF00',
        '#800000',
        '#FF0000',

        '#8D44AB',
        '#A0522D',
		'#C0C0C0',
		'#CE0069',
		'#C90036',
		'#FF00FF',
		'#D75152',
		'#D1D029',
		'#0536C9',
		'#AC8C48',

		'#459CB6',
		'#F05E67',
		'#FFF347',
		'#118512',
		'#E466AE',
		'#EEAC56',
		'#008996',
		'#bbd1e0',
		'#71e4bf',
		'#b1fbb2',

		'#d4d4d4',
		'#d1c6f9',
		'#ffb2d6',
		'#ffb3b3',
		'#70e9f5',
		'#f9f4b1',
		'#c2cafb',
		'#f1c7af',
		'#9e9e9e',
		'#a7d4ec',

		'#efd1ad',
		'#83e084',
		'#b5f4f9',
		'#458CC8',
		'#b9e641',
        '#f8dafb',
        '#98FB98',
        '#B0E0E6',
        '#eaeaea',
        '#EEE8AA',
    ];
    public $textColors = [
        'Белый' => '#ffffff',
        'Чёрный' => '#000000',
        'Жёлтый' => '#FFF256',
		'Красный' => '#F90023',
        'Синий' => '#0000FF',
        'Зеленый' => '#008000',
        'Серый' => '#808080',
        'Фиолетовый' => '#800080',
    ];

    public $timeZoneList = [
        'Atlantic/Reykjavik' => '(UTC+00:00) Рейкьявик, Исландия',
		'Europe/London' => '(UTC+01:00) Лондон, Великобритания',
		'Europe/Kaliningrad' => '(UTC+02:00) Калининград, Россия',
        'Europe/Moscow' => '(UTC+03:00) Москва, Россия',
        'Europe/Samara' => '(UTC+04:00) Самара, Россия',
        'Asia/Yekaterinburg' => '(UTC+05:00) Екатеринбург, Россия',
        'Asia/Novosibirsk' => '(UTC+06:00) Новосибирск, Россия',
        'Asia/Novokuznetsk' => '(UTC+07:00) Новокузнецк, Россия',
        'Asia/Irkutsk' => '(UTC+08:00) Иркутск, Россия',
        'Asia/Yakutsk' => '(UTC+09:00) Якутск, Россия',
        'Asia/Vladivostok' => '(UTC+10:00) Владивосток, Россия',
        'Asia/Sakhalin' => '(UTC+11:00) Сахалин, Россия',
        'Asia/Anadyr' => '(UTC+12:00) Анадырь, Россия',	
		'Europe/Kiev' => '(UTC+02:00) Киев, Украина',
		'Europe/Minsk' => '(UTC+03:00) Минск, Беларусь',
		'Europe/Warsaw' => '(UTC+01:00) Варшава, Польша',
		'Europe/Belgrade' => '(UTC+01:00) Белград, Сербия',
		'Europe/Istanbul' => '(UTC+03:00) Стамбул, Турция',
		'Asia/Hong_Kong' => '(UTC+08:00) Гонконг, Китай',		
		'Asia/Tbilisi' => '(UTC+04:00) Тбилиси, Грузия',
		'Asia/Aqtau' => '(UTC+05:00) Актау, Казахстан',
		'Asia/Baku' => '(UTC+04:00) Баку, Азербайджан',
		'Asia/Yerevan' => '(UTC+04:00) Ереван, Армения',
		'Asia/Bishkek' => '(UTC+06:00) Бишкек, Киргизия',
		'Asia/Tashkent' => '(UTC+05:00) Ташкент, Туркменистан',

    ];

    // цветы фильтров - название классы css => основной цвет
    public $filterColors = [
        'filter-color-inc' => '#5883A2',
        'filter-color-gold' => '#FFD700',
        'filter-color-red' => '#EF5359',
        'filter-color-green' => '#4B966A',
        'filter-color-sea' => '#2DBDF5',
        'filter-color-orange' => '#F38926',
        'filter-color-purple' => '#800080',
        'filter-color-aqua' => '#19A8A6',
        'filter-color-black' => '#000000',
        'filter-color-olive' => '#808000',
        'filter-color-deeppink' => '#FF1493',
        'filter-color-dimgray' => '#696969',
        'filter-color-blue' => '#0000FF',
        'filter-color-pigeon' => '#18486D',
    ];

	public function beforeAction($a)
	{
        parent::beforeAction($a);
        $action = $a->getId();
        $timeZone = Settings::model()->findByPk(7);
        Yii::app()->setTimeZone($timeZone->value);

        if (Yii::app()->user->isGuest && !in_array($action, array('login', 'registration', 'password_recovery',
                'activation', 'lost_success', 'registration_success', 'Pay_CRM'))) {
            $this->redirect(array('login'));
            return;
        }
        $user = Users::model()->findByPk(Yii::app()->user->id);
        if(!Yii::app()->user->isGuest && !$user && !in_array($action, array(
            'login','registration', 'password_recovery', 'activation', 'lost_success', 'registration_success', 'Pay_CRM'))){
            $this->redirect(array('login'));
            return;
        }
        if ($user) {
            if ($user->status == 'limited') {
                $access = false;
                if ($rangeIP = RangeIp::model()->findAll()) {
                    $ipUser = ip2long($_SERVER['REMOTE_ADDR']);
                    foreach ($rangeIP as $value) {
                        if ($ipUser >= $value->begin_ip && $ipUser <= $value->end_ip) {
                            $access = true;
                            break;
                        }
                    }
                } else {
                    $access = true;
                }
                if ($access) {
                    return true;
                } else {
                  $this->actionLogout();
                }
            }
        }
        return true;
	}

	/**
	 * \brief Возвращает правила для задач контроллера
	 */
	public function actions()
	{
		return array(
			'captcha' => array(
				'class' => 'CCaptchaAction',
				'backColor' => 0xFFFFFF,
				//'testLimit' => '2',
			),
		);
	}


    public function actionRegistration_success()
    {

        $this->render('registration_success');
    }

	public function actionError()
	{
        if (Yii::app()->user->isGuest) {
            $this->layout = '//layouts/login';
        }
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                switch ($error['code']) {
                    case '404':
                        $this->render('not_found_page');
                        break;
                    case '412':
                        switch ($error['message']) {
                            case 'no_access_client':
                                $this->render('no_access_client');
                                break;
                            case 'no_access_deal':
                                $this->render('no_access_deal');
                                break;
                            case 'no_access_action':
                                $this->render('no_access_action');
                                break;
                            case 'no_access_user':
                                $this->render('no_access_user');
                                break;
                            case 'no_access_settings':
                                $this->render('no_access_settings');
                                break;
                            case 'no_access_file':
                                $this->render('no_access_file');
                                break;
                            case 'no_access_new_action':
                                $this->render('no_access_new_action');
                                break;
                            case 'no_access_new_client':
                                $this->render('no_access_new_client');
                                break;
                            case 'no_access_edit_filter':
                                $this->render('no_access_edit_filter');
                                break;
                        }
                        break;
                }
            }
        }
	}

	/**
	 * \brief AJAX валидация
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax']) {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionLogin($login_type = 'login')
	{
        $this->layout = '//layouts/login';
		$model = new LoginForm;
		if (isset($_POST['LoginForm'])) {
			$model->attributes = $_POST['LoginForm'];

			if ($model->validate() && $model->login()) {
                $this->redirect(array('clients_page'));
            }
		}
        $appearance = Appearance::model()->find();
		$this->render('login',
            [
                'model' => $model,
                'login_type' => $login_type,
                'appearance' => $appearance
            ]);
	}

    public function actionIndex()
    {
        $this->redirect(array('clients_page'));
    }

    public function actionPay_CRM()
    {
        $this->render('pay_crm');
    }

    public function actionCreate()
    {
        $model=new User;
        $this->performAjaxValidation($model);
        if(isset($_POST['User']))
        {
            $model->attributes=$_POST['User'];
            if($model->save())
                $this->redirect('index');
        }
        $this->render('create',array('model'=>$model));
    }

    private function getSelectedFilterId() {
	    $filterDefaultId = 1;
        $clientsFilters = ClientFilters::model()->findAll();

        foreach ($clientsFilters as $value) {
            if ($value->author == Yii::app()->user->id && $value->is_default) {
                $filterDefaultId = $value->id;
                break;
            }
        }

        return $filterDefaultId;
    }

    public function actionClients_page($filterId = null, $labelId = null, $keyword = null, $isDeleteFilter = false)
    {
        $this->layout = '//layouts/main';
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);         
        $userRight = UserRight::model()->find(['condition' => 'user_id = :USER', 'params' =>[':USER' => Yii::app()->user->id]]); 
        
        $parent_user = Users::model()->with('roles')->findByPk($user->parent_id);
        $clients = new Clients();
        $actions = new Actions();
        $selectedSteps = new StepsInClients();

        $allLabels = Labels::model()->findAll( 'type_id = :ID', [':ID' => 1]);
        $customSelectedLabels = [];
        $labelsType = LabelsType::model()->find('name = :NAME', [':NAME' => 'actions']) ;
        $allEventLabels = Labels::model()->findAll('type_id = :ID', [':ID' => $labelsType->id]);
        if ($labelId) {
            if (!is_array($labelId)) {
                $labelId = [$labelId];
            }
            foreach ($allLabels as $label) {
                if (in_array($label->id, $labelId)) {
                    $customSelectedLabels [$label->id] = $label;
                }
            }
        }

        //этапы (1 - clients)
        $listStep = Steps::model()->findAll(['condition' => 'steps_type_id = :TYPE', 'order' => 'weight', 'params' => [':TYPE' => 1]]);
        array_unshift($listStep, (object) ['id' => 0, 'name' => 'Все воронки']);

        $listStepOption = [];
        foreach ($listStep as $steps) {
            if ($options = StepsOptions::model()->findAll(['condition' => 'steps_id = :ID', 'order' => 'weight', 'params' =>[':ID' => $steps->id]])) {
                $listStepOption[$steps->id] = $options;
            }
        }

        if (!$selectedSteps->steps_id) {
            $selectedSteps->steps_id = 0;
        }

        // фильтр
        $labelIds = null;
        $responsibleIds = null;
        $stepOptionsIds = null;
        $isFiles = false;
        $pageSize = null;

        if (!$filterId) {
            $filterId = $this->getSelectedFilterId();
        }

        $modelClientFilter = ClientFilters::model()->findByPk($filterId);

        if (!$modelClientFilter) {
            $filterId = $this->getSelectedFilterId();
            $modelClientFilter = ClientFilters::model()->findByPk($filterId);
        }

        if ($modelClientFilter) {
            if ($modelClientFilter->id != 1 && !Clients::isAccessVisible($modelClientFilter->who_visible, $user->roles[0]->name, $modelClientFilter->author)) {
                throw new CHttpException(404, 'Доступ к фильтру закрыт');
            }

            $modelClientFiltersBlockInfoLeft = ClientFiltersBlockInfo::model()->find('client_filters_id=:ID && client_filters_block_type_id=:TP', [':ID' => $modelClientFilter->id, ':TP' => 1]);
            $modelClientFiltersBlockInfoRight = ClientFiltersBlockInfo::model()->find('client_filters_id=:ID && client_filters_block_type_id=:TP', [':ID' => $modelClientFilter->id, ':TP' => 2]);
            $modelClientFiltersBlockAdditionalFieldsLeft = ClientFiltersBlockAdditionalFields::model()->with('additionalFields')->findAll('client_filters_id=:ID && client_filters_block_type_id=:TP', [':ID' => $modelClientFilter->id, ':TP' => 1]);
            $modelClientFiltersBlockAdditionalFieldsRight = ClientFiltersBlockAdditionalFields::model()->with('additionalFields')->findAll('client_filters_id=:ID && client_filters_block_type_id=:TP', [':ID' => $modelClientFilter->id, ':TP' => 2]);

            $modelClientFiltersStepOptions = ClientFiltersStepOptions::model()->findAll('client_filters_id=:ID', [':ID' => $modelClientFilter->id]);
            $modelClientFiltersLabels = ClientFiltersLabels::model()->findAll('client_filters_id=:ID', [':ID' => $modelClientFilter->id]);
            $modelClientFiltersResponsibles = ClientFiltersResponsibles::model()->findAll('client_filters_id=:ID', [':ID' => $modelClientFilter->id]);

            $labelIds = array_column($modelClientFiltersLabels, 'labels_id');
            $responsibleIds = array_column($modelClientFiltersResponsibles, 'users_id');

            foreach ($modelClientFiltersStepOptions as $value) {
                $stepOptionsIds [] = $value->steps_options_id;
            }

            $isFiles = $modelClientFilter->is_files;
            $pageSize = $modelClientFilter->page_size;
        } else {
            throw new CHttpException(404, 'Фильтр не найден');
        }

        $clientTableData = $clients->searchForFilter(true, $isFiles, $labelIds, $responsibleIds, $stepOptionsIds, $pageSize, $keyword);

        $additionalFiledValuesInClient = Yii::app()->commonFunction->getValueAdditionalFiledNewClient($user, false);
        

        $client = new Clients();
        $this->render('clients_page', [
                'user' => $user,
                'parent_user' => $parent_user,
                'client' => $client,
                'clients' => $clients,
                'actions' => $actions,
                'clientTableData' => $clientTableData,
                'additionalFiledValuesInClient' => $additionalFiledValuesInClient,
                'userRight' => $userRight,
                'selectedFilter' => isset($modelClientFilter) ? $modelClientFilter : null,
                'listStep' => $listStep,
                'listStepOption' => $listStepOption,
                'selectedSteps' => $selectedSteps,
                'allLabels' => $allLabels,
                'allEventLabels' => $allEventLabels,
                'customSelectedLabels' => $customSelectedLabels,
                'isDeleteFilter' => $isDeleteFilter == 'true',
                'keyword' => $keyword,
                'filterColors' => $this->filterColors,
                'modelClientFiltersBlockInfoLeft' => isset($modelClientFiltersBlockInfoLeft) ? $modelClientFiltersBlockInfoLeft : null,
                'modelClientFiltersBlockInfoRight' => isset($modelClientFiltersBlockInfoRight) ? $modelClientFiltersBlockInfoRight : null,
                'modelClientFiltersBlockAdditionalFieldsLeft' => isset($modelClientFiltersBlockAdditionalFieldsLeft) ? $modelClientFiltersBlockAdditionalFieldsLeft : null,
                'modelClientFiltersBlockAdditionalFieldsRight' => isset($modelClientFiltersBlockAdditionalFieldsRight) ? $modelClientFiltersBlockAdditionalFieldsRight : null,
            ]
        );
    }

    public function actionGetJSStyle($for_edit_id = null, $form_attributes = null, $date_time_picker = null)
    {
        Yii::app()->clientScript->registerScriptFile('/assets/c0e20273/jquery-ui-timepicker-addon.js', CClientScript::POS_END);
        $style = '
<script>
var $deleteRow = $(".function-delete > a"),
			$deleteRowConfirm = $(".function-delete-confirm a");
	$deleteRow.on("click touchstart", function() {
		var $this = $(this),
				$delete = $this.closest(".function-delete"),
				$confirm = $delete.next(".function-delete-confirm");

		$delete.css("display", "none");
		$confirm.css("display", "block");
		return false
	});

	$deleteRowConfirm.on("click touchstart", function(e) {
		var $clicked = $(e.target),
				$this = $(this),
				$confirm = $this.closest(".function-delete-confirm"),
				$delete = $confirm.prev(".function-delete"),
				deleteRow = $this.closest(".popup").attr("rel");

		if(!$clicked.is(".btn")) {
		    $delete.css("display", "block");
			$confirm.css("display", "none");
		}
		return false
	});

	var $helpDropdown = $(".help-dropdown"),
			$helpDropdownDt = $helpDropdown.find(".dt2"),
			$helpDropdownDd = $helpDropdown.find(".dd2");

	if($helpDropdown.is(".open")){
		$helpDropdownDd.stop(true,true).slideDown(300);
	}
	//Open, Close on click touch
	$helpDropdownDt.on("click touchstart", function() {
		var $this = $(this),
				$helpDropdown = $this.closest(".help-dropdown"),
				$helpDropdownDd = $helpDropdown.find(".dd2");
		$this.toggleClass("open");
		$helpDropdownDd.stop(true,true).slideToggle(300);
	});
  //  $("input, select").styler();

    //Валидация редактируемых форм
    
    jQuery(function ($) {
        jQuery("#' . $for_edit_id . '").yiiactiveform({
            "validateOnSubmit": true,
            "attributes": ' . $form_attributes . ',
            "errorCss": "error"
        });
    });</script>';

        $style .= $date_time_picker != null ? '


    <script>
    jQuery("#' . $date_time_picker . '").datetimepicker(jQuery.extend({showMonthAfterYear: false}, jQuery.datepicker.regional["ru"], {
    "dateFormat": "dd.mm.yy ",
    "changeMonth": "true",
    "changeYear": "true",
    "timepicker": "true",
    "showButtonPanel": true,
    "beforeShow":function(element){dataPickerFocus = $(element).attr("id").trim();}
}));</script>' : '';

        return $style;
    }

    public function actionDelete_action($id, $render_page = null)
    {
        if ($actions = Actions::model()->findByPk($id)) {
            if (UsersRoles::model()->find('user_id = ' . Yii::app()->user->id)->itemname == 'admin' ||
                UserRight::model()->find('user_id = ' . Yii::app()->user->id)->delete_action == 1
            ) {
                $actions->delete();
                $this->redirect(array('client_profile', 'id' => $actions->client_id));
                /*
                $render_page = explode(';', $render_page);
                if ($render_page[0] == 'actions_page') {
                    $this->redirect(array('actions_page'));
                } else {
                    $this->redirect(array('client_profile', 'id' => $actions->client_id));
                }*/
            }
        }
    }

    public function actionDelete_deal($id, $render_page = null)
    {
        if ($deals = Deals::model()->findByPk($id)) {
            if (UsersRoles::model()->find('user_id = ' . Yii::app()->user->id)->itemname == 'admin' ||
                UserRight::model()->find('user_id = ' . Yii::app()->user->id)->delete_deals == 1
            ) {
                $deals->delete();
                $this->redirect(array('client_profile', 'id' => $deals->client_id));
                /*if ($render_page == 'dealings_page') {
                    $this->redirect(array('dealings_page'));
                } else {
                    $this->redirect(array('client_profile', 'id' => $deals->client_id));
                }*/
            }
        }
    }

    public function actionEdit_deal($id, $render_page = null, $isSuccessSave = null)
    {

        if (!$deal = Deals::model()->with('client')->findByPk($id)) {
            throw new CHttpException(404, 'Сделка не найдена');
        }
        $resultTransaction = false;
        $changeUpdateDate = false;
        $errorMessage = '';
        $transaction = Yii::app()->db->beginTransaction();
        $deal_resp_role = UsersRoles::model()->find('user_id=' . $deal->responsable_id);
        $client = $deal->client;
        $correct_path = 'http://' . $_SERVER["HTTP_HOST"];
        $user = Users::model()->findByPk(Yii::app()->user->id);
        $reasons = DealsReason::model()->findAll(['order'=>'t.weight']);
        $dealType = DealsType::model()->findAll();
        $dealTypeList = [];
        $justPushSaveBtn = false;
        $residue = (float)$deal->balance ? '. Остаток: ' . (float)$deal->balance : '';
        $messageSaveType = '';

        foreach ($dealType as $value) {
            $dealTypeList [$value->id]['name'] = $value->name;
            if ($value->id == 3) {
                $dealAndReasonModel = DealAndReason::model()->find('deals_id = :ID', [':ID' => $deal->id]);
                $dealTypeList[$value->id]['reason'] = $dealAndReasonModel ? $dealAndReasonModel->dealsReason->name : 'Проиграно';
            }
        }

        switch ($deal->deal_type_id) {
            case 2: {
                $messageSaveType = '<strong>Сделка выиграна</strong><br> Сделка успешно закрыта на сумму: ' . (float)$deal->paid . $residue . '. Срок: ' . Yii::app()->commonFunction->getDateWithString($deal->creation_date, $deal->closed_date);
                break;
            }
            case 3: {
                $messageSaveType = '<strong>Сделка проиграна</strong><br> Сделка закрыта без результата на сумму: ' . (float)$deal->paid . $residue . '. Причина: ' . $dealAndReasonModel->dealsReason->name;
                break;
            }
        }

        // метки
        if ($labelsType = LabelsType::model()->find('name = :NAME', [':NAME' => 'deals'])) {
            $allLabels = Labels::model()->findAll('type_id = :ID', [':ID' => $labelsType->id]);
            $selectedLabels = LabelsInDeals::model()->with('label')->findAll('deal_id = :ID', [':ID' => $deal->id]);
            if (isset($_POST['MainDeals']['Labels']) ?? $_POST['MainDeals']['Labels']) {
                foreach ($allLabels as $label) {
                    if ($_POST['MainDeals']['Labels'][$label->id]
                        && $_POST['MainDeals']['Labels'][$label->id] == '1') {
                        $customSelectedLabels [$label->id] = $label;
                    }
                }

                // проверка на изменение меток
                $enableLableList = [];
                $postLabelList = $_POST['MainDeals']['Labels'];
                foreach ($postLabelList as $key => $enable) {
                    if ($enable == 1) {
                        $enableLableList [] = $key;
                    }
                }
                if ($selectedLabels && !$enableLableList || !$selectedLabels && $enableLableList) {
                    $changeUpdateDate = true;
                } else {
                    if ($selectedLabels && $enableLableList) {
                        if (count($selectedLabels) == count($enableLableList)) {
                            foreach ($selectedLabels as $value) {
                                if (!in_array($value->label_id, $enableLableList)) {
                                    $changeUpdateDate = true;
                                    unset($value);
                                    break;
                                }
                            }
                        } else {
                            $changeUpdateDate = true;
                        }
                    }
                }

            } else {
                foreach ($selectedLabels as $label) {
                    $customSelectedLabels [$label->label->id] = $label->label;
                }
            }
        }

        //этапы (1 - clients)
        $listStep = Steps::model()->findAll(['condition' => 'steps_type_id = :TYPE', 'order' => 'weight', 'params' => [':TYPE' => 2]]);
        if (!$selectedSteps = StepsInDeals::model()->find('deals_id = :ID', [':ID' => $deal->id])) {
            $selectedSteps = new StepsInDeals();
        }

        if (isset($_POST['MainStepsInDeals']) && isset($_POST['MainStepsInDeals']['steps_id'])) {
            $selectedSteps->deals_id = $deal->id;
            $selectedSteps->steps_id = $_POST['MainStepsInDeals']['steps_id'];
            $oldOptionId = $selectedSteps->selected_option_id;
            $selectedSteps->selected_option_id = $selectedSteps->steps_id != 2 ? $_POST['MainStepsInDeals']['selected_option_id'] : 0;

            if ($oldOptionId != $selectedSteps->selected_option_id) {
                $changeUpdateDate = true;
            }
        }

        $listStepOption = [];
        foreach ($listStep as $steps) {
            if ($options = StepsOptions::model()->findAll(['condition' => 'steps_id = :ID', 'order' => 'weight', 'params' =>[':ID' => $steps->id]])) {
                $listStepOption[$steps->id] = $options;
            }

            if (!$selectedSteps->steps_id && $steps->selected_default) {
                $selectedSteps->steps_id = $steps->id;
                $selectedSteps->selected_option_id = $options[0]->id;
            }
        }

        if (Deals::checkAccess($deal, $user)) {
            $dealFiles = DealsFiles::model()->findAll('deal_id = :deal', [':deal' => $id]);
            $accessClient = Clients::checkAccess($deal->client, $user);
            $userRight = Yii::app()->commonFunction->getUserRight($user);
            if (isset($_POST['MainDeals'])) {
                if ($user->roles[0]->name == 'admin' || $userRight['create_deals'] == 1) {
                    $oldDeal = clone $deal;
                    $deal->attributes = $_POST['MainDeals'];
                    if (isset($_POST['type'])) {
                        if ($_POST['type'] == 'i') {
                            $deal->responsable_id = Yii::app()->user->id;
                        } elseif ($_POST['type'] == 'manager_edit_deal') {
                            $deal->responsable_id = $_POST['MainDeals']['manager_id'];
                        } elseif ($_POST['type'] == 'director_edit_deal') {
                            $deal->responsable_id = $_POST['MainDeals']['director_id'];
                        } elseif ($_POST['type'] == 'no') {
                            $admin = new Users();
                            $admin_id = $admin->getAdminId();
                            $deal->responsable_id = $admin_id;
                        }
                    }

                    if ($oldDeal->responsable_id != $deal->responsable_id
                        || $oldDeal->text != $deal->text
                        || $oldDeal->paid != $deal->paid
                        || $oldDeal->balance != $deal->balance
                        || $oldDeal->description != $deal->description
                    ) {
                        $changeUpdateDate = true;
                    }

                    if (isset($_POST['ajax']) && $_POST['ajax'] == 'edit-deal') {
                        echo CActiveForm::validate($deal);
                        Yii::app()->end();
                    }

                    if ($oldDeal->deal_type_id == 1) {
                        $deal->balance = is_numeric($deal->balance) ? $deal->balance : 0;
                        $deal->paid = is_numeric($deal->paid) ? $deal->paid : 0;
                    } else {
                        $deal->balance = $oldDeal->balance;
                        $deal->paid = $oldDeal->paid;
                    }

                    // работа с типами сделки
                    if (isset($_POST['MainDeals']['deal_type_id']) && $_POST['MainDeals']['deal_type_id']) {
                        $justPushSaveBtn = isset($_POST['yt1']) && $_POST['yt1'] == 'Сохранить';
                        $dealTypeId = $_POST['MainDeals']['deal_type_id'];
                        $residue = (float)$deal->balance ? '. Остаток: ' . (float)$deal->balance : '';
                        switch ($dealTypeId) {
                            case '1':
                                {
                                    //active
                                    $deal->deal_type_id = $dealTypeId;
                                    if ($dealAndReason = DealAndReason::model()->find('deals_id = :ID', [':ID' => $deal->id])) {
                                        $dealAndReason->delete();
                                    }
                                    $dealTypeList[3]['reason'] = $dealTypeList[3]['name'];
                                    $messageSaveType = $justPushSaveBtn ? '' : '<strong>Сделка снова в работе.</strong><br> Сделка восстановлена на сумму: ' . (float)$deal->paid . $residue . '. Контакт: ';
                                    break;
                                }
                            case '2':
                                {
                                    // win
                                    $deal->deal_type_id = $dealTypeId;
                                    if ($dealAndReason = DealAndReason::model()->find('deals_id = :ID', [':ID' =>  $deal->id])) {
                                        $dealAndReason->delete();
                                    }
                                    $dealTypeList[3]['reason'] = $dealTypeList[3]['name'];
                                    $deal->closed_date = date('Y-m-d H:i');
                                    $messageSaveType = '<strong>Сделка выиграна</strong><br> Сделка успешно закрыта на сумму: ' . (float)$deal->paid . $residue . '. Срок: ' . Yii::app()->commonFunction->getDateWithString($deal->creation_date, $deal->closed_date);
                                    break;
                                }
                            case '3':
                                {
                                    //lose
                                    $deal->deal_type_id = $dealTypeId;
                                    if (isset($_POST['DealsReason']['id']) && $_POST['DealsReason']['id']) {

                                        if ($reason = DealsReason::model()->findByPk($_POST['DealsReason']['id'])) {
                                            if ($dealAndReason = DealAndReason::model()->find('deals_id = :ID', [':ID' =>  $deal->id])) {
                                                $dealAndReason->deals_reason_id = $reason->id;
                                                $dealAndReason->save();
                                            } else {
                                                $dealAndReason = new DealAndReason();
                                                $dealAndReason->deals_id = $deal->id;
                                                $dealAndReason->deals_reason_id = $reason->id;
                                                $dealAndReason->save();
                                            }
                                            $dealTypeList[3]['reason'] = $dealAndReason->dealsReason->name;
                                            $deal->closed_date = date('Y-m-d H:i');
                                            $messageSaveType = '<strong>Сделка проиграна</strong><br> Сделка закрыта без результата на сумму: ' . (float)$deal->paid . $residue . '. Причина: '. $dealAndReason->dealsReason->name;
                                        } else {
                                            $errorMessage = 'Причина с таким id не существует в базе данных';
                                        }
                                    }
                                    break;
                                }
                        }
                    }


                    if ($resultTransaction = $deal->save() && !$errorMessage) {
                        if (isset($_POST['MainDeals']['Labels'])) {
                            $labelsOper = $_POST['MainDeals']['Labels'];
                            foreach ($labelsOper as $id => $oper) {
                                if ($oper == 1) {
                                    $addLabel = new LabelsInDeals();
                                    $addLabel->deal_id = $deal->id;
                                    $addLabel->label_id = $id;
                                    $resultTransaction = true;
                                    if ($addLabel->checkDuplicate($id, $deal->id)) {
                                        if (!$addLabel->save()) {
                                            $resultTransaction = false;
                                            break;
                                        }
                                    }
                                } elseif ($oper == 0) {
                                    LabelsInDeals::model()->deleteAll('deal_id = :CID && label_id = :LID',
                                        [
                                            ':CID' => $deal->id,
                                            ':LID' => $id
                                        ]
                                    );
                                }
                            }
                        }

                        if (isset($_POST['MainStepsInDeals'])) {
                            if (!$selectedSteps->save()) {
                                $resultTransaction = false;
                            }
                        }
                    }

                    if($resultTransaction) {
                        $transaction->commit();
                        if ($changeUpdateDate) {
                            $deal->change_date = date('Y-m-d H:i:s');
                            $deal->save();
                        }
                        $deal_resp_role = UsersRoles::model()->find('user_id=' . $deal->responsable_id);
                        if (array_key_exists('save_and_create', $_POST)) {
                            $this->redirect(array('new_deal', 'id' => $deal->client_id));
                        } else {
                            $isShowBlockSave = true;
                        }
                    } else {
                        $transaction->rollback();
                        $messageSaveType = '';
                    }
                }
            }
        } else {
            throw new CHttpException(412,'no_access_deal');
        }

        $this->render('edit_deal', array
        (
            'deal' => $deal,
            'client' => $client,
            'user' => $user,
            'userRight' => $userRight,
            'deal_resp_role' => $deal_resp_role,
            'correct_path' => $correct_path,
            'allLabels' => $allLabels ?? [],
            'customSelectedLabels' => $customSelectedLabels ?? [],
            'accessClient' => $accessClient,
            'dealFiles' => $dealFiles,
            'listStepOption' => $listStepOption,
            'listStep' => $listStep,
            'selectedSteps' => $selectedSteps,
            'isShowBlockSave' => $isShowBlockSave ?? false,
            'isSuccessSave' => $isSuccessSave,
            'reasons' => $reasons,
            'newReason' => new DealsReason(),
            'dealTypeList' => $dealTypeList,
            'errorMessage' => $errorMessage,
            'messageSaveType' => $messageSaveType,
            'justPushSaveBtn' => $justPushSaveBtn,
            ));
    }

    public function actionEdit_action($id, $render_page = null, $term = null, $isSuccessSave = null)
    {
        if (!$actions = Actions::model()->with('client.city')->findByPk($id)) {
            throw new CHttpException(404,'Задача не найдена');
        }
        $client = $actions->client;
        $resultTransaction = false;
        $transaction = Yii::app()->db->beginTransaction();

        $action_resp_role = UsersRoles::model()->find('user_id=' . $actions->responsable_id);
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $userRight = Yii::app()->commonFunction->getUserRight($user);

        // метки
        if ($labelsType = LabelsType::model()->find('name = :NAME', [':NAME' => 'actions'])) {
            $allLabels = Labels::model()->findAll('type_id = :ID', [':ID' => $labelsType->id]);
            $selectedLabels = LabelsInActions::model()->with('label')->findAll('action_id = :ID', [':ID' => $actions->id]);
            if (isset($_POST['MainActions']['Labels']) && $_POST['MainActions']['Labels']) {
                foreach ($allLabels as $label) {
                    if ($_POST['MainActions']['Labels'][$label->id]
                        && $_POST['MainActions']['Labels'][$label->id] == '1') {
                        $customSelectedLabels [$label->id] = $label;
                    }
                }
            } else {
                foreach ($selectedLabels as $label) {
                    $customSelectedLabels [$label->label->id] = $label->label;
                }
            }
        }

        if (Actions::checkAccess($actions, $user)) {
            $accessClient = Clients::checkAccess($actions->client, $user);
            $actionFiles = ActionsFiles::model()->findAll('action_id = :action', [':action' => $id]);
            if (isset($_POST['MainActions'])) {

                if ($user->roles[0]->name == 'admin' || $userRight['create_action'] == 1) {

                    $actions->attributes = $_POST['MainActions'];
                    if ($_POST['type'] == 'i') {
                        $actions->responsable_id = Yii::app()->user->id;
                    } elseif ($_POST['type'] == 'manager_edit_action') {
                        $actions->responsable_id = $_POST['MainActions']['manager_id'];
                    } elseif ($_POST['type'] == 'director_edit_action') {
                        $actions->responsable_id = $_POST['MainActions']['director_id'];
                    } elseif ($_POST['type'] == 'no') {
                        $admin = new Users();
                        $admin_id = $admin->getAdminId();
                        $actions->responsable_id = $admin_id;
                    }
                    if ($_POST['MainActions']['action_date'] != '') {
                        $actions->action_date = date('Y-m-d H:i', strtotime($_POST['MainActions']['action_date']));
                    }

                    if ($resultTransaction = $actions->save()) {
                        if (isset($_POST['MainActions']['Labels'])) {
                            $labelsOper = $_POST['MainActions']['Labels'];
                            foreach ($labelsOper as $id => $oper) {
                                if ($oper == 1) {
                                    $addLabel = new LabelsInActions();
                                    $addLabel->action_id = $actions->id;
                                    $addLabel->label_id = $id;
                                    $resultTransaction = true;
                                    if ($addLabel->checkDuplicate($id, $actions->id)) {
                                        if (!$addLabel->save()) {
                                            $resultTransaction = false;
                                            break;
                                        }
                                    }
                                } elseif ($oper == 0) {
                                    LabelsInActions::model()->deleteAll('action_id = :CID && label_id = :LID',
                                        [
                                            ':CID' => $actions->id,
                                            ':LID' => $id
                                        ]
                                    );
                                }
                            }
                        }
                    }

                    if ($resultTransaction) {
                        $transaction->commit();
                        $action_resp_role = UsersRoles::model()->find('user_id=' . $actions->responsable_id);
                        if (array_key_exists('save_and_create', $_POST)) {
                            $this->redirect(array('new_action', 'id' => $actions->client_id));
                        } else {
                            $isShowBlockSave = true;
                        }
                    } else {
                        $transaction->rollback();
                    }
                }
            }
        } else {
            throw new CHttpException(412,'no_access_action');
        }

        $this->render('edit_action', array
        (
            'actions' => $actions,
            'user' => $user,
            'userRight' => $userRight,
            'action_resp_role' => $action_resp_role,
            'allLabels' => $allLabels ?? [],
            'customSelectedLabels' => $customSelectedLabels ?? [],
            'accessClient' => $accessClient,
			'actionFiles' => $actionFiles,
            'selectedLabels' => $selectedLabels ?? [],
            'isShowBlockSave' => $isShowBlockSave ?? false,
            'isSuccessSave' => $isSuccessSave,
            'client' => $client,
            ));
    }

    public function actionNotFoundPage(){
        $this->render('not_found_page');
    }

    public function actionClient_profile($id, $labelActionsId = null, $labelDealsId = null)
    {
        if (!$modelClient = Clients::model()->findByPk($id)) {
            throw new CHttpException(404, 'Контакт не найден');
        }
        $user = Users::model()->with(['roles', 'userInGroups', 'userRights', 'parent'])->findByPk(Yii::app()->user->id);
        $userRight = Yii::app()->commonFunction->getUserRight($user);

        // метки
        if ($labelsType = LabelsType::model()->find('name = :NAME', [':NAME' => 'clients'])) {
            $selectedLabelsInClients = LabelsInClients::model()->with('label')->findAll('client_id = :ID', [':ID' => $modelClient->id]);
        }

        //этапы (1 - clients)
        $listStep = Steps::model()->findAll('steps_type_id = :TYPE', [':TYPE' => 1]);
        $selectedSteps = StepsInClients::model()->find('clients_id = :ID', [':ID' => $modelClient->id]);

        if (!$selectedSteps) {
            foreach ($listStep as $steps) {
                if ($steps->selected_default) {
                    $stepsInfo = [
                        'steps_id' => $steps->id,
                    ];
                    break;
                }
            }
        } else {
            $stepsInfo = [
                'steps_id' => $selectedSteps->steps_id,
            ];
        }

        if (isset($stepsInfo['steps_id']) && $stepsInfo['steps_id'] != 1) {
            foreach (StepsOptions::model()->findAll(['condition' => 'steps_id = :ID', 'order' => 'weight', 'params' =>[':ID' => $stepsInfo['steps_id']]]) as $key => $option) {
                if ($selectedSteps && $selectedSteps->selected_option_id == $option->id) {
                    $stepsInfo['optionName'] = $option->name;
                    $stepsInfo['selectedIndex'] = $key;
                } elseif ($key === 0 && !$selectedSteps){
                    $stepsInfo['optionName'] = $option->name;
                    $stepsInfo['selectedIndex'] = $key;
                }
                $stepsInfo['options'][] = $option->attributes;
            }
        } else {
            $stepsInfo['optionName'] = 'Нет воронки';
        }
                   

        if (Clients::checkAccess($modelClient, $user)) {
            $additionalFiledValuesInClient = Yii::app()->commonFunction->getValueAdditionalFiled($modelClient, $user);
                                                                         
            $actions = new Actions();
            $client_actions_table = $actions->searchActions($id, false, $labelActionsId);  
            
            $deals = new Deals();
            $client_deals_table = $deals->searchDeals($id, false, $labelDealsId);

            $files = new ClientsFiles();

            $clientFilesTable = $files->search($id);
            
                 $listStep = Steps::model()->findAll(['condition' => 'steps_type_id = :TYPE', 'order' => 'weight', 'params' => [':TYPE' => 1]]);
            if (!$selectedSteps = StepsInClients::model()->find('clients_id = :ID', [':ID' => $modelClient->id])) {
                $selectedSteps = new StepsInClients();
            }            
            $listStepOption = [];
            foreach ($listStep as $steps) {
                if ($options = StepsOptions::model()->findAll(['condition' => 'steps_id = :ID', 'order' => 'weight', 'params' =>[':ID' => $steps->id]])) {
                    $listStepOption[$steps->id] = $options;
                }

                if (!$selectedSteps->steps_id && $steps->selected_default) {
                    $selectedSteps->steps_id = $steps->id;
                    $oldOptionId = $selectedSteps->selected_option_id;
                    $selectedSteps->selected_option_id = $options[0]->id;

                    if ($oldOptionId != $selectedSteps->selected_option_id) {
                        $changeUpdateDate = true;
                    }
                }
            }
            // метки
            if ($labelsType = LabelsType::model()->find('name = :NAME', [':NAME' => 'clients'])) {
                $allLabels = Labels::model()->findAll('type_id = :ID', [':ID' => $labelsType->id]);
                $selectedLabels = LabelsInClients::model()->with('label')->findAll('client_id = :ID', [':ID' => $modelClient->id]);
                    foreach ($selectedLabels as $label) {
                        $customSelectedLabels [$label->label->id] = $label->label;
                    } 
            }
            $notes = ClientsNotes::model()->with('user')->with('edit_user')->findAll(
                     [  'condition' => 'client_id = :ID',
                        'order' => 'added DESC',
                        'params' => [':ID' => $modelClient->id]] 
             ); 
           
            $this->render('client_profile', array
            (
                'user' => $user,
                'modelClient' => $modelClient,
                'client_actions_table' => $client_actions_table,
                'client_deals_table' => $client_deals_table,
                'deals' => $deals,             
                'actions' => $actions,
                'notes' => $notes,
                'userRight' => $userRight,
                'selectedLabelsInClients' => $selectedLabelsInClients,
                'additionalFiledValuesInClient' => $additionalFiledValuesInClient,
                'labelActionsId' => $labelActionsId,
                'labelDealsId' => $labelDealsId,
                'stepsInfo' => $stepsInfo,
                'clientFilesTable' => $clientFilesTable,
                'client' => $modelClient,
                'selectedSteps' => $selectedSteps,
                'listStepOption' => $listStepOption,
                'listStep' => $listStep,
                'allLabels' => $allLabels,
                'selectedLabels' => $selectedLabels,
                'customSelectedLabels' => $customSelectedLabels ?? [],
                ));
        } else {
            throw new CHttpException(412, 'no_access_client');
        }
    }

    public function actionDealings_page($labelId = null, $dealTypeFilter = 1)
    {
        $user = Users::model()->findByPk(Yii::app()->user->id);
        $deals = new Deals();
        $userRight = UserRight::model()->find('user_id = ' . Yii::app()->user->id);
        $selectedSteps = new StepsInDeals();
        if ($userRight->create_deals) {
            if (isset($_GET['Deals']) && isset($_GET['Deals']['search'])) {
                $deals->keyword = $_GET['Deals']['keyword'];
                $deals->start_date = $_GET['Deals']['start_date'];
                $deals->stop_date = $_GET['Deals']['stop_date'];
                if ($_GET['type'] == Yii::app()->user->id) {
                    $deals->responsable_id = Yii::app()->user->id;
                } elseif ($_GET['type'] == 'manager') {
                    $deals->responsable_id = $_GET['Deals']['manager_id'];
                } elseif ($_GET['type'] == 'director') {
                    $deals->responsable_id = $_GET['Deals']['director_id'];
                } elseif ($_GET['type'] == 'all') {
                    $deals->responsable_id = 'all';
                } elseif ($_GET['type'] == 'no') {
                    $deals->responsable_id = 'no';
                } elseif ($_GET['type'] == 'admin'){
                    $deals->responsable_id = $user->parent_id;
                }
                $deals->attributes = $_GET['Deals'];
                $deals->client_group_id = $_GET['Deals']['client_group_id'] ?? null;
                $deals->documents = $_GET['Deals']['documents'] ?? '0';

                if (isset($_GET['Deals']['Labels'])) {
                    $labelId = [];
                    foreach ($_GET['Deals']['Labels'] as $id => $enable) {
                        if ($enable == 1) {
                            $labelId [] = $id;
                        }
                    }
                }

                if (isset($_GET['StepsInDeals'])) {
                    $selectedSteps->steps_id = $_GET['StepsInDeals']['steps_id'];
                    $selectedSteps->selected_option_id = $selectedSteps->steps_id != 2 ? $_GET['StepsInDeals']['selected_option_id'] : 0;
                }
            }

        $allLabels = Labels::model()->findAll('type_id = 3');
        $customSelectedLabels = [];

        if ($labelId) {
            if (!is_array($labelId)) {
                $labelId = [$labelId];
            }
            foreach ($allLabels as $label) {
                if (in_array($label->id, $labelId)) {
                    $customSelectedLabels [$label->id] = $label;
                }
            }
        }

            //этапы (1 - clients)
            $listStep = Steps::model()->findAll(['condition' => 'steps_type_id = :TYPE', 'order' => 'weight', 'params' => [':TYPE' => 2]]);
            array_unshift($listStep, (object) ['id' => 0, 'name' => 'Все воронки']);

            $listStepOption = [];
            foreach ($listStep as $steps) {
                if ($options = StepsOptions::model()->findAll(['condition' => 'steps_id = :ID', 'order' => 'weight', 'params' =>[':ID' => $steps->id]])) {
                    $listStepOption[$steps->id] = $options;
                }
            }

            if (!$selectedSteps->steps_id) {
                $selectedSteps->steps_id = 0;
            }

            $deals_table_data = $deals->searchDeals(null, false, $labelId, $selectedSteps, $dealTypeFilter);

            $sumPaid = 0;
            $sumBalance = 0;

            foreach ($deals_table_data->getData() as $value) {
                $sumPaid += $value->paid;
                $sumBalance += $value->balance;
            }
            $l = $deals_table_data->getData();
            $modelCount = clone($deals);

            $countTypeAll = $modelCount->searchDeals(null, true, $labelId, $selectedSteps, 0);
            $countTypeAll = $countTypeAll ? count($countTypeAll) : 0;

            $countTypeActive = $modelCount->searchDeals(null, true, $labelId, $selectedSteps, 1);
            $countTypeActive = $countTypeActive ? count($countTypeActive) : 0;

            $countTypeWin = $modelCount->searchDeals(null, true, $labelId, $selectedSteps, 2);
            $countTypeWin = $countTypeWin ? count($countTypeWin) : 0;

            $countTypeLose = $modelCount->searchDeals(null, true, $labelId, $selectedSteps, 3);
            $countTypeLose = $countTypeLose ? count($countTypeLose) : 0;

            $this->render('dealings_page', array(
                'user' => $user,
                'deals' => $deals,
                'deals_table_data' => $deals_table_data,
                'allLabels' => $allLabels,
                'listStep' => $listStep,
                'listStepOption' => $listStepOption,
                'selectedSteps' => $selectedSteps,
                'customSelectedLabels' => $customSelectedLabels,
                'countTypeAll' => $countTypeAll,
                'countTypeActive' => $countTypeActive,
                'countTypeWin' => $countTypeWin,
                'countTypeLose' => $countTypeLose,
                'dealTypeFilter' => $dealTypeFilter,
                'sumPaid' => $sumPaid,
                'sumBalance' => $sumBalance,
            ));
        } else {
            $this->redirect('NotFoundPage');
        }
    }

    public function actionActions_page($term = 1, $labelId = null)
    {
        $user = Users::model()->findByPk(Yii::app()->user->id);
        $actions = new Actions();
        $clients = new Clients();
        $actions->term = $term;   
        $userRight = UserRight::model()->find(['condition' => 'user_id = :USER', 'params' =>[':USER' => Yii::app()->user->id]]); 
        if ($userRight->create_action) {
            if (isset($_GET['Actions']) && isset($_GET['Actions']['search'])) {
                $actions->keyword = $_GET['Actions']['keyword'];
                $actions->start_date = $_GET['Actions']['start_date'] ?? '';
                $actions->stop_date = $_GET['Actions']['stop_date'] ?? '';
                if ($_GET['type'] == Yii::app()->user->id) {
                    $actions->responsable_id = Yii::app()->user->id;
                } elseif ($_GET['type'] == 'manager') {
                    $actions->responsable_id = $_GET['Actions']['manager_id'];
                } elseif ($_GET['type'] == 'director') {
                    $actions->responsable_id = $_GET['Actions']['director_id'];
                } elseif ($_GET['type'] == 'all') {
                    $actions->responsable_id = 'all';
                } elseif ($_GET['type'] == 'no') {
                    $actions->responsable_id = 'no';
                } elseif ($_GET['type'] == 'admin'){
                    $actions->responsable_id = $user->parent_id;
                }
                $actions->attributes = $_GET['Actions'];
                $actions->client_group_id = $_GET['Actions']['client_group_id'] ?? null;
                $actions->documents = $_GET['Actions']['documents'] ?? '0';

                if (isset($_GET['Actions']['Labels'])) {
                    $labelId = [];
                    foreach ($_GET['Actions']['Labels'] as $id => $enable) {
                        if ($enable == 1) {
                            $labelId [] = $id;
                        }
                    }
                }
            }
            $labelsType = LabelsType::model()->find('name = :NAME', [':NAME' => 'actions']) ;  
            $allLabels = $allEventLabels = Labels::model()->findAll('type_id = :ID', [':ID' => $labelsType->id]);

            $customSelectedLabels = [];

            if ($labelId) {
                if (!is_array($labelId)) {
                    $labelId = [$labelId];
                }
                foreach ($allLabels as $label) {
                    if (in_array($label->id, $labelId)) {
                        $customSelectedLabels [$label->id] = $label;
                    }
                }
            }

            $actions_table_data = $actions->searchActions(null, false, $labelId);
            $modelCount = clone($actions);

            $modelCount->term = 1;
            $actionCountTerm1 = $modelCount->searchActions(null, true);
            $actionCountTerm1 = $actionCountTerm1 ? count($actionCountTerm1) : 0;

            $modelCount->term = 2;
            $actionCountTerm2 = $modelCount->searchActions(null, true);
            $actionCountTerm2 = $actionCountTerm2 ? count($actionCountTerm2) : 0;

            $modelCount->term = 3;
            $actionCountTerm3 = $modelCount->searchActions(null, true);
            $actionCountTerm3 = $actionCountTerm3 ? count($actionCountTerm3) : 0;

            $modelCount->term = 4;
            $actionCountTerm4 = $modelCount->searchActions(null, true);
            $actionCountTerm4 = $actionCountTerm4 ? count($actionCountTerm4) : 0;

            $this->render('actions_page',
                array(
                    'term' => $term,
                    'user' => $user,
                    'actions' => $actions,
                    'clients' => $clients,
                    'userRight' => $userRight,
                    'allEventLabels' => $allEventLabels,
                    'actions_table_data' => $actions_table_data,
                    'actionCountTerm1' => $actionCountTerm1,
                    'actionCountTerm2' => $actionCountTerm2,
                    'actionCountTerm3' => $actionCountTerm3,
                    'actionCountTerm4' => $actionCountTerm4,
                    'allLabels' => $allLabels,
                    'customSelectedLabels' => $customSelectedLabels,
                ));
        } else {
            $this->redirect('NotFoundPage');
        }
    }

    public function actionUser_profile($id, $labelActionsId = null, $labelDealsId = null, $labelClientsId = null)
    {
        if (!$user = Users::model()->with('roles', 'parent')->findByPk($id)) {
            throw new CHttpException(404,'Указанная запись не найдена');
        }
        $callUser = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $callUserRole = $callUser->roles[0]->name;

        if (!Users::checkAccessUser($callUserRole, $user, $callUser)) {
            throw new CHttpException(412,'no_access_user');
        }

        $userRight = UserRight::model()->find('user_id = ' . $id);

        $callUserRight = Yii::app()->commonFunction->getUserRight($callUser);
        $userFiles = UsersFiles::model()->findAll('user_id = :user', [':user' => $id]);
        $client = new Clients();
        $client->responsable_id = $id;
        $client_table_data = $client->searchClients(false, $labelClientsId);

        $userGroup = UserInGroups::model()->with('group')->find('user_id = :user', [':user' => $id])->group->name;

        $priority = array(
            'clients' => array(
                'low' => 0,
                'middle' => 0,
                'high' => 0,
            ),
            'actions' => array(
                'expected' => 0,
                'completed' => 0,
                'noResult' => 0,
                'countToDay' => 0,
                'countFuture' => 0,
                'countFinish' => 0,
                'countOverdue' => 0,
            ),
            'deals' => array(
                'potential' => 0,
                'imProcessing' => 0,
                'completed' => 0,
                'noResult' => 0,
                'sumPaid' => 0,
                'sumBalance' => 0,
                'countAll' => 0,
                'active' => [
                    'count' => 0,
                    'paid' => 0,
                    'balance' => 0,
                ],
                'win' => [
                    'count' => 0,
                    'paid' => 0,
                    'balance' => 0,
                ],
                'lose' => [
                    'count' => 0,
                    'paid' => 0,
                    'balance' => 0,
                ],
            )
        );

        foreach ($client_table_data->data as $client) {
           switch ($client->priority->name) {
               case 'Важный':
                   $priority['clients']['high'] += 1;
                   break;
               case 'Средний':
                   $priority['clients']['middle'] += 1;
                   break;
               case 'Низкий':
                   $priority['clients']['low'] += 1;
                   break;
           }
        }
        $action = new Actions();
        $action->responsable_id = $id;
        $actions_table_data = $action->searchActions(null, false, $labelActionsId);
        foreach ($actions_table_data->data as $actions) {
            switch ($actions->actionStatus->name) {
                case 'Ожидается':
                    $priority['actions']['expected'] += 1;
                    break;
                case 'Завершено':
                    $priority['actions']['completed'] += 1;
                    break;
                case 'Нет результата':
                    $priority['actions']['noResult'] += 1;
                    break;
            }
        }
        $priority['actions']['all'] = $priority['actions']['expected'] + $priority['actions']['completed'] + $priority['actions']['noResult'];

        $modelCount = clone($action);

        $modelCount->term = 1;
        $actionCountTerm1 = $modelCount->searchActions(null, true);
        $priority['actions']['countToDay'] = $actionCountTerm1 ? count($actionCountTerm1) : 0;

        $modelCount->term = 2;
        $actionCountTerm2 = $modelCount->searchActions(null, true);
        $priority['actions']['countFuture'] = $actionCountTerm2 ? count($actionCountTerm2) : 0;

        $modelCount->term = 3;
        $actionCountTerm3 = $modelCount->searchActions(null, true);
        $priority['actions']['countFinish'] = $actionCountTerm3 ? count($actionCountTerm3) : 0;

        $modelCount->term = 4;
        $actionCountTerm4 = $modelCount->searchActions(null, true);
        $priority['actions']['countOverdue'] = $actionCountTerm4 ? count($actionCountTerm4) : 0;

        $deals = new Deals();
        $deals->responsable_id = $id;
        $deals_table_data = $deals->searchDeals(null, false, $labelDealsId);
        foreach ($deals_table_data->data as $deals) {
            switch ($deals->deal_type_id) {
                case 1: {
                    $priority['deals']['active']['count']++;
                    $priority['deals']['active']['paid'] += $deals->paid;
                    $priority['deals']['active']['balance'] += $deals->balance;
                    break;
                }
                case 2: {
                    $priority['deals']['win']['count']++;
                    $priority['deals']['win']['paid'] += $deals->paid;
                    $priority['deals']['win']['balance'] += $deals->balance;
                    break;
                }
                case 3: {
                    $priority['deals']['lose']['count']++;
                    $priority['deals']['lose']['paid'] += $deals->paid;
                    $priority['deals']['lose']['balance'] += $deals->balance;
                    break;
                }
            }
            $priority['deals']['countAll']++;
            $priority['deals']['sumPaid'] += $deals->paid;
            $priority['deals']['sumBalance'] += $deals->balance;
        }

        $files = new UsersFiles();
        $filesTableData = $files->search($id);

        $this->render('user_profile', array(
		    'user' => $user, 'userRight' => $userRight,
            'client_table_data' => $client_table_data,
            'actions_table_data' => $actions_table_data,
            'deals_table_data' => $deals_table_data,
            'priority' => $priority,
            'userGroup' => $userGroup,
            'callUserRight' => $callUserRight,
            'userFiles' => $userFiles,
            'labelDealsId' => $labelDealsId,
            'labelActionsId' => $labelActionsId,
            'labelClientsId' => $labelClientsId,
            'filesTableData' => $filesTableData,
            )
        );
	}

	/**
	 * \brief Выход из системы
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionActivation($key)
	{
		$record = Activations::model()->findByAttributes(array('key' => $key));
		if ($record === null) {
			throw new CHttpException(412, Yii::t('page', 'Wrong activation key'));
		}
        if (Yii::app()->user->id != $record->user_id && $record->type != 'password_recovery'
            && $record->type != 'manager_registration' && $record->type != 'registration' && $record->type != 'reset_activation') {
            $this->render('wrong_user');
            return;
        }
        if ($record->type === 'registration') {
            Users::model()->updateByPk($record->user_id, array('status' => 'active'));
            $record->delete();
            $this->redirect(array('login', 'login_type' => 'activation'));
        } elseif ($record->type === 'change_email') {
            Users::model()->updateByPk($record->user_id, array('email' => $record->add_data));
            $record->delete();
            $this->redirect(array('email_changed'));
        } elseif ($record->type == 'password_recovery') {
            $user = Users::model()->findByPk($record->user_id);
            $model = new LostForm();
            $model->email = $user->email;
            if ($model->validate() && $model->sendPassword()) {
                $this->layout = '//layouts/login';
                $this->render('pass_success_send');
            }
        } elseif ($record->type == 'manager_registration') {
		    $data = json_decode($record->add_data, true);
            Users::model()->updateByPk($record->user_id, array('status' => 'active'));
            $user_inf = Users::model()->findByPk($record->user_id);
            $email = Yii::app()->email;
            $email->AddAddress($user_inf->email);
            $email->Subject = 'Регистрация';
            $email->Body = 'Поздравляем, ' . $user_inf->first_name . '!
                                        <br/>
                                        Доступ в CRM - ' . 'http://' . $_SERVER['HTTP_HOST'] . '
                                         <br/>
                                        Ваш e-mail - ' . $user_inf->email . '
                                         <br/>
                                        Ваш пароль - ' . $data['password'] . '<br/>
										--------------<br/>
                                        CRM Инклиент<br/>
                                        ';
            $email->send();

            $record->delete();
            $this->redirect(array('login', 'login_type' => 'activation'));
        } elseif ($record->type == 'reset_activation') {
            $data = json_decode($record->add_data, true);
            Users::model()->updateByPk($record->user_id, array('status' => 'active'));
            $user_inf = Users::model()->findByPk($record->user_id);
            $email = Yii::app()->email;
            $email->AddAddress($user_inf->email);
            $email->Subject = 'Регистрация';
            $email->Body = 'Поздравляем, ' . $user_inf->first_name . '!
                                        <br/>
                                        Доступ в CRM - ' . 'http://' . $_SERVER['HTTP_HOST'] . '
                                         <br/>
                                        Ваш e-mail - ' . $user_inf->email . '
                                         <br/>
                                        Ваш пароль - ' . $data['password'] . '<br/>
										--------------<br/>
                                        CRM Инклиент<br/>
                                        ';
            $email->send();

            $record->delete();
            $this->redirect(array('login', 'login_type' => 'activation'));
        }
	}

    public function actionEmail_changed()
    {
        $this->render('email_changed');
    }

	public function actionPass_success_send()
	{
		$this->render('pass_success_send');
	}

	public function actionPassword_recovery()
	{
        $this->layout = '//layouts/login';
		$model = new LostForm();
        $appearance = Appearance::model()->find();

		if (isset($_POST['LostForm'])) {
			$model->attributes = $_POST['LostForm'];
			if ($model->validate()) {
				$record = Users::model()->findByAttributes(array('email' => $model->email));
				if ($record === null) {
					$model->addError('email', 'Пользователь с указанным e-mail не найден');
				} else {
					$activated = new Activations();
					$activated->setAttributes(
						array(
							'type' => 'password_recovery',
							'user_id' => $record->id,
							'key' => substr(
								preg_replace('/[oO0Il1]/i', '', md5(rand() . rand() . rand() . time())),
								0,
								24
							),
							'date' => new CDbExpression('NOW()'),
						)
					);
					if ($activated->save()) {
                        $link = 'http://' . $_SERVER['HTTP_HOST'] . '/page/activation/key/' . $activated->key;
                        $userInf = Users::model()->find('email="' . $model->email . '"');
                        $email = Yii::app()->email;
                        $email->ClearAllRecipients();
                        $email->AddAddress($model->email);
                        $email->Subject = 'Восстановление пароля';
						$email->Body = '
						Привет, '. $userInf->first_name . ' ' . $userInf->second_name .'!<br/>
						Получен запрос на восстановление пароля в CRM. Чтобы поменять пароль на другой, перейдите по  ' . CHtml::link('ссылке восстановления', $link) . ' или просто нажмите здесь ' . $link .'
                        <br/>--------------<br/>
                        CRM Инклиент<br/>';
						$email->send();
					}
                    $this->redirect(array('lost_success'));
				}
			}
		}

		$this->render('password_recovery', array('model' => $model, 'appearance' => $appearance));
	}

	public function actionLost_success()
    {
        $this->render('lost_success');
    }

    //настройки контактов

    public function actionSettings_clients_group()
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        $groups = new ClientsGroups();
        $groups_table_data = $groups->search();
        if ($user->roles[0]->name == 'admin') {
            if (isset($_POST['ClientsGroups'])) {
                $groups->attributes = $_POST['ClientsGroups'];
                if (isset($_POST['ajax']) && $_POST['ajax'] == 'create-group') {
                    echo CActiveForm::validate($groups);
                    Yii::app()->end();
                }
                if ($groups->validate()) {
                    $groups->save();
                    $groups = new ClientsGroups();
                }
            }
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
        $this->render('settings_clients_group', array('user' => $user, 'groups' => $groups, 'groups_table_data' => $groups_table_data));
    }

    public function actionDelete_client_group($id)
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            if ($groups = ClientsGroups::model()->findByPk($id)) {
                if ($default_group = ClientsGroups::model()->find('name="Главная группа"')) {
                    Clients::model()->updateAll(array('group_id' => $default_group->id), 'group_id=' . $id);
                    $groups->delete();
                    $this->redirect(array('settings_clients_group'));
                }
            }
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionEdit_client_group($id)
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            $groups = ClientsGroups::model()->findByPk($id);
            if (isset($_POST['ajax']) && $_POST['ajax'] == 'edit-group') {
                echo CActiveForm::validate($groups);
                Yii::app()->end();
            }
            if (isset($_POST['MainClientsGroups'])) {
                $groups->attributes = $_POST['MainClientsGroups'];

                if ($groups->validate() && $groups->update()) {
                    $this->redirect(array('settings_clients_group'));
                } else {
                    $this->redirect(array('settings_clients_group'));
                }
            }

            $delete_button = CHtml::button("Удалить", array(
                'onClick' => 'window.location.href="' . Yii::app()->createUrl("page/delete_client_group",
                        array("id" => $id)) . '"',
                'class' => 'btn',
            ));
            echo '<div class="popup" id="popup-edit-group" style="display: block;">
	<div class="popup__head">
		<div class="title">Редактор группы</div>
	</div>
	<div class="popup__form">';

            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'edit-group',
                'enableAjaxValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
            ));

            echo '<div class="form-group">
				' . $form->textField($groups, 'name', array('class' => 'form-control', 'placeholder' => 'Имя группы')) .
                $form->error($groups, 'name', array('class' => 'form-error')) . '
				<span class="star">*</span>
			</div>
			<div class="form-group">
				' . CHtml::submitButton('Редактировать группу', array('class' => 'btn')) . '
			</div>
			<div class="function-delete">
				<a class="delete" href="#">Удалить группу</a>
			</div>
			<div class="function-delete-confirm">
				<ul class="horizontal">
					<li class="big">Контакты из этой группы примут параметр - "Главная группа"</li>
					<li><a class="delete" href="#">Отмена</a></li>
					<li>' . $delete_button . '</li>
				</ul>
			</div>
	</div>
</div>' . $this->actionGetJSStyle('edit-group', '[{
                "id": "MainClientsGroups_name",
                "inputID": "MainClientsGroups_name",
                "errorID": "MainClientsGroups_name_em_",
                "model": "MainClientsGroups",
                "name": "name",
                "enableAjaxValidation": true
            }]');
            $this->endWidget();
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }


    public function actionSettings_clients_source()
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            $sources = new ClientsSources();
            $sources_table_data = $sources->search();
            if (isset($_POST['ClientsSources'])) {
                $sources->attributes = $_POST['ClientsSources'];
                if (isset($_POST['ajax']) && $_POST['ajax'] == 'create-source') {
                    echo CActiveForm::validate($sources);
                    Yii::app()->end();
                }
                if ($sources->validate()) {
                    $sources->save();
                    $sources = new ClientsSources();
                }
            }
            $this->render('settings_clients_source', array('user' => $user, 'sources' => $sources, 'sources_table_data' => $sources_table_data));
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionDelete_client_source($id)
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            if ($sources = ClientsSources::model()->findByPk($id)) {
                if ($default_source = ClientsSources::model()->find('name="Нет источника"')) {
                    Clients::model()->updateAll(array('source_id' => $default_source->id), 'source_id=' . $id);
                    $sources->delete();
                    $this->redirect(array('settings_clients_source'));
                }
            }
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionEdit_client_source($id)
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            $sources = ClientsSources::model()->findByPk($id);
            if (isset($_POST['ajax']) && $_POST['ajax'] == 'edit-source') {
                echo CActiveForm::validate($sources);
                Yii::app()->end();
            }
            if (isset($_POST['MainClientsSources'])) {
                $sources->attributes = $_POST['MainClientsSources'];
                if ($sources->update()) {
                    $this->redirect(array('settings_clients_source'));
                }
            }

            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'edit-source',
                'enableAjaxValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
            ));

            $delete_button = CHtml::button("Удалить", array(
                'onClick' => 'window.location.href="' . Yii::app()->createUrl("page/delete_client_source",
                        array("id" => $id)) . '"',
                'class' => 'btn',
            ));

            echo '<div class="popup" id="popup-edit-source" style="display: block;">
	<div class="popup__head">
		<div class="title">Редактор источника</div>
	</div>
	<div class="popup__form">
			<div class="form-group">
				' . $form->textField($sources, 'name', array('class' => 'form-control', 'placeholder' => 'Имя источника')) .
                $form->error($sources, 'name', array('class' => 'form-error')) . '
				<span class="star">*</span>
			</div>
			<div class="form-group">
				' . CHtml::submitButton('Редактировать источник', array('class' => 'btn')) . '
			</div>
			<div class="function-delete">
				<a class="delete" href="#">Удалить источник</a>
			</div>
			<div class="function-delete-confirm">
				<ul class="horizontal">
					<li class="big">Контакты этого источника примут параметр - "Нет источника"</li>
					<li><a class="delete" href="#">Отмена</a></li>
					<li>' . $delete_button . '</li>
				</ul>
			</div>
	</div>
</div>' . $this->actionGetJSStyle('edit-source', '[{
                "id": "MainClientsSources_name",
                "inputID": "MainClientsSources_name",
                "errorID": "MainClientsSources_name_em_",
                "model": "MainClientsSources",
                "name": "name",
                "enableAjaxValidation": true
            }]');

            $this->endWidget();
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionSettings_clients_priority()
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            $prioritys = new ClientsPriority();
            $prioritys_table_data = $prioritys->search();

            $this->render('settings_clients_priority', array('user' => $user, 'prioritys' => $prioritys, 'prioritys_table_data' => $prioritys_table_data));
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionSettings_clients_goal()
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            $goals = new ClientsGoals();
            $goals_table_data = $goals->search();
            if (isset($_POST['ClientsGoals'])) {
                $goals->attributes = $_POST['ClientsGoals'];
                if (isset($_POST['ajax']) && $_POST['ajax'] == 'create-goal') {
                    echo CActiveForm::validate($goals);
                    Yii::app()->end();
                }
                if ($goals->validate()) {
                    $goals->save();
                    $goals = new ClientsGoals();
                }
            }

            $this->render('settings_clients_goal', array('user' => $user, 'goals' => $goals, 'goals_table_data' => $goals_table_data));
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionDelete_client_goal($id)
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            if ($goals = ClientsGoals::model()->findByPk($id)) {
                if ($default_goal = ClientsGoals::model()->find('name="Нет цели"')) {
                    Clients::model()->updateAll(array('goal_id' => $default_goal->id), 'goal_id=' . $id);
                    $goals->delete();
                    $this->redirect(array('settings_clients_goal'));
                }
            }
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionEdit_client_goal($id)
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            $goals = ClientsGoals::model()->findByPk($id);
            if (isset($_POST['ajax']) && $_POST['ajax'] == 'edit-goal') {
                echo CActiveForm::validate($goals);
                Yii::app()->end();
            }
            if (isset($_POST['MainClientsGoals'])) {
                $goals->attributes = $_POST['MainClientsGoals'];
                if ($goals->update()) {
                    $this->redirect(array('settings_clients_goal'));
                }
            }

            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'edit-goal',
                'enableAjaxValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
            ));

            $delete_button = CHtml::button("Удалить", array(
                'onClick' => 'window.location.href="' . Yii::app()->createUrl("page/delete_client_goal",
                        array("id" => $id)) . '"',
                'class' => 'btn',
            ));

            echo '<div class="popup" id="popup-edit-source" style="display: block;">
	<div class="popup__head">
		<div class="title">Редактор цель</div>
	</div>
	<div class="popup__form">
			<div class="form-group">
				' . $form->textField($goals, 'name', array('class' => 'form-control', 'placeholder' => 'Имя цели')) .
                $form->error($goals, 'name', array('class' => 'form-error')) . '
				<span class="star">*</span>
			</div>
			<div class="form-group">
				' . CHtml::submitButton('Редактировать цель', array('class' => 'btn')) . '
			</div>
			<div class="function-delete">
				<a class="delete" href="#">Удалить цель</a>
			</div>
			<div class="function-delete-confirm">
				<ul class="horizontal">
					<li class="big">Контакты с этой целью примут параметр - "Нет цели"</li>
					<li><a class="delete" href="#">Отмена</a></li>
					<li>' . $delete_button . '</li>
				</ul>
			</div>
	</div>
</div>' . $this->actionGetJSStyle('edit-goal', '[{
                "id": "MainClientsGoals_name",
                "inputID": "MainClientsGoals_name",
                "errorID": "MainClientsGoals_name_em_",
                "model": "MainClientsGoals",
                "name": "name",
                "enableAjaxValidation": true
            }]');

            $this->endWidget();
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionSettings_clients_city()
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            $cityes = new ClientsCityes();
            $cityes_table_data = $cityes->search();
            if (isset($_POST['ClientsCityes'])) {
                $cityes->attributes = $_POST['ClientsCityes'];
                if (isset($_POST['ajax']) && $_POST['ajax'] == 'create-city') {
                    echo CActiveForm::validate($cityes);
                    Yii::app()->end();
                }
                if ($cityes->validate()) {
                    $cityes->save();
                    $cityes = new ClientsCityes();
                }
            }

            $this->render('settings_clients_city', array('user' => $user, 'cityes' => $cityes, 'cityes_table_data' => $cityes_table_data));
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionDelete_client_city($id)
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            if ($cityes = ClientsCityes::model()->findByPk($id)) {
                if ($default_city = ClientsCityes::model()->find('name="Нет города"')) {
                    Clients::model()->updateAll(array('city_id' => $default_city->id), 'city_id=' . $id);
                    $cityes->delete();
                    $this->redirect(array('settings_clients_city'));
                }
            }
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionEdit_client_city($id)
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            $cityes = ClientsCityes::model()->findByPk($id);
            if (isset($_POST['ajax']) && $_POST['ajax'] == 'edit-city') {
                echo CActiveForm::validate($cityes);
                Yii::app()->end();
            }
            if (isset($_POST['MainClientsCityes'])) {
                $cityes->attributes = $_POST['MainClientsCityes'];
                if ($cityes->update()) {
                    $this->redirect(array('settings_clients_city'));
                }
            }

            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'edit-city',
                'enableAjaxValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
            ));

            $delete_button = CHtml::button("Удалить", array(
                'onClick' => 'window.location.href="' . Yii::app()->createUrl("page/delete_client_city",
                        array("id" => $id)) . '"',
                'class' => 'btn',
            ));

            echo '<div class="popup" id="popup-edit-source" style="display: block;">
	<div class="popup__head">
		<div class="title">Редактор город</div>
	</div>
	<div class="popup__form">
			<div class="form-group">
				' . $form->textField($cityes, 'name', array('class' => 'form-control', 'placeholder' => 'Имя города')) .
                $form->error($cityes, 'name', array('class' => 'form-error')) . '
				<span class="star">*</span>
			</div>
			<div class="form-group">
				' . CHtml::submitButton('Редактировать город', array('class' => 'btn')) . '
			</div>
			<div class="function-delete">
				<a class="delete" href="#">Удалить город</a>
			</div>
			<div class="function-delete-confirm">
				<ul class="horizontal">
					<li class="big">Контакты этого города примут параметр - "Нет города"</li>
					<li><a class="delete" href="#">Отмена</a></li>
					<li>' . $delete_button . '</li>
				</ul>
			</div>
	</div>
</div>' . $this->actionGetJSStyle('edit-city', '[{
                "id": "MainClientsCityes_name",
                "inputID": "MainClientsCityes_name",
                "errorID": "MainClientsCityes_name_em_",
                "model": "MainClientsCityes",
                "name": "name",
                "enableAjaxValidation": true
            }]');

            $this->endWidget();
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }


    //настройка задач

    public function actionSettings_action_priority()
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            $prioritys = new ActionsPriority();
            $prioritys_table_data = $prioritys->search();

            $this->render('settings_action_priority', array('user' => $user, 'prioritys' => $prioritys, 'prioritys_table_data' => $prioritys_table_data));
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionSettings_action_status()
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            $statuses = new ActionsStatuses();
            $statuses_table_data = $statuses->search();

            $this->render('settings_action_status', array('user' => $user, 'statuses' => $statuses, 'statuses_table_data' => $statuses_table_data));
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionSettings_action_type()
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            $types = new ActionsTypes();
            $types_table_data = $types->search();
            if (isset($_POST['ActionsTypes'])) {
                $types->attributes = $_POST['ActionsTypes'];
                if (isset($_POST['ajax']) && $_POST['ajax'] == 'create-action-type') {
                    echo CActiveForm::validate($types);
                    Yii::app()->end();
                }
                if ($types->validate()) {
                    $types->save();
                    $types = new ActionsTypes();
                }
            }

            $this->render('settings_action_type', array('user' => $user, 'types' => $types, 'types_table_data' => $types_table_data));
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionDelete_action_type($id)
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            if ($types = ActionsTypes::model()->findByPk($id)) {
                if ($default_type = ActionsTypes::model()->find('name="Задача не указана"')) {
                    Actions::model()->updateAll(array('action_type_id' => $default_type->id), 'action_type_id=' . $id);
                    $types->delete();
                    $this->redirect(array('settings_action_type'));
                }
            }
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionEdit_action_type($id)
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            $types = ActionsTypes::model()->findByPk($id);
            if (isset($_POST['ajax']) && $_POST['ajax'] == 'edit-type') {
                echo CActiveForm::validate($types);
                Yii::app()->end();
            }
            if (isset($_POST['MainActionsTypes'])) {
                $types->attributes = $_POST['MainActionsTypes'];
                if ($types->update()) {
                    $this->redirect(array('settings_action_type'));
                }
            }

            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'edit-type',
                'enableAjaxValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
            ));

            $delete_button = CHtml::button("Удалить", array(
                'onClick' => 'window.location.href="' . Yii::app()->createUrl("page/delete_action_type",
                        array("id" => $id)) . '"',
                'class' => 'btn',
            ));

            echo '<div class="popup" id="popup-edit-source" style="display: block;">
	<div class="popup__head">
		<div class="title">Редактор типа задачи</div>
	</div>
	<div class="popup__form">
			<div class="form-group">
				' . $form->textField($types, 'name', array('class' => 'form-control', 'placeholder' => 'Имя типа задачи')) .
                $form->error($types, 'name', array('class' => 'form-error')) . '
				<span class="star">*</span>
			</div>
			<div class="form-group">
				' . CHtml::submitButton('Редактировать тип задачи', array('class' => 'btn')) . '
			</div>
			<div class="function-delete">
				<a class="delete" href="#">Удалить тип задачи</a>
			</div>
			<div class="function-delete-confirm">
				<ul class="horizontal">
					<li class="big">Задачи по этому типу примут параметр - "Не указан"</li>
					<li><a class="delete" href="#">Отмена</a></li>
					<li>' . $delete_button . '</li>
				</ul>
			</div>
	</div>
</div>' . $this->actionGetJSStyle('edit-type', '[{
                "id": "MainActionsTypes_name",
                "inputID": "MainActionsTypes_name",
                "errorID": "MainActionsTypes_name_em_",
                "model": "MainActionsTypes",
                "name": "name",
                "enableAjaxValidation": true
            }]');

            $this->endWidget();
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    //настройка сделок

    public function actionSettings_deal_priority()
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            $prioritys = new DealsPriority();
            $prioritys_table_data = $prioritys->search();

            $this->render('settings_deal_priority', array('user' => $user, 'prioritys' => $prioritys, 'prioritys_table_data' => $prioritys_table_data));
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionSettings_deal_status()
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            $statuses = new DealsStatuses();
            $statuses_table_data = $statuses->search();

            $this->render('settings_deal_status', array('user' => $user, 'statuses' => $statuses, 'statuses_table_data' => $statuses_table_data));
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionSettings_deal_category()
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            $categories = new DealsCategories();
            $categories_table_data = $categories->search();
            if (isset($_POST['DealsCategories'])) {
                $categories->attributes = $_POST['DealsCategories'];
                if (isset($_POST['ajax']) && $_POST['ajax'] == 'create-deal-category') {
                    echo CActiveForm::validate($categories);
                    Yii::app()->end();
                }
                if ($categories->validate()) {
                    $categories->save();
                    $categories = new DealsCategories();
                }
            }

            $this->render('settings_deal_category', array('user' => $user, 'categories' => $categories, 'categories_table_data' => $categories_table_data));
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionDelete_deal_category($id)
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            if ($categories = DealsCategories::model()->findByPk($id)) {
                if ($default_cat = DealsCategories::model()->find('name="Нет категории"')) {
                    Deals::model()->updateAll(array('deal_category_id' => $default_cat->id), 'deal_category_id=' . $id);
                    $categories->delete();
                    $this->redirect(array('settings_deal_category'));
                }
            }
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionEdit_deal_category($id)
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            $categories = DealsCategories::model()->findByPk($id);
            if (isset($_POST['ajax']) && $_POST['ajax'] == 'edit-category') {
                echo CActiveForm::validate($categories);
                Yii::app()->end();
            }
            if (isset($_POST['MainDealsCategories'])) {
                $categories->attributes = $_POST['MainDealsCategories'];
                if ($categories->update()) {
                    $this->redirect(array('settings_deal_category'));
                }
            }

            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'edit-category',
                'enableAjaxValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
            ));

            $delete_button = CHtml::button("Удалить", array(
                'onClick' => 'window.location.href="' . Yii::app()->createUrl("page/delete_deal_category",
                        array("id" => $id)) . '"',
                'class' => 'btn',
            ));

            echo '<div class="popup" id="popup-edit-source" style="display: block;">
	<div class="popup__head">
		<div class="title">Редактор категории</div>
	</div>
	<div class="popup__form">
			<div class="form-group">
				' . $form->textField($categories, 'name', array('class' => 'form-control', 'placeholder' => 'Имя категории')) .
                $form->error($categories, 'name', array('class' => 'form-error')) . '
				<span class="star">*</span>
			</div>
			<div class="form-group">
				' . CHtml::submitButton('Редактировать категорию', array('class' => 'btn')) . '
			</div>
			<div class="function-delete">
				<a class="delete" href="#">Удалить категорию</a>
			</div>
			<div class="function-delete-confirm">
				<ul class="horizontal">
					<li class="big">Сделки этой категории примут параметр - "Нет категории"</li>
					<li><a class="delete" href="#">Отмена</a></li>
					<li>' . $delete_button . '</li>
				</ul>
			</div>
	</div>
</div>' . $this->actionGetJSStyle('edit-category', '[{
                "id": "MainDealsCategories_name",
                "inputID": "MainDealsCategories_name",
                "errorID": "MainDealsCategories_name_em_",
                "model": "MainDealsCategories",
                "name": "name",
                "enableAjaxValidation": true
            }]');

            $this->endWidget();
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    //профиль пользователя

    public function actionUser_info($roleFilter = null)
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        $role = $user->roles[0]->name;

        if ($role != 'admin' && $role != 'director') {
            throw new CHttpException(404,'Указанная запись не найдена');
        }
        $new_user = new Users();

        $newUserRight = New UserRight();
        $userRight = UserRight::model()->find('user_id = ' . Yii::app()->user->id);
        $clientCount = Clients::model()->count();
        $actionCount = Actions::model()->count();
        $dealCount = Deals::model()->count();
        $userCount = Users::model()->count();
        $allGroupsRecord = UsersGroups::model()->findAll();

        $allGroups = ['0' => 'Все'];
        foreach ($allGroupsRecord as $group) {
            $allGroups[$group->id] = $group->name;
        }

        if (isset($_GET['Users']))
        {
            $new_user->keyword = $_GET['Users']['keyword'];
            if (isset($_GET['type'])) {
                if ($_GET['type'] == Yii::app()->user->id) {
                    $new_user->parent_id = Yii::app()->user->id;
                } elseif ($_GET['type'] == 'manager') {
                    $new_user->parent_id = $_GET['Users']['manager_id'];
                } elseif ($_GET['type'] == 'director') {
                    $new_user->parent_id = $_GET['Users']['director_id'];
                } elseif ($_GET['type'] == 'all') {
                    $new_user->parent_id = 'all';
                } elseif ($_GET['type'] == 'no') {
                    $new_user->parent_id = 'no';
                }
            }
            $new_user->attributes = $_GET['Users'];

            $new_user->data['group'] = $_GET['data']['group'] ?? null;
            $new_user->role = $_GET['Users']['role'] ?? null;
        } elseif ($roleFilter) {
            $new_user->role = $roleFilter;
        }

        $users_table_data = $new_user->searchUsers();
        $this->render('user_info', array('user' => $user, 'new_user' => $new_user, 'users_table_data' => $users_table_data,
            'newUserRight' => $newUserRight, 'userRight' => $userRight, 'role' => $role, 'clientCount' => $clientCount, 'roleFilter' => $roleFilter,
            'actionCount' => $actionCount, 'dealCount' => $dealCount, 'userCount' => $userCount, 'allGroups' => $allGroups));
    }

    public function actionEdit_current_user()
    {
        $user = Users::model()->findByPk(Yii::app()->user->id);
        $user->setScenario('edit_current_user');
        if (isset($_POST['Users'])) {
            $user->attributes = $_POST['Users'];
            if(isset($_POST['ajax']) && $_POST['ajax']=='edit-current-user')
            {
                echo CActiveForm::validate($user);
                Yii::app()->end();
            }

            if ($user->update()) {
                $this->redirect(array('user_info'));
            }
        }
    }

    public function actionCreate_user()
    {
        $currentUser = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        $currentRoles = $currentUser->roles[0]->name;
        $callUserRight = UserRight::model()->find('user_id = ' . $currentUser->id);
        $newUserRight = new UserRight();
        $user = new Users();
        if ($currentRoles == 'admin') {
            $roleArray = array('director' => 'Руководитель', 'manager' => 'Менеджер');
        } else {
            $roleArray = array('manager' => 'Менеджер');
        }
        $groupArray = [];
        foreach (UsersGroups::model()->findAll() as $group) {
            $groupArray[$group->id] = $group->name;
        }
        $statusArray = array('active' => 'Активен', 'none' => 'Не активен', 'limited' => 'Ограничен по ip',
            'dismissed' => 'Уволен', 'noActivated' => 'Требует активации');
        $userRole = new UsersRoles();
        $directorsArray = array(Yii::app()->user->id => 'Я ответственный');
        $responsible = Users::model()->with('roles')->findAll(array(
                'select' => array('id', 'first_name'),
                'condition' => 'roles.name = "director" and status != "dismissed" and id != '
                    . Yii::app()->user->id,
               )
        );
        foreach ($responsible as $responsibleUser) {
            $directorsArray[$responsibleUser->id] = $responsibleUser->first_name;
        }
        $user->newPassword = substr(preg_replace('/[oO0Il1]/i', '', md5(rand() . rand() . rand() . time())), 0, 8);
        if (isset($_POST['Users'])) {
            $transaction = $currentUser->getDbConnection()->beginTransaction();
            try {
                $user->attributes = $_POST['Users'];
                if (isset($_POST['UsersRoles']['itemname']) && $_POST['UsersRoles']['itemname'] == 'director') {
                    $user->common_access = $_POST['Users']['common_access_director'];
                } elseif ((isset($_POST['UsersRoles']['itemname']) && $_POST['UsersRoles']['itemname'] == 'manager') || $currentRoles == 'director') {
                    $user->common_access = $_POST['Users']['common_access_manager'];
                } else {
                    $user->common_access = 6;
                }
                $user->reg_date = new CDbExpression('NOW()');
                $user->position = $_POST['Users']['position'];
                if ($currentRoles == 'admin') {
                    if ($_POST['UsersRoles']['itemname'] == 'director') {
                        $user->parent_id = $currentUser->id;
                    } elseif ($_POST['UsersRoles']['itemname'] == 'manager') {
                        $user->parent_id = $_POST['Users']['parent_id'];
                    }
                } else {
                    $user->parent_id = $currentUser->id;
                }
                $user->password = $_POST['Users']['newPassword'];
                if ($user->validate() && $user->save()) {
                    $userGroup = new UserInGroups;
                    $userGroup->user_id = $user->id;
                    if ($currentRoles == 'admin') {
                        $userGroup->group_id = $_POST['Users']['data']['group'];
                    } else {
                        $userGroup->group_id = UserInGroups::model()->find('user_id = :user', [':user' => Yii::app()->user->id])->group_id;
                    }
                    $userGroup->save();
                    $role = new UsersRoles();
                    $role->user_id = $user->id;
                    if ($currentRoles == 'admin') {
                        $role->itemname = $_POST['UsersRoles']['itemname'];
                    } else {
                        $role->itemname = 'manager';
                    }
                    if ($role->save()) {
                        if ($role->itemname == 'manager') {
                            $_POST['UserRight']['create_field'] = '0';
                            $_POST['UserRight']['delete_field'] = '0';
                            $_POST['UserRight']['delete_section'] = '0';
                            $_POST['UserRight']['add_files_user'] = '0';
                            $_POST['UserRight']['delete_files_user'] = '0';
                        }

                        $newUserRight->attributes = $_POST['UserRight'];
                        $newUserRight->user_id = $user->id;
                        if (Yii::app()->commonFunction->checkSetRight($newUserRight, 'newUser', $callUserRight)) {
                            if ($newUserRight->save()) {
                                $user->image = CUploadedFile::getInstance($user, 'image');
                                if ($user->image) {
                                    $salt = rand();
                                    $user->setScenario('avatar');
                                    $avatarLink = '/public/avatar_' . $user->id . '_' . $salt . '.' . $user->image->getExtensionName();
                                    $path = Yii::getPathOfAlias('webroot') . $avatarLink;
                                    $user->avatar = $avatarLink;
                                    $user->image->saveAs($path);
                                    $user->save();
                                }
                                $transaction->commit();
                                if ($_POST['Users']['status'] == 'noActivated') {
                                    $data = json_encode(['password' => $_POST['Users']['newPassword']]);
                                    $activated = new Activations();
                                    $activated->setAttributes(
                                        array(
                                            'type' => 'manager_registration',
                                            'add_data' => $data,
                                            'user_id' => $user->id,
                                            'key' => substr(
                                                preg_replace('/[oO0Il1]/i', '', md5(rand() . rand() . rand() . time())),
                                                0,
                                                24
                                            ),
                                            'date' => new CDbExpression('NOW()'),
                                        )
                                    );
                                    if ($activated->save()) {
                                        $link = 'http://' . $_SERVER['HTTP_HOST'] . '/page/activation/key/' . $activated->key;
                                        $email = Yii::app()->email;
                                        $email->ClearAllRecipients();
                                        $email->AddAddress($user->email);
                                        $email->Subject = 'Подтверждение регистрации';
                                        $email->Body = '
                Привет, ' . $user->first_name . '!<br>
                В CRM системе была зарегистрирована учетная запись для вас.<br>
                Пожалуйста, подтвердите регистрацию вашей учетной записи - перейдите по ' . CHtml::link('ссылке активации', $link) . '.
                <br><br>После подтверждения аккаунта вам поступит письмо с паролем.<br>
                ';
                                        $email->send();
                                        $this->redirect(array('/page/user_profile/', 'id' => $user->id));
                                    }
                                } else {
                                    $this->redirect(array('/page/user_profile/', 'id' => $user->id));
                                }
                            } else {
                                $transaction->rollback();
                            }
                        } else {

                            $transaction->rollback();
                        }
                    } else {
                        $transaction->rollback();
                    }
                } else {
                    $transaction->rollback();
                }
            } catch (Exception $e) {
                $transaction->rollback();
            }
        }
        $this->render('create_user', array('user' => $user, 'currentUser' => $currentUser, 'currentRoles' => $currentRoles,
            'callUserRight' => $callUserRight, 'newUserRight' => $newUserRight, 'roleArray' =>$roleArray,
            'directorsArray' => $directorsArray, 'statusArray' => $statusArray, 'userRole' => $userRole, 'groupArray' => $groupArray));
    }

    public function actionDelete_user($id){
        if ($user = Users::model()->findByPk($id)) {
            $admin = new Users();
            $admin_id = $admin->getAdminId();
            $new_responsible = $user->parent_id != null ? $user->parent_id : $admin_id;
            Clients::model()->updateAll(array('responsable_id' => $new_responsible, 'creator_id' => $new_responsible), 'responsable_id=' . $id);
            Clients::model()->updateAll(array('creator_id' => $new_responsible), 'creator_id=' . $id);
            Deals::model()->updateAll(array('responsable_id' => $new_responsible), 'responsable_id=' . $id);
            Actions::model()->updateAll(array('responsable_id' => $new_responsible), 'responsable_id=' . $id);
            Users::model()->updateAll(array('parent_id' => $new_responsible), 'parent_id = ' . $id);
            if (isset($user->avatar) && file_exists(Yii::getPathOfAlias('webroot') . $user->avatar)) {
                @unlink(Yii::getPathOfAlias('webroot') . $user->avatar);
            }
            $user->delete();

            $this->redirect(array('user_info'));
        }
    }

    public function actionEdit_user_password($id)
    {
        $result = [
            'status' => 'fail',
            'errorList' => []
        ];

        ob_start();
        if ($user = Users::model()->findByPk($id)) {
            $password = substr(preg_replace('/[oO0Il1]/i', '', md5(rand() . rand() . rand() . time())), 0, 8);
            $user->password = md5($password);
            if ($user->update()) {
                $email = Yii::app()->email;
                $email->ClearAllRecipients();
                $email->AddAddress($user->email);
                $email->Subject = 'Изменен пароль';
                $email->Body = "Ваш новый пароль: " . $password;
                $email->send();
                if ($email->IsError()) {
                    $result['errorList'] = ['Ошибка отправки сообщения'];
                } else {
                    $result['status'] = 'success';
                }
            } else {
                $result['errorList'] = $user->getErrors();
            }

            //стираем сообщенеи об ошибках в буфере, чтобы получить нормальный ответ на контакта, а не вот это вот все...
            ob_end_clean();
            echo json_encode($result);
        }
    }

    public function actionChange_email()
    {
        $user = Users::model()->findByPk(Yii::app()->user->id);
        if (isset($_POST['Users'])) {
            $user->email == $_POST['Users']['email'];
            if(isset($_POST['ajax']) && $_POST['ajax']=='change-email')
            {
                echo CActiveForm::validate($user);
                Yii::app()->end();
            }
            $activated = new Activations();
            $activated->setAttributes(
                array(
                    'type' => 'change_email',
                    'add_data' => $_POST['Users']['email'],
                    'user_id' => $user->id,
                    'key' => substr(
                        preg_replace('/[oO0Il1]/i', '', md5(rand() . rand() . rand() . time())),
                        0,
                        24
                    ),
                    'date' => new CDbExpression('NOW()'),
                )
            );
            if($activated->save()){
                $email = Yii::app()->email;
                $email->ClearAllRecipients();
                $email->AddAddress($_POST['Users']['email']);
                $email->Subject = 'Изменен E-mail';
                $email->Body = "Для подтверждения изменения адресса электронной почты, пожалуйста перейдите по " . CHtml::link('ссылке', 'http://' . $_SERVER['HTTP_HOST'] . '/page/activation/key/' . $activated->key);
                $email->send();
                $this->redirect(array('user_info'));
            }
        }
    }

    public function actionChange_password()
    {
        $user = Users::model()->findByPk(Yii::app()->user->id);
        $password = substr(preg_replace('/[oO0Il1]/i', '', md5(rand() . rand() . rand() . time())), 0, 8);
        $user->password = md5($password);
        if ($user->update()) {
            $email = Yii::app()->email;
            $email->ClearAllRecipients();
            $email->AddAddress($user->email);
            $email->Subject = 'Изменен пароль';
            $email->Body = "Ваш новый пароль: " . $password;
            $email->send();
            $this->redirect(array('user_info'));
        }
    }

    public function actionEdit_user($id)
    {

        $targetUser = Users::model()->with('roles')->findByPk($id);
        $targetUserRight = UserRight::model()->find('user_id = ' . $id);
        $targetUserRole = UsersRoles::model()->find('user_id=' . $targetUser->id);
        $callUser = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        $callUserRole = $callUser->roles[0]->name;
        $callUserRight = UserRight::model()->find('user_id = ' . Yii::app()->user->id);
        $groupArray = [];
        $isProfile = $callUser->id == $targetUser->id;
        foreach (UsersGroups::model()->findAll() as $group) {
            $groupArray[$group->id] = $group->name;
        }
        $userGroup = UserInGroups::model()->find('user_id = :user', [':user' => $id]);
        $targetUser->data['group'] = $userGroup->group_id;

        $roleArray = array('manager' => 'Менеджер', 'director' => 'Руководитель', 'admin' => 'Директор');
        $statusArray = array('active' => 'Активен', 'none' => 'Не активен', 'limited' => 'Ограничен по ip',
            'dismissed' => 'Уволен', 'noActivated' => 'Требует активации');

        $directorsArray = [];
        if ($callUserRole == 'admin') {
            $responsible = Users::model()->with('roles')->findAll(array(
                    'select' => array('id', 'first_name'),
                    'condition' => 'roles.name = "director" and status != "dismissed" and id != '
                        . $targetUser->id . ' and id != ' . Yii::app()->user->id,
                  )

            );
            $directorsArray = array(Yii::app()->user->id => 'Я ответственный');
            foreach ($responsible as $responsibleUser) {
                $directorsArray[$responsibleUser->id] = $responsibleUser->first_name;
            }
        }

        if (!Users::checkAccessUser($callUserRole, $targetUser, $callUser)) {
            throw new CHttpException(412,'no_access_user');
        }


        if (isset($_POST['Users'])) {
            $transaction = $targetUser->getDbConnection()->beginTransaction();
            try {
                $oldUser = clone $targetUser;
                $oldStatus = $targetUser->status;
                $targetUser->attributes = $_POST['Users'];
                if ($callUserRole == 'admin') {
                    if ($targetUserRole->itemname == 'director') {
                        $targetUser->parent_id = $callUser->id;
                    } elseif ($targetUserRole->itemname == 'manager') {
                        $targetUser->parent_id = $_POST['Users']['parent_id'];
                    }
                };
                if ($targetUserRole->itemname != 'admin') {
                    if ($targetUser->id != $callUser->id) {
                        $targetUser->common_access = $_POST['Users']['common_access'];
                    }
                }
                if ($callUserRole == 'admin') {
                    $userGroups = UserInGroups::model()->findAll('user_id = :user', [':user' => $id]);
                    $userGroupsIds = [];
                    foreach ($userGroups as $userGroup) {
                        $userGroupsIds[$userGroup->group_id] = $userGroup->group_id;
                    }
                    foreach ($_POST['Users']['data'] as $group) {
                        if (!isset($userGroupsIds[$group])) {
                            $newUserGroup = new UserInGroups();
                            $newUserGroup->user_id = $id;
                            $newUserGroup->group_id = $group;
                            $newUserGroup->save();
                        }
                        unset($userGroupsIds[$group]);
                    }
                    if (count($userGroupsIds) > 0) {
                        $idGroupStr = implode(',', $userGroupsIds);
                        UserInGroups::model()->deleteAll("user_id = :user AND group_id in ($idGroupStr)", [':user' => $id]);
                    }
                }

                $targetUser->position = $_POST['Users']['position'];
                if (isset($_POST) && trim($_POST['Users']['newPassword'] != '')) {
                    $targetUser->password = md5($_POST['Users']['newPassword']);
                }
                if ($targetUser->avatar == 'del') {
                    $targetUser->avatar = null;
                }
                if ($targetUser->save()) {
                    $oldUserRight = clone $targetUserRight;
                    if ($targetUserRole->itemname != 'admin' && !$isProfile) {
                        if ($targetUserRole->itemname == 'manager') {
                            $_POST['UserRight']['create_field'] = '0';
                            $_POST['UserRight']['delete_field'] = '0';
                            $_POST['UserRight']['delete_section'] = '0';
                            $_POST['UserRight']['add_files_user'] = '0';
                            $_POST['UserRight']['delete_files_user'] = '0';
                        }
                        $targetUserRight->attributes = $_POST['UserRight'];
                    }
                    if ($targetUserRole->save()) {
                        if (Yii::app()->commonFunction->checkSetRight($targetUserRight, $oldUserRight, $callUserRight)) {
                            if ($targetUserRole == 'admin' || $targetUserRight->update()) {
                                $targetUser->save();
                                $targetUser->image = CUploadedFile::getInstance($targetUser, 'image');
                                if ($targetUser->image) {
                                    $salt = rand();
                                    $targetUser->setScenario('avatar');
                                    $avatarLink = '/public/avatar_' . $targetUser->id . '_' . $salt . '.' . $targetUser->image->getExtensionName();
                                    $path = Yii::getPathOfAlias('webroot') . $avatarLink;
                                    $targetUser->avatar = $avatarLink;
                                    $targetUser->image->saveAs($path);
                                    $targetUser->save();
                                }
                                $transaction->commit();
                                $isShowBlockSave = true;
                                if (!isset($targetUser->avatar) && file_exists(Yii::getPathOfAlias('webroot') . $oldUser->avatar)) {
                                    @unlink(Yii::getPathOfAlias('webroot') . $oldUser->avatar);
                                }

                                if (isset($_POST['Users']['status']) && $_POST['Users']['status'] == 'noActivated' && $oldStatus != 'noActivated') {
                                    $newPassword = substr(preg_replace('/[oO0Il1]/i', '', md5(rand() . rand() . rand() . time())), 0, 8);;
                                    $newActivation = true;
                                    $activated = new Activations();
                                    $activated->setAttributes(
                                        array(
                                            'type' => 'reset_activation',
                                            'add_data' => json_encode(['password' => $newPassword]),
                                            'user_id' => $targetUser->id,
                                            'key' => substr(
                                                preg_replace('/[oO0Il1]/i', '', md5(rand() . rand() . rand() . time())),
                                                0,
                                                24
                                            ),
                                            'date' => new CDbExpression('NOW()'),
                                        )
                                    );
                                    if ($activated->save()) {

                                        $targetUser->password = md5($newPassword);
                                        $targetUser->save();

                                        $link = 'http://' . $_SERVER['HTTP_HOST'] . '/page/activation/key/' . $activated->key;
                                        $email = Yii::app()->email;
                                        $email->ClearAllRecipients();
                                        $email->AddAddress($targetUser->email);
                                        $email->Subject = 'Подтверждение регистрации';
                                        $email->Body = '
                                    Привет, ' . $targetUser->first_name . '! 
                                    Ваш аккаунт нужно подтвердить. <br>Пожалуйста, активируйте учетную запись перейдя по ' . CHtml::link('ссылке активации', $link) . '.<br/>--------------<br/>
                                        CRM Инклиент<br/>';
                                        $email->send();
                                    }
                                }
                                //$this->redirect(array('/page/user_profile/', 'id' => $id));
                            } else {
                                $transaction->rollback();
                            }
                        } else {
                            $transaction->rollback();
                        }
                    } else {
                        $transaction->rollback();
                    }
                } else {
                    $transaction->rollback();

                }
            } catch (Exception $e) {
                $transaction->rollback();
            }
        }
        $this->render('edit_user', array('targetUser' => $targetUser, 'callUser' => $callUser, 'targetUserRight' => $targetUserRight,
            'callUserRight' => $callUserRight, 'roleArray' => $roleArray, 'statusArray' => $statusArray, 'directorsArray' => $directorsArray,
            'targetUserRole' => $targetUserRole, 'callUserRole' => $callUserRole,
            'groupArray' => $groupArray,
            'isProfile' => $isProfile,
            'isShowBlockSave' => $isShowBlockSave ?? false,
            'newActivation' => $newActivation ?? false
        ));
    }

    public function actionSettings_security()
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            $rangeIP = new RangeIp();
            $rangeIP_table_data = $rangeIP->search();
            if (isset($_POST['RangeIp'])) {
                $rangeIP->attributes = $_POST['RangeIp'];
                if (isset($_POST['ajax']) && $_POST['ajax'] == 'create-range') {
                    echo CActiveForm::validate($rangeIP);
                    Yii::app()->end();
                }

                if (!$rangeIP->save()) {
                    $this->redirect(array('settings_security?status=error'));
                }
                $rangeIP = new RangeIp();
            }
            $this->render('settings_security', array('user' => $user, 'rangeIP' => $rangeIP, 'rangeIP_table_data' => $rangeIP_table_data));
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }


    public function actionNew_additional_field($id)
    {
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $addField = new AdditionalFields();

        if ($sections = AdditionalFieldsSection::model()->findByPk($id)) {

            if (!isset($_POST['AdditionalFields'])) {
                $endAddField = AdditionalFields::model()->find(
                    [
                        'condition' => 'section_id = :section',
                        'order' => 'weight DESC',
                        'params' => [':section' => $id]]
                );
                if ($endAddField) {
                    $addField->weight = $endAddField->weight + 1;
                } else {
                    $addField->weight = 1;
                }
            }

            if ($user->roles[0]->name == 'admin' || $user->userRights[0]->create_field) {
                if (isset($_POST['AdditionalFields'])) {
                    try {
                        $transaction = $user->getDbConnection()->beginTransaction();
                        $addField->attributes = $_POST['AdditionalFields'];
                        $addField->section_id = $id;
                        $addField->table_name = 'time';

                        switch ($_POST['AdditionalFields']['type']) {
                            case 'int':
                            case 'varchar':
                                $addField->default_value = $_POST['AdditionalFields']['defaultValueType']['string'];
                                break;
                            case 'checkbox':
                                $addField->default_value = $_POST['AdditionalFields']['defaultValueType']['checkBox'];
                                break;
                            case 'date':
                                $addField->default_value = $_POST['AdditionalFields']['defaultValueType']['date'] != ''
                                    ? strtotime($_POST['AdditionalFields']['defaultValueType']['date']) : '';
                                break;
                            case 'select':
                                if ($addField->checkValidTypeSelectValue($listOptions = $_POST['AdditionalFields']['Select'])) {
                                    foreach ($listOptions as $key => &$option) {
                                        $option['optionWeight'] = (int)$option['optionWeight'];
                                        $option['id'] = (int)$key;
                                    }
                                    function sortWeight ($arr1, $arr2) {
                                        return ($arr1['optionWeight'] > $arr2['optionWeight']);
                                    }
                                    uasort($listOptions, 'sortWeight');
                                    $addField->default_value = json_encode($listOptions);
                                }
                                break;
                        }

                        if (!$addField->getError('selectError')) {
                            $addField->save();
                        }

                        if (!$addField->getErrors() || $addField->getError('default_value')) {
                            $addField->table_name = 'field_' . $addField->id;
                            if ($addField->save()) {
                                $typeField = $_POST['AdditionalFields']['type'] == 'varchar' ? 'TEXT' : 'VARCHAR(255)';
                                Yii::app()->db->createCommand("ALTER TABLE `additional_fields_values` ADD `$addField->table_name` $typeField NOT NULL")->query();
                                $transaction->commit();
                                Yii::app()->user->setFlash('create_additional_field','Новое дополнительное поле "'. $addField->name . '" успешно создано');
                                $this->redirect(array('Settings_additional_field'));
                            }
                        }
                    } catch (Exception $e) {
                        $transaction->rollback();
                    }
                }
            } else {
                throw new CHttpException(404, 'Указанная запись не найдена');
            }
            $this->render('new_additional_field', array(
                'user' => $user,
                'addField' => $addField,
                'selectOptions' => isset($_POST['AdditionalFields']) && isset($_POST['AdditionalFields']['Select']) ?
                    $_POST['AdditionalFields']['Select'] : [
                        ['optionWeight' => 1, 'optionName' => '', 'default' => true],
                        ['optionWeight' => 2, 'optionName' => '']
                    ]
            ));
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionAdditional_field_edit($id)
    {
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $addField = AdditionalFields::model()->with('section0')->findByPk($id);
        if ($addField) {
            if ($user->roles[0]->name == 'admin' || ($user->userRights[0]->create_field && Yii::app()->commonFunction->checkAccessField($user, $addField))) {
                $sections = AdditionalFieldsSection::model()->findAll();
                $sectionsArr = [];
                foreach ($sections as $section) {
                    $sectionsArr[$section->id] = $section->name;
                }
                if (isset($_POST['AdditionalFields'])) {
                    try {
                        $transaction = $user->getDbConnection()->beginTransaction();
                        $oldAddField = null;
                        if ($addField->type == 'select') {
                            $oldListOptions = json_decode($addField->default_value, true);
                        }
                        $addField->attributes = $_POST['AdditionalFields'];
                        switch ($addField->type) {
                            case 'int':
                            case 'varchar':
                                $addField->default_value = $_POST['AdditionalFields']['default_value'] ?? '';
                                break;
                            case 'checkbox':
                                $addField->default_value = $_POST['AdditionalFields']['default_value'] ?? '';
                                break;
                            case 'date':
                                $addField->default_value = $_POST['AdditionalFields']['default_value'] != ''
                                    ? strtotime($_POST['AdditionalFields']['default_value']) : '';
                                break;
                            case 'select':
                                if ($addField->checkValidTypeSelectValue($_POST['AdditionalFields']['Select'])) {
                                    $listOptions = $_POST['AdditionalFields']['Select'];
                                    $maxId = max(array_column($listOptions,'id'));
                                    foreach ($listOptions as &$option) {
                                        isset($option['id']) && $option['id'] != '' ?: $option['id'] = ++$maxId;
                                        $option['optionWeight'] = (int)$option['optionWeight'];
                                        if (isset($option['default']) && $option['default']) {
                                            $defaultID = $option['id'];
                                        }
                                    }
                                    $ids = array_column($listOptions, 'id');
                                    foreach ($oldListOptions as $value) {
                                        if (!in_array($value['id'], $ids)) {
                                            AdditionalFieldsValues::model()->updateAll([$addField->table_name => $defaultID], $addField->table_name . '=' . $value['id']);
                                        }
                                    }
                                     function sortWeight ($arr1, $arr2) {
                                        return ($arr1['optionWeight'] > $arr2['optionWeight']);
                                    }
                                    uasort($listOptions, 'sortWeight');
                                    $addField->default_value = json_encode($listOptions);
                                }
                                break;
                        }

                        if (!$addField->getErrors() || $addField->getError('default_value')) {
                            if ($addField->save()) {
                                $transaction->commit();
                                if ($addField->type == 'select') {
                                    $selectOptions = json_decode($addField->default_value, true);
                                    foreach ($selectOptions as $value) {
                                        if (isset($value['default']) && $value['default']) {
                                            $addField->default_value = $value['optionName'];
                                            break;
                                        }
                                    }
                                }
                                $isShowBlockSave = true;
                                //$this->redirect(array('Settings_additional_field'));
                            }
                        }
                    } catch (Exception $e) {
                        $transaction->rollback();
                        $this->redirect(array('Settings_additional_field'));
                    }
                } elseif ($addField->type == 'select') {
                    $selectOptions = json_decode($addField->default_value, true);
                    foreach ($selectOptions as $value) {
                        if (isset($value['default']) && $value['default']) {
                            $addField->default_value = $value['optionName'];
                            break;
                        }
                    }
                }
             } else {
                throw new CHttpException(412, 'no_access_settings');
            }
        } else {
            throw new CHttpException(404);
        }
        $this->render('additional_field_edit', array(
            'user' => $user,
            'addField' => $addField,
            'sectionsArr' => $sectionsArr,
            'selectOptions' => $selectOptions ?? $_POST['AdditionalFields']['Select'] ?? [],
            'isShowBlockSave' => $isShowBlockSave ?? false,
        ));
    }

    public function actionAdditional_field_delete($id)
    {
        $addField = AdditionalFields::model()->findByPk($id);
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        if ($addField->table_name != 'fieldFio' && ($user->roles[0]->name == 'admin' || ($user->userRights[0]->delete_field && Yii::app()->commonFunction->checkAccessField($user, $addField)))
        ) {
            try {
                $transaction = $user->getDbConnection()->beginTransaction();
                Yii::app()->db->createCommand(" ALTER TABLE `additional_fields_values` DROP `$addField->table_name`")->query();
                $addField->delete();
                $transaction->commit();
                $this->redirect(array('Settings_additional_field'));
            } catch (Exception $e) {
                $transaction->rollback();
                $this->redirect(array('Settings_additional_field'));
            }

        } else {
            throw new CHttpException(412, 'no_access_settings');
        }

        $this->render('additional_field_edit', array('user' => $user, 'addField' => $addField));
    }

    public function actionSettings_additional_field()
    {
        $user = Users::model()->with(['roles', 'userRights', 'userInGroups'])->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin' || $user->userRights[0]->create_field) {
            $criteria = new CDbCriteria;
            $criteria->with = ['additionalFields', 'sectorInGroups'];
            $criteria->select = [
                'id',
                'name',
                'access',
                'weight'
            ];
            $criteria->order = 't.weight';
            $userGroup = [];
            foreach ($user->userInGroups as $value) {
                $userGroup[] = $value->group_id;
            }
            if ($user->roles[0]->name != 'admin') {
                $criteria->addCondition('sectorInGroups.group_id in('. implode(',', $userGroup). ') OR t.access = "all"');
            }
            $selectionAddFields = AdditionalFieldsSection::model()->findAll($criteria);
            $groupsUsers = [];
            foreach (UsersGroups::model()->findAll() as $groupUsers) {
                $groupsUsers[$groupUsers->id] = $groupUsers->name;
            }
            $addField = new AdditionalFields;
            $allAddFiled = [];
            $textGroup = [];
            $countSection = [];
            foreach ($selectionAddFields as $section) {
                $countSection[$section->id] = count($section->additionalFields);
                $allAddFiled[$section->id] = $addField->search($section->id);
                if ($section->access == 'groups') {
                    foreach ($section->sectorInGroups as $group) {
                        if (!isset($textGroup[$group->sector_id])) {
                            $textGroup[$group->sector_id] = '';
                        }
                        $textGroup[$group->sector_id] .= ' ' . $groupsUsers[$group->group_id] . ',';
                    }
                    $textGroup[$section->id] = substr($textGroup[$section->id], 0, -1);
                }
            }
            $this->render('settings_additional_field', array('user' => $user, 'addField' => $addField,
                'selectionAddFields' => $selectionAddFields, 'allAddFiled' => $allAddFiled, 'textGroup' => $textGroup,
                'countSection' => $countSection));
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionSettings_users_groups()
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            $userGroups = new UsersGroups();
            $userGroups = $userGroups->search();
            $this->render('settings_users_groups', array('user' => $user,
                'userGroups' => $userGroups,));
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    //return array
    private function selectedBackgroundType($type = 'rotate') {
        $backgroundImageType = [
            'rotate' => 1,
            'link' => 0,
            'image' => 0,
            'gradient_1' => 0,
            'gradient_2' => 0,
            'gradient_3' => 0,
        ];

        foreach ($backgroundImageType as $key => $value) {
            $backgroundImageType[$key] = $key === $type ? 1 : 0;
        }

        return $backgroundImageType;
    }

    public function actionSettings_common()
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        $versionBD = Version::model()->findAll(['order' => 'id Desc', 'limit' => 1]);
        $typeTab = 'appearance';
        $logoPathDefault = "/img/logo.svg";
        $backgroundImagePathDefault = "/img/background_image.jpg";
        //цвета градиентов
        $gradients = [
            'gradient_1' => 'linear-gradient(to top right, #1d1c1f, #70b7f8)',
            'gradient_2'  => 'linear-gradient(to top right, #ef6b50, #f8ad70)',
            'gradient_3'  => 'linear-gradient(357deg, #d65078, #341344)',
        ];

        $transaction = Yii::app()->db->beginTransaction();

        if ($user->roles[0]->name == 'admin') {
            $errors = [];
            $isHowBlockSave = false;
            $settings = new Settings();

            $appearance = Appearance::model()->find();
            $appearanceLinks = AppearanceLinks::model()->findAll();
            $fileModelLogo = new UploadImportFile();
            $newLinks = [];
            $appearanceErrors = [];

            $backgroundImageType = $this->selectedBackgroundType($appearance->background_image_type);

            // если какой либо градиент будет удалён слуйчайно или намерено и его не окажется в массиве типов
            if (!isset($backgroundImageType[$appearance->background_image_type])) {
                $appearance->background_image_type = 'rotate';
                $backgroundImageType = $this->selectedBackgroundType('rotate');
            }

            switch ($appearance->background_image_type) {
                case 'rotate': {
                    $backgroundImageType = $this->selectedBackgroundType('rotate');
                    break;
                };
                case 'link': {
                    $backgroundImageType = $this->selectedBackgroundType('link');
                    break;
                };
                case 'image': {
                    $backgroundImageType = $this->selectedBackgroundType('image');
                    break;
                }
            }

            if (isset($_POST['settingsBtn'])) {
                $typeTab = 'filesAndTime';
                foreach ($_POST['Settings'] as $key => $value) {
                    $settings->value = $value['value'];
                    $settings->param = $key;
                    if ($settings->validate()) {
                        $settings->updateAll(['value' => $value['value']], 'param = "' . $key . '"');
                    } else {
                        $errors[$key] = $settings->getErrors()['value'];
                    }
                }

                if (count($errors) == 0) {
                    Yii::app()->user->setFlash('settings_success','Новый контакт создан');
                    $transaction->commit();
                } else {
                    $transaction->rollback();
                }
            } elseif (isset($_POST['appearanceBtn'])) {
                $typeTab = 'appearance';

                if (isset($_POST['Appearance'])) {
                    // AppearanceLinks
                    $currentLinks = $_POST['AppearanceLinks']['current'] ?? [];
                    $newLinks = $_POST['AppearanceLinks']['new'] ?? [];

                    // работаем с тякущими ссылками
                    foreach ($appearanceLinks as $modelLink) {
                        $isDelete = true; // флаг отвечающий за удалённую ссылку в интерфейсе
                        foreach ($currentLinks as $id => $value) {
                            if ($modelLink->id == $id) {
                                $modelLink->link_name = $value['link_name'];
                                $modelLink->link_value = $value['link_value'];
                                $isDelete = false;

                                if (!$modelLink->save()) {
                                    $appearanceErrors = $modelLink->getErrors();
                                }
                                break;
                            }
                        }

                        if ($isDelete) {
                            $modelLink->delete();
                        }

                        if ($appearanceErrors) {
                            break;
                        }
                    }

                    if (!$appearanceErrors) {
                        // сохраняем новые ссылки
                        foreach ($newLinks as $value) {
                            $newAppearanceLink = new AppearanceLinks();
                            $newAppearanceLink->link_name = $value['link_name'];
                            $newAppearanceLink->link_value = $value['link_value'];

                            if (!$newAppearanceLink->save()) {
                                $appearanceErrors = $newAppearanceLink->getErrors();
                                break;
                            }
                        }
                    }

                    if (!$appearanceErrors) {
                        $newLinks = [];
                        // Appearance
                        $appearance->menu_name = $_POST['menu_name'];
                        $appearance->menu_link = $_POST['menu_link'];
                        $appearance->footer_name = $_POST['footer_name'];
                        $appearance->footer_link = $_POST['footer_link'];

                        $keys = array_keys($_POST['backgroundImageType']);
                        $appearance->background_image_type = $keys[0];

                        switch ($appearance->background_image_type) {
                            case 'rotate': {
                                $backgroundImageType = $this->selectedBackgroundType('rotate');
                                $appearance->background_image_type_value = 'http://src.inclient.ru/crm/v' . $versionBD[0]->version .'/rotate.php';
                                break;
                            };
                            case 'link': {
                                $backgroundImageType = $this->selectedBackgroundType('link');
                                $appearance->setScenario('scenarioBackgroundLink');
                                $appearance->background_image_type_value = $_POST['backgroundImageTypeValues']['link'] ?? '';
                                break;
                            };
                            case 'image': {
                                $backgroundImageType = $this->selectedBackgroundType('image');
                                if ($_POST['Appearance']['customImageValue']) {
                                    $appearance->setScenario('scenarioCustomImage');
                                    $appearance->customImage = CUploadedFile::getInstance($appearance, 'customImage');

                                    if ($appearance->customImage) {
                                        $appearance->background_image_type_value = '/public/' . $appearance->customImage->getName();
                                        $appearance->customImage->saveAs(Yii::getPathOfAlias('webroot') . $appearance->background_image_type_value);
                                    } else {
                                        $appearance->background_image_type_value = $backgroundImagePathDefault;
                                        $appearance->customImage = $backgroundImagePathDefault;
                                    }
                                } else {
                                    $appearance->background_image_type_value = $backgroundImagePathDefault;
                                }
                                break;
                            };
                            case 'gradient_1':
                            case 'gradient_2':
                            case 'gradient_3': {
                                $backgroundImageType = $this->selectedBackgroundType($appearance->background_image_type);
                                $appearance->background_image_type_value = $_POST['gradientColor'];
                                break;
                            }
                        }

                        // логотип
                        if ($_POST['Appearance']['logo']) {
                            $appearance->image = CUploadedFile::getInstance($appearance, 'image');

                            if ($appearance->image) {
                                if ($appearance->getScenario() === 'scenarioBackgroundLink') {
                                    $appearance->setScenario('scenarioBackgroundLinkAndLoadImage');
                                } else if($appearance->getScenario() === 'scenarioCustomImage') {
                                    $appearance->setScenario('customImageAndLoadImage');
                                } else {
                                    $appearance->setScenario('scenarioCustomImageAndLoadImage');
                                }
                                $appearance->logo = '/public/' . $appearance->image->getName();
                                $appearance->image->saveAs(Yii::getPathOfAlias('webroot') . $appearance->logo);
                            } else {
                                $appearance->logo = $logoPathDefault;
                                $appearance->image = $logoPathDefault;
                            }
                        }

                        if (!$appearance->save()) {
                            $appearanceErrors = $appearance->getErrors();
                        }
                    }

                    if (!$appearanceErrors) {
                        $transaction->commit();
                        Yii::app()->user->setFlash('appearance_success','asdas');

                        //обновляем данные
                        $appearanceLinks = AppearanceLinks::model()->findAll();
                    } else {
                        $transaction->rollback();
                    }
                }
            }

            $settingsSearch = Settings::model()->search();
            $this->render('settings_common', array(
                'user' => $user,
                'settingsSearch' => $settingsSearch,
                'isHowBlockSave' => $isHowBlockSave,
                'timeZoneList' => $this->timeZoneList,
                'typeTab' => $typeTab,
                'appearance' => $appearance,
                'appearanceLinks' => $appearanceLinks,
                'newLinks' => $newLinks,
                'backgroundImageType' => $backgroundImageType,
                'fileModelLogo' => $fileModelLogo,
                'logoPathDefault' => $logoPathDefault,
                'backgroundImagePathDefault' => $backgroundImagePathDefault,
                'gradients' => $gradients,
                'appearanceErrors' => $appearanceErrors,
                'errors' => $errors));
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }
    
    public function actionEdit_rangeIP($id)
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            $rangeIP = RangeIp::model()->findByPk($id);
            if (isset($_POST['ajax']) && $_POST['ajax'] == 'edit-range') {
                echo CActiveForm::validate($rangeIP);
                Yii::app()->end();
            }

            if (isset($_POST['RangeIp'])) {
                $rangeIP->attributes = $_POST['RangeIp'];

                if ($rangeIP->validate() && $rangeIP->update()) {
                    $this->redirect(array('settings_security'));
                } else {
                    $this->redirect(array('settings_security?status=error'));
                }
            }

            $delete_button = CHtml::button("Удалить", array(
                'onClick' => 'window.location.href="' . Yii::app()->createUrl("page/delete_range",
                        array("id" => $id)) . '"',
                'class' => 'btn',
            ));
            echo '<div class="popup" id="popup-edit-range" style="display: block;">
	<div class="popup__head">
		<div class="title">Редактор диапазона</div>
	</div>
	<div class="popup__form">';

            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'edit-range',
                'enableAjaxValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
            ));

            echo
                '<div class="form-group">
				<div class="client_info">Начало диапазона:<span class="star">*</span></div>
				' . $form->textField($rangeIP, 'begin_ip', array('class' => 'form-control', 'maxlength' => 50,
                    'placeholder' => 'Начало диапазона', 'value' => long2ip($rangeIP->begin_ip))) .
                $form->error($rangeIP, 'name', array('class' => 'form-error')) . '
			</div>';

            echo
                '<div class="form-group">
				<div class="client_info">Конец диапазона:<span class="star">*</span></div>
				' . $form->textField($rangeIP, 'end_ip', array('class' => 'form-control',
                    'maxlength' => 50, 'placeholder' => 'Конец диапазона', 'value' => long2ip($rangeIP->end_ip))) .
                $form->error($rangeIP, 'name', array('class' => 'form-error')) . '				
			</div>';
            echo
                '<div class="form-group">
				' . $form->textField($rangeIP, 'comment', array('class' => 'form-control', 'placeholder' => 'Комментарий')) .
                $form->error($rangeIP, 'name', array('class' => 'form-error')) . '
			
			</div>';

            echo '<div class="form-group">
				' . CHtml::submitButton('Сохранить', array('class' => 'btn')) . '
			</div>
			<div class="function-delete" style="display: block;padding-left: 0px;margin-top: 15px;text-align: center;">
				<a class="delete" href="#">Удалить диапазон</a>
			</div>
			<div class="function-delete-confirm" style="margin-top: 15px;">
				<ul class="horizontal">
				    <li style="margin-right: 10px;"><a href="#" class="cancel">Отмена</a></li>
					<li style="margin-top: 9px;">' . $delete_button . '</li>
				</ul>
			</div>
	</div>
</div>' . $this->actionGetJSStyle('edit-range', '[{
                "id": "MainRangeIP",
                "inputID": "MainRangeIP",
                "errorID": "MainRangeIP_em_",
                "model": "rangeIP",
                "name": "name",
                "enableAjaxValidation": true
            }]');
            $this->endWidget();
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionDelete_range($id)
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            if ($range = RangeIp::model()->findByPk($id)) {
                $range->delete();
                $this->redirect(array('settings_security'));
            }
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionNew_client()
    {
        $resultTransaction = false;
        $transaction = Yii::app()->db->beginTransaction();
        $id = Yii::app()->request->getParam('id');
        $targetUser = null;
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        $userRight = UserRight::model()->find('user_id = ' . $user->id);
        if ($user->roles[0]->name == 'admin' || $userRight['create_client']) {
            try {
                if ($id) {
                    $targetUser = Users::model()->findByPk($id);
                }
                // метки
                if ($labelsType = LabelsType::model()->find('name = :NAME', [':NAME' => 'clients'])) {
                    $allLabels = Labels::model()->findAll('type_id = :ID', [':ID' => $labelsType->id]);
                    if (isset($_POST['Clients']['Labels']) && $_POST['Clients']['Labels'] && count($allLabels) > 0) {
                        // запишем выбранные лейблы на случай непрохождения валидации
                        $customSelectedLabels = [];
                        foreach ($allLabels as $label) {
                            if ($_POST['Clients']['Labels'][$label->id]) {
                                $customSelectedLabels [] = $label;
                            }
                        }
                    }
                }

                //этапы (1 - clients)
                $listStep = Steps::model()->findAll(['condition' => 'steps_type_id = :TYPE', 'order' => 'weight', 'params' => [':TYPE' => 1]]);
                $selectedSteps = new StepsInClients();
                if (isset($_POST['StepsInClients'])) {
                    $selectedSteps->steps_id = $_POST['StepsInClients']['steps_id'];
                    $selectedSteps->selected_option_id = $selectedSteps->steps_id != 1 ? $_POST['StepsInClients']['selected_option_id'] : 0;
                }
                $listStepOption = [];
                foreach ($listStep as $steps) {
                    if ($options = StepsOptions::model()->findAll(['condition' => 'steps_id = :ID', 'order' => 'weight', 'params' =>[':ID' => $steps->id]])) {
                        $listStepOption[$steps->id] = $options;
                    }

                    if (!$selectedSteps->steps_id && $steps->selected_default) {
                        $selectedSteps->steps_id = $steps->id;
                        $selectedSteps->selected_option_id = $options[0]->id;
                    }
                }


                $errorAddField = false;
                $errorAddFieldText = '';
                $client = new Clients();
                $additionalFiledValuesInClient = Yii::app()->commonFunction->getValueAdditionalFiledNewClient($user);
                $additionalFiledValue = new AdditionalFieldsValues();
                if (isset($_POST['Clients'])) {
                    if ($user->roles[0]->name == 'admin' || $userRight->create_client == 1) {
                        $client->attributes = $_POST['Clients'];
                        $client->creation_date = date('Y-m-d H:i:s');//new CDbExpression('NOW()');
                        $client->change_client_date = $client->creation_date;
                        $client->name = $_POST['Clients']['additionalField']['fieldFio'];
                        $client->priority_id = ClientsPriority::model()->find()->id;
                        $client->source_id = ClientsSources::model()->find()->id;
                        $client->city_id = ClientsCityes::model()->find()->id;
                        $client->group_id = ClientsGroups::model()->find()->id;

                        if ($_POST['type'] == Yii::app()->user->id) {
                            $client->responsable_id = Yii::app()->user->id;
                            $client->creator_id = Yii::app()->user->id;
                        } elseif ($_POST['type'] == 'manager_create_client') {
                            $client->responsable_id = $_POST['Clients']['manager_id'];
                            $client->creator_id = $_POST['Clients']['manager_id'];
                        } elseif ($_POST['type'] == 'director_create_client') {
                            $client->responsable_id = $_POST['Clients']['director_id'];
                            $client->creator_id = $_POST['Clients']['director_id'];
                        } elseif ($_POST['type'] == 'targetUser') {
                            $client->responsable_id = $_POST['Clients']['responsable_id'];
                            $client->creator_id = Yii::app()->user->id;
                        } elseif ($_POST['type'] == 'no') {
                            $admin = new Users();
                            $admin_id = $admin->getAdminId();
                            $client->responsable_id = $admin_id;
                            $client->creator_id = $admin_id;
                        } elseif (is_numeric($_POST['type'])) {
                            if($res = Users::model()->findByPk($_POST['type'])) {
                                $client->responsable_id = $res->id;
                                $client->creator_id = $res->id;
                            }
                        }
                    }
                    if (isset($_POST['Clients']['additionalField'])) {
                        foreach ($_POST['Clients']['additionalField'] as $addFieldName => $addFieldValue) {
                            $additionalFiledValue->$addFieldName = $addFieldValue;
                        }
                    }
                    $additionalFiledValue = Yii::app()->commonFunction->convertAdditionalField($additionalFiledValue, $additionalFiledValuesInClient);
                    if ($client->save()) {
                        if (isset($_POST['Clients']['additionalField'])) {
                            $additionalFiledValue->client_id = $client->id;
                            $saveAddField = Yii::app()->commonFunction->checkAdditionalFiledValue($additionalFiledValue, $additionalFiledValuesInClient);
                            $errorAddFieldText = '';
                            if ($saveAddField['status'] == 'success') {
                                if ($additionalFiledValue->save()) {
                                    $resultTransaction = true;
                                } else {
                                    $errorAddFieldText.= $additionalFiledValue->getError('duplicate') ?? '';
                                    $errorAddFieldText.= '<br>';
                                    $errorAddField = true;
                                    $resultTransaction = false;
                                }
                            } elseif($saveAddField['status'] == 'failed') {
                                $errorAddField = true;
                                foreach ($saveAddField['type'] as $type) {
                                    switch ($type) {
                                        case 'incorrectData':
                                            $errorAddFieldText.= 'Некорректное значение в поле.';
                                            break;
                                        case 'requiredData':
                                            $errorAddFieldText.= 'Пропущено обязательное поле';
                                            break;
                                        /*case 'uniqueData':
                                            foreach ($saveAddField['fieldsError'] as $key => $fieldError) {
                                                $errorAddFieldText .= '"' . $fieldError . '"' . (count($saveAddField['fieldsError']) != $key +1 ? ', ' : '');
                                            }
                                            $errorAddFieldText.= ' - у другого контакта уже есть такое значение. Заполните поле другими данными';
                                            break;*/
                                    }
                                    $errorAddFieldText.= '<br>';
                                }
                            }
                        } else {
                            $resultTransaction = true;
                        }
                        if (isset($_POST['Clients']['Labels'])) {
                            $labelsOper = $_POST['Clients']['Labels'];
                            foreach ($labelsOper as $id => $oper) {
                                if ($oper == 1) {
                                    $addLabel = new LabelsInClients();
                                    $addLabel->client_id = $client->id;
                                    $addLabel->label_id = $id;
                                    if (!$addLabel->save()) {
                                        $resultTransaction = false;
                                        break;
                                    }
                                }
                            }

                        }

                        if (isset($_POST['StepsInClients'])) {
                            $selectedSteps->clients_id = $client->id;
                            if (!$selectedSteps->save()) {
                                $resultTransaction = false;
                            }
                        }
                    } else {
                        $errorAddField = true;
                        $errorAddFieldText.= 'Пожалуйста, заполните все обязательные поля.';
                    }
                    if ($resultTransaction) {
                        $transaction->commit();
                        if (array_key_exists('save_and_create', $_POST)) {
                            $this->redirect(array('new_client'));
                        } else {


                            Yii::app()->user->setFlash('success','Новый контакт создан');
                            $this->redirect(array('client_profile', 'id' => $client->id));
                        }
                    } else {
                        $transaction->rollback();
                    }
                }
            } catch (Exception $e) {
                $transaction->rollback();
            }
            $this->render('new_client',
                array('client' => $client,
                    'user' => $user,
                    'targetUser' => $targetUser,
                    'userRight' => $userRight,
                    'additionalFiledValuesInClient' => $additionalFiledValuesInClient,
                    'errorAddField' => $errorAddField,
                    'errorAddFieldText' => $errorAddFieldText,
                    'allLabels' => $allLabels ?? [],
                    'customSelectedLabels' => $customSelectedLabels ?? [],
                    'additionalFiledValue' => $additionalFiledValue,
                    'listStep' => $listStep,
                    'listStepOption' => $listStepOption,
                    'selectedSteps' => $selectedSteps)
            );
        } else {
            throw new CHttpException(412, 'no_access_new_client');
        }
    }

    public function actionNew_action()
    {
        $id = Yii::app()->request->getParam('id');
        $resultTransaction = false;
        $user = Users::model()->with(['roles', 'parent'])->findByPk(Yii::app()->user->id);
        $userRight = UserRight::model()->find('user_id = ' . Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin' || $userRight['create_action']) {
            $transaction = Yii::app()->db->beginTransaction();

            $client = null;
            $actions = new Actions();
            if ($id) {
                $client = Clients::model()->with('city')->findByPk($id);
                $actions->client_id = $id;
            }

            // метки
            if ($labelsType = LabelsType::model()->find('name = :NAME', [':NAME' => 'actions'])) {
                $allLabels = Labels::model()->findAll('type_id = :ID', [':ID' => $labelsType->id]);
                if (isset($_POST['Actions']['Labels']) && $_POST['Actions']['Labels'] && count($allLabels) > 0) {
                    // запишем выбранные лейблы на случай непрохождения валидации
                    $customSelectedLabels = [];
                    foreach ($allLabels as $label) {
                        if ($_POST['Actions']['Labels'][$label->id]) {
                            $customSelectedLabels [] = $label;
                        }
                    }
                }
            }
            if (isset($_POST['Actions'])) {
                if ($user->roles[0]->name == 'admin' || $userRight->create_action == 1) {
                    $actions->attributes = $_POST['Actions'];
                    $actions->creation_date = date('Y-m-d H:i:s');//new CDbExpression('NOW()');
                    if ($_POST['type'] == 'i') {
                        $actions->responsable_id = Yii::app()->user->id;
                    } elseif ($_POST['type'] == 'manager_action') {
                        $actions->responsable_id = $_POST['Actions']['manager_id'];
                    } elseif ($_POST['type'] == 'director_action') {
                        $actions->responsable_id = $_POST['Actions']['director_id'];
                    } elseif ($_POST['type'] == 'no') {
                        $admin = new Users();
                        $admin_id = $admin->getAdminId();
                        $actions->responsable_id = $admin_id;
                    }

                    if (strtotime($_POST['Actions']['action_date'])) {
                        $actions->action_date = date('Y-m-d H:i', strtotime($_POST['Actions']['action_date']));
                    } else {
                        $actions->action_date = null;
                    }

                    //дефолтные значения
                    $actions->action_type_id = 3;
                    $actions->action_priority_id = 1;

                    if ($resultTransaction = $actions->save()) {
                        if (isset($_POST['Actions']['Labels'])) {
                            $labelsOper = $_POST['Actions']['Labels'];
                            foreach ($labelsOper as $id => $oper) {
                                if ($oper == 1) {
                                    $addLabel = new LabelsInActions();
                                    $addLabel->action_id = $actions->id;
                                    $addLabel->label_id = $id;
                                    $resultTransaction = true;

                                    if (!$addLabel->save()) {
                                        $resultTransaction = false;
                                        break;
                                    }

                                }
                            }
                        }
                    }
                    if ($resultTransaction) {
                        $transaction->commit();
                        if (array_key_exists('save_and_create', $_POST)) {
                            $this->redirect(array('new_action', 'id' => $actions->client_id));
                        } else {
                            $this->redirect(array('edit_action', 'id' => $actions->id, 'isSuccessSave' => true));
                        }
                    } else {
                        $transaction->rollback();
                    }
                }
            }
            $this->render('new_action', array
            ('actions' => $actions,
                'user' => $user,
                'client' => $client,
                'allLabels' => $allLabels ?? [],
                'customSelectedLabels' => $customSelectedLabels ?? [],
                'userRight' => $userRight));
        } else {
            throw new CHttpException(412, 'no_access_new_action');
        }
    }


    public function actionNew_deal()
    {

        $id = Yii::app()->request->getParam('id');
        $resultTransaction = false;
        $transaction = Yii::app()->db->beginTransaction();
        $client = null;
        $deals = new Deals();
        $deals->deal_type_id = 1;
        if ($id) {
            $client = Clients::model()->findByPk($id);
            $deals->client_id = $id;
        }

        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        $userRight = UserRight::model()->find('user_id = ' . Yii::app()->user->id);

        // метки
        if ($labelsType = LabelsType::model()->find('name = :NAME', [':NAME' => 'deals'])) {
            $allLabels = Labels::model()->findAll('type_id = :ID', [':ID' => $labelsType->id]);
            if (isset($_POST['Deals']['Labels']) && $_POST['Deals']['Labels'] && count($allLabels) > 0) {
                // запишем выбранные лейблы на случай непрохождения валидации
                $customSelectedLabels = [];
                foreach ($allLabels as $label) {
                    if ($_POST['Deals']['Labels'][$label->id]) {
                        $customSelectedLabels [] = $label;
                    }
                }
            }
        }

        //этапы (1 - clients)
        $listStep = Steps::model()->findAll(['condition' => 'steps_type_id = :TYPE', 'order' => 'weight', 'params' => [':TYPE' => 2]]);
        $selectedSteps = new StepsInDeals();
        if (isset($_POST['StepsInDeals'])) {
            $selectedSteps->steps_id = $_POST['StepsInDeals']['steps_id'];
            $selectedSteps->selected_option_id = $selectedSteps->steps_id != 2 ? $_POST['StepsInDeals']['selected_option_id'] : 0;
        }
            $listStepOption = [];
        foreach ($listStep as $steps) {
            if ($options = StepsOptions::model()->findAll(['condition' => 'steps_id = :ID', 'order' => 'weight', 'params' =>[':ID' => $steps->id]])) {
                $listStepOption[$steps->id] = $options;
            }

            if (!$selectedSteps->steps_id && $steps->selected_default) {
                $selectedSteps->steps_id = $steps->id;
                $selectedSteps->selected_option_id = $options[0]->id;
            }
        }

        if (isset($_POST['Deals'])) {
            if ($user->roles[0]->name == 'admin' || $userRight->create_deals == 1) {
                $deals->attributes = $_POST['Deals'];
                $deals->creation_date = date('Y-m-d H:i:s');//new CDbExpression('NOW()');

                $deals->paid = isset($_POST['Deals']['paid']) ? $_POST['Deals']['paid'] : 0;
                $deals->balance = isset($_POST['Deals']['balance']) ? $_POST['Deals']['balance'] : 0;

                if ($_POST['type'] == 'i') {
                    $deals->responsable_id = Yii::app()->user->id;
                } elseif ($_POST['type'] == 'manager_deal') {
                    $deals->responsable_id = $_POST['Deals']['manager_id'];
                } elseif ($_POST['type'] == 'director_deal') {
                    $deals->responsable_id = $_POST['Deals']['director_id'];
                } elseif ($_POST['type'] == 'no') {
                    $admin = new Users();
                    $admin_id = $admin->getAdminId();
                    $deals->responsable_id = $admin_id;
                }
                 $deals->balance = is_numeric($deals->balance) ? $deals->balance : 0;
                 $deals->paid = is_numeric($deals->paid) ? $deals->paid : 0;

                 //дефолтные значения
                $deals->deal_category_id = 4;
                $deals->deal_priority_id = 1;
                $deals->deal_status_id = 1;

                if ($resultTransaction = $deals->save()) {
                    if (isset($_POST['Deals']['Labels'])) {
                        $labelsOper = $_POST['Deals']['Labels'];
                        foreach ($labelsOper as $id => $oper) {
                            if ($oper == 1) {
                                $addLabel = new LabelsInDeals();
                                $addLabel->deal_id = $deals->id;
                                $addLabel->label_id = $id;
                                $resultTransaction = true;
                                if (!$addLabel->save()) {
                                    $resultTransaction = false;
                                    break;
                                }

                            }
                        }

                    }

                    if (isset($_POST['StepsInDeals'])) {
                        $selectedSteps->deals_id = $deals->id;
                      if (!$selectedSteps->save()) {
                            $resultTransaction = false;
                        }
                    }
                }

                if ($resultTransaction) {
                    $transaction->commit();
                    if (array_key_exists('save_and_create', $_POST)) {
                        $this->redirect(array('new_deal', 'id' => $deals->client_id));
                    }
                    $this->redirect(array('edit_deal', 'id' => $deals->id, 'isSuccessSave' => true));
                } else {
                    $transaction->rollback();
                }
            }
        }

        $this->render('new_deal', array
            (
            'deals' => $deals,
            'user' => $user,
            'client' => $client,
            'allLabels' => $allLabels ?? [],
            'customSelectedLabels' => $customSelectedLabels ?? [],
            'userRight' => $userRight,
            'listStep' => $listStep,
            'listStepOption' => $listStepOption,
            'selectedSteps' => $selectedSteps
            )
        );
    }

    public function actionEdit_client($id)
    {
        if (!$client = Clients::model()->findByPk($id)) {
            throw new CHttpException(404, 'Контакт не найден');
        }
        $oldClientName = $client->name;
        $resultTransaction = false;
        $transaction = Yii::app()->db->beginTransaction();
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        $changeUpdateDate = false;
        $isShowBlockSave = false;
        try {
            $userRight = UserRight::model()->find('user_id = ' . Yii::app()->user->id);
            $additionalFiledValuesInClient = Yii::app()->commonFunction->getValueAdditionalFiled($client, $user);
            // метки
            if ($labelsType = LabelsType::model()->find('name = :NAME', [':NAME' => 'clients'])) {
                $allLabels = Labels::model()->findAll('type_id = :ID', [':ID' => $labelsType->id]);
                $selectedLabels = LabelsInClients::model()->with('label')->findAll('client_id = :ID', [':ID' => $client->id]);
                if (isset($_POST['Clients']['Labels']) && $postLabelList = $_POST['Clients']['Labels']) {
                    foreach ($allLabels as $label) {
                        if ($postLabelList[$label->id] && $postLabelList[$label->id] == '1') {
                            $customSelectedLabels [$label->id] = $label;
                        }
                    }
                    // проверка на изменения
                    $enableLableList = [];
                    foreach ($postLabelList as $key => $enable) {
                        if ($enable == 1) {
                            $enableLableList [] = $key;
                        }
                    }
                    if ($selectedLabels && !$enableLableList || !$selectedLabels && $enableLableList) {
                        $changeUpdateDate = true;
                    } else {
                        if ($selectedLabels && $enableLableList) {
                            if (count($selectedLabels) == count($enableLableList)) {
                                foreach ($selectedLabels as $value) {
                                    if (!in_array($value->label_id, $enableLableList)) {
                                        $changeUpdateDate = true;
                                        unset($value);
                                        break;
                                    }
                                }
                            } else {
                                $changeUpdateDate = true;
                            }
                        }
                    }

                } else {
                    foreach ($selectedLabels as $label) {
                        $customSelectedLabels [$label->label->id] = $label->label;
                    }
                }
            }

            //этапы (1 - clients)
            $listStep = Steps::model()->findAll(['condition' => 'steps_type_id = :TYPE', 'order' => 'weight', 'params' => [':TYPE' => 1]]);
            if (!$selectedSteps = StepsInClients::model()->find('clients_id = :ID', [':ID' => $client->id])) {
                $selectedSteps = new StepsInClients();
            }

            if (isset($_POST['MainStepsInClients'])) {
                $selectedSteps->clients_id = $client->id;
                $selectedSteps->steps_id = $_POST['MainStepsInClients']['steps_id'] ?? $_POST['StepsInClients']['steps_id'];
                $oldOptionId = $selectedSteps->selected_option_id;
                $selectedSteps->selected_option_id = $selectedSteps->steps_id != 1 ? $_POST['MainStepsInClients']['selected_option_id'] : 0;

                if ($oldOptionId != $selectedSteps->selected_option_id) {
                    $changeUpdateDate = true;
                }
            }

            $listStepOption = [];
            foreach ($listStep as $steps) {
                if ($options = StepsOptions::model()->findAll(['condition' => 'steps_id = :ID', 'order' => 'weight', 'params' =>[':ID' => $steps->id]])) {
                    $listStepOption[$steps->id] = $options;
                }

                if (!$selectedSteps->steps_id && $steps->selected_default) {
                    $selectedSteps->steps_id = $steps->id;
                    $oldOptionId = $selectedSteps->selected_option_id;
                    $selectedSteps->selected_option_id = $options[0]->id;

                    if ($oldOptionId != $selectedSteps->selected_option_id) {
                        $changeUpdateDate = true;
                    }
                }
            }

            $errorAddField = false;
            $errorAddFieldText = '';
            $additionalFiledValue = new AdditionalFieldsValues();
             
            if (Clients::checkAccess($client, $user)) {
                if (isset($_POST['Clients'])) {
                    if ($user->roles[0]->name == 'admin' || $userRight->create_client == 1) {
                        $oldResponsibleId = $client->responsable_id;
                        if ($_POST['type'] == 'i') {
                            $client->responsable_id = Yii::app()->user->id;
                        } elseif ($_POST['type'] == 'manager') {
                            $client->responsable_id = $_POST['Clients']['manager_id'];
                        } elseif ($_POST['type'] == 'director') {
                            $client->responsable_id = $_POST['Clients']['director_id'];
                        } elseif ($_POST['type'] == 'no') {
                            $admin = new Users();
                            $admin_id = $admin->getAdminId();
                            $client->responsable_id = $admin_id;
                        }
                        if ($oldResponsibleId != $client->responsable_id) {
                            $changeUpdateDate = true;
                        }
                    }
                    $client->attributes = $_POST['Clients'];
                    if (!$additionalFiledValue = AdditionalFieldsValues::model()->find('client_id = :client', [':client' => $client->id])) {
                        $additionalFiledValue = new AdditionalFieldsValues();
                        $additionalFiledValue->client_id = $client->id;
                    }
                    if (isset($_POST['Clients']['additionalField'])) {
                        foreach ($_POST['Clients']['additionalField'] as $addFieldName => $addFieldValue) {

                            if ($additionalFiledValue->$addFieldName != $addFieldValue) {
                                $addField = AdditionalFields::model()->find('table_name = :TN', [':TN' => $addFieldName]);
                                if ($addField->type == 'date') {
                                    if ($addFieldValue != '') {
                                        $addFieldValue = $date = date('d.m.Y', strtotime($addFieldValue));
                                    }
                                    if ($additionalFiledValue->$addFieldName == '') {
                                        if ($additionalFiledValue->$addFieldName != $addFieldValue) {
                                            $changeUpdateDate = true;
                                        }
                                    } else {
                                        $date = date('d.m.Y', $additionalFiledValue->$addFieldName);
                                        if ($date != $addFieldValue) {
                                            $changeUpdateDate = true;
                                        }
                                    }
                                } else {
                                    $changeUpdateDate = true;
                                }
                            }
                            $additionalFiledValue->$addFieldName = $addFieldValue;
                            if ($addFieldName == 'fieldFio') {
                                $client->name = $addFieldValue;
                            }
                        }
                    }
                    $additionalFiledValue = Yii::app()->commonFunction->convertAdditionalField($additionalFiledValue, $additionalFiledValuesInClient);
                    if ($changeUpdateDate) {
                        $client->change_client_date = date('Y-m-d H:i');
                    }

                    if ($resultTransaction = $client->save()) {
                        if (isset($_POST['Clients']['additionalField'])) {
                            $saveAddField = Yii::app()->commonFunction->checkAdditionalFiledValue($additionalFiledValue, $additionalFiledValuesInClient);
                            if ($saveAddField['status'] == 'success') {
                                $resultTransaction = $additionalFiledValue->save();
                            } elseif($saveAddField['status'] == 'failed') {
                                $errorAddField = true;
                                $errorAddFieldText = '';
                                foreach ($saveAddField['type'] as $type) {
                                    switch ($type) {
                                        case 'incorrectData':
                                            $errorAddFieldText.= 'Некорректное значение в поле.';
                                            break;
                                        case 'requiredData':
                                            $errorAddFieldText.= 'Пропущено обязательное поле';
                                            break;
                                        case 'uniqueData':
                                            foreach ($saveAddField['fieldsError'] as $key => $fieldError) {
                                                $errorAddFieldText .= '"' . $fieldError . '"' . (count($saveAddField['fieldsError']) != $key +1 ? ', ' : '');
                                            }
                                            $errorAddFieldText.= ' - у другого контакта уже есть такое значение. Заполните поле другими данными';
                                            break;
                                    }
                                    $errorAddFieldText.= '<br>';
                                }
                                $resultTransaction = false;
                            }
                        }

                        if (isset($_POST['MainStepsInClients'])) {
                            if (!$selectedSteps->save()) {
                                $resultTransaction = false;
                            }
                        }

                        if (isset($_POST['Clients']['Labels'])) {
                            $labelsOper = $_POST['Clients']['Labels'];
                            foreach ($labelsOper as $id => $oper) {
                                if ($oper == 1) {
                                    $addLabel = new LabelsInClients();
                                    $addLabel->client_id = $client->id;
                                    $addLabel->label_id = $id;
                                    if ($addLabel->checkDuplicate($id, $client->id)) {
                                        if (!$addLabel->save()) {
                                            $resultTransaction = false;
                                            break;
                                        }
                                    }
                                } elseif ($oper == 0) {
                                    LabelsInClients::model()->deleteAll('client_id = :CID && label_id = :LID',
                                        [
                                            ':CID' => $client->id,
                                            ':LID' => $id
                                        ]
                                    );
                                }
                            }
                        }

                    }
                    if ($resultTransaction) {
                        $transaction->commit();
                        $isShowBlockSave = true;
                        if (array_key_exists('save_and_create', $_POST)) {
                            $this->redirect(array('new_client'));
                        } else {
                            $this->redirect(array('client_profile', 'id' => $client->id));
                        }
                    } else {
                        $client->name = $oldClientName;
                        $transaction->rollback();
                    }
                }
            } else {
                throw new CHttpException(412, 'no_access_client');
            }
        } catch (Exception $e) {
            $errorAddField = true;
            $errorAddFieldText = 'Ошибка сервера';
            $transaction->rollback();
            //Непонятно, зачем кто-то сделал чтобы отлавливались все исключения
            // когда некоторые кастомные?! Вообщем, костыль
            if ($e->statusCode == 412) {
                throw new CHttpException(412, 'no_access_client');
            }
        }
        $this->render('edit_client', array(
            'client' => $client,
            'user' => $user,
            'userRight' => $userRight,
            'additionalFiledValuesInClient' => $additionalFiledValuesInClient,
            'errorAddField' => $errorAddField,
            'errorAddFieldText' => $errorAddFieldText,
            'additionalFiledValue' => $additionalFiledValue,
            'allLabels' => $allLabels ?? [],
            'customSelectedLabels' => $customSelectedLabels ?? [],
            'listStepOption' => $listStepOption,
            'listStep' => $listStep,
            'selectedSteps' => $selectedSteps,
            'isShowBlockSave' => $isShowBlockSave,
            ));
    }

    public function actionAdditional_field_section_edit($id)
    {

        $user = Users::model()->with(['roles', 'userRights', 'userInGroups'])->findByPk(Yii::app()->user->id);
        $transaction = $user->getDbConnection()->beginTransaction();
        if ($additionalFiledSelect = AdditionalFieldsSection::model()->with(['sectorInGroups'])->findByPk($id)) {
            if ($user->roles[0]->name == 'admin' || ($user->userRights[0]->create_field
                    && Yii::app()->commonFunction->checkAccessFieldSection($user, $additionalFiledSelect))
            ) {
                try {
                    $isShowBlockSave = false;
                    $additionalFiledSelect->oldData = $additionalFiledSelect->attributes;
                    $firstSection = AdditionalFieldsSection::model()->findByPk(1);
                    $groupUser = UsersGroups::model()->findAll();
                    $allGroup = [];
                    foreach ($groupUser as $group) {
                        $allGroup[$group->id] = $group->name;
                    }
                    $oldGroupSector = [];
                    $thisGroupSector = SectorInGroups::model()->findAll('sector_id = :sector', [':sector' => $additionalFiledSelect->id]);
                    foreach ($thisGroupSector as $group) {
                        $additionalFiledSelect->data['group'][$group->group_id] = 1;
                        $oldGroupSector[$group->group_id] = $group->group_id;
                    }
                    if (isset($_POST['AdditionalFieldsSection'])) {
                        $additionalFiledSelect->attributes = $_POST['AdditionalFieldsSection'];
                        if (isset($_POST['AdditionalFieldsSection']['data'])) {
                            $additionalFiledSelect->data = $_POST['AdditionalFieldsSection']['data'];
                        }
                        if ($user->roles[0]->name == 'admin') {
                            $additionalFiledSelect->groups = $_POST['AdditionalFieldsSection']['data']['group'];
                            if ($additionalFiledSelect->access == 'groups') {
                                foreach ($_POST['AdditionalFieldsSection']['data']['group'] as $key => $group) {
                                    if ($group == 1) {
                                        if (!isset($oldGroupSector[$key])) {
                                            $newGroupSector = new SectorInGroups();
                                            $newGroupSector->sector_id = $additionalFiledSelect->id;
                                            $newGroupSector->group_id = $key;
                                            $newGroupSector->save();

                                        }
                                        unset($oldGroupSector[$key]);
                                    }
                                }
                                if (count($oldGroupSector) > 0) {
                                    $idGroupStr = implode(',', $oldGroupSector);
                                    SectorInGroups::model()->deleteAll("sector_id = :sector AND group_id in ($idGroupStr)",
                                        ['sector' => $additionalFiledSelect->id]);
                                }
                            } else {
                                SectorInGroups::model()->deleteAll('sector_id = :sector', [':sector' => $additionalFiledSelect->id]);
                            }
                        } else {
                            $userGroup = $user->userInGroups[0]->group_id;
                            $additionalFiledSelect->groups = [$userGroup => 1];
                        }

                        if ($additionalFiledSelect->save()) {
                            $transaction->commit();
                            $isShowBlockSave = true;
                        }
                    }
                } catch (Exception $e) {
                    $transaction->rollback();
                }
            } else {
                throw new CHttpException(412, 'no_access_settings');
            }
        } else {
            throw new CHttpException(404, 'not_found_page');
        }

        $this->render('additional_field_section_edit', array(
            'additionalFiledSelect' => $additionalFiledSelect, 'allGroup' => $allGroup, 'firstSection' => $firstSection,
            'user' => $user,
            'isShowBlockSave' => $isShowBlockSave
            ));
    }

    public function actionAdditional_field_section_create()
    {

        $user = Users::model()->with(['roles', 'userRights', 'userInGroups'])->findByPk(Yii::app()->user->id);
        $transaction = $user->getDbConnection()->beginTransaction();
        if ($user->roles[0]->name == 'admin' || $user->userRights[0]->create_field) {
            try {
                $additionalFiledSection = new AdditionalFieldsSection();
                $groupUser = UsersGroups::model()->findAll();
                $allGroup = [];
                foreach ($groupUser as $group) {
                    $allGroup[$group->id] = $group->name;
                }
                if (!isset($_POST['AdditionalFieldsSection'])) {
                    $endAddFieldSection = AdditionalFieldsSection::model()->find(['order' => 'weight DESC']);
                    if ($endAddFieldSection) {
                        $additionalFiledSection->weight = $endAddFieldSection->weight + 1;
                    } else {
                        $additionalFiledSection->weight = 1;
                    }
                }
                if (isset($_POST['AdditionalFieldsSection'])) {
                    $additionalFiledSection->attributes = $_POST['AdditionalFieldsSection'];
                    if ($user->roles[0]->name != 'admin') {
                        $userGroup = $user->userInGroups[0]->group_id;
                        $additionalFiledSection->access = 'groups';
                        $additionalFiledSection->groups = [$userGroup => 1];
                    } else {
                        $additionalFiledSection->groups = $_POST['AdditionalFieldsSection']['data']['group'];
                    }

                    if ($additionalFiledSection->save()) {
                        if ($additionalFiledSection->access == 'groups') {
                            foreach ($additionalFiledSection->groups as $key => $group) {
                                if ($group == 1) {
                                    $newGroupSector = new SectorInGroups();
                                    $newGroupSector->sector_id = $additionalFiledSection->id;
                                    $newGroupSector->group_id = $key;
                                    $newGroupSector->save();
                                }
                            }
                        }
                        $transaction->commit();
                        Yii::app()->user->setFlash('create_section', 'Новый раздел: ' . $additionalFiledSection->name);
                        $this->redirect(['settings_additional_field']);
                    }
                }
            } catch (Exception $e) {
                $transaction->rollback();
            }
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }

        $this->render('additional_field_section_create', array(
            'additionalFiledSelect' => $additionalFiledSection, 'allGroup' => $allGroup, 'user' => $user));
    }

    public function actionAdditional_field_section_delete_transfer($id)
    {

        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        if ($additionalFiledSelect = AdditionalFieldsSection::model()->findByPk($id)) {
            if ($user->roles[0]->name == 'admin' || ($user->userRights[0]->delete_section
                && Yii::app()->commonFunction->checkAccessFieldSection($user, $additionalFiledSelect))
            ) {
                $transaction = $additionalFiledSelect->getDbConnection()->beginTransaction();
                try {
                    $nameNewSectionField = [];
                    $fieldSections = AdditionalFields::model()->findAll(['select' => ['weight', 'name'], 'condition' => 'section_id = 1', 'order' => 'weight DESC']);
                    if ($fieldSections) {
                        $lastWeight = $fieldSections[0]->weight;
                        foreach ($fieldSections as $field) {
                            $nameNewSectionField[] = $field->name;
                        }
                        $additionalFields = AdditionalFields::model()->findAll('section_id = :section',
                            [':section' => $id]);
                        foreach ($additionalFields as $additionalField) {
                            $double = true;
                            while ($double) {
                                if (in_array($additionalField->name, $nameNewSectionField)) {
                                    $additionalField->name .= '#';
                                } else {
                                    $double = false;
                                }
                            }
                            $lastWeight+= 1;
                            $additionalField->weight = $lastWeight;
                            $additionalField->save();
                        }
                    }
                    AdditionalFields::model()->updateAll(['section_id' => 1], 'section_id = :section', [':section' => $id]);
                    if ($additionalFiledSelect->delete()) {
                        $transaction->commit();
                    }
                } catch (Exception $e) {
                    $transaction->rollback();
                }
            } else {
                throw new CHttpException(412, 'no_access_settings');
            }
        } else {
            throw new CHttpException(404, 'Раздел не найден');
        }
        $this->redirect(['settings_additional_field']);
    }

    public function actionAdditional_field_section_delete($id)
    {

        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        if ($additionalFiledSelect = AdditionalFieldsSection::model()->findByPk($id)) {
            if ($user->roles[0]->name == 'admin' || ($user->userRights[0]->delete_section && $user->userRights[0]->delete_field
                    && Yii::app()->commonFunction->checkAccessFieldSection($user, $additionalFiledSelect))
            ) {
                $transaction = $additionalFiledSelect->getDbConnection()->beginTransaction();
                try {
                    foreach (AdditionalFields::model()->findAll('section_id = :section', [':section' => $id]) as $field) {
                        Yii::app()->db->createCommand(" ALTER TABLE `additional_fields_values` DROP `$field->table_name`")->query();
                    }
                    if ($additionalFiledSelect->delete()) {
                        $transaction->commit();
                    }
                } catch (Exception $e) {
                    $transaction->rollback();
                }
            } else {
                throw new CHttpException(412, 'no_access_settings');
            }
        } else {
            throw new CHttpException(404, 'Раздел не найден');
        }
        $this->redirect(['settings_additional_field']);
    }

    public function actionUsers_groups_create()
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            $userGroups = new UsersGroups();
            if (isset($_POST['UsersGroups'])) {
                $userGroups->attributes = $_POST['UsersGroups'];
                if ($userGroups->save()) {
                    Yii::app()->user->setFlash('create_group','Группу "'. $userGroups->name . '" можно использовать');
                    $this->redirect(['settings_users_groups']);
                }
            }
            $this->render('new_users_groups', array(
                'userGroups' => $userGroups));
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionUsers_groups_edit($id)
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            $isShowBlockSave = false;
            $userGroups = UsersGroups::model()->findByPk($id);
            if ($userGroups) {
                if (isset($_POST['UsersGroups'])) {
                    $userGroups->attributes = $_POST['UsersGroups'];
                    if ($userGroups->save()) {
                        $isShowBlockSave = true;
                    }
                }
            } else {
                throw new CHttpException(404, 'Группа не найдена');
            }
            $this->render('edit_users_groups', array(
                'userGroups' => $userGroups,
                'isShowBlockSave' => $isShowBlockSave
                ));
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionUsers_groups_delete($id)
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);

        $transaction = $user->getDbConnection()->beginTransaction();
        if ($user->roles[0]->name == 'admin') {
            $userGroups = UsersGroups::model()->findByPk($id);
            if ($userGroups) {
                try {
                    if ($userGroups->delete()) {
                        $transaction->commit();
                    }
                } catch (Exception $e) {
                    $transaction->rollback();
                }
            } else {
                throw new CHttpException(404, 'Группа не найдена');
            }
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }

        $this->redirect(['settings_users_groups']);
    }


    public function actionDocument_client_page()
    {
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $userRight = Yii::app()->commonFunction->getUserRight($user);
        $documents = new ClientsFiles();
        if ($userRight['add_files_client']) {
            if (isset($_GET['ClientsFiles']) && isset($_GET['ClientsFiles']['search'])) {
                $documents->keyword = $_GET['ClientsFiles']['keyword'];
                $documents->start_date = $_GET['ClientsFiles']['start_date'];
                $documents->stop_date = $_GET['ClientsFiles']['stop_date'];
                $documents->client_group_id = $_GET['ClientsFiles']['client_group_id'] ?? null;

                if ($_GET['type'] == Yii::app()->user->id) {
                    $documents->responsable_id = Yii::app()->user->id;
                } elseif ($_GET['type'] == 'manager') {
                    $documents->responsable_id = $_GET['ClientsFiles']['manager_id'];
                } elseif ($_GET['type'] == 'director') {
                    $documents->responsable_id = $_GET['ClientsFiles']['director_id'];
                } elseif ($_GET['type'] == 'all') {
                    $documents->responsable_id = 'all';
                } elseif ($_GET['type'] == 'no') {
                    $documents->responsable_id = 'no';
                } elseif ($_GET['type'] == 'admin') {
                    $documents->responsable_id = $user->parent_id;
                }
            }
            $documentClient = $documents->search();
            $this->render('document_client_page', array('user' => $user, 'documentClient' => $documentClient,
                'userRight' => $userRight, 'documents' => $documents));
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionDocument_action_page()
    {
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $userRight = Yii::app()->commonFunction->getUserRight($user);
        $documents = new ActionsFiles();
        if ($userRight['add_files_action']) {
            if(isset($_GET['ActionsFiles']) && isset($_GET['ActionsFiles']['search'])) {
                $documents->keyword = $_GET['ActionsFiles']['keyword'];
                $documents->start_date = $_GET['ActionsFiles']['start_date'];
                $documents->stop_date = $_GET['ActionsFiles']['stop_date'];
                $documents->client_group_id = $_GET['ActionsFiles']['client_group_id'] ?? null;

                if($_GET['type'] == Yii::app()->user->id){
                    $documents->responsable_id = Yii::app()->user->id;
                } elseif($_GET['type'] == 'manager'){
                    $documents->responsable_id = $_GET['ActionsFiles']['manager_id'];
                } elseif($_GET['type'] == 'director'){
                    $documents->responsable_id = $_GET['ActionsFiles']['director_id'];
                } elseif ($_GET['type'] == 'all'){
                    $documents->responsable_id = 'all';
                } elseif ($_GET['type'] == 'no'){
                    $documents->responsable_id = 'no';
                } elseif ($_GET['type'] == 'admin'){
                    $documents->responsable_id = $user->parent_id;
                }
            }
            $documentAction = $documents->search();
            $this->render('document_action_page', array('user' => $user, 'documentAction' => $documentAction,
                'userRight' => $userRight, 'documents' => $documents));
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionDocument_deal_page()
    {
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $userRight = Yii::app()->commonFunction->getUserRight($user);
        $documents = new DealsFiles();
        if ($userRight['add_files_deal']) {
            if(isset($_GET['DealsFiles']) && isset($_GET['DealsFiles']['search'])) {
                $documents->keyword = $_GET['DealsFiles']['keyword'];
                $documents->start_date = $_GET['DealsFiles']['start_date'];
                $documents->stop_date = $_GET['DealsFiles']['stop_date'];
                $documents->client_group_id = $_GET['DealsFiles']['client_group_id'] ?? null;

                if($_GET['type'] == Yii::app()->user->id){
                    $documents->responsable_id = Yii::app()->user->id;
                } elseif($_GET['type'] == 'manager'){
                    $documents->responsable_id = $_GET['DealsFiles']['manager_id'];
                } elseif($_GET['type'] == 'director'){
                    $documents->responsable_id = $_GET['DealsFiles']['director_id'];
                } elseif ($_GET['type'] == 'all'){
                    $documents->responsable_id = 'all';
                } elseif ($_GET['type'] == 'no'){
                    $documents->responsable_id = 'no';
                } elseif ($_GET['type'] == 'admin'){
                    $documents->responsable_id = $user->parent_id;
                }
            }
            $documentDeal = $documents->search();
            $this->render('document_deal_page', array('user' => $user, 'documentDeal' => $documentDeal,
                'userRight' => $userRight, 'documents' => $documents));
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionDocument_user_page()
    {
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $userRight = Yii::app()->commonFunction->getUserRight($user);
        $documents = new UsersFiles();
        if ($userRight['add_files_user']) {
            if(isset($_GET['UsersFiles']) && isset($_GET['UsersFiles']['search'])) {
                $documents->keyword = $_GET['UsersFiles']['keyword'];
                $documents->start_date = $_GET['UsersFiles']['start_date'];
                $documents->stop_date = $_GET['UsersFiles']['stop_date'];
                $documents->user_group = $_GET['UsersFiles']['user_group'] ?? null;
                $documents->type_user = $_GET['UsersFiles']['type_user'] ?? null;

            }
            $documentUser = $documents->search();
            $this->render('document_user_page', array('user' => $user, 'documentUser' => $documentUser,
                'userRight' => $userRight, 'documents' => $documents));

        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionUploadClientFile()
    {
        $fileSettings = Yii::app()->commonFunction->getFileSettings();
        Yii::import("ext.EAjaxUpload.qqFileUploader");
        $folder = Yii::getPathOfAlias('webroot') . '/uploads/';// folder for uploaded files
        $allowedExtensions = explode(',', str_replace(' ', '', $fileSettings['extFile']));//array("jpg","jpeg","gif","exe","mov" and etc...
        $sizeLimit = $fileSettings['sizeFile'] * 1024 * 1024;// maximum file size in bytes
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($folder);
        $fileName = $result['filename'];
        $info = new SplFileInfo($folder . $fileName);
        $ext = $info->getExtension();
        $salt = substr(md5(time() + rand() + rand()), 10, 10) . '-client-' . date('Y-m-d-H-i');
        $newName = $salt . '.' . $ext;
        copy($folder . $fileName, $folder . $newName);
        unlink($folder . $fileName);

        $file = new Files();
        $file->link = $newName;
        $file->name = $fileName;
        $file->date_upload = date('Y-m-d-H-i');
        $file->save();

        $fileClient = new ClientsFiles();
        $fileClient->client_id = $_GET['id'];
        $fileClient->file_id = $file->id;
        $fileClient->save();
        $result['fileId'] = $fileClient->id;
        $result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        echo $result;// it's array
    }

    public function actionUploadActionFile()
    {
        $fileSettings = Yii::app()->commonFunction->getFileSettings();
        Yii::import("ext.EAjaxUpload.qqFileUploader");
        $folder = Yii::getPathOfAlias('webroot') . '/uploads/';// folder for uploaded files
        $allowedExtensions = explode(',', str_replace(' ', '', $fileSettings['extFile']));//array("jpg","jpeg","gif","exe","mov" and etc...
        $sizeLimit = $fileSettings['sizeFile'] * 1024 * 1024;// maximum file size in bytes
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($folder);
        $fileName = $result['filename'];
        $info = new SplFileInfo($folder . $fileName);
        $ext = $info->getExtension();
        $salt = substr(md5(time() + rand() + rand()), 10, 10) . '-action-' . date('Y-m-d-H-i');
        $newName = $salt . '.' . $ext;
        copy($folder . $fileName, $folder . $newName);
        unlink($folder . $fileName);

        $file = new Files();
        $file->link = $newName;
        $file->name = $fileName;
        $file->date_upload = date('Y-m-d-H-i');
        $file->save();

        $fileAction = new ActionsFiles();
        $fileAction->action_id = $_GET['id'];
        $fileAction->file_id = $file->id;
        $fileAction->save();
        $result['fileId'] = $fileAction->id;

        $result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);

        echo $result;// it's array
    }

    public function actionUploadDealFile()
    {
        $fileSettings = Yii::app()->commonFunction->getFileSettings();
        Yii::import("ext.EAjaxUpload.qqFileUploader");
        $folder = Yii::getPathOfAlias('webroot') . '/uploads/';// folder for uploaded files
        $allowedExtensions = explode(',', str_replace(' ', '', $fileSettings['extFile']));//array("jpg","jpeg","gif","exe","mov" and etc...
        $sizeLimit = $fileSettings['sizeFile'] * 1024 * 1024;// maximum file size in bytes
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($folder);
        $fileName = $result['filename'];
        $info = new SplFileInfo($folder . $fileName);
        $ext = $info->getExtension();
        $salt = substr(md5(time() + rand() + rand()), 10, 10) . '-deal-' . date('Y-m-d-H-i');
        $newName = $salt . '.' . $ext;
        copy($folder . $fileName, $folder . $newName);
        unlink($folder . $fileName);

        $file = new Files();
        $file->link = $newName;
        $file->name = $fileName;
        $file->date_upload = date('Y-m-d-H-i');
        $file->save();

        $fileDeal = new DealsFiles();
        $fileDeal->deal_id = $_GET['id'];
        $fileDeal->file_id = $file->id;
        $fileDeal->save();
        $result['fileId'] = $fileDeal->id;
        $result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);

        echo $result;// it's array
    }

    public function actionUploadUserFile()
    {
        $fileSettings = Yii::app()->commonFunction->getFileSettings();
        Yii::import("ext.EAjaxUpload.qqFileUploader");
        $folder = Yii::getPathOfAlias('webroot') . '/uploads/';// folder for uploaded files
        $allowedExtensions = explode(',', str_replace(' ', '', $fileSettings['extFile']));//array("jpg","jpeg","gif","exe","mov" and etc...
        $sizeLimit = $fileSettings['sizeFile'] * 1024 * 1024;// maximum file size in bytes
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($folder);
        $fileName = $result['filename'];
        $info = new SplFileInfo($folder . $fileName);
        $ext = $info->getExtension();
        $salt = substr(md5(time() + rand() + rand()), 10, 10) . '-user-' . date('Y-m-d-h-i');
        $newName = $salt . '.' . $ext;
        copy($folder . $fileName, $folder . $newName);
        unlink($folder . $fileName);

        $file = new Files();
        $file->link = $newName;
        $file->name = $fileName;
        $file->date_upload = date('Y-m-d-H-i');
        $file->save();

        $fileUser = new UsersFiles();
        $fileUser->user_id = $_GET['id'];
        $fileUser->file_id = $file->id;
        $fileUser->save();
        $result['fileId'] = $fileUser->id;

        $result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);

        echo $result;// it's array
    }

    public function actionClient_document_delete($id, $return = 'card')
    {
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $userRight = Yii::app()->commonFunction->getUserRight($user);
        $file = ClientsFiles::model()->with(['file', 'client'])->findByPk($id);
        if ($userRight['role'] == 'admin' || ($userRight['delete_files_client'] && Clients::checkAccess($file->client, $user))) {
            $folder = Yii::getPathOfAlias('webroot') . '/uploads/';
            @unlink($folder . $file->file->link);
            Files::model()->deleteAll('id = :id', [':id' => $file->file_id]);
        }
        if ($return == 'table') {
            $this->redirect(array('document_client_page'));
        } else {
            $this->redirect(array('client_profile', 'id' => $file->client->id));
        }
    }

    public function actionAction_document_delete($id, $return = 'card')
    {
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $userRight = Yii::app()->commonFunction->getUserRight($user);
        $file = ActionsFiles::model()->with(['file', 'action'])->findByPk($id);
        if ($userRight['role'] == 'admin' || ($userRight['delete_files_action'] && Actions::checkAccess($file->action, $user))) {
            $folder = Yii::getPathOfAlias('webroot') . '/uploads/';
            @unlink($folder . $file->file->link);
            Files::model()->deleteAll('id = :id', [':id' => $file->file_id]);
        }
        if ($return == 'table') {
            $this->redirect(array('document_action_page'));
        } else {
            $this->redirect(array('edit_action', 'id' => $file->action->id));
        }
    }

    public function actionDeal_document_delete($id, $return = 'card')
    {
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $userRight = Yii::app()->commonFunction->getUserRight($user);
        $file = DealsFiles::model()->with(['file', 'deal'])->findByPk($id);
        if ($userRight['role'] == 'admin' || ($userRight['delete_files_action'] && Deals::checkAccess($file->deal, $user))) {
            $folder = Yii::getPathOfAlias('webroot') . '/uploads/';
            @unlink($folder . $file->file->link);
            Files::model()->deleteAll('id = :id', [':id' => $file->file_id]);
        }
        if ($return == 'table') {
            $this->redirect(array('document_deal_page'));
        } else {
            $this->redirect(array('edit_deal', 'id' => $file->deal->id));
        }
    }

    public function actionUser_document_delete($id, $return = 'card')
    {
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $userRight = Yii::app()->commonFunction->getUserRight($user);
        $file = UsersFiles::model()->with(['file', 'user'])->findByPk($id);
        if ($userRight['role'] == 'admin' || ($userRight['delete_files_user'] && Users::checkAccessUser($userRight['role'], $file->user, $user))) {
            $folder = Yii::getPathOfAlias('webroot') . '/uploads/';
            @unlink($folder . $file->file->link);
            Files::model()->deleteAll('id = :id', [':id' => $file->file_id]);
        }
        if ($return == 'table') {
            $this->redirect(array('document_user_page'));
        } else {
            $this->redirect(array('user_profile', 'id' => $file->user->id));
        }
    }

    public function actionGet_file_client($id)
    {
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $userRight = Yii::app()->commonFunction->getUserRight($user);
        $fileClient = ClientsFiles::model()->with(['file', 'client'])->findByPk($id);
        if ($fileClient) {
            if ($userRight['role'] == 'admin' || Clients::checkAccess($fileClient->client, $user)) {
                $folder = Yii::getPathOfAlias('webroot') . '/uploads/' . $fileClient->file->link;
                if (file_exists($folder)) {
                    $file = fopen($folder, 'rb');
                    $mime = mime_content_type($file);
                    $size = filesize($folder);
                    header('Content-Type: ' . $mime);
                    header('Content-Size: ' . $size);
                    header('Content-Disposition: attachment; filename="' . $fileClient->file->name . '"');
                    fpassthru($file);
                    exit;
                } else {
                    throw new CHttpException(404);
                }
            } else {
                throw new CHttpException(412, 'no_access_file');
            }
        } else {
            throw new CHttpException(404);
        }
    }


    public function actionGet_file_action($id)
    {
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $userRight = Yii::app()->commonFunction->getUserRight($user);
        $fileAction = ActionsFiles::model()->with(['file', 'action'])->findByPk($id);
        if ($fileAction) {
            if ($userRight['role'] == 'admin' || Actions::checkAccess($fileAction->action, $user)) {
                $folder = Yii::getPathOfAlias('webroot') . '/uploads/' . $fileAction->file->link;
                if (file_exists($folder)) {
                    $file = fopen($folder, 'rb');
                    $mime = mime_content_type($file);
                    $size = filesize($folder);
                    header('Content-Type: ' . $mime);
                    header('Content-size: ' . $size);
                    header('Content-Disposition: attachment; filename="' . $fileAction->file->name . '"');
                    fpassthru($file);
                    exit;
                } else {
                    throw new CHttpException(404);
                }
            } else {
                throw new CHttpException(412, 'no_access_file');
            }
        } else {
            throw new CHttpException(404);
        }
    }

    public function actionGet_file_deal($id)
    {
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $userRight = Yii::app()->commonFunction->getUserRight($user);
        $fileDeal = DealsFiles::model()->with(['file', 'deal'])->findByPk($id);
        if ($fileDeal) {
            if ($userRight['role'] == 'admin' || Deals::checkAccess($fileDeal->deal, $user)) {
                $folder = Yii::getPathOfAlias('webroot') . '/uploads/' . $fileDeal->file->link;
                if (file_exists($folder)) {
                    $file = fopen($folder, 'rb');
                    $mime = mime_content_type($file);
                    $size = filesize($folder);
                    header('Content-Type: ' . $mime);
                    header('Content-size: ' . $size);
                    header('Content-Disposition: attachment; filename="' . $fileDeal->file->name . '"');
                    fpassthru($file);
                    exit;
                } else {
                    throw new CHttpException(404);
                }
            } else {
                throw new CHttpException(412, 'no_access_file');
            }
        } else {
            throw new CHttpException(404);
        }
    }

    public function actionGet_file_user($id)
    {
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $userRight = Yii::app()->commonFunction->getUserRight($user);
        $fileUser = UsersFiles::model()->with(['file', 'user'])->findByPk($id);
        if ($fileUser) {
            if ($userRight['role'] == 'admin' || Users::checkAccessUser($userRight['role'], $fileUser->user, $user)) {
                $folder = Yii::getPathOfAlias('webroot') . '/uploads/' . $fileUser->file->link;
                if (file_exists($folder)) {
                    $file = fopen($folder, 'rb');
                    $mime = mime_content_type($file);
                    $size = filesize($folder);
                    header('Content-Type: ' . $mime);
                    header('Content-size: ' . $size);
                    header('Content-Disposition: attachment; filename="' . $fileUser->file->name . '"');
                    fpassthru($file);
                    exit;
                } else {
                    throw new CHttpException(404);
                }
            } else {
                throw new CHttpException(412, 'no_access_file');
            }
        } else {
            throw new CHttpException(404);
        }
    }


    public function actionLabels_delete ($id, $type) {
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $right = Yii::app()->commonFunction->getUserRight($user);

        if (Yii::app()->commonFunction->checkAccessDeleteLabel($right, $type)) {
            if ($deleteLabel = Labels::model()->with('type')->findByPk($id)) {
                $right = 'delete_label_' . $deleteLabel->type->name;
                if ($right && $user->userRights[0]->$right){
                    $deleteLabel->delete();
                }else {
                    throw new CHttpException(412, 'no_access_settings');
                }
            } else {
                throw new CHttpException(404, 'Метка не найдена в базе данных');
            }
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
        $this->redirect(['settings_labels', ['type' => $type]]);
    }

    public function actionSettings_labels()
    {
        $user = Users::model()->with('roles', 'userRights')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin' || $user->userRights[0]->create_label_clients
            || $user->userRights[0]->create_label_deals || $user->userRights[0]->create_label_actions) {
            $listTabs = [];
            if ($user->userRights[0]->create_label_clients) {
                $listTabs ['clients'] = 'Контакты';
            }
            if ($user->userRights[0]->create_label_actions) {
                $listTabs ['actions'] = 'Задачи';
            }
            if ($user->userRights[0]->create_label_deals) {
                $listTabs ['deals'] = 'Сделки';
            }
            if (count($listTabs) == 0) {
                throw new CHttpException(412, 'no_access_settings');
            }
            $labelsGroupByType = Labels::model()->findAll((new Labels())->countTypes());
            $dateCountType = [];
            foreach ($labelsGroupByType as $value) {
                $dateCountType[$value->type->name] = $value->countType;
            }

            $this->render('settings_labels',
                [
                    'user' => $user,
                    'dataLabelsForClients' => (new Labels())->search('clients'),
                    'dataLabelsForActions' => (new Labels())->search('actions'),
                    'dataLabelsForDeals' => (new Labels())->search('deals'),
                    'dateCountType' => $dateCountType,
                    'listTabs' => $listTabs,
                    'labelsClients' => (new LabelsInClients())->search(),
                    'labelsActions' => (new LabelsInActions())->search(),
                    'labelsDeals' => (new LabelsInDeals())->search(),
                    'typeLabels' => $_GET[0]['type'] ?? null
                ]
            );
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionNew_label($type)
    {
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $right = Yii::app()->commonFunction->getUserRight($user);
        if (Yii::app()->commonFunction->checkAccessEditLabel($right, $type))  {
            if ($labelType = LabelsType::model()->find('name = :name', [':name' => $type])) {
                $right = 'create_label_' . $labelType->name;
                if ($right && $user->userRights[0]->$right){
                    $newLabel = new Labels();
                    if ((isset($_POST['Labels'])) && $params = $_POST['Labels']) {
                        $newLabel->attributes = $params;
                        $newLabel->type_id = $labelType->id;
                        if ($newLabel->save()) {
                            Yii::app()->user->setFlash('create_label','Метку "'. $newLabel->name . '" можно использовать');
                            $this->redirect(['settings_labels', ['type' => $type]]);
                        }
                    } else {
                        $newLabel->type_id = $labelType->id;
                        //дефолтные значения
                        $newLabel->color = '#3E55B2';
                    }

                    $this->render('new_label',
                        [
                            'user' => $user,
                            'newLabel' => $newLabel,
                            'listColors' => $this->colors,
                            'listTextColors' => $this->textColors
                        ]
                    );
                }else {
                    throw new CHttpException(412, 'no_access_settings');
                }
            } else {
                throw new CHttpException(404, 'Тип метки не найден в базе данных');
            }
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionEdit_label ($id, $type) {
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $right = Yii::app()->commonFunction->getUserRight($user);
        if (Yii::app()->commonFunction->checkAccessEditLabel($right, $type)) {
            if ($editLabel = Labels::model()->with('type')->findByPk($id)) {
                $right = 'create_label_' . $editLabel->type->name;
                $rightDelete ='delete_label_' . $editLabel->type->name;
                if ($right && $user->userRights[0]->$right){
                    if ((isset($_POST['MainLabels'])) && $params = $_POST['MainLabels']) {
                        $editLabel->attributes = $params;
                        if ($editLabel->validate() && $editLabel->update()) {
                            $isSuccessSave = true;
                        }
                    }
                    $this->render('edit_label',
                        [
                            'user' => $user,
                            'editLabel' => $editLabel,
                            'listColors' => $this->colors,
                            'listTextColors' => $this->textColors,
                            'accessDelete' => $user->userRights[0]->$rightDelete == 1,
                            'isSuccessSave' => $isSuccessSave ?? false,
                            'type' => $type
                        ]
                    );
                }else {
                    throw new CHttpException(412, 'no_access_settings');
                }
            } else {
                throw new CHttpException(404, 'Метка не найдена в базе данных');
            }
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionSettings_steps()
    {
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $right = Yii::app()->commonFunction->getUserRight($user);
        if ($right['role'] === 'admin' || $right['create_steps'] === 1) {
            $listTabs = [
                'clients' => 'Контакты',
                'deals' => 'Сделки'
            ];
            $stepsGroupByType = Steps::model()->findAll((new Steps())->countTypes());
            $dataCountType = [];
            foreach ($stepsGroupByType as $value) {
                $dataCountType[$value->stepsType->name] = $value->countType;
            }

            $this->render('settings_steps',
                [
                    'user' => $user,
                    'dataCountType' => $dataCountType,
                    'dataStepsForClients' => (new Steps())->search('clients'),
                    'dataStepsForDeals' => (new Steps())->search('deals'),
                    'listTabs' => $listTabs,
                    'typeSteps' => $_GET[0]['type'] ?? null
                ]
            );
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionNew_step($type)
    {
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $right = Yii::app()->commonFunction->getUserRight($user);
        $transaction = Yii::app()->db->beginTransaction();
        $isCommit = true;
        if ($right['role'] === 'admin' || $right['create_steps'] === 1) {
            if ($stepType = StepsType::model()->find('name = :name', [':name' => $type])) {
                $newStep = new Steps();
                $newStep->steps_type_id = $stepType->id;

                if ((isset($_POST['Steps'])) && $params = $_POST['Steps']) {
                    $newStep->attributes = $params;
                    if ($newStep->checkValidSelectValue($params['Select'])) {
                        if ($newStep->save()) {
                            if ($newStep->selected_default == 1) {
                                Steps::model()->updateAll(['selected_default' => 0], 'id !=' . $newStep->id . '&& selected_default = 1 && steps_type_id = ' . $newStep->steps_type_id);
                            }
                            foreach ($params['Select'] as $option) {
                                $newStepOptions = new StepsOptions();
                                $newStepOptions->attributes = $option;
                                $newStepOptions->steps_id = $newStep->id;
                                if (!$newStepOptions->save()) {
                                    $transaction->rollback();
                                    $isCommit = false;
                                    break;
                                }
                            }
                            if ($isCommit) {
                                $transaction->commit();
                                Yii::app()->user->setFlash('create_step','Воронку "'. $newStep->name . '" можно использовать');
                                $this->redirect(['settings_steps', ['type' => $type]]);
                            }
                        }
                    }
                    $selectOptions = [];
                    foreach ($params['Select'] as &$option) {
                        $option['weight'] = (int)$option['weight'];
                        $selectOptions [] = $option;
                    }
                    function sortWeight ($arr1, $arr2) {
                        return ($arr1['weight'] > $arr2['weight']);
                    }
                    uasort($selectOptions, 'sortWeight');

                } else {
                    // расчёт порядка взять по аналогии с доп. полями
                    $stepsMaxWeight= Steps::model()->find(
                        [
                            'condition' => 'steps_type_id = :typeId',
                            'order' => 'weight DESC',
                            'params' => [':typeId' => $stepType->id]
                        ]
                    );
                    $newStep->weight = $stepsMaxWeight ? $stepsMaxWeight->weight + 1 : 1;
                }

                $this->render('new_step',
                    [
                        'user' => $user,
                        'modelNewStep' => $newStep,
                        'selectOptions' => $selectOptions ?? [
                                [
                                    'name' => '',
                                    'color' => $this->colors[0],
                                    'weight' => '1'
                                ],
                                [
                                    'name' => '',
                                    'color' => $this->colors[1],
                                    'weight' => '2'
                                ]
                            ],
                        'listColor' => $this->colors
                    ]
                );
            } else {
                throw new CHttpException(404, 'Тип этапа не найден в базе данных');
            }
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionEdit_step($id, $type)
    {
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $right = Yii::app()->commonFunction->getUserRight($user);
        $transaction = Yii::app()->db->beginTransaction();
        $isCommit = true;
        $isShowBlockSave = false;
        function sortWeight ($arr1, $arr2) {
            return ($arr1['weight'] > $arr2['weight']);
        }

        if ($right['role'] === 'admin' || $right['create_steps'] === 1) {
            if ($stepType = StepsType::model()->find('name = :name', [':name' => $type])) {
                if ($editStep = Steps::model()->find('id = :ID && steps_type_id = :TYPE',
                    [':ID' => $id, ':TYPE' => $stepType->id])) {
                    $selectOptions = StepsOptions::model()->findAll(['condition' => 'steps_id = :ID',
                        'order' => 'weight', 'params' =>[':ID' => $editStep->id]]);
                    $useSelectOptions = [];
                    $model = $stepType->id == 1 ? 'StepsInClients' : 'StepsInDeals';
                    foreach ($selectOptions as $option) {
                        $count = $model::model()->count('selected_option_id = :SOID', [':SOID' => $option->id]);
                        if ($count) {
                            $useSelectOptions[$option->name] = $count;
                        }
                    }

                    if (isset($_POST['MainSteps']) && $params = $_POST['MainSteps']) {
                        $editStep->attributes = $params;
                        $editStep->isNewRecord = false;
                        $deleteIds = [];
                        if ($editStep->checkValidSelectValue($params['Select'])) {
                            if ($editStep->save()) {
                                if ($editStep->selected_default == 1) {
                                    Steps::model()->updateAll(['selected_default' => 0], 'id !=' . $editStep->id . '&& selected_default = 1 && steps_type_id = ' . $editStep->steps_type_id);
                                }

                                foreach ($params['Select'] as $option) {
                                    if (isset($option['id']) && $option['id']) {
                                        $editStepOption = StepsOptions::model()->findByPk($option['id']); 
                                        if (!$editStepOption) { 
                                            $editStepOption = new StepsOptions(); 
                                        } 
                                    } else { 
                                        $editStepOption = new StepsOptions(); 
                                    }

                                    $editStepOption->attributes = $option;
                                    $editStepOption->steps_id = $editStep->id;
                                    if (!$editStepOption->save()) {
                                        $transaction->rollback();
                                        $isCommit = false;
                                        break;
                                    }
                                }


                                // удаляем опции
                                $optionIds = array_column($params['Select'], 'id');
                                foreach ($selectOptions as $key => $option) {
                                    if (!in_array($option->id, $optionIds)) {
                                        $deleteIds [] = $option->id;
                                    }
                                }


                                if (count($deleteIds) > 0) {
                                    $editSelectOptions = [];
                                    foreach ($newSelectOptions = StepsOptions::model()->findAll('steps_id = :ID', [':ID' => $editStep->id]) as &$option) {
                                        $option['weight'] = (int)$option['weight'];
                                        $editSelectOptions [] = $option;
                                    }

                                    uasort($editSelectOptions, 'sortWeight');
                                    $editSelectOptions = array_values($editSelectOptions);
                                    // меняем удалённые опции на предыдущие по весу
                                    $id = 0;

                                    foreach ($newSelectOptions as $key => $option) {
                                        if (in_array($option->id, $deleteIds)) {
                                            foreach ($editSelectOptions as $editOption) {
                                                if ($editOption['weight'] <= $option->weight
                                                    && $option->id != $editOption->id && !in_array($editOption->id, $deleteIds)) {
                                                    $id = $editOption['id'];
                                                }
                                            }
                                            // если нет меньше по весу или равный, то берём наименьший
                                            if (!$id) {
                                                foreach ($editSelectOptions as $editOption) {
                                                    if (!in_array($editOption->id, $deleteIds)) {
                                                        $id = $editOption->id;
                                                        break;
                                                    }
                                                }
                                            }

                                            if ($stepType->id == 1) {
                                                //контакты
                                                StepsInClients::model()->updateAll(['selected_option_id' => $id], 'selected_option_id=' . $option->id);
                                            } else {
                                                StepsInDeals::model()->updateAll(['selected_option_id' => $id], 'selected_option_id=' . $option->id);
                                                //сделки
                                            }
                                        }
                                    }
                                    $stringIds = implode(',', $deleteIds);
                                    StepsOptions::model()->deleteAll("id in ($stringIds)");
                                }


                                if ($isCommit) {
                                    $transaction->commit();
                                    $isShowBlockSave = true;
                                }
                            }
                        }
                        $selectOptions = StepsOptions::model()->findAll(['condition' => 'steps_id = :ID',
                            'order' => 'weight', 'params' =>[':ID' => $editStep->id]]);
                    }

                    $this->render('edit_step',
                        [
                            'user' => $user,
                            'modelEditStep' => $editStep,
                            'selectOptions' => $selectOptions,
                            'listColor' => $this->colors,
                            'type' => $type,
                            'useSelectOptions' => $useSelectOptions,
                            'isShowBlockSave' => $isShowBlockSave
                        ]
                    );

                } else {
                    throw new CHttpException(404, 'Этап не найден в базе данных');
                }
            } else {
                throw new CHttpException(404, 'Тип этапа не найден в базе данных');
            }
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionSteps_delete($id, $type) {
        $user = Users::model()->with(['roles', 'userRights'])->findByPk(Yii::app()->user->id);
        $right = Yii::app()->commonFunction->getUserRight($user);
        if ($right['role'] === 'admin' || $right['delete_steps'] === 1) {
            $transaction = Yii::app()->db->beginTransaction();
            if($type == 'clients') {
                StepsInClients::model()->updateAll(['steps_id' => 1, 'selected_option_id' => 0], 'steps_id = ' . $id);
            } elseif ($type == 'deals') {
                StepsInDeals::model()->updateAll(['steps_id' => 2, 'selected_option_id' => 0], 'steps_id = ' . $id);
            } else {
                throw new CHttpException(404, 'Тип этапа не найден в базе данных');
            }
            if ($deleteStep = Steps::model()->findByPk($id)) {
                $deleteStep->delete();
                $transaction->commit();
            } else {
                $transaction->rollback();
                throw new CHttpException(404, 'Этап не найден в базе данных');
            }
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
        $this->redirect(['settings_steps', ['type' => $type]]);
    }

    public function actionDelete_client($id)
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        $right = Yii::app()->commonFunction->getUserRight($user);
        $client = Clients::model()->findByPk($id);
        if (Clients::checkAccess($client, $user)) {
            if ($right['role'] === 'admin' || $right['delete_client'] === 1) {
                if ($client = Clients::model()->findByPk($id)) {
                    $client->delete();
                    $this->redirect(array('clients_page'));
                }
            } else {
                throw new CHttpException(412, 'no_access_client');
            }
        }
    }

    public function actionClearFiles () {

	    $return = [
	        'status' => 'success',
            'error' => []
        ];

	    try {
            $files = Files::model()->findAll();
            $fileIdsDelete = '';
            $fileDBLinkList = [];
            foreach ($files as $file) {
                $addfile = true;
                if (!ClientsFiles::model()->find('file_id = ' . $file->id)) {
                    if (!UsersFiles::model()->find('file_id = ' . $file->id)) {
                        if (!ActionsFiles::model()->find('file_id = ' . $file->id)) {
                            if (!DealsFiles::model()->find('file_id = ' . $file->id)) {
                                $fileIdsDelete .= $file->id . ',';
                                $addfile = false;
                            }
                        }
                    }
                }
                if ($addfile) {
                    $fileDBLinkList [] = $file->link;
                }
            }

            if ($fileIdsDelete != '') {
                $fileIdsDelete = substr($fileIdsDelete, 0, -1);
                Files::model()->deleteAll('id in (' . $fileIdsDelete . ')');
            }

            $filesForDir = scandir('./uploads');
            $filesForDir = \array_diff($filesForDir, [".", "..", '.gitignore', '.htaccess']);

            foreach ($filesForDir as $link) {
                if (!in_array($link, $fileDBLinkList)) {
                    $pathDelete = './uploads/' . $link;
                    if (file_exists($pathDelete)) {
                        unlink($pathDelete);
                    }
                }
            }
        } catch (Exception $e) {
            $return['status'] = 'failed';
            $return['error'] = $e->getMessage();
        }
        echo json_encode($return);
    }
    
        
    #
    #функции масовых операций с пользователями      
    #
    
    
    
   #массовое изменение пользователей
    public function actionDel_clients () {   
        $clients = Yii::app()->request->getParam('rows') ;
        if (!empty($clients)) {
            $criteria = new CDbCriteria;
            $criteria->addInCondition('id', $clients);
            Clients::model()->deleteAll($criteria);
            Yii::app()->user->setFlash('success', Yii::app()->request->getParam('msg') );  
        }
    }

    public function actionEdit_clients () {   
        $clients = Yii::app()->request->getParam('clients') ;  
        
        if (!empty($clients)) {
            $data = Yii::app()->request->getParam('data') ;  
            $edited_fields = []; 
            $dates = []; 
            $fields = [];
            $save_data = [];
            foreach ($data as $key=>$val) {
                $name = str_replace(']','',$val['name']);
                $names  = explode('[',$name); 
                $value = $val['value'];
                if ($names[0]=='isEdit' and $value >0) {#поле менялось
                    $edited_fields[$names[1]]=1;
                }
                if ($names[0]=='isdate' and $value >0) {#поле дата
                    $dates[$names[1]]=1;
                }
                if ($names[0]=='Clients' and $names[1]=='editFields' ) {
                    $fields[ $names[2]]=$value;
                }
            }
           
            if (!empty($edited_fields)) {
                //var_dump($edited_fields);die;
                foreach ($edited_fields as $field_name=>$val) {
                    if (!empty($dates[$field_name])) {
                        $save_data[$field_name] = strtotime($fields[$field_name]) ;
                    } else {
                        $save_data[$field_name] = $fields[$field_name]; 
                    } 
                }
            }  
            $criteria = new CDbCriteria;
            $criteria->addInCondition('client_id', $clients); 
            AdditionalFieldsValues::model()->updateAll($save_data, $criteria);
            Yii::app()->user->setFlash('success', Yii::app()->request->getParam('msg') );
        } 
    }

    public function actionSet_step_clients () {   

        $clients = Yii::app()->request->getParam('clients') ;

        if (!empty($clients)) {
            $criteria = new CDbCriteria;
            $criteria->addInCondition('clients_id', $clients);  
            if (!empty($clients) and Yii::app()->request->getParam('step')){  
                $save_data = ['steps_id' => (int)Yii::app()->request->getParam('step'),
                    'selected_option_id'  =>  (int)Yii::app()->request->getParam('step_option')];   
                StepsInClients::model()->updateAll($save_data, $criteria);
            }        
            if (!empty($clients) and Yii::app()->request->getParam('step')==0) { 
                $save_data = ['steps_id' => 1,
                    'selected_option_id'  =>  0];     
                StepsInClients::model()->updateAll($save_data, $criteria);
            }
            Yii::app()->user->setFlash('success', Yii::app()->request->getParam('msg') );
        }
    }

    public function actionSet_label_clients () {   
        $clients = Yii::app()->request->getParam('clients') ;
        $labels = Yii::app()->request->getParam('levels_list') ;
        if (!$labels || !is_array($labels)) {
            return;
        }
        Yii::app()->user->setFlash('success', Yii::app()->request->getParam('msg') );
        if (!empty($clients)) {
            foreach ($clients as $client) {
                LabelsInClients::model()->deleteAll('client_id = :CID', [':CID' => $client ]  );
                foreach ($labels as $label) {
                    if ($label!='no') {
                        $addLabel = new LabelsInClients();
                        $addLabel->client_id = $client;
                        $addLabel->label_id = $label;
                        $addLabel->save();     
                    }  
                }
            }  
        }
        Yii::app()->user->setFlash('success', Yii::app()->request->getParam('msg') );
    }

    public function actionSet_master_clients () {  
        $user = Users::model()->findByPk(Yii::app()->user->id); 
        $clients = Yii::app()->request->getParam('clients') ; 
        if (!empty($clients)) { 
            $criteria = new CDbCriteria;
            $criteria->addInCondition('id', $clients);  
            $type =  Yii::app()->request->getParam('master');
            $save_data = ['responsable_id' => $type];

            if(is_numeric($type)){   
                $save_data['responsable_id'] = $type;
            }else{
                $save_data['responsable_id'] = Yii::app()->request->getParam('master_id') ;
            }   
            Clients::model()->updateAll($save_data, $criteria);  
            
            $criteria = new CDbCriteria;
            $criteria->addInCondition('client_id', $clients);  
            $is_event = Yii::app()->request->getParam('is_event') ;
            if ($is_event) { 
                if ($user->roles[0]->name == 'admin'  ) {# для директора меняем все задачи     
                    $query =    Actions::model()->updateAll($save_data, $criteria);

                } else {
                    $responsable_list = [Yii::app()->user->id, $user->parent_id] ;

                    if ($user->roles[0]->name == 'manager') {         
                        
                        $parent_user = Users::model()->findByPk($user->parent_id); 
                        
                        if($parent_user->roles[0]->name!= 'admin'){
                            $my_users =  Users::model()->findAll( 'parent_id = :ID', [':ID' => $user->parent_id]); 
                            foreach ($my_users as $my_user) {
                                $responsable_list[ ] = $my_user->id; 
                            }
                        }
                    } else {    
                        $my_users =  Users::model()->findAll('parent_id = :ID', [':ID' => Yii::app()->user->id]);
                        foreach ($my_users as $my_user) {
                            $responsable_list[ ] = $my_user->id;
                        }   
                    }
                    $criteria->addInCondition('responsable_id', $responsable_list);  
                    Actions::model()->updateAll($save_data, $criteria);
                }
            }
        }
        Yii::app()->user->setFlash('success', Yii::app()->request->getParam('msg') ); 
    }

    public function actionSet_action_clients () {      

        $clients = Yii::app()->request->getParam('clients') ;
        $labels = Yii::app()->request->getParam('lebels');  
        $user = Users::model()->findByPk(Yii::app()->user->id); 
        
        if (!empty($clients)) { 
            foreach ($clients as $client_id) {    
                $actions = new Actions(); 
                $actions->creation_date = date('Y-m-d H:i:s');
                $actions->client_id = $client_id; 
                $actions->responsable_id = Yii::app()->user->id;
                if ($_POST['type_id'] == 'manager_action') {
                    $actions->responsable_id = $_POST['manager_id'];
                } elseif ($_POST['type_id'] == 'director_action') {
                    $actions->responsable_id = $_POST['director_id'];
                }elseif ($_POST['type_id'] == 'no') {
                    $actions->responsable_id = $user->parent_id ;
                }

                if (strtotime($_POST['date'])) {
                    $actions->action_date = date('Y-m-d H:i', strtotime($_POST['date']));
                } else {
                    $actions->action_date = null;
                }
                $actions->text = $_POST['title'];
                $actions->description = $_POST['desc'];
                $actions->director_id =$_POST['director_id'];  
                                                  
                $actions->action_type_id = 3;
                $actions->action_priority_id = 1;   
                $actions->action_status_id = $_POST['status'];  
                $actions->save();
                if (!empty($labels)) {
                    foreach ($labels  as $label) {
                        $addLabel = new LabelsInActions();
                        $addLabel->action_id = $actions->id;
                        $addLabel->label_id = $label;
                        $addLabel->save();
                    }
                }
            }
        }    
        Yii::app()->user->setFlash('success', Yii::app()->request->getParam('msg') );  
    }
    
    
    #массовое изменение задач    
    public function actionDel_actions () {   
        $rows = Yii::app()->request->getParam('rows') ;
        if (!empty($rows)) {
            $criteria = new CDbCriteria;
            $criteria->addInCondition('id', $rows);
            Actions::model()->deleteAll($criteria);
            Yii::app()->user->setFlash('success', Yii::app()->request->getParam('msg') );  
        }
    }
    
    public function actionSet_levels_actions () {   
        $rows = Yii::app()->request->getParam('rows') ;
        $labels = Yii::app()->request->getParam('levels_list') ;
        if (!empty($rows)) { 
            foreach ($rows as $row) {
                LabelsInActions::model()->deleteAll('action_id = :CID', [':CID' => $row ]  ); 
                foreach ($labels as $label) {
                    if ($label!='no') {
                        $addLabel = new LabelsInActions();
                        $addLabel->action_id = $row;
                        $addLabel->label_id = $label;
                        $addLabel->save();     
                    }  
                }
            }  
        }
        Yii::app()->user->setFlash('success', Yii::app()->request->getParam('msg') );
        Yii::app()->user->setFlash('action_edit_success', Yii::app()->request->getParam('msg') );
    }  
    
    public function actionSet_master_actions () {  
        $user = Users::model()->findByPk(Yii::app()->user->id); 
        $rows = Yii::app()->request->getParam('rows') ; 
        if (!empty($rows)) { 
            $criteria = new CDbCriteria;
            $criteria->addInCondition('id', $rows);  
            $type =  Yii::app()->request->getParam('master');
            $save_data = ['responsable_id' => $type];

            if(is_numeric($type)){   
                $save_data['responsable_id'] = $type;
            }else{
                $save_data['responsable_id'] = Yii::app()->request->getParam('master_id') ;
            }   
            Actions::model()->updateAll($save_data, $criteria);  
             
        }
        Yii::app()->user->setFlash('success', Yii::app()->request->getParam('msg') ); 
    }
        
    public function actionSet_date_actions () {  
        $user = Users::model()->findByPk(Yii::app()->user->id); 
        $rows = Yii::app()->request->getParam('rows') ; 
        if (!empty($rows)) { 
            $criteria = new CDbCriteria;
            $criteria->addInCondition('id', $rows);  
            $type =  Yii::app()->request->getParam('master');
            $save_data = ['action_date' =>  date('Y-m-d H:i', strtotime(Yii::app()->request->getParam('date') ))];
            Actions::model()->updateAll($save_data, $criteria);              
        }
        Yii::app()->user->setFlash('success', Yii::app()->request->getParam('msg') ); 
    }
    
    public function actionSet_state_actions () {  
        $user = Users::model()->findByPk(Yii::app()->user->id); 
        $rows = Yii::app()->request->getParam('rows') ; 
        if (!empty($rows)) { 
            $criteria = new CDbCriteria;
            $criteria->addInCondition('id', $rows);  
            $type =  Yii::app()->request->getParam('master');
            $save_data = ['action_status_id' =>   Yii::app()->request->getParam('state')  ];
            Actions::model()->updateAll($save_data, $criteria);              
        }
        Yii::app()->user->setFlash('success', Yii::app()->request->getParam('msg') ); 
    }
    public function actionSet_action_actions () {      

        $clients = Yii::app()->request->getParam('clients') ;
        $clients = array_unique ($clients);
        $labels = Yii::app()->request->getParam('lebels');  
        $user = Users::model()->findByPk(Yii::app()->user->id); 
        
        if (!empty($clients)) { 
            foreach ($clients as $client_id) {    
                $actions = new Actions(); 
                $actions->creation_date = date('Y-m-d H:i:s');
                $actions->client_id = $client_id; 
                $actions->responsable_id = Yii::app()->user->id;
                if (intval($_POST['type_id']) >0 ) {
                    $actions->responsable_id = $_POST['type_id'];
                } elseif ($_POST['type_id'] == 'manager') {
                    $actions->responsable_id = $_POST['manager_id'];
                } elseif ($_POST['type_id'] == 'director') {
                    $actions->responsable_id = $_POST['director_id'];
                }elseif ($_POST['type_id'] == 'no') {
                    $actions->responsable_id = $user->parent_id ;
                }

                if (strtotime($_POST['date'])) {
                    $actions->action_date = date('Y-m-d H:i', strtotime($_POST['date']));
                } else {
                    $actions->action_date = null;
                }
                $actions->text = $_POST['title'];
                $actions->description = $_POST['desc'];
                $actions->director_id =$_POST['director_id'];  
                                                  
                $actions->action_type_id = 3;
                $actions->action_priority_id = 1;   
                $actions->action_status_id = $_POST['status'];  
                $actions->save();
                if (!empty($labels)) {
                    foreach ($labels  as $label) {
                        $addLabel = new LabelsInActions();
                        $addLabel->action_id = $actions->id;
                        $addLabel->label_id = $label;
                        $addLabel->save();
                    }
                }
            }
        }
        Yii::app()->user->setFlash('action_create_success', Yii::app()->request->getParam('msg') );
    }
    public function actionSet_edit_actions () { 
    
    
        $actions = Yii::app()->request->getParam('rows') ;
        $fields = Yii::app()->request->getParam('fields');
        $labels = Yii::app()->request->getParam('lebels');  
        $user = Users::model()->findByPk(Yii::app()->user->id); 

        if (!empty($actions) and !empty($fields)) { 
            foreach ($actions as $action_id) {    
                $attributes = [];
                foreach ($fields as $field) {
                    switch ($field) {
                        case 'text':  case 'description':  case 'action_status_id':  
                            $value = Yii::app()->request->getParam($field);
                            if(!empty($value))  {
                                $attributes[$field] = Yii::app()->request->getParam($field);                            
                            }         
                            break;

                        case 'action_date': 
                            $attributes[$field] = date('Y-m-d H:i', strtotime(Yii::app()->request->getParam('action_date')));
                            break;

                        case 'responsable_id': 
                                if (intval($_POST['type_id']) >0 ) {
                                    $attributes[$field] = $_POST['type_id'];
                                } elseif ($_POST['type_id'] == 'manager') {
                                    $attributes[$field] = $_POST['manager_id'];
                                } elseif ($_POST['type_id'] == 'director') {
                                    $attributes[$field] = $_POST['director_id'];
                                }elseif ($_POST['type_id'] == 'no') {
                                    $attributes[$field] = $user->parent_id ;
                                }
                            break;

                        case 'labels': 
                            if (!empty($labels)) {
                                LabelsInActions::model()->deleteAll('action_id = :CID', [':CID' => $action_id ]  ); 
                                foreach ($labels  as $label) {
                                    $addLabel = new LabelsInActions();
                                    $addLabel->action_id = $action_id;
                                    $addLabel->label_id = $label;
                                    $addLabel->save();
                                }
                            }
                            break;
                    } 
                    if (!empty($attributes)) {
                        Actions::model()->updateByPk( $action_id ,$attributes );     
                    }
                    
                } 
            }
        }    
        Yii::app()->user->setFlash('success', Yii::app()->request->getParam('msg') );  
        
    }
    
    
    public function actionAjax_clients () { 

        $term = Yii::app()->request->getParam('tag');

        $clients = new Clients();
        $clients->keyword = $term;
        $clientTableData = $clients->searchClients(true);
        if ($clientTableData) {
            $result_data = [];
            foreach ($clientTableData as $row) {
                $result_data[] = [
                    'key'=>(string)$row->id, 
                    'value'=>$row->name, 
                    'r_name'=>$row->responsable->first_name . " " . $row->responsable->second_name,
                    'avatar'=>(string)$row->responsable->avatar
                ] ;
            }
        } 

        echo json_encode($result_data);

    }


    public function actionSettings_contacts($example = false, $error = false, $export = false)
    {
        $user = Users::model()->with('roles')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {
            $manager = Yii::app()->getComponent('phpExcelManager');
            $errorMessage = '';
            $importSuccess = false;
            $duplicateSuccess = false;
            $typeTab = 'import';
            $fileModel = new UploadImportFile();
            $templateClient = new Clients();
            $templateClient->responsable_id = $user->id;
            $selectedSteps = new StepsInClients();
            $allDuplicatesFields = Settings::getAllUniqueDefaultFields();
            $configDuplicateList = [
                'miss' => 'Пропустить',
                'rewrite' => 'Перезаписать',
                'join' => 'Склеить',
                'rename' => 'Переименовать'
            ];
            $selectedTypeDuplicate = 'miss';

            //для экспорта

            $section = AdditionalFieldsSection::model()->findAll();
            $sectionFieldsExp = [];
            foreach ($section as $section) {
                $sectionFieldsExp [$section->id]['name'] = $section->name;
            }

            $additionalFields = AdditionalFields::model()->with('section0')->findAll();
            $additionalFieldsLabels = [];
            $addFieldTypeSelector = [];
            $addFieldType = [];
            $additionalFieldsDefaultValue = [];
            $headerFio = 'Имя контакта';
            $uniqueAdditionalFields = [];
            $duplicateParam = json_decode(Settings::getValueSettingByCode('duplicateParam'), true);
            $duplicateAdditionalFieldsEnabled = Settings::getValueSettingByCode('duplicateAdditionalFieldsEnabled');
            foreach ($additionalFields as $field) {
                $additionalFieldsDefaultValue[$field->table_name] = $field->default_value;
                if ($field->table_name == 'fieldFio') {
                    $headerFio = $field->name;
                }
                if ($field->type == 'select') {
                    $addFieldTypeSelector[$field->table_name] = json_decode($field->default_value);
                }
                if ($field->unique) {
                    $uniqueAdditionalFields[$field->table_name] = [
                        'active' => $field->table_name == 'fieldFio' ? 1 : 0,
                        'name' => $field->name,
                        'table_name' => $field->table_name
                    ];
                }
                $addFieldType[$field->table_name] = $field->type;
                $sectionFieldsExp[$field->section0->id]['fields'][] = [
                    'table_name' => $field->table_name,
                    'name' => $field->name,
                    'active' => 1
                ];

                $additionalFieldsLabels[$field->table_name] = $field->name;
            }

            $steps = Steps::model()->findAll('steps_type_id = 1');
            $stepsExp = [];
            $listStepOption = [];
            foreach ($steps as $step) {
                if ($options = StepsOptions::model()->findAll(['condition' => 'steps_id = :ID', 'order' => 'weight', 'params' =>[':ID' => $step->id]])) {
                    $listStepOption[$step->id] = $options;
                }
                $stepsExp[] = [
                    'id' => $step->id,
                    'name' => $step->name,
                    'active' => 1
                ];
            }

            $allLabels = Labels::model()->findAll('type_id = 1');
            $customSelectedLabelsImport = [];

            $users = Users::model()->with('roles')->findAll();
            $usersExp = [
                'admin' => ['name' => 'Админ', 'users' => []],
                'director' => ['name' => 'Руководитель', 'users' => []],
                'manager' => ['name' => 'Менеджер', 'users' => []]
            ];
            foreach ($users as $userVal) {
                $usersExp[$userVal['roles'][0]->name]['users'][] = [
                    'id' => $userVal->id,
                    'name' => $userVal->first_name . ' ' . $userVal->second_name,
                    'avatar' => $userVal->avatar,
                    'active' => 1
                ];
            }
            $checkAllSteps = 0;
            $checkAllAdditionFields = [];
            $checkAllUsers = [];

            //для импорта

            $excelExample = $manager->create();
            $excelExample->setSettings(array(
                'autoSize' => true,
                'useTempDir' => true
            ));
            //Стили заголовков
            $headerStyle = array(
                'fill' => array(
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'CCCCCC')
                ),
                'font'  => array(
                    'size'  => 10,
                    'name'  => 'Tahoma'
                )
            );
            //Стили контента
            $style = [
                'font'  => array(
                    'size'  => 10,
                    'name'  => 'Tahoma'
            )];

            $headersExample =  [
                $headerFio . '*',
            ];

            foreach ($additionalFields as $value) {
                if ($value->table_name != 'fieldFio') {
                    $headersExample[] = $value->name;
                }
            }

            array_push($headersExample, 'Дата создания');

            $excelExample->addHeaderRow(
                $headersExample,
                $headerStyle
            );

            $excelExample->addData([]);
            $excelExample->phpExcel->getDefaultStyle()->applyFromArray($style);
            $excelExample->setWorksheetTitle(date('d-m-Y'));
            $excelExample->filename = 'Import_Example.xlsx';
            $excelExample->save();
            $exampleLinkFile = $excelExample->filePath . $excelExample->filename;

            if (isset($_POST['exportBtn'])) {
                $typeTab = 'export';
                $clients = new Clients();
                $labelIds = isset($_POST['Export']['labels']) && $_POST['Export']['labels'] ? array_keys($_POST['Export']['labels']) : [];
                $stepIds = isset($_POST['Export']['steps']) && $_POST['Export']['steps'] ? array_keys($_POST['Export']['steps']) : [];
                $userIds = isset($_POST['Export']['users']) && $_POST['Export']['users'] ? array_keys($_POST['Export']['users']) : [];
                $additionalFieldTableNames = $_POST['Export']['sections'] ? array_keys($_POST['Export']['sections']) : [];
                $checkAllSteps = isset($_POST['Export']['checkAllSteps']) ? $_POST['Export']['checkAllSteps'] : 0;
                $checkAllAdditionFields = isset($_POST['Export']['checkAllAdditionFields']) ? $_POST['Export']['checkAllAdditionFields'] : 0;
                $checkAllUsers = isset($_POST['Export']['checkAllUsers']) ? $_POST['Export']['checkAllUsers'] : 0;
                // выбранные значения
                foreach ($allLabels as $value) {
                    if (in_array($value->id, $labelIds)) {
                        $customSelectedLabels [$value->id] = $value;
                    }
                }

                foreach ($sectionFieldsExp as &$sectionValue) {
                    if (isset($sectionValue['fields'])) {
                        foreach ($sectionValue['fields'] as &$fieldValue) {
                            $fieldValue['active'] = in_array($fieldValue['table_name'], $additionalFieldTableNames) ? 1 : 0;
                        }
                    }
                }

                foreach ($usersExp as &$roleValue) {
                    foreach ($roleValue['users'] as &$userValue) {
                        $userValue['active'] = in_array($userValue['id'], $userIds) ? 1 : 0;
                    }
                }

                foreach ($stepsExp as &$stepValue) {
                    $stepValue['active'] = in_array($stepValue['id'], $stepIds) ? 1 : 0;
                }

                if (count($userIds)) {
                    $exportClients = $clients->searchForExport($labelIds, (count($steps) == count($stepIds) ? [] : $stepIds), $userIds);
                   // создание excel
                    $excel = $manager->create();
                    $excel->setSettings(array(
                        'autoSize' => true,
                        'useTempDir' => true,
                    ));

                    $headersExp =  [
                        'ID' => 'ID',
                        $headerFio => $headerFio,
                        'Ответственный' => 'Ответственный'
                    ];

                    foreach ($additionalFieldTableNames as $value) {
                        $headersExp [$value] = $additionalFieldsLabels[$value];
                    }

                    $headersExp ['Этапы'] = 'Этапы';
                    $headersExp ['Метки'] = 'Метки';
                    $headersExp ['Дата создания'] = 'Дата создания';
                    $headersExp ['Дата изменения'] = 'Дата изменения';


                    $excel->addHeaderRow(
                        $headersExp,
                        $headerStyle
                    );

                    $dataExp = [];
                    $exportCountClients = count($exportClients);
                    foreach ($exportClients as $exportClient) {
                        $headersExp['ID'] = $exportClient->id;
                        $headersExp[$headerFio] = $exportClient->name;
                        $headersExp['Ответственный'] = $exportClient->responsable->first_name . $exportClient->responsable->second_name;
                        $headersExp ['Дата создания'] = $exportClient->creation_date;
                        $headersExp ['Дата изменения'] = $exportClient->change_client_date;

                        // динамическое заполнение доп полей
                        foreach ($exportClient->additionalFieldsValues[0] as $key => $value) {
                            if (isset($headersExp[$key])){
                                if ($addFieldType[$key] == 'select') {
                                    foreach ($addFieldTypeSelector[$key] as $option) {
                                        if ($option->id == $value) {
                                            $headersExp[$key] = $option->optionName;
                                            break;
                                        }
                                    }
                                } elseif ($addFieldType[$key] == 'date' && $value) {
                                    $headersExp[$key] = date('d.m.Y', $value);
                                } else {
                                    $headersExp[$key] = $value;
                                }
                            }
                        }

                        $headersExp['Метки'] = '';
                        foreach ($exportClient->labelsInClients as $key => $value) {
                            $headersExp['Метки'] .= $value->label->name . ($key + 1 != count($exportClient->labelsInClients) ? ", " : "");
                        }

                        $headersExp['Этапы'] = '';
                        foreach ($exportClient->stepsInClients as $key => $value) {
                            $stepOptionName = '';
                            foreach ($value->steps->stepsOptions as $val) {
                                if ($value->selected_option_id == $val->id) {
                                    $stepOptionName = $val->name;
                                    break;
                                }
                            }
                            $headersExp['Этапы'] .= $value->steps->name . ($stepOptionName ? ": " . $stepOptionName : '');
                        }

                        $dataExp[] = $headersExp;
                    }

                    $excel->addData($dataExp);
                    $excel->phpExcel->getDefaultStyle()->applyFromArray($style);
                    $dateExp = date('d-m-y h:m');
                    $dateExp = str_replace(' ', '_', $dateExp);
                    $dateExp = str_replace(':', '_', $dateExp);
                    $excel->setWorksheetTitle($dateExp);
                    $excel->filename = 'Export_List_' . $dateExp . '.xlsx';
                    $excel->save();
                    //$excel->download();
                    $fileExportPath = $excel->filePath . $excel->filename;
                } else {
                    $errorMessage = 'Выберите хотя бы одного ответственного';
                }


            } elseif (isset($_POST['importBtn'])) {
                $transaction = Yii::app()->db->beginTransaction();
                $typeTab = 'import';
                switch ($_POST['type']) {
                    case 'i': {
                        $templateClient->responsable_id = $user->id;
                        break;
                    }
                    case 'manager': {
                        $templateClient->responsable_id = $_POST['Clients']['manager_id'];
                        break;
                    }
                    case 'director': {
                        $templateClient->responsable_id = $_POST['Clients']['director_id'];
                        break;
                    }
                }

                if (isset($_POST['Clients']['Labels'])) {
                    $labelId = [];
                    foreach ($_POST['Clients']['Labels'] as $id => $enable) {
                        if ($enable == 1) {
                            $labelId [] = $id;
                        }
                    }

                    if ($labelId) {
                        if (!is_array($labelId)) {
                            $labelId = [$labelId];
                        }
                        foreach ($allLabels as $label) {
                            if (in_array($label->id, $labelId)) {
                                $customSelectedLabelsImport [$label->id] = $label;
                            }
                        }
                    }
                }

                if (isset($_POST['StepsInClients'])) {
                    $selectedSteps->steps_id = $_POST['StepsInClients']['steps_id'];
                    $selectedSteps->selected_option_id = $selectedSteps->steps_id != 1 ? $_POST['StepsInClients']['selected_option_id'] : 0;
                }

                if (isset($_POST['uniqueAdditionalFields'])) {
                    foreach ($uniqueAdditionalFields as &$value) {
                        $value['active'] = isset($_POST['uniqueAdditionalFields'][$value['table_name']]) ? 1 : 0;
                    }
                    unset($value);
                }

                $selectedTypeDuplicate = $_POST['Clients']['Duplicate'];

                $fileModel->fileLoad = CUploadedFile::getInstance($fileModel, 'fileLoad');
                if ($fileModel->fileLoad) {
                    if ($fileModel->fileLoad->getType() == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                        $manager = Yii::app()->getComponent('phpExcelManager');
                        $excel = $manager->get($fileModel->fileLoad->getTempName());
                        $excelRowList = [];
                        $headersLabel = [];
                        $headers = [];
                        foreach ($excel->phpExcel->getWorksheetIterator() as $activeWorksheet) {
                            $excelRowList = $activeWorksheet->toArray();
                            $indexRow = 0;
                            foreach ($excelRowList[0] as $key => $value) {
                                $indexRow = $key;
                                $headersLabel[] = $value;
                                if ($value === 'Дата создания') {
                                    break;
                                }
                            }
                            break;
                        }

                        try {
                            foreach ($headersLabel as $key => $value) {
                                if ($key > 0 && $key + 1 != count($headersLabel)) {
                                    if ($AddFiled = AdditionalFields::model()->find('name=:NAME', [':NAME' => $value])) {
                                        $headers[$key] = $AddFiled->table_name;
                                    } else {
                                        throw new Exception('');
                                    }
                                    $headers[$key] = AdditionalFields::model()->find('name=:NAME', [':NAME' => $value])->table_name;
                                }
                            }
                        } catch (Throwable $e) {
                            $errorMessage = 'Ошибка в названии заголовка - файл заполнен некорректно';
                        }

                        if (!$errorMessage) {
                            try {
                                $condition = '';
                                $typeDuplicate = $_POST['Clients']['Duplicate'];
                                $uniqueFields = $_POST['uniqueAdditionalFields'];
                                $countImportClient = 0;
                                $countImportDuplicate = 0;
                                $changeCountClient = 0;
                                $additionalFieldValueErrors = [];
                                $excelError = $manager->create();
                                $excelError->setSettings(array(
                                    'autoSize' => true,
                                    'useTempDir' => true,
                                ));
                                $excelError->addHeaderRow(
                                    ['Ошибки'],
                                    $headerStyle
                                );

                                // чистим пустые строки файла
                                foreach ($excelRowList as $key => $value) {
                                    if (!count(array_diff($value, array(null)))) {
                                        unset($excelRowList[$key]);
                                    }
                                }

                                foreach ($excelRowList as $keyRow => $importRow) {
                                    // 0 - заголовки
                                    if ($keyRow) {
                                        // запись в ТБ
                                        $newClient = new Clients();
                                        $newClient->responsable_id = $templateClient->responsable_id;
                                        $newClient->creator_id = $user->id;
                                        $newClient->priority_id = ClientsPriority::model()->find()->id;
                                        $newClient->source_id = ClientsSources::model()->find()->id;
                                        $newClient->city_id = ClientsCityes::model()->find()->id;
                                        $newClient->group_id = ClientsGroups::model()->find()->id;

                                        $responsible = $newClient->responsable->first_name;

                                        $newAdditionalFieldsValues = new AdditionalFieldsValues();
                                        foreach ($importRow as $keyCell => $value) {
                                            if ($indexRow == $keyCell) {
                                                // Дата создания
                                                $create_date = '';
                                                if ($value) {
                                                    try {
                                                        $dateObj= new DateTime($value);
                                                        $create_date = date('Y-m-d', $dateObj->getTimestamp());
                                                        if ($create_date == '1970-01-01') {
                                                            $additionalFieldValueErrors[]['Ошибки'] = ($keyRow + 1) . ' строка: "Дата создания" - это поле с датой, укажите дату по образцу 30.12.2019';
                                                        }
                                                    } catch (Exception $e) {
                                                        $additionalFieldValueErrors[]['Ошибки'] = ($keyRow + 1) . ' строка: "Дата создания" - это поле с датой, укажите дату по образцу 30.12.2019';
                                                    }
                                                }
                                                $newClient->creation_date = isset($create_date) && $create_date ?  $create_date : date('Y-m-d');;
                                                break;
                                            } else {
                                                switch ($keyCell) {
                                                    //имя
                                                    case 0: {
                                                        $newClient->name = trim($value);
                                                        if($newClient->name === '' || $newClient->name === null) {
                                                            $additionalFieldValueErrors[]['Ошибки'] = ($keyRow + 1) . ' строка: "Имя контакта" - не заполнено';
                                                        }
                                                        break;
                                                    }
                                                    default: {
                                                        if (!($table_name = $headers[$keyCell])) {
                                                            $errorMessage = '"' . $headersLabel[$keyCell] .  '" - ' . ' заголовок записан с ошибкой в файле или его нет в базе данных';
                                                            throw new Exception('');
                                                        }

                                                        if (!$value && $additionalFieldsDefaultValue[$table_name] && $addFieldType[$table_name] != 'select') {
                                                            $value = $additionalFieldsDefaultValue[$table_name];
                                                        }
                                                        if ($value) {
                                                            switch ($addFieldType[$table_name]) {
                                                                case 'int': {
                                                                    $checkValue = str_replace([' ', '(', ')', '-', '+', '-'], '', $value);
                                                                    if (!is_numeric($checkValue)) {
                                                                        $additionalFieldValueErrors[]['Ошибки'] = ($keyRow + 1) . ' строка: "' . $headersLabel[$keyCell] . '" - это числовое поле, укажите число или спец. символ';
                                                                    }
                                                                    break;
                                                                }
                                                                case 'date': {
                                                                    try {
                                                                        if ($value) {
                                                                            if (is_numeric($value)) {
                                                                                $date = date('Y-m-d', $value);
                                                                            } else {
                                                                                $dateObj= new DateTime($value);
                                                                                $date = date('Y-m-d', $dateObj->getTimestamp());
                                                                                $value = $dateObj->getTimestamp();
                                                                            }
                                                                            if ($date == '1970-01-01') {
                                                                                $additionalFieldValueErrors[]['Ошибки'] = ($keyRow + 1) . ' строка: "' . $headersLabel[$keyCell] . '" - это поле с датой, укажите дату по образцу 30.12.2019';
                                                                            }
                                                                        }
                                                                    } catch (Exception $e) {
                                                                        $additionalFieldValueErrors[]['Ошибки'] = ($keyRow + 1) . ' строка: "' . $headersLabel[$keyCell] . '" - это поле с датой, укажите дату по образцу 30.12.2019';
                                                                    }
                                                                    break;
                                                                }
                                                                case 'checkbox': {
                                                                    if (!is_numeric($value) || $value != 1 && $value != 0) {
                                                                        $additionalFieldValueErrors[]['Ошибки'] = ($keyRow + 1) . ' строка: "' . $headersLabel[$keyCell] . '" - это чебокс, укажите 1 (включено) или 0 (отключено)';
                                                                    }
                                                                    break;
                                                                }
                                                                case 'select': {
                                                                    $options = $addFieldTypeSelector[$headers[$keyCell]];
                                                                    $optionValid = false;
                                                                    foreach ($options as $option) {
                                                                        if ($value == $option->optionName) {
                                                                            $value = $option->id;
                                                                            $optionValid = true;
                                                                            break;
                                                                        }
                                                                    }

                                                                    if (!$optionValid) {
                                                                        $additionalFieldValueErrors[]['Ошибки'] = ($keyRow + 1) . ' строка: "' . $headersLabel[$keyCell] . '" - такой опции нет в селекторе';
                                                                    }
                                                                    break;
                                                                }
                                                            }
                                                        }

                                                        $newAdditionalFieldsValues->$table_name = $value !== null ? $value : '';
                                                    }
                                                }
                                            }
                                        }

                                        if (count($additionalFieldValueErrors)) {
                                            continue;
                                        }

                                        $newAdditionalFieldsValues->fieldFio = $newClient->name;
                                        $count = 1;
                                        $condition = '';
                                        foreach ($uniqueFields as $keyTableName => $value) {
                                            if ($newAdditionalFieldsValues->$keyTableName !== '' && $newAdditionalFieldsValues->$keyTableName !== null) {
                                                $condition .= ($count != 1 ? ' AND ' : '') . $keyTableName . " = '" . $newAdditionalFieldsValues->$keyTableName . "' ";
                                                $count++;
                                            }
                                            //$condition .= ($count != count($uniqueFields) ? " AND " : "");
                                        }
                                        // Логика дубликатов
                                        switch ($typeDuplicate) {
                                            case 'miss':
                                                {
                                                    if ($condition == "" || $condition != "" && !AdditionalFieldsValues::model()->find($condition)) {
                                                        if ($newClient->save()) {
                                                            $newAdditionalFieldsValues->client_id = $newClient->id;
                                                            $newAdditionalFieldsValues->save();
                                                            // метки
                                                            foreach ($labelId as $value) {
                                                                $newLabel = new LabelsInClients();
                                                                $newLabel->client_id = $newClient->id;
                                                                $newLabel->label_id = $value;
                                                                $newLabel->save();
                                                            }

                                                            //этапы
                                                            $newStep = new StepsInClients();
                                                            $newStep->clients_id = $newClient->id;
                                                            $newStep->steps_id = $selectedSteps->steps_id;
                                                            $newStep->selected_option_id = $selectedSteps->selected_option_id;
                                                            $newStep->save();
                                                            //
                                                            $countImportClient++;
                                                        } else {
                                                            $errorMessage = "Ошибка при импорте! Файл импорта настроен некорректно";
                                                            throw new Exception('');
                                                        }
                                                    } else {
                                                        $countImportDuplicate++;
                                                    }
                                                    break;
                                                }
                                            case 'rewrite':
                                                {
                                                    if ($condition != "" && ($additionalFieldsValuesDuplicate = AdditionalFieldsValues::model()->findAll($condition))) {
                                                        foreach ($additionalFieldsValuesDuplicate as $value) {
                                                            $clientId = $value->client_id;
                                                            foreach ($value->attributes as $attName => $val2) {
                                                                if ($attName != 'id') {
                                                                    $value[$attName] = $newAdditionalFieldsValues[$attName];
                                                                }
                                                            }
                                                            $value->client_id = $clientId;
                                                            $oldClient = Clients::model()->findByPk($value->client_id);
                                                            $oldClient->attributes = $newClient->attributes;
                                                            if ($oldClient->save() && $value->save()) {
                                                                LabelsInClients::model()->deleteAll('client_id = '. $oldClient->id);
                                                                // метки
                                                                foreach ($labelId as $value2) {
                                                                    $newLabel = new LabelsInClients();
                                                                    $newLabel->client_id = $oldClient->id;
                                                                    $newLabel->label_id = $value2;
                                                                    $newLabel->save();
                                                                }

                                                                //этапы
                                                                if (!($stepClient = StepsInClients::model()->find('clients_id=:ID', [':ID' => $oldClient->id]))) {
                                                                    $stepClient = new StepsInClients();
                                                                    $stepClient->clients_id = $newClient->id;
                                                                }
                                                                $stepClient->steps_id = $selectedSteps->steps_id;
                                                                $stepClient->selected_option_id = $selectedSteps->selected_option_id;
                                                                $stepClient->save();
                                                                $changeCountClient++;
                                                            } else {
                                                                $errorMessage = "Ошибка при импорте! Файл импорта настроен некорректно";
                                                                throw new Exception('');
                                                            }
                                                        }

                                                    } else {
                                                        if ($newClient->save()) {
                                                            $newAdditionalFieldsValues->client_id = $newClient->id;
                                                            $newAdditionalFieldsValues->save();
                                                            // метки
                                                            foreach ($labelId as $value) {
                                                                $newLabel = new LabelsInClients();
                                                                $newLabel->client_id = $newClient->id;
                                                                $newLabel->label_id = $value;
                                                                $newLabel->save();
                                                            }

                                                            //этапы
                                                            $newStep = new StepsInClients();
                                                            $newStep->clients_id = $newClient->id;
                                                            $newStep->steps_id = $selectedSteps->steps_id;
                                                            $newStep->selected_option_id = $selectedSteps->selected_option_id;
                                                            $newStep->save();
                                                            //
                                                            $countImportClient++;
                                                        } else {
                                                            $errorMessage = "Ошибка при импорте! Файл импорта настроен некорректно";
                                                            throw new Exception('');
                                                        }
                                                    }

                                                    break;
                                                }
                                            case 'join':
                                                {
                                                    // $condition == "" не записывает нового контакта. Подставлять null
                                                    $isJoin = false;
                                                    if ($condition != "" && ($additionalFieldsValuesDuplicate = AdditionalFieldsValues::model()->findAll($condition))) {
                                                        foreach ($additionalFieldsValuesDuplicate as $value) {
                                                            foreach ($value->attributes as $atr => $value2) {
                                                                if ($value->$atr == '' && $value->$atr == null
                                                                    && $newAdditionalFieldsValues->$atr !== ''
                                                                    && $newAdditionalFieldsValues->$atr !== null) {
                                                                    $value->$atr = $newAdditionalFieldsValues->$atr;
                                                                    $isJoin = true;
                                                                }
                                                            }

                                                            if (!$value->save()) {
                                                                $errorMessage = "Ошибка при импорте! Файл импорта настроен некорректно";
                                                                throw new Exception('');
                                                            }
                                                        }

                                                        if ($isJoin) {
                                                            $changeCountClient++;
                                                        }

                                                    } else {
                                                        if ($newClient->save()) {
                                                            $newAdditionalFieldsValues->client_id = $newClient->id;
                                                            $newAdditionalFieldsValues->save();
                                                            // метки
                                                            foreach ($labelId as $value) {
                                                                $newLabel = new LabelsInClients();
                                                                $newLabel->client_id = $newClient->id;
                                                                $newLabel->label_id = $value;
                                                                $newLabel->save();
                                                            }

                                                            //этапы
                                                            $newStep = new StepsInClients();
                                                            $newStep->clients_id = $newClient->id;
                                                            $newStep->steps_id = $selectedSteps->steps_id;
                                                            $newStep->selected_option_id = $selectedSteps->selected_option_id;
                                                            $newStep->save();
                                                            //
                                                            $countImportClient++;
                                                        } else {
                                                            $errorMessage = "Ошибка при импорте! Файл импорта настроен некорректно";
                                                            throw new Exception('');
                                                        }
                                                    }
                                                    break;
                                                }
                                            case 'rename':
                                                {
                                                    if ($condition != "" && AdditionalFieldsValues::model()->find($condition)) {
                                                        if ($newClient->save()) {
                                                            $newAdditionalFieldsValues['fieldFio'] = $newAdditionalFieldsValues['fieldFio'] . '_duplicate_' . $newClient->id;
                                                            $newClient->name = $newAdditionalFieldsValues->fieldFio;
                                                            $newClient->update();
                                                            $newAdditionalFieldsValues->client_id = $newClient->id;
                                                            $newAdditionalFieldsValues->save();
                                                            // метки
                                                            foreach ($labelId as $value) {
                                                                $newLabel = new LabelsInClients();
                                                                $newLabel->client_id = $newClient->id;
                                                                $newLabel->label_id = $value;
                                                                $newLabel->save();
                                                            }

                                                            //этапы
                                                            $newStep = new StepsInClients();
                                                            $newStep->clients_id = $newClient->id;
                                                            $newStep->steps_id = $selectedSteps->steps_id;
                                                            $newStep->selected_option_id = $selectedSteps->selected_option_id;
                                                            $newStep->save();
                                                            //
                                                            $changeCountClient++;
                                                        } else {
                                                            $errorMessage = "Ошибка при импорте! Файл импорта настроен некорректно";
                                                            throw new Exception('');
                                                        }

                                                    } else {
                                                        if ($newClient->save()) {
                                                            $newAdditionalFieldsValues->client_id = $newClient->id;
                                                            $newAdditionalFieldsValues->save();
                                                            // метки
                                                            foreach ($labelId as $value) {
                                                                $newLabel = new LabelsInClients();
                                                                $newLabel->client_id = $newClient->id;
                                                                $newLabel->label_id = $value;
                                                                $newLabel->save();
                                                            }

                                                            //этапы
                                                            $newStep = new StepsInClients();
                                                            $newStep->clients_id = $newClient->id;
                                                            $newStep->steps_id = $selectedSteps->steps_id;
                                                            $newStep->selected_option_id = $selectedSteps->selected_option_id;
                                                            $newStep->save();
                                                            //
                                                            $countImportClient++;
                                                        } else {
                                                            $errorMessage = "Ошибка при импорте! Файл импорта настроен некорректно";
                                                            throw new Exception('');
                                                        }
                                                    }
                                                    break;
                                                }
                                        }

                                    }
                                }

                                if (count($additionalFieldValueErrors)) {
                                    $transaction->rollback();
                                    $excelError->addData($additionalFieldValueErrors);
                                    $excelError->phpExcel->getDefaultStyle()->applyFromArray($style);
                                    $excelError->filename = 'Ошибки.xlsx';
                                    $excelError->save();
                                    $errorMessage = 'Файл импорта заполнен некорректно. Пожалуйста, исправьте ошибки и попробуйте снова';
                                    //$excelError->download();
                                    $fileErrorPath = $excelError->filePath . $excelError->filename;
                                } else {
                                    $importSuccess = true;
                                    $transaction->commit();
                                }

                            } catch (Exception $e) {
                                $transaction->rollback();
                                $errorMessage = $errorMessage != '' ? $errorMessage : $e->getMessage();
                            }
                        }
                    } else {
                        $errorMessage = 'Некорректный тип файла или файл с ошибкой. Файл импорта должен иметь расширение ".xlsx" и заполнен по образцу';
                    }
                } else {
                    $errorMessage = 'Не загружен файл импорта. Загрузите файл и попробуйте снова';
                }
            } elseif (isset($_POST['duplicateBtn'])) {
                if (isset($_POST['duplicateAdditionalFields'])) {
                    $duplicateFields = [];
                    foreach ($_POST['duplicateAdditionalFields'] as $keyDuplicateField => $duplicateField) {
                        $duplicateFields[] = $keyDuplicateField;
                    }

                    $addFields = array_intersect(array_column($allDuplicatesFields, 'table_name'), $duplicateFields);
                    $delFields = array_diff(array_column($allDuplicatesFields, 'table_name'), $duplicateFields);

                    foreach($addFields as $addField) {
                        $additionalField = AdditionalFields::model()->findByAttributes(['table_name' => $addField]);
                        $additionalField->unique = 1;
                        $additionalField->save();
                    }

                    foreach($delFields as $delField) {
                        $additionalField = AdditionalFields::model()->findByAttributes(['table_name' => $delField]);
                        $additionalField->unique = 0;
                        $additionalField->save();
                    }

                    Settings::setSettings('duplicateParam', json_encode($duplicateFields, JSON_FORCE_OBJECT));
                    $duplicateSuccess = true;
                }
                if (isset($_POST['duplicateTypeCheck'])) {
                    Settings::setSettings('duplicateTypeCheck', $_POST['duplicateTypeCheck']);
                }
                if (isset($_POST['duplicateAdditionalFieldsEnabled'])) {
                    Settings::setSettings('duplicateAdditionalFieldsEnabled', $_POST['duplicateAdditionalFieldsEnabled']);

                    if (!$_POST['duplicateAdditionalFieldsEnabled']) {
                        // делаем уникальными все три поля
                        foreach ($additionalFields as $field) {
                            switch ($field->table_name){
                                case 'fieldFio':
                                case 'fieldTelephone':
                                case 'fieldEmail': {
                                    $field->unique = 1;
                                    $field->save();
                                    break;
                                }
                            }
                        }
                    }

                    $actualDuplicatesFields = Settings::getAllUniqueDefaultFields();

                    foreach($actualDuplicatesFields as $field) {
                        if ($field->unique) {
                            if (!isset($uniqueAdditionalFields[$field->table_name])) {
                                $uniqueAdditionalFields[$field->table_name] = [
                                    'active' => 0,
                                    'name' => $field->name,
                                    'table_name' => $field->table_name
                                ];
                            }
                        } else {
                            unset($uniqueAdditionalFields[$field->table_name]);
                        }
                    }
                }
                $duplicateParam = json_decode(Settings::getValueSettingByCode('duplicateParam'), true);
                $duplicateAdditionalFieldsEnabled = Settings::getValueSettingByCode('duplicateAdditionalFieldsEnabled');
            } elseif ($example) {
                $excelExample->download();
            } elseif ($error) {
                $manager = Yii::app()->getComponent('phpExcelManager');
                $excel = $manager->get($error);
                $excel->download();
            } elseif ($export) {
                $manager = Yii::app()->getComponent('phpExcelManager');
                $excel = $manager->get($export);
                $excel->download();
            }

            $this->render('settings_contacts', [
                'user' => $user,
                'sectionFieldsExp' => $sectionFieldsExp,
                'stepsExp' => $stepsExp,
                'usersExp' => $usersExp,
                'allLabels' => $allLabels,
                'customSelectedLabels' => $customSelectedLabels ?? [],
                'errorMessage' => $errorMessage,
                'checkAllSteps' => $checkAllSteps,
                'checkAllAdditionFields' => $checkAllAdditionFields,
                'checkAllUsers' => $checkAllUsers,
                'typeTab' => $typeTab,
                'fileModel' => $fileModel,
                'exampleLinkFile' => $exampleLinkFile,
                'templateClient' => $templateClient,
                'listStep' => $steps,
                'selectedSteps' => $selectedSteps,
                'listStepOption' => $listStepOption,
                'customSelectedLabelsImport' => $customSelectedLabelsImport,
                'importSuccess' => $importSuccess,
                'countImportClient' => $countImportClient ?? null,
                'configDuplicateList' => $configDuplicateList,
                'uniqueAdditionalFields' => $uniqueAdditionalFields,
                'countImportDuplicate' => $countImportDuplicate ?? null,
                'responsible' => $responsible ?? null,
                'selectedTypeDuplicate' => $selectedTypeDuplicate,
                'changeCountClient' => $changeCountClient ?? null,
                'fileErrorPath' => $fileErrorPath ?? "",
                'fileExportPath' => $fileExportPath ?? "",
                'exportCountClients' => $exportCountClients ?? null,
                'duplicateParam' => $duplicateParam ?? null,
                'duplicateAdditionalFieldsEnabled' => $duplicateAdditionalFieldsEnabled ?? null,
                'allDuplicatesFields' => $allDuplicatesFields,
                'duplicateSuccess' => $duplicateSuccess,
            ]);
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }   

    public function actionEdit_client_ajax () {    

        $field = Yii::app()->request->getParam('field');
        $client_id = Yii::app()->request->getParam('client');
        $value = Yii::app()->request->getParam('value');
        $client = Clients::model()->with('responsable')->findByPk($client_id);  
        $user = Users::model()->findByPk(Yii::app()->user->id);
                     
        if (!empty($client)) {   
            if (!Clients::checkAccess($client, $user)) {
                return false;
            }
            switch ($field) {
                case 'responsable_id':
                    $roleArray = array('manager' => 'Менеджер', 'director' => 'Руководитель', 'admin' => 'Директор');
                    $responsable_id = $value;

                    if ($responsable_id == 'i') {
                        $responsable_id = Yii::app()->user->id;
                    } elseif ($responsable_id == 'admin') {
                        $responsable_id = Users::getAdminId();
                    } elseif ($res = Users::model()->findByPk($responsable_id)) {
                        $responsable_id = $res->id;
                    } else {
                        $responsable_id = null;
                    }

                    $client->responsable_id = $responsable_id;        
                    $client->change_client_date = date('Y-m-d H:i');                            
                    $client->save();
                    $client = Clients::model()->with('responsable')->findByPk($client_id); 
                    $result = [];  
                    $result['name'] = $client->responsable->first_name;
                    $result['avatar'] = $client->responsable->avatar; 
                    $result['type_name'] = $roleArray[$client->responsable->roles[0]->name]; 
                    if (empty($result['avatar'])) {
                        $result['avatar'] = '/img/ava_admin.svg';
                        if ($client->responsable->roles[0]->name=='manager') {
                            $result['avatar'] = '/img/employee.svg';
                        } elseif ($client->responsable->roles[0]->name=='director') {
                            $result['avatar'] = '/img/ava_adminisrtr.svg';
                        } 
                    }
                    echo json_encode($result);

                    break;
                    
                case 'steps_id':
                    $option = Yii::app()->request->getParam('option');
                    $save_data = ['steps_id' => $value, 'selected_option_id'=>0 ];
                    if (!empty($option)) {
                        $save_data['selected_option_id'] = $option;     
                    }
                    StepsInClients::model()->updateAll($save_data,  'clients_id = :ID', [':ID' => $client_id]);
                    $client->change_client_date = date('Y-m-d H:i');                            
                    $client->save();
                    break;
                case 'lebel':
                
                    LabelsInClients::model()->deleteAll('client_id = :CID', [':CID' => $client_id ]  ); 
                    $labels = Yii::app()->request->getParam('labels');
                    foreach ($labels as $label) { 
                        $addLabel = new LabelsInClients();
                        $addLabel->client_id = $client_id;
                        $addLabel->label_id = $label;
                        $addLabel->save();   
                    }   
                    $client->change_client_date = date('Y-m-d H:i');                            
                    $client->save();
                    break;


                case 'additionalField':
                    $changeUpdateDate = false;
                    $errorAddField = false;
                    $errorAddFieldText = '';
                    $additionalFiledValue = new AdditionalFieldsValues();
                    $additionalFiledValuesInClient = Yii::app()->commonFunction->getValueAdditionalFiled($client, $user);
                    
                    if (!$additionalFiledValue = AdditionalFieldsValues::model()->find('client_id = :client', [':client' => $client->id])) {
                        $additionalFiledValue = new AdditionalFieldsValues();
                        $additionalFiledValue->client_id = $client->id;

                    }
                    if (isset($_POST['Clients']['additionalField'])) {
                        foreach ($_POST['Clients']['additionalField'] as $addFieldName => $addFieldValue) {

                            if ($additionalFiledValue->$addFieldName != $addFieldValue) {
                                $addField = AdditionalFields::model()->find('table_name = :TN', [':TN' => $addFieldName]);
                                if ($addField->type == 'date') {
                                    if ($addFieldValue != '') {
                                        $addFieldValue = $date = date('d.m.Y', strtotime($addFieldValue));
                                    }
                                    if ($additionalFiledValue->$addFieldName == '') {
                                        if ($additionalFiledValue->$addFieldName != $addFieldValue) {
                                            $changeUpdateDate = true;
                                        }
                                    } else {
                                        $date = date('d.m.Y', $additionalFiledValue->$addFieldName);
                                        if ($date != $addFieldValue) {
                                            $changeUpdateDate = true;
                                        }
                                    }
                                } else {
                                    $changeUpdateDate = true;
                                }
                            }
                            $additionalFiledValue->$addFieldName = $addFieldValue;
                            if ($addFieldName == 'fieldFio') {
                                $client->name = $addFieldValue;
                            }
                        }
                    }
                    $additionalFiledValue = Yii::app()->commonFunction->convertAdditionalField($additionalFiledValue, $additionalFiledValuesInClient);
                    if ($changeUpdateDate) {
                        $client->change_client_date = date('Y-m-d H:i');
                    }


                    if (isset($_POST['Clients']['additionalField'])) {
                        $saveAddField = Yii::app()->commonFunction->checkAdditionalFiledValue($additionalFiledValue, $additionalFiledValuesInClient);
                        $errorAddFieldText = '';
                        if ($saveAddField['status'] == 'success') {
                            if (!$resultTransaction = $additionalFiledValue->save()) {
                                $errorAddFieldText .= $additionalFiledValue->getError('duplicate') ?? '';
                                $errorAddFieldText .= '<br>';
                                $errorAddField = true;
                            }
                        } elseif($saveAddField['status'] == 'failed') {
                            $errorAddField = true;
                            foreach ($saveAddField['type'] as $type) {
                                switch ($type) {
                                    case 'incorrectData':
                                        $errorAddFieldText.= 'Некорректное значение в поле.';
                                        break;
                                    case 'requiredData':
                                        $errorAddFieldText.= 'Пропущено обязательное поле';
                                        break;
                                }
                                $errorAddFieldText.= '<br>';
                            }
                            $resultTransaction = false;
                        }
                    } 
                    if (!$errorAddField) {
                        $client->save();    
                    }
                    echo json_encode(['err'=>$errorAddFieldText]);
                    break;
            }   
        } 
 
    }

    public function actionEdit_note_ajax () {
        
        $id = Yii::app()->request->getParam('id');                 
        $client_id = Yii::app()->request->getParam('client_id');    
        $color = Yii::app()->request->getParam('color'); 
        $text = Yii::app()->request->getParam('text'); 
        if ( $id >0 ) {
            $ClientsNote = ClientsNotes::model()->findByPk($id);
            $ClientsNote->edit_user_id = Yii::app()->user->id;
            $ClientsNote->edited = Time();

        } else {                                 
            $ClientsNote = new ClientsNotes;    
            $ClientsNote->user_id = Yii::app()->user->id;
            $ClientsNote->added = Time();
            $ClientsNote->client_id = $client_id;   
        }
        $ClientsNote->text = $text;      
        $ClientsNote->color = $color;                                 
        $ClientsNote->save();
         $this->renderPartial('_note_box', ['note'=>$ClientsNote]);       
    }
    
    public function actionDel_note_ajax () {
        $id = Yii::app()->request->getParam('id');     
        $ClientsNote = ClientsNotes::model()->findByPk($id) ;                                 
        $ClientsNote->delete();
        
    }

    public function actionSettings_deals()
    {
        $user = Users::model()->with('roles', 'userRights')->findByPk(Yii::app()->user->id);
        if ($user->roles[0]->name == 'admin') {

            $resultTransaction = false;
            $isShowBlockSave = false;
            $errorMessageList = [];
            $customReason = [];
            $transaction = Yii::app()->db->beginTransaction();
            $reasons = DealsReason::model()->findAll(['order'=>'t.weight']);
            $listTabs = [
                'refusal' => 'Отказы'
            ];

            if (isset($_POST['Reasons']) && $_POST['Reasons']) {

                // делаю массив для коллекции. Так как причина с одинаковыми параметрами например (весом) может быть изменена раньше, чем удалена или отредактированная другая.
                $savesReasons = [];
                foreach ($reasons as $value) {
                    $editReason = isset($_POST['Reasons'][$value->id]) ? $_POST['Reasons'][$value->id] : null;
                    if ($editReason) {
                        //редачим
                        if ($value->name !== $editReason['name'] || $value->weight != $editReason['weight']
                        ) {
                            $value->name = $editReason['name'];
                            $value->weight = $editReason['weight'];
                            $savesReasons [] = $value;
                        }

                    } else {
                        //удаляем
                        DealsReason::model()->deleteByPk($value->id);
                    }
                }

                foreach ($savesReasons as $value) {
                    if (!$value->save()) {
                        $errorMessageList = $value->getErrors();
                        break;
                    }
                }

                $isSave = true;
                foreach ($_POST['Reasons'] as $value) {
                    // сохраняем новые
                    if (!isset($value['id'])) {
                        if ($isSave) {
                            $newReason = new DealsReason();
                            $newReason->name = $value['name'];
                            $newReason->weight = $value['weight'];
                            // в ходе доработки Сан решил отказаться от этого поля.
                            // оставляю на всякий случай, забивая дефолтным значением
                            $newReason->is_default = 0;
                            if (!$newReason->save()) {
                                $errorMessageList = $newReason->getErrors();
                                $isSave = false;
                            }
                        }
                        $customReason [] = $value;
                    }
                }

                if (!$errorMessageList) {
                    if (DealsReason::checkDuplicateWeight()) {
                        $resultTransaction = true;
                    } else {
                        $errorMessageList[] = ["Причина с таким номером уже существует"];
                    }
                }

                if ($resultTransaction) {
                    $transaction->commit();
                    $customReason = [];
                    $reasons = DealsReason::model()->findAll(['order'=>'t.weight']);
                    $dealsLose = Deals::model()->findAll('deal_type_id = 3');
                    $reasonDefaultId = $reasons[0]->id;

                    foreach ($dealsLose as $value) {
                        if (!DealAndReason::model()->find('deals_id = ' . $value->id)) {
                            $dealsAndReasons = new DealAndReason();
                            $dealsAndReasons->deals_id = $value->id;
                            $dealsAndReasons->deals_reason_id = $reasonDefaultId;
                            $dealsAndReasons->save();
                        }
                    }
                    $isShowBlockSave = true;
                } else {
                    $transaction->rollback();
                }
            }

            $this->render('settings_deals',
                [
                    'user' => $user,
                    'listTabs' => $listTabs,
                    'reasons' => $reasons,
                    'errorMessageList' => $errorMessageList,
                    'customReason' => $customReason,
                    'isShowBlockSave' => $isShowBlockSave,
                ]
            );
        } else {
            throw new CHttpException(412, 'no_access_settings');
        }
    }

    public function actionClients_filters_add() {
        $user = Users::model()->with('roles', 'userRights')->findByPk(Yii::app()->user->id);
        $clientInfoLeft = [
            'name' => 'Данные о контакте',
            'active' => false,
            'fields' => [
                'is_id_client' => [
                    'name' => 'ID клиента',
                    'active' => false,
                ],
                'is_last_change' => [
                    'name' => 'Последнее изменение',
                    'active' => false,
                ],
                'is_create_date' => [
                    'name' => 'Дата создания',
                    'active' => false,
                ],
                'is_responsible' => [
                    'name' => 'Ответственный',
                    'active' => false,
                ],
                'is_step' => [
                    'name' => 'Воронка',
                    'active' => false,
                ],
                'is_option_step' => [
                    'name' => 'Этап воронки',
                    'active' => false,
                ],
            ],

        ];

        $clientInfoRight = $clientInfoLeft;

        $clientInfoLeft['fields']['is_id_client']['active'] = true;
        $clientInfoLeft['fields']['is_create_date']['active'] = true;
        $clientInfoLeft['fieldsCount'] = count($clientInfoLeft['fields']);

        $clientInfoRight['fields']['is_responsible']['active'] = true;
        $clientInfoRight['fields']['is_option_step']['active'] = true;
        $clientInfoRight['fieldsCount'] = count($clientInfoRight['fields']);

        $additionalFields = AdditionalFields::model()->findAll();
        $sections = AdditionalFieldsSection::model()->findAll();
        $sectionFields = [];

        $accessSection = Clients::getAccessSection();
        $accessSectionIds = array_keys($accessSection);

        foreach ($sections as $item) {
            if (in_array($item->id, $accessSectionIds)) {
                $sectionFields [$item->id]['name'] = $item->name;
                $sectionFields [$item->id]['active'] = false;
                $sectionFields [$item->id]['fieldsCount'] = count(AdditionalFields::model()->findAll("section_id=:SID", [":SID" => $item->id]));

                //декриментируем из-за фио, которые вырезается
                if ($item->id == 1) {
                    $sectionFields[$item->id]['fieldsCount']--;
                }
            }
        }

        foreach ($additionalFields as $item) {
            if (in_array($item->section_id, $accessSectionIds)) {
                if ($item->table_name != 'fieldFio') {
                    $sectionFields [$item->section_id]['fields'][$item->id] = [
                        'id' => $item->id,
                        'name' => $item->name,
                        'table_name' => $item->table_name,
                        'active' => false,
                    ];
                }
            }
        }

        $Steps = Steps::model()->findAll(['condition' => 'steps_type_id = :TYPE', 'order' => 'weight', 'params' => [':TYPE' => 1]]);

        $filterSteps = [
            0 => [
                'active' => true,
                'name' => 'Нет воронки',
                'optionsCount' => 0,
                'options' => [],
            ]
        ];

        foreach ($Steps as $step) {
            if ($options = StepsOptions::model()->findAll(['condition' => 'steps_id = :ID', 'order' => 'weight', 'params' =>[':ID' => $step->id]])) {
                $filterSteps[$step->id]['active'] = true;
                $filterSteps[$step->id]['name'] = $step->name;
                $filterSteps[$step->id]['optionsCount'] = count($options);

                foreach ($options as $option) {
                    $filterSteps[$step->id]['options'][$option->id] = $option->attributes;
                    $filterSteps[$step->id]['options'][$option->id]['active'] = true;
                }
            }
        }

        $users = Users::getAccessUsersForFilter($user);
        $filterUsers = [];

        foreach ($users as $value) {
            if (!isset($filterUsers[$value->roles[0]->name]['usersCount'])) {
                $filterUsers[$value->roles[0]->name]['usersCount'] = 0;
            }
            $avatar = $value->avatar;
            if (!$avatar) {
                switch ($value->roles[0]->name) {
                    case 'admin': {
                        $avatar = '/img/ava_admin.svg';
                        break;
                    }
                    case 'director': {
                        $avatar = '/img/ava_adminisrtr.svg';
                        break;
                    }
                    case 'manager': {
                        $avatar = '/img/employee.svg';
                        break;
                    }
                }
            }

            $filterUsers[$value->roles[0]->name]['active'] = true;
            $filterUsers[$value->roles[0]->name]['usersCount'] += 1 ;

            $filterUsers[$value->roles[0]->name]['users'][$value->id]['name'] = $value->first_name;
            $filterUsers[$value->roles[0]->name]['users'][$value->id]['id'] = $value->id;
            $filterUsers[$value->roles[0]->name]['users'][$value->id]['active'] = true;
            $filterUsers[$value->roles[0]->name]['users'][$value->id]['avatar'] = $avatar;
        }

        $allLabels = Labels::model()->findAll('type_id = 1');
        $filterLabels = [];
        foreach ($allLabels as $label) {
            $filterLabels[$label->id] = $label->attributes;
            $filterLabels[$label->id]['active'] = false;
        }

        $this->render('clients_filters_add',
            [
                'user' => $user,
                'sectionFields' => $sectionFields,
                'clientInfoLeft' => $clientInfoLeft,
                'clientInfoRight' => $clientInfoRight,
                'filterLabels' => $filterLabels,
                'filterSteps' => $filterSteps,
                'filterUsers' => $filterUsers,
                'allLabels' => $allLabels,
                'filterColors' => $this->filterColors,
                'classNameColorDefault' => array_keys($this->filterColors)[0],
            ]
        );
    }

    // API фильтров для клиентов
    public function actionAddFilterForClients() {
        $return = [
            'status' => 'error',
            'errors' => [],
            'values' => []
        ];

        $isError = false;
        $transaction = Yii::app()->db->beginTransaction();

        // фильтр общее
        if (isset($_POST['clientFilters'])) {
            $modelClientFilter = new ClientFilters();
            $modelClientFilter->setAttributes($_POST['clientFilters']);

            if (!$modelClientFilter->save()) {
                $return['errors'] = $modelClientFilter->getErrors();
                $isError = true;
            }

            // блоки с полями
            if (!$isError && isset($_POST['clientFiltersBlock'])
                && isset($_POST['clientFiltersBlock']['left'])
                && isset($_POST['clientFiltersBlock']['right'])
                && isset($_POST['clientFiltersBlock']['left']['additionalFields'])
                && isset($_POST['clientFiltersBlock']['right']['additionalFields'])
                && isset($_POST['clientFiltersBlock']['left']['clientInfo'])
                && isset($_POST['clientFiltersBlock']['right']['clientInfo'])
            ) {

                $leftClientInfo = $_POST['clientFiltersBlock']['left']['clientInfo'];
                $rightClientInfo = $_POST['clientFiltersBlock']['right']['clientInfo'];

                $leftAddFields = $_POST['clientFiltersBlock']['left']['additionalFields'] ? $_POST['clientFiltersBlock']['left']['additionalFields'] : [];
                $rightAddFields = $_POST['clientFiltersBlock']['right']['additionalFields'] ? $_POST['clientFiltersBlock']['right']['additionalFields'] : [];

                // валидация для блоков
                $countFieldsLeft = 0;
                $countFieldsLeft = $countFieldsLeft + count($leftAddFields);

                foreach ($leftClientInfo as $value) {
                    if ($value) {
                        $countFieldsLeft++;
                    }
                }

                if (!$countFieldsLeft) {
                    $return['errors'] = ['countFields' => 'Нужно выбрать не менее 2-х параметров'];
                    $isError = true;
                }

                $countFieldsRight = 0;
                $countFieldsRight = $countFieldsRight + count($rightAddFields);

                foreach ($rightClientInfo as $value) {
                    if ($value) {
                        $countFieldsRight++;
                    }
                }

                if ($countFieldsRight > 4) {
                    $return['errors'] = ['countFields' => 'Только до 4-х параметров'];
                    $isError = true;
                }

                if (!$isError) {
                    // клиентская инфа
                    $modalClientFiltersBlockInfoLeft = new ClientFiltersBlockInfo();
                    $modalClientFiltersBlockInfoLeft->client_filters_id = $modelClientFilter->id;
                    $modalClientFiltersBlockInfoLeft->client_filters_block_type_id = 1; //left
                    $modalClientFiltersBlockInfoLeft->setAttributes($leftClientInfo);
                    $modalClientFiltersBlockInfoLeft->setScenario('scenarioLeftBlock');

                    if (!$modalClientFiltersBlockInfoLeft->save()) {
                        $return['errors'] = $modalClientFiltersBlockInfoLeft->getErrors();
                        $isError = true;
                    }

                    $modalClientFiltersBlockInfoRight = new ClientFiltersBlockInfo();
                    $modalClientFiltersBlockInfoRight->client_filters_id = $modelClientFilter->id;
                    $modalClientFiltersBlockInfoRight->client_filters_block_type_id = 2; //right
                    $modalClientFiltersBlockInfoRight->setAttributes($rightClientInfo);
                    $modalClientFiltersBlockInfoRight->setScenario('scenarioRightBlock');

                    if (!$modalClientFiltersBlockInfoRight->save()) {
                        $return['errors'] = $modalClientFiltersBlockInfoRight->getErrors();
                        $isError = true;
                    }

                    // Доп поля
                    foreach ($leftAddFields as $id) {
                        $modalClientFiltersBlockAddFieldsLeft = new ClientFiltersBlockAdditionalFields();
                        $modalClientFiltersBlockAddFieldsLeft->client_filters_block_type_id = 1; //left
                        $modalClientFiltersBlockAddFieldsLeft->client_filters_id = $modelClientFilter->id;
                        $modalClientFiltersBlockAddFieldsLeft->additional_fields_id = $id;

                        if (!$modalClientFiltersBlockAddFieldsLeft->save()) {
                            $return['errors'] = $modalClientFiltersBlockAddFieldsLeft->getErrors();
                            $isError = true;
                            break;
                        }
                    }

                    foreach ($rightAddFields as $id) {
                        $modalClientFiltersBlockAddFieldsRight = new ClientFiltersBlockAdditionalFields();
                        $modalClientFiltersBlockAddFieldsRight->client_filters_block_type_id = 2; //right
                        $modalClientFiltersBlockAddFieldsRight->client_filters_id = $modelClientFilter->id;
                        $modalClientFiltersBlockAddFieldsRight->additional_fields_id = $id;

                        if (!$modalClientFiltersBlockAddFieldsRight->save()) {
                            $return['errors'] = $modalClientFiltersBlockAddFieldsRight->getErrors();
                            $isError = true;
                            break;
                        }
                    }
                }
            }

            // Опции воронки
            if (!$isError && isset($_POST['clientFiltersStepOptions'])) {
                $optionList = $_POST['clientFiltersStepOptions'] ? $_POST['clientFiltersStepOptions'] : [];

                foreach ($optionList as $id) {
                    $modalClientFiltersStepOption = new ClientFiltersStepOptions();
                    $modalClientFiltersStepOption->client_filters_id = $modelClientFilter->id;
                    $modalClientFiltersStepOption->steps_options_id = $id == 0 ? null : $id;

                    if (!$modalClientFiltersStepOption->save()) {
                        $return['errors'] = $modalClientFiltersStepOption->getErrors();
                        $isError = true;
                        break;
                    }
                }
            }

            // Лейблы
            if (!$isError && isset($_POST['clientFiltersLabels'])) {
                $labelList = $_POST['clientFiltersLabels'] ? $_POST['clientFiltersLabels'] : [];

                foreach ($labelList as $id) {
                    $modalClientFiltersLabel = new ClientFiltersLabels();
                    $modalClientFiltersLabel->client_filters_id = $modelClientFilter->id;
                    $modalClientFiltersLabel->labels_id = $id;

                    if (!$modalClientFiltersLabel->save()) {
                        $return['errors'] = $modalClientFiltersLabel->getErrors();
                        $isError = true;
                        break;
                    }
                }
            }

            // Ответственные
            if (!$isError && isset($_POST['clientFiltersResponsibles'])) {
                $responsibleList = $_POST['clientFiltersResponsibles'] ? $_POST['clientFiltersResponsibles'] : [];

                foreach ($responsibleList as $id) {
                    $modalClientFiltersResponsible = new ClientFiltersResponsibles();
                    $modalClientFiltersResponsible->client_filters_id = $modelClientFilter->id;
                    $modalClientFiltersResponsible->users_id = $id;

                    if (!$modalClientFiltersResponsible->save()) {
                        $return['errors'] = $modalClientFiltersResponsible->getErrors();
                        $isError = true;
                        break;
                    }
                }
            }
        }

        if ($isError) {
            $transaction->rollback();
        } else {
            $transaction->commit();
            $return['status'] = 'success';
            $return['description'] = 'Фильтр "' . $modelClientFilter->name . '" успешно создан';
            $return['values']['id'] = $modelClientFilter->id;
        }

        Json::render($return);
    }

    public function actionClients_filters_edit($filterId = null, $isAddFilter = false, $isEditFilter = false) {
        $user = Users::model()->with('roles', 'userRights')->findByPk(Yii::app()->user->id);

        if ($filterId != 1) {
            $modelClientFilter = ClientFilters::model()->findByPk($filterId);

            if ($modelClientFilter) {
                if ($modelClientFilter->author == $user->id) {
                    $modelClientFiltersBlockInfoLeft = ClientFiltersBlockInfo::model()->find('client_filters_id=:ID && client_filters_block_type_id=:TP', [':ID' => $modelClientFilter->id, ':TP' => 1]);
                    $modelClientFiltersBlockInfoRight = ClientFiltersBlockInfo::model()->find('client_filters_id=:ID && client_filters_block_type_id=:TP', [':ID' => $modelClientFilter->id, ':TP' => 2]);

                    // клиентская инфа
                    $clientInfoLeft = [
                        'name' => 'Данные о контакте',
                        'active' => false,
                        'fields' => [
                            'is_id_client' => [
                                'name' => 'ID клиента',
                                'active' => false,
                            ],
                            'is_last_change' => [
                                'name' => 'Последнее изменение',
                                'active' => false,
                            ],
                            'is_create_date' => [
                                'name' => 'Дата создания',
                                'active' => false,
                            ],
                            'is_responsible' => [
                                'name' => 'Ответственный',
                                'active' => false,
                            ],
                            'is_step' => [
                                'name' => 'Воронка',
                                'active' => false,
                            ],
                            'is_option_step' => [
                                'name' => 'Этап воронки',
                                'active' => false,
                            ],
                        ],
                    ];

                    $clientInfoRight = $clientInfoLeft;

                    foreach ($clientInfoLeft['fields'] as $key => $value) {
                        $clientInfoLeft['fields'][$key]['active'] = (boolean) $modelClientFiltersBlockInfoLeft->$key;
                    }

                    foreach ($clientInfoRight['fields'] as $key => $value) {
                        $clientInfoRight['fields'][$key]['active'] = (boolean) $modelClientFiltersBlockInfoRight->$key;
                    }

                    $clientInfoLeft['fieldsCount'] = count($clientInfoLeft['fields']);
                    $clientInfoRight['fieldsCount'] = count($clientInfoRight['fields']);

                    // Доп поля
                    $additionalFields = AdditionalFields::model()->findAll();
                    $sections = AdditionalFieldsSection::model()->with('additionalFields', 'sectorInGroups')->findAll('');

                    $sectionFieldsLeft = [];
                    $sectionFieldsRight = [];

                    $accessSection = Clients::getAccessSection();
                    $accessSectionIds = array_keys($accessSection);

                    foreach ($sections as $item) {
                        if (in_array($item->id, $accessSectionIds)) {
                            $sectionFieldsLeft[$item->id]['name'] = $item->name;
                            $sectionFieldsLeft[$item->id]['active'] = false;
                            $sectionFieldsLeft[$item->id]['fieldsCount'] = count(AdditionalFields::model()->findAll("section_id=:SID", [":SID" => $item->id]));

                            $sectionFieldsRight[$item->id]['name'] = $item->name;
                            $sectionFieldsRight[$item->id]['active'] = false;
                            $sectionFieldsRight[$item->id]['fieldsCount'] = count(AdditionalFields::model()->findAll("section_id=:SID", [":SID" => $item->id]));

                            //декриментируем из-за фио, которые вырезается
                            if ($item->id == 1) {
                                $sectionFieldsLeft[$item->id]['fieldsCount']--;
                                $sectionFieldsRight[$item->id]['fieldsCount']--;
                            }
                        }
                    }

                    foreach ($additionalFields as $item) {
                        if (in_array($item->section_id, $accessSectionIds)) {
                            if ($item->table_name != 'fieldFio') {
                                $leftBlockField = ClientFiltersBlockAdditionalFields::model()->exists('client_filters_id=:ID && client_filters_block_type_id=:TP && additional_fields_id=:AF', [':ID' => $modelClientFilter->id, ':TP' => 1, ':AF' => $item->id]);
                                $rightBlockField = ClientFiltersBlockAdditionalFields::model()->exists('client_filters_id=:ID && client_filters_block_type_id=:TP && additional_fields_id=:AF', [':ID' => $modelClientFilter->id, ':TP' => 2, ':AF' => $item->id]);

                                $sectionFieldsLeft[$item->section_id]['fields'][$item->id] = [
                                    'id' => $item->id,
                                    'name' => $item->name,
                                    'table_name' => $item->table_name,
                                    'active' => $leftBlockField,
                                ];

                                $sectionFieldsRight[$item->section_id]['fields'][$item->id] = [
                                    'id' => $item->id,
                                    'name' => $item->name,
                                    'table_name' => $item->table_name,
                                    'active' => $rightBlockField,
                                ];
                            }
                        }
                    }

                    // опции воронки
                    $Steps = Steps::model()->findAll(['condition' => 'steps_type_id = :TYPE', 'order' => 'weight', 'params' => [':TYPE' => 1]]);

                    $filterSteps = [
                        0 => [
                            'active' => ClientFiltersStepOptions::model()->exists('client_filters_id=:ID && steps_options_id is null', [':ID' => $modelClientFilter->id]),
                            'name' => 'Нет воронки',
                            'optionsCount' => 0,
                            'options' => [],
                        ]
                    ];

                    foreach ($Steps as $step) {
                        if ($options = StepsOptions::model()->findAll(['condition' => 'steps_id = :ID', 'order' => 'weight', 'params' =>[':ID' => $step->id]])) {
                            $filterSteps[$step->id]['active'] = true;
                            $filterSteps[$step->id]['name'] = $step->name;
                            $filterSteps[$step->id]['optionsCount'] = count($options);

                            foreach ($options as $option) {
                                $filterSteps[$step->id]['options'][$option->id] = $option->attributes;
                                $filterSteps[$step->id]['options'][$option->id]['active'] = ClientFiltersStepOptions::model()->exists('client_filters_id=:ID && steps_options_id=:SO', [':ID' => $modelClientFilter->id, ':SO' => $option->id]);
                            }
                        }
                    }

                    // ответственные
                    $users = Users::getAccessUsersForFilter($user);

                    $filterUsers = [];

                    foreach ($users as $value) {
                        if (!isset($filterUsers[$value->roles[0]->name]['usersCount'])) {
                            $filterUsers[$value->roles[0]->name]['usersCount'] = 0;
                        }
                        $avatar = $value->avatar;
                        if (!$avatar) {
                            switch ($value->roles[0]->name) {
                                case 'admin': {
                                    $avatar = '/img/ava_admin.svg';
                                    break;
                                }
                                case 'director': {
                                    $avatar = '/img/ava_adminisrtr.svg';
                                    break;
                                }
                                case 'manager': {
                                    $avatar = '/img/employee.svg';
                                    break;
                                }
                            }
                        }

                        $filterUsers[$value->roles[0]->name]['active'] = true;
                        $filterUsers[$value->roles[0]->name]['usersCount'] += 1 ;

                        $filterUsers[$value->roles[0]->name]['users'][$value->id]['name'] = $value->first_name;
                        $filterUsers[$value->roles[0]->name]['users'][$value->id]['id'] = $value->id;
                        $filterUsers[$value->roles[0]->name]['users'][$value->id]['active'] = ClientFiltersResponsibles::model()->exists('client_filters_id=:ID && users_id=:UI', [':ID' => $modelClientFilter->id, ':UI' => $value->id]);
                        $filterUsers[$value->roles[0]->name]['users'][$value->id]['avatar'] = $avatar;
                    }

                    // лейблы
                    $allLabels = Labels::model()->findAll('type_id = 1');
                    $filterLabels = [];

                    foreach ($allLabels as $label) {
                        $filterLabels[$label->id] = $label->attributes;
                        $filterLabels[$label->id]['active'] = ClientFiltersLabels::model()->exists('client_filters_id=:ID && labels_id=:LI', [':ID' => $modelClientFilter->id, ':LI' => $label->id]);;
                    }

                    if (!$modelClientFilter->class_name || !isset($this->filterColors[$modelClientFilter->class_name])) {
                        $modelClientFilter->class_name = array_keys($this->filterColors)[0];
                    }

                    $this->render('clients_filters_edit',
                        [
                            'user' => $user,
                            'sectionFieldsLeft' => $sectionFieldsLeft,
                            'sectionFieldsRight' => $sectionFieldsRight,
                            'clientInfoLeft' => $clientInfoLeft,
                            'clientInfoRight' => $clientInfoRight,
                            'filterLabels' => $filterLabels,
                            'filterSteps' => $filterSteps,
                            'filterUsers' => $filterUsers,
                            'allLabels' => $allLabels,
                            'modelClientFilter' => $modelClientFilter,
                            'isAddFilter' => $isAddFilter == 'true',
                            'isEditFilter' => $isEditFilter == 'true',
                            'filterColors' => $this->filterColors,
                        ]
                    );
                } else {
                    throw new CHttpException(412, 'no_access_edit_filter');
                }
            } else {
                throw new CHttpException(404, 'not_found_page');
            }
        } else {
            throw new CHttpException(404, 'not_found_page');
        }
    }

    // API редактирование фильтра
    public function actionEditFilterForClients() {
        $return = [
            'status' => 'error',
            'errors' => [],
            'values' => []
        ];

        $isError = false;
        $transaction = Yii::app()->db->beginTransaction();

        // фильтр общее
        if (isset($_POST['clientFilters'])) {
            $modelClientFilter = ClientFilters::model()->findByPk($_POST['clientFilters']['id']);
            $modelClientFilter->setAttributes($_POST['clientFilters']);

            if ($modelClientFilter->id != 1) {
                if ($modelClientFilter->author == Yii::app()->user->id) {
                    if (!$modelClientFilter->save()) {
                        $return['errors'] = $modelClientFilter->getErrors();
                        $isError = true;
                    }

                    // блоки с полями
                    if (!$isError && isset($_POST['clientFiltersBlock'])
                        && isset($_POST['clientFiltersBlock']['left'])
                        && isset($_POST['clientFiltersBlock']['right'])
                        && isset($_POST['clientFiltersBlock']['left']['additionalFields'])
                        && isset($_POST['clientFiltersBlock']['right']['additionalFields'])
                        && isset($_POST['clientFiltersBlock']['left']['clientInfo'])
                        && isset($_POST['clientFiltersBlock']['right']['clientInfo'])
                    ) {

                        $leftClientInfo = $_POST['clientFiltersBlock']['left']['clientInfo'];
                        $rightClientInfo = $_POST['clientFiltersBlock']['right']['clientInfo'];

                        $leftAddFields = $_POST['clientFiltersBlock']['left']['additionalFields'] ? $_POST['clientFiltersBlock']['left']['additionalFields'] : [];
                        $rightAddFields = $_POST['clientFiltersBlock']['right']['additionalFields'] ? $_POST['clientFiltersBlock']['right']['additionalFields'] : [];

                        // валидация для блоков
                        $countFieldsLeft = 0;
                        $countFieldsLeft = $countFieldsLeft + count($leftAddFields);

                        foreach ($leftClientInfo as $value) {
                            if ($value) {
                                $countFieldsLeft++;
                            }
                        }

                        if (!$countFieldsLeft) {
                            $return['errors'] = ['countFields' => ['Нужно выбрать не менее 2-х параметров']];
                            $isError = true;
                        }

                        $countFieldsRight = 0;
                        $countFieldsRight = $countFieldsRight + count($rightAddFields);

                        foreach ($rightClientInfo as $value) {
                            if ($value) {
                                $countFieldsRight++;
                            }
                        }

                        if ($countFieldsRight > 4) {
                            $return['errors'] = ['countFields' => ['Только до 4-х параметров']];
                            $isError = true;
                        }

                        if (!$isError) {
                            // клиентская инфа
                            $modalClientFiltersBlockInfoLeft = ClientFiltersBlockInfo::model()->find('client_filters_id=:ID && client_filters_block_type_id=:TP', [':ID' => $modelClientFilter->id, ':TP' => 1]);
                            $modalClientFiltersBlockInfoLeft->setAttributes($leftClientInfo);
                            $modalClientFiltersBlockInfoLeft->setScenario('scenarioLeftBlock');

                            if (!$modalClientFiltersBlockInfoLeft->save()) {
                                $return['errors'] = $modalClientFiltersBlockInfoLeft->getErrors();
                                $isError = true;
                            }

                            $modalClientFiltersBlockInfoRight = ClientFiltersBlockInfo::model()->find('client_filters_id=:ID && client_filters_block_type_id=:TP', [':ID' => $modelClientFilter->id, ':TP' => 2]);
                            $modalClientFiltersBlockInfoRight->setAttributes($rightClientInfo);
                            $modalClientFiltersBlockInfoRight->setScenario('scenarioRightBlock');

                            if (!$modalClientFiltersBlockInfoRight->save()) {
                                $return['errors'] = $modalClientFiltersBlockInfoRight->getErrors();
                                $isError = true;
                            }

                            // Доп поля
                            //удаляем все доп поля фильтра, чтобы записать новые
                            ClientFiltersBlockAdditionalFields::model()->deleteAll('client_filters_id=:ID', [':ID' => $modelClientFilter->id]);

                            foreach ($leftAddFields as $id) {
                                $modalClientFiltersBlockAddFieldsLeft = new ClientFiltersBlockAdditionalFields();
                                $modalClientFiltersBlockAddFieldsLeft->client_filters_block_type_id = 1; //left
                                $modalClientFiltersBlockAddFieldsLeft->client_filters_id = $modelClientFilter->id;
                                $modalClientFiltersBlockAddFieldsLeft->additional_fields_id = $id;

                                if (!$modalClientFiltersBlockAddFieldsLeft->save()) {
                                    $return['errors'] = $modalClientFiltersBlockAddFieldsLeft->getErrors();
                                    $isError = true;
                                    break;
                                }
                            }

                            foreach ($rightAddFields as $id) {
                                $modalClientFiltersBlockAddFieldsRight = new ClientFiltersBlockAdditionalFields();
                                $modalClientFiltersBlockAddFieldsRight->client_filters_block_type_id = 2; //right
                                $modalClientFiltersBlockAddFieldsRight->client_filters_id = $modelClientFilter->id;
                                $modalClientFiltersBlockAddFieldsRight->additional_fields_id = $id;

                                if (!$modalClientFiltersBlockAddFieldsRight->save()) {
                                    $return['errors'] = $modalClientFiltersBlockAddFieldsRight->getErrors();
                                    $isError = true;
                                    break;
                                }
                            }
                        }
                    }

                    // Опции воронки
                    if (!$isError && isset($_POST['clientFiltersStepOptions'])) {
                        $optionList = $_POST['clientFiltersStepOptions'] ? $_POST['clientFiltersStepOptions'] : [];

                        //удаляем все опции фильтра, чтобы записать новые
                        ClientFiltersStepOptions::model()->deleteAll('client_filters_id=:ID', [':ID' => $modelClientFilter->id]);

                        foreach ($optionList as $id) {
                            $modalClientFiltersStepOption = new ClientFiltersStepOptions();
                            $modalClientFiltersStepOption->client_filters_id = $modelClientFilter->id;
                            $modalClientFiltersStepOption->steps_options_id = $id == 0 ? null : $id;

                            if (!$modalClientFiltersStepOption->save()) {
                                $return['errors'] = $modalClientFiltersStepOption->getErrors();
                                $isError = true;
                                break;
                            }
                        }
                    }

                    // Лейблы
                    if (!$isError && isset($_POST['clientFiltersLabels'])) {
                        $labelList = $_POST['clientFiltersLabels'] ? $_POST['clientFiltersLabels'] : [];

                        //удаляем все лейблы фильтра, чтобы записать новые
                        ClientFiltersLabels::model()->deleteAll('client_filters_id=:ID', [':ID' => $modelClientFilter->id]);

                        foreach ($labelList as $id) {
                            $modalClientFiltersLabel = new ClientFiltersLabels();
                            $modalClientFiltersLabel->client_filters_id = $modelClientFilter->id;
                            $modalClientFiltersLabel->labels_id = $id;

                            if (!$modalClientFiltersLabel->save()) {
                                $return['errors'] = $modalClientFiltersLabel->getErrors();
                                $isError = true;
                                break;
                            }
                        }
                    }

                    // Ответственные
                    if (!$isError && isset($_POST['clientFiltersResponsibles'])) {
                        $responsibleList = $_POST['clientFiltersResponsibles'] ? $_POST['clientFiltersResponsibles'] : [];

                        //удаляем всех отвественных, чтобы записать новые
                        ClientFiltersResponsibles::model()->deleteAll('client_filters_id=:ID', [':ID' => $modelClientFilter->id]);

                        foreach ($responsibleList as $id) {
                            $modalClientFiltersResponsible = new ClientFiltersResponsibles();
                            $modalClientFiltersResponsible->client_filters_id = $modelClientFilter->id;
                            $modalClientFiltersResponsible->users_id = $id;

                            if (!$modalClientFiltersResponsible->save()) {
                                $return['errors'] = $modalClientFiltersResponsible->getErrors();
                                $isError = true;
                                break;
                            }
                        }
                    }
                } else {
                    $return['errors'] = ['notAccess' => 'Увас нетправ на изменение этого фильтра'];
                    $isError = true;
                }
            } else {
                $return['errors'] = ['notDelete' => 'Нельзя удалить фильтр по умолчанию'];
                $isError = true;
            }
        }

        if ($isError) {
            $transaction->rollback();
        } else {
            $modelClient = new Clients();
            $modelClientFiltersStepOptions = ClientFiltersStepOptions::model()->findAll('client_filters_id=:ID', [':ID' => $modelClientFilter->id]);
            $modelClientFiltersLabels = ClientFiltersLabels::model()->findAll('client_filters_id=:ID', [':ID' => $modelClientFilter->id]);
            $modelClientFiltersResponsibles = ClientFiltersResponsibles::model()->findAll('client_filters_id=:ID', [':ID' => $modelClientFilter->id]);

            $labelIds = array_column($modelClientFiltersLabels, 'labels_id');
            $responsibleIds = array_column($modelClientFiltersResponsibles, 'users_id');
            foreach ($modelClientFiltersStepOptions as $value) {
                $stepOptionsIds [] = $value->steps_options_id;
            }
            $isFiles = $modelClientFilter->is_files;
            $pageSize = $modelClientFilter->page_size;
            $countClients = count($modelClient->searchForFilter(false, $isFiles, $labelIds, $responsibleIds, $stepOptionsIds, $pageSize, null));

            $return['status'] = 'success';
            $return['description'] = 'Информация в фильтре обновлена';
            $return['values'] = $modelClientFilter->getAttributes();
            $return['values'] ['countClients'] = $countClients;
            $transaction->commit();
        }

        Json::render($return);
    }

    public function actionDeleteFilterForClients() {
        $return = [
            'status' => 'error',
            'errors' => [],
            'values' => []
        ];

        if (isset($_POST['filterId'])) {
            if ($_POST['filterId'] != 1) {
                $modelClientFilter = ClientFilters::model()->findByPk($_POST['filterId']);
                if ($modelClientFilter) {
                    ClientFilters::model()->deleteByPk($_POST['filterId']);
                    $return['status'] = 'success';
                    $return['description'] = 'Фильтр удален';
                } else {
                    $return['errors'] = ['notFiend' => ['Данный фильтр не найден в бд']];
                }
            } else {
                $return['errors'] = ['notDelete' => ['Нельзя удалить фильтр по умолчанию']];
            }
        } else {

        }

        Json::render($return);
	}
}

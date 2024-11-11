<?

Yii::import('zii.widgets.grid.CGridView');
Yii::import('application.components.extended.web.widgets.MyCDataColumn');
Yii::import('application.components.extended.web.widgets.MyCLinkPager');

class MyCGridView extends CGridView
{
    public $displayVar;
    public $pageVar;
    public $sortVar;
	public function init()
	{
        $this->displayVar = $this->getId().'display';
        $this->pageVar = $this->getId().'page';
        $this->sortVar = $this->getId().'sort';

        $this->enablePagination = true;
        $this->enableSorting = true;
        $this->dataProvider->getPagination()->setPageSize(Yii::app()->request->getParam($this->getId().'display') ? Yii::app()->request->getParam($this->getId().'display') : 50);
        $this->dataProvider->getPagination()->pageVar = $this->getId().'page';

		$display = Yii::app()->request->getParam($this->displayVar);

		$this->ajaxUpdate = false;
		$this->summaryText = '
				<ul>
					<li>' . Yii::t('menu', 'Всего') . ': {count}</li>
				</ul>';
		$data = $this->dataProvider;
		$pagerBlock = $data->getTotalItemCount() > 50 ?
			'<div class="pager-block">
				<div class="left">{summary}</div>
				<div class="right">{pager}</div>
	  		</div>' : null;
		$this->template =  '<div class="table-block">{items}</div>' . $pagerBlock;
        $this->dataProvider->getSort()->sortVar = $this->sortVar;
		$this->pager = array(
			'header' => '',
			'cssFile' => '',
			'class' => 'MyCLinkPager',
            'pageVar' => $this->pageVar,
            'displayVar' => $this->displayVar,
//			'firstPageLabel' => '',
			'prevPageLabel' => '',
			'nextPageLabel' => '',
			'maxButtonCount' => '3',
//			'lastPageLabel' => '',
		);
		parent::init();
	}
	public function  getUrlParams(){
        $params = $_GET;
        unset($params[$this->displayVar]);
        unset($params[$this->pageVar]);
		return http_build_query($params);
	}
    protected function createDataColumn($text)
    {
        if(!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/',$text,$matches))
            throw new CException(Yii::t('zii','The column must be specified in the format of "Name:Type:Label", where "Type" and "Label" are optional.'));
        $column=new MyCDataColumn($this);
        $column->name=$matches[1];
        if(isset($matches[3]) && $matches[3]!=='')
            $column->type=$matches[3];
        if(isset($matches[5]))
            $column->header=$matches[5];
        return $column;
    }
    protected function initColumns()
    {
        if($this->columns===array())
        {
            if($this->dataProvider instanceof CActiveDataProvider)
                $this->columns=$this->dataProvider->model->attributeNames();
            elseif($this->dataProvider instanceof IDataProvider)
            {
                // use the keys of the first row of data as the default columns
                $data=$this->dataProvider->getData();
                if(isset($data[0]) && is_array($data[0]))
                    $this->columns=array_keys($data[0]);
            }
        }
        $id=$this->getId();
        foreach($this->columns as $i=>$column)
        {
            if(is_string($column))
                $column=$this->createDataColumn($column);
            else
            {
                if(!isset($column['class']))
                    $column['class']='MyCDataColumn';
                $column=Yii::createComponent($column, $this);
            }
            if(!$column->visible)
            {
                unset($this->columns[$i]);
                continue;
            }
            if($column->id===null)
                $column->id=$id.'_c'.$i;
            $this->columns[$i]=$column;
        }

        foreach($this->columns as $column)
            $column->init();
    }
}
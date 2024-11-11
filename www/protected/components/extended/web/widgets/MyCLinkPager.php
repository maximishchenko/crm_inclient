<?

class MyCLinkPager extends CLinkPager
{
    public $pageVar;
    public $displayVar;

    public function init()
	{
		parent::init();
	}

	protected function createPageButtons()
	{
		if(($pageCount=$this->getPageCount())<=1)
			return array();

		list($beginPage,$endPage)=$this->getPageRange();
		$currentPage=$this->getCurrentPage(false); // currentPage is calculated in getPageRange()
		$buttons=array();

		// first page
		if($beginPage!=0)
			$buttons[]=$this->createFirstPageButton(1,0,$this->firstPageCssClass,$currentPage<=0,false);

		// prev page
		if(($page=$currentPage-1)<0)
			$page=0;
		if($currentPage != $beginPage)
			$buttons[]=$this->createPageButton($this->prevPageLabel,$page,$this->previousPageCssClass,$currentPage<=0,false);

		// internal pages
		for($i=$beginPage;$i<=$endPage;++$i)
			$buttons[]=$this->createPageButton($i+1,$i,$this->internalPageCssClass,false,$i==$currentPage);

		// next page
		if(($page=$currentPage+1)>=$pageCount-1)
			$page=$pageCount-1;
		if($currentPage != $endPage)
			$buttons[]=$this->createPageButton($this->nextPageLabel,$page,$this->nextPageCssClass,$currentPage>=$pageCount-1,false);

		// last page
		if($endPage!=$pageCount-1)
			$buttons[]=$this->createLastPageButton($pageCount,$pageCount-1,$this->lastPageCssClass,$currentPage>=$pageCount-1,false);

		return $buttons;
	}
	protected function createFirstPageButton($label,$page,$class,$hidden,$selected){
		$page++;
		$link = "?$this->pageVar=$page&";
		list($beginPage,$endPage)=$this->getPageRange();
		if($display = Yii::app()->request->getParam('display'))
			$link.="&display=$display&";

		if($hidden || $selected)
			$class.=' '.($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);
		if($page == $beginPage)
			return '<li class="'.$class.'"><a href="'.$link.$this->getUrlParams().'">'.$label.'</a></li>';
		return '<li class="'.$class.'"><a href="'.$link.$this->getUrlParams().'">'.$label.'</a> ...</li>';
	}
	protected function createLastPageButton($label,$page,$class,$hidden,$selected){
		$page++;
		$link = "?$this->pageVar=$page&";
		list($beginPage,$endPage)=$this->getPageRange();
		if($display = Yii::app()->request->getParam('display'))
			$link.="&display=$display&";

		if($hidden || $selected)
			$class.=' '.($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);
		if($page-2 == $endPage)
			return '<li class="'.$class.'"><a href="'.$link.$this->getUrlParams().'">'.$label.'</a></li>';
		return '<li class="'.$class.'">... <a href="'.$link.$this->getUrlParams().'">'.$label.'</a></li>';
	}
	protected function createPageButton($label,$page,$class,$hidden,$selected)
	{
		$page++;
		$link = "?$this->pageVar=$page&";
		if($display = Yii::app()->request->getParam('display'))
			$link.="&display=$display&";

		if($hidden || $selected)
			$class.=' '.($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);
		return '<li class="'.$class.'"><a href="'.$link.$this->getUrlParams().'">'.$label.'</a></li>';
	}
	public function  getUrlParams(){
		$params = $_GET;
		unset($params[$this->pageVar]);
//		unset($params[$this->displayVar]);
		return http_build_query($params);
	}
}

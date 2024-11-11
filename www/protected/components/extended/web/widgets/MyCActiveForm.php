<?

Yii::import('application.components.extended.web.widgets.MyCHtml');

class MyCActiveForm extends CActiveForm
{
	public $method = 'get';
	public function init(){
		if(!isset($this->htmlOptions['id']))
			$this->htmlOptions['id']=$this->id;
		else
			$this->id=$this->htmlOptions['id'];

		if($this->stateful)
			echo MyCHtml::statefulForm($this->action, $this->method, $this->htmlOptions);
		else
			echo MyCHtml::beginForm($this->action, $this->method, $this->htmlOptions);

		if($this->errorMessageCssClass===null)
			$this->errorMessageCssClass=CHtml::$errorMessageCss;
	}
}
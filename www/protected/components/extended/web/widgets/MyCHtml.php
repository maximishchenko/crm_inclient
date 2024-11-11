<?

class MyCHtml extends CHtml
{
	public static function beginForm($action='',$method='post',$htmlOptions=array())
	{
		$htmlOptions['action']=$url=strtok($_SERVER["REQUEST_URI"],'?');
		$htmlOptions['method']=$method;
		$form=self::tag('form',$htmlOptions,false,false);
		$hiddens=array();
		if(!strcasecmp($method,'get') && ($pos=strpos($url,'?'))!==false)
		{
			foreach(explode('&',substr($url,$pos+1)) as $pair)
			{
				if(($pos=strpos($pair,'='))!==false)
					$hiddens[]=self::hiddenField(urldecode(substr($pair,0,$pos)),urldecode(substr($pair,$pos+1)),array('id'=>false));
				else
					$hiddens[]=self::hiddenField(urldecode($pair),'',array('id'=>false));
			}
		}
		$request=Yii::app()->request;
		if($request->enableCsrfValidation && !strcasecmp($method,'post'))
			$hiddens[]=self::hiddenField($request->csrfTokenName,$request->getCsrfToken(),array('id'=>false));
		if($hiddens!==array())
			$form.="\n".self::tag('div',array('style'=>'display:none'),implode("\n",$hiddens));
		return $form;
	}
}
<?php

class MyCSort extends CSort
{
    public function createUrl($controller,$directions)
    {
        $sorts=array();
        foreach($directions as $attribute=>$descending)
            $sorts[]=$descending ? $attribute.$this->separators[1].$this->descTag : $attribute;
        $params=$this->params===null ? $_GET : $this->params;
        $params[$this->sortVar]=implode($this->separators[0],$sorts);

        return '?'.http_build_query($params);
    }
    public function link($attribute,$label=null,$htmlOptions=array())
    {
        if($label===null)
            $label=$this->resolveLabel($attribute);
        if(($definition=$this->resolveAttribute($attribute))===false)
            return $label;
        $directions=$this->getDirections();
        if(isset($directions[$attribute]))
        {
            $class=$directions[$attribute] ? 'desc' : 'asc';
            if(isset($htmlOptions['class']))
                $htmlOptions['class'].=' '.$class;
            else
                $htmlOptions['class']=$class;
            $descending=!$directions[$attribute];
            unset($directions[$attribute]);
        }
        elseif(is_array($definition) && isset($definition['default']))
            $descending=$definition['default']==='desc';
        else
            $descending=false;

        if($this->multiSort)
            $directions=array_merge(array($attribute=>$descending),$directions);
        else
            $directions=array($attribute=>$descending);
        $url=$this->createUrl(Yii::app()->getController(),$directions);

        return $this->createLink($attribute,$label,$url,$htmlOptions);
    }
}
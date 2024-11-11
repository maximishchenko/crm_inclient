<?php

class MyCActiveDataProvider extends CActiveDataProvider
{
    public function getSort($className='MyCSort')
    {
        if(($sort=parent::getSort($className))!==false)
            $sort->modelClass=$this->modelClass;
        return $sort;
    }
}
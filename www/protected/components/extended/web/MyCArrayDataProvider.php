<?php

class MyCArrayDataProvider extends MyCDataProvider
{
    /**
     * @var string the name of the key field. This is a field that uniquely identifies a
     * data record. In database this would be the primary key.
     * Defaults to 'id'. If it's set to false, keys of {@link rawData} array are used.
     */
    public $keyField='id';
    /**
     * @var array the data that is not paginated or sorted. When pagination is enabled,
     * this property usually contains more elements than {@link data}.
     * The array elements must use zero-based integer keys.
     */
    public $rawData=array();
    /**
     * @var boolean controls how sorting works. True value means that case will be
     * taken into account. False value will lead to the case insensitive sort. Default
     * value is true.
     * @since 1.1.13
     */
    public $caseSensitiveSort=true;

    /**
     * Constructor.
     * @param array $rawData the data that is not paginated or sorted. The array elements must use zero-based integer keys.
     * @param array $config configuration (name=>value) to be applied as the initial property values of this class.
     */
    public function __construct($rawData,$config=array())
    {
        $this->rawData=$rawData;
        foreach($config as $key=>$value)
            $this->$key=$value;
    }

    /**
     * Fetches the data from the persistent data storage.
     * @return array list of data items
     */
    protected function fetchData()
    {
        if(($sort=$this->getSort())!==false && ($order=$sort->getOrderBy())!='')
            $this->sortData($this->getSortDirections($order));

        if(($pagination=$this->getPagination())!==false)
        {
            $pagination->setItemCount($this->getTotalItemCount());
            return array_slice($this->rawData, $pagination->getOffset(), $pagination->getLimit());
        }
        else
            return $this->rawData;
    }

    /**
     * Fetches the data item keys from the persistent data storage.
     * @return array list of data item keys.
     */
    protected function fetchKeys()
    {
        if($this->keyField===false)
            return array_keys($this->rawData);
        $keys=array();
        foreach($this->getData() as $i=>$data)
            $keys[$i]=is_object($data) ? $data->{$this->keyField} : $data[$this->keyField];
        return $keys;
    }

    /**
     * Calculates the total number of data items.
     * This method simply returns the number of elements in {@link rawData}.
     * @return integer the total number of data items.
     */
    protected function calculateTotalItemCount()
    {
        return count($this->rawData);
    }

    /**
     * Sorts the raw data according to the specified sorting instructions.
     * After calling this method, {@link rawData} will be modified.
     * @param array $directions the sorting directions (field name => whether it is descending sort)
     */
    protected function sortData($directions)
    {
        if(empty($directions))
            return;
        $args=array();
        $dummy=array();
        foreach($directions as $name=>$descending)
        {
            $column=array();
            $fields_array=preg_split('/\.+/',$name,-1,PREG_SPLIT_NO_EMPTY);
            foreach($this->rawData as $index=>$data)
                $column[$index]=$this->getSortingFieldValue($data, $fields_array);
            $args[]=&$column;
            $dummy[]=&$column;
            unset($column);
            $direction=$descending ? SORT_DESC : SORT_ASC;
            $args[]=&$direction;
            $dummy[]=&$direction;
            unset($direction);
        }
        $args[]=&$this->rawData;
        call_user_func_array('array_multisort', $args);
    }

    /**
     * Get field for sorting, using dot like delimiter in query.
     * @param mixed $data array or object
     * @param array $fields sorting fields in $data
     * @return mixed $data sorting field value
     */
    protected function getSortingFieldValue($data, $fields)
    {
        if(is_object($data))
        {
            foreach($fields as $field)
                $data=isset($data->$field) ? $data->$field : null;
        }
        else
        {
            foreach($fields as $field)
                $data=isset($data[$field]) ? $data[$field] : null;
        }
        return $this->caseSensitiveSort ? $data : mb_strtolower($data,Yii::app()->charset);
    }

    /**
     * Converts the "ORDER BY" clause into an array representing the sorting directions.
     * @param string $order the "ORDER BY" clause.
     * @return array the sorting directions (field name => whether it is descending sort)
     */
    protected function getSortDirections($order)
    {
        $segs=explode(',',$order);
        $directions=array();
        foreach($segs as $seg)
        {
            if(preg_match('/(.*?)(\s+(desc|asc))?$/i',trim($seg),$matches))
                $directions[$matches[1]]=isset($matches[3]) && !strcasecmp($matches[3],'desc');
            else
                $directions[trim($seg)]=false;
        }
        return $directions;
    }
}
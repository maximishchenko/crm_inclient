<?php

class MyRestController extends CController
{
    public function getActionParamsPOST()
    {
        //Получаем данные
        $fh = fopen("php://input", 'r');
        $post_string = stream_get_contents($fh);

        $post_param = explode("&", $post_string);
        $array_post = array();

        foreach ($post_param as $post_val) {
            $param = explode("=", $post_val);
            $array_post[$param[0]] = urldecode($param[1]);
        }

        return $array_post;
    }

    public function checkSign()
    {
        if (isset($_REQUEST['sign'])) {
            $sign = $_REQUEST['sign'];
            unset($_REQUEST['sign']);
            ksort($_REQUEST);
            $mySign = md5(implode('', $_REQUEST) . Yii::app()->params['ApiKey']);
            return $sign == $mySign;
        }
       return false;
    }

    public function beforeAction($a)
    {
        if (!Yii::app()->params['ApiKey']) {
            Json::render(array('result' => 'failed', 'description' => 'ApiKey not configured',));
        } elseif (!$this->checkSign()) {
            Json::render(array('result' => 'failed', 'description' => 'ApiKey not valid',));
        } else {
            return true;
        }
    }
} 
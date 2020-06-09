<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/4
 * Time: 11:36
 */

namespace Finley\authPlugins\excepetion;


class InvalidArgumentException extends \Exception
{
    public $code = 400;
    public $msg = 'invalid parameters';
    public $errorCode = 999;

    public $shouldToClient = true;

    /**
     * 构造函数，接收一个关联数组
     * @param array $params 关联数组只应包含code、msg和errorCode，且不应该是空值
     */
    public function __construct($params=[])
    {
        if(!is_array($params)){
            return;
        }
        if(array_key_exists('code',$params)){
            $this->code = $params['code'];
        }
        if(array_key_exists('msg',$params)){
            $this->msg = $params['msg'];
        }
        if(array_key_exists('errorCode',$params)){
            $this->errorCode = $params['errorCode'];
        }
    }

    public function error(){
        header('Content-Type:application/json; charset=utf-8');
        $result = [
            'msg' => $this->msg,
            'error_code' => $this->errorCode,
        ];
        exit(json_encode($result,JSON_UNESCAPED_UNICODE));
    }
}
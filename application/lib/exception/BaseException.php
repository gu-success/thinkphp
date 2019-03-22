<?php
/**
 * Created by gu.
 * User: gu
 * Date: 2018/12/17
 * Time: 15:37
 */

namespace app\lib\exception;


use think\Exception;

class BaseException extends Exception
{
    //http 状态码 200 400 403
    public $code = '400';
    //具体错误信息
    public $msg = 'Parameter Error';
    //自定义状态码
    public $errorCode = '10000';

    public function __construct($params = [])
    {
        if (!is_array($params)){
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
}
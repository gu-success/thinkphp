<?php
/**
 * Created by gu.
 * User: gu
 * Date: 2018/12/17
 * Time: 15:36
 */

namespace app\lib\exception;


use Exception;
use think\exception\Handle;
use think\facade\Log;
use think\facade\Request;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;
    //需要返回客户端当前的url
    public function render(Exception $e)
    {
        if ($e instanceof BaseException){
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        }else{
            if (config('app_debug')){
               return parent::render($e);
            }else{
                $this->code = '500';
                $this->msg = 'sorry,we make a mistake！';
                $this->errorCode = '999';
                $this ->recordErrorLog($e);
            }
        }
        $result =[
            'msg' =>  $this->msg,
            'errorCode' => $this->errorCode,
            'requestUrl' =>  Request::url()
        ];
        return json($result, $this->code);
    }

    private function recordErrorLog(Exception $e){
        Log::write($e->getMessage(),'error');
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/12
 * Time: 12:54
 */
namespace app\lib\exception;


class OrderMissException extends BaseException
{
    public $code = '404';
    public $msg = '订单不存在吗，请检查ID';
    public $errorCode = '80000';
}
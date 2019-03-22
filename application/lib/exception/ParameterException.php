<?php
/**
 * Created by gu.
 * User: gu
 * Date: 2018/12/18
 * Time: 16:35
 */

namespace app\lib\exception;


class ParameterException extends BaseException
{
    public $code = '400';
    public $msg = '参数异常';
    public $errorCode = '10000';
}
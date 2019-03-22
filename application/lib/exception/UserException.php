<?php
/**
 * Created by UserException.php.
 * User: gu
 * Date: 2018/12/26
 * Time: 16:24
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    public $code = '404';
    public $msg = '用户不存在';
    public $errorCode = '60000';
}
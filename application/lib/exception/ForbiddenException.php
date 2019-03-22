<?php
/**
 * Created by ForbiddenException.php.
 * User: gu
 * Date: 2018/12/27
 * Time: 14:51
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    public $code = '403';
    public $msg = '权限不够';
    public $errorCode = '10001';
}
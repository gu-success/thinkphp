<?php
/**
 * Created by SuccessMessage.php.
 * User: gu
 * Date: 2018/12/26
 * Time: 16:38
 */

namespace app\lib\exception;


class SuccessMessage extends BaseException
{
    public $code = '201';
    public $msg = 'ok';
    public $errorCode = '0';
}
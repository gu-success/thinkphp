<?php
/**
 * Created by WeChatException.php.
 * User: gu
 * Date: 2018/12/25
 * Time: 14:34
 */

namespace app\lib\exception;


class WeChatException extends BaseException
{
    public $code = '400';
    public $msg = '微信内部错误';
    public $errorCode = '999';
}
<?php
/**
 * Created by TokenException.php.
 * User: gu
 * Date: 2018/12/25
 * Time: 16:46
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
    public $code = '401';
    public $msg = 'Token令牌不存在，或已过期';
    public $errorCode = '10001';
}
<?php
/**
 * Created by ProductException.php.
 * User: gu
 * Date: 2018/12/21
 * Time: 17:13
 */

namespace app\lib\exception;


class ProductException extends BaseException
{
    public $code = '404';
    public $msg = '指定的商品不存在';
    public $errorCode = '20000';
}
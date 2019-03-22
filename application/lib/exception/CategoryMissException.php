<?php
/**
 * Created by CategoryMissException.php.
 * User: gu
 * Date: 2018/12/24
 * Time: 13:51
 */

namespace app\lib\exception;


class CategoryMissException extends BaseException
{
    public $code = '404';
    public $msg = '指定的类目不存在，请检查参数';
    public $errorCode = '50000';
}
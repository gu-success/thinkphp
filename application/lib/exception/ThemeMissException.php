<?php
/**
 * Created by gu.
 * User: gu
 * Date: 2018/12/20
 * Time: 17:16
 */

namespace app\lib\exception;


class ThemeMissException extends BaseException
{
    public $code = '404';
    public $msg = '指定的主体不存在，请检查主题id';
    public $errorCode = '30000';
}
<?php
/**
 * Created by gu.
 * User: gu
 * Date: 2018/12/17
 * Time: 15:48
 */

namespace app\lib\exception;


class BannerMissException extends BaseException
{
    public $code = '404';
    public $msg = '请求的banner不存在';
    public $errorCode = '40000';
}
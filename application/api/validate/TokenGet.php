<?php
/**
 * Created by TokenGet.php.
 * User: gu
 * Date: 2018/12/24
 * Time: 15:18
 */

namespace app\api\validate;


class TokenGet extends BaseValidate
{
    protected $rule = [
        'code' => 'require|isNotEmpty'
    ];

    protected $message = [
        'code' => 'code不存在无法获得token'
    ];
}
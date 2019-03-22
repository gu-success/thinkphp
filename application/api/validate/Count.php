<?php
/**
 * Created by Count.php.
 * User: gu
 * Date: 2018/12/21
 * Time: 16:27
 */

namespace app\api\validate;


class Count extends BaseValidate
{
    protected $rule = [
        'count' => 'isPositiveInteger|between:1,20'
    ];
}
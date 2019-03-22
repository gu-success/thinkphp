<?php
/**
 * Created by gu.
 * User: gu
 * Date: 2018/12/13
 * Time: 13:58
 */

namespace app\api\validate;


class IDMustBePositiveInt extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger'
    ];

    protected $message = [
        'id' => 'id必须是正整数'
    ];
}
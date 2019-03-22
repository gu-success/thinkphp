<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/18
 * Time: 14:28
 */

namespace app\api\validate;


class PagingParameter extends BaseValidate
{
    protected $rule = [
        'size' => 'isPositiveInteger',
        'page' => 'isPositiveInteger'
    ];

    protected $message = [
        'size' => '显示数量必须是正整数',
        'page' => '分页数必须是正整数'
    ];
}
<?php
/**
 * Created by gu.
 * User: gu
 * Date: 2018/12/20
 * Time: 16:09
 */

namespace app\api\validate;


class IDCollection extends BaseValidate
{
    protected $rule = [
        'ids' => 'require|checkIDS'
    ];

    protected $message = [
        'ids' => 'ids必须是以逗号相隔的正整数'
    ];

    protected function checkIDS ($value){
        $values = explode(',',$value);
        if (empty($values)){
            return false;
        }
        foreach ($values as $id){
            if(!$this->isPositiveInteger($id)){
                return false;
            }
        }
        return true;
    }
}
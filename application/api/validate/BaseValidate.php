<?php
/**
 * Created by gu.
 * User: gu
 * Date: 2018/12/13
 * Time: 15:07
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\facade\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck(){
        //获取http传入的参数
        //对参数进行检验
        $params = Request::param();
        $result = $this->batch()->check($params);
        if ($result){
            return true;
        }else{
            $e = new ParameterException([
               'msg' => $this->error
            ]);
            throw $e;
        }
    }

    protected function isPositiveInteger($value){
        if (is_numeric($value) && is_int($value + 0) && ($value + 0)> 0 ){
            return true;
        }else{
            return false;
        }
    }

    protected function isNotEmpty($value){
        if (empty($value)){
            return false;
        }else{
            return true;
        }
    }

    protected function isMobile($value){
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function getDataByRule($arrays){
        if (array_key_exists('user_id',$arrays) | array_key_exists('uid ',$arrays)){
            throw new ParameterException([
                'msg' => '传入非法参数uid或user_id'
            ]);
        }
        $newArray=[];
        foreach ($this->rule as $key => $value){
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }
}
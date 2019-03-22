<?php
/**
 * Created by OrderPlace.php.
 * User: gu
 * Date: 2018/12/28
 * Time: 15:01
 */

namespace app\api\validate;

use app\lib\exception\ProductException;

class OrderPlace extends BaseValidate
{
    protected $rule = [
        'products' => 'checkProducts'
    ];

    protected $singleRule = [
        'product_id' => 'require|isPositiveInteger',
        'count' => 'require|isPositiveInteger'
    ];

    protected function checkProducts($values){
        if (!is_array($values)){
            throw new ProductException([
               'msg' => '商品参数错误'
            ]);
        }
        if (empty($values)){
            throw new ProductException([
                'msg' => '商品列表不可为空'
            ]);
        }
        foreach ($values as $value){
            $this->checkProduct($value);
        }
        return true;
    }

    protected function checkProduct($value){
        $validate = new BaseValidate($this->singleRule);
        $result = $validate->check($value);
        if(!$result){
            throw new ProductException([
                'msg' => '商品列表参数错误'
            ]);
        }
    }
}
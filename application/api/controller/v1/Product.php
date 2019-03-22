<?php
/**
 * Created by Product.php.
 * User: gu
 * Date: 2018/12/21
 * Time: 16:06
 */

namespace app\api\controller\v1;

use app\api\validate\Count;
use app\api\modle\Product as ProductModle;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ProductException;

class Product
{
    /**
     * 获得最近的商品信息
     * @url /product/recent
     * @http GET
     * @count 显示商品的个数
     */

    public function getRecent($count='20'){
        (new Count())->goCheck();
        $product = ProductModle::getMostRecent($count);
        if ($product->isEmpty()){
            throw new ProductException();
        }
        $product->hidden(['summary']);
        return $product;
    }

    /**
     * 获得指定列表下的商品信息
     * @url /product/by_category
     * @http GET
     * @id Category的id
     */

    public function getCategoryProduct($id){
        (new IDMustBePositiveInt())->goCheck();
        $product = ProductModle::getProductsByCategoryID($id);
        if ($product->isEmpty()){
            throw new ProductException();
        }
        $product->hidden(['summary']);
        return $product;
    }

    /**
     * 获得商品详细信息
     * @url /product/:id
     * @http GET
     * @id 商品的id
     */

    public function getOne($id){
        (new IDMustBePositiveInt())->goCheck();
        $product = ProductModle::getProductDetail($id);
        if (!$product){
            throw new ProductException();
        }
        $product->hidden(['summary']);
        return $product;
    }
}
<?php
/**
 * Created by Category.php.
 * User: gu
 * Date: 2018/12/24
 * Time: 13:33
 */

namespace app\api\controller\v1;

use app\api\modle\Category as CategoryModel;
use app\lib\exception\CategoryMissException;

class Category
{
    /**
     * 获取全部列表
     * @url /category/all
     * @http GET
     */
    public function getAllCategories(){
        $categories = CategoryModel::getAllCategory();
        if ($categories->isEmpty()){
            throw new CategoryMissException();
        }
        return $categories;
    }
}
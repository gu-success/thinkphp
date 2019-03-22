<?php
/**
 * Created by ProductProperty.php.
 * User: gu
 * Date: 2018/12/26
 * Time: 13:23
 */

namespace app\api\modle;


class ProductProperty extends BaseModel
{
    protected $hidden = ['product_id','delete_time','update_time','id'];
}
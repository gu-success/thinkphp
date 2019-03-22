<?php
/**
 * Created by ProductImage.php.
 * User: gu
 * Date: 2018/12/26
 * Time: 13:06
 */

namespace app\api\modle;


class ProductImage extends BaseModel
{
    protected $hidden = ['img_id','delete_time','product_id','id'];
    public function imgUrl(){
        return $this->belongsTo('Image','img_id','id');
    }
}
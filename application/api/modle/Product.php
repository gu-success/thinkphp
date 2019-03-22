<?php
/**
 * Created by gu.
 * User: gu
 * Date: 2018/12/20
 * Time: 15:18
 */

namespace app\api\modle;


class Product extends BaseModel
{
    protected $hidden = ['delete_time','create_time','update_time','pivot','from','category_id'];
    public function theme(){
        return $this->belongsToMany('Theme','theme_product','theme_id','product_id');
    }

    public function imgs(){
        return $this->hasMany('ProductImage','product_id','id')->order('order','asc');
    }

    public function property(){
        return $this->hasMany('ProductProperty','product_id','id');
    }

    public function getMainImgUrlAttr($url,$data){
        return $this->prefixImgUrl($url,$data);
    }

    static  public function getMostRecent($count){
        return self::limit($count)->order('create_time', 'desc')->select();
    }

    static public function getProductsByCategoryID($id){
        return self::where('category_id','=',$id)->select();
    }

    static public function getProductDetail($id){
        return self::with(['imgs.imgUrl','property'])->find($id);
    }
}
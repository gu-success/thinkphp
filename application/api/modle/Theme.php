<?php
/**
 * Created by gu.
 * User: gu
 * Date: 2018/12/20
 * Time: 15:19
 */

namespace app\api\modle;


class Theme extends BaseModel
{
    protected $hidden = ['topic_img_id','head_img_id','delete_time','update_time'];

    public function topicImg(){
        return $this->belongsTo('Image','topic_img_id','id');
    }
    public function headImg(){
        return $this->belongsTo('Image','head_img_id','id');
    }
    public  function products(){
        return $this->belongsToMany('Product','theme_product','product_id','theme_id');
    }

    static public function getTheme($ids){
        $ids = explode(',',$ids);
        $result = self::with(['topicImg','headImg'])->select($ids);
        return $result;
    }

    static public function getThemeWithProducts($id){
        $result = self::with(['products','topicImg','headImg'])->find($id);
        return $result;
    }
}
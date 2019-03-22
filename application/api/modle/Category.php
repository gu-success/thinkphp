<?php
/**
 * Created by Category.php.
 * User: gu
 * Date: 2018/12/24
 * Time: 13:34
 */

namespace app\api\modle;


class Category extends BaseModel
{
    protected $hidden = ['delete_time','update_time'];

    public function topicImg(){
        return $this->belongsTo('Image','topic_img_id','id');
    }

    static public function getAllCategory(){
        return self::all([],'topicImg');
    }
}
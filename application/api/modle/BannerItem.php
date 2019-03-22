<?php
/**
 * Created by gu.
 * User: gu
 * Date: 2018/12/20
 * Time: 10:51
 */

namespace app\api\modle;



class BannerItem extends BaseModel
{
    protected $hidden = ['id','img_id','banner_id','delete_time','update_time'];

    public function img(){
        return $this->belongsTo('Image','img_id','id');
    }
}
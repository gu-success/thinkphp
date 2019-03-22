<?php
/**
 * Created by gu.
 * User: gu
 * Date: 2018/12/17
 * Time: 14:06
 */

namespace app\api\modle;



class Banner extends BaseModel
{
    protected $hidden = ['delete_time','update_time'];

    public function items(){
        return $this->hasMany('BannerItem','banner_id','id');
    }
    public static function getBannerById($id){
        $result = self::with(['items','items.img'])->find($id);
        return $result;
    }
}
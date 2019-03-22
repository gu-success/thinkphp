<?php
/**
 * Created by gu.
 * User: gu
 * Date: 2018/12/20
 * Time: 11:10
 */

namespace app\api\modle;


class Image extends BaseModel
{
    protected $hidden = ['id','from','delete_time','update_time'];
    public function getUrlAttr($url,$data){
        return $this->prefixImgUrl($url,$data);
    }
}
<?php
/**
 * Created by gu.
 * User: gu
 * Date: 2018/12/20
 * Time: 14:29
 */

namespace app\api\modle;


use think\Model;

class BaseModel extends Model
{
    protected function prefixImgUrl($url,$data){
        $finalUrl = $url;
        if ($data['from'] == '1'){
            $finalUrl = config('setting.img_prefix').$url;
        }
        return   $finalUrl;
    }
}
<?php
/**
 * Created by User.php.
 * User: gu
 * Date: 2018/12/24
 * Time: 15:30
 */

namespace app\api\modle;


class User extends BaseModel
{
    public function address(){
        return $this->hasOne('UserAddress','user_id','id');
    }

    static public function getByOpenID($openid){
       return self::where('openid','=',$openid)->find();
    }
}
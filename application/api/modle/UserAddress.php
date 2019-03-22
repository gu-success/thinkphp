<?php
/**
 * Created by UserAddress.php.
 * User: gu
 * Date: 2018/12/26
 * Time: 17:06
 */

namespace app\api\modle;


class UserAddress extends BaseModel
{
    protected $hidden = ['id','delete_time','user_id'];
}
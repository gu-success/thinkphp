<?php
/**
 * Created by Token.php.
 * User: gu
 * Date: 2018/12/24
 * Time: 15:04
 */

namespace app\api\controller\v1;


use app\api\service\UserToken;
use app\api\validate\TokenGet;

class Token
{
    public function getToken($code = ''){
        (new TokenGet())->goCheck();
        $usertoken = new UserToken($code);
        $token = $usertoken ->get();
        return json([
            'token' => $token
        ]);
    }
}
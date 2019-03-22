<?php
/**
 * Created by Token.php.
 * User: gu
 * Date: 2018/12/25
 * Time: 16:19
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Exception;
use think\facade\Cache;
use think\facade\Request;

class Token
{
    static public function generateToken(){
        $randChar = getRandChar(32);
        //时间搓
        $timestamp = $_SERVER['REQUEST_TIME'];
        //salt 盐
        $salt =config('secuer.token_salt');
        return md5($randChar.$timestamp.$salt);
    }

    static public function getCurrentTokenVar($key){
        $token = Request::header('token');
        $vars = Cache::get($token);
        if (!$vars){
            throw new TokenException();
        }else{
            if (!is_array($vars)){
                $vars = json_decode($vars,true);
            }

            if (!array_key_exists($key,$vars)){
                throw new Exception('尝试请求的Token变量不存在');
            }
            return $vars[$key];
        }
    }

    static public function getCurrentUID(){
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    //用户和cms管理员都可访问的权限
    static public function needPrimaryScope(){
        $scope = self::getCurrentTokenVar('scope');
        if ($scope){
            if ($scope >= ScopeEnum::User){
                return true;
            }
            throw new ForbiddenException();
        }
        throw new TokenException();
    }

    //用户独有的权限
    static public function needExclusiveScope(){
        $scope = self::getCurrentTokenVar('scope');
        if ($scope){
            if ($scope == ScopeEnum::User){
                return true;
            }
            throw new ForbiddenException();
        }
        throw new TokenException();
    }

    //管理员独有的权限
    static public function needSuperScope(){
        $scope = self::getCurrentTokenVar('scope');
        if ($scope){
            if ($scope >= ScopeEnum::Super){
                return true;
            }
            throw new ForbiddenException();
        }
        throw new TokenException();
    }


    public static function isValidOperate($checkUID)
    {
        if (!$checkUID){
            throw new Exception('检查UID时，必须传入一个被检查的UID');
        }
        $CurrentUID = self::getCurrentUID();
        if ( $CurrentUID == $checkUID){
            return true;
        }
        return false;
    }
}
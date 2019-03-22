<?php
/**
 * Created by UserToken.php.
 * User: gu
 * Date: 2018/12/24
 * Time: 15:40
 */

namespace app\api\service;


use app\api\modle\User as UserModel;
use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Exception;

class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    function __construct($code)
    {
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'),$this->wxAppID,$this->wxAppSecret,$this->code);
    }

    public function get(){
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result,true);
        if (empty($wxResult)){
            throw new Exception('获取session_key及openid时异常，微信内部错误');
        }else{
            $loginFail = array_key_exists('errcode',$wxResult);
            if ($loginFail){
                $this -> processLoginError($wxResult);
            }else{
                return $this -> grantToken($wxResult);
            }
        }
    }

    private function grantToken($wxResult){
        //拿到openid
        //到数据库中查询openid 是否存在
        //不存在则新建一条user记录
        //生成令牌，打开缓存，存入缓存
        //返回令牌到客户端
        $openid = $wxResult['openid'];
        $session_key = $wxResult['session_key'];
        $user = UserModel::getByOpenID($openid);
        if ($user){
            $uid = $user->id;
        }else{
            $uid = $this->newUser($openid);
        }
        $cachedValue = $this->prepareCachedValue($wxResult,$uid);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }

    private function saveToCache($cachedValue){
        $key = self::generateToken();
        $value = json_encode($cachedValue);
        $expire_in = config('setting.tokrn_expire_in');
        $request = cache($key,$value,$expire_in);
        if (!$request){
            throw new TokenException([
                'msg' => '服务器缓存错误',
                'errorCode' => '10005'
            ]);
        }
        return $key;
    }

    private function prepareCachedValue($wxResult,$uid){
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        $cachedValue['scope'] = ScopeEnum::User;
        return $cachedValue;
    }

    private function newUser($openid){
        $user = UserModel::create([
           'openid' => $openid
        ]);
        return $user->id;
    }

    private function processLoginError($wxResult){
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' =>  $wxResult['errcode']
        ]);
    }
}
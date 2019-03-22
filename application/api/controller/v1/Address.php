<?php
/**
 * Created by Address.php.
 * User: gu
 * Date: 2018/12/26
 * Time: 14:53
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\api\modle\User as UserModle;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;

class Address extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only'=> 'createOrUpdateAddress']
    ];
    /**
     * 更新或添加用户地址
     * @url /address
     * @http post
     * @token 从header中获取用户的token令牌
     * {"name":"哈哈","mobile":"13888888888","province":"北京","city":"北京市","country":"西城","detail":"龙泽"}
     */

    public function createOrUpdateAddress(){
        $vaildate = new AddressNew();
        $vaildate->goCheck();
        $uid = TokenService::getCurrentUID();
        $user = UserModle::get($uid);
        if (!$user){
            throw new UserException();
        }
        $dataArray =  $vaildate->getDataByRule(input('post.'));
        $userAddress = $user->address;
        if ($userAddress){
            $user->address->save($dataArray);
        }else{
            $user->address()->save($dataArray);
        }
        throw new SuccessMessage();
    }
}
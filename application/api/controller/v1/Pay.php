<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/13
 * Time: 11:06
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePositiveInt;
use app\api\service\Pay as PayService;

class Pay extends BaseController
{
    protected $beforeActionList=[
        'checkExclusiveScope' => ['only' => 'getPreOrder']
    ];

    public function getPreOrder($id = ''){
        (new IDMustBePositiveInt())->goCheck();
        $pay = new PayService($id);
        return $pay ->pay();
    }

    public function receiveNotify(){
        //1，检测库存  超卖
        //2，更新订单status状态
        //3，减库存
        $notify = new WxNotify();
        $notify->Handle();
    }
}
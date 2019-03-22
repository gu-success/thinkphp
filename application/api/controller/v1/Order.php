<?php
/**
 * Created by Order.php.
 * User: gu
 * Date: 2018/12/28
 * Time: 13:08
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\OrderPlace;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderSercice;
use app\api\modle\Order as OrderModel;
use app\api\validate\PagingParameter;
use app\lib\exception\OrderMissException;

class Order extends BaseController
{
    //用户在选择商品后，向API提交他所选择的相关信息
    //API接收到信息后，需要检查订单相关商品的库存量
    //有库存则把订单数据存到数据库中（下单成功），返回客户端信息，告诉客户端可以支付了
    //调用我们的支付接口进行支付
    //再次进行库存量的检测
    //服务器调用微信的支付接口进行支付
    //微信返回给我们一个支付结果（异步）
    //成功 检测库存量 进行库存量的扣除

    //前置方法验证token令牌
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'placeOrder'],
        'checkPrimaryScope' => ['only' => 'getDetail,getSummaryByUser']
    ];

    public function placeOrder(){
        (new OrderPlace())->goCheck();
        $products = input('post.products/a');
        $uid = TokenService::getCurrentUID();
        $order = new OrderSercice();
        $status = $order->place($uid,$products);
        return $status;
    }

    public function getSummaryByUser($page=1,$size=10){
        (new PagingParameter())->goCheck();
        $uid = TokenService::getCurrentUID();
        $paginfOrders = OrderModel::getSummaryByUser($uid,$page,$size);
        if ($paginfOrders->isEmpty()){
            return ['data'=>[]];
        }
        $data = $paginfOrders->hidden(['snap_items', 'snap_address'])
            ->toArray();
        return [
            'data' => $data
        ];
    }

    public function getDetail($id){
        (new IDMustBePositiveInt())->goCheck();
        $orderDetail = OrderModel::get($id);
        if (!$orderDetail) {
            throw new OrderMissException();
        }
        return $orderDetail->hidden(['prepay_id']);
    }
}
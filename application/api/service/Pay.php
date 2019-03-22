<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/13
 * Time: 11:15
 */

namespace app\api\service;


use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderMissException;
use app\lib\exception\TokenException;
use think\Exception;
use app\api\service\Order as OrderService;
use app\api\modle\Order as OrderModle;
use think\facade\Log;

require_once EXTEND_PATH."WxPay\WxPay.Api.php";

class Pay
{
    private $orderID;
    private $orderNO;
    function __construct($orderID)
    {
        if (!$orderID){
            throw new Exception('订单号不可为空');
        }
        $this -> orderNO = $orderID;
    }

    public function pay(){
        $this->checkOrderValid();
        $orderService = new OrderService();
        $status = $orderService->checkOrderSrock($this->orderID);
        if (!$status['pass']){
            return $status;
        }
        return $this->makeWxpreOrder($status['orderPrice']);
    }

    // 构建微信支付订单信息
    private function makeWxpreOrder($totalPrice){
        $openid = Token::getCurrentTokenVar('openid');
        if (!$openid){
            throw new TokenException();
        }
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNO);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice * 100);
        $wxOrderData->SetBody('商城');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url(config('secure.pay_bock_url'));
        return $this->getPaySingatrue($wxOrderData);
    }

    //向微信请求订单号并生成签名
    private function getPaySingatrue($wxOrderData){
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
        if($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] !='SUCCESS'){
            Log::write($wxOrder,'error');
            Log::write('获取预支付订单失败','error');
        }
        $this->recordPreOrder($wxOrder);
        $rawValues = $this->sign($wxOrder);
        return $rawValues;
    }

    // 签名
    private function sign($wxOrder)
    {
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        $rand = md5(time() . mt_rand(0, 1000));
        $jsApiPayData->SetNonceStr($rand);
        $jsApiPayData->SetPackage('prepay_id=' . $wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');
        $sign = $jsApiPayData->MakeSign();
        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;
        unset($rawValues['appId']);
        return $rawValues;
    }

    private function recordPreOrder($wxOrder){
        OrderModle::where('id','=','$this->orderID')->update(['prepay_id'=>$wxOrder->prepay_id]);
    }

    private function checkOrderValid(){
        $order = OrderModle::where('id','=',$this->orderID)->find();
        if (!$order){
            throw new OrderMissException();
        }
        if (!Token::isValidOperate($order->user_id)){
            throw new TokenException([
                'msg' => '订单与用户不匹配',
                'errorCode' => 10003
            ]);
        }
        if ($order->status != OrderStatusEnum::UNPAID){
            throw new OrderMissException([
                'msg' => '订单已经支付过了',
                'code' => 400,
                'errorCode' => 80003
            ]);
        }
        $this->orderNO = $order->order_no;
        return true;
    }
}
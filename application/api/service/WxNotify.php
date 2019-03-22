<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/15
 * Time: 16:05
 */

namespace app\api\service;

use app\api\modle\Order as orderModle;
use app\api\modle\Product;
use app\api\service\Order as OrderService;
use app\lib\enum\OrderStatusEnum;
use think\Db;
use think\Exception;
use think\facade\Log;

require_once EXTEND_PATH."WxPay\WxPay.Api.php";

class WxNotify extends \WxPayNotify
{
    public function NotifyProcess($objData, $config, &$msg)
    {
        if ($objData['result_code'] == 'SUCCESS'){
            $orderNO = $objData['out_trade_no'];
            Db::startTrans();
            try{
                $order = orderModle::where('order_no','=',$orderNO)->lock(true)->find();
                if ($order->status == 1){
                    $service = new OrderService();
                    $status=$service->checkOrderSrock($order->id);
                    if ($status['pass']){
                        $this->updateOrderStatus($order->id,true);
                        $this->reduceStock($status);
                    }else{
                        $this->updateOrderStatus($order->id,false);
                    }
                }
                Db::commit();
                return true;
            }catch(Exception $e){
                Db::rollback();
                Log::write($e,'error');
                return false;
            }
        }else{
            Log::write($msg,'error');
            return true;
        }
    }
    private function updateOrderStatus($orderID,$type){
        $status = $type ? OrderStatusEnum::PAID:OrderStatusEnum::PAID_BUT_OUT_OF;
        orderModle::where('id' ,'=',$orderID)
            ->update(['status'=>$status]);
    }

    private function reduceStock($status){
        foreach ($status['pStatusArray'] as $singlePStatus){
            Product::where('id','=',$singlePStatus['id'])->setDec('stock',$singlePStatus['count']);
        }
    }
}
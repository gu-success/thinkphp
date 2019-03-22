<?php
/**
 * Created by Order.php.
 * User: gu
 * Date: 2018/12/28
 * Time: 15:39
 */

namespace app\api\service;


use app\api\modle\OrderProduct;
use app\api\modle\Product;
use app\api\modle\UserAddress;
use app\api\modle\Order as Ordermodle;
use app\lib\exception\OrderMissException;
use app\lib\exception\UserException;
use think\Db;
use think\Exception;

class Order
{
    //客户端传过来的商品订单信息
    protected $oProducts;
    //商品真实信息（包括库存）
    protected $products;
    protected $uid;

    public  function place($uid,$oProducts){
        //$oProducts 和 $products做对比
        //products 从数据库查出

        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);
        $this->uid = $uid;
        $status = $this -> getOrderStatus();
        if (!$status['pass']){
            $status['order_id'] = -1;
            return $status;
        }

        //开始创建订单
        $orderSnap = $this->snapOrder($status);
        $order = $this->createOrder($orderSnap);
        $order['pass'] = true;
        return $order;
    }

    private function createOrder($snap){
        //事务
        Db::startTrans();
        try {
            $orderNo = $this->makeOrderNo();
            $order = new Ordermodle();
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);
            $order->save();

            $orderID = $order->id;
            $create_time = $order->create_time;

            foreach ($this->oProducts as &$p) {
                $p['order_id'] = $orderID;
            }
            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProducts);
            Db::commit();
            return [
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $create_time
            ];
        }catch(\Exception $e){
            Db::rollback();
            throw $e;
        }
    }

    public static function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2019] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }

    private function snapOrder($status){
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatus' => [],
            'snapAddress' => '',
            'snapName' => '',
            'snapImg' => ''
        ];
        $snap['orderPrice'] = $status['orderprice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = json_encode($this->getUserAddress());
        $snap['snapName'] = $this->products[0]['name'];
        $snap['snapImg'] = $this->products[0]['main_img_url'];
        if (count($this->products)>1){
            $snap['snapName'] .= '等';
        }
        return $snap;
    }

    private function getUserAddress(){
        $userAddress = UserAddress::where('user_id','=',$this->uid)->find()->toArray();
        if (!$userAddress){
            throw new UserException([
               'msg' => '用户收获地址不存在，下单失败',
                'errorCode' => 60001
            ]);
        }
        return $userAddress;
    }

    public function checkOrderSrock($orderID){
        $oProducts = OrderProduct::where('order_id','=',$orderID)->select();
        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);
        $status = $this->getOrderStatus();
        return $status;
    }

    private function getOrderStatus(){
        $status = [
            'pass' => true,
            'orderprice' => 0,
            'pStatusArray' => [],
            'totalCount' => 0
        ];

        foreach ($this->oProducts as $oProduct){
            $pSatatus = $this -> getProductStatus($oProduct['product_id'],$oProduct['count'],$this->products);
            if (!$pSatatus['haveStock']){
                $status['pass'] = false;
            }
            $status['orderprice'] +=  $pSatatus['totalPrice'];
            $status['totalCount'] +=  $pSatatus['count'];
            array_push($status['pStatusArray'], $pSatatus);
        }
        return $status;
    }

    private function getProductStatus($oPID,$oCount,$products){
        $pIndex = -1;
        $pSatatus = [
            'id' => null,
            'haveStock' => false,
            'count' => 0,
            'name' => '',
            'totalPrice' => 0
        ];
        for ($i=0;$i < count($products);$i++){
            if ($oPID == $products[$i]['id']){
                $pIndex = $i;
                break;
            }
        }
        if ($pIndex == -1){
            throw new OrderMissException([
                'msg' => 'ID为'.$oPID.'的商品不存在，创建订单失败'
            ]);
        }else{
            $product = $products[$pIndex];
            $pSatatus['id'] = $product['id'];
            $pSatatus['name'] = $product['name'];
            $pSatatus['count'] = $oCount;
            $pSatatus['totalPrice'] = $oCount * $product['price'];
            if ($product['stock'] - $oCount >= 0 ){
                $pSatatus['haveStock'] = true;
            }
        }
        return  $pSatatus;
    }

    private function getProductsByOrder($oProducts){
        $opids = [];
        foreach ($oProducts as $value){
            array_push($opids,$value['product_id']);
        }
        $product = Product::all($opids)
            ->visible(['id','price','stock','name','main_img_url'])
            ->toArray();
        return $product;
    }
}
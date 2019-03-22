<?php
/**
 * Created by gu.
 * User: gu
 * Date: 2018/12/13
 * Time: 10:34
 */

namespace app\api\controller\v1;

use app\api\validate\IDMustBePositiveInt;
use app\api\modle\Banner as BannerModel;
use app\lib\exception\BannerMissException;

class Banner
{
    /**
     * 获得指定id的banner信息
     * @url /banner/：id
     * @http GET
     * @id banner的id
     */
    public function getBanner($id){
        //AOP
        (new IDMustBePositiveInt())->goCheck();
        $banner = BannerModel::getBannerById($id);
        if (!$banner){
            throw new BannerMissException();
        }
        return $banner;
    }
}
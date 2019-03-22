<?php
/**
 * Created by gu.
 * User: gu
 * Date: 2018/12/20
 * Time: 15:17
 */

namespace app\api\controller\v1;


use app\api\validate\IDCollection;
use app\api\modle\Theme as ThemeModle;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ThemeMissException;

class Theme
{
    /**
     * 获得专题
     * @url /theme?$ids=id1,id2,id3
     * @http GET
     * @id 一组theme
     */
    public function getSimpleList($ids=''){
        (new IDCollection())->goCheck();
        $theme = ThemeModle::getTheme($ids);
        if ($theme->isEmpty()){
            throw new ThemeMissException();
        }
        return $theme;
    }

    /**
     * 获得专题详情
     * @url /theme/$id
     * @http GET
     * @id 一组theme详情
     */
    public function getComplexOne($id){
        (new IDMustBePositiveInt())->goCheck();
        $themeComplex = ThemeModle::getThemeWithProducts($id);
        if (!$themeComplex){
            throw new ThemeMissException();
        }
        return  $themeComplex;
    }
}
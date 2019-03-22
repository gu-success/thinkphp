<?php
/**
 * Created by gu.
 * User: gu
 * Date: 2018/12/13
 * Time: 10:34
 */

namespace app\api\controller\v2;

use QL\Ext\PhantomJs;
use QL\QueryList;

class Banner
{
    /**
     * 获得指定id的banner信息
     * @url /banner/：id
     * @http GET
     * @id banner的id
     */
    public function getBanner($id){
        $ql = QueryList::get('http://www.find800.cn');
        $rt = [];
        // 采集文章标题
        $rt['title'] = $ql->find('h1')->texts();
        // 采集文章作者
        $rt['author'] = $ql->find('.title')->texts();
        // 采集文章内容
        $rt['content'] = $ql->find('.article-content')->html();

        //打印结果
        echo '<pre>';
        print_r($rt);
        echo '</pre>';
    }
}
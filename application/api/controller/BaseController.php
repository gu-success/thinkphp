<?php
/**
 * Created by BaseController.php.
 * User: gu
 * Date: 2018/12/28
 * Time: 14:15
 */

namespace app\api\controller;


use think\Controller;
use app\api\service\Token;
class BaseController extends Controller
{
    //用户和cms管理员都可访问的权限
    protected function checkPrimaryScope(){
        Token::needPrimaryScope();
    }
    //用户独有的权限
    protected function checkExclusiveScope(){
        Token::needExclusiveScope();
    }
    //管理员独有的权限
    protected function checkSuperScope(){
        Token::needSuperScope();
    }
}
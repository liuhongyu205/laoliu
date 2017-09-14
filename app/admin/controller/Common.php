<?php
namespace app\admin\controller;
use houdunwang\core\Controller;

class Common extends Controller
{
//    通过构造方法来判断是否有登录
    public function __construct()
    {
//        进行登录验证
//        1、如果正常登陆进来则页面保持
//        2、如果页面非正常登录即改变地址栏参数则，退返到登陆界面
            if(!isset($_SESSION['admin_id'])){
                header('location:?s=admin/login/index');
                exit;
            }

    }
}
<?php
namespace app\admin\controller;


use houdunwang\view\View;
use system\model\Admin;

class Entry extends Common
{
    public function index(){

        return  View::make();
    }

    public function changePass(){
//        修改密码点击操作
        if(IS_POST){

//            调用修改密码函数
            $res=(new Admin())->changePass($_POST);
//            dd($res);

            if($res['code']){
//                密码修改成功进行清除session操作
            session_unset();
            session_destroy();
//            密码修改成功以后跳转至主页面后跳转至登陆页面
            $this->setRedirect(u('entry.index'))->message($res['msg']);

            }
//            密码修改失败后刷新页面
            else{
                $this->setRedirect()->message($res['msg']);
            }
        }

        return View::make();
    }
    public function out(){
        session_unset();
        session_destroy();
        header('location:?s=admin/login/index');exit;
    }




}
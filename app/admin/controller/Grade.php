<?php
namespace app\admin\controller;
use houdunwang\view\View;
use system\model\Grade as GradeModel;
class Grade extends Common {
//    调用登录窗口页面
    public function index(){
        $model = GradeModel::order("gid desc")->getAll();
        $data = $model ? $model->toArray() : [];
//       dd($data);die;
//        调用返回值显示主页信息
        return View::make()->with(compact ('data'));

    }
    public function add ()
    {
//        增加班级点击信息
        if(IS_POST){
            $res = (new GradeModel())->add($_POST);
            if($res['code']){
//                当添加成功则跳转至主页
                $this->setRedirect (u('index'))->message ($res['msg']);
            }else{
//                当添加失败则失败信息提示并且返回添加页面
                $this->setRedirect ()->message ($res['msg']);
            }
        }
        return View::make();
    }
    public function del(){
        $gid=$_GET['gid'];
        GradeModel::destory($gid);
        $this->setRedirect(u('index'))->message('删除成功');
    }
    public function edit(){
//        获取get参数
        $gid=$_GET['gid'];
//        点击编辑后操作
        if(IS_POST){
            $res=(new GradeModel())->edit($gid,$_POST);
            if($res['code']){
                $this->setRedirect(u('index'))->message($res['msg']);

            }else{
                $this->setRedirect()->message($res['msg']);

            }
        }
//        获取旧的数据
        $oldData=GradeModel::find($gid)->toArray();
        return View::make()->with(compact('oldData'));

    }

}
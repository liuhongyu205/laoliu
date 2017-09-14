<?php
namespace app\admin\controller;
use houdunwang\view\View;
use system\model\Student as StudentModel;
class Student extends Common
{
    public function index(){
//        给data赋空值保证不报错
        $data=[];
//        dd($data);die;
        $data=StudentModel::query("select * from student s join grade g on s.gid=g.gid");
//        打印测试查看是否抓到data
//        dd($data);die;
        return View::make()->with(compact('data'));
    }
//    添加公共函数
    public function add(){
//        1添加点击事件
//        2判断只有点击以后才能操作，避免页面直接操作引起的非必要报错
        if(IS_POST){
//            引用$_post传参
            $res=(new StudentModel)->add($_POST);
//                添加事件
            if($res['code']){
//                添加成功则页面成功提示并且跳转至主页
                $this->setRedirect(u('index'))->message($res['msg']);

            }else{
//                添加失败则页面失败提示并且返回添加页面
                $this->setRedirect()->message($res['msg']);

            }
        }
//        1获取头像数据
//        2千万注意字母的拼写
        $materialData=$this->getMaterialData();
//       1 获取班级数据
//        2千万注意字母的拼写，最好复制粘贴
        $gradeData=$this->getGradeData();
        return View::make()->with(compact('materialData','gradeData'));
    }
//    添加删除事件
    public function del(){
//        根据sid获取删除项
        $sid=$_GET['sid'];
//        静态引用destory 删除
        StudentModel::destory($sid);
//        操作项，删除成功页面成功提示并且返回主页面
        $this->setRedirect(u('index'))->message('删除成功');
//        return View::make();
    }
    private function getGradeData(){
        $data=\system\model\Grade::getAll();
        $data=$data ?$data->toArray() :[];
        return $data;

    }
    private function getMaterialData(){
        $data=\system\model\Material::getAll();
        $data=$data ? $data->toArray() :[];
        return $data;
    }
    public function edit(){
        $sid = $_GET['sid'];
        if(IS_POST){
            $res = (new StudentModel)->edit($sid,$_POST);
            if($res['code']){
                $this->setRedirect (u('index'))->message ($res['msg']);
            }else{
                $this->setRedirect ()->message ($res['msg']);
            }
        }
        //获取头像数据
        $materialData =$this->getMaterialData ();
        //获取班级数据
        $gradeData = $this->getGradeData ();
        //获取旧数据
        $oldData = StudentModel::find($sid)->toArray();
        //dd($oldData);
        return View::make ()->with(compact ('materialData','gradeData','oldData'));

    }
}

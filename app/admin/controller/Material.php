<?php
namespace app\admin\controller;
use houdunwang\view\View;
use system\model\Material as MaterialModel;
class Material extends Common
{
    public function index(){
//        输出测试是否能加载该页面
        //echo 1;
        //die;
        $data=MaterialModel::getAll();
//        三元判断如果$data能输出则输出data 以数组形式，否则为空
        $data = $data ? $data->toArray():[];
        //return View::make();
//        将data返回出去
        return View::make()->with(compact('data'));

    }
    public function add(){
//        编辑点击操作
        if(IS_POST){
//            实例化add类
            $res=(new MaterialModel )->add();

            if($res['code']){
//                添加成功则页面成功提示并且返回主页
                $this->setRedirect(u('index'))->message($res['msg']);

            }else{
//                添加失败页面错误提示并且留在原页
                $this->setRedirect()->message($res['msg']);
            }

        }
        return View::make();
    }
    public function del(){
        //接受删除的数据主键
        $mid = $_GET['mid'];
        //删除服务器上对应的文件
        $data = MaterialModel::find($mid)->toArray();
        //dd(file_exists ($data['mpath']));
        if(file_exists ($data['mpath'])){
            unlink ($data['mpath']);
        }
        //删除数据表数据
        MaterialModel::destory($mid);
        $this->setRedirect (u('index'))->message ('删除成功');
    }

}
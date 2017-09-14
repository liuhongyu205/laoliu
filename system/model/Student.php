<?php
namespace system\model;
use houdunwang\model\Model;

class Student extends Model
{
    public function add($data)
    {
//        对填写注册信息进行判断、判断姓名，当姓名填写为空时页面错误提示
        if(!trim ($data['sname'])) return ['code'=>0,'msg'=>'请输入姓名'];
//        对填写注册信息进行判断、判断性别，当姓名填写为空时页面错误提示
        if(!isset($data['ssex'])) return ['code'=>0,'msg'=>'请选择性别'];
//        对填写注册信息进行判断、判断头像，如果当头像选择为空时页面错误提示
        if(!isset($data['mid'])) return ['code'=>0,'msg'=>'请选择头像'];
//        对填写注册信息进行判断、判断年龄，当年龄填写为空时页面错误提示
        if(!trim ($data['sage'])) return ['code'=>0,'msg'=>'请输入年龄'];
//        对填写注册信息进行判断、判断学生所在班级，当班级选择为空时页面错误提示
        if(!$data['gid']) return ['code'=>0,'msg'=>'请选择班级'];
//        数据库写入
        $this->insert($data);
        return ['code'=>1,'msg'=>'添加成功'];


    }
    public function edit( $sid,$data){
        if(!trim ($data['sname'])) return ['code'=>0,'msg'=>'请输入姓名'];
        if(!isset($data['ssex'])) return ['code'=>0,'msg'=>'请选择性别'];
        if(!isset($data['mid'])) return ['code'=>0,'msg'=>'请选择头像'];
        if(!trim ($data['sage'])) return ['code'=>0,'msg'=>'请输入年龄'];
        if(!$data['gid']) return ['code'=>0,'msg'=>'请选择班级'];
        $this->where("sid={$sid}")->update($data);
        return['code'=>1,'msg'=>'信息编辑成功'];
    }
}
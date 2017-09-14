<?php
//创建命名空间
namespace system\model;
use houdunwang\model\Model;

class Admin extends Model
{
//    创建登录函数
    public function login($data)
    {
//        用户名输入
//        dd($data);die();
        $admin_username = $data['admin_username'];
//        密码输入
        $admin_password = $data['admin_password'];
//        dd($admin_password);die;
//        验证码输入
        $captcha = $data['captcha'];
//        数据验证（用户名，密码，验证码）
//        判断用户名是否输入，没有输入则返回未输消息（数组形式存在）
//        code表示成功还是失败的标示 1代表成功 0代表失败
//        msg 代表提示消息
        if (!trim($admin_username)) return ['code' => 0, 'msg' => '请输入用户名'];
        if (!ltrim($admin_password)) return ['code' => 0, 'msg' => '请输入密码'];
        if (!ltrim($captcha)) return ['code=>0', 'msg=>请输入验证码'];
//        对比用户名或者设密码是否正确，根据用户提交的信息在数据库中查找
//        数据库存在即为正确，不存在即为失败
        $userInfo = $this->where("admin_username='{$admin_username}'")->getAll();
//        判断用户名是否正确
//        dd($userInfo);die;
        if (!$userInfo) return ['code' => 0, 'msg' => '用户名不存在'];
//        $userInfo含有数据，
        $userInfo = $userInfo->toArray();
//        dd($userInfo);die;
        if (!password_verify($admin_password,$userInfo[0]["admin_password"] )) return ['code' => 0, 'msg' => '密码有误'];
//        判断密码是否有误，有误则弹出错误提示页面
//        if ($admin_password!=$userInfo) return ['code' => 0, 'msg' => '密码有误'];
//        判断验证码是否有误，有误则弹出验证码有误显示页面

        if(strtolower($captcha)!=strtolower($_SESSION['phrase']))return['code'=>0,'msg'=>'验证码有误请重新输入'];
//        登录成功，并将用户的登录信息储存到session中
        $_SESSION['admin_id']=$userInfo[0]['admin_id'];
        $_SESSION['admin_username']=$userInfo[0]['admin_username'];
        return['code'=>1,'msg'=>'登录成功'];
    }
    public function changePass($data){
//        从管理库admin中调用旧密码
        $admin_password=$data['admin_password'];
//        新密码
        $new_password=$data['new_password'];
//        确认密码的输入
        $confirm_password=$data['confirm_password'];
//        1数据验证，密码和重复密码时候为空
        if(!$admin_password) return['code'=>0,'msg'=>'原密码不能为空'];
        if(!$new_password) return['code'=>0,'msg'=>'原密码不能为空'];
        if(!$confirm_password) return['code'=>0,'msg'=>'原密码不能为空'];
//       /当前登录的用户信息Article::find(1)
        //$_SESSION['admin_id']  登录成功之后存储到session中当前用户的主键id
        //$this->>find(1)  self::find(1)  static::find(1)
        $userInfo = self::find($_SESSION['admin_id'])->toArray();
        //dd($userInfo);
        if(!password_verify ($admin_password,$userInfo['admin_password'])) return ['code'=>0,'msg'=>'原始密码不正确'];
        //3.比对两次新密码是否一致
        if($new_password != $confirm_password) return ['code'=>0,'msg'=>'两次新密码不一致'];
        //4.进行密码修改
        //该数组为修改数据库数据
        $newData = [
            //数据库要修改的字段   =>  更新的值
            'admin_password'=>password_hash ($new_password,PASSWORD_DEFAULT),
        ];
        $this->where("admin_id={$_SESSION['admin_id']}")->update($newData);
        return ['code'=>1,'msg'=>'密码修改成功'];


    }
}


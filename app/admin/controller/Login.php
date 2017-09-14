<?php
namespace app\admin\controller;
use houdunwang\core\Controller;
use houdunwang\view\View;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use system\model\Admin;

class Login extends Controller
{
    //登录管理界面
    public function index(){
//        测试方法
//        echo 1;exit;
//        首先生成一个加密之后的密码，手动写入数据库中
//        dd(password_hash('admin888',PASSWORD_DEFAULT));die;

        if(IS_POST){
//            调用Model执行登录功能
            $res=(new Admin())->login($_POST);
//            打印测试是否加载到$res
//            dd($res);die;
            if($res['code']){
//                进行判断调用全局变量setRedirect 当密码账号等无误后页面登录成功提示并且进入主页
                $this->setRedirect(u('entry.index'))->message($res['msg']);
            }else{
//                当不成功则错误提示后留在原页面
                $this->setRedirect()->message($res['msg']);

            }
        }

        return View::make();

    }
    /**
     * 加载验证码
     */
    public function captcha(){
        header('Content-type: image/jpeg');
        $phraseBuilder = new PhraseBuilder(4);
        $builder = new CaptchaBuilder(null,$phraseBuilder);
        $builder->build ();
        //将验证码存入到session
        $_SESSION['phrase'] = $builder->getPhrase();
        $builder->output ();
    }
}
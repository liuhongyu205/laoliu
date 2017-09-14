<?php

namespace houdunwang\core;




 class Boot
 {
	/**
	 * 执行应用
	 */
	public static function run ()
    {
        //3.运行抛出异常
//        self::handler();
		//echo 1;
		//dd(1);
		//1.初始化框架
		self::init ();
		//2.执行应用
		//?s=home/entry/add
		self::appRun ();
	}
	private static function handler(){
		$whoops = new \Whoops\Run;
		$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
		$whoops->register();
	}
	/**
	 * 执行应用
	 */
	public static function appRun ()
	{
		if ( isset( $_GET[ 's' ] ) ) {//地址栏有s参数
			//dd($_GET['s']);//home/entry/index:模块/控制器/方法
			$info = explode ( '/' , $_GET[ 's' ] );
			//dd($info);
			$class  = "\app\\{$info[0]}\controller\\" . ucfirst ( $info[ 1 ] );
			$action = $info[ '2' ];
			//dd($class);
			//定义常量
			define ( 'MODULE' , $info[ 0 ] );
			define ( 'CONTROLLER' , $info[ 1 ] );
			define ( 'ACTION' , $info[ 2 ] );
		} else {//地址栏没有s参数，需要给默认值
			$class  = "\app\home\controller\Entry";
			$action = 'index';
			//定义常量
			define ( 'MODULE' , 'home' );
			define ( 'CONTROLLER' , 'entry' );
			define ( 'ACTION' , 'index' );
		}
		echo call_user_func_array ( [ new $class , $action ] , [] );
//		(new  $class)->$action();
	}

	/**
	 * 初始化框架
	 */
	public static function init ()
	{
		//声明头部
		//如果不加头部，浏览器输出会出现乱码
		header ( 'Content-type:text/html;charset=utf8' );
		//1.设置时区
		//2.不设置时区，使用时间的时候可能会出现时间不正确
		date_default_timezone_set ( 'PRC' );
		//1.开始session
		//2.使用session必须开启，如果有session_id则不再重复开启session
		session_id () || session_start ();
	}

}


<?php

namespace houdunwang\core;

class Controller
{
	//定义调转地址属性
	private $url = "window.history.back()";




	/**
	 * 提示消息
	 * @param $message		消息内容
	 */
	public function message($message){
		//加载public/view/message.php文件
		//消息提示的模板文件
		include "./view/message.php";
		exit;
	}

	/**
	 * 跳转
	 * @param string $url	跳转地址
	 *
	 * @return $this
	 */
	public function setRedirect($url = ''){
		if(empty($url)){
			$this->url = "window.history.back()";
		}else{
			$this->url = "location.href='$url'";
		}
		return $this;
	}
}
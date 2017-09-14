<?php

namespace houdunwang\view;

class View
{
	/**
	 * 当调用不存在的方法时候出发
	 * @param $name			不存在方法名称
	 * @param $arguments	方法参数
	 *
	 * @return mixed
	 */
	public function __call ( $name , $arguments )
	{

		return self::parseAction ($name,$arguments);
	}
	/**
	 * 当静态调用不存在的方法时候出发
	 * @param $name			不存在方法名称
	 * @param $arguments	方法参数
	 *
	 * @return mixed
	 */
	public static function __callStatic ( $name , $arguments )
	{
		return self::parseAction ($name,$arguments);
	}

	public static function parseAction($name,$arguments){

		return call_user_func_array ([new Base,$name],$arguments);
	}
}
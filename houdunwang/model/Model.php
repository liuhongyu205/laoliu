<?php

namespace houdunwang\model;

class Model
{
	public function __call ( $name , $arguments )
	{
		// TODO: Implement __call() method.
		return self::parseAction ( $name , $arguments );
	}

	public static function __callStatic ( $name , $arguments )
	{
		// TODO: Implement __callStatic() method.
		return self::parseAction ( $name , $arguments );
	}

	public static function parseAction ( $name , $arguments )
	{
		$class = get_called_class ();
		return call_user_func_array ( [ new Base($class) , $name ] , $arguments );
	}
}
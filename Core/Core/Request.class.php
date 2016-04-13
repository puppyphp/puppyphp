<?php
class Request{
	
	//获取请求的方法
	public static function method(){
		$request_method = strtolower($_SERVER['REQUEST_METHOD']);
		return $request_method;
	}
	
	//判断请求的方法
	public static function isMethod($method){
		if(self::method() == strtolower($method)){
			return true;			
		}
		return false;
	}
}
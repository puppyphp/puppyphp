<?php
class Input{
	public static $request;
	
	
	// "get"方法适用于所有类型(GET、POST、PUT和DELETE)的请求，
	// 而不仅仅是GET请求。如果post中含有和get相同的键,前者将覆盖后者
	public static function get($key=NULL,$default=NULL){
		$request = self::getRequest();
		if($request == NULL){
			return $request;
		}elseif(array_key_exists($key, $request)){
			return $request[$key];
		}else{
			return $default;
		}
	}
	
	//获取Request
	public static function getRequest(){
		if(isset(self::$request)){
			return self::$request;
		}
		return $_REQUEST;
	} 
	
	
	
	
	
}
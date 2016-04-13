<?php
class Config{
	public static $config;
	
	//获取配置
	public function get($key){
		if(!isset(self::$config)){
			self::$config = self::setConfig();
		}
		return self::$config[$key];
	}
	
	//设置配置
	public function set($key,$value){
		if(!isset(self::$config)){
			self::$config = self::setConfig();
		}
		self::$config[$key] = $value;
		return self::$config;
	}
	
	//加载并合并配置文件
	public function setConfig(){
		// 加载框架配置文件
		$config_arr1 = require_once FRAMEWORK_PATH.'Config/config.php';	
		// 加载应用配置文件
		$config_arr2 = @require_once APPLICATION_PATH.'Common/Config/config.php';
		// 加载模块配置文件
		$config_arr3 = @include_once APPLICATION_PATH.MODULE_NAME.'/'.'Common/Config/config.php';
		
		$config_arr1 = is_array($config_arr1) ? $config_arr1 : array();
		$config_arr2 = is_array($config_arr2) ? $config_arr2 : array();
		$config_arr3 = is_array($config_arr3) ? $config_arr3 : array();
		
		$config = array_merge($config_arr1,$config_arr2,$config_arr3);
		return $config;
		/*惯例重于配置是系统遵循的一个重要思想，框架内置有一个惯例配置文件，
		 * 按照大多数的使用对常用参数进行了默认配置。所以，对于应用的配置文
		 * 件， 往往只需要配置和惯例配置不同的或者新增的配置参数，如果你完
		 * 全采用默认配置，甚至可以不需要定义任何配置文件。
		*/
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
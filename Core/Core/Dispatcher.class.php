<?php
class Dispatcher {
	public function dispatch(){
		$urlModel = Config::get('URL_MODEL');
		switch ($urlModel){
			case 1:
				$this->normal();
				break;
			default:
				$this->normal();
				break;
		}	
	}
	
	//检查路径参数，获取模块，控制器和方法名
	public function normal(){
		$module 	= isset($_GET['m']) ? $_GET['m'] : 'Index';
		$controller = isset($_GET['c']) ? $_GET['c'] : 'Index';
		$action 	= isset($_GET['a']) ? $_GET['a'] : 'index';
		
		//检测没有参数的情况下,比如首页
		if(!isset($_GET['m'])){
			defined('DEFAULT_MODULE') && $module = DEFAULT_MODULE;
		}
		
		define('MODULE_NAME',$module);
		define('CONTROLLER_NAME',$controller);
		define('ACTION_NAME', $action);

		$this->instantiate();		
	}
	
	
	//实例化控制器
	public function instantiate(){
		$file = APPLICATION_PATH.MODULE_NAME."/Controllers/".CONTROLLER_NAME."Controller.class.php";
		if(!is_file($file)){
			p("<h1>Dispatcher:找不到文件！<br/>$file</h1>"); 
			exit();
		}
		require_once $file;

		//实例化控制器
		$class = CONTROLLER_NAME."Controller";
		if (class_exists($class)){
			$ctrl = new $class();
		}else{
			p("<h1>Dispatcher:找不到".$class."类定义！<br/>文件位置：$file</h1>"); 
			exit();
		}
		
		//执行方法
		$action = ACTION_NAME;
		if(method_exists($ctrl,$action)){
			$ctrl->$action();
		}else{
			p("<h1>Dispatcher:".$class."找不到".$action."方法！<br/>文件位置：$file</h1>"); 
			exit();
		}
		
	}
	


}
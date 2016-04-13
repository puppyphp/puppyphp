<?php
class Controller{
	private $view;
	
	public function __construct(){
		$this->view = new View();
	}

	//模板中赋值
    protected function assign($var, $value) {
        $this->view->vars_array[$var] = $value;
    }
	

	//输出模板
	public function display($template = ''){
		return $this->view->display($template);
	}
	

	//提示信息与跳转地址
	public function success($message,$url=NULL){
		header("Content-type: text/html; charset=utf-8");
		if($url==NULL){
			$url = $_SERVER['REQUEST_URI'];
		}
		require_once Config::get('JUMP_PAGE');
	}
	

	//提示信息与跳转地址
	public function error($message,$url=NULL,$error=TRUE){
		header("Content-type: text/html; charset=utf-8");
		if($url==NULL){
			$url = $_SERVER['REQUEST_URI'];
		}
		require_once Config::get('JUMP_PAGE');
	}
	
	
	
	
	
	
	
	
	
	
	
}
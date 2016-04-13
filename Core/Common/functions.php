<?php

//输出变量 带有<pre>标签 第二个参数是否终止，第三个参数是否显示h1
function p($var,$is_die=false,$is_big=false){
	header("Content-type: text/html; charset=utf-8");
	if($is_big){
		echo "<h1>";
		echo $var;
	}else{
		echo "<pre>";
		print_r($var);
	}
	$is_die && die();
}

/*
//模板:生成url
function url($info,$arr=NULL){
	//普通模式
	if(Config::get('URL_MODEL')==1){
		$url_arr = explode('/',$info);
		$url = $_SERVER['SCRIPT_NAME']."?m=".$url_arr['0'].'&c='.$url_arr['1'].'&a='.$url_arr['2'];
		if(!is_array($arr)){
			return $url;
		}		
		foreach($arr as $key => $value){
			$arg = '&'.$key.'='.$value;
			$url .= $arg;
		}
		return $url;
	}
}*/

/*
//获取系统$_GET变量
function get($name=NULL){
	$arr = $_GET;
	if($name == NULL || !array_key_exists($name,$arr)){
		return NULL;
	}
	return $arr[$name];
}

//获取系统$_POST变量
function post($name=NULL){
	$arr = $_POST;
	if($name == NULL || !array_key_exists($name,$arr)){
		return NULL;
	}
	return $arr[$name];
}
*/
//加载类库文件
function import($name){
	$file = FRAMEWORK_PATH.'Library/'.$name;
	if(!is_file($file)){
		p("import 要包含的文件不存在");die();
	}
	require_once $file;
}

/*
// 写入配置文件的变量
function rewriteConfig($key, $value) {
    $path = app_path() . '/storage/Config.php';
    $contents = file_get_contents($path);
    $contents = str_replace(self::loadConfig($key), $value, $contents);
    file_put_contents($path, $contents);
}

// 读取配置文件的变量
function loadConfig($key) {
    $Config = require(app_path() . '/storage/Config.php');
    return $Congig[$key];
}
*/











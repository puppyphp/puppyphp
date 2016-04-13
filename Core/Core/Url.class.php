<?php
class Url{
	//获取web应用的根路径(base URL)
	public static function base(){
		
	}
	

	/**
	 * [to 生成相对于根路径的URL：]
	 * @param  [String] $info [地址字符串，例如：Home/Index/index]
	 * @param  [Array] 	$arr  [后面带有的参数字符串]
	 * @return [type]       [description]
	 */
	public static function to($info,$arr=NULL){
		//普通模式
		if(Config::get('URL_MODEL')==1){
			$url_arr = explode('/',$info);
			$url = $_SERVER['SCRIPT_NAME']."?m=".$url_arr['0'].'&c='.$url_arr['1'].'&a='.$url_arr['2'];
			
			if(is_array($arr) && sizeof($arr)>0){
				$url_end = http_build_query($arr);
				$url = $url."&".$url_end;
			}
			return $url;
		}
	}

	
	/**
	 * [getTempletUrl 生成相对于根路径的模板URL,在/Core/View.class.php中使用,<include file="Index/Index/test"/>]
	 * @param  [String] $info [地址字符串，例如：Home/Index/index]
	 * @return [type]       [description]
	 */
	public static function getTempletUrl($info){
		//普通模式
		if(Config::get('URL_MODEL')==1){
			$url_arr = explode('/',$info);
			$filename = APPLICATION_PATH.$url_arr[0]."/Views/".$url_arr[1]."/".$url_arr[2].'.'.Config::get("URL_HTML_SUFFIX");
			if(!file_exists($filename)){
				p("<h1>Url:找不到".$info."对应的文件！<br/>文件路径：$filename</h1>"); 
			}
			return $filename;
		}
	}

	
	//生成基于HTTPS协议的URL
	public static function toSecure(){
	
	}
	
	//获取当前请求的URL
	public static function current(){
	
	}
	
	
	
	
}
<?php
return array(
	//'配置项'=>'配置值'
	'URL_MODEL'          => '1', 		 //URL模式      1,普通模式 2,PATH INFO 模式 3,phpinfo伪静态 //2和3 暂时不继续编写
	'URL_HTML_SUFFIX'	 => 'html',		 //模板后缀

	//模板配置
	'__PUBLIC__'		 => strlen(str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME'])))>1 ? 
							str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME'])).'/Public' : 
							str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME'])).'Public',
	
	//子目录，当应用在更目录的时候返回空，当在子目录的时候返回子目录，格式如下：/dir1/dir2/dir3
	'CHILDREN_DIR'		 => strlen(str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME'])))>1 ? 
							str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME'])) : 
							'',
							
	// 定义当前请求的系统常量
	'REQUEST_METHOD'	 => $_SERVER['REQUEST_METHOD'],
	'IS_GET'			 => $_SERVER['REQUEST_METHOD'] =='GET' ? true : false,
	'IS_POST' 			 => $_SERVER['REQUEST_METHOD'] =='POST' ? true : false,
	'IS_PUT'			 => $_SERVER['REQUEST_METHOD'] =='PUT' ? true : false,
	'IS_DELETE'			 => $_SERVER['REQUEST_METHOD'] =='DELETE' ? true : false,
	
	//操作提示的模板
	'JUMP_PAGE'			 => FRAMEWORK_PATH."Template/jump.html",
	
	//数据库相关配置
	'DB_TYPE'   	=> 'mysql', // 数据库类型
	'DB_HOST'   	=> '127.0.0.1', // 服务器地址
	'DB_NAME'   	=> '', // 数据库名
	'DB_USER'   	=> '', // 用户名
	'DB_PASSWORD'	=> '', // 密码
	'DB_PORT'   	=> '3306', // 端口
	'DB_PREFIX' 	=> 'test_', // 数据库表前缀 
	'DB_CHARSET'	=> 'utf8' // 字符集

);
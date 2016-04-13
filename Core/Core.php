<?php
//加载必备的类文件
require_once FRAMEWORK_PATH."Core/Config.class.php";
require_once FRAMEWORK_PATH."Core/Request.class.php";
require_once FRAMEWORK_PATH."Core/Input.class.php";
require_once FRAMEWORK_PATH."Core/Url.class.php";
require_once FRAMEWORK_PATH."Common/functions.php";
require_once FRAMEWORK_PATH.'Core/Dispatcher.class.php';
require_once FRAMEWORK_PATH."Core/Model.class.php";
require_once FRAMEWORK_PATH."Core/View.class.php";
require_once FRAMEWORK_PATH."Core/Controller.class.php";


//路由开始
$D =new Dispatcher();
$D->dispatch();
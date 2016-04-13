<?php
class View{
	public $vars_array;
	
	public function __construct(){
		defined('__PUBLIC__') || define('__PUBLIC__',Config::get('__PUBLIC__'));
	}
	
	public function display($template = ''){
		//解开变量
		if ($this->vars_array) {
            extract($this->vars_array);
        }
	
		$template = $template == '' ? ACTION_NAME : $template;
		$file = APPLICATION_PATH.MODULE_NAME."/Views/".CONTROLLER_NAME."/".$template.'.'.Config::get('URL_HTML_SUFFIX');
		if(!is_file($file)){
			p("View:找不到模板文件！<br/>$file</h1>",true,true);
		}
	
		//编译文件名
		$parse_file = APPLICATION_PATH."Runtime/Cache/".MODULE_NAME.CONTROLLER_NAME.'/'.md5($template).'.php';
		$parse_file_dir = dirname($parse_file);
		//APPLICATION_DEBUG == true
		
		//检查目录是否存在,不存在就创建
		if(!is_dir($parse_file_dir)){
			mkdir(dirname($parse_file),0700);
		}
			
		//写入编译文件
		if (!file_exists($parse_file) || filemtime($parse_file) < filemtime($file) || APPLICATION_DEBUG) {
			$content = file_get_contents($file);
			$content = $this->parse($content);
			
			if (!file_put_contents($parse_file,$content)){
				p("View:编译文件生成出错！");
			}
		}
		
		require $parse_file;
	}
	
	//标签解析
	public function parse($content){
		
		$content = $this->includeFile($content);	//匹配 <include file="Index/Index/index"/>
		$content = $this->replaceVar($content);		//匹配变量
		$content = $this->replaceConst($content);	//匹配模板常量
		$content = $this->replaceIf($content);		//匹配if标签
		$content = $this->replaceVolist($content);	//匹配Volist



        


        //匹配格式如：<eq name="username" value="abc">
        $content = preg_replace('/<eq name="([a-zA-Z][a-zA-Z0-9_-]*)" value="([a-zA-Z0-9._-]*)">/', '<?php if (\$\\1 == "\\2") {?>', $content); 
        //匹配格式如：<eq name="user.name" value="abc">
        $content = preg_replace('/<eq name="([a-zA-Z][a-zA-Z0-9_-]*)\.([a-zA-Z][a-zA-Z0-9_-]*)" value="([a-zA-Z0-9._-]*)">/', '<?php if (\$\\1["\\2"] == "\\3") {?>', $content); 
        //匹配格式如：<eq name="vo.id" value="$uid">
        $content = preg_replace('/<eq name="([a-zA-Z][a-zA-Z0-9_-]*)\.([a-zA-Z][a-zA-Z0-9_-]*)" value="(\$[a-zA-Z][a-zA-Z0-9_-]*)">/', '<?php if (\$\\1["\\2"] == \\3) { ?>', $content); 
        
        //匹配格式如：<neq name="username" value="abc">
        $content = preg_replace('/<neq name="([a-zA-Z][a-zA-Z0-9_-]*)" value="([a-zA-Z0-9._-]*)">/', '<?php if (\$\\1 != "\\2") {?>', $content);  
        //匹配格式如：<neq name="user.name" value="abc">
        $content = preg_replace('/<neq name="([a-zA-Z][a-zA-Z0-9_-]*)\.([a-zA-Z][a-zA-Z0-9_-]*)" value="([a-zA-Z0-9._-]*)">/', '<?php if (\$\\1["\\2"] != "\\3") {?>', $content);
        //匹配格式如：<neq name="vo.id" value="$uid">
        $content = preg_replace('/<neq name="([a-zA-Z][a-zA-Z0-9_-]*)\.([a-zA-Z][a-zA-Z0-9_-]*)" value="(\$[a-zA-Z][a-zA-Z0-9_-]*)">/', '<?php if (\$\\1["\\2"] != \\3) { ?>', $content); 
       

        //匹配格式如：<eq name="user.id" value="$vo.id">
        $content = preg_replace('/<eq name="([a-zA-Z][a-zA-Z0-9_-]*)\.([a-zA-Z][a-zA-Z0-9_-]*)" value="(\$[a-zA-Z][a-zA-Z0-9_-]*)\.([a-zA-Z][a-zA-Z0-9_-]*)">/', '<?php if (\$\\1["\\2"] == \\3["\\4"]) { ?>', $content); 
        
        //匹配格式如：<empty name="username"></empty>
        $content = preg_replace('/<empty name="([a-zA-Z][a-zA-Z0-9_-]*)">/', '<?php if (empty(\$\\1)) {?>', $content);  
        //匹配格式如：<empty name="user.name"></empty>
        $content = preg_replace('/<empty name="([a-zA-Z][a-zA-Z0-9_-]*)\.([a-zA-Z][a-zA-Z0-9_-]*)">/', '<?php if (empty(\$\\1["\\2"])) {?>', $content);
        //匹配格式如：<notempty name="username"></empty>
        $content = preg_replace('/<notempty name="([a-zA-Z][a-zA-Z0-9_-]*)">/', '<?php if (!empty(\$\\1)) {?>', $content); 
        //匹配格式如：<notempty name="user.name"></empty>
        $content = preg_replace('/<notempty name="([a-zA-Z][a-zA-Z0-9_-]*)\.([a-zA-Z][a-zA-Z0-9_-]*)">/', '<?php if (!empty(\$\\1["\\2"])) {?>', $content); 

		return $content;
	}
	
	

	//匹配 <include file=""/>
	public function includeFile($content){
        $flag = preg_match_all('/<include file="([a-zA-Z][a-zA-Z0-9\/_-]*)"\/>/',$content,$matches,PREG_PATTERN_ORDER);
        foreach ($matches[1] as $str) {
        	$data = file_get_contents(Url::getTempletUrl($str));
        	$str = explode('/', $str);
	        $str = implode('\/', $str);
	       	$content = preg_replace('/<include file="'.$str.'"\/>/', $data, $content); 
        }
        return $content;
	}


	//匹配变量
	public function replaceVar($content){
		//匹配 {$test}普通变量
		$content = preg_replace('/\{\$([\w\d]+)\}/', '<?php echo $$1 ?>', $content);
		//匹配 {$test['dddd']}
		$content = preg_replace('/\{\$([\w\d]+)+\[([^]]*)\]\}/', '<?php echo $$1[$2] ?>', $content);
		//匹配 {:function()}
		$content = preg_replace('/\{\:+([^\(]+)+\(([^}]*)\)\}/', '<?php echo $1($2)?>', $content);
		//匹配 {:url('Index/Index/index',array('sss'=>'ssss'))}
		$content = preg_replace('/\{\:+([^\(]+)+\(([^)}]*)\)/', '<?php echo $1($2)?>', $content);
		// 匹配格式如：{$username}
		$content = preg_replace('/{(\$[a-zA-Z][a-zA-Z0-9_-]*)}/', '<?php echo \\1;?>', $content); 
		//匹配格式如：{$vo.id}
        $content = preg_replace('/{(\$[a-zA-Z][a-zA-Z0-9_-]*)\.([a-zA-Z][a-zA-Z0-9_-]*)}/', '<?php echo \\1["\\2"];?>', $content); 
        //匹配格式如：{$data.user.id}
        $content = preg_replace('/{(\$[a-zA-Z][a-zA-Z0-9_-]*)\.([a-zA-Z][a-zA-Z0-9_-]*)\.([a-zA-Z][a-zA-Z0-9_-]*)}/', '<?php echo \\1["\\2"]["\\3"];?>', $content);
        return $content;
	}
	

	//匹配模板常量
	public function replaceConst($content){
		//仅仅匹配 __PUBLIC__
		$content = preg_replace("/__PUBLIC__/","<?php echo __PUBLIC__;?>",$content);
		return $content;
	}
	
	//匹配if标签
	public function replaceIf($content){
		//匹配格式如：<if name="username" value="abc">
        $content = preg_replace('/<if name="([a-zA-Z][a-zA-Z0-9_-]*)" value="([a-zA-Z0-9._-]*)">/', '<?php if (\$\\1 == "\\2") {?>', $content); 
        //匹配格式如：<if name="user.id" value="1">
        $content = preg_replace('/<if name="([a-zA-Z][a-zA-Z0-9_-]*)\.([a-zA-Z][a-zA-Z0-9_-]*)" value="([a-zA-Z0-9._-]*)">/', '<?php if (\$\\1["\\2"] == "\\3") {?>', $content);  
        //匹配格式如：<if name="vo.id" value="$uid">
        $content = preg_replace('/<if name="([a-zA-Z][a-zA-Z0-9_-]*)\.([a-zA-Z][a-zA-Z0-9_-]*)" value="(\$[a-zA-Z][a-zA-Z0-9_-]*)">/', '<?php if (\$\\1["\\2"] == \\3) { ?>', $content);
        //匹配格式如：<if name="id" value="$uid.uid">
        $content = preg_replace('/<if name="([a-zA-Z][a-zA-Z0-9_-]*)" value="(\$[a-zA-Z][a-zA-Z0-9_-]*)\.([a-zA-Z][a-zA-Z0-9_-]*)">/', '<?php if (\$\\1 == \\2["\\3"]) { ?>', $content);
        //匹配格式如：<if name="user.id" value="$vo.id">
        $content = preg_replace('/<if name="([a-zA-Z][a-zA-Z0-9_-]*)\.([a-zA-Z][a-zA-Z0-9_-]*)" value="(\$[a-zA-Z][a-zA-Z0-9_-]*)\.([a-zA-Z][a-zA-Z0-9_-]*)">/', '<?php if (\$\\1["\\2"] == \\3["\\4"]) { ?>', $content); 
        //匹配格式如：<if name="key" value="$sid">
        $content = preg_replace('/<if name="([a-zA-Z][a-zA-Z0-9_-]*)" value="(\$[a-zA-Z][a-zA-Z0-9_-]*)">/', '<?php if (\$\\1 == \\2) { ?>', $content); 
        //匹配格式如：</else>
        $content = preg_replace('/<\/else>/', '<?php } else { ?>', $content);
         //匹配格式如：</eq> 或 </neq></if></empty>
        $content = preg_replace('/<\/eq>|<\/neq>|<\/if>|<\/empty>|<\/notempty>/', '<?php }?>', $content); 
        return $content;
	}
	

	//匹配volist
	public function replaceVolist($content){
		//匹配格式如：<volist name="list" id="vo">
        $content = preg_replace('/<volist name="([a-zA-Z][a-zA-Z0-9_-]*)" id="([a-zA-Z][a-zA-Z0-9_-]*)">/','<?php foreach (\$\\1 as \$key=>\$\\2) { ?>', $content);  
        //匹配格式如：<volist name="list.sub" id="sub">
        $content = preg_replace('/<volist name="([a-zA-Z][a-zA-Z0-9_-]*)\.([a-zA-Z][a-zA-Z0-9_-]*)" id="([a-zA-Z][a-zA-Z0-9_-]*)">/','<?php foreach (\$\\1["\\2"] as \$key1=>\$\\3) { ?>', $content);
        //匹配格式如：</volist>
        $content = preg_replace('/<\/volist>/','<?php }?>', $content);
        return $content;
	}
	
}
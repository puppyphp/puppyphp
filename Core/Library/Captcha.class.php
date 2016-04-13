<?php
class Captcha{
	public 	$im;			//资源句柄
	private $fontSize;		//字符尺寸
	private $width;			//图片宽度
	private $height;		//图片高度
	private $fontFile;		//字体文件
	private $codeLength;			//验证码长度
	private $code;			//验证码
	private $charset = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';//随机因子

	
	//
	public function __construct($fontSize = 32, $width = 150, $height = 50,$codeLength=4){
		$this->fontSize 	= $fontSize;
		$this->width 	= $width;
		$this->height 	= $height;
		$this->fontFile 	= FRAMEWORK_PATH.'Library/Captcha/ttfs/4.ttf';
		//$this->fontFile 	= 'Captcha/ttfs/1.ttf';
		$this->codeLength = $codeLength;
	}

	//返回图像资源
	private function createImage(){
		$this->im = imagecreate($this->width,$this->height); // 画一张指定宽高的图片
		$back = ImageColorAllocate($this->im, 245,245,245); // 定义背景颜色 第一次对 imagecolorallocate() 的调用会给基于调色板的图像填充背景色，即用 imagecreate() 建立的图像。 
		imagefill($this->im, $this->width, $this->height, $back);
	}

	//加入干扰像素
	private function createBackgroundpoint(){
		for($i=0;$i<100;$i++){
			$randcolor = ImageColorallocate($this->im,rand(0,255),rand(0,255),rand(0,255));
			imagesetpixel($this->im, rand()%$this->width , rand()%$this->height , $randcolor); // 画像素点函数
		}
	}

	//生成随机码
	private function createCode() {
		$_len = strlen($this->charset)-1;
		for ($i=0;$i<$this->codeLength;$i++) {
			$this->code .= $this->charset[mt_rand(0,$_len)];
		}
	}
	
	//生成验证字符
	private function createString(){
		for($i=0;$i<4;$i++){
			$color = ImageColorAllocate($this->im, rand(100,255),rand(0,100),rand(100,255)); // 生成随机颜色
			imagestring($this->im, 5, 2+$i*10, 1, $this->code[$i], $color);
			//imagefttext($this->im,$this->fontSize, 0, 0+5, $this->height-10, $color, $this->fontFile, $code);  
		}
	}
	
	//生成验证字符
	private function createText(){
		$_x = $this->width / 4;
		for ($i=0;$i<4;$i++) {
			$color = imagecolorallocate($this->im,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
			imagettftext($this->im,$this->fontSize,mt_rand(-30,30),$_x*$i+mt_rand(1,5),$this->height / 1.4,$color,$this->fontFile,$this->code[$i]);
		}
	}


	private function createSnow(){
		for ($i=0;$i<6;$i++) {
			$color = imagecolorallocate($this->im,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
			imageline($this->im,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color);
		}
	}

	public function createPngs(){
		$this->createImage();
		$this->createBackgroundpoint();
		$this->createSnow();
		$this->createCode();
		$this->createString();
		header('Cache-Control:max-age=1,s-maxage=1,no-cache,must-revalidate');
		header('Content-type:image/png;charset=utf8');
		ImagePNG($this->im);
		ImageDestroy($this->im);
		$this->setCode();
	}
	
	public function createPng(){
		$this->createImage();
		$this->createBackgroundpoint();
		$this->createSnow();
		$this->createCode();
		$this->createText();
		header('Cache-Control:max-age=1,s-maxage=1,no-cache,must-revalidate');
		header('Content-type:image/png;charset=utf8');
		ImagePNG($this->im);
		ImageDestroy($this->im);
		$this->setCode();
	}

	
	
	public function setCode(){
		if (!session_id()) session_start();
		//$_SESSION["code"] = strtolower($this->code);
		$_SESSION["captcha_code"] = $this->code;
		$_SESSION["captcha_time"] = time();
	}

}

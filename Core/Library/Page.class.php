<?php
class Page{
	private $listNum;			//每页显示文章条数
	private $firstRow;			//当前页的第一行,mysql limit 使用
	private $currentPage; 		//当前页数
	private $totalPagesNum; 	//文章总页数
	private $pageLiNum;			//每页显示的分页<li>连接个数
	
	/*
	* 构造函数	
	* $count 接受的文章总条数		
	* $listNum 每页显示文章条数		
	* $pageLiNum 分页导航条显示的页码链接数 e.g. << 1 2 3 4 5 >> $pageLiNum=5
	*/
	public function __construct($count,$listNum,$pageLiNum=5){
		$this->listNum = $listNum;
		$this->totalPagesNum = ceil($count / $listNum);
		$this->currentPage = Input::get('page')==NULL ? 1 : intval(Input::get('page'));
		$this->firstRow = $this->getFirstRow();
		$this->pageLiNum = $pageLiNum;		//导航条现实的导航连接数
	}
	
	//输出导航
	public function show(){		
		$str='';
		$first = $this->getCurrentPageFirstNav($this->currentPage);
		for($i=0; $i < $this->pageLiNum; $i++){
			$url = $this->createUrl($first);
			if($first == $this->currentPage){
				$str .= "<li class='current active'><a href='$url'>".$first."</a></li>";
			}else{
				$str .= "<li><a href='$url'>".$first."</a></li>";
			}
			$first++;
			if ($first > $this->totalPagesNum){break;}
		}
		
		//如果只有一页就不现实页码
		if($this->totalPagesNum == 0){
			$str ="";
		}
		
		return $this->createPrevNav().$str.$this->createNextNav();
		// << 1 2 3 4 5 >>
	}
	  
	//获取分页导航的第一个链接页码
	public function getCurrentPageFirstNav($currentPage){
		$first = ceil($currentPage / $this->pageLiNum) * $this->pageLiNum - $this->pageLiNum + 1;
		if($first <= 0){$first=1;}
		return $first;
	}
	
	//返回当前页最后一个导航连接,可能不存在
	public function getCurrentPageLastNav($currentPage){
		$first = ceil($currentPage / $this->pageLiNum) * $this->pageLiNum;
		return $first;
	}
	
	//生成 url
	public function createUrl($pageNumber){
		//phpinfo
		if(Config::get('URL_MODEL') == 1){
			$url = $_SERVER['REQUEST_URI'];
			//默认模板时的url路径
			if(MODULE_NAME == DEFAULT_MODULE && empty($_SERVER['PATH_INFO']) && !isset($_GET['m'])){
				//$url = $_SERVER['PHP_SELF'].'/'.MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
				$url = $_SERVER['PHP_SELF'].'?m='.MODULE_NAME.'&c='.CONTROLLER_NAME.'&a='.ACTION_NAME;
			}
			$index = intval(isset($_GET['page']) ? $_GET['page'] : 0);
			
			if($index){
				//$url_arr[$index+1] = $pageNumber;
				$url_arr = array_filter($_GET);
				$url_arr['page'] = $pageNumber;
				foreach($url_arr as $key=>$value){
					$url_arr_temp[] = $key.'='.$value;
				}
				$arg = implode('&',$url_arr_temp);
				$url = $_SERVER['PHP_SELF'].'?'.$arg;				
			}else{
				$page = $pageNumber;
				$url = $url."&page=".$pageNumber;
			}
			return $url;
		}
		
		$url_arr = array_filter(explode('/', $url));
		$index = array_search('page', $url_arr);
		
		if($index){
			$url_arr[$index+1] = $pageNumber;
		}else{
			$url_arr[]='page';
			$url_arr[] = $pageNumber;
		}
		
		$url = implode('/', $url_arr);
		return '/'.$url;
	}
	
	//创建导航左翻页
	public function createPrevNav(){
		if($this->getCurrentPageFirstNav($this->currentPage) <= 1){
			return;
		}
		$pageNumber = $this->getCurrentPageFirstNav($this->currentPage) - 1;
		$url = $this->createUrl($pageNumber);
		return "<li class='nav-left'><a href='$url'><<</a></li>";
	}
	
	//创建导航右翻页
	public function createNextNav(){
		if($this->totalPagesNum <= $this->getCurrentPageLastNav($this->currentPage)){
			return;
		}
		$pageNumber = $this->getCurrentPageLastNav($this->currentPage) + 1;
		$url = $this->createUrl($pageNumber);
		return "<li class='nav-right'><a href='$url'>>></a></li>";
	}
	
	//获取当前页的第一行,mysql limit 使用
	public function getFirstRow(){
		$first = ($this->currentPage - 1) * $this->listNum;
		if($first <= 0){
			$first = 0;
		}
		return $first;
	}
	
	
	public function __get($name){
		return $this->$name;
	}
	
	public function __set($name,$value){
		$this->$name = $value;
	}
	
	
}
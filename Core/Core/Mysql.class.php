<?php
class Mysql{
	private $dbh;			//数据库句柄
	public $tableName;		//驼峰表名
	public $trueTableName;	//真正的表名
	

	public function __construct(){
		try{
			$dsn = Config::get('DB_TYPE').':dbname='.Config::get('DB_NAME').';host='.Config::get('DB_HOST').';port='.Config::get('DB_PORT');
			$username = Config::get('DB_USER');
			$password = Config::get('DB_PASSWORD');
			$options = array(
					PDO::ATTR_AUTOCOMMIT=>true,
					PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_TIMEOUT => 3
			);
			$this->dbh = new PDO($dsn, $username, $password, $options);
			$this->dbh->query("SET NAMES ".Config::get('DB_CHARSET'));
		}catch(PDOException $e){
			echo "Failed to get DB handle: " . $e->getMessage() . "\n";  
    		exit;
		}
	}

	//PDOStatement::fetchAll() 返回一个包含结果集中所有剩余行的数组
	public function query($sql){
		//过滤__Article__这样的表名,然后再执行插入
		$sql = preg_replace("/__([a-zA-Z][a-zA-Z0-9_-]*)__/",Config::get('DB_PREFIX')."$1",$sql);
		$res = $this->dbh->query($sql);
		return $res->fetchAll(PDO::FETCH_ASSOC);
	}
	
	
	
	public function execute($sql, $arr=array()){
		$sql = preg_replace("/__([a-zA-Z][a-zA-Z0-9_-]*)__/",Config::get('DB_PREFIX')."$1",$sql);
		try{
			if(sizeof($arr)==0){
				$res = $this->dbh->exec($sql);
			}else{
				$stmt = $this->dbh->prepare($sql);
				//$arr = array_values($arr);  //prepare不支持字符串的下标，?,?,? 只支持 数字下标
				$res = $stmt->execute($arr);
			}
		}catch(PDOException $e){
			p("Mysql:execute：" . $e->getMessage());
			exit;
		}
		return $res;
	}
	
	
    //获取数据库表字段
    public function getTableFields(){
        $dbFields = array();
	    try{
			$res = $this->dbh->query('show columns from '.$this->trueTableName);
	        $temp = $res->fetchAll();
		}catch(PDOException $e){
			echo "MySQL getTableFields:" . $e->getMessage() . "\n";  
    		exit;
		}
        foreach($temp as $t){
        	$fields[] = $t[0];
        }
        return $fields;
    }
	
	
    //数据写入 如果成功 返回最后插入行的ID或序列值 
    public function add($data){
        $tableField = $this->getTableFields();
        foreach ($data as $key=>$value){
			if(in_array($key,$tableField)){
				$tempField[] = $key;
				$tempValue[] = $value;
				$Parameter[] = '?';
			}
        }
        
        $sqlField 		= implode(',', $tempField);
        $sqlParameter 	= implode(',', $Parameter);
        
        $insertSql = "INSERT INTO ".$this->trueTableName."(".$sqlField.") VALUES (".$sqlParameter.")";
        $res = $this->execute($insertSql,$tempValue);
        return $this->dbh->lastInsertId();
    }
    
    //数据写入 如果成功 返回最后插入行的ID或序列值 
    public function save($data){
    	if(isset($data['id']) && !empty($data['id'])){
    		$id = intval($data['id']);
    	}else{
    		p("<h1>Model->save的参数data，必须包含id主键</h1>");
    		die();
    	}

        $tableField = $this->getTableFields();

        foreach ($data as $key=>$value){
			if(in_array($key,$tableField)){
				$temp[] = $key."=?";
				$values[] = $value;
			}
        }
        
        $str = implode(',', $temp);
        
        $updateSql = "UPDATE ".$this->trueTableName." SET ".$str." WHERE id='$id'";
        //echo $updateSql;
		$res = $this->execute($updateSql,$values);
        return $res;
    }
    
    
    
    
    
    
    
    
    
    
    
    
	
}
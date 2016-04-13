<?php
class Model{
    public $db;             //数据库驱动

    public function __construct($tableName=NULL){
        $class = ucwords(strtolower(Config::get('DB_TYPE')));
        require_once $class.".class.php";
        $this->db = new $class();

        if($tableName != NULL){
            $this->setTableName($tableName);
        }
    }


    //供查询
    public function query($sql){
        return $this->db->query($sql);
    }

    
    //执行SQL语句
    public function execute($sql,$arr=array()){
        return $this->db->execute($sql,$arr);
    }

    /*
    //创建数据对象
    public function create(){
        $fields = $this->db->getTableFields();  //获取表字段
        $data = array();

        foreach($_GET as $key=>$value){
            if(in_array($key, $fields)){
                $data[$key] = $value;
            }
        }

        foreach($_POST as $key=>$value){
            if(in_array($key, $fields)){
                $data[$key] = $value;
            }
        }
		
        return $data;
    }
    */
    //创建数据对象
    public function create(){
    	$fields = $this->db->getTableFields();  //获取表字段
    	$data = array();
    	
    	foreach($fields as $key){
    		if(array_key_exists($key, $_POST)){
    			$data[$key] = $_POST[$key];
    		}
    	}
    
    	return $data;
    }


    //设置表名 表名 TagMap,对应数据库中的表 core_tag_map
    public function setTableName($tableName){
        $this->db->tableName = $tableName;

        $name_arr = preg_split("/(?=[A-Z])/", $tableName);
        foreach ($name_arr as $key => $value) {
            $name_arr[$key] = strtolower($value);
        }
        $name_arr = array_filter($name_arr);
        $str = implode('_', $name_arr);

        $this->db->trueTableName = Config::get('DB_PREFIX').$str;
    }

  	//添加create创建的数据到数据库
  	public function add($data){
  		return $this->db->add($data);
  	}
      
    //添加create创建的数据到数据库
    public function save($data){
      return $this->db->save($data);
    }
  
  
  
  








}

/*
class Database {
   protected $_conn;

   public function __construct($connection) {
       $this->_conn = $connection;
   }

   public function ExecuteObject($sql, $data) {
       // stuff
   }
}

abstract class Model {
   protected $_db;

   public function __construct(Database $db) {
       $this->_db = $db;
   }
}

class User extends Model {
   public function CheckUsername($username) {
       // ...
       $sql = "SELECT Username FROM" . $this->usersTableName . " WHERE ...";
       return $this->_db->ExecuteObject($sql, $data);
   }
}

$db = new Database($conn);
$model = new User($db);
$model->CheckUsername('foo');

Everything that is business logic belongs in a model, whether it is a database query, calculations, a REST call, etc.

You can have the data access in the model itself (the MVC pattern doesn't restrict you from doing that), but it's easier to have a separate object that actually executes the database queries instead of having them being executed in the model directly: this will especially come in handy when unit testing (because of the easiness of injecting a mock database dependency in your model):

*/















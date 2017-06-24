<?php
/*************************************************************************
 *     File Name: Netease.php
 *     Author: fannner
 *     Created Time: 2017年05月16日 星期二 18时28分19秒
 *************************************************************************/
class Db_NewPdo {

	const LIST_COM = 0;
	const LIST_AND = 1;
	const LIST_SET = 2;

    private static $instance = null;
	private $dsn;
	private $dbUser;
	private $dbPass;
	private $sth;
	private $dbh;
	
	public function __construct() {
		header("Content-Type:text/html; charset=utf-8");
		//这里在Bootstrap阶段注册了config类Yaf_Registry::set("config", Yaf_Application::app()->getConfig());
		$dbConf = Yaf_Registry::get('config')->database;
		$this->dsn = 'mysql:host='.$dbConf->server.';port='.$dbConf->port.';dbname='.$dbConf->database_name;
		$this->dbUser = $dbConf->username;
		$this->dbPass = $dbConf->password;
		$this->connect();
		$this->dbh->query('SET NAMES '.$dbConf->charset);
	}

	public static function getInstance() {
		if (self::$instance === null) {
			self::$instance = new Db_NewPdo();
		}
		return self::$instance;
	}
	
	//连接数据库
	private function connect() {
		try {
			$this->dbh = new PDO($this->dsn,$this->dbUser,$this->dbPass);
		} catch (PDOException $e) {
			//这里需要做处理
			exit('连接失败:'.$e->getMessage());
		}
	}

	public function select($tables, $fields, $conds = NULL, $appends = NULL) {
        	$sql = 'SELECT ';
        	// 1. fields
        	$fields = $this->__makeList($fields, self::LIST_COM);
        	if(!strlen($fields)) {
           	 	$sql = NULL;
            		return NULL;
        	}
        	$sql .= "$fields FROM ";

		// 2. from
		$tables = $this->__makeList($tables, self::LIST_COM);
		if(!strlen($tables)) {
		    	$sql = NULL;
		    	return NULL;
		}
		$sql .= $tables;

		// 3. conditions
		if($conds !== NULL) {
		    	$conds = $this->__makeList($conds, self::LIST_AND);
		    	if(!strlen($conds)) {
				$sql = NULL;
				return NULL;
		    	}
		    	$sql .= " WHERE $conds";
        	}

		// 4. other append
		if($appends !== NULL) {
			$appends = $this->__makeList($appends, self::LIST_COM, ' ');
			if(!strlen($appends)) {
			$sql = NULL;
			return NULL;
		    }
		    $sql .= " $appends";
		}

		$query = $this->dbh->prepare($sql);
		$ret = array();
		$query->execute();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$ret[] = $row;
		}
		return $ret;
    	}
	
	public function selectCount($tables, $conds = NULL, $appends = NULL) {

		$fields = "COUNT(*) as num";
		$count = $this->select($tables, $fields, $conds, $appends);
		if ($count) {
			return $count[0]['num'];
		}
		return false;
	}
	
	public function insert($table, $row, $onDup = NULL) {
		$sql = 'INSERT ';

		// 1. table
		$sql .= "$table SET ";

		// 2. clumns and values
		$row = $this->__makeList($row, self::LIST_SET);
		if(!strlen($row)) {
		    $sql = NULL;
		    return NULL;
		}
        	$sql .= $row;

		if(!empty($onDup)) {
		    $sql .= ' ON DUPLICATE KEY UPDATE ';
		    $onDup = $this->__makeList($onDup, self::LIST_SET);
		    if(!strlen($onDup)) {
			$sql = NULL;
			return NULL;
		    }
		    $sql .= $onDup;
		}

		$query = $this->dbh->prepare($sql);
		$query->execute();

		if (!$query->rowCount()) {
			return false;
		}

		return true;
	}


	public function update($table, $row, $conds = NULL, $appends = NULL) {
		//1.fields
		$sql = "UPDATE $options $table SET ";
		$row = $this->__makeList($row, self::LIST_SET);
		if(!strlen($row)) {
			$sql = NULL;
			return NULL;
		}
		$sql .= "$row ";

		// 2.conditions
		if($conds !== NULL) {
		    $conds = $this->__makeList($conds, self::LIST_AND);
		    if(!strlen($conds)) {
			$sql = NULL;
			return NULL;
		    }
		    $sql .= "WHERE $conds ";
		}

		// 3. other append
		if($appends !== NULL) {
		    $appends = $this->__makeList($appends, self::LIST_COM, ' ');
		    if(!strlen($appends)) {
			$sql = NULL;
			return NULL;
		    }
		    $sql .= $appends;
		}
		$query = $this->dbh->prepare($sql);
		$query->execute();

		if (!$query->rowCount()) {
			return false;
		}

		return true;
		
	}

	public function escapeString($str) {
		return $this->dbh->quote($str);
	}
	
	private function __makeList($arrList, $type = self::LIST_SET, $cut = ', ') {
		if(is_string($arrList)) {
		    return $arrList;
		}
		$sql = '';

		// for set in insert and update
		if($type == self::LIST_SET) {
			foreach($arrList as $name => $value) {
				if(is_int($name)) {
					$sql .= "$value, ";
				} else {
					if(!is_int($value)) {
						if($value === NULL) {
							$value = 'NULL';
						} else {
							//$value = '\''.$this->escapeString($value).'\'';
							$value = $this->escapeString($value);
						}
					}
					$sql .= "$name=$value, ";
				}
			}
			$sql = substr($sql, 0, strlen($sql) - 2);
		} else if($type == self::LIST_AND) {
			// for where conds
			foreach($arrList as $name => $value) {
				if(is_int($name)) {
					$sql .= "($value) AND ";
				} else {
					if(!is_int($value)) {
						if($value === NULL) {
							$value = 'NULL';
						} else {
							$value = $this->escapeString($value);
							//$value = '\''.$this->escapeString($value).'\'';
						}
					}
					$sql .= "($name $value) AND ";
				}
			}
			$sql = substr($sql, 0, strlen($sql) - 5);
		} else {
			$sql = implode($cut, $arrList);
		}
		return $sql;
	}	

	/*private function getPDOError() {
		if ($this->dbh->errorCode() != '00000')
		{
			$error = $this->dbh->errorInfo();
			if(strstr($error[2], 'Duplicate entry')){
				$err_info  ='';
			}else{
				$err_info = $error[2];
			}
		}
		return $err_info;
	}*/

	//关闭数据连接
	public function __destruct() {
		$this->dbh = null;
	}
}

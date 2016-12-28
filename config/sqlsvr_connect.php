<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	class sqlsvr_connect extends PDO
	{
		private $host;
		private $username;
		private $password;
		public $connection ;
		
		public function __construct($host,$username,$password){
			$this->host=$host;
			$this->username = $username;
			$this->password = $password;
			$this->database = $database;
			try{
				$this->connection = odbc_connect("$host",$username,$password);
			}catch(PDOException $e){
				echo "Error : ".$e->getMessage();
			}
		}	

		public function fetch($table,$field,$where = null){
			if($field == ""){ $field = "*"; }
			$sql = "select $field from $table";
			if($where != null){
				$sql .= " where ".$where;
			}
			$query = odbc_exec($this->connection,$sql) or die($this->connection->error);
			
			$rows = array();
			
			while($row = odbc_fetch_array($query)){
				$rows[] = $row;
			}
			
			return $rows;
		}		
		
		
		public function insert($table , $rows = null)
		{
			$sql = "insert into $table";
			$row = null;
			$value = null;
			foreach($rows as $key => $nilai)
			{
				$row .= ",".$key;
				$value .= ",'".$nilai."'";
			}
			$sql .= "(".substr($row,1).")";
			$sql .= " values(".substr($value,1).")";
			
			
			$query = odbc_prepare($this->connection,$sql) or die($this->connection->error);
			$query = odbc_exec($this->connection,$sql) or die($this->connection->error);
		}
		
		public function update($table, $fild = null, $where = null)
		{
			$sql = "update $table set ";
			$set = null;
			foreach($fild as $key =>$values)
			{
				$set .= ", ".$key."= '".$values."'";
			}
			$sql .= substr($set,1)." where $where";
			
			$query = odbc_prepare($this->connection,$sql) or die($this->connection->error);
			$query = odbc_exec($this->connection,$sql) or die($this->connection->error);
		}
		
		public function delete($table,$where)
		{
			if($where != ""){
				$sql = "delete from $table where $where";
			}else{
				$sql = "delete from $table";
			}
			
			$query = odbc_prepare($this->connection,$sql) or die($this->connection->error);
			$query = odbc_exec($this->connection,$sql) or die($this->connection->error);
		}
		
		public function __destruct()
		{
			$this->connection = null;
		}

	}
	
	// beberapa jenis koneksi database
	
	$crud_sql = new sqlsvr_connect("kinosentraacc","sa","");	
?>
<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	class koneksi extends PDO
	{
		private $host;
		private $username;
		private $password;
		private $database;
		public $connection ;
		
		public function __construct($host,$username,$password,$database){
			$this->host=$host;
			$this->username = $username;
			$this->password = $password;
			$this->database = $database;
			try{
				$this->connection = new PDO("mysql:host=$this->host;dbname=$this->database",$this->username,$this->password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
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
			$query = $this->connection->query($sql) or die($this->connection->error);
			return $query->fetchAll(PDO::FETCH_ASSOC);
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
			
			$query = $this->connection->prepare($sql) or die($this->connection->error);
			$query->execute();
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
			$query = $this->connection->prepare($sql)or die($this->connection->error);
			$query->execute();
		}
		
		public function delete($table,$where)
		{
			if($where != ""){
				$sql = "delete from $table where $where";
			}else{
				$sql = "delete from $table";
			}
			$this->connection->query($sql) or die($this->connection->error);
		}
		
		public function module_alert(){
			return "<div class=\"col-sm-12 col-md-12 col-lg-6\">
						<div class=\"alert alert-warning\" role=\"alert\">
							You don't have permission to this operation..please contact your administrator !!
						</div>
					</div>";			
		}		
		
		public function message_success($message){
			return "<div class=\"col-sm-12 col-md-12 col-lg-12\">
						<div class=\"col-sm-12 col-md-12 col-lg-6\">
							<div class=\"alert alert-info\" role=\"alert\">
								$message
							</div>
						</div>
					</div>";	 
		}
		
		public function message_error($e){
			return "<div class=\"col-sm-12 col-md-12 col-lg-6\">
						<div class=\"alert alert-danger\" role=\"alert\">
							$e;
						</div>
					</div>";	 
		}
		
		public function cek_akses($username,$group_id,$departemen_id,$module_id){
			$sql = "select * from v_user_module where username='".$username."' 
			       and group_id='".$group_id."' and departemen_id='".$group_id."' 
				   and module_id='".$module_id."'";
			
			$query = $this->connection->query($sql) or die($this->connection->error);
			return $query->fetchAll(PDO::FETCH_ASSOC); 
			return $sql;
		}
		
		public function __destruct()
		{
			//$this->connection->close();
		}
		
		public function cetak_tanggal($tanggal){
			if(($tanggal == "0000-00-00 00:00:00")or($tanggal == "")){
				$date = "";
			}else{
				$date = date('d M Y',strtotime($tanggal));
			}
			return $date;
		}
		
		public function sendmail($from,$subject,$message){
			$headers = "From: noreply.skproject@morinaga-kino.co.id\r\n";
			$headers .= "Reply-to: noreply.skproject@morinaga-kino.co.id\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n"; 

			// send email
			@mail($from,$subject,$message,$headers);
		}

	}
	
	// beberapa jenis koneksi database
	
	$crud = new koneksi("localhost","root","","skproject");	
?>
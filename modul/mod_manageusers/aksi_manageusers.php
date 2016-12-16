<?php 
	session_start();
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	include "../../config/koneksi.php";
	
	$r = $_GET['r'];
	$mod = $_GET['mod'];
	$act = $_GET['act'];
	$id = $_GET['id'];
	
	if($r == "groupuser" and $act == "del"){
		
		$group_id = $_GET['id'];
		try{
			$crud->delete("group_user","group_id = '$group_id' ");
			$_SESSION['message'] = $crud->message_success("Group ID : ".$group_id." has been deleted successfully !!");
		}catch(exception $e){
			$_SESSION['message'] = $crud->message_error($e->getmessage());
		}
		header("location:../../user.php?r=$r&mod=$mod");
	}
	
	//menambahkan user baru
	if($r == "manageusers" and $act == "add"){
		
		$username = $_POST['username'];
		$password = md5($_POST['username']."@".$_POST['password']);
		$fullname = $_POST['fullname'];
		$handphone = $_POST['handphone'];
		$email = $_POST['email'];
		$uploads_dir = "../../../images/";
		$tmp_name = $_FILES["foto"]["tmp_name"];
		$filename = $_FILES["foto"]["name"];		
		
		try{
			if(empty($filename)){			
				$data = array("username" => $username,"password" => $password,"full_name" => $fullname,
							  "hp" => $handphone, "email" => $email,"foto" => "images/default.png");				
			}else{
				move_uploaded_file($tmp_name,$uploads_dir.$filename);
				$data = array("username" => $username,"password" => $password,"full_name" => $fullname,
							  "hp" => $handphone, "email" => $email,"foto" => "images/".$filename);				
			}
			$crud->insert("user",$data);
			$_SESSION['message'] = $crud->message_success("Username  : ".$username." has been saved successfully !!");
		}catch(exception $e){
			$_SESSION['message'] = $crud->message_error($e->getmessage());
		}
		
		header("location:../../user.php?r=$r&mod=$mod");
	}
	
	if($r == "manageusers" and $act == "update"){
		
		$username = $_POST['username'];
		$password = md5($_POST['username']."@".$_POST['password']);
		$fullname = $_POST['fullname'];
		$handphone = $_POST['handphone'];
		$email = $_POST['email'];
		$uploads_dir = "../../../images/";
		$tmp_name = $_FILES["foto"]["tmp_name"];
		$filename = $_FILES["foto"]["name"];
		
		try{
			if(empty($filename)){			
				if($password == ""){
					$data = array("full_name" => $fullname,"hp" => $handphone, 
								"email" => $email);
				}else{
					$data = array("password" => $password,"full_name" => $fullname,
							  "hp" => $handphone, "email" => $email);
				}				
			}else{
				move_uploaded_file($tmp_name,$uploads_dir.$filename);
				if($password == ""){
					$data = array("full_name" => $fullname,"hp" => $handphone, 
								"email" => $email,"foto" => "images/".$filename);
				}else{
					$data = array("password" => $password,"full_name" => $fullname,
							  "hp" => $handphone, "email" => $email,"foto" => "images/".$filename);
				}
			}
			$crud->update("user",$data,"username = '$username' ");	
			$_SESSION['message'] = $crud->message_success("Username : ".$username." has been updated successfully !!");
		}catch(exception $e){
			$_SESSION['message'] = $crud->message_error($e->getmessage());
		}
		header("location:../../user.php?r=$r&mod=$mod");
		
	}
	
	if($r == "manageusers" and $act == "departemen"){
		$username = $_POST['username'];
		$departemen_id = $_POST['departemen_id'];
		$group_id = $_POST['group_id'];
		
		try{
			$data = array("username"=>$username,"group_id" => $group_id,"departemen_id" => $departemen_id);
			$crud->insert("user_authority",$data);
			$_SESSION['message'] = $crud->message_success("Departemen  : ".$departemen_id." has been added to user : ".$username." successfully !!");
		}catch(exception $e){
			$_SESSION['message'] = $crud->message_error($e->getmessage());
		}
		
		header("location:../../user.php?r=".$r."&mod=".$mod."&act=departemen&id=".$username);
	}
	
	if($r == "manageusers" and $act == "del_departemen"){
		
		$username = $_GET['id'];
		$departemen_id = $_GET['dep'];
		$group_id = $_GET['group_id'];
		
		try{
			$crud->delete("user_authority","username = '$username' and departemen_id='$departemen_id' and group_id='$group_id' ");
			$_SESSION['message'] = $crud->message_success("Departemen ID : ".$departemen_id." has been deleted successfully !!");
		}catch(exception $e){
			$_SESSION['message'] = $crud->message_error($e->getmessage());
		}
		header("location:../../user.php?r=$r&mod=$mod&act=departemen&id=$username");
		
	}
	
	if($r == "manageusers" and $act == "default"){
		
		$username = $_GET['id'];
		$departemen_id = $_GET['dep'];
		$group_id = $_GET['group_id'];
		
		
		try{
			//update dulu ceknya jadi 0 semua.
			$data = array("cek" => 0);
			$crud->update("user_authority",$data,"username = '$username'");
			
			//update cek untuk username, departemen, dan group tersebut menjadi satu atau default.
			$data = array("cek" => 1 );
			$crud->update("user_authority",$data,"username = '$username' and departemen_id='$departemen_id' and group_id='$group_id' ");
			$_SESSION['message'] = $crud->message_success("Departemen : ".$departemen_id." has been set default successfully !!");
		}catch(exception $e){
			$_SESSION['message'] = $crud->message_error($e->getmessage());
		}
		header("location:../../user.php?r=$r&mod=$mod&act=departemen&id=$username");
	}


?>
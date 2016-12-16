<?php
	/*=====================================================================
	created by toeko triyanto
	logout.php is a file that used for destroy session user in web browser
	=======================================================================*/
	  session_start();
	  session_unset();
	  session_destroy();
	  header('location:index.php');
	  
?>

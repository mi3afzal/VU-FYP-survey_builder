<?php
session_start();
session_destroy();
		  header("Refresh: 2; url=index.php");
		  echo"Logout successfully....";
	
		
?>


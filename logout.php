<?php
	session_start();

	//unset variables that were set in login
	unset($_SESSION['uName']);
	unset($_SESSION['uID']);
	unset($_SESSION['position']);
	
	//destroy the session completely
	session_destroy();
	
	Header("Location:index.php");

?>
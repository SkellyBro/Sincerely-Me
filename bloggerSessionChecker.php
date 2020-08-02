<?php
//this is used to check if the logged in user is a blogger, if the user is not a blogger they are redirected to the login screen. 
    session_start();
	if($_SESSION['position']!=1){
		
		Header("Location:login.php?feedback=You must be a logged in sitter to access this page...");
		
	}
?>
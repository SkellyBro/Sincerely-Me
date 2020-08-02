<?php
//this is used to check if the logged in user is an administrator, if the user is not an admin they are redirected to the login screen. 
    session_start();
	if($_SESSION['position']!=2){
		
		Header("Location:login.php?feedback=You must be a logged in to access this page...");
		
	}
?>
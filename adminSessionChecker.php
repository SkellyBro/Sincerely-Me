<?php
    session_start();
	if($_SESSION['position']!=2){
		
		Header("Location:login.php?feedback=You must be a logged in to access this page...");
		
	}
?>
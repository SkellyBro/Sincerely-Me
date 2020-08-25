<?php
ob_start();
	/*This page handles the login for the site*/
	if(isset($_POST['submit'])){
		$uName= $_POST['uName'];
		$pass= $_POST['pass'];
		//create variables for error handling
		$count=0; 
		$feedback="";
		$success="";
		
		//variable to get the user's ID
		$uID=0;
		$position=0;
		
		//validate data
		validate($uName, $pass);
		
		//sanitize data
		sanitize($uName, $pass);
		if($count==0){
			//function to authenticate user
			if(authentication($uName, $pass)){
				if($position==1){
					//do session start here
					session_start();
					$_SESSION['uName']=$uName;
					$_SESSION['uID']=$uID;
					$_SESSION['position']=$position;
					echo $_SESSION['uID'];
					header("Location:index.php");
				}else if($position==2){
					//do session start here
					session_start();
					$_SESSION['uName']=$uName;
					$_SESSION['uID']=$uID;
					$_SESSION['position']=$position;
					echo $_SESSION['uID'];
					header("Location:index.php");
				}//end of if
			}//end of authentication-if
		}//end of if
	}//end of isset
	
	/*Function to authenticate user
	*$uN refers to the entered username
	*$p refers to the entered password
	*/
	function authentication($uN, $p){
	global $count; 
	global $feedback;
	global $success; 
	global $uID;
	global $position;
	
	//include database connection
	include('dbConnect.php');
	
	//encrypt password with MD5
	$encrypted= md5($p);
	
	if($stmt= mysqli_prepare ($mysqli, 
		"SELECT tbluser.userID, tbluser.position FROM tbluser WHERE tbluser.username=? AND tbluser.password=?"))
		{

			//bind entered parameters to mysqli statement
			mysqli_stmt_bind_param($stmt, "ss", $uN, $encrypted);
			
			//execute mysqli statement
			mysqli_stmt_execute($stmt);
			
			//bind result to global variables
			mysqli_stmt_bind_result($stmt, $uID, $position);

			if (mysqli_stmt_fetch($stmt))
			{
				$success="<br/>Login Successful!";
				return true;
			}else{
				
				$feedback.="<br/>Invalid Login Credentials";
				$count++;
				return false;
			}//end of mysqli_stmt_fetch
		
		mysqli_stmt_close($stmt);
		}//end of stmt
		mysqli_close($mysqli);
		
	}//end of authentication
	
	
	/*function to escape any special characters from entered user data
	*$val is the variable being escaped
	*/
	function escapeString($val){

		//include database connections
		include('dbConnect.php');
		
		//sanitize data going into MySQL
		$val= mysqli_real_escape_string($mysqli, $val);
		return $val;
	}//end of escapeString
	
	/*Function to validate data
	*$uN refers to the entered username
	*$p refers to the entered password
	*/
	function validate($uN, $p){
		global $count;
		global $feedback; 
		
		//username validation
		if($uN=="" || $uN==null){
			$count++;
			$feedback.="<br/>You must enter a username to login!";
		}else if(!preg_match("/^[\w]+$/", $uN)){
			$count++;
			$feedback.="<br/>Your username cannot contain special characters.";
		}//end of username validation
		
		//password validation
		if($p=="" || $p==null){
			$count++;
			$feedback.="<br/>You must enter a password.";
		}else if(strlen($p)<8){
			$count++;
			$feedback.="<br/>Your password cannot be less than 8 characters.";
		}
		//end of password validation
		
	}//end of validation function
	
	/*Function to sanitize data
	*$uN refers to the username
	*$p refers to the user's password
	*/
	function sanitize($uN, $p){
		//sanitize form data
		$uN= filter_var($uN, FILTER_SANITIZE_STRING);
		$p= filter_var($p, FILTER_SANITIZE_STRING);
		
		//include escape string function here, this uses the mysqli escape string to prevent special characters from being entered into the db
		escapeString($uN);
		escapeString($p);
		
	}//end of sanitize

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Sincerely, Me.</title>
  <meta content="" name="descriptison">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="assets/vendor/venobox/venobox.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/css/custom.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Eterna - v2.0.0
  * Template URL: https://bootstrapmade.com/eterna-free-multipurpose-bootstrap-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Top Bar ======= -->
  <section id="topbar" class="d-none d-lg-block"></section>

  <!-- ======= Header ======= -->
  <header id="header">
    <div class="container d-flex">

      <div class="logo mr-auto">
        <!-- Uncomment below if you prefer to use an image logo -->
		<a href="index.php"><img src="assets/img/logo.PNG" alt="" class="img-fluid"></a>
      </div>

      <nav class="nav-menu d-none d-lg-block">
        <ul>
          <li class="active"><a href="index.php">Home</a></li>

          <li class="drop-down"><a href="#">About</a>
            <ul>
              <li><a href="admin.php">About Us</a></li>
			  <li><a href="contact.php">Contact</a></li>
            </ul>
          </li>

         
          <li><a href="login.php">Login</a></li>
          <li><a href="registration.php">Register</a></li>

        </ul>
      </nav><!-- .nav-menu -->

    </div>
  </header><!-- End Header -->

  <main id="main">

    <!-- ======= Breadcrumbs ======= -->
    <section id="breadcrumbs" class="breadcrumbs">
      <div class="container">

        <ol>
          <li><a href="index.php">Home</a></li>
          <li><a href="#">Login</a></li>
        </ol>
        <h2>Login</h2>

      </div>
	  
    </section><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="form">
	
	
      
	  <!--Content Here :0-->
	  <div class="container">
	   <?php
			global $feedback;
			global $count; 
			global $success;
			
	         if($feedback != ""){
		 
		     echo "<div class= 'alert alert-danger'>"; 
		       if ($count == 1) echo "<strong>$count error found.</strong>";
			   if ($count > 1) echo "<strong>$count errors found.</strong>";
		     echo "$feedback
			   </div>";
			}//end of error code
			
			//this is feedback code for success messages
			if($success != ""){
				 echo "<div class= 'alert alert-success'>"; 
				 echo "$success
				   </div>";
			}//end of if statement for error
			
			if(isset($_GET['feedback'])){
				$feedback=$_GET['feedback'];
				
				 if($feedback != ""){
		 
				 echo "<div class= 'alert alert-danger'>"; 
				   if ($count == 1) echo "<strong>$count error found.</strong>";
				   if ($count > 1) echo "<strong>$count errors found.</strong>";
				 echo "$feedback
				   </div>";
				}//end of error code
				
				//this is feedback code for success messages
				if($success != ""){
					 echo "<div class= 'alert alert-success'>"; 
					 echo "$success
					   </div>";
				}//end of if statement for error
			}

		?>
	  
	  
		<form class="form-horizontal" action="login.php" onsubmit="return valLog(this)" method="post">
		
			<div class="form-group"> 
				<label class="control-label col-sm-2">Username:</label>
			 <div class="col-sm-10">

				<input type="text" class="form-control" name="uName" id="uName" aria-labelledby="username"/> 
			 </div>
			 <span class="error" id="user_err"></span>
			</div>
			
			<div class="form-group"> 
				<label class="control-label col-sm-2">Password:</label>
			 <div class="col-sm-10">

				<input type="password" class="form-control" name="pass" id="pass" aria-labelledby="password"/> 
			 </div>
			 <span class="error" id="pass_err"></span>
			</div>
			
			<div class="col-sm-10">
				<input type="submit" class="btn btn-outline-primary form-control sincerely" name="submit" value="Login" onClick="return valLog();"/>
			</div>
		</form>
		<br/>
		<br/> 
		<a href="fpassword.php">Forgot Password?</a>
	 	<p>If you have any problems logging in please contact an administrator <a href="contact.php">Here!<a/></p>
	 
	  
	  </div>
	  
    </section><!-- End Blog Section -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">

    <div class="footer-top">
      <div class="container">
        <div class="row">

         <div class="col-lg-3 col-md-6 footer-links">
            <h4>Useful Links</h4>
             <ul>
			  <li class="active"><a href="index.php">Home</a></li>
			  <li><a href="admin.php">About</a></li>
			  <li><a href="contact.php">Contact</a></li>			  
			  <li><a href="userAccount.php">Your Account</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-contact">
            <h4>Contact Us</h4>
            <p>
             Gulf View Medical Centre <br>
             715-716 Mc Connie St<br>
              Trinidad and Tobago <br><br>
              <strong>Phone:</strong> 868-283-HELP(4357) / <br/>868-798-4261<br>
              <strong>Email:</strong> theracoconsultants@gmail.com<br>
            </p>

          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong><span>Sincerely, Me</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/eterna-free-multipurpose-bootstrap-template/ -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/jquery-sticky/jquery.sticky.js"></script>
  <script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="assets/vendor/counterup/counterup.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/venobox/venobox.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script src="assets/js/validation.js"></script>

</body>

</html>
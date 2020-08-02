<?php
	/*This handles the registration for the website*/
	
	//this isset handles the actual registration
	if(isset($_POST['submit'])){
		$uName= $_POST['uName'];
		$pass= $_POST['pass'];
		$cPass= $_POST['cPass'];
		$captcha=$_POST['captcha_code'];
		$uID=0;
		
		//error count variable
		$count=0;
		//feedback message
		$feedback="";
		//success message
		$success="";
		
		//validate user information
		validate($uName, $pass, $cPass, $captcha);
		
		//sanitize information before making insert into database
		sanitize($uName, $pass);
		
		//check username to ensure that it is unique in the database to prevent conflicts
		usernameCheck($uName);
		
		//insert into database
		
		if($count==0){
			//this function makes the insert into the database
			insert($uName, $pass);
			//this function gets the user's ID for another insert into the blogger table
			getID($uName);
			//this function inserts the user's name into the disjointed table
			insertBlogger($uID);
		}
	}//end of isset
	
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
	
	/*function to get the user's ID for insert into another table
	*$uName is the user's username. 
	*/
	function getID($uName){
		global $count;
		global $feedback;
		global $uID; 
		
		//include database connection
		include('dbConnect.php');
		
		if($stmt=mysqli_prepare($mysqli, 
		"SELECT tblUser.userID FROM tblUser WHERE tblUser.username=?"
		)){
			//bind entered parameters to mysqli object
			mysqli_stmt_bind_param($stmt, "s", $uName);
			
			//execute the stmt
			mysqli_stmt_execute($stmt);
			
			//get results of query
			mysqli_stmt_bind_result($stmt, $uID);
			
			if(mysqli_stmt_fetch($stmt)){
				return true; 
			}else{
				$count++;
				$feedback.="Error occured with procuring ID, please contact an administrator for assistance";
				return false; 
			}//end of if-else 
			mysqli_stmt_close($stmt);
		}//end of stmt
		mysqli_close($mysqli);
	}//end of getID
	
	/*function to make the insert into the database into the user table
	*$uN is the user's username
	*$p is the user's password
	*/
	function insert($uN, $p){
		global $count;
		global $feedback;
		global $success; 
		
		//create new date object for insert
		$date=date("Y-m-d");
		
		//encrypt password with MD5
		$encrypted= md5($p);
		
		//position for the user
		$pos=1;
		
		//include database connection
		include('dbConnect.php');
		
		if($stmt= mysqli_prepare($mysqli, 
		"INSERT INTO tblUser(username, password, regDate, position) VALUES(?,?,?,?)")){
			//bind parameters to SQL Object
			mysqli_stmt_bind_param($stmt,"sssi", $uN, $encrypted, $date, $pos);
			
			//execute statement and see if successful
			if(mysqli_stmt_execute($stmt)){
				return true; 
			}else{
				$feedback.="Registration Unsuccessful. Please contact an administrator for assistance!<br/> User insert failed.";
				$count++;
				return false;
			}//end of if-else
		mysqli_stmt_close($stmt);
		}//end of stmt
		mysqli_close($mysqli);
	}//end of insert
	
	function insertBlogger($uID){
		global $count; 
		global $feedback;
		global $success;		
		
		//create new random object for insert
		//create random number to use as bloggerID
		$bloggerID=rand(10,10000);
		
		include('dbConnect.php');
		
		if($stmt= mysqli_prepare($mysqli, 
		"INSERT INTO tblBlogger(userID, bloggerID) VALUES(?,?)")){
			//bind parameters to SQL Object
			mysqli_stmt_bind_param($stmt,"ii", $uID, $bloggerID);
			
			//execute statement and see if successful
			if(mysqli_stmt_execute($stmt)){
				$success="Registration Successful! You will be redirected to the login page shortly!";
				header("refresh:3; url=login.php" ); 
			}else{
				$feedback.="Registration Unsuccessful. Please contact an administrator for assistance! <br/> Blogger insert failed";
				$count++;
				return false;
			}//end of if-else
		mysqli_stmt_close($stmt);
		}//end of stmt
		mysqli_close($mysqli);
	}//end of insertBlogger
	
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
	
	/*This function checks to see if the user's username has been taken
	*$uN is the user's username
	*/
	function usernameCheck($uN){
		global $count; 
		global $feedback;
		
		include("dbConnect.php");
		//do check here
		
		if($stmt =mysqli_prepare($mysqli, 
		"SELECT * FROM tblUser WHERE tblUser.username=?")){
			//bind entered parameters to mysqli statement
			 mysqli_stmt_bind_param($stmt, "s", $uN);
			 
			 //execute the stmt
			 mysqli_stmt_execute($stmt);

			 //echo results of query
			 if(mysqli_stmt_fetch($stmt))
			 {	 
				 $count++;
				 $feedback.="</br>Your chosen username has been taken already, please enter another username.";
				 return false;
			 }else{
				 return true;
			 }//end of if-else
		mysqli_stmt_close($stmt);
		}//end of stmt
		mysqli_close($mysqli);
	}//end of usernameCheck
	
	/*This function is used to validate the entered user information
	*$uN refers to the user's username
	*$p refers to the user's password
	*cP refers to the confirmation password
	*/
	
	function validate($uN, $p, $cP, $capt){
		global $count;
		global $feedback;
		
		//start of username validation
		if($uN=="" || $uN==null){
			$count++;
			$feedback.="<br/>You must enter a username.";
		}
		
		if(strlen($uN)<4 || strlen($uN)>25){
			$count++;
			$feedback.="<br/>Your username must be between 4-25 characters.";
		}
		
		if(!preg_match("/^[\w]+$/", $uN)){
			$count++;
			$feedback.="<br/>Your username cannot contain special characters.";
		}//end of username validation
		
		//start of password validation
		if($p=="" || $p==null){
			$count++;
			$feedback.="<br/>You must enter a password.";
		}
		
		if(strlen($p)<8){
			$count++;
			$feedback.="<br/>Your password cannot be less than 8 characters.";
		}
		//end of password validation
		
		//confirm password validation
		if($cP=="" || $cP==null){
			$count++;
			$feedback.="<br/> Confirm Password Required.";
		}
		
		if(strcmp($p, $cP)!==0){
			$count++;
			$feedback.="<br/> Your passwords are not identical."; 
		}//end of confirm password validation
		
		//captcha validation
		if($capt=="" || $capt==null){
			$count++;
			$feedback.="<br/> You must complete the captcha to continue.";
		}else{
			valCaptcha($capt);
		}
		
	}//end of validation 
	

	/*
	PHP CAPTCHA Validation Code
	Code adapted from: https://www.phpcaptcha.org/
	@copyright 2013 Drew Phillips
	@author Drew Phillips <drew@drew-phillips.com>
	@version 3.5.1 (June 21, 2013)
	@package securimage
	This function is used to validate the captcha
	*/
	function valCaptcha($capt){
		global $feedback;
		global $count; 
		//create a captcha function
		include_once('securimage/securimage.php');
		
		$securimage = new Securimage();
		
		if ($securimage->check($capt) == false) {
			// the code was incorrect
			$count++;
			$feedback.= "<br/>The captcha you entered does not match the one provided by the system. Please try again.";
		  
		}
	}//end of valCaptcha

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
              <li><a href="about.html">About Us</a></li>
              <li><a href="team.html">Team</a></li>

              <li class="drop-down"><a href="#">Drop Down 2</a>
                <ul>
                  <li><a href="#">Deep Drop Down 1</a></li>
                  <li><a href="#">Deep Drop Down 2</a></li>
                  <li><a href="#">Deep Drop Down 3</a></li>
                  <li><a href="#">Deep Drop Down 4</a></li>
                  <li><a href="#">Deep Drop Down 5</a></li>
                </ul>
              </li>
            </ul>
          </li>

          <li><a href="services.html">Services</a></li>
          <li><a href="contact.html">Contact</a></li>
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
          <li><a href="registration.php">Registration</a></li>
        </ol>
        <h2>Registration</h2>

      </div>
	  
    </section><!-- End Breadcrumbs -->
	
    <!-- ======= Blog Section ======= -->
    <section id="form">
	
	<div class="container">

      <div class="row">

			<div class="col-sm-12">

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

				?>


		  <br/>

		  </div>

		  <!--Your Content Goes Here-->

		  <div class="col-sm-6">
		  
				<form onsubmit="return valReg(this)" class="form-horizontal" method="post" action="registration.php">
							
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
						
						<div class="form-group"> 
							<label class="control-label col-sm-2">Confirm Password:</label>
						 <div class="col-sm-10">

							<input type="password" class="form-control" name="cPass" id="cPass" aria-labelledby="confirm password"/> 
						 </div>
						 <span class="error" id="cpass_err"></span>
						</div>
						
						<!--Word CAPTCHA HTML-->
						<!--
						Code adapted from: https://www.phpcaptcha.org/
						@copyright 2013 Drew Phillips
						@author Drew Phillips <drew@drew-phillips.com>
						@version 3.5.1 (June 21, 2013)
						@package securimage
						-->
						<div>
							<label class="control-label">Word Captcha:</label>
						 <div class="col-sm-10">
							
							<img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" /> 
							<a href="#" onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false">[ Different Image ]</a> 
							
							<p> Type the characters seen in the field below:</p> 
							<input type="text" class="form-control" name="captcha_code" id="c_code" aria-labelledby="c_code"/>
							
						 </div>
						 <span id="captcha_err"></span>
						</div>
						<br/>
						<div class="col-sm-10">
						<input type="submit" class="btn btn-outline-primary form-control sincerely" name="submit" value="Register" onClick="return valReg();"/>
					</div>
				</form>

		 </div>

		

			<div class="col-sm-6 verticalLine loginpadding">

			

			<br>

			

			<p>For any Problems with registration or Technical Issues, </p>

			<p>Contact an Admin through our contact form. <a href="contact.html">Here.</a></p>
			
			<br>
			<br>
			<br>
			
			<img src="assets/img/Logo.PNG" class="img-fluid" alt="A picture of the logo for sincerely, me." width="400px" height="200px">

		

		</div>

		</div>

			<br/>

			<br/>

			<br/>

			<br/>

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
          <li><a href="#">About</a></li>
          <li><a href="services.html">Services</a></li>
          <li><a href="blog.html">Your Blog</a></li>
          <li><a href="contact.html">Contact</a></li>
          <li><a href="contact.html">Login</a></li>
          <li><a href="contact.html">Register</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-contact">
            <h4>Contact Us</h4>
            <p>
              A108 Adam Street <br>
              New York, NY 535022<br>
              United States <br><br>
              <strong>Phone:</strong> +1 5589 55488 55<br>
              <strong>Email:</strong> info@example.com<br>
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
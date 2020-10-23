<?php
ob_start();
	/*This page has all the content for the administrator panel*/

	//call to session checker to ensure that the user is an admin
	include('adminSessionChecker.php');
	require_once 'vendor/autoload.php';
	
	//these are error handling variables
	$count=0; //error count
	$feedback=""; //error messages
	$success=""; // success messages
	
	//get userID
	if(isset($_SESSION['uID'])){
		$uID=$_SESSION['uID'];
	}
	
	//This isset is used to capture the administrator's daily, public message
	if(isset($_POST['submit'])){
		$body=$_POST['body'];
		//create new date object for the insert into the database
		$date=date('Y-m-d H:i:s');
		
		//validate post data
		if($body=="" || $body==null){
			$count++;
			$feedback.="<br/> Your post cannot be empty.";
		}else if(strlen($body)<2){
			$count++;
			$feedback.="<br/> Your post cannot be less than 2 characters.";
		}else if($body=='{"ops":[{"insert":"\n"}]}'){
			$count++;
			$feedback.="<br/> Your post cannot be empty.";
		}		
		
		if($count==0){

			//include database connections
			include('dbConnect.php');
				
			//sanitize data
			//$result= filter_var($result, FILTER_SANITIZE_STRING); 
			
			//sanitize data going into MySQL
			$body= mysqli_real_escape_string($mysqli, $body);
			
			//if there are no errors then do the insert	
			if($count==0){	
				
				//make insert into database
				if($stmt=mysqli_prepare($mysqli,
				"INSERT INTO tblpmessage(pMessage, pMessageDate, userID) VALUES(?, ?, ?)")){
					//bind all the parameters
					mysqli_stmt_bind_param($stmt, "ssi", $body, $date, $uID);
					
					//execute query
					if(mysqli_stmt_execute($stmt)){
						//successful insert
						$success.="The daily post has been made and is now accessible on the homepage";
					}else{
						//unsuccessful insert
						$count++;
						$feedback.="<br/> A problem occured with setting up the daily message, please contact a technician for assistance.";
					}
					mysqli_stmt_close($stmt);
				}//end of stmt
				mysqli_close($mysqli);
			}//end of count
		}//end of count
	}//end of isset

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
  
   <!--ckeditor Link-->
   <script src="ckeditor/ckeditor.js"></script>

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
              <li><a href="privacyPolicy.php">Privacy Policy</a></li>
              <li><a href="termsAndConditions.php">Terms and Conditions</a></li>
            </ul>
          </li>

          <?php
		  //This is just some navigational elements that display different nav options based on the user position
			if(isset($_SESSION['uName'])){
				//if user is a blogger
				if(($_SESSION['position']==1)){
				$uName=$_SESSION['uName'];
				echo"
					<li><a href='createBlog.php'>Create Blogpost</a></li>
					<li><a href='messaging.php'>Messaging</a></li>
					<li><a href='userAccount.php'>$uName's Account</a></li>
					<li><a href='logout.php'>Logout</a></li>
					
				";
				
				}else if(($_SESSION['position']==2)){
					//if user is an admin
					$uName=$_SESSION['uName'];
					echo"
						<li><a href='createBlog.php'>Create Blogpost</a></li>
						<li><a href='adminPanel.php'>Administrator Panel</a></li>
						<li><a href='messaging.php'>Messaging</a></li>
						<li><a href='userAccount.php'>$uName's Account</a></li>
						<li><a href='logout.php'>Logout</a></li>
						
					";
				}
				
			}else{
				//if user is not logged in
				echo"
					
					<li><a href='login.php'>Login</a></li>
					<li><a href='registration.php'>Register</a></li>
				
				";
			}
		  
		  ?>


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
          <li><a href="adminPanel.php">Administrator Panel</a></li>
        </ol>
        <h2>Administrator Panel</h2>
		<h6>Welcome to the administrator panel, you can perform all of your administrative tasks here. </h6>
      </div>
	  
    </section><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="button menu">
      
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

		?>
	  
	  
		<div class="col-sm-12">
		
			<h3>Daily Message:</h3>
		
		</div>
	  <br/>
	  <!--This is the form that allows the admin to create the daily message-->
	  <form id="target" class="form-horizontal" action="adminPanel.php" method="post">
			
			<div class="form-group">
				<div class="col-sm-12">
					<small>You can use emotes when you're making your post, click on the right, next to the omega (Ω) symbol.</small>
					<!--This is needed for the rich text area-->
					<textarea id="editor" name="body"></textarea>
				</div>
			</div>
			
			<div class="col-sm-12">
				<input type="submit" class="btn btn-outline-primary form-control sincerely" name="submit" value="Submit"/>
			</div>
		</form>
		
		<br/>
		<hr/>
		
		<!-- Initialize Quill editor -->
		<script>
		//These are the options for the toolbar
		
		CKEDITOR.replace( 'editor' );
		
		//This is some neat little code to prevent a form resubmission if you reload the page
		/*
		Code Author: dtbaker
		Code Accessed On:23/06/2020
		Code Source: https://stackoverflow.com/questions/6320113/how-to-prevent-form-resubmission-when-page-is-refreshed-f5-ctrlr
		
		*/
		  if ( window.history.replaceState ) {
				window.history.replaceState( null, null, window.location.href );
			}
		  
		</script>
		<!--This is a set of buttons that facilitate the various options an admin has at their disposal-->
		<h3>General Tasks:</h3>
		<br/>
		<div class="row">
			<div class="col-sm-6">
			
				<a href='viewUserPosts.php'>
				<button class='btn btn-outline-primary form-control sincerely'>Confirm User Posts</button></a>
			
			</div>
			
			<div class="col-sm-6">
			
				<a href='adminReportMenu.php'>
				<button class='btn btn-outline-primary form-control sincerely'>View User Reports</button></a>
			
			</div>
		</div>
		<br/>
		<div class="row">
			<div class="col-sm-6">
			
				<a href='deleteBlogger.php'>
				<button class='btn btn-outline-primary form-control sincerely'>Delete User</button></a>
			
			</div>
		</div>
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
<?php 
/*This page is used to help facilitate the reporting functionality of the system, where a user can report either a message or a comment*/
session_start();

if($_SESSION['uName']==""){
		
	Header("Location:login.php?feedback=You must be logged in to access this page...");
	
}

//error handling variables
$feedback="";
$count="";
$success="";

//initialize variables for comment reporting
$commentContent="";
$commentDate="";
$commentUser="";
$commentPicture="";
$commentUserID="";

//initalize variables for message reporting
$mSender="";
$mSenderName="";
$mTitle="";
$mContent="";
$mDate="";

//variables to identify comments or messages
$commentID=0;
$messageID=0;

//create new date object for the insert into the database
$date=date('Y-m-d H:i:s');

//get userID to store into the table as well

if(isset($_SESSION['uName'])){
	$uName=$_SESSION['uName'];
	$uID=$_SESSION['uID'];
}

//get the commentID 
if(isset($_GET['commentID'])){
	$commentID=$_GET['commentID'];
}else if(isset($_GET['messageID'])){
	$messageID=$_GET['messageID'];
}

//this isset handles the reporting 
if(isset($_POST['reportComment'])){
	$reason=$_POST['reason'];
	$commentID=$_POST['commentID'];
	$rUser=$_POST['rUser'];
	
	//validate data
	
	if($reason==null || $reason==""){
		$count++;
		$feedback.="<br/> You must enter a reason to report this user.";
	}
	
	if($commentID=="" || $commentID==null){
		$count++;
		$feedback.="<br/> Comment ID not found, please try again later or contact an administrator for assistance.";
	}
	
	if($rUser=="" || $rUser==null){
		$count++;
		$feedback.="<br/> User ID not found, please try again later or contact an administrator for assistance.";
	}
	
	if($count==0){
		//sanitize data
		$reason= filter_var($reason, FILTER_SANITIZE_STRING);
		$commentID= filter_var($commentID, FILTER_SANITIZE_STRING);
		$rUser= filter_var($rUser, FILTER_SANITIZE_STRING);
		
		//include database connections
		include('dbConnect.php');
		
		//sanitize data going into MySQL
		$reason= mysqli_real_escape_string($mysqli, $reason);
		$commentID= mysqli_real_escape_string($mysqli, $commentID);
		$rUser= mysqli_real_escape_string($mysqli, $rUser);
		

		//insert into database
		if($stmt=mysqli_prepare($mysqli, 
		"INSERT INTO tblcommentreport(commentID, reason, reportedUser, reportedBy, reportedByID, reportDate) VALUES (?,?,?,?,?,?)")){
			mysqli_stmt_bind_param($stmt, 'isisis', $commentID, $reason, $rUser, $uName, $uID, $date);
			if(mysqli_stmt_execute($stmt)){
				$success="Report made successfully! The admins will attend to this matter as soon as possible! You will be returned to the homepage shortly.";
				header("refresh:2; url=index.php" );
			}else{
				$count++;
				$feedback.="<br/> Report could not be made, database error encountered. Please contact an administrator for assistance";
			}
		}//end of if-stmt		
	}//end of count
}//end of isset

//this isset handles the reporting done for a message
if(isset($_POST['reportMessage'])){
	//get variables
	global $uName;
	global $uID;
	
	//get information passed from post
	$mSender=$_POST['mSender'];
	$reason=$_POST['reason'];
	$messageID=$_POST['messageID'];
	
	//validate data
	if($mSender=="" || $mSender==null){
		$count++;
		$feedback.="<br/> User ID not found, please try again later or ask an administrator for assistance.";
	}
	
	if($reason==""||$reason==null){
		$count++;
		$feedback.="<br/> You must enter a reason to report this user.";
	}
	
	if($messageID==0||$messageID==null){
		$count++;
		$feedback.="<br/> Message ID not found, please try again later or ask an administrator for assistance.";
	}
	
	if($count==0){
		//sanitize and insert
		
		$reason= filter_var($reason, FILTER_SANITIZE_STRING);
		$messageID= filter_var($messageID, FILTER_SANITIZE_STRING);
		$mSender= filter_var($mSender, FILTER_SANITIZE_STRING);
		
		//include database connections
		include('dbConnect.php');
		
		//sanitize data going into MySQL
		$reason= mysqli_real_escape_string($mysqli, $reason);
		$messageID= mysqli_real_escape_string($mysqli, $messageID);
		$mSender= mysqli_real_escape_string($mysqli, $mSender);
		
		//insert into database
		if($stmt=mysqli_prepare($mysqli, 
		"INSERT INTO tblmessagereport(messageID, reason, reportedUser, reportedBy, reportedByID, reportDate) VALUES (?,?,?,?,?,?)")){
			mysqli_stmt_bind_param($stmt, 'isisis', $messageID, $reason, $mSender, $uName, $uID, $date);
			if(mysqli_stmt_execute($stmt)){
				$success="Report made successfully! The admins will attend to this matter as soon as possible! You will be returned to the homepage shortly.";
				header("refresh:2; url=index.php" );
			}else{
				$count++;
				$feedback.="<br/> Report could not be made, database error encountered. Please contact an administrator for assistance";
				printf("Error #%d: %s.\n", mysqli_stmt_errno($stmt), mysqli_stmt_error($stmt));
			}
		}//end of if-stmt		
	}//end of count
}//end of reportMessage isset

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
			  <li><a href="services.html">Services</a></li>
			  <li><a href="contact.html">Contact</a></li>

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
		  
            <?php
			if(isset($_SESSION['uName'])){
				if(($_SESSION['position']==1)){
				$uName=$_SESSION['uName'];
				echo"
					<li><a href='createBlog.php'>Create Blogpost</a></li>
					<li><a href='messaging.php'>Messaging</a></li>
					<li><a href='userAccount.php'>$uName's Account</a></li>
					<li><a href='logout.php'>Logout</a></li>
					
				";
				
				}else if(($_SESSION['position']==2)){
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
          <li><a href="reporting.php">Reporting</a></li>
        </ol>
        <h2>Reporting</h2>
		<h6>Here you can report a comment or message that you think violates the "Sincerely, Me" terms of service.</h6>

      </div>
	  
    </section><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="form">
      <div class='container'>
	  
		<div class='blog'>
	  
		  <?php
		  
			global $feedback;
			global $count; 
			global $success;
			
	         if($feedback != ""){
			echo"<div class='container'>";
		     echo "<div class= 'alert alert-danger'>"; 
		       if ($count == 1) echo "<strong>$count error found.</strong>";
			   if ($count > 1) echo "<strong>$count errors found.</strong>";
		     echo "$feedback
			   </div>";
			echo"</div>";
			}//end of error code
			
			//this is feedback code for success messages
			if($success != ""){
				echo"<div class='container'>";
				 echo "<div class= 'alert alert-success'>"; 
				 echo "$success
				   </div>";
				 echo"</div>";
			}//end of if statement for error
		  
			if($commentID!=0 || $commentID!=null){
				//initialize variables
				global $commentContent;
				global $commentDate;
				global $commentUser;
				global $commentPicture;
				global $commentUserID;
				
				include('dbConnect.php');
				
				if($stmt=mysqli_prepare($mysqli, 
				"SELECT tblblogcomments.content, tblblogcomments.postDate, tbluser.username, tbluser.pictureID, tbluser.userID
				FROM tblbloggercomments, tblblogcomments, tbluser
				WHERE tblblogcomments.commentID= tblbloggercomments.commentID
				AND tblbloggercomments.userID=tbluser.userID
				AND tblblogcomments.commentID=?")){
					mysqli_stmt_bind_param($stmt, 'i', $commentID);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_bind_result($stmt, $commentContent, $commentDate, $commentUser, $commentPicture, $commentUserID);
					if(mysqli_stmt_fetch($stmt)){
						echo"
						
						<h4>Reported Comment:</h4>
						
						<div class='blog-comments'>
							<div class='comment clearfix'>";
							if($commentPicture!=null){
								echo"
									<img src='accountAvatar/$commentPicture' class='comment-img  float-left'>
								";
							}else{
								 echo" <img src='accountAvatar/default.png' class='comment-image rounded-circle float-left'>";
								  echo"<p>Icon made by Freepik from www.flaticon.com</p>";
							}
							
							//this is a small check to ensure that if a user clicks on their own username, they're send to the userAccount page instead of the viewUserProfile page
							if(isset($_SESSION['uName'])){
								if($_SESSION['uName']==$commentUser){
										echo"<h5> <a href='#'>$commentUser</a></h5>";
									}else{
										echo"<h5> <a href='#'>$commentUser</a></h5>";
									}
							}else{
								echo"<h5><a href='#'>$commentUser</a></h5>";
							}
							echo"<time datetime='2020-01-01'>$commentDate</time>
							
							<div class='col-sm-10'>
							<p>$commentContent</p>
							</div>
							
					  </div>";
					 mysqli_stmt_close($stmt);
					}//end of stmt
					mysqli_close($mysqli);
				}//end of if-stmt
				 echo"
				 
				  <form class='form-horizontal' method='post' action='reporting.php' onsubmit='return valReport(this)'>
					<div class='col-sm-12'>
						<input type='hidden' name='commentID' value='$commentID'>
						
						<input type='hidden' name='rUser' value='$commentUserID'>
						
						<div>
							<br/>
							<div class='container'>
								<div class='col-sm-8'>
									<h4>Reasons:</h4>
									<input type='radio' id='violence' name='reason' value='Violence'>
									<label for='violence'>Violence</label><br>
									
									<input type='radio' id='bullying' name='reason' value='Bullying'>
									<label for='bullying'>Bullying</label><br>
									
									<input type='radio' id='harassment' name='reason' value='Harassment'>
									<label for='harassment'>Harassment</label><br>
									
									<input type='radio' id='suicide/self-injury' name='reason' value='Suicide/Self-Injury'>
									<label for='suicide/self-injury'>Suicide/Self-Injury</label><br>
									
									<input type='radio' id='spam' name='reason' value='Spam'>
									<label for='Spam'>Spam</label><br>
									
									<span class='error' id='reason_err'></span>
									<br/>
									<br/>
								</div>
								<input type='submit' class='btn btn-outline-primary form-control sincerely' name='reportComment' onClick='return valReport();'/>
							</div>
						</div>
					</div>
				  
				  </form>";
				  
			}else if($messageID!=0||$messageID!=null){
				//call variables
				global $mContent;
				global $mDate;
				global $mSender;
				global $mSenderName;
				global $mTitle;
				
				include('dbConnect.php');
			  
				if($stmt=mysqli_prepare($mysqli, 
				"SELECT tbluser.userID, tbluser.username, tblmessage.messageContent, tblmessage.messageDate, tblmessage.messageTitle
				FROM tbluser, tblmessage
                WHERE tbluser.userID=tblmessage.sender
                AND tblmessage.messageID=?")){
					mysqli_stmt_bind_param($stmt, 'i', $messageID);
					
					mysqli_stmt_execute($stmt);
					
					mysqli_stmt_bind_result($stmt, $mSender, $mSenderName, $mContent, $mDate, $mTitle);
					
					if(mysqli_stmt_fetch($stmt)){
						echo"
						
						
						<div class='card'>

							<div class='card-body'>
							  <h4 class='card-title'>Message Title: $mTitle</h4>
							  
							  <h4 class='card-title'>Message Sender: $mSenderName</h4>

							  <p class='card-text'>$mContent</p>

							  <p class='card-text'>Sent: $mDate</p>

							</div>
						 </div>
						 <br/>
						";
					mysqli_stmt_close($stmt);
					}//end of while
					 mysqli_close($mysqli);
				}//end of if-stmt
				echo"
				 
				  <form class='form-horizontal' method='post' action='reporting.php' onsubmit='return valReport(this)'>
					<div class='col-sm-12'>
						
						<input type='hidden' name='mSender' value='$mSender'>
						<input type='hidden' name='messageID' value='$messageID'>
						
						<div>
							<br/>
							<div class='container'>
								<div class='col-sm-8'>
									<h4>Reasons:</h4>
									<input type='radio' id='violence' name='reason' value='Violence'>
									<label for='violence'>Violence</label><br>
									
									<input type='radio' id='bullying' name='reason' value='Bullying'>
									<label for='bullying'>Bullying</label><br>
									
									<input type='radio' id='harassment' name='reason' value='Harassment'>
									<label for='harassment'>Harassment</label><br>
									
									<input type='radio' id='suicide/self-injury' name='reason' value='Suicide/Self-Injury'>
									<label for='suicide/self-injury'>Suicide/Self-Injury</label><br>
									
									<input type='radio' id='spam' name='reason' value='Spam'>
									<label for='Spam'>Spam</label><br>
									
									<span class='error' id='reason_err'></span>
									<br/>
									<br/>
								</div>
								<input type='submit' class='btn btn-outline-primary form-control sincerely' name='reportMessage' onClick='return valReport();'/>
							</div>
						</div>
					</div>
				  
				  </form>";
				
				
			}//end of if-else
		  ?>
		  <br/>
		  <div class="container">
		  <div class="col-sm-12">
		  <button class="btn btn-outline-primary form-control sincerely"><a href="index.php">Return Home</a></button>
		  </div>
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
          <li><a href="#">About</a></li>
          <li><a href="services.html">Services</a></li>
          <li><a href="blog.html">Your Blog</a></li>
          <li><a href="contact.html">Contact</a></li>
          <li><a href="login.php">Login</a></li>
          <li><a href="registration.php">Register</a></li>
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
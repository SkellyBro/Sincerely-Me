<?php

session_start();
ob_start();

//This is for PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once 'vendor/autoload.php';

//create error handling variables
$success="";
$feedback="";
$count=0;

if(isset($_POST['submit'])){
	//get variables
	$name=$_POST['name'];
	$email=$_POST['email'];
	$subject=$_POST['subject'];
	$message=$_POST['message'];
	
	//validate user information
	validate($name, $email, $subject, $message);
	
	if($count==0){
		//create email
		sendEmail($name, $email, $subject, $message);
	}//end of count
	
}//end of isset

function sendEmail($name, $email, $subject, $message){
	global $feedback;
	global $count;
	global $success;
	
	//this is for phpmailer
	$mail = new PHPMailer(true);
	
	/*fam look, this code works as is and IT ONLY WORKS WITH GMAIL. 
	If you need to edit it, look up the documentation for PHPMailer first, they have a github here: 
	https://github.com/PHPMailer/PHPMailer
	
	btw this goes without saying, but the code below ain't mine.
	*/
	try{
		//server settings
		$mail->isSMTP();
		$mail->SMTPOptions = array(
		'ssl' => array(
		'verify_peer' => false,
		'verify_peer_name' => false,
		'allow_self_signed' => true
		)
		);
		//$mail->SMTPDebug = SMTP::DEBUG_SERVER;
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = '587';
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mail->SMTPAuth = true;
		$mail->Username='ewsdgroup2018@gmail.com';//this is the sending email
		$mail->Password= 'EWSD2018';//well, password is password
		$mail->SetFrom($email);
		$mail->addReplyTo($email);
		$mail->AddAddress('theracoconsultants@gmail.com');
		//content
		$mail->isHTML();
		$mail->Subject=$subject;
		$mail->Body=  
			'New message received from '.$name.'('.$email.')
			<br/>
			'.$message.'<br/>
			Kind Regards, <br/>
			Email Bot (PHPMailer)';
		
		//recipient
		
		
		$mail->Send();
		$success.='Message sent!';
	} catch (Exception $e) {
		$count++;
		$feedback.= "<br/>Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}
	
}//end of function sendEmail

//This function takes the user information and validates it for presence, range and format.
/*
*$n is the user's name
*$e is the user's email
*$s is the subject of the message
*$m is the message
*/
function validate($n, $e, $s, $m){
	global $feedback;
	global $count;
	
	//name validation
	if($n=="" || $n==null){
		$count++;
		$feedback.="<br/>You must enter your name!";
	}else if(strlen($n)>100){
		$count++;
		$feedback.="<br/>Your name cannot be more than 100 characters!";
	}else if(!ctype_alnum($n)){
		$count++;
		$feedback.="<br/>Your name can only contain letters!";
	}
	
	//email validation
	if($e=="" || $e==null){
		$count++;
		$feedback.="<br/>Your email cannot be empty!";
	}else if(!filter_var($e, FILTER_SANITIZE_EMAIL)){
		$count++;
		$feedback.="<br/> Please enter a valid email.";
	}else if(!preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/',$e)){
		$count++;
		$feedback.="<br/> Your email must be in the format xyz@xyz.com";
	}

	//subject validation
	if($s=="" || $s==null){
		$count++;
		$feedback.="<br/>You message must have a subject";
	}else if(strlen($s)>100){
		$count++;
		$feedback.="<br/>Your subject cannot be more than 100 characters";
	}
	
	//message validation
	if($m=="" || $m==null){
		$count++;
		$feedback.="<br/>Your message cannot be empty";
	}
	
}//end of validate

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Sincerely, Me.</title>
  <meta content="Get in contact with our therapist, or our support team if you're in need of assistance!" name="description">
  <meta content="blogging, trinidad and tobago, online, therapy, therapist, group journal, online blog, online therapy blog, venting blog, online venting blog, trinidad, sincerely, me, sincerelyme, sincerelyme-tt, sincerely" name="keywords">

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
          <li><a href="contact.php">Contact Us</a></li>
        </ol>
        <h2>Contact Us</h2>
		<h6>Here's how you can get in contact with us!</h6>
      </div>
	  
    </section><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
     <section id="contact" class="contact">
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

        <div class="row">
          <div class="col-lg-6">
            <div class="info-box mb-4">
              <i class="bx bx-map iconColor"></i>
              <h3>Our Address</h3>
              <p>Gulf View Medical Centre <br/> 715-716 Mc Connie St</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="info-box  mb-4">
              <i class="bx bx-envelope iconColor"></i>
              <h3>Email Us</h3>
              <p>theracoconsultants@gmail.com</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="info-box  mb-4">
              <i class="bx bx-phone-call iconColor"></i>
              <h3>Call Us</h3>
              <p>868-283-HELP(4357) <br/>/ 868-798-4261</p>
            </div>
          </div>

        </div>

        <div class="row">

          <div class="col-lg-6 ">
			<iframe  class="mb-4 mb-lg-0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3926.0317296275102!2d-61.47013538538185!3d10.259024571335932!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8c358db523f12487%3A0xe33ef2ffda01932d!2sGulf%20View%20Medical%20Centre!5e0!3m2!1sen!2stt!4v1596671652720!5m2!1sen!2stt" frameborder="0" style="border:0; width: 100%; height: 384px;" allowfullscreen></iframe>
		 </div>

          <div class="col-lg-6 info-box">
			  <div class='padding'>
				<form class="form-horizontal" action="contact.php" method="post">
				
				<div class="form-row">
					<div class="col form-group">
					  <input type="text" name="name" class="form-control"  placeholder="Your Name"/>
					</div>
					<div class="col form-group">
					  <input type="email" class="form-control" name="email" placeholder="Your Email" />
					</div>
				</div>
				
				<div class="form-group">
					<input type="text" class="form-control" name="subject" placeholder="Subject"/>
				</div>
				<div class="form-group">
					<textarea class="form-control" name="message" rows="5" placeholder="Message"></textarea>
				</div>
				 <div class="text-center"><input type="submit" class="btn btn-outline-primary sincerely" name="submit" value="Send Message"/></div>
				</form>
			</div>
        </div>

      </div>
    </section><!-- End Contact Section -->

  </main><!-- End #main -->
  
  <script>
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
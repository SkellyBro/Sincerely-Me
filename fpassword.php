<?php
session_start();
ob_start();
//This is for PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once 'vendor/autoload.php';

$feedback="";
$success="";
$count=0;
$uID=0;

if(isset($_POST['submit'])){
    $uN=$_POST['uName'];
    $email=$_POST['email'];

   //username validation
    if($uN=="" || $uN==null){
        $count++;
        $feedback.="<br/>You must enter a username to continue.";
    }else if(!preg_match("/^[\w]+$/", $uN)){
        $count++;
        $feedback.="<br/>Your username cannot contain special characters.";
    }//end of username validation

    if($email=="" || $email==null){
        $count++;
        $feedback.="<br/>You must enter an email to continue.";
    }else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $count++;
        $feedback.="<br/>Your email is not in the correct format.";
    } 

    //if there are no errors then check the username and email against the database
    if($count==0){
        include('dbConnect.php');

        if($stmt=mysqli_prepare($mysqli, "SELECT tbluser.userID FROM tbluser WHERE tbluser.username=? AND tbluser.email=?"))
        {
            mysqli_stmt_bind_param($stmt, 'ss', $uN, $email);

            mysqli_stmt_execute($stmt);
            
            mysqli_stmt_bind_result($stmt, $uID);

            if(mysqli_stmt_fetch($stmt)){
                sendEmail($email, $uN, $uID);
            }else{
                $count++;
                $feedback.="<br/>Your username or email could not be found in the system.";
            }
        }
    }

}//end of post

function sendEmail($email, $uN, $uID){
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
    
    //remember to create a link that contains the user's ID so the reset can be done properly
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
		$mail->AddAddress($email);
		//content
		$mail->isHTML();
		$mail->Subject="Password Reset";
		$mail->Body=  
			'Hello '.$uN.', ' .$uID.'
			<br/>
			Please click on the link below to reset your password: <br/>
			Kind Regards, <br/>
			Email Bot (PHPMailer)';
		
		//recipient
		
		
		$mail->Send();
		$success.='Password reset email sent! Please check your inbox.';
	} catch (Exception $e) {
		$count++;
		$feedback.= "<br/>Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}
	
}//end of function sendEmail

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
          <li><a href="login.php">Login</a></li>
          <li><a href="fpassword.php">Forgot Password</a></li>
        </ol>
        <h2>Forgot Password</h2>
        <h5>Please follow the instructions below to reset your password.</h5>
      </div>
	  
    </section><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="form">
      <div class='container'>

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

	  <!--Content Here :0-->
	  <form class='horizontal' method='post' action='fpassword.php' onsubmit="return valUName(this)">
      
        <div class="form-group"> 
            <label class="control-label col-sm-4">Please Enter your Username:</label>
            <div class="col-sm-12">

            <input type="text" class="form-control" name="uName" id="uName" aria-labelledby="username"/> 
            </div>
            <span class="error" id="user_err"></span>
        </div>

        <div class="form-group"> 
            <label class="control-label col-sm-4">Please Enter your Email:</label>
            <div class="col-sm-12">

            <input type="email" class="form-control" name="email" id="email" aria-labelledby="username"/> 
            </div>
            <span class="error" id="email_err"></span>
        </div>

        <div class="col-sm-12">
            <input type="submit" class="btn btn-outline-primary form-control sincerely" name="submit" value="Submit" onClick="return valUName();"/>
        </div>

      </form>
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
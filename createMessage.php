<?php
ob_start();
//this is for the quill editor thing

//This is for PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//this is a session checker to ensure that the user is logged in, as this page could be used by both an administrator and blogger
session_start();
if($_SESSION['uName']==""){
	Header("Location:login.php?feedback=You must be logged in to access this page...");
}

//load stuff
require_once 'vendor/autoload.php';
	
	//init variables
	$count=0;
	$feedback="";
	$success="";
	$recipientID=0;
	$recPos=0; //this is the position of the recipient
	$recEmail="";//this is the recipient's email
	
	//If session isset, store all the user's data as it would be needed for the messaging
	if(isset($_SESSION['uID'])){
		$uID=$_SESSION['uID'];
		$uName=$_SESSION['uName'];
		$pos=$_SESSION['position'];
		$_SESSION['messageContent']="";
		$_SESSION['messageTitle']="";
	}
	
	//get the message sent by the user
	if(isset($_POST['submit'])){
		//get variables
		$body=$_POST['body'];
		$recipient=$_POST['recipient'];
		$title=$_POST['title'];
		
		//create new date object for the insert into the database
		$date=date('Y-m-d H:i:s');
		
		$dt = new DateTime("now", new DateTimeZone('America/Guyana'));
		
		$date = $dt->format('Y-m-d H:i:s');
		
		$rand=rand(0,1000);
		
		checkRand($rand);
		
		//validate quill_json data
		if($body=="" || $body==null){
			$count++;
			$feedback.="<br/> Your post cannot be empty.";
		}else if($body=='{"ops":[{"insert":"\n"}]}'){
			$count++;
			$feedback.="<br/> Your post cannot be empty.";
		}else if(strlen($body)>2500){
			$count++;
			$feedback.="<br/> Your post cannot be more than 2500 characters.";
		}

		//recipient validation
		if($recipient=="" || $recipient==null){
			$count++;
			$feedback.="<br/> You must enter the recipient's name for your message!";
		}else if(!ctype_alnum($recipient)){
			$count++;
			$feedback.="<br/> Your recipient name can only be alphanumeric.";
		}else if($recipient==$uName){
			$count++;
			$feedback.="<br/> You cannot message yourself!";
		}
		
		//title validation
		if($title==""||$title==null){
			$count++;
			$feedback.="<br/> Your title cannot be empty!";
		}else if(!preg_match("/\\w|\\s+/", $title)){
			$count++;
			$feedback.="<br/> No special characters allowed in your title!";
		}else if(strlen($title)>200){
			$count++;
			$feedback.="<br/> Your title cannot be more than 200 characters.";
		}
		
		if($count==0){

			//include database connections
			include('dbConnect.php');
				
			//sanitize data
			$title= filter_var($title, FILTER_SANITIZE_STRING); 
			$recipient= filter_var($recipient, FILTER_SANITIZE_STRING); 
			
			//sanitize data going into MySQL
			$body= mysqli_real_escape_string($mysqli, $body);
			$title= mysqli_real_escape_string($mysqli, $title);
			$recipient= mysqli_real_escape_string($mysqli, $recipient);
			
			
			//put the content and title into the session to repopulate fields if the recipient's username is lost.
			if(isset($_SESSION['uName'])){
				$_SESSION['messageContent']=$body;
				$_SESSION['messageTitle']=$title;
			}
				
			if($count==0){	
				//this is a check to see if the entered recipient is found in the system, and if they are found then the insert query would be run
				if(getRecipient($recipient)){
					
					include('dbConnect.php');
					
					//create insert statement to store the message in the system database
					if($stmt=mysqli_prepare($mysqli, 
					"INSERT INTO tblmessage(conversationID, originalSender, originalRecipient, sender, recipient, messageDate, messageTitle, messageContent, userPosition) VALUES(?,?,?,?,?,?,?,?,?)")){
						mysqli_stmt_bind_param($stmt, 'iiiiisssi', $rand, $uID, $recipientID, $uID, $recipientID, $date, $title, $body, $pos);
						
						if(mysqli_stmt_execute($stmt)){
							//so, as part of the functionality for the system, if an admin receives a message, an email copy is sent to them
							//recPos=1 is a blogger, if the user is a blogger no email is sent
							if($recPos==1){
								$success="Message Sent!";
								
								//$recPos=2 is an admin, if the user is an admin then the sendEmail function is run to send the admin a copy of the message via email
							}else if($recPos==2){
								//send email to admin
								sendEmail($uID, $date, $title, $body, $recEmail, $recipient);
							}
							
						}else{
							$count++;
							$feedback.="<br/> An error occured with sending your message, try again later or contact an Administrator for assistance";
						}
						mysqli_stmt_close($stmt);
					}//end of stmt
					mysqli_close($mysqli);
					
				}else{
					$count++;
					$feedback.="<br/> Could not send message, user not found.";
				}
				
			}//end of count
		}//end of count
	}//end of isset
	
	function checkRand($rand){
		include('dbConnect.php');
		
		if($stmt=mysqli_prepare($mysqli, "SELECT conversationID FROM tblmessage WHERE conversationID=?")){
			mysqli_stmt_bind_param($stmt, 'i', $rand);
			
			if(mysqli_stmt_execute($stmt)){
				$rand=rand(0,1000);
				return $rand;
			}
		}
	}

	/*This is a function used to get information of the recipient
	$rec is the recipient username
	*/
	function getRecipient($rec){
		global $recipientID;
		global $recPos;
		global $recEmail;
		
		include('dbConnect.php');
		if($stmt=mysqli_prepare($mysqli, 
		"SELECT userID, position, email FROM tbluser WHERE tbluser.username=?")){
			mysqli_stmt_bind_param($stmt, 's', $rec);
			
			mysqli_stmt_execute($stmt);
			
			mysqli_stmt_bind_result($stmt, $recipientID, $recPos);
			
			if(mysqli_stmt_fetch($stmt)){
				//user found!
				return true;
			}else{
				//what user lmao
				return false;
			}
		}//end of stmt
	}//end of getUserID
	
	/*This function is used to send an email to the administrators if they receive a message
	* $uID is the sender's ID 
	* $date is the date the message was sent
	* $title is the title of the message
	* $message is the message to be sent (this was called $body in the isset)
	* $recEmail is the email of the recipient of this email
	* $recipient is the name of the person this email is going to
	*/
	function sendEmail($uID, $date, $title, $message, $recipient){
		global $feedback;
		global $count;
		global $success;
		
		
		//get the user's username
		if(isset($_SESSION['uName'])){
			$uName=$_SESSION['uName'];
		}
		
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
			$mail->SetFrom('no-reply@Sincerely.com');
			$mail->AddAddress('theracoconsultants@gmail.com');
			//content
			$mail->isHTML();
			$mail->Subject='New Message from '.$uName;
			$mail->Body= 'Hello '.$recipient.', 
				Please be advised that you have received a new message from '.$uName.':
				<br/>
				<b>Title:</b> ' .$title.
				'<br/> 
				<b>Message:</b> '.$message.
				'</br>
				<b>Date Sent:</b> ' .$date.
				'<br/>
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
  
  <!--Quill Link-->
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
		  <li><a href="messaging.php">Messaging Menu</a></li>
		  <li><a href="createMessage.php">Create Message</a></li>
        </ol>
        <h2>Create Message</h2>
		
		<h6>You can compose your message here!</h6>
		
      </div>
	  
    </section><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="form">
      
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
	  
	  
		<form id="target" class="form-horizontal" action="createMessage.php" method="post">
		
			<div class="form-group"> 
				<label class="control-label col-sm-2">Recipient Username:</label>
			 <div class="col-sm-12">

				<input type="text" class="form-control" name="recipient" id="recipient" aria-labelledby="recipient" <?php if(isset($_GET['user'])){$user=$_GET['user']; echo"value='$user'";}?>/> 
			 </div>
			 <span class="error" id="user_err"></span>
			</div>
			
			<div class="form-group"> 
				<label class="control-label col-sm-2">Message Title:</label>
			 <div class="col-sm-12">

				<input type="text" class="form-control" name="title" id="title" aria-labelledby="title" value="<?php if(isset($_SESSION['messageTitle'])){echo $_SESSION['messageTitle'];}?>";/> 
			 </div>
			 <span class="error" id="user_err"></span>
			</div>
			
			<div class="form-group">
				<label class="control-label col-sm-2">Message Content:</label>
				<div class="col-sm-12">
				<small>You can use emotes when you're making your post, click on the right, next to the omega (Ω) symbol.</small>
				<!--This is needed for the rich text area-->
					<textarea id="editor" name="body"></textarea>
				</div>
			</div>
			
			<div class="col-sm-12">
				<input type="submit" class="btn btn-outline-primary form-control sincerely" name="submit" value="Submit"/>
			</div>
			
			<br/>
			
			<div class="col-sm-12">
				<button class='btn btn-outline-primary form-control sincerely'><a href='messaging.php'>Return to Messaging Menu</a></button>
			</div>
		</form>
	  
	  </div>
	  
	  <?php if(isset($_SESSION['messageContent'])){
		  $content=$_SESSION['messageContent'];
		}?>
	  
	  <script>
		var x = "<?php echo $content ?>";
		
		CKEDITOR.replace( 'editor' );
			
		//this sets the text inside of the editor
		CKEDITOR.instances['editor'].setData(x);
		
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
<?php
/*This page handles the message replies*/
//this is for quill.js
namespace DBlackborough\Quill;

//This is for PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//custom session checker to ensure that the user is logged in 
session_start();
if($_SESSION['uName']==""){
	Header("Location:login.php?feedback=You must be logged in to access this page...");
}

require_once 'vendor/autoload.php';

/*A lot of the code on this page was recycled from createMessage.php, you can go there to view a more in-depth documentation for the page.*/

	//error handling variables	
	$count=0;
	$feedback="";
	$success="";
	$recPos=0;
	$recEmail="";
	$recName="";
		
	//get message ID and store it in the session in case
	if(isset($_GET['cID'])){
		$cID=$_GET['cID'];
		$mTitle=$_GET['title'];
		$rID=$_GET['rID'];
		$oSender=$_GET['oSender'];
		$oRecipient=$_GET['oRecipient'];
		
		//store in session
		$_SESSION['cID']=$cID;
		$_SESSION['title']=$mTitle;
		$_SESSION['rID']=$rID;
		$_SESSION['oSender']=$oSender;
		$_SESSION['oRecipient']=$oRecipient;
	}else if(isset($_SESSION['cID'])){
		$cID=$_SESSION['cID'];
		$mTitle=$_SESSION['title'];
		$rID=$_SESSION['rID'];
		$oSender=$_SESSION['oSender'];
		$oRecipient=$_SESSION['oRecipient'];
	}
	
	//get userID
	if(isset($_SESSION['uID'])){
		$uID=$_SESSION['uID'];
		$pos=$_SESSION['position'];
	}
	
	//create new reply to message
	if(isset($_POST['submit'])){
		global $cID;
		global $rID;
		global $oSender;
		global $mTitle;
		global $feedback;
		global $count;
		global $success;
		
		$quill_json=$_POST['quill_json'];
		
		//validate
		//create new date object for the insert into the database
		$date=date('Y-m-d H:i:s');
		
		//validate post data
		if($quill_json=="" || $quill_json==null){
			$count++;
			$feedback.="<br/> Your post cannot be empty.";
		}else if($quill_json=='{"ops":[{"insert":"\n"}]}'){
			$count++;
			$feedback.="<br/> Your post cannot be empty.";
		}else if(strlen($quill_json)>2500){
			$count++;
			$feedback.="<br/> Your post cannot be more than 2500 words.";
		}
		
		if($count==0){
			//magic code that renders the quill delta into readable text
			try {
				$quill = new \DBlackborough\Quill\Render($quill_json, 'HTML');
				$result = $quill->render();
			} catch (\Exception $e) {
				echo $e->getMessage();
			}

			//revalidate date 
			if($result=="" || $result==null){
				$count++;
				$feedback.="<br/> Your post cannot be empty.";
			}
			
			//include database connections
			include('dbConnect.php');
			
			//sanitize data going into MySQL
			$result= mysqli_real_escape_string($mysqli, $result);
			
			if($count==0){

				if(getRecipient($rID)){
				
					include('dbConnect.php');
					
					if($stmt=mysqli_prepare($mysqli, 
					"INSERT INTO tblmessage(conversationID, originalSender, originalRecipient, sender, recipient, messageDate, messageTitle, messageContent, userPosition) VALUES(?,?,?,?,?,?,?,?,?)")){
						mysqli_stmt_bind_param($stmt, 'iiiiisssi', $cID, $oSender, $oRecipient, $uID, $rID, $date, $mTitle, $result, $pos);
						
						if(mysqli_stmt_execute($stmt)){
							if($recPos==1){
								$success="Message Sent!";
							}else if($recPos==2){
								//send email to admin
								sendEmail($uID, $date, $mTitle, $result, $recEmail, $recName);
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

	}//end of submit isset
	
	/*This is a function used to get details of the recipient for emailing purposes
	*$rID is the ID of the recipient
	*/
	function getRecipient($rID){
		global $recPos;
		global $recEmail;
		global $recName;
		
		include('dbConnect.php');
		if($stmt=mysqli_prepare($mysqli, 
		"SELECT username, position, email FROM tbluser WHERE tbluser.userID=?")){
			mysqli_stmt_bind_param($stmt, 'i', $rID);
			
			mysqli_stmt_execute($stmt);
			
			mysqli_stmt_bind_result($stmt, $recName, $recPos, $recEmail);
			
			if(mysqli_stmt_fetch($stmt)){
				return true;
			}else{
				return false;
			}
		}//end of stmt
	}//end of getUserID
	
	/*This function is used to send an email to the administrators if they receive a message
	* $uID is the sender's ID 
	* $date is the date the message was sent
	* $title is the title of the message
	* $message is the message to be sent (this was called $result in the isset)
	* $recEmail is the email of the recipient of this email
	* $recipient is the name of the person this email is going to
	*/
	function sendEmail($uID, $date, $title, $message, $recEmail, $recipient){
		global $feedback;
		global $count;
		global $success;
		
		
		//get the user's username
		if(isset($_SESSION['uName'])){
			$uName=$_SESSION['uName'];
		}
		
		//this is for phpmailer
		$mail = new PHPMailer(true);
		
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
			$mail->Username='ewsdgroup2018@gmail.com';
			$mail->Password= 'EWSD2018';
			$mail->SetFrom('no-reply@Sincerely.com');
			$mail->AddAddress($recEmail);
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
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
   <link rel="stylesheet" type="text/css" href="node_module/quill-emoji/dist/quill-emoji.css">
  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
  <script type="text/javascript" src="node_modules/quill-emoji/dist/quill-emoji.js"></script>

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
          <li><a href="messaging.php">Messaging Menu</a></li>
          <li><a href="messageReply.php">Message Replies</a></li>
        </ol>
        <h2>Message Reply</h2>
		
		<h6>You can reply to your messages here!</h6>
		
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
			  
			  
			  //variable declaration
			  $username="";
			  $mContent="";
			  $mDate="";
			  $mID=0;
			  $mUserPosition=0;
			  
				global $mTitle;
				echo"<h4>$mTitle</h4>
				<hr/>
				<div class='scroll col-sm-12'>";
			  
				include('dbConnect.php');
			  
				if($stmt=mysqli_prepare($mysqli, 
				"SELECT tbluser.username, tblmessage.messageContent, tblmessage.messageDate, tblmessage.messageID, tblmessage.userPosition
				FROM tbluser, tblmessage
				WHERE tblmessage.sender=tbluser.userID
				AND tblmessage.conversationID=?
				ORDER BY tblmessage.messageDate ASC")){
					mysqli_stmt_bind_param($stmt, 'i', $cID);
					
					mysqli_stmt_execute($stmt);
					
					mysqli_stmt_bind_result($stmt, $username, $mContent, $mDate, $mID, $mUserPosition);
					
					while(mysqli_stmt_fetch($stmt)){
						echo"
						
						
						<div class='card'>

							<div class='card-body'>
							  <h4 class='card-title'>$username</h4>

							  <p class='card-text'>$mContent</p>

							  <p class='card-text'>Sent: $mDate</p>

							</div>";
							
							if($_SESSION['uName']!=$username && $mUserPosition!=2){
								echo"
								<div class='col-sm-2'>
								<a href='reporting.php?messageID=$mID'><sub>Report Message</sub></a>
								<br/>
								<br/>
								</div>";
							}
							
						echo"
						 </div>
						 <br/>
						";
					}//end of while
					 mysqli_stmt_close($stmt);
					 mysqli_close($mysqli);
				}//end of if-stmt
			  
			  
			  ?>
		</div>
		<br/>
		<hr/>
			<form id="target" class="form-horizontal" action="messageReply.php" method="post">
				
				<div class="form-group">
					<div class="col-sm-12">
						<div id="editor">
							
						</div>
						<!--This is needed to store the information that the user enters into the rich text area.-->
						<input type="hidden" id="quill_json" name="quill_json" aria-labelledby="blog writing area"/>
						<input type="hidden" id="recipient" name="recipient" value="<?php global $rID; echo $rID; ?>"/>
					</div>
				</div>
				
				<div class="col-sm-12">
					<input type="submit" class="btn btn-outline-primary form-control sincerely" name="submit" value="Submit"/>
				</div>
			</form>
	  </div>
    </section><!-- End Blog Section -->

  </main><!-- End #main -->
  
  <!-- Initialize Quill editor -->
		<script>
		//These are the options for the toolbar
			var toolbarOptions = [
			  ['bold', 'italic', 'underline', 'strike'],        // toggled buttons

			  [{ 'header': 1 }, { 'header': 2 }],               // custom button values
			  [{ 'list': 'ordered'}, { 'list': 'bullet' }],
			  [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript                

			  [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

			  ['clean']                                         // remove formatting button
			];
		
			//this creates the actual rich text area
			var quill = new Quill('#editor', {
			  modules: {
				toolbar: toolbarOptions
			  },
			  theme: 'snow'
			});	
			
		  //this gets the data from the rich text area
		  $('#target').submit(function() {
			$('#quill_json').val(JSON.stringify(quill.getContents()));
			return true;
		});
		
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
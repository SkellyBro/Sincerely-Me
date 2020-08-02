<?php
/*This is the messaging menu for the website*/
//session checker stuff
session_start();
if($_SESSION['uName']==""){
	Header("Location:login.php?feedback=You must be logged in to access this page...");
}

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
          <li><a href="messaging.php">Messaging Menu</a></li>
        </ol>
        <h2>Messaging Menu</h2>

      </div>
	  
    </section><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="form">
      
	  
	  <div class="container">
	  
	  
		<div class="row">
		<div class="col-sm-4"><h4>Compose Message:</h4></div>
		<div class="col-sm-8"><button class='btn btn-outline-primary form-control sincerely'><a href='createMessage.php'>Compose</a></button></div>
		</div>
		
		<br/>
		<br/>
		<br/>
	  <?php
		
		//variable declaration
		$cID=0;
		$rID=0;
		$oSender=0;
		$oRecipient=0;
		$username="";
		$mTitle="";
		$mContent="";
		$mDate="";
		
		//get the user ID
		if(isset($_SESSION['uID'])){
			$uID=$_SESSION['uID'];
		}
		
		echo"<h4>Conversations You've Started:</h4>";
		
		//get all messages sent to the user
		include('dbConnect.php');
		
		//This gets a list of all conversations the user has started with others
		if($stmt=mysqli_prepare($mysqli, 
		"SELECT tblmessage.conversationID, tbluser.userID, tblmessage.originalSender, tblmessage.originalRecipient, tbluser.username, tblmessage.messageTitle, tblmessage.messageContent, tblmessage.messageDate
		FROM tbluser, tblmessage
		WHERE tblmessage.originalRecipient=tbluser.userID
		AND tblmessage.originalSender=?
		GROUP BY tblmessage.conversationID
		ORDER BY tblmessage.messageDate DESC")){
			mysqli_stmt_bind_param($stmt, 'i', $uID);
			
			mysqli_stmt_execute($stmt);
			
			mysqli_stmt_bind_result($stmt, $cID, $rID, $oSender, $oRecipient, $username, $mTitle, $mContent, $mDate);
			
			echo"
			<table class='table-hover table'>
				<thead>
					<tr>
						<th><h5>Recipient</h5></th>
						<th><h5>Title</h5></th>
						<th><h5>Body Preview</h5></th>
						<th><h5>Date Sent</h5></th>
					</tr>
				</thead>
			<tbody>
			";
			
			while(mysqli_stmt_fetch($stmt)){
				$preview=substr($mContent,0,100);
				echo"
				
					
					<tr>	
						<td>$username</td>
						<td>$mTitle</td>
						<td>$preview...</td>
						<td>$mDate</td>
						<td><a href='messageReply.php?cID=$cID&title=$mTitle&rID=$rID&oSender=$oSender&oRecipient=$oRecipient'>Reply</a></td>
					</tr>
				
				
				";
			}//end of while
			
			echo"</tbody></table>";		
			mysqli_stmt_close($stmt);
		}//end of stmt
		mysqli_close($mysqli);
	  
	  
	  echo"
	  
	  <br/>
	  <br/>
	  <h4>Conversations With Others:</h4>";
		
		//get all messages sent to the user
		include('dbConnect.php');
		//this gets a list of all conversations others have started with the user
		if($stmt=mysqli_prepare($mysqli, 
		"SELECT tblmessage.conversationID, tbluser.userID, tblmessage.originalSender, tblmessage.originalRecipient, tbluser.username, tblmessage.messageTitle, tblmessage.messageContent, tblmessage.messageDate
		FROM tbluser, tblmessage
		WHERE tblmessage.originalSender=tbluser.userID
		AND tblmessage.originalRecipient=?
		GROUP BY tblmessage.conversationID
		ORDER BY tblmessage.messageDate DESC")){
			mysqli_stmt_bind_param($stmt, 'i', $uID);
			
			mysqli_stmt_execute($stmt);
			
			mysqli_stmt_bind_result($stmt, $cID, $rID, $oSender, $oRecipient, $username, $mTitle, $mContent, $mDate);
			
			echo"
			<table class='table-hover table'>
				<thead>
					<tr>
						<th><h5>Sender</h5></th>
						<th><h5>Title</h5></th>
						<th><h5>Body Preview</h5></th>
						<th><h5>Date Sent</h5></th>
					</tr>
				</thead>
			<tbody>
			";
			
			while(mysqli_stmt_fetch($stmt)){
				$preview=substr($mContent,0,100);
				echo"
				
					
					<tr>	
						<td>$username</td>
						<td>$mTitle</td>
						<td>$preview...</td>
						<td>$mDate</td>
						<td><a href='messageReply.php?cID=$cID&title=$mTitle&rID=$rID&oSender=$oSender&oRecipient=$oRecipient'>Reply</a></td>
					</tr>
				
				
				";
			}//end of while
			
			echo"</tbody></table>";		
			mysqli_stmt_close($stmt);
		}//end of stmt
		mysqli_close($mysqli);
	  
	 
	  ?>
	  
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
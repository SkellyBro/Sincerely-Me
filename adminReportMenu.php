<?php
ob_start();
/*This page handles the user reports sent to the administrators*/
//call to the session checker for the admins
require('adminSessionChecker.php');

//error handling variables
$feedback=""; //error messages
$count=0;// error count
$success=""; // success messages

//this isset handles deleting a reported comment
if(isset($_POST['deleteComment'])){
	$cID=$_POST['cID'];
	
	//minor validation
	if($cID==0 || $cID==null){
		$count++;
		$feedback.="<br/> ID could not be found for the reported comment.";
	}
	
	if($count==0){
		include('dbConnect.php');
		
		if($stmt=mysqli_prepare($mysqli, "DELETE FROM tblbloggercomments WHERE commentID=?")){
		mysqli_stmt_bind_param($stmt, 'i', $cID);
			
			if(!mysqli_stmt_execute($stmt)){
				$count++;
				$feedback.="<br/> An error occured with deleting the user's comment. Please contact a technician for assistance.";
				printf("Error #%d: %s.\n", mysqli_stmt_errno($stmt), mysqli_stmt_error($stmt));
			}
			mysqli_stmt_close($stmt);
		}//end of stmt
		
		
		if($stmt2=mysqli_prepare($mysqli, "DELETE FROM tblcommentreport WHERE commentID=?")){
		mysqli_stmt_bind_param($stmt2, 'i', $cID);
				
			if(!mysqli_stmt_execute($stmt2)){
				$count++;
				$feedback.="<br/> An error occured with deleting the user's report. Please contact a technician for assistance.";
				printf("Error #%d: %s.\n", mysqli_stmt_errno($stmt2), mysqli_stmt_error($stmt2));
			}
		mysqli_stmt_close($stmt2);
		}//end of stmt
		
		if($stmt3=mysqli_prepare($mysqli, "DELETE FROM tblblogcomments WHERE commentID=?")){
		mysqli_stmt_bind_param($stmt3, 'i', $cID);
		
			if(mysqli_stmt_execute($stmt3)){
			
				$success="User comment deleted successfully!";
				
			}else{
					
				$count++;
				$feedback.="<br/> An error occured with deleting the user's comment. Please contact a technician for assistance.";
				printf("Error #%d: %s.\n", mysqli_stmt_errno($stmt3), mysqli_stmt_error($stmt3));
			}
		mysqli_stmt_close($stmt3);
		}//end of if-stmt

	}//end of count
	
}//end of deleteComment isset

//this isset handles dismissing a report
if(isset($_POST['dismiss'])){
	$rID=$_POST['rID'];
	$dType=$_POST['dismissType'];
	
	//minor validation
	if($rID==0 || $rID==null){
		$count++;
		$feedback.="<br/> ID could not be found for the dismissed report.";
	}
	
	if($count==0){
		
		if($dType=="comment"){
			//make connection to database
			include('dbConnect.php');
			
			if($stmt=mysqli_prepare($mysqli, 
			"DELETE FROM tblcommentreport WHERE reportID=?")){
				mysqli_stmt_bind_param($stmt, 'i', $rID);
				
					if(mysqli_stmt_execute($stmt)){
						$success="User report dismissed successfully!";
					}else{
						$count++;
						$feedback.="<br/> An error occured with deleting the user's report. Please contact a technician for assistance.";
					}
				mysqli_stmt_close($stmt);
			}//end of stmt
			mysqli_close($mysqli);
		
		}else if($dType=="message"){
			//make connection to database
			include('dbConnect.php');
			
			if($stmt=mysqli_prepare($mysqli, 
			"DELETE FROM tblmessagereport WHERE reportID=?")){
				mysqli_stmt_bind_param($stmt, 'i', $rID);
				
					if(mysqli_stmt_execute($stmt)){
						$success="User report dismissed successfully!";
					}else{
						$count++;
						$feedback.="<br/> An error occured with deleting the user's report. Please contact a technician for assistance.";
					}
				mysqli_stmt_close($stmt);
			}//end of stmt
			mysqli_close($mysqli);
		}//end of if-else
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
          <li><a href="adminPanel.php">Admin Panel</a></li>
          <li><a href="adminReportMenu.php">User Reports</a></li>
        </ol>
        <h2>Report Menu</h2>
		<h6>Here are all the reports made from users.</h6>      
		</div>
	  
    </section><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="form">
      
	  <!--Content Here :0-->
	  <div class="container">
	  
		<?php
			//this is the user feedback code
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
			
			//this is to capture the feedback received from the DeleteUser.php script
			if(isset($_GET['feedback'])){
				$feedback=$_GET['feedback'];
				$success=$_GET['success'];
				$count=$_GET['count'];
				
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
			
			
			//get the page the user is on
			$page=$_SERVER['REQUEST_URI'];
			
			//variable declaration displaying all the reported comments
			$reportedBy="";
			$reason="";
			$content="";
			$reportDate="";
			$reportedUser="";
			$reportedUserID="";
			$reportID=0;
			$commentID=0;
			
			//call to the database connection script
			include('dbConnect.php');
		
			//display all the comment reports first
			if($stmt=mysqli_prepare($mysqli, 
			"SELECT tblcommentreport.reportID, tbluser.username, tblcommentreport.reportedUser, tblcommentreport.reason, tblblogcomments.content, tblcommentreport.reportDate, tblcommentreport.reportedBy, tblcommentreport.commentID
			FROM tblcommentreport, tblblogcomments, tbluser
			WHERE tblcommentreport.commentID= tblblogcomments.commentID
			AND tblcommentreport.reportedUser= tbluser.userID")){
				mysqli_stmt_execute($stmt);
				
				mysqli_stmt_bind_result($stmt, $reportID, $reportedUser, $reportedUserID, $reason, $content, $reportDate, $reportedBy, $commentID);
				//this sets up the table header
				echo"
					<h4>Reported Comments:</h4>
					<table class='table-hover table'>
						<thead>
							<tr>
								<th><h5>Reported User</h5></th>
								<th><h5>Reason for Report</h5></th>
								<th><h5>Comment Content</h5></th>
								<th><h5>Date of Report</h5></th>
								<th><h5>Report Filed By</h5></th>
							</tr>
						</thead>
					<tbody>
					";
				//the table body is echoed out of here
				while(mysqli_stmt_fetch($stmt)){
					echo"
					
					<tr>	
						<td>$reportedUser</td>
						<td>$reason</td>
						<td>$content</td>
						<td>$reportDate</td>
						<td>$reportedBy</td>
						<td>
							<form method='post' action='adminReportMenu.php'>
							<input type='hidden' value='$reportID' name='rID'/>
							<input type='hidden' value='comment' name='dismissType'/>
							<input type='submit' class='btn btn-outline-primary form-control sincerely' name='dismiss' value='Dismiss Report'/>
							</form>
						<br/>
							<form method='post' action='adminReportMenu.php' onsubmit='return commentConfirmation(this)'>
							<input type='hidden' value='$commentID' name='cID'/>
							<input type='submit' class='btn btn-outline-primary form-control sincerely' name='deleteComment' value='Delete Comment'/>
							</form>
						<br/>
							<form method='post' action='deleteUser.php' onsubmit='return adminConfirmation(this)'>
							<input type='hidden' value='$reportedUserID' name='userID'/>
							<input type='hidden' value='$page' name='page'/>
							<input type='submit' class='btn btn-outline-primary form-control sincerely' name='deleteUser' value='Delete Reported User'/>
							</form>
						</td>
					</tr>
					
					";
				}//end of while
				echo"</tbody></table>";		
				//close statement
				mysqli_stmt_close($stmt);
			}//end of stmt
			mysqli_close($mysqli);
			
			echo"<br/>";
			echo"<br/>";
			
			//this is variable declaration for the message reports, ideally these should have different names than the above stuff, but the system
			//doesn't care, most likely because of variable scope.
			$reportedBy="";
			$reason="";
			$content="";
			$reportDate="";
			$reportedUser="";
			$reportedUserID="";
			$reportID=0;
			
			include('dbConnect.php');
		
			//display all the comment reports first
			if($stmt=mysqli_prepare($mysqli, 
			"SELECT tblmessagereport.reportID, tbluser.username, tblmessagereport.reportedUser, tblmessagereport.reason, tblmessage.messageContent, tblmessagereport.reportDate, tblmessagereport.reportedBy
			FROM tblmessagereport, tbluser, tblmessage
			WHERE tblmessagereport.reportedUser= tbluser.userID
            AND tblmessagereport.messageID= tblmessage.messageID")){
				mysqli_stmt_execute($stmt);
				
				mysqli_stmt_bind_result($stmt, $reportID, $reportedUser, $reportedUserID, $reason, $content, $reportDate, $reportedBy);
				//this sets up the table header
				echo"
					<h4>Reported Messages:</h4>
					<table class='table-hover table'>
						<thead>
							<tr>
								<th><h5>Reported User</h5></th>
								<th><h5>Reason for Report</h5></th>
								<th><h5>Message Content</h5></th>
								<th><h5>Date of Report</h5></th>
								<th><h5>Report Filed By</h5></th>
							</tr>
						</thead>
					<tbody>
					";
				//table body echoed out of here
				while(mysqli_stmt_fetch($stmt)){
					echo"
					
					<tr>	
						<td>$reportedUser</td>
						<td>$reason</td>
						<td>$content</td>
						<td>$reportDate</td>
						<td>$reportedBy</td>
						<td>
							<form method='post' action='adminReportMenu.php'>
							<input type='hidden' value='$reportID' name='rID'/>
							<input type='hidden' value='message' name='dismissType'/>
							<input type='submit' class='btn btn-outline-primary form-control sincerely' name='dismiss' value='Dismiss'/>
							</form>
						<br/>
							<form method='post' action='deleteUser.php'>
							<input type='hidden' value='$reportedUserID' name='userID'/>
							<input type='hidden' value='$page' name='page'/>
							<input type='submit' class='btn btn-outline-primary form-control sincerely' name='deleteUser' value='Delete Reported User'/>
							</form>
						</td>
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
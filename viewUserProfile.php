<?php 
/*This page is used to show a preview of a user's profile*/
	session_start();
	ob_start();
	$count=0;
	$feedback="";
	
	$username="";
	$description="";
	$picture="";
	$email="";
	$position=0;
	
	$result[]="";
	
	if(isset($_GET['userID'])){
		$userID=$_GET['userID'];
		$viewedUser=$_GET['uName'];
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
          <li><a href="viewUserProfile.php"><?php global $uName; echo"$viewedUser's"?> Profile</a></li>
        </ol>
        <h2><?php global $uName; echo"$viewedUser's"?> Profile</h2>

      </div>
	  
    </section><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="form">
      <div class="container"> 
	  <!--Content Here :0-->
	  <?php
	  global $userID;
	  
	    require("dbConnect.php");
		  //get the user information from the database
		  if($stmt=mysqli_prepare($mysqli, 
		  "SELECT tbluser.description, tbluser.pictureID, tbluser.email, tbluser.position FROM tbluser WHERE userID=?")){
				  //bind entered parameters to mysqli object
				mysqli_stmt_bind_param($stmt, "i", $userID);
				
				//execute the stmt
				mysqli_stmt_execute($stmt);
				
				//get results of query
				mysqli_stmt_bind_result($stmt, $description, $picture, $email, $position);
						
					if(mysqli_stmt_fetch($stmt)){
							/*Echo user profile information*/
							echo"<div class='row'>";
							
							
								//username
								echo"<div class='col-lg-6'> <h4>Username:</h4> <h5>$viewedUser</h5>";
								
								if($position==2){
									echo"<div><strong class='error'>This user is an administrator.</strong></div>";
								}
								
								//Contact
								echo"<br/><h4>Contact Information:</h4>";
								if($email!=null || $email!=""){					
									echo"<h5>$email</h5>";
								}else{
									echo"<h5>This user has not set a contact email.</h5>";
								}
								
								//Description
								echo"<br/><h4>Description:</h4>";
								if($description!=null || $description!=""){					
									echo"<h5 class='wrapword'>$description</h5></div>";
								}else{
									echo"<h5>This user has not set a description.</h5></div>";
								}
								
								//Image
								echo"<div class='col-lg-6'><h4>Avatar:</h4>";
								if($picture!=null || $picture!=""){
									echo"<img src='accountAvatar/$picture' style='width:300px; height:200px;'/>";
								}else{
									echo"<h5>This user has not set a description.</h5>";
								}
								echo"</div>";
								
							echo"</div>";
					}else{
						echo"<h6 class='error'>Database error encountered. Please contact an administrator for assistance.</h6>";
					}
				 /* close statement */
				mysqli_stmt_close($stmt);
			}//end of stmt
			//close connection
			mysqli_close($mysqli);
			//this is a link that would allow a user to start a conversation with a user from their profile
			if($_SESSION['uID']!= $userID){
				echo"<a href='createMessage.php?user=$viewedUser'><button class='btn btn-outline-primary sincerely col-sm-12'>Message this User</button></a>";
			
			}
			
			echo"<br/>";
			echo"<br/>";
			//initalize all variables
				$postID=0;
				$content="";
				$heading="";
				$postDate="";
				$status="";
				$reason="";
				
				include('dbConnect.php');
				
				//this query is to get all of the user's blogposts and display them.
				if($stmt=mysqli_prepare($mysqli, 
				"SELECT tblblogpost.postID, tblblogpost.content, tblblogpost.heading, tblblogpost.postDate, tblconfirmedposts.confirmed, tblconfirmedposts.reason
				FROM tblblogpost, tblconfirmedposts
				WHERE tblblogpost.postID=tblconfirmedposts.postID
				AND tblblogpost.userID=?")){
					//bind parameters
					mysqli_stmt_bind_param($stmt, 'i', $userID);
					
					//execute query
					mysqli_stmt_execute($stmt);
					
					//bind results
					mysqli_stmt_bind_result($stmt, $postID, $content, $heading, $postDate, $status, $reason);
					
					//create table head
					echo"
					
					<table class='table-hover table'>
					<thead>
						<tr>
							<th><h5>Heading</h5></th>
							<th><h5>Body Preview</h5></th>
							<th><h5>Date/Time</h5></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					";
					
					//fetch and display results
					while (mysqli_stmt_fetch($stmt)){
						 $heading=substr($heading,0,50);
					        $content=substr($content,0,50);
					        $content=strip_tags($content);
					    	$content = str_ireplace(array("\r","\n",'\r','\n'),'', $content);
							$postDate=date('h:i:s a m/d/Y', strtotime($postDate));
						
						if($status==1){
							echo"
							<tr>	
							<td>$heading</td>
							<td>$content</td>
							<td>$postDate</td>
							<td><a href='viewBlogSingle.php?postID=$postID'>View Post</a></td>
							</tr>";
						}
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
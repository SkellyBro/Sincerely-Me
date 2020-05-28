<?php

	require('adminSessionChecker.php');
	
	//set up error variables
	$feedback="";
	$count=0;
	$success="";
	
	if(isset($_GET['postID'])){
		$postID=$_GET['postID'];
	}
	
	if(isset($_SESSION['uID'])){
		$uID=$_SESSION['uID'];
	}//end of isset
	
	if(isset($_POST['confirm'])){
		//make database connection
		include('dbConnect.php');
		
		//get postIDs
		$postID=$_POST['postID'];
		
		//set up the confirmed variable
		$confirmed=true;
	
		//make database string
		if($stmt=mysqli_prepare($mysqli, 
		"UPDATE tblconfirmedposts 
		SET tblconfirmedposts.userID=?, tblconfirmedposts.confirmed=? 
		WHERE tblconfirmedposts.postID=?")){
			//bind parameter
			mysqli_stmt_bind_param($stmt, "isi", $uID, $confirmed, $postID);
			//execute query
			if(mysqli_stmt_execute($stmt)){
				$success.="<br/>Blogpost confirmed successfully! You will be returned to the blogpost list shortly.";
				header("refresh:2; url=viewUserPosts.php" );
			}else{
				$feedback.="<br/>Blogpost could not be confirmed. Please contact a technician for assistance.";
				$count++;
			}
			
		}//end of if-stmt
	}//end of confirm isset
	
	if(isset($_POST['deny'])){
		$count++;
		$feedback.="<br/> This hasn't been built out yet. ";
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
          <?php
			if(isset($_SESSION['uName'])){
				if(($_SESSION['position']==1)){
				$uName=$_SESSION['uName'];
				echo"
					<li><a href='createBlog.php'>Create Blogpost</a></li>
					<li><a href='userAccount.php'>$uName's Account</a></li>
					<li><a href='logout.php'>Logout</a></li>
					
				";
				
				}else if(($_SESSION['position']==2)){
					$uName=$_SESSION['uName'];
					echo"
						<li><a href='createBlog.php'>Create Blogpost</a></li>
						<li><a href='adminPanel.php'>Administrator Panel</a></li>
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
          <li><a href="adminPanel.php">Administrator Panel</a></li>
          <li><a href="viewUserPosts.php">View All User Posts</a></li>
          <li><a href="confirmPost.php">Confirm Post</a></li>
        </ol>
        <h2>Confirm Post</h2>

      </div>
	  
    </section><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="blog" class="blog">
	
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
	
	
      
	  <!--Content Here :0-->
	  <?php
	  //create query to get all of the user's data for their post. 
	  //initialize variables to store user information
	
	
	  $username="";
	  $heading="";
	  $postDate="";
	  $content="";
	  $userID=0;
	  $image=[];
	  $tags=[];
	  $tagCount=0;
	  $imageCount=0;
	 
	//make database connection	 
	 include('dbConnect.php');
	//code to get the tags out of the database
		if($stmt=mysqli_prepare($mysqli, 
		"SELECT tbltags.tagName
		FROM tbltags
		WHERE tbltags.postID=?")){
			//bind post ID for the query
			mysqli_stmt_bind_param($stmt, "i", $postID);
			
			//execute query
			mysqli_stmt_execute($stmt);
			
			//get results
			$result=mysqli_stmt_get_result($stmt);
			
			while($row=mysqli_fetch_array($result, MYSQLI_NUM))
			{
				
				foreach ($row as $r)
				{
					$tags[$tagCount]=$r;
					$tagCount++;
				}
           
			}//end of while loop
			mysqli_stmt_close($stmt);
		}//end of stmt
	  
	//code to get the image names from the database
		
		
		if($stmt=mysqli_prepare($mysqli, 
		"SELECT tblimages.imageName
		FROM tblimages
		WHERE tblimages.postID=?")){
			//bind postID for the query
			mysqli_stmt_bind_param($stmt, 'i', $postID);
			
			//execute query
			mysqli_stmt_execute($stmt);
			
			$result=mysqli_stmt_get_result($stmt);
			
			while($row=mysqli_fetch_array($result, MYSQLI_NUM))
			{
				
				foreach ($row as $r)
				{
					$image[$imageCount]=$r;
					$imageCount++;
				}
				
			}//end of while loop
			mysqli_stmt_close($stmt);
		}//end of stmt
		
		//get actual post content from database
		if($stmt=mysqli_prepare($mysqli, 
		"SELECT tbluser.userID, tbluser.username, tblblogpost.heading, tblblogpost.postDate, tblblogpost.content
		FROM tbluser, tblblogpost
		WHERE tbluser.userID=tblblogpost.userID
		AND tblblogpost.postID=?"))
		{
			//bind postID for the query
			mysqli_stmt_bind_param($stmt, "i", $postID);
			
			//execute query
			mysqli_stmt_execute($stmt);
			
			//get results
			mysqli_stmt_bind_result($stmt, $userID, $username, $heading, $postDate, $content);
			
			//fetch results
			if(mysqli_stmt_fetch($stmt)){
				echo"
					<div class='container'>

						<div class='row'>

							<div class='col-lg-12 entries'>

							<article class='entry entry-single'>

							  <div class='entry-img'>";
							  /*
							  This code is used to determine how the images should be displayed based on the image count
							  */
							  if($imageCount>=1){
								  echo" <img src='blogImages/$image[0]' class='img-fluid'>";
							  }else if($imageCount==1){
								  echo" <img src='blogImages/$image[0]' class='img-fluid'>";
							  }
							//continuation of echoing out the article itself	
							echo"
							  </div>
							
							  <h2 class='entry-title'>
								<a href='confirmPost.php'>$heading</a>
							  </h2>

							  <div class='entry-meta'>
								<ul>
								  <li class='d-flex align-items-center'><i class='icofont-user'></i> <a href='blog-single.html'>$username</a></li>
								  <li class='d-flex align-items-center'><i class='icofont-wall-clock'></i> <a href='blog-single.html'><time datetime='2020-01-01'>$postDate</time></a></li>
								</ul>
							  </div>

							  <div class='entry-content'>
							   
								$content

							  </div>";
							  
							   //this is another bit of code used to display the secondary image, if any. 
							  if($imageCount>1){
								  echo" <img src='blogImages/$image[1]' class='img-fluid'>";
							  }
							  
							echo"
							  <div class='entry-footer clearfix'>

								  <i class='icofont-tags'></i>
								  <ul class='tags'>
							";
								  /*
								  This code is used to display the tags used for a post
								  */
								  for($x=0; $x<$tagCount; $x++){
									  echo"
										<li><a href='#'>$tags[$x]</a></li>
									  ";
								  }//end of for loop
							//continuation of echoing out the article	  
							echo"
								  </ul>
								</div>

							  </div>
							  
							 <div>
							</article><!-- End blog entry -->
							</div>
						</div>
					</div>
					";//end of article echoing
				}else{
					echo"<h4 class='error'>An error occured, cannot connect to database.</h4>";
				}
			}//end of if-stmt
			
			/*
			
				Todo: Ask Antonia, what she would like done in the event where she doesn't accept a post. 
				Should the user be informed and the blogpost deleted or should the blogpost just be straight-up deleted 
				no questions asked. 
				
				This would result in extended functionality for a notificaiton area to inform the user of this. 
				Which coincides with the existing idea to build a notificaiton system. 
			
			*/
	  
	  //Form used to confirm or deny the user's post
	  echo"
			  <form class='form-horizontal' method='post' action='confirmPost.php'>
			  
				<div class='col-sm-12'>
					<input type='hidden' name='postID' value='$postID'/>
					<input type='submit' class='btn btn-outline-primary form-control sincerely' name='confirm' value='Confirm Post'/>
					
				</div>
				<br/>
				<div class='col-sm-12'>
				
					<input type='submit' class='btn btn-outline-primary form-control sincerely' name='deny' value='Deny Post'/>
				
				</div>
			  
			  </form>
	  </div>
	  ";
	?>  
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
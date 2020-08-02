<?php
	/*This page is used by the admins to confirm a user's blogpost before it is made public to the rest of the site*/
	require('adminSessionChecker.php');
	
	//set up error variables
	$feedback="";
	$count=0;
	$success="";
	
	/*this isset catches the postID sent from viewUserPosts.php
	The post ID is stored in the session as a precaution, if the page reloads then the postID would not be lost.
	The first isset captures the postID and stores it into the session, the second isset only runs if the postID is not isset i.e. a page reload took place
	Then the second isset will try to get the postID from the session.
	
	*/
	if(isset($_GET['postID'])){
		$postID=$_GET['postID'];
		$_SESSION['confirmPostID']=$postID;
	}else if(isset($_SESSION['confirmPostID'])){
		$post=$_SESSION['confirmPostID'];
	}
	
	//capture userID from session. 
	if(isset($_SESSION['uID'])){
		$uID=$_SESSION['uID'];
	}//end of isset
	
	//confirm post
	if(isset($_POST['confirm'])){
		//make database connection
		include('dbConnect.php');
		
		//get postIDs
		$postID=$_POST['postID'];
		
		//set up the confirmed variable (1 being true, 2 being false)
		$confirmed=1;
	
		//make database string to update the post's confirmation status.
		if($stmt=mysqli_prepare($mysqli, 
		"UPDATE tblconfirmedposts 
		SET tblconfirmedposts.userID=?, tblconfirmedposts.confirmed=? 
		WHERE tblconfirmedposts.postID=?")){
			//bind parameter
			mysqli_stmt_bind_param($stmt, "isi", $uID, $confirmed, $postID);
			//execute query
			if(mysqli_stmt_execute($stmt)){
				//if post was confirmed successfully
				$success.="Blogpost confirmed successfully! You will be returned to the blogpost list shortly.";
				header("refresh:2; url=viewUserPosts.php" );
			}else{
				//if post was not confirmed
				$feedback.="Blogpost could not be confirmed. Please contact a technician for assistance.";
				$count++;
			}
			
		}//end of if-stmt
	}//end of confirm isset
	
	//this isset handles the denial of a blogpost.
	if(isset($_POST['deny'])){
		//include database connection
		include('dbConnect.php');
		//init variable
		$reason="";
		
		//get variable from post
		if(isset($_POST['reason'])){
			$reason=$_POST['reason'];
		}
		$postID=$_POST['postID'];
		//set confirmation status (1 being true, 2 being false)
		$confirmed=2;
		
		//if reason is not empty then run db query
		if($reason!="" || $reason!=null){
			//make database string to update the blogpost's confirmation status
			if($stmt=mysqli_prepare($mysqli, 
			"UPDATE tblconfirmedposts 
			SET tblconfirmedposts.userID=?, tblconfirmedposts.confirmed=?, tblconfirmedposts.reason=?
			WHERE tblconfirmedposts.postID=?")){
				//bind parameter
				mysqli_stmt_bind_param($stmt, "issi", $uID, $confirmed, $reason, $postID);
				//execute query
				if(mysqli_stmt_execute($stmt)){
					//successful denial of blogpost
					$success.="Blogpost denied successfully! You will be returned to the blogpost list shortly.";
					header("refresh:2; url=viewUserPosts.php" );
				}else{
					//unsuccessful denial of blogpost
					$feedback.="<br/>Blogpost could not be denied. Please contact a technician for assistance.";
					$count++;
				}
			}//end of stmt
		}else{
			$feedback.="<br/>You must select a reason to deny the post.";
			$count++;
		}
		
		
		
	}//end of deny
	
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
	  //initialize variables to store user information to display a preview of the user's blogpost.
	  //the code below is a copy-paste of the code in confirmBlog.php, there are more indepth comments on that page that explains what the code does.
	
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
							  if($image[0]!="" || $image[0]!=null ){
								   
								  if($imageCount>=1){
									echo" <img src='blogImages/$image[0]' class='img-fluid'>";
								  }else if($imageCount==1){
									echo" <img src='blogImages/$image[0]' class='img-fluid'>";
								  }
							  }
							 
							//continuation of echoing out the article itself	
							echo"
							  </div>
							
							  <h2 class='entry-title'>
								<a href='confirmPost.php'>$heading</a>
							  </h2>

							  <div class='entry-meta'>
								<ul>
								  <li class='d-flex align-items-center'><i class='icofont-user'></i> <a href='viewUserProfile.php?userID=$userID&uName=$username'>$username</a></li>
								  <li class='d-flex align-items-center'><i class='icofont-wall-clock'></i><time datetime='2020-01-01'>$postDate</time></a></li>
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
			
	  
	  /*These are two froems used to confirm or deny a user's blogpost, the first form handles the confirmation of a blogpost.
	  The second form handles the denial of a blogpost, requiring the admin to give a reason for the user's blogpost to be denied.*/
	  echo"
			  <form class='form-horizontal' method='post' action='confirmPost.php'>
			  
				<div class='col-sm-12'>
					<input type='hidden' name='postID' value='$postID'/>
					<input type='submit' class='btn btn-outline-primary form-control sincerely' name='confirm' value='Confirm Post'/>
					
				</div>
				</form>
				
				<form class='form-horizontal' method='post' action='confirmPost.php' onsubmit='return valReason(this)'>
				<br/>
				<div class='col-sm-12'>
					<input type='hidden' name='postID' value='$postID'
					<div>
					<button type='button' class='btn btn-outline-primary form-control sincerely' data-toggle='collapse' 
					data-target='#denial'>View Denial Options</button>
					</div>
					<div id='denial' class='collapse'>
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
								
								<input type='radio' id='nudity' name='reason' value='Nudity'>
								<label for='nudity'>Nudity</label><br>
								
								<input type='radio' id='spam' name='reason' value='Spam'>
								<label for='Spam'>Spam</label><br>
								
								<span class='error' id='reason_err'></span>
								<br/>
								<br/>
							</div>
							<input type='submit' class='btn btn-outline-primary form-control sincerely' name='deny' onClick='return valReason();'/>
						</div>
					</div>
					
				
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
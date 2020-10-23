<?php
ob_start();
	//the purpose of this page is to give the user a preview of their blogpost after creating it.
	//this is a custom session-checker, as this page could be accessed by either a blogger or administrator
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
		  <li><a href="createBlog.php">Create Blog</a></li>
		  <li><a href="confirmBlog.php">Blog Preview</a></li>
        </ol>
        <h2>Blog Preview</h2>
		<p>Here you can see a preview of your blogpost.</p>
      </div>
	  
    </section><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="blog" class="blog">
      
	  
	  
	  <!--Content Here :0-->
	  <?php
		//get the post ID of the blogpost if isset from createBlog.php
		if(isset($_GET['postID'])){
			$postID=$_GET['postID'];
		}
	
	//variable declaration for the blog preview
	$username="";
	$heading="";
	$postDate="";
	$content="";
	$userID=0;
	$image=[];
	$tags=[];
	$tagCount=0;
	$imageCount=0;
	 
	//the preview has to be done in phases, so this query gets the tags for the blogpost and stores them inside of an array
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
			//this is inefficient, there's probably a better way to do this but I'm not sure how
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
	  
	//code to get the image names from the database, similar to the tags, this gets the image names and stores them inside of an array
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
		//so this query gets the actual blogpost content from the database and inserts the tags/images appropriately
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
				$content = str_ireplace(array("\r","\n",'\r','\n'),'', $content);
				$postDate=date('h:i:s a m/d/Y', strtotime($postDate));
				echo"
					<div class='container'>";
					//this is just a feedback message
					if($_SESSION['position']==1){
						echo"<div class='alert alert-success'>";
						echo"<h5>Your blogpost has been made successfully! All of your posts can be seen on your account.</h5>
						<p>Please be advised that your post will need to be verified by an administrator before it is made public, this may take some time.
						If you dislike your post or want to change anything about it, go into your profile to edit or delete the post as you would like.</p>";
						echo"</div>";
					}else{
						echo"<div class='alert alert-success'>";
						echo"<h5>Blogpost made successfully, all content has been saved! All of your posts can be seen on your account!</h5>";
						echo"<p>If you dislike your post or want to change anything about it, go into your profile to edit or delete the post as you would like.</p>";
						echo"</div>";
					}
					
					//this is the post echo
					echo"
					<br/>
						<div class='row'>

							<div class='col-lg-12 entries'>

							<article class='entry entry-single'>

							  <div class='entry-img'>";
							  /*
							  This code is used to determine how the images should be displayed based on the image count.
							  So, if position 0 in the array is not empty, then there is atleast 1 image in the array. 
							  
							  The if-statements are just there to only include those html scripts as needed, if they're included without
							  the checks, then on the page the user would see the default html image placeholder icon. 
							  */
							  if($image[0]!=""|| $image[0]!=null){
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
								<div class='wrapword'>$heading</div>
							  </h2>

							  <div class='entry-meta'>
								<ul>
								  <li class='d-flex align-items-center'><i class='icofont-user'></i> <a href='viewUserProfile.php?userID=$userID&uName=$username'>$username</a></li>
								  <li class='d-flex align-items-center'><i class='icofont-wall-clock'></i><time datetime='2020-01-01'>$postDate</time></a></li>
								</ul>
							  </div>

							  <div class='entry-content'>
							   
								<div class='wrapword'>$content</div>

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
					echo"<div class=container><h4 class='error'>An error occured, cannot connect to database.</h4></div>";
				}
			}//end of if-stmt
		
	  ?>
	  
	  <!--This button allows the user to return home-->
	  <div class='container'>
	  <a href="index.php"><button class="btn btn-outline-primary form-control sincerely">Return to Home</button></a>
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
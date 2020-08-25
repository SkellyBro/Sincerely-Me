<?php
/*This page handles the search results for the website*/
session_start();
ob_start();
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
          <li><a href="searchResults.php">Search Results</a></li>
        </ol>
        <h2>Search Results</h2>
      </div>
	  
    </section><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="blog" class='blog'>
      <div class='container'>
	 <?php
		/*How this search works is that it tries to find all users, blogposts and tags associated with the search string.*/
			//get the search string sent from index.php
			if(isset($_GET['search'])){
				$searchString=$_GET['search'];
				$search='%'.$searchString.'%';
			}
			
			echo"<h3>Search Results for: '$searchString'</h3>
			<br/>";
			
			//USER SEARCH RESULTS
			$uID="";
			$uName="";
			$pictureID="";
			$descriptison="";
			
			echo"<h4>Users Found:</h4>";
			
			include('dbConnect.php');
			
			if($stmt=mysqli_prepare($mysqli, 
			"SELECT tbluser.userID, tbluser.username, tbluser.pictureID, tbluser.description
			FROM tbluser
			WHERE tbluser.username LIKE ?")){
				mysqli_stmt_bind_param($stmt, 's', $search);
				
				mysqli_stmt_execute($stmt);
				
				mysqli_stmt_bind_result($stmt, $uID, $uName, $pictureID, $description);
				
				mysqli_stmt_store_result($stmt);
				
				$rowCount=mysqli_stmt_num_rows($stmt);
				
				if($rowCount>=1){
					while(mysqli_stmt_fetch($stmt)){
						echo"
							 <article class='entry'>";
							/*
							This is a very inelegant workaround for the images 
							
							*/
							if($pictureID!="" || $pictureID!=null){
							echo"
							<div class='entry-img'>
								<img src='accountAvatar/$pictureID' class='img-fluid'>
							</div>";
							}
							
						echo"
							<div class='col-sm-12'>
							<div class='row'>
							<div class='col-sm-12'><h2 class='entry-title'>$uName</h2></div>
							<div class='col-sm-12'><h4>$description</h4></div>
							<div class='col-sm-12'>";
								  
									
							if(isset($_SESSION['uName'])){
									if($_SESSION['uName']==$uName){
									echo" <button class='btn btn-outline-primary form-control sincerely'><a href='userAccount.php'>View Account</a></button>";
								}else{
									echo"<button class='btn btn-outline-primary form-control sincerely'><a href='viewUserProfile.php?userID=$uID&uName=$uName'>View Account</a></button>";
								}
							}else{
								echo"<li class='d-flex align-items-center'><i class='icofont-user'></i> <a href='viewUserProfile.php?userID=$userID&uName=$uName'>View Account</a></li>";
							}
								  
								echo"
								</div>
								</div>
								</div>
							</article><!-- End blog entry -->
						
						";
					}//end of while
					mysqli_stmt_close($stmt);
				}else{
					echo"<h5 class='error'>No users found</h5><br/>";
				}
			}//end of stmt
			
			//BLOGPOST RESULTS
			//variables for blogpost results
			$postID=0;
			$username="";
			$heading="";
			$content="";
			$postDate="";
			$imageName="";
			$userID="";
			
			//blogpost results
			echo"<h4>Blogposts Found:</h4>";
			
			include('dbConnect.php');
			//make sql string to search titles first
			if($stmt2=mysqli_prepare($mysqli,
			"SELECT tblblogpost.postID, tbluser.username, tblblogpost.heading, tblblogpost.content, tblblogpost.postDate, tblimages.imageName, tbluser.userID
			FROM tbluser, tblblogpost, tblconfirmedposts, tblimages
			WHERE tbluser.userID=tblblogpost.userID 
			AND tblblogpost.postID=tblconfirmedposts.postID
			AND tblblogpost.postID=tblimages.postID
			AND tblconfirmedposts.confirmed=1
			AND tblblogpost.heading LIKE ?
			GROUP BY tblblogpost.postID
			ORDER BY tblblogpost.postDate DESC")){
				mysqli_stmt_bind_param($stmt2, 's', $search);
				
				mysqli_stmt_execute($stmt2);
				
				mysqli_stmt_bind_result($stmt2, $postID, $username, $heading, $content, $postDate, $imageName, $userID);
				
				mysqli_stmt_store_result($stmt2);
				
				$rowCount=mysqli_stmt_num_rows($stmt2);
				if($rowCount>=1){
				
				while(mysqli_stmt_fetch($stmt2)){
					$preview=substr($content,0,100);
					$preview=strip_tags($preview);
					
					echo"
						 <article class='entry'>";
						/*
						This is a very inelegant workaround for the images 
						
						*/
						if($imageName!="" || $imageName!=null){
						echo"
						<div class='entry-img'>
							<img src='blogImages/$imageName' class='img-fluid'>
						</div>";
						}
						
					echo"	
						<h2 class='entry-title'>
								<a href='viewBlogSingle.php?postID=$postID'>$heading</a>
							</h2>
							
						  <div class='entry-meta'>
							<ul>";
							  
							  	
						if(isset($_SESSION['uName'])){
								if($_SESSION['uName']==$username){
								echo"<li class='d-flex align-items-center'><i class='icofont-user'></i> <a href='userAccount.php'>$username</a></li>";
							}else{
								echo"<li class='d-flex align-items-center'><i class='icofont-user'></i> <a href='viewUserProfile.php?userID=$userID&uName=$username'>$username</a></li>";
							}
						}else{
							echo"<li class='d-flex align-items-center'><i class='icofont-user'></i> <a href='viewUserProfile.php?userID=$userID&uName=$username'>$username</a></li>";
						}
							  
							echo"
							  <li class='d-flex align-items-center'><i class='icofont-wall-clock'></i><time datetime='2020-01-01'>$postDate</time></a></li>
							</ul>
						  </div>

						  <div class='entry-content'>
							<p>$preview...</p>
							<div class='read-more'>
							  <a href='viewBlogSingle.php?postID=$postID'>Read More</a>
							</div>
						  </div>

						</article><!-- End blog entry -->
					
					";
				}//end of fetch	
				mysqli_stmt_close($stmt2);
				}else{
					echo"<h5 class='error'>No posts found</h5><br/>";
				}
		}//end of stmt
		

		//TAG RESULTS
		//BLOGPOST RESULTS
			//variables for blogpost results
			$postID=0;
			$username="";
			$heading="";
			$content="";
			$postDate="";
			$imageName="";
			$userID="";
			
			//blogpost results
			echo"<h4>Posts Tagged as \"$searchString\": </h4>";
			
			include('dbConnect.php');
			//make sql string to search titles first
			if($stmt3=mysqli_prepare($mysqli,
			"SELECT tblblogpost.postID, tbluser.username, tblblogpost.heading, tblblogpost.content, tblblogpost.postDate, tblimages.imageName, tbluser.userID
			FROM tbluser, tblblogpost, tblconfirmedposts, tblimages, tbltags
			WHERE tbluser.userID=tblblogpost.userID 
			AND tblblogpost.postID=tblconfirmedposts.postID
			AND tblblogpost.postID=tblimages.postID
			AND tblblogpost.postID=tbltags.postID
			AND tbltags.tagName LIKE ?
			AND tblconfirmedposts.confirmed=1
			GROUP BY tblblogpost.postID
			ORDER BY tblblogpost.postDate DESC")){
				mysqli_stmt_bind_param($stmt3, 's', $search);
				
				mysqli_stmt_execute($stmt3);
				
				mysqli_stmt_bind_result($stmt3, $postID, $username, $heading, $content, $postDate, $imageName, $userID);
				
				mysqli_stmt_store_result($stmt3);
				
				$rowCount=mysqli_stmt_num_rows($stmt3);
				if($rowCount>=1){
				
				while(mysqli_stmt_fetch($stmt3)){
					$preview=substr($content,0,100);
					$preview=strip_tags($preview);
					
					echo"
						 <article class='entry'>";
						/*
						This is a very inelegant workaround for the images 
						
						*/
						if($imageName!="" || $imageName!=null){
						echo"
						<div class='entry-img'>
							<img src='blogImages/$imageName' class='img-fluid'>
						</div>";
						}
						
					echo"	
						<h2 class='entry-title'>
								<a href='viewBlogSingle.php?postID=$postID'>$heading</a>
							</h2>
							
						  <div class='entry-meta'>
							<ul>";
							  
							  	
						if(isset($_SESSION['uName'])){
								if($_SESSION['uName']==$username){
								echo"<li class='d-flex align-items-center'><i class='icofont-user'></i> <a href='userAccount.php'>$username</a></li>";
							}else{
								echo"<li class='d-flex align-items-center'><i class='icofont-user'></i> <a href='viewUserProfile.php?userID=$userID&uName=$username'>$username</a></li>";
							}
						}else{
							echo"<li class='d-flex align-items-center'><i class='icofont-user'></i> <a href='viewUserProfile.php?userID=$userID&uName=$username'>$username</a></li>";
						}
							  
							echo"
							  <li class='d-flex align-items-center'><i class='icofont-wall-clock'></i><time datetime='2020-01-01'>$postDate</time></a></li>
							</ul>
						  </div>

						  <div class='entry-content'>
							<p>$preview...</p>
							<div class='read-more'>
							  <a href='viewBlogSingle.php?postID=$postID'>Read More</a>
							</div>
						  </div>

						</article><!-- End blog entry -->
					
					";
				}//end of fetch	
				mysqli_stmt_close($stmt3);
				}else{
					echo"<h5 class='error'>No posts found.</h5><br/>";
				}
		}//end of stmt
		
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
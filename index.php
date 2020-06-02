<?php

session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Sincerely, Me</title>
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
        </ol>
        <h2>Sincerely, Me.</h2>
		<!--
		<h6>
		I wanted to create a safe space to express how you feel without revealing who you are. 
		The ultimate bloggers dream to ventilate every thought, feeling, and behaviour, without the fear of anyone knowing your identity. 
		A group journal, where others can be inspired by your narrative as you relieve all that’s pent up. 
		This is a judgement and abuse free place. We only encourage supportive networking. 
		If you’re interested in booking a therapeutic counselling session, check out our website <a href="https://theracoconsultants.com/">here</a>. 
		<br/><br/> -Antonia Mootoo
		-->
		
		<?php
			
			//make connection to database and pull message
			include('dbConnect.php');
			
			if($stmt=mysqli_prepare($mysqli, 
			"SELECT tblpmessage.pMessage, tbluser.username
			FROM tblpmessage, tbluser, tbladmin
			WHERE tblpmessage.userID=tbladmin.userID
			AND tbladmin.userID=tbluser.userID
			GROUP BY tblpmessage.pMessageDate DESC")){
				
				//no variables to bind
				//execute query
				mysqli_stmt_execute($stmt);
				
				//bind results
				mysqli_stmt_bind_result($stmt, $pMessage, $username);
				
				//fetch results
				if(mysqli_stmt_fetch($stmt)){
					echo"<h6>$pMessage</h6>";
					echo"<div class='row col-sm-12'>
							
							<div class='col-sm-6'>--$username</div>			
						
						</div>";
				}else{
					echo"
					
					I wanted to create a safe space to express how you feel without revealing who you are. 
					The ultimate bloggers dream to ventilate every thought, feeling, and behaviour, without the fear of anyone knowing your identity. 
					A group journal, where others can be inspired by your narrative as you relieve all that’s pent up. 
					This is a judgement and abuse free place. We only encourage supportive networking. 
					If you’re interested in booking a therapeutic counselling session, check out our website <a href='https://theracoconsultants.com/'>here</a>. 
					<br/><br/> -Antonia Mootoo
					
					
					";
				}//end of else
			}//end of if
		
		?>
		</h6>

      </div>
	  
    </section><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="blog" class="blog">
      <div class="container">

        <div class="row">

          <div class="col-lg-8 entries">
		  
		  <?php
		  
		  //setting up variables for the blog lists
		  $postID=0;
		  $username="";
		  $heading="";
		  $content="";
		  $postDate="";
		  $imageName="";
		  $userID=0;
		  
			include('dbConnect.php');
			
			if($stmt=mysqli_prepare($mysqli, 
			"SELECT tblblogpost.postID, tbluser.username, tblblogpost.heading, tblblogpost.content, 
			tblblogpost.postDate, tblimages.imageName, tbluser.userID
			FROM tbluser, tblblogpost, tblconfirmedposts, tblimages
			WHERE tbluser.userID=tblblogpost.userID 
			AND tblblogpost.postID=tblconfirmedposts.postID
			AND tblblogpost.postID=tblimages.postID
			AND tblconfirmedposts.confirmed=1
			GROUP BY tblblogpost.postID
			ORDER BY tblblogpost.postDate DESC")){
				//execute query
				mysqli_stmt_execute($stmt);
				
				//bind results
				mysqli_stmt_bind_result($stmt, $postID, $username, $heading, $content, $postDate, $imageName, $userID);
				
				while(mysqli_stmt_fetch($stmt)){
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
							<ul>
							  <li class='d-flex align-items-center'><i class='icofont-user'></i> <a href='viewUserProfile.php?userID=$userID&uName=$username'>$username</a></li>
							  <li class='d-flex align-items-center'><i class='icofont-wall-clock'></i> <a href='blog-single.html'><time datetime='2020-01-01'>$postDate</time></a></li>
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
				
			mysqli_stmt_close($stmt);
		}//end of stmt
		mysqli_close($mysqli);
		  
		  ?>

            <div class="blog-pagination">
              <ul class="justify-content-center">
                <li class="disabled"><i class="icofont-rounded-left"></i></li>
                <li><a href="#">1</a></li>
                <li class="active"><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#"><i class="icofont-rounded-right"></i></a></li>
              </ul>
            </div>

          </div><!-- End blog entries list -->

          <div class="col-lg-4">

            <div class="sidebar">

              <h3 class="sidebar-title">Search</h3>
              <div class="sidebar-item search-form">
                <form action="">
                  <input type="text">
                  <button type="submit"><i class="icofont-search"></i></button>
                </form>

              </div><!-- End sidebar search formn-->

              <h3 class="sidebar-title">Categories</h3>
              <div class="sidebar-item categories">
                <ul>
                  <li><a href="#">General <span>(25)</span></a></li>
                  <li><a href="#">Lifestyle <span>(12)</span></a></li>
                  <li><a href="#">Travel <span>(5)</span></a></li>
                  <li><a href="#">Design <span>(22)</span></a></li>
                  <li><a href="#">Creative <span>(8)</span></a></li>
                  <li><a href="#">Educaion <span>(14)</span></a></li>
                </ul>

              </div><!-- End sidebar categories-->

              <h3 class="sidebar-title">Recent Posts</h3>
              <div class="sidebar-item recent-posts">
                <div class="post-item clearfix">
                  <img src="assets/img/blog-recent-1.jpg" alt="">
                  <h4><a href="blog-single.html">Nihil blanditiis at in nihil autem</a></h4>
                  <time datetime="2020-01-01">Jan 1, 2020</time>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/blog-recent-2.jpg" alt="">
                  <h4><a href="blog-single.html">Quidem autem et impedit</a></h4>
                  <time datetime="2020-01-01">Jan 1, 2020</time>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/blog-recent-3.jpg" alt="">
                  <h4><a href="blog-single.html">Id quia et et ut maxime similique occaecati ut</a></h4>
                  <time datetime="2020-01-01">Jan 1, 2020</time>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/blog-recent-4.jpg" alt="">
                  <h4><a href="blog-single.html">Laborum corporis quo dara net para</a></h4>
                  <time datetime="2020-01-01">Jan 1, 2020</time>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/blog-recent-5.jpg" alt="">
                  <h4><a href="blog-single.html">Et dolores corrupti quae illo quod dolor</a></h4>
                  <time datetime="2020-01-01">Jan 1, 2020</time>
                </div>

              </div><!-- End sidebar recent posts-->

              <h3 class="sidebar-title">Tags</h3>
              <div class="sidebar-item tags">
                <ul>
                  <li><a href="#">App</a></li>
                  <li><a href="#">IT</a></li>
                  <li><a href="#">Business</a></li>
                  <li><a href="#">Business</a></li>
                  <li><a href="#">Mac</a></li>
                  <li><a href="#">Design</a></li>
                  <li><a href="#">Office</a></li>
                  <li><a href="#">Creative</a></li>
                  <li><a href="#">Studio</a></li>
                  <li><a href="#">Smart</a></li>
                  <li><a href="#">Tips</a></li>
                  <li><a href="#">Marketing</a></li>
                </ul>

              </div><!-- End sidebar tags-->

            </div><!-- End sidebar -->

          </div><!-- End blog sidebar -->

        </div>

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
          <li><a href="contact.html">Login</a></li>
          <li><a href="contact.html">Register</a></li>
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

</body>

</html>
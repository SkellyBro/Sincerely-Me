<?php

session_start();
ob_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Sincerely, Me.</title>
  <meta content="You can learn more about Sincerely, Me and our resident therapist here!" name="description">
  <meta content="blogging, trinidad and tobago, online, therapy, therapist, group journal, online blog, online therapy blog, venting blog, online venting blog, trinidad, sincerely, me, sincerelyme, sincerelyme-tt, sincerely" name="keywords">

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
          <li><a href="admin.php">Meet our Admininistrator</a></li>
        </ol>
        <h2>About Us</h2>
		<h6>Here you can get a chance to learn about Sincerely, Me and meet Antonia Mootoo, the administrator of Sincerely, Me.</h6>
      </div>
	  
    </section><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="body">
	
		<div class='container'>
		<h4>About Sincerely, Me:</h4>
			<blockquote>
			
				<p>
					I wanted to create a safe space to express how you feel without revealing who you are. 
					The ultimate bloggers dream to ventilate every thought, feeling, and behaviour, without the fear of anyone knowing your identity. 
					A group journal, where others can be inspired by your narrative as you relieve all that’s pent up and support eachother. 
					This is a judgement and abuse free place. We only encourage supportive networking. Feel free to create your own pseudo-identity, write blogs and comment on other posts.
					
					If you’re interested in booking a therapeutic counselling session, check out our website <a href="https://theracoconsultants.com/">here</a>
				</p>
					
				<footer>-Antonia Mootoo</footer>

			
			</blockquote>
		<hr/>
		</div>
		<br/>
		<div class='container'>
		
			<div class='row'>
			
				<div class="col-sm-6">
				<h4>Meet our Admininistrator</h4>
					<p class='text-center vertical-center'><b style='color:#990000'>Antonia Mootoo</b> has a Bachelors of Science in Psychology with a minor in Human Resource Management as well as a 
					Masters of Science in Clinical Social Work, with honours. She has well over a decade of experiential practice in the 
					field of therapeutic counselling (talk-therapy). She has completed a wide range of training courses within the field of 
					counselling and therapeutic interventions. She is a believer in progressive self enhancement and so continues to educate 
					herself with new approaches and therapeutic practices. She adheres to strict terms of confidentiality with clients, 
					and maintains an judgement-free safe space for clients to ventilate their issues and receive treatment.
					</p>
				</div>

				<div class="col-sm-6">
				
					<img class='img-fluid' src="http://theracoconsultants.com/wp-content/uploads/2019/06/antonia.png" alt="An image of Antonia Mootoo"/>
				
				</div>
			</div>
			<br/>
			<a href='https://theracoconsultants.com/'><button class='btn btn-outline-primary form-control sincerely'>Make a Therapy appointment with Antonia</button></a>
			<br/>
			<br/>
			<small class='text-center'>Please note that clicking this button will take you to <a href='https://theracoconsultants.com/'>theracoconsultants.com</a> where you can schedule an appointment with Antonia Mootoo</small>
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
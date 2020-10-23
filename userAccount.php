<?php
	/*This page handles user profile functionality*/
	session_start();
	ob_start();
	if($_SESSION['uName']==""){
		
		Header("Location:login.php?feedback=You must be logged in to access this page...");
		
	}
	
	//error handling variables
	$count=0;
	$feedback="";
	$success="";
	
	//this is to get the informaiton stored in the session and to give a more personalized touch to the page
	if(isset($_SESSION['uName'])){
		$uName=$_SESSION['uName'];
		$uID=$_SESSION['uID'];
	}
	
	//this is used to store the user description
	$description="";
	
	//this is used to store the user's image name
	$picture="";
	
	//this is used to store the user's email
	$email="";
	
	//this isset handles post deletion
	if(isset($_GET['deletePost'])){
		//get postID
		$blog=$_GET['postID'];
		
		//array to hold commentId's*/
		$commentID=[];
		$commentCount=0;
		
		//simple validation
		if($blog==0 || $blog==null){
			$count++;
			$feedback.="<br/> ID for post could not be found, please try again later or contact a administrator for assistance.";
		}
		
		include('dbConnect.php');
		
		//cascading delete
		//delete tags
		if($stmt=mysqli_prepare($mysqli, 
		"DELETE FROM tbltags WHERE tbltags.postID=?")){
			mysqli_stmt_bind_param($stmt, 'i', $blog);
			if(!mysqli_stmt_execute($stmt)){
				$count++;
				$feedback.="<br/> Blogpost tags could not be deleted.";
			}
			mysqli_stmt_close($stmt);
		}//end of delete tag
		
		if($stmt2=mysqli_prepare($mysqli,
		//delete images
		"DELETE FROM tblimages WHERE tblimages.postID=?")){
			mysqli_stmt_bind_param($stmt2, 'i', $blog);
			if(!mysqli_stmt_execute($stmt2)){
				$count++;
				$feedback.="<br/> Blogpost images could not be deleted.";
			}
			mysqli_stmt_close($stmt2);
		}//end of delete images
		
		if($stmt3=mysqli_prepare($mysqli,
		//delete confirmation
		"DELETE FROM tblconfirmedposts WHERE tblconfirmedposts.postID=?")){
			mysqli_stmt_bind_param($stmt3, 'i', $blog);
			if(!mysqli_stmt_execute($stmt3)){
				$count++;
				$feedback.="<br/> Blogpost confirmation could not be deleted.";
			}
			mysqli_stmt_close($stmt3);
		}//end of delete confirmation
		
		//due to the complicated nature of the blogposts, I have to get the comment information for the blogposts if any and delete those too
		//get comment Id's
		if($stmt4=mysqli_prepare($mysqli, 
		"SELECT tblblogcomments.commentID
		FROM tblblogcomments
		WHERE tblblogcomments.postID=?")){
			//bind post ID for the query
			mysqli_stmt_bind_param($stmt4, "i", $blog);
			
			//execute query
			if(mysqli_stmt_execute($stmt4)){
				//get results
				$result=mysqli_stmt_get_result($stmt4);
			
				while($row=mysqli_fetch_array($result, MYSQLI_NUM))
				{
					foreach ($row as $r)
					{
						$commentID[$commentCount]=$r;
						$commentCount++;
						
					}
				}//end of while loop
				mysqli_stmt_close($stmt4);
			}//end of if
		}//end of stmt
		
		if(count($commentID)!=0){
			
			foreach($commentID as $comment){
				//delete the record that maintain which blogger commented on which post
				if($stmt=mysqli_prepare($mysqli,
				"DELETE FROM tblbloggercomments WHERE tblbloggercomments.commentID=?")){
					mysqli_stmt_bind_param($stmt, 'i', $comment);
					if(!mysqli_stmt_execute($stmt)){
						$count++;
						$feedback.="<br/> Comments could not be deleted.";
					}
					mysqli_stmt_close($stmt);
				}//end of delete confirmation

				if($stmt3=mysqli_prepare($mysqli, 
				//delete any reports
				"DELETE FROM tblcommentreport WHERE tblcommentreport.commentID=?")){
					mysqli_stmt_bind_param($stmt3, 'i', $comment);
					
						if(!mysqli_stmt_execute($stmt3)){
							$count++;
							$feedback.="<br/> An error occured with deleting the user's comment report. Please contact a technician for assistance.";
						}
					mysqli_stmt_close($stmt3);
				}//end of stmt
			}//end of foreach
		}//end of if
		
		//final delete on comments 
		if($stmt5=mysqli_prepare($mysqli,
		"DELETE FROM tblblogcomments WHERE tblblogcomments.postID=?")){
			mysqli_stmt_bind_param($stmt5, 'i', $blog);
			if(!mysqli_stmt_execute($stmt5)){
				$count++;
				$feedback.="<br/> User comments could not be deleted.";
			}
			mysqli_stmt_close($stmt5);
		}//end of delete confirmation
		
		//delete blogpost
			if($stmt6=mysqli_prepare($mysqli,
			"DELETE FROM tblblogpost WHERE tblblogpost.postID=?")){
			mysqli_stmt_bind_param($stmt6, 'i', $blog);
			if(mysqli_stmt_execute($stmt6)){
				$success.="Blogpost deleted successfully!";
			}else{
				$count++;
				$feedback.="<br/>User Blogposts could not be deleted.";
			}
			mysqli_stmt_close($stmt6);
		}//end of delete confirmation
	
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
  
  <!--Quill Stuff-->
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

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
          <li><a href="userAccount.php">Your Account</a></li>
        </ol>
		<?php
		
			echo"<h2>$uName's Account</h2>";
		
		?>
		
		<h6>
			This is your account, you can change your personal information here. 
			All information is kept secure and its completely optional to provide any kind of information as well, 
			if you would prefer to maintain anonymity.
		</h6>

      </div>
	  
    </section><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="userInfo">
      
	  <!--Content Here :0-->
	  <div class="container">
		  <h3>Your Information: </h3>
		  <?php
		  
		global $feedback;
		global $count; 
		global $success;
		
		 if($feedback != ""){
	 
		 echo "<div class= 'alert alert-danger container'>"; 
		   if ($count == 1) echo "<strong>$count error found.</strong>";
		   if ($count > 1) echo "<strong>$count errors found.</strong>";
		 echo "$feedback
		   </div>";
		}//end of error code
		
		//this is feedback code for success messages
		if($success != ""){
			 echo "<div class= 'alert alert-success container'>"; 
			 echo "$success
			   </div>";
		}//end of if statement for error
			
		  
		  //get description from db if any. 
		  require("dbConnect.php");
		  
		  if($stmt=mysqli_prepare($mysqli, "SELECT tbluser.description, tbluser.pictureID, tbluser.email FROM tbluser WHERE userID=?")){
				  //bind entered parameters to mysqli object
				mysqli_stmt_bind_param($stmt, "i", $uID);
				
				//execute the stmt
				mysqli_stmt_execute($stmt);
				
				//get results of query
				mysqli_stmt_bind_result($stmt, $description, $picture, $email);
					$description = str_ireplace(array("\r","\n",'\r','\n'),'', $description);
					if(mysqli_stmt_fetch($stmt)){
							/*Echo user profile information*/
							echo"<div class='row'>";
								//username
								echo"<div class='col-lg-6'> <h4>Your Username:</h4> <h5>$uName</h5>";
								
								//Contact
								echo"<br/><h4>Contact:</h4>";
								if($email!=null || $email!=""){					
									echo"<h5>$email</h5>";
								}else{
									echo"<h5>You have not set a contact email, you can set one if you would like!</h5>";
								}
								
								//Description
								echo"<br/><h4>Description:</h4>";
								if($description!=null || $description!=""){					
									echo"<h5>$description</h5></div>";
								}else{
									echo"<h5>You have not set a description, you can set one if you would like!</h5></div>";
								}
								
								//Image
								echo"<div class='col-lg-6'><h4>Avatar:</h4>";
								if($picture!=null || $picture!=""){
									echo"<img src='accountAvatar/$picture' style='width:300px; height:200px;'/>";
								}else{
									echo"<h5>You have not set an avatar, you can set one if you like!</h5>";
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
		  ?>
		  <br/>
		  <div class="col-sm-12">
			<?php 
			if(isset($_SESSION['uID'])){$uID=$_SESSION['uID'];};
			echo"<a href='editDescription.php?uID=$uID'> <button class='btn btn-outline-primary form-control sincerely'>Edit Account Details</button></a>"; ?>
			<br/>
		</div>
		
		<hr/>
		<div>
		
			<h4>Your Blogposts:</h4>
			<br/>
			<h5>These are all the blogposts you have made, you can see the verification status of your blogposts here!</h5>
			
			<?php
				//initalize all variables
				$postID=0;
				$content="";
				$heading="";
				$postDate="";
				$status="";
				$reason="";
				
				include('dbConnect.php');
				
				//this query is to get all of the user's posts and display them.
				if($stmt=mysqli_prepare($mysqli, 
				"SELECT tblblogpost.postID, tblblogpost.content, tblblogpost.heading, tblblogpost.postDate, tblconfirmedposts.confirmed, tblconfirmedposts.reason
				FROM tblblogpost, tblconfirmedposts
				WHERE tblblogpost.postID=tblconfirmedposts.postID
				AND tblblogpost.userID=?")){
					//bind parameters
					mysqli_stmt_bind_param($stmt, 'i', $uID);
					
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
							<th><h5>Body</h5></th>
							<th><h5>Date/Time</h5></th>
							<th><h5>Verification Status</h5></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					";
					
					//fetch and display results
					while (mysqli_stmt_fetch($stmt)){
						  $heading=substr($heading,0,25);
					        $content=substr($content,0,25);
					        $content=strip_tags($content);
					    	$content = str_ireplace(array("\r","\n",'\r','\n'),'', $content);
							$postDate=date('h:i:s a m/d/Y', strtotime($postDate));
						echo"
							
						<tr>	
						<td><a href='viewBlogSingle.php?postID=$postID&status=$status'>$heading</a></td>
						<td>$content</td>
						<td>$postDate</td>
						<td>"; if($status==1)
						{echo"<p>Verified</p>";}
						else if($status==2){echo "<p class='error'>Post Denied</p> <p class='error'>Reason: $reason</p>";} 
						else {echo"<p>Pending</p>";}echo"</td>
						<td><a class='btn btn-outline-primary sincerely' href='editPost.php?postID=$postID'>Edit Post</a></td>
						
						<td><form method='get' action='userAccount.php' onsubmit='return blogConfirmation(this)'>	
						<input type='hidden' value='$postID' name='postID' />
						<input type='submit' class='btn btn-outline-primary form-control sincerely'
						name='deletePost' value='Delete Post'/>
						</form></td>
						</tr>";
						
					}//end of while
					echo"</tbody></table>";		
					mysqli_stmt_close($stmt);
				}//end of stmt
				mysqli_close($mysqli);
			
			
			
			?>
		
		</div>
		
		<hr/>
			<br/>
			<form class="form-horizonal" action="deleteUser.php" method="post" onsubmit="return confirmation(this)">
				<div class="col-sm-12">
					
					<input type="hidden" name="userID" value="<?php global $uID; echo $uID; ?>"/>
					
					<input type="hidden" name="page" value="login.php"/>
					
					<input type="submit" class="btn btn-outline-primary form-control sincerely" 
					name="deleteUser" value="Delete Account"/>
				</div>
			</form>
		  
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
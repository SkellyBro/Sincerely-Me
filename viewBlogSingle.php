<?php
	namespace DBlackborough\Quill;
	session_start();
	require_once 'vendor/autoload.php';
	
	if($_SESSION['uName']==""){
		
		Header("Location:login.php?feedback=You must be logged in to access this page...");
		
	}
	
	if(isset($_GET['postID'])){
		$_SESSION['postID']=$_GET['postID'];
	}else{
		$postID=$_SESSION['postID'];
	}
	
	//uID from session
	if(isset($_SESSION['uID'])){
		$uID=$_SESSION['uID'];
	}
	
	/*Error Variables*/
	$count=0;
	$success="";
	$feedback="";
	$commentID=0;
	
	//comment submit
	if(isset($_POST['submit'])){
		$quill_json=$_POST['quill_json'];
		$post=$_POST['postID'];
		
		
		//create new date object for the insert into the database
		$date=date('Y-m-d H:i:s');
		
		//validate post data
		if($quill_json=="" || $quill_json==null){
			$count++;
			$feedback.="<br/> Your post cannot be empty.";
		}else if(strlen($quill_json)<20){
			$count++;
			$feedback.="<br/> Your post cannot be less than 20 characters.";
		}else if($quill_json=='{"ops":[{"insert":"\n"}]}'){
			$count++;
			$feedback.="<br/> Your post cannot be empty.";
		}	
		
		if($count==0){
			//magic code that renders the quill delta into readable text
			try {
				$quill = new \DBlackborough\Quill\Render($quill_json, 'HTML');
				$result = $quill->render();
			} catch (\Exception $e) {
				echo $e->getMessage();
			}
			
			//validate post data
			if($result=="" || $result==null){
				$count++;
				$feedback.="<br/> Your post cannot be empty.";
			}else if(strlen($result)<20){
				$count++;
				$feedback.="<br/> Your post cannot be less than 20 characters.";
			}	
			
			//include database connections
			include('dbConnect.php');
				
			//sanitize data
			$result= filter_var($result, FILTER_SANITIZE_STRING); 
			
			//sanitize data going into MySQL
			$result= mysqli_real_escape_string($mysqli, $result);
				
			if($count==0){	
				//insert comment
				insertComment($result, $date, $post);
				
				//getID
				getCommentID($result, $post);
				
				//insert into blogger comments
				insertBloggerComments($commentID, $uID);
			}//end of count
		}//end of count
	}//end of isset
	
	function insertBloggerComments($commentID, $uID){
		global $feedback;
		global $count; 
		global $success;
		
		//connect to dbConnect
		include('dbConnect.php');
		
		//make insert 
		if($stmt=mysqli_prepare($mysqli, 
		"INSERT INTO tblbloggercomments(userID, commentID) VALUES(?, ?)")){
			//bind params
			mysqli_stmt_bind_param($stmt, "ii", $uID, $commentID);
			
			//execute
			if(mysqli_stmt_execute($stmt)){
				$success.="<br/> Comment Made Successfully!";
			}else{
				$count++;
				$feedback.="<br/> Blog comment insert failed. Please contact an administrator or technician for assistance.";
			}
			mysqli_stmt_close($stmt);
		}//end of stmt
		mysqli_close($mysqli);
	}//end of insertBloggerComments
	
	function getCommentID($result, $post){
		global $feedback;
		global $count;
		global $success;
		global $commentID;
		
		include('dbConnect.php');
		
		if($stmt=mysqli_prepare($mysqli, 
		"SELECT tblblogcomments.commentID FROM tblblogcomments WHERE tblblogcomments.content=? AND tblblogcomments.postID=?")){
			//bind params
			mysqli_stmt_bind_param($stmt, "si", $result, $post);
			
			//execute query
			mysqli_stmt_execute($stmt);
			
			//bind result
			mysqli_stmt_bind_result($stmt, $commentID);
			
			//fetch result
			if(mysqli_stmt_fetch($stmt)){
				return true; 
			}else{
				$count++;
				$feedback.="<br/> Could not get comment ID.";
				return false;
			}
			mysqli_stmt_close($stmt);
		}//end of stmt
		mysqli_close($mysqli);
		
	}//end of get comment ID
	
	/*this method is to insert the comment in the database
	$result is the entered user script
	$date is the datetime of the comment
	$post is the postid for the comment
	*/
	function insertComment($result, $date, $post){
		global $feedback;
		global $count; 
		global $success;
		
		include('dbConnect.php');
		
		//make insert into database
			if($stmt=mysqli_prepare($mysqli,
			"INSERT INTO tblblogcomments(content, postDate, postID) VALUES(?, ?, ?)")){
				//bind all the parameters
				mysqli_stmt_bind_param($stmt, "ssi", $result, $date, $post);
				
				//execute query
				if(mysqli_stmt_execute($stmt)){
					return true;
				}else{
					$count++;
					$feedback.="<br/> A problem with creating your comment, please contact a technician for assistance.";
				}
				mysqli_stmt_close($stmt);
			}//end of stmt
			mysqli_close($mysqli);
	}//end of insertComment

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
  
  <!--Quill Link-->
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>

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
          <li><a href="viewBlogSingle.php">View Blog</a></li>
        </ol>
        <h2>Blogpost</h2>

      </div>
	  
    </section><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="blog" class="blog">
      
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
	  $imageName="";
	  $desc="";
	  
	  if(isset($_SESSION['postID'])){
		  $postID=$_SESSION['postID'];
	  }
	 
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
		"SELECT tbluser.userID, tbluser.username, tblblogpost.heading, tblblogpost.postDate, tblblogpost.content, tbluser.pictureID, tbluser.description
		FROM tbluser, tblblogpost
		WHERE tbluser.userID=tblblogpost.userID
		AND tblblogpost.postID=?"))
		{
			//bind postID for the query
			mysqli_stmt_bind_param($stmt, "i", $postID);
			
			//execute query
			mysqli_stmt_execute($stmt);
			
			//get results
			mysqli_stmt_bind_result($stmt, $userID, $username, $heading, $postDate, $content, $imageName, $desc);
			
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
							  if($imageCount>=1 && $image[0]!=null){
								  echo" <img src='blogImages/$image[0]' class='img-fluid'>";
							  }else if($imageCount==1 && $image[0]!=null){
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
								  <li class='d-flex align-items-center'><i class='icofont-user'></i> <a href='viewBlogSingle.php'>$username</a></li>
								  <li class='d-flex align-items-center'><i class='icofont-wall-clock'></i> <a href='viewBlogSingle.php'><time datetime='2020-01-01'>$postDate</time></a></li>
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
							  <div class='col-lg-12'>
									  <div class='blog-author clearfix'>";
									  
									  if($imageName==null){
										  echo"<img src='accountAvatar/default.png' class='rounded-circle float-left'>";
										  echo"<p>Icon made by Freepik from www.flaticon.com</p>";
									  }else{
										  echo" <img src='accountAvatar/$imageName' class='rounded-circle float-left'>";
									  }
									  
									 
									  echo"
									  <h4>$username</h4>
										<p>
											$desc
										</p>
									</div><!-- End blog author bio -->
								</div>
							 <div>
							</article><!-- End blog entry -->
							</div>
						</div>
					
					";//end of article echoing
				}else{
					echo"<div class='container'><h4 class='error'>An error occured, cannot connect to database.</h4></div>";
				}
			}//end of if-stmt
			

			
			
			echo"
			<div class='container'>
			 <div class='reply-form'>
                <h4>Leave a Reply</h4>";
				
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
				
				
					//create variables
		$commentContent="";
		$commentDate="";
		$commentUser="";
		$commentPicture="";
		
		//get comments
		include('dbConnect.php');
		
		if($stmt=mysqli_prepare($mysqli, 
		"SELECT tblblogcomments.content, tblblogcomments.postDate, tbluser.username, tbluser.pictureID
		FROM tblbloggercomments, tblblogcomments, tbluser
		WHERE tblblogcomments.commentID= tblbloggercomments.commentID
		AND tblbloggercomments.userID=tbluser.userID
		AND tblblogcomments.postID=?")){
			//bind params
			mysqli_stmt_bind_param($stmt, "i", $postID);
			
			//execute
			mysqli_stmt_execute($stmt);
			
			//result
			mysqli_stmt_bind_result($stmt, $commentContent, $commentDate, $commentUser, $commentPicture);
			
			//fetch results
			if(mysqli_stmt_fetch($stmt)){
				echo"
				<div class='blog-comments'>
					<div class='comment clearfix'>";
					if($commentPicture!=null){
						echo"
							<img src='accountAvatar/$commentPicture' class='comment-img  float-left'>
						";
					}else{
						 echo" <img src='accountAvatar/default.png' class='comment-image rounded-circle float-left'>";
						  echo"<p>Icon made by Freepik from www.flaticon.com</p>";
					}
					
					echo"
					<h5><a href='viewBlogSingle.php'>$username</a></h5>
					<time datetime='2020-01-01'>$commentDate</time>
					<p>
					  $commentContent
					</p>

				  </div>
			  </div>";
			}else{
				echo"<h6 class='error'>No Comments Available</h6>";
			}
		}//end of if-stmt

				
				echo"
                <form id='target' class='form-horizontal' action='viewBlogSingle.php' method='post'>

				<div id='editor'>
					
				</div>
				<!--This is needed to store the information that the user enters into the rich text area.-->
				<input type='hidden' id='quill_json' name='quill_json' aria-labelledby='blog writing area'/>
				<input type='hidden' name='postID' value='$postID'/>
                  <input type='submit' class='btn btn-outline-primary form-control sincerely' name='submit' value='Submit'/>

                </form>
			</div>";
		?>		
				<!-- Initialize Quill editor -->
		<script>
		//These are the options for the toolbar
			var toolbarOptions = [
			  ['bold', 'italic', 'underline', 'strike'],        // toggled buttons

			  [{ 'header': 1 }, { 'header': 2 }],               // custom button values
			  [{ 'list': 'ordered'}, { 'list': 'bullet' }],
			  [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript                

			  [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

			  ['clean']                                         // remove formatting button
			];
		
			//this creates the actual rich text area
			var quill = new Quill('#editor', {
			  modules: {
				toolbar: toolbarOptions
			  },
			  theme: 'snow'
			});	
			
		  //this gets the data from the rich text area
		  $('#target').submit(function() {
			$('#quill_json').val(JSON.stringify(quill.getContents()));
			return true;
		});
		  
		</script>
		<br/>
		<a href="index.php"><button class="btn btn-outline-primary form-control sincerely">Back to home</button></a>
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
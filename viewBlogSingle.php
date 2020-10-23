<?php

	session_start();
	ob_start();

	
	if($_SESSION['uName']==""){
		
		Header("Location:login.php?feedback=You must be logged in to access this page...");
		
	}
	
	//get blogpost ID and store it in the session should the page reload and the postID lost.
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
		//get the comment body and the blogpost ID
		$body=$_POST['body'];
		$post=$_POST['postID'];
		
		//create new date object for the insert into the database
		$date=date('Y-m-d H:i:s');
		
		$dt = new DateTime("now", new DateTimeZone('America/Guyana'));
		
		$date = $dt->format('Y-m-d H:i:s');
		
		//validate post data
		if($body=="" || $body==null){
			$count++;
			$feedback.="<br/> Your post cannot be empty.";
		}
		
		if($count==0){
			
			//include database connections
			include('dbConnect.php');
				
			//sanitize data
			//$result= filter_var($result, FILTER_SANITIZE_STRING); 
			
			//sanitize data going into MySQL
			$body= mysqli_real_escape_string($mysqli, $body);
				
			if($count==0){	
				//insert comment
				insertComment($body, $date, $post);
				
				//getID
				getCommentID($body, $post);
				
				//insert into blogger comments
				insertBloggerComments($commentID, $uID);
			}//end of count
		}//end of count
	}//end of isset
	
	/*This submits the comment into the tblbloggercomments that keeps track of what user made what blogpost*/
	function insertBloggerComments($commentID, $uID){
		global $feedback;
		global $count; 
		global $success;
		
		//get user position
		if(isset($_SESSION['position'])){
			$pos=$_SESSION['position'];
		}
		
		//connect to dbConnect
		include('dbConnect.php');
		
		//make insert 
		if($stmt=mysqli_prepare($mysqli, 
		"INSERT INTO tblbloggercomments(userID, commentID, userPosition) VALUES(?, ?, ?)")){
			//bind params
			mysqli_stmt_bind_param($stmt, "iii", $uID, $commentID, $pos);
			
			//execute
			if(mysqli_stmt_execute($stmt)){
				$success="Comment Made Successfully!";
			}else{
				$count++;
				$feedback.="<br/> Blog comment insert failed. Please contact an administrator or technician for assistance.";
			}
			mysqli_stmt_close($stmt);
		}//end of stmt
		mysqli_close($mysqli);
	}//end of insertBloggerComments
	
	/*This gets the ID of the recently made comment
	*$body is the body of the comment
	*$post is the postID
	*/
	function getCommentID($body, $post){
		global $feedback;
		global $count;
		global $success;
		global $commentID;
		
		include('dbConnect.php');
		
		if($stmt=mysqli_prepare($mysqli, 
		"SELECT tblblogcomments.commentID FROM tblblogcomments WHERE tblblogcomments.content=? AND tblblogcomments.postID=?")){
			//bind params
			mysqli_stmt_bind_param($stmt, "si", $body, $post);
			
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
	$body is the entered user script
	$date is the datetime of the comment
	$post is the postid for the comment
	*/
	function insertComment($body, $date, $post){
		global $feedback;
		global $count; 
		global $success;
		
		include('dbConnect.php');
		
		//make insert into database
			if($stmt=mysqli_prepare($mysqli,
			"INSERT INTO tblblogcomments(content, postDate, postID) VALUES(?, ?, ?)")){
				//bind all the parameters
				mysqli_stmt_bind_param($stmt, "ssi", $body, $date, $post);
				
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
	
	//this gets the comment ID and deletes the user's comment 
	if(isset($_GET['commentID'])){
		$cID= $_GET['commentID'];
		
		//simple validation
		if($cID==0 || $cID==null){
			$count++;
			$feedback.="<br/> Comment ID not found for delete.";
		}
		
		if($count==0){
		include('dbConnect.php');
		
		//this deletes the record from the table responsible for keeping track of what user made what blogpost
		if($stmt=mysqli_prepare($mysqli, "DELETE FROM tblbloggercomments WHERE commentID=?")){
		mysqli_stmt_bind_param($stmt, 'i', $cID);
			
			if(!mysqli_stmt_execute($stmt)){
				$count++;
				$feedback.="<br/> An error occured with deleting the user's comment. Please contact a technician for assistance.";
				printf("Error #%d: %s.\n", mysqli_stmt_errno($stmt), mysqli_stmt_error($stmt));
			}
			mysqli_stmt_close($stmt);
		}//end of stmt
		
		//this deletes the record from the table responsible for storing any reports bound to the comment
		if($stmt2=mysqli_prepare($mysqli, "DELETE FROM tblcommentreport WHERE commentID=?")){
		mysqli_stmt_bind_param($stmt2, 'i', $cID);
				
			if(!mysqli_stmt_execute($stmt2)){
				$count++;
				$feedback.="<br/> An error occured with deleting the user's report. Please contact a technician for assistance.";
				printf("Error #%d: %s.\n", mysqli_stmt_errno($stmt2), mysqli_stmt_error($stmt2));
			}
		mysqli_stmt_close($stmt2);
		}//end of stmt
		
		//this deletes the actual comment
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
		
	}//end of isset
	
	//this handles the deletion of the user's blogpost.
	if(isset($_POST['deletePost'])){
		$blog=$_POST['postID'];
		
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
				$success.="Blogpost deleted successfully! You will be returned to the home screen shortly..";
			}else{
				$count++;
				$feedback.="<br/>User Blogposts could not be deleted.";
			}
			mysqli_stmt_close($stmt6);
		}//end of delete confirmation
		
		header("refresh:2; url=index.php" );
		
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
  
   <!--ckeditor Link-->
   <script src="ckeditor/ckeditor.js"></script>

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
	
	//init variables for the blogpost display
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
	  $status=0;
	  
	  //get post ID
	  if(isset($_SESSION['postID'])){
		  $postID=$_SESSION['postID'];
	  }
	 
	//this is just a rehash of the data from the blogpost preview 
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
		"SELECT tbluser.userID, tbluser.username, tblblogpost.heading, tblblogpost.postDate, tblblogpost.content, tbluser.pictureID, tbluser.description, tblconfirmedposts.confirmed
		FROM tbluser, tblblogpost, tblconfirmedposts
		WHERE tbluser.userID=tblblogpost.userID
        AND tblblogpost.postID=tblconfirmedposts.postID
		AND tblblogpost.postID=?"))
		{
			//bind postID for the query
			mysqli_stmt_bind_param($stmt, "i", $postID);
			
			//execute query
			mysqli_stmt_execute($stmt);
			
			//get results
			mysqli_stmt_bind_result($stmt, $userID, $username, $heading, $postDate, $content, $imageName, $desc, $status);
			
			//fetch results
			if(mysqli_stmt_fetch($stmt)){
				$content = str_ireplace(array("\r","\n",'\r','\n'),'', $content);
				$postDate=date('h:i:s a m/d/Y', strtotime($postDate));
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
								<div class='wrapword'><a href='confirmPost.php'>$heading</a></div>
							  </h2>

							  <div class='entry-meta'>
								<ul>
								  <li class='d-flex align-items-center'><i class='icofont-user'></i> <a href='viewBlogSingle.php'>$username</a></li>
								  <li class='d-flex align-items-center'><i class='icofont-wall-clock'></i> <a href='viewBlogSingle.php'><time datetime='2020-01-01'>$postDate</time></a></li>
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
										<li><a href='searchResults.php?search=$tags[$x]'>$tags[$x]</a></li>
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
									  
								if(isset($_SESSION['uName'])){
									if($_SESSION['uName']==$username){
											echo"<h4> <a href='userAccount.php'>$username</a></h4>";
										}else{
											echo"<h4> <a href='viewUserProfile.php?userID=$userID&uName=$username'>$username</a></h4>";
										}
								}else{
									echo"<h4><a href='viewUserProfile.php?userID=$userID&uName=$username'>$username</a></h4>";
								}
									echo"<p>
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
			
			
			
			if($_SESSION['position']==2){
				echo"
				<div class='container'>
				<form method='post' action='viewBlogSingle.php' onsubmit='return blogConfirmation(this)'>
					
					<input type='hidden' value='$postID' name='postID'/>
				
					<input type='submit' class='btn btn-outline-primary form-control sincerely'
					name='deletePost' value='Delete User Post'/>
				</form>
				</div>
				<br/>
				";
			}//end of if

			
		//beginning of comments
		//the code below here handles the commenting feature for the blogpost
		echo"
		<div class='container'>
		<div class='reply-form'>
		<h4>Leave a Reply</h4>";
		
		//create variables
		$commentContent="";
		$commentDate="";
		$commentUser="";
		$commentPicture="";
		$commentUserID="";
		$commentID=0;
		$commentUserPosition=0;
		
		//This code would get any previously made comments and append them to the blogpost.
		include('dbConnect.php');
		
		if($stmt=mysqli_prepare($mysqli, 
		"SELECT tblblogcomments.commentID, tblblogcomments.content, tblblogcomments.postDate, tbluser.username, tbluser.pictureID, tbluser.userID, tblbloggercomments.userPosition
		FROM tblbloggercomments, tblblogcomments, tbluser
		WHERE tblblogcomments.commentID= tblbloggercomments.commentID
		AND tblbloggercomments.userID=tbluser.userID
		AND tblblogcomments.postID=?
		ORDER BY tblblogcomments.postDate ASC")){
			//bind params
			mysqli_stmt_bind_param($stmt, "i", $postID);
			
			//execute
			mysqli_stmt_execute($stmt);
			
			//result
			mysqli_stmt_bind_result($stmt, $commentID, $commentContent, $commentDate, $commentUser, $commentPicture, $commentUserID, $commentUserPosition);
			
			//fetch results
			while(mysqli_stmt_fetch($stmt)){
				$commentDate=date('h:i:s a m/d/Y', strtotime($commentDate));
				$commentContent = str_ireplace(array("\r","\n",'\r','\n'),'', $commentContent);
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
					
					//this is a small check to ensure that if a user clicks on their own username, they're send to the userAccount page instead of the viewUserProfile page
					if(isset($_SESSION['uName'])){
						if($_SESSION['uName']==$commentUser){
								echo"<h5> <a href='userAccount.php'>$commentUser</a></h5>";
							}else{
								echo"<h5> <a href='viewUserProfile.php?userID=$commentUserID&uName=$commentUser'>$commentUser</a></h5>";
							}
					}else{
						echo"<h5><a href='viewUserProfile.php?userID=$commentUserID&uName=$commentUser'>$commentUser</a></h5>";
					}
					echo"<time datetime='2020-01-01'>$commentDate</time>
					
					<div class='row'>
					
					<div class='col-sm-10'>
					<div class='wrapword'>$commentContent</div>
					
					</div>";
					

					//if the user is an admin they can delete a comment
					if($_SESSION['position']==2){
						
						if($_SESSION['uName']==$commentUser){
							echo"
							<div class='col-sm-2 row'>
							<a href='viewBlogSingle.php?commentID=$commentID'><sub>Delete Comment</sub></a>
							</div>
							";
						}else{
							echo"
							<div class='col-sm-2 row'>
							<a href='reporting.php?commentID=$commentID'><sub>Report Comment</sub></a>
							<br/>
							<a href='viewBlogSingle.php?commentID=$commentID'><sub>Delete Comment</sub></a>
							</div>";
						}
						
					}
					
					//regular bloggers cannot delete comments
					else if($_SESSION['uName']!=$commentUser && $commentUserPosition!=2){
						echo"
						<div class='col-sm-2'>
						<a href='reporting.php?commentID=$commentID'><sub>Report Comment</sub></a>
						</div>";
					}
					
					
					echo"
					</div>
				  </div>
				  <hr/>
			  </div>";
			}//end of while
			mysqli_stmt_close($stmt);
			mysqli_close($mysqli);
		}//end of if-stmt
		//this is a check to ensure that the blogpost is confirmed so that users can comment on it.
		global $status;
				if($status==1){
					echo"
						<form id='target' class='form-horizontal' action='viewBlogSingle.php' method='post'>

						<small>You can use emotes when you're making your post, click on the right, next to the omega (Ω) symbol.</small>
						<!--This is needed for the rich text area-->
						<textarea id='editor' name='body'></textarea>

						<!--This is needed to store the information that the user enters into the rich text area.-->
						<input type='hidden' id='quill_json' name='quill_json' aria-labelledby='blog writing area'/>
						<input type='hidden' name='postID' value='$postID'/>
						  <input type='submit' class='btn btn-outline-primary form-control sincerely' name='submit' value='Submit'/>

						</form>
					</div>";
				}else{
					echo"<h5>Post not verified, commenting not allowed.</h5>";
				}
				
		?>		
				<!-- Initialize Quill editor -->
		<script>
		CKEDITOR.replace( 'editor' );
		//This is some neat little code to prevent a form resubmission if you reload the page
		/*
		Code Author: dtbaker
		Code Accessed On:23/06/2020
		Code Source: https://stackoverflow.com/questions/6320113/how-to-prevent-form-resubmission-when-page-is-refreshed-f5-ctrlr
		
		*/
		  if ( window.history.replaceState ) {
				window.history.replaceState( null, null, window.location.href );
			}
		  
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
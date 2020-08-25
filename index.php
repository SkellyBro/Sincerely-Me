<?php
/*This is the homepage for the website*/
//session start
session_start();
ob_start();
//set up error variables
$count=0;
$feedback="";	
	//this handles some of the search for the system. 
	if(isset($_POST['search'])){
		$search=$_POST['search'];
		global $count;
		global $feedback;
		
		//validate and sanitize search string
		if($search=="" || $search==null){
			$count++;
			$feedback.="<br/> You haven't entered anything to search!";
		}else if(!preg_match("/\\w|\\s+/", $search)){
			$count++;
			$feedback.="<br/> No special characters allowed!";
		}else if(strlen($search)<2){
			$count++;
			$feedback.="<br/> Your search string cannot be less than 2 characters.";
		}else if(strlen($search)>200){
			$count++;
			$feedback.="<br/> Your search string cannot be more than 200 characters.";
		}
		
		if($count==0){
			//sanitize
			$search= filter_var($search, FILTER_SANITIZE_STRING);
			
			include('dbConnect.php');
			$search= mysqli_real_escape_string($mysqli, $search);
			
			header('Location:searchResults.php?search='.$search);
		}
		
	}//end of search isset
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

<!--Stuff for AJAX-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

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
			//this gets the daily message from the admin.
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
					$pMessage = str_ireplace(array("\r","\n",'\r','\n'),'', $pMessage);
					echo"<h6>$pMessage</h6>";
					
					echo"<div class='row col-sm-12'>
							
							<div class='col-sm-6'>--$username</div>			
						
						</div>";
						
						echo"<br/><small>Reminder: If you feel like you're in need of therapy, you can create a booking with Ms. Antonia Mootoo at <a href='https://theracoconsultants.com/'>theracoconsultants.com</a></small>";
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
		  //display notifications
		  /*Users can get notifications if:
		  *Their blogpost has been confirmed
		  *Their blogpost has been denied
		  *Their blogpost has been commented on
		  
		  Which means I need to do three sql queries and do some cool stuff. Oy vey.
		  */
		  
			if(isset($_SESSION['uID'])){
			  $uID=$_SESSION['uID'];
			  
			  $messages="";
			  $comments="";
			  $output="";
			  $heading="";
			  $commentReport="";
			  $messageReport="";
			  $blogpostConfirmations="";
			  //this query would get the blogpost confirmation notifications
			  include('dbConnect.php');
			  if($stmt2=mysqli_prepare($mysqli, "SELECT tblblogpost.heading, tblblogpost.postID, tblblogpost.postDate
				FROM tblblogpost, tblconfirmedposts, tbluser
				WHERE tblblogpost.postID=tblconfirmedposts.postID
				AND tblblogpost.userID=tbluser.userID
				AND tbluser.userID=?
				AND tblconfirmedposts.confirmed=1
				AND tbluser.position=1
				ORDER By tblblogpost.postDate DESC LIMIT 2")){
					mysqli_stmt_bind_param($stmt2, 'i', $uID);
					
					mysqli_stmt_execute($stmt2);
					
					//get results
					$result=mysqli_stmt_get_result($stmt2);
					
					while($row=mysqli_fetch_array($result))
					{
						
					  $output .= '
					  <li>
					  <a href="viewBlogSingle.php?postID='.$row["postID"].'">
					  <strong>'.$row["heading"].' has been confirmed</strong><br />
					  <small><em>'.$row["postDate"].'</em></small>
					  </a>
					  </li>
					  ';
				   
					}//end of while loop
					mysqli_stmt_close($stmt2);
				}//end of stmt
				mysqli_close($mysqli);
				
				//this query would get the blogpost denial notifications
				 include('dbConnect.php');
				if($stmt3=mysqli_prepare($mysqli, "SELECT tblblogpost.heading, tblblogpost.postDate
				FROM tblblogpost, tblconfirmedposts, tbluser
				WHERE tblblogpost.postID=tblconfirmedposts.postID
				AND tblblogpost.userID=tbluser.userID
				AND tbluser.userID=?
				AND tblconfirmedposts.confirmed=2
				AND tbluser.position=1
				ORDER By tblblogpost.postDate DESC LIMIT 2")){
					mysqli_stmt_bind_param($stmt3, 'i', $uID);
					
					mysqli_stmt_execute($stmt3);
					
					//get results
					$result=mysqli_stmt_get_result($stmt3);
					
					while($row=mysqli_fetch_array($result))
					{
						
					  $output .= '
					  <li>
					  <a href="userAccount.php">
					  <strong>'.$row["heading"].' has been rejected by admins.</strong><br />
					   <small><em>'.$row["postDate"].'</em></small>
					  </a>
					  </li>
					  ';
				   
					}//end of while loop
					mysqli_stmt_close($stmt3);
				}//end of stmt
				mysqli_close($mysqli);
				
				//this query would get the blogpost comment notifications
				 include('dbConnect.php');
				if($stmt4=mysqli_prepare($mysqli, 
				"SELECT tbluser.username, tblblogpost.postID, tblblogpost.heading, tblblogpost.postDate
				FROM tbluser, tblblogcomments, tblbloggercomments, tblblogpost, tblblogger
				WHERE tblblogpost.postID=tblblogcomments.postID
				AND tblblogcomments.commentID=tblbloggercomments.commentID
				AND tblbloggercomments.userID=tblblogger.userID
				AND tblblogger.userID=tbluser.userID
				AND tblblogpost.userID=?
				ORDER By tblblogpost.postDate DESC LIMIT 5")){
					mysqli_stmt_bind_param($stmt4, 'i', $uID);
					
					mysqli_stmt_execute($stmt4);
					
					//get results
					$result=mysqli_stmt_get_result($stmt4);
					
					while($row=mysqli_fetch_array($result))
					{
						
					  $comments .= '
					  <li>
					  <a href="viewBlogSingle.php?postID='.$row["postID"].'">
					  <strong>'.$row["username"].' commented on "'.$row["heading"].'"</strong><br />
					  <small><em>'.$row["postDate"].'</em></small>
					  </a>
					  </li>
					  ';
				   
					}//end of while loop
					mysqli_stmt_close($stmt4);
				}//end of stmt
				mysqli_close($mysqli);
				
				//this query would get the message notifications
				include('dbConnect.php');
				
				if($stmt5=mysqli_prepare($mysqli, 
				"SELECT tbluser.username, tbluser.userID, tblmessage.conversationID, tblmessage.messageDate, tblmessage.messageTitle, tblmessage.originalSender, tblmessage.originalRecipient
				FROM tbluser, tblmessage
				WHERE tbluser.userID=tblmessage.sender
				AND tblmessage.recipient=?
				ORDER BY tblmessage.messageDate DESC LIMIT 5")){
					mysqli_stmt_bind_param($stmt5, 'i', $uID);
					
					mysqli_stmt_execute($stmt5);
					
					//get results
					$result=mysqli_stmt_get_result($stmt5);
					
					while($row=mysqli_fetch_array($result))
					{
						
					  $messages .= '
					  <li>
					  <a href="messageReply.php?cID='.$row["conversationID"].'&title='.$row["messageTitle"].'&rID='.$row["userID"].'&oSender='.$row["originalSender"].'&oRecipient='.$row["originalRecipient"].'">
					  <strong>'.$row["username"].' sent a new message in conversation: '.$row["messageTitle"].'</strong><br />
					  <small><em>'.$row["messageDate"].'</em></small>
					  </a>
					  </li>
					  ';
				   
					}//end of while loop
					mysqli_stmt_close($stmt5);
				}//end of stmt
				mysqli_close($mysqli);
				
				//this is to display the count of reports for the admin
				if($_SESSION['position']==2){
					//this query would get the message notifications
					include('dbConnect.php');
					//get comment report notifications
					if($stmt=mysqli_prepare($mysqli, 
					"SELECT COUNT(tblcommentreport.reportID) AS Comment
					FROM tblcommentreport")){
						
						mysqli_stmt_execute($stmt);
						
						//get results
						$result=mysqli_stmt_get_result($stmt);
						
						while($row=mysqli_fetch_array($result))
						{
							
						  $commentReport .= '
						  <li>
						  <a href="adminReportMenu.php">
						  <strong>'.$row["Comment"].' new comments have been reported.</strong><br />
						  </a>
						  </li>
						  ';
					   
						}//end of while loop
						mysqli_stmt_close($stmt);
					}//end of stmt
					
					//get message report notifications
					if($stmt2=mysqli_prepare($mysqli, 
					"SELECT COUNT(tblmessagereport.reportID) AS Message
					FROM tblmessagereport")){
						
						mysqli_stmt_execute($stmt2);
						
						//get results
						$result=mysqli_stmt_get_result($stmt2);
						
						while($row=mysqli_fetch_array($result))
						{
							
						  $messageReport .= '
						  <li>
						  <a href="adminReportMenu.php">
						  <strong>'.$row["Message"].' new messages have been reported.</strong><br />
						  </a>
						  </li>
						  ';
					   
						}//end of while loop
						mysqli_stmt_close($stmt2);
					}//end of stmt
				
				//get blogpost confirmation notifications
					if($stmt3=mysqli_prepare($mysqli, 
					"SELECT COUNT(tblconfirmedposts.cPostID) AS posts
					FROM tblconfirmedposts
					WHERE tblconfirmedposts.confirmed=0")){
						
						mysqli_stmt_execute($stmt3);
						
						//get results
						$result=mysqli_stmt_get_result($stmt3);
						
						while($row=mysqli_fetch_array($result))
						{
							
						  $blogpostConfirmations .= '
						  <li>
						  <a href="viewUserPosts.php">
						  <strong>'.$row["posts"].' blogposts have been submitted for confirmation.</strong><br />
						  </a>
						  </li>
						  ';
					   
						}//end of while loop
						mysqli_stmt_close($stmt3);
					}//end of stmt
				mysqli_close($mysqli);
				}//end of if
			
			//this handles the display of the notifications to a user.
			echo 
				"<article class='entry'>
				<div class='row'>
				<h4>Recent Notifications:</h4>
				<button type='button' class='btn btn-outline-primary form-control sincerely' data-toggle='collapse' 
				data-target='#notifs'>View Notifications</button>
				</div>
				<div id='notifs' class='collapse'>
					<br/>";
				//this is only shown to bloggers
				if($_SESSION['position']==1){
					echo
					"<h5>Post Notifications</h5>
					$output";
				}
					echo"
					<h5>Comments:</h5>
					$comments
					<h5>Messages:</h5>
					$messages
					";
					//these are only shown to the admins 
					if($_SESSION['position']==2){
						echo"
						<h5>Blogpost Confirmations</h5>
						$blogpostConfirmations
						<h5>Reports:</h5>
						$commentReport
						$messageReport
						";
					}//end of if
					
					echo"
				</div>
				<br/>
				<small>You may need to reload the page to view new notifications.</small>
				</article>";
			}//end of isset
		  
		  //start of blogpost echoing 
		  //setting up variables for the blog lists
		  $postID=0;
		  $username="";
		  $heading="";
		  $content="";
		  $postDate="";
		  $imageName="";
		  $userID=0;
		  
		  //this is for the pagination
			global $perpage;
			global $curpage;
			global $start;
			global $endpage;
			global $startpage;
			global $previouspage;
			global $nextpage;
			global $total_recs;
		  
		  //this handles the blogpost viewing 
		  $sql="SELECT tblblogpost.postID, tbluser.username, tblblogpost.heading, tblblogpost.content, 
			tblblogpost.postDate, tblimages.imageName, tbluser.userID
			FROM tbluser, tblblogpost, tblconfirmedposts, tblimages
			WHERE tbluser.userID=tblblogpost.userID 
			AND tblblogpost.postID=tblconfirmedposts.postID
			AND tblblogpost.postID=tblimages.postID
			AND tblconfirmedposts.confirmed=1
			GROUP BY tblblogpost.postID
			ORDER BY tblblogpost.postDate DESC";
			
			/*So from here on is the voodoo code for the pagination
			*/
			include('dbConnect.php');
			//this sets the amount of results per page
			$perpage = 10;
			
			//this gets the page # sent by some forms below
			if(isset($_GET['page']) & !empty($_GET['page'])){
				$curpage = $_GET['page'];
			}else{
				$curpage = 1;
			}
			$start = ($curpage * $perpage) - $perpage;

			//do sql query to get the length of the resultset
			if($stmt=mysqli_prepare($mysqli, $sql)){
				mysqli_stmt_execute($stmt);
		
				/* store result */
				mysqli_stmt_store_result($stmt);
				$total_recs = mysqli_stmt_num_rows($stmt);
			}
			
			//this is to set up the actual buttons to click on for the pagination to work
			$endpage = ceil($total_recs/$perpage);
					$startpage = 1;
					$nextpage = $curpage + 1;
					$previouspage = $curpage - 1;
			
			//query to actually show the results
			//sql2 is an appended version of sql that puts a limit onto the SQL search string so that it only displays results as per the $start and $perpage variables
			$sql2=$sql." LIMIT $start, $perpage";
			
			//this executes the query
			$stmt=mysqli_prepare($mysqli, $sql2);
				//execute query
				if(mysqli_stmt_execute($stmt)){
				
				//bind results
				mysqli_stmt_bind_result($stmt, $postID, $username, $heading, $content, $postDate, $imageName, $userID);
				
				while(mysqli_stmt_fetch($stmt)){
					$preview=substr($content,0,100);
					$preview=strip_tags($preview);
					$postDate=date('h:i:s a m/d/Y', strtotime($postDate));
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
								<div class='wrapword'><a href='viewBlogSingle.php?postID=$postID'>$heading</a></div>
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
			mysqli_stmt_close($stmt);
		}//end of stmt
		mysqli_close($mysqli);
		  
		  ?>
		  
		  <!--This handles the blog pagination-->
		  <div class="blog-pagination">
		<ul class="justify-content-center">
		  <?php if($curpage != $startpage){ ?>
			<li class="page-item">
			  <a href="?page=<?php echo $startpage ?>" tabindex="-1" aria-label="Previous">
				<span aria-hidden="true">&laquo;</span>
				<span class="sr-only">First</span>
			  </a>
			</li>
			<?php } ?>
			<?php if($curpage >= 2){ ?>
			<li class="page-item"><a href="?page=<?php echo $previouspage ?>"><?php echo $previouspage ?></a></li>
			<?php } ?>
			<li class="page-item active"><a href="?page=<?php echo $curpage ?>"><?php echo $curpage ?></a></li>
			<?php if($curpage != $endpage){ ?>
			<li class="page-item"><a href="?page=<?php echo $nextpage ?>"><?php echo $nextpage ?></a></li>
			<li class="page-item">
			  <a href="?page=<?php echo $endpage ?>" aria-label="Next">
				<span aria-hidden="true">&raquo;</span>
				<span class="sr-only">Last</span>
			  </a>
			</li>
			<?php } ?>
		 </ul>
		</div>

          </div><!-- End blog entries list -->

          <div class="col-lg-4">

            <div class="sidebar">
			
			<?php
			global $feedback;
			global $count; 
			
	         if($feedback != ""){
		 
		     echo "<div class= 'alert alert-danger'>"; 
		       if ($count == 1) echo "<strong>$count error found.</strong>";
			   if ($count > 1) echo "<strong>$count errors found.</strong>";
		     echo "$feedback
			   </div>";
			}//end of error code
			?>
			
			<!--This handles the search for the home page-->
              <h3 class="sidebar-title">Search</h3>
              <div class="sidebar-item search-form">
                <form action="index.php" method="post">
                  <input type="text" name="search">
                  <button type="submit"><i class="icofont-search"></i></button>
                </form>

              </div><!-- End sidebar search formn-->


              <h3 class="sidebar-title">Recent Posts</h3>
              <div class="sidebar-item recent-posts">
			  <?php
			  
				include('dbConnect.php');
				
				$sql="SELECT tblblogpost.postID, tblblogpost.heading, tblblogpost.postDate, tblimages.imageName
				FROM tbluser, tblblogpost, tblconfirmedposts, tblimages
				WHERE tbluser.userID=tblblogpost.userID 
				AND tblblogpost.postID=tblconfirmedposts.postID
				AND tblblogpost.postID=tblimages.postID
				AND tblconfirmedposts.confirmed=1
				GROUP BY tblblogpost.postID
				ORDER BY tblblogpost.postDate DESC
				LIMIT 5";
				
				if($stmt=mysqli_prepare($mysqli, $sql)){
					//execute query
					if(mysqli_stmt_execute($stmt)){
					
					//bind results
					mysqli_stmt_bind_result($stmt, $postID, $heading, $postDate, $imageName);
					
						while(mysqli_stmt_fetch($stmt)){
							$preview=substr($content,0,100);
							$preview=strip_tags($preview);
							$postDate=date('h:i:s a m/d/Y', strtotime($postDate));
							
							if($imageName=="" || $imageName==null){
								echo"
							
									 <div class='post-item clearfix'>
									  <h4><a href='viewBlogSingle.php?postID=$postID'>$heading</a></h4>
									  <time datetime='2020-01-01'>$postDate</time>
									</div>
							
								";
							}else{
								echo"
							
									 <div class='post-item clearfix'>
									  <img src='blogImages/$imageName' alt='image of a user's blogpost'>
									  <h4><a href='viewBlogSingle.php?postID=$postID'>$heading</a></h4>
									  <time datetime='2020-01-01'>$postDate</time>
									</div>
							
								";
							}
							
							
							
						}//end of while
					}//end of if
				mysqli_stmt_close($stmt);
			}//end of stmt
			mysqli_close($mysqli);
			  
			  ?>


              </div><!-- End sidebar recent posts-->

              <h3 class="sidebar-title">Recent Tags</h3>
              <div class="sidebar-item tags">
			  
			  <?php
			  
				include('dbConnect.php');
				//variable declaration
				$recentTags="";
				
				//this query would get all the tags of the recently made posts
				if($stmt=mysqli_prepare($mysqli,
				"SELECT tbltags.tagName
				FROM tbltags, tblblogpost, tblconfirmedposts
				WHERE tbltags.postID=tblblogpost.postID
                AND tbltags.postID=tblconfirmedposts.postID
                AND tblconfirmedposts.confirmed=1
				ORDER By tblblogpost.postDate DESC
				LIMIT 12
				")){
					mysqli_stmt_execute($stmt);
					
					mysqli_stmt_bind_result($stmt, $recentTags);
					
					echo"<ul>";
					
					while(mysqli_stmt_fetch($stmt)){
						echo"
						
						<li><a href='searchResults.php?search=$recentTags'>$recentTags</a></li>
						
						";
					}//end of while
					echo"</ul>";
				mysqli_stmt_close($stmt);
			}//end of stmt
			mysqli_close($mysqli);
			  
			  ?>
               
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

</body>

</html>
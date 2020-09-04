<?php	
ob_start();
	//This is for PHPMailer
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	
	//custom session checker to ensure that user is logged in, as both bloggers and admins can make use of the same page
	session_start();
	if($_SESSION['uName']==""){
		Header("Location:login.php?feedback=You must create an account to access this page! You can register <a href='registration.php'>here!</a>");
	}
	//load stuff for the Quill rendering & PHPMailer
	require_once 'vendor/autoload.php';

	
	//isset for the form post
	if(isset($_POST['submit'])){
		//get the post from the form
		$body=$_POST['body'];
		$title=$_POST['title'];
		//setup error handling variables
		
		//get userID from session
		if(isset($_SESSION['uID'])){
			$uID= $_SESSION['uID'];
		}
		
		//error handling variables
		$count=0; 
		$feedback="";
		$success="";
		//variable for storing the post ID
		$postID=0;
		
		//success variables for database inserts
		$blogInsert=false;
		$imageInsert=false;
		$tagInsert=false;
		$confirmInsert=false;
		
		/*User Tag Management code to get the user tags
		This ain't mine, chief. 
		Code Author: SamA74
		Code Accessed On: 19/05/2020
		Code Source: https://www.sitepoint.com/community/t/how-to-do-a-tag-system/292438
		*/
		//variable for setting a limit on the tags
		$limit=6;
		
		//explode the entered data into the $raw variable
		$raw= explode(',', $_POST['tags'], $limit);
		//create new array to store data
		$tags= array();
		//for each element in $raw, trim the whitespace off the element and cram it into the $tags array
		foreach($raw as $tag){
			trim($tag);
			/*This little bit of code isn't mine.
			Code Author: alvin alexander
			Code Accessed On: 24/06/2020
			Code Source: https://alvinalexander.com/php/php-string-strip-characters-whitespace-numbers/
			*/
			$tag = preg_replace("/[^a-zA-Z]/", "", $tag);
			$tags[]=$tag;
			
		}//end of foreach
		
		//validate code so it fits the format
		validate($body, $title, $tags);
		
		//santize data so there are no scary things in it
		sanitize($body, $title);
		
		//this is a check done to ensure that there are no errors, if there are errors then the render/insert won't run
		/*
		This code isn't mine. 
		Code Author: deanblackborough 
		Code Source: https://github.com/deanblackborough/php-quill-renderer/blob/master/README.md
		Code Used: 07/05/2020
		
		*/
		if($count==0){
			//magic code that renders the quill delta into readable text
			
			//store the html in the database
			insert($body, $title, $uID);
			
			//get postID for insert into database
			getID($body, $title, $uID);
			
			//validate and insert images
			insertImage();
			
			//insert tags for the post
			insertTags($tags);
			
			//insert into confirmation table
			insertConfirmation();
			
			if($_SESSION['position']==1){
				sendEmail($result, $title);
			}
			
			if($blogInsert){
				if($tagInsert && $imageInsert && $confirmInsert){
					if($_SESSION['position']==1){
							global $postID;
						$success.="Blogpost made successfully, all content has been saved! All of your posts can be seen on your account!
						<br/> Please be advised that your post will need to be verified by an administrator before it is made public, this may take some time.";
						header("Location:confirmBlog.php?postID=".$postID);
					}else{
							global $postID;
						$success.="Blogpost made successfully, all content has been saved! All of your posts can be seen on your account!";
						header('Location:confirmBlog.php?postID='.$postID);
					}

				}else if($tagInsert && $confirmInsert){
					if($_SESSION['position']==1){
							global $postID;
						$success.="Blogpost made successfully, all content has been saved! All of your posts can be seen on your account!
						<br/> Please be advised that your post will need to be verified by an administrator before it is made public, this may take some time.";
						header('Location:confirmBlog.php?postID='.$postID);
					}else{
							global $postID;
						$success.="Blogpost made successfully, all content has been saved! All of your posts can be seen on your account!";
						header('Location:confirmBlog.php?postID='.$postID);
					}
				}
			}
		}//end of count
		
	}//end of isset
	
	/*This function is used to get the autoincremented ID from the database
	*$result is the content entered into the database for the blogpost
	*$title is the title of the post
	*$uID is the user's ID.
	*/
	function getID($result, $title, $uID){
		global $feedback;
		global $count; 
		global $postID;
		
		//include database connection
		include('dbConnect.php');
		
		//create sql select
		if($stmt=mysqli_prepare($mysqli, "SELECT postID FROM tblblogpost WHERE tblblogpost.content=? AND tblblogpost.heading=?
		AND tblblogpost.userID=?")){
			//bind params for the select
			mysqli_stmt_bind_param($stmt, "ssi", $result, $title, $uID);
			
			//execute
			mysqli_stmt_execute($stmt);
			
			//bind result of the stmt
			mysqli_stmt_bind_result($stmt, $postID);
			
			if(mysqli_stmt_fetch($stmt)){
				//return true
				return true;
			}else{
				$feedback.="An error occured with saving your images. Please contact an admin for help. <br/> Error: Could not get postID for image insert.";
				$count++;
				return false; 
			}//end of if-else
				mysqli_stmt_close($stmt);
		}//end of stmt
		mysqli_close($mysqli);
	}//end of function getID
	
	/*This function is used to insert a record into tblcomfirmedposts. This record will be used for the confirmation functionality of the application*/
	function insertConfirmation(){
		global $postID;
		global $count;
		global $feedback;
		global $confirmInsert;
		
		$dbSuccess=false;
		//connect to db
		include('dbConnect.php');
		
		/*given that both a user and admin can make a blogpost, its necessary to use an if here to check the position stored in the session. 
		If the user's position is 1 (a normal user) then an insert would be made where an admin has to confirm the post. 
		If the user's position is not 1, then the would be an admin and as such, their blogpost would not need confirmation from an admin.
		*/
		
		if(isset($_SESSION['position'])){
			$uID=$_SESSION['uID'];
		}
		
		if($_SESSION['position']==1){
			//this is the variable that would be inserted into the database to store the confirmation status of the blogpost
			$confirmation=false;
			//create stmt and insert into db
			if($stmt=mysqli_prepare($mysqli, "INSERT INTO tblconfirmedposts(postID, confirmed) VALUES(?,?)")){
				//bind params
				mysqli_stmt_bind_param($stmt, "is", $postID, $confirmation);
				
				//execute code
				if(mysqli_stmt_execute($stmt)){
					$dbSuccess=true; 
				}else{
					$count++;
					$feedback.="<br/> An error occured with your blogpost please contact an Administrator for assistance. <br/> Error: Confirmation Insert Failed";
				}//end of if-else	
			}//end of if
		}else{
			$confirmation=true;
			//create stmt and insert into db
			if($stmt=mysqli_prepare($mysqli, "INSERT INTO tblconfirmedposts(postID, userID, confirmed) VALUES(?,?,?)")){
				//bind params
				mysqli_stmt_bind_param($stmt, "iis", $postID, $uID, $confirmation);
				
				//execute code
				if(mysqli_stmt_execute($stmt)){
					$dbSuccess=true; 
				}else{
					$count++;
					$feedback.="<br/> An error occured with your blogpost please contact an Administrator for assistance. <br/> Error: Confirmation Insert Failed";
				}//end of if-else	
			}//end of if
		}//end of else 
			
		if($dbSuccess){
			$confirmInsert=true;
		}
	}//end of insertConfirmation
	
	/*This function handles the insertion of the image names into the database and into the correct file on the server.*/
	function insertImage(){
		global $feedback;
		global $count;
		global $success;
		global $imageInsert;
		global $postID;
		
		$dbSuccess=false;
		
		include('dbConnect.php');
		//validate images
		
		//code adapted from: https://www.youtube.com/watch?v=DL8LT8beVU0
		//accessed on: 26/11/2017
		if($_FILES['fileUpload']['name'][0]!=""){
			for($i=0; $i<count($_FILES['fileUpload']['name']); $i++)
			{
				//create random number for inserting the images
				$rand=rand(1,1000);
				
				//this is some minor file type validation
				if((($_FILES['fileUpload']['type'][$i] == 'image/jpg')||
				($_FILES['fileUpload']['type'][$i] == 'image/jpeg')||
				($_FILES['fileUpload']['type'][$i] == 'image/pjpeg')||
				($_FILES['fileUpload']['type'][$i] == 'image/bmp')||
				($_FILES['fileUpload']['type'][$i] == 'image/png'))
				&&
				($_FILES['fileUpload']['size'][$i]<1000000))
				{
					$uploadedFile = $_FILES['fileUpload']['name'][$i]."(".$_FILES['fileUpload']['type'][$i].",".ceil($_FILES['fileUpload']['size'][$i]/1024).")Kb"."<br />";
				}
					move_uploaded_file($_FILES['fileUpload']['tmp_name'][$i],'blogImages/'.$rand.$_FILES['fileUpload']['name'][$i]);

				$imageName= $rand.$_FILES['fileUpload']['name'][$i];
			
				//store filename in database
				if ($stmt = mysqli_prepare($mysqli,
					"INSERT INTO tblimages(imageName, postID)
					VALUES(?, ?)")){
					
					//Bind parameters to SQL Statement Object
					mysqli_stmt_bind_param($stmt, "si", $imageName, $postID);
					
					//Execute statement object and check if successful
					if(mysqli_stmt_execute($stmt)){
						$dbSuccess=true;
					
					}else{
						$feedback.= "<br/>Images Saved Unsuccessfully. Please contact a technician.";
						echo (mysqli_stmt_error($stmt));
						$count++;
					}//end of feedback if else 
					
				}//end mysqli prepare statement
			}//end of for loop
			mysqli_stmt_close($stmt);
			mysqli_close($mysqli);
		}else{
			/*to simplify some querying with the system, a null record will be inserted into tblimage if the blogpost has no images
			this is done to keep track of what blogposts have images and what blogposts have no images
			*/
			$imageName=null;
			//store filename in database
				if ($stmt = mysqli_prepare($mysqli,
					"INSERT INTO tblimages(imageName, postID)
					VALUES(?, ?)")){
					
					//Bind parameters to SQL Statement Object
					mysqli_stmt_bind_param($stmt, "si", $imageName, $postID);
					
					//Execute statement object and check if successful
					if(mysqli_stmt_execute($stmt)){
						$dbSuccess=true;
					
					}else{
						$feedback.= "<br/>Images Saved Unsuccessfully. Please contact a technician.";
						echo (mysqli_stmt_error($stmt));
						$count++;
					}//end of feedback if else 
					
					mysqli_stmt_close($stmt);
				}//end of stmt
			mysqli_close($mysqli);
		}//end of else
		
		if($dbSuccess){
			$imageInsert=true;
		}
	}//end of insertImage
	/*This function handles the insert of blogpost tags into the database
	*$tags is an array of the tags the user has included for their blogpost
	*/
	function insertTags($tags){
		global $feedback;
		global $count; 
		global $success;
		global $postID;
		global $tagInsert;
		
		//success variables
		$dbSuccess=false;
		
		//include database connection
		include('dbConnect.php');
		
		//lowercase all tags to standardize them in the system
		$loweredTags= array_map('strtolower', $tags);
		
		//loop through insert statement, inserting tags into the database
		foreach($loweredTags as $tag){
			//insert tag
			if($stmt=mysqli_prepare($mysqli, "INSERT INTO tbltags(postID, tagName) VALUES(?,?)")){
				//Bind parameters to SQL Statement Object
				mysqli_stmt_bind_param($stmt, "is", $postID, $tag);
				
				//Execute statement object and check if successful
				if(mysqli_stmt_execute($stmt)){
					$dbSuccess=true;
				
				}else{
					$feedback.= "<br/>Tags Saved Unsuccessfully. Please contact an administrator for assistance.";
					echo (mysqli_stmt_error($stmt));
					$count++;
				}//end of feedback if else
			}//end of if statement
		}//end of foreach
		mysqli_stmt_close($stmt);
		mysqli_close($mysqli);
		if($dbSuccess){
			$tagInsert=true;
		}
	}//end of function insertTags
	
	/*function to sanitize data with mysqli functions 
	*mT is the markup text
	*/
	function sanitize($post, $t){
		//sanitize form data
		$post= filter_var($post, FILTER_SANITIZE_STRING); 
		$t= filter_var($t, FILTER_SANITIZE_STRING); 
		
		//include escape string function here, this uses the mysqli escape string to prevent special characters from being entered into the db
		escapeString($post);
		escapeString($t);
		
		return $post;
		return $t;
	}//end of sanitize
	
	/*This function is used to send the administrators and email if a user creates a blogpost to be confirmed*/
	function sendEmail($post, $title){
		global $feedback;
		global $count;
		global $success;
		$blogpostConfirmations="";
		
		//get the user's username
		if(isset($_SESSION['uName'])){
			$uName=$_SESSION['uName'];
		}
		
		//create new date object to inform the user when the blogpost was made.
		$date=date('Y-m-d H:i:s');
		
		$dt = new DateTime("now", new DateTimeZone('America/Guyana'));
		
		$date = $dt->format('Y-m-d H:i:s');
		
		//include database connection
		include('dbConnect.php');
		//get count of blogposts that need to be confirmed to put into the body of the email
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
				 
				  <strong>'.$row["posts"].' other blogposts have been submitted for confirmation, you can view them here --put link for page here--</strong><br />
				 
				  ';
			   
				}//end of while loop
				mysqli_stmt_close($stmt3);
			}//end of stmt
		mysqli_close($mysqli);
		
		//this is for phpmailer
		$mail = new PHPMailer(true);
		
		/*fam look, this code works as is and IT ONLY WORKS WITH GMAIL. 
		If you need to edit it, look up the documentation for PHPMailer first, they have a github here: 
		https://github.com/PHPMailer/PHPMailer
		
		btw this goes without saying, but the code below ain't mine.
		*/
		try{
			//server settings
			$mail->isSMTP();
			$mail->SMTPOptions = array(
			'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
			)
			);
			//$mail->SMTPDebug = SMTP::DEBUG_SERVER;
			$mail->Host = 'smtp.gmail.com';
			$mail->Port = '587';
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			$mail->SMTPAuth = true;
			$mail->Username='ewsdgroup2018@gmail.com';//this is the sending email
			$mail->Password= 'EWSD2018';//well, password is the password for the gmail account
			$mail->SetFrom('no-reply@Sincerely.com');
			$mail->AddAddress('rys19@live.com');
			//content
			$mail->isHTML();
			$mail->Subject='Blogpost Confirmation Required';
			$mail->Body= 'Hello jimmy2, 
				Please be advised that a new blogpost has been submitted for confirmation by user: '.$uName.':
				<br/>
				<b>Title:</b> ' .$title.
				'<br/> 
				<b>Blogpost Body:</b> '.$post.
				'</br>
				<b>Date Sent:</b> ' .$date.
				'<br/>
				
				<p>'.$blogpostConfirmations.'</p><br/>
				--Put link to view post here--
				
				Kind Regards, <br/>
				Email Bot (PHPMailer)';
			
			//recipient
			
			
			$mail->Send();
			$success.='Message sent!';
		} catch (Exception $e) {
			$count++;
			$feedback.= "<br/>Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	}//end of sendEmail
	
	/*function to escape any special characters from entered user data
	*$val is the variable being escaped
	*/
	function escapeString($val){

		//include database connections
		include('dbConnect.php');
		
		//sanitize data going into MySQL
		$val= mysqli_real_escape_string($mysqli, $val);
		return $val;
	}//end of escapeString
	
	/*function to validate the entered user data 
	*$mT is the user's entered text with the markup tags
	*/
	function validate($quill, $t, $tags){
		global $count;
		global $feedback; 
		
		if($quill=="" || $quill==null){
			$count++;
			$feedback.="<br/> Your post cannot be empty.";
		}else if(strlen($quill)<100){
			$count++;
			$feedback.="<br/> Your post cannot be less than 100 characters.";
		}else if($quill=='{"ops":[{"insert":"\n"}]}'){
			$count++;
			$feedback.="<br/> Your post cannot be empty.";
		}else if(strlen($quill)>10000){
			$count++;
			$feedback.="<br/> Your post cannot be more than 10,000 characters.";
		}	
		
		if($t=="" || $t==null){
			$count++;
			$feedback.="<br/> You must enter a title";
		}else if(strlen($t)>100){
			$count++;
			$feedback.="<br/> Your title cannot be more than 100 characters.";
		}else if (!preg_match("/\\w|\\s+/", $t)){
			$count++;
			$feedback.="<br/> Your title can only contain alphanumeric characters.";
		}
		//file validation
		
		if(count($_FILES['fileUpload']['name'])>2){
			$feedback.="<br/> You can only attach 2 images.";
			$count++;
		}
		
		$emptyImages=array_filter($_FILES['fileUpload']);
		
		if(!empty($emptyImages) && $_FILES['fileUpload']['name'][0]!=""){
			//code adapted from:https://www.w3schools.com/php/php_file_upload.asp
			//accessed on: 26/03/2019
			$allowed =  array('jpeg', 'png', 'jpg', 'bmp', 'pjpeg', 'JPEG', 'PNG', 'JPG', 'BMP', 'PJPEG');
			
			for($i=0; $i<count($_FILES['fileUpload']['name']); $i++)
			{
			$path = $_FILES['fileUpload']['name'][$i];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
				if(!in_array($ext,$allowed)){
					$feedback.="<br/> Image uploaded is not of type: .jpg, .jpeg, .bmp, .pjpeg or .png";
					$count++;
				}//end of if
			}//end of loop
		}//end fo else-if
		
		//validation for tags
		if(count($tags)>5){
			$feedback.="<br/> You cannot have more than 5 tags for your post.";
			$count++;
		}//end of count
		
		//validation for empty arrays
		/*This code isn't mine
		Code Author: James
		Code Adapted From: https://stackoverflow.com/questions/2216052/how-to-check-whether-an-array-is-empty-using-php
		Code Accessed On: 1/06/2020
		*/
		$emptyArrayTest=array_filter($tags);
		
		if(empty($emptyArrayTest)){
			$feedback.="<br/> You must tag your post!";
			$count++;
		}
		
		//validation for empty tags between tags
		/*This code isn't mine
		Code Author: Tyler Carter
		Code Adapted From: https://stackoverflow.com/questions/2216052/how-to-check-whether-an-array-is-empty-using-php
		Code Accssed On: 1/06/2020
		
		*/
		foreach ($tags as $key => $value) {
			if (empty($value)) {
			   //unset($tags[$key]);
			   $feedback.="<br/> Please do not enter empty tags.";
			   $count++;
			}
		}
		
	}//end of validation
	
	/*This function (despite its crap name) is used to make the insert into the tblblogpost table, storing the majority of content relating to a blogpost
	*$content refers to the blogpost body
	*$heading refers to the title of the blogpost
	*$uID is the userID of the user who created the blogpost
	*/
	function insert($content, $heading, $uID){
		global $count;
		global $feedback; 
		global $success; 
		global $blogInsert;
		
		//create new date object for the insert into the database
		$date=date('Y-m-d H:i:s');
		
		$dt = new DateTime("now", new DateTimeZone('America/Guyana'));
		
		$date = $dt->format('Y-m-d H:i:s');
		
		$dbSuccess=false;
		
		//get db connector
		include('dbConnect.php');
		
		if($stmt=mysqli_prepare($mysqli, 
		"INSERT INTO tblBlogPost(content, heading, postDate, userID) VALUES (?, ?, ?, ?)")){
			//bind parameters to SQL Object
			mysqli_stmt_bind_param($stmt,"sssi", $content, $heading, $date, $uID);
			
			//execute statement and see if successful
			if(mysqli_stmt_execute($stmt)){
				$dbSuccess=true;
			}else{
				$feedback.="<br/> Blogpost Unsuccessful. Please contact an administrator for assistance!";
				echo (mysqli_stmt_error($stmt));
				$count++;
				return false;
			}//end of if-else
			mysqli_stmt_close($stmt);
		}//end of stmt
		mysqli_close($mysqli);	
		if($dbSuccess){
			$blogInsert=true;
		}
	}//end of insert
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
        </ol>
        <h2>Create Blogpost</h2>
		 <h6>You can create your own blogpost here! Be as descriptive as you would like, this is a safe place for you to tell it as it is.</h6>
      </div>
	  
    </section><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="form">
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
	  <form id="target" class="form-horizontal" action="createBlog.php" method="post" onSubmit="return valBlog(this)" enctype="multipart/form-data">
	  
			<div class="form-group"> 
				<label class="control-label col-sm-2"><h4>Blog Title:</h4></label>
				 <div class="col-sm-12">

					<input type="text" class="form-control" name="title" id="title" aria-labelledby="title"/> 
				 </div>
			 <span class="error" id="title_err"></span>
			</div>
			
			<div class="form-group">
				<label class="control-label col-sm-2"><h4>Blog Body:</h4></label>
				<div class="col-sm-12">
				<small>You can use emotes when you're making your post, click on the right, next to the omega (Ω) symbol.</small>
				<!--The id for the text area has to be 'editor' for the script to recognize it-->
					<textarea id="editor" name="body"></textarea>
					<!--This is needed to store the information that the user enters into the rich text area.-->
				</div>
			</div>
			
			<div class="form-group"> 
				<label class="control-label col-sm-5"><h4>Attach Images (Optional, 2 Maximum):</h4></label>
			 <div class="col-sm-12">
				<!--Code Adapted from:http://www.javascripthive.info/php/php-multiple-files-upload-validation/-->
				<!--Accessed on: 26/11/2017-->
				<input type="file" class="form-control" name="fileUpload[]" aria-labelledby="Select the images you want to upload" multiple/> 
				<span id="img_err"></span>
			 </div>
			</div>
			
			<div class="form-group"> 
				<label class="control-label col-sm-2"><h4>Blog Tags:</h4></label>
				<div class="col-sm-12">You can tag your blog however you like, please separate tags with a comma and only
				choose tags relevant to your post! <br/> For example: <strong>personal, vent, anxiety, stress, frustrated etc.</strong> <br/> (Max tags: 5)</div>
				 <div class="col-sm-12">

					<input type="text" class="form-control" name="tags" id="tags" aria-labelledby="blog tags"/> 
				 </div>
			 <span class="error" id="tag_err"></span>
			</div>
			
			<div class="col-sm-12">
				<input type="submit" class="btn btn-outline-primary form-control sincerely" name="submit" value="Submit & View Preview" onClick="return valBlog();"/>
			</div>
		</form>
	  </div>
    </section><!-- End Blog Section -->
	
	<br/>
	<br/>
	<br/>
	<!-- Initialize Quill editor -->
		<script>
		//These are the options for the toolbar
			CKEDITOR.replace( 'editor' );
		</script>

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
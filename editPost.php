<?php
/*So, this is a really long, complex page used allow a user to edit their blogpost.*/
	//quill rendering
	namespace DBlackborough\Quill;
	//session management
	session_start();
	if($_SESSION['uName']==""){
		
		Header("Location:login.php?feedback=You must be logged in to access this page...");
		
	}
	
	require_once 'vendor/autoload.php';
	
	//set up all variables to be used
	$feedback="";
	$count=0;
	$postID=0;
	$success="";
	
	//get post ID and put it into the session, should the user reload the page the ID would not be lost
	if(isset($_GET['postID'])){
		$postID=$_GET['postID'];
		$_SESSION['editID']=$postID;
	}else if(isset($_SESSION['editID'])){
		$postID=$_SESSION['editID'];
	}
	
	//this isset controls the edit for the title
	if(isset($_POST['titleEdit'])){
		global $postID; 
		//get new title from user
		$newTitle=$_POST['newTitle'];
		
		//validate new title
		if($newTitle=="" || $newTitle==null){
			$count++;
			$feedback.="<br/> You must enter a title";
		}else if(strlen($newTitle)>100){
			$count++;
			$feedback.="<br/> Your title cannot be more than 100 characters.";
		}else if (!preg_match("/\\w|\\s+/", $newTitle)){
			$count++;
			$feedback.="<br/> Your title can only contain alphanumeric characters.";
		}
		
		//sanitize form data
		$newTitle= filter_var($newTitle, FILTER_SANITIZE_STRING); 
		
		//include database connections for string escape
		include('dbConnect.php');
		
		//sanitize data going into MySQL
		$newTitle= mysqli_real_escape_string($mysqli, $newTitle);
		
		if($count==0){
			//insert data into the database
			if($stmt=mysqli_prepare($mysqli, 
			"UPDATE tblblogpost SET tblblogpost.heading=? WHERE tblblogpost.postID=?")){
				//bind parameters
				mysqli_stmt_bind_param($stmt, "si", $newTitle, $postID);
				
				//execute
				if(mysqli_stmt_execute($stmt)){
					//successful insert
					$success.="Blogpost title successfully updated!";
					confirmationUpdate();
				}else{
					//unsuccessful insert
					$count++;
					$feedback.="<br/> Title could not be updated, an error occured, please contact a technician for assistance.";
				}//end of if-else
			mysqli_stmt_close($stmt);
			}//end of stmt
			mysqli_close($mysqli);
		}//end of count
	}//end of titleEdit isset
	
	//this isset controls the edit for the body
	if(isset($_POST['bodyEdit'])){
		global $postID;
		
		$quill_json=$_POST['quill_json'];
		
		//validate new body
		if($quill_json=="" || $quill_json==null){
			$count++;
			$feedback.="<br/> Your post cannot be empty.";
		}else if(strlen($quill_json)<100){
			$count++;
			$feedback.="<br/> Your post cannot be less than 100 characters.";
		}else if($quill_json=='{"ops":[{"insert":"\n"}]}'){
			$count++;
			$feedback.="<br/> Your post cannot be empty.";
		}else if(strlen($quill_json)>10000){
			$count++;
			$feedback.="<br/> Your post cannot be more than 10,000 characters.";
		}
		
		if($count==0){
			//magic code that renders the quill delta into readable text
			try {
				$quill = new \DBlackborough\Quill\Render($quill_json, 'HTML');
				$result = $quill->render();
			} catch (\Exception $e) {
				echo $e->getMessage();
			}
			
			//revalidate body
			if($result=="" || $result==null){
				$count++;
				$feedback.="<br/> Your post cannot be empty.";
			}else if(strlen($result)<100){
				$count++;
				$feedback.="<br/> Your post cannot be less than 100 characters.";
			}else if($result=='{"ops":[{"insert":"\n"}]}'){
				$count++;
				$feedback.="<br/> Your post cannot be empty.";
			}

			//sanitize
			include('dbConnect.php');
			
			//sanitize data going into MySQL
			$result= mysqli_real_escape_string($mysqli, $result);
			
			if($count==0){
				if($stmt=mysqli_prepare($mysqli, 
				"UPDATE tblblogpost SET tblblogpost.content=? WHERE tblblogpost.postID=?")){
					//bind parameters
					mysqli_stmt_bind_param($stmt, "si", $result, $postID);
					
					//execute
					if(mysqli_stmt_execute($stmt)){
						//if insert was successful
						$success.="Blogpost content successfully updated!";
						confirmationUpdate();
					}else{
						//if insert was unsuccessful
						$count++;
						$feedback.="<br/> Content could not be updated, an error occured, please contact a technician for assistance.";
					}//end of if-else
					mysqli_stmt_close($stmt);
				}//end of stmt
				mysqli_close($mysqli);
			}//end of count
		}//end of count
	}//end of bodyEdit isset
	
	//this isset controls the image deletion
	if(isset($_POST['deleteImage'])){
		global $postID;
		//this variable will be inserted after the delete into the images table
		//this is done to keep track of the fact that this post has no images to show.
		$imageName=null;
		//if the posted variable is not empty then get the image name and do stuff
		if($_POST['image']!=""){
			$image=$_POST['image'];
			
			$imageArray=array_filter($image);
			//if the image array is and the posted image are not empty 
			if(!empty($imageArray) && ($image[0]!="" || $image[0]!=null)){
				$arrayCount=count($image);
				//for each image, run the delete script
				for($i=0; $i<=$arrayCount; $i++){
					include ('dbConnect.php');
					
					if($stmt=mysqli_prepare($mysqli, 
					"DELETE FROM tblimages WHERE tblimages.imageName=?")){
						//bind parameters 
						mysqli_stmt_bind_param($stmt,"s",$image[$i]);
						
						//execute query
						if(mysqli_stmt_execute($stmt)){
							$success="Images successfully deleted";
							confirmationUpdate();
						}else{
							$count++;
							$feedback.="<br/> Images could not be deleted, an error occured. Please contact an adminitrator for assistance.";
						}
						
					}
				}//end of for
				//close the statement
				mysqli_stmt_close($stmt);
				//close the connection
				mysqli_close($mysqli);
				
				
				//this is used to run the insert to push new images to the database
				include ('dbConnect.php');
				//insert a null to keep track that this post has no images
				if ($stmt = mysqli_prepare($mysqli,
					"INSERT INTO tblimages(imageName, postID)
					VALUES(?, ?)")){
					
					//Bind parameters to SQL Statement Object
					mysqli_stmt_bind_param($stmt, "si", $imageName, $postID);
					
					//Execute statement object and check if successful
					if(mysqli_stmt_execute($stmt)){
						$dbSuccess=true;
						confirmationUpdate();
					}else{
						$feedback.= "<br/>Images Saved Unsuccessfully. Please contact a technician.";
						echo (mysqli_stmt_error($stmt));
						$count++;
					}//end of feedback if else 
					
					mysqli_stmt_close($stmt);
				}//end of stmt
			mysqli_close($mysqli);
			
			}else{
				$count++;
				$feedback.="<br/> No images to be deleted!";
			}
		}else{
			$count++;
			$feedback.="<br/> No images to be deleted!";
		}
	}//end of deleteImage isset
	
	//this isset controls the image upload
	if(isset($_POST['imageUpload'])){
		global $postID;
		
		$image=$_POST['image'];
		$imageArray=array_filter($image);
		$files=$_FILES['fileUpload'];
		
		//validate files
		if(count($_FILES['fileUpload']['name'])!=0){
			//this creates a filtered array that I can use to check for an empty array.
			//idk man, i saw it off stackoverflow and it worked the first time so we doing it
			$filesArray=array_filter($files);
			if($_FILES['fileUpload']['name'][0]==null || $_FILES['fileUpload']['name'][0]==""){
				$count++;
				$feedback.="<br/>No images have been attached";
			}else if(empty($filesArray)){
				$count++;
				$feedback.="<br/>No images have been attached";
			}else if(count($_FILES['fileUpload']['name'])>2){
				$feedback.="<br/> You can only attach 2 images.";
				$count++;
			}
			
			
			//validation for filetype
			if($count==0){
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
				
				if($count==0){
					
					//this if is a check to see if this is a reupload or a new set of uploads
					//so, if $imageArray is empty then this is a new upload
					//meaning i just have to do some inserts
					//but if $imageArray is not empty then this is a reupload and i could run updates instead
					if(empty($imageArray)){

						//delete the null
						include ('dbConnect.php');
					
						if($stmt=mysqli_prepare($mysqli, 
						"DELETE FROM tblimages WHERE tblimages.postID=?")){
							//bind parameters 
							mysqli_stmt_bind_param($stmt,"i", $postID);
							
							//execute query
							if(!mysqli_stmt_execute($stmt)){
								$count++;
								$feedback.="<br/> Images could not be deleted, an error occured. Please contact an administrator for assistance.";
							}
							
							mysqli_stmt_close($stmt);
						}
						mysqli_close($mysqli);
						
						//make insert into database
						include('dbConnect.php');
						
						if($files['name'][0]!=""){
							for($i=0; $i<count($_FILES['fileUpload']['name']); $i++)
							{
								//create random number for inserting the images
								$rand=rand(1,1000);
								
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
										$success="Images uploaded successfully!";
										confirmationUpdate();
									
									}else{
										$feedback.= "<br/>Images Saved Unsuccessfully. Please contact a technician.";
										echo (mysqli_stmt_error($stmt));
										$count++;
									}//end of feedback if else 
									
								}//end mysqli prepare statement
							}//end of for loop
							mysqli_stmt_close($stmt);
							mysqli_close($mysqli);
						}
						
					}else{
					//do the update here
						//make insert into database
						include('dbConnect.php');
						
						if($files['name'][0]!="" && $image[0]!=""){
							
							for($i=0; $i<count($_FILES['fileUpload']['name']); $i++)
							{
								//create random number for inserting the images
								$rand=rand(1,1000);
								
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
									"UPDATE tblimages SET tblimages.imageName=? WHERE tblimages.imageName=?")){
									
									//Bind parameters to SQL Statement Object
									mysqli_stmt_bind_param($stmt, "ss", $imageName, $image[$i]);
									
									//Execute statement object and check if successful
									if(mysqli_stmt_execute($stmt)){
										$success="Images uploaded successfully.";
										confirmationUpdate();
									
									}else{
										$feedback.= "<br/>Images Saved Unsuccessfully. Please contact a technician.";
										echo (mysqli_stmt_error($stmt));
										$count++;
									}//end of feedback if else 
									
								}//end mysqli prepare statement
							}//end of for loop
							mysqli_stmt_close($stmt);
							mysqli_close($mysqli);
						}//end of if
					}//end of if-else
				}//end of count-if
				
			}//end of count-if
			
		}else{
			$count++;
			$feedback.="<br/> No images have been attached";
		}
	}//end of imageUpload isset
	
	if(isset($_POST['tagEdit'])){
		global $postID; 
		//get the told tags that blogpost had
		$oldTags=$_POST['oldTags'];
			
			/*User Tag Management code to get the user tags
			This ain't mine, chief. 
			Code Author: SamA74
			Code Accessed On: 19/05/2020
			Code Source: https://www.sitepoint.com/community/t/how-to-do-a-tag-system/292438
			*/
			//variable for setting a limit on the tags
			$limit=6;
			
			//explode the entered data into the $raw variable
			$raw= explode(',', $_POST['newTags'], $limit);
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
			
			/*this is a check to see if users entered new tags or not, by comparing both arrays and their contents
			if a user didn't enter anything new, then logically it would be same tags pulled from the database in the preview
			so, what this does is just compare the tags pulled from the db, to the new tags to ensure users are actually entering
			new tags into the system
			*/

			if($oldTags===$tags){
				$count++;
				$feedback.="You have not entered any new tags";
			}else{
				
				//tag validation
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
				
				if($count==0){
					
					include ('dbConnect.php');
					//delete all existing tags
						if($stmt=mysqli_prepare($mysqli, 
						"DELETE FROM tbltags WHERE tbltags.postID=?")){
							//bind parameters 
							mysqli_stmt_bind_param($stmt,"s",$postID);
							
							//execute query
							if(!mysqli_stmt_execute($stmt)){
								$count++;
								$feedback="<br/> Previous tags could not be deleted, please contact an administrator for assistance";
							}
							
						}
					
					
					if($count==0){
					//insert new tags
					//include database connection
					include('dbConnect.php');
					
					//lowercase all tags to standardize them in the system
					$loweredTags= array_map('strtolower', $tags);
					
					//create insert statement and loop through it 
					foreach($loweredTags as $tag){
						//insert tag
						if($stmt=mysqli_prepare($mysqli, "INSERT INTO tbltags(postID, tagName) VALUES(?,?)")){
							//Bind parameters to SQL Statement Object
							mysqli_stmt_bind_param($stmt, "is", $postID, $tag);
							
							//Execute statement object and check if successful
							if(mysqli_stmt_execute($stmt)){
								$success="Tag saved!";
								confirmationUpdate();
							}else{
								$feedback.= "<br/>Tags Saved Unsuccessfully. Please contact an administrator for assistance.";
								echo (mysqli_stmt_error($stmt));
								$count++;
							}//end of feedback if else
						}//end of if statement
					}//end of foreach
						mysqli_stmt_close($stmt);
					mysqli_close($mysqli);
					}
					
					
				}//end of count
			}//end of if-else
	}//end of tagEdit
	
	/*This is a method to update the confirmation status of a user's blogpost
	*Effectively, should an edit be made to the user's blogpost the admins want to re-confirm the post
	so they can view the content of the blogpost to ensure that its nothing bad/against terms of service etc. 
	*/
	function confirmationUpdate(){
		//get postID
		global $postID;
		global $feedback;
		global $count;
		global $success;
		
		//set confirmed to 0
		$confirmed=0;
		
		//get db connect
		include('dbConnect.php');
		
		//make update
		if($stmt=mysqli_prepare($mysqli, 
		"UPDATE tblconfirmedposts SET tblconfirmedposts.confirmed=? WHERE tblconfirmedposts.postID=?")){
			mysqli_stmt_bind_param($stmt, 'ii', $confirmed, $postID);
			
			if(mysqli_stmt_execute($stmt)){
				$success.="<br/> Your edit has been saved and handed off to the admins for confirmation, this may take some time.";
			}else{
				$count++;
				$feedback.="<br/> Your edit could not be saved, please contact an administrator for assistance.";
			}
		}
		
	}//end of confirmationUpdate

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
          <li><a href="userAccount.php">Your Account</a></li>
          <li><a href="editPost.php">Edit Blogpost</a></li>
        </ol>
        <h2>Edit Blogpost</h2>
		<h4>This is a preview of your blogpost, below this post are a set of tools you can use to edit your post.</h4>
      </div>
	  
    </section><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="blog" class="blog">
	
	 <?php
			global $feedback;
			global $count; 
			global $success;
			
	         if($feedback != ""){
			echo"<div class='container'>";
		     echo "<div class= 'alert alert-danger'>"; 
		       if ($count == 1) echo "<strong>$count error found.</strong>";
			   if ($count > 1) echo "<strong>$count errors found.</strong>";
		     echo "$feedback
			   </div>";
			echo"</div>";
			}//end of error code
			
			//this is feedback code for success messages
			if($success != ""){
				echo"<div class='container'>";
				 echo "<div class= 'alert alert-success'>"; 
				 echo "$success
				   </div>";
				 echo"</div>";
			}//end of if statement for error


		//show preview of blogpost to edit
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
							  
							  if(count($image)!=0){
								 $imageArray=array_filter($image);
								 if(!empty($imageArray) && ($image[0]!=null || $image[0]!="")){
								   if($imageCount>=1){
										echo" <img src='blogImages/$image[0]' class='img-fluid'>";
								  }else if($imageCount==1){
									  echo" <img src='blogImages/$image[0]' class='img-fluid'>";
								  }//end of if-else
								}//end of if
							  }//end of count-if
							  
							 
							//continuation of echoing out the article itself	
							echo"
							  </div>
							
							  <h2 class='entry-title'>
								<a href=#>$heading</a>
							  </h2>

							  <div class='entry-meta'>
								<ul>
								  <li class='d-flex align-items-center'><i class='icofont-user'></i>$username</li>
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
				}
		}
	?>
	  
	  <div class='container'>
	  
	  <hr>
	  
	  <h4>Editing Tools:</h4>
	  <!--Edit Title-->
	  <form class="horizontal" method="post" action="editPost.php">
	  
		<div class="form-group"> 
				<label class="control-label col-sm-2"><h5>Current Title:</h5></label>
			 <div class="col-sm-10">
				<input type="text" class="form-control" name="newTitle" id="newTitle"
				aria-labelledby="new title" value="<?php global $heading; echo $heading;?>"/> 
			 </div>
		</div>
		
		<div class="col-sm-10">
			<input type="submit" class="btn btn-outline-primary form-control sincerely" name="titleEdit" value="Edit"/>
		</div>
	  
	  </form>
	  
	  <hr/>
	  <br/>
	  
	  <!--Edit Body-->
	  <form id="target" class="horizontal" method="post" action="editPost.php">
	  
		<div class="form-group"> 
				<label class="control-label col-sm-2"><h5>Current Body:</h5></label>
				<p class="error col-sm-10">Please note that with editing the body of your blogpost, any formatting previously applied is lost and may need to be reapplied.</p>
			 	<div class="col-sm-10">
					<div id="editor">
						
					</div>
					<!--This is needed to store the information that the user enters into the rich text area.-->
					<input type="hidden" id="quill_json" name="quill_json" aria-labelledby="blog writing area"/>
				</div>
		</div>
		
		<div class="col-sm-10">
			<input type="submit" class="btn btn-outline-primary form-control sincerely" name="bodyEdit" value="Edit"/>
		</div>
	  
	  </form>
	  
	  <hr/>
	  <br/>
	  <!--Edit Images-->
	   <form class="horizontal" method="post" action="editPost.php" enctype="multipart/form-data">
	  
		<div class="form-group"> 
			<div class="form-group"> 
				<label class="control-label col-sm-5"><h5>Current Images:</h5></label>
				
				<?php
				global $image;
				if(count($image)!=0){
					echo"<div class='row col-sm-10'>";
					for($i=0; $i<count($image); $i++){
						if($image[$i]!=null ||$image[$i]!="" ){
							echo" <img src='blogImages/$image[$i]' class='img-fluid col-sm-5' width='200' height='100'>";
						}else{
							echo"<div class='container'><p>No images have been attached to this post.</p></div>";
						}
					}
					echo"</div>";
				}else{
					echo"<div class='container'><p>No Images have been set.</p></div>";
				}
				
				
				?>
				<br/>
			 <div class="col-sm-10">
				<!--Code Adapted from:http://www.javascripthive.info/php/php-multiple-files-upload-validation/-->
				<!--Accessed on: 26/11/2017-->
				<input type="file" class="form-control" name="fileUpload[]" aria-labelledby="Select the images you want to upload" multiple/> 
				<?php
				/*
				This code isn't mine
				Code Accessed On: 11/06/2020
				Code Author: Shakti Singh
				Code Available at:https://stackoverflow.com/questions/6547209/passing-an-array-using-an-html-form-hidden-element
				
				*/
				global $image;
				//this would take the image array and allow me to access it through the POST
				foreach($image as $img)
				{
					echo '<input type="hidden" name="image[]" value="'. $img. '">';
				}
								
				?>
			
				<span id="img_err"></span>
			 </div>
			</div>
		</div>
		
		<div class="col-sm-10">
			<input type="submit" class="btn btn-outline-primary form-control sincerely" name="imageUpload" value="Upload Images (2 Max)"/>
			<br/>
			<br/>
			<input type="submit" class="btn btn-outline-primary form-control sincerely" name="deleteImage" value="Delete All Images" <?php if(count($image)==0){echo"disabled=disabled";} ?> onClick="return imageDelete();"/>
		</div>
	  
	  </form>
	  
	  <hr/>
	  <br/>
	 
	  <form class="horizontal" method="post" action="editPost.php">
			<div class="form-group"> 
				<label class="control-label col-sm-2"><h5>Tags:</h5></label>
				<div class="col-sm-10">You can tag your blog however you like, please separate tags with a comma and only
				choose tags relevant to your post! (Max tags: 5)</div>
				<br/>
				<?php global $tags; echo"<div class='container'> <h6>Current Tags:</h6>"; for($e=0; $e<count($tags); $e++){echo "$tags[$e]  ,";}echo"</div>";?>
				<br/>
			 <div class="col-sm-10">
				<input type="text" class="form-control" name="newTags" id="newTags"
				aria-labelledby="new tags"/> 
			 </div>
			 <?php
			 	global $tags;
				//this would take the image array and allow me to access it through the POST
				foreach($tags as $tag)
				{
					echo "<input type='hidden' name='oldTags[]' value='$tag'>";
				}
								
				?>
			 
		</div>
		<div class="col-sm-10">
			<input type="submit" class="btn btn-outline-primary form-control sincerely" name="tagEdit" value="Edit"/>
		</div>
		
		
	  </form>
	  
	  </div>
	  
    </section><!-- End Blog Section -->

  </main><!-- End #main -->
  <?php
	/*
	So, when i get the content back from mysql, it has tags all over it and the quill.js thing doesn't understand html
	so as a workaround, how this works is that the content's tags will be stripped and the pure content without editing
	gets put into the quill.js editor. So, yeah. Its a shit workaround, but still a workaround so that the user's content can be
	put back into the editor. 
	
	
	so the code below is to make a global call to the content and prep it via a tag-strip before handing it over to the 
	javascript which would set the value of the quill.js to whatever the content was.
	*/
	global $content;
	$content= strip_tags($content);
  
  ?>
  <!-- Initialize Quill editor -->
		<script type="text/javascript">
		
		var x = "<?php echo"$content"?>"; 
		
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
			//this sets the blogpost body into the quill text area
			quill.setText(x);
			
		  //this gets the data from the rich text area
		  $('#target').submit(function() {
			$('#quill_json').val(JSON.stringify(quill.getContents()));
			return true;
		});
		  
		</script>

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
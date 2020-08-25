<?php
ob_start();
/*this page is used to allow a user to edit their profile details*/
	//session checker
	session_start();
	if($_SESSION['uName']==""){
		
		Header("Location:login.php?feedback=You must be logged in to access this page...");
		
	}
	require_once 'vendor/autoload.php';
	
	//set up error variables
	$count=0; 
	$feedback="";
	$success="";
	$email="";
	$description="";
	$picture="";
	
	
	//get userID from session and pull the user's data from the database to populate fields
	if(isset($_SESSION['uID'])){
		global $description;
		global $email;
		global $picture;
		
		$uID= $_SESSION['uID'];
		
		//get database connection
		include('dbConnect.php');
		  if($stmt=mysqli_prepare($mysqli, "SELECT tbluser.description, tbluser.pictureID, tbluser.email FROM tbluser WHERE userID=?")){
				  //bind entered parameters to mysqli object
				mysqli_stmt_bind_param($stmt, "i", $uID);
				
				//execute the stmt
				mysqli_stmt_execute($stmt);
				
				//get results of query
				mysqli_stmt_bind_result($stmt, $description, $picture, $email);
						
				mysqli_stmt_fetch($stmt);
		
		mysqli_stmt_close($stmt);
		}//end of stmt
		mysqli_close($mysqli);
	}//end of isset
	
	
	//this isset handles the email insert into the database
	if(isset($_POST['email'])){
		//get user information
		$email=$_POST['uEmail'];
		
		//validate user information
		if($email=="" || $email==null){
			$count++;
			$feedback.="<br/> Please enter an email if you wish to update your contact information.";
		}else if(strlen($email)>100){
			$count++;
			$feedback.="<br/> Your email cannot be more than 100 characters";
		}else if(!preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/',$email)){
			$count++;
			$feedback.="<br/> Your email must be in the format xyz@xyz.com";
		}else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		  $count++;
		  $feedback.="<br/> $email is not a valid email address.";
		}
		
		//sanitize 
		sanitize($email);
		
		
		if($count==0){		
			//insert email
			//make connection to database
			include('dbConnect.php');
			
			if($stmt=mysqli_prepare($mysqli,"UPDATE tbluser SET tbluser.email=? WHERE tbluser.userID=?")){
				//bind parameters
				mysqli_stmt_bind_param($stmt, "si", $email, $uID);
				
				//execute
				if(mysqli_stmt_execute($stmt)){
					$success.="Email successfully updated! You may need to reload this page to see the effects take place.";
				}else{
					$count++;
					$feedback.="<br/> Email could not be updated, an error occured, please contact a technician for assistance.";
				}
			mysqli_stmt_close($stmt);
			}//end of stmt
			mysqli_close($mysqli);
		}//end of count
	}//end of isset
	
	//this isset handles the description insert into the database
	if(isset($_POST['description'])){
		//get delta
		$body=$_POST['body'];
		
		//validate code so it fits the format
		if($body=="" || $body==null){
			$count++;
			$feedback.="<br/> Your post cannot be empty.";
		}else if(strlen($body)>2000){
			$count++;
			$feedback.="<br/> Your post cannot be more than 2000 characters.";
		}else if($body=='{"ops":[{"insert":"\n"}]}'){
			$count++;
			$feedback.="<br/> Your new description cannot be empty.";
		}	
		
		//santize data so there are no scary things in it
		//sanitize($body);
		
		escapeString($body);
		
		//this is a check done to ensure that there are no errors, if there are errors then the render/insert won't run
		if($count==0){
			
			//other code that takes the entered text and turns it into HTML markup
			$markupText = htmlentities($body);
			
			//store the html in the database
			
			insert($body, $uID);
		}//end of count
	}//end of isset
	
	if(isset($_POST['upload'])){
		//set variable to store pictureID
		$pictureID="";
		
		//validate photos
		if(($_FILES['fileUpload']['name'])!=null || ($_FILES['fileUpload']['name'])!=""){
			//code adapted from:https://www.w3schools.com/php/php_file_upload.asp
			//accessed on: 26/03/2019
			$allowed =  array('docx', 'doc', 'pdf', 'jpeg', 'png', 'jpg', 'bmp', 'pjpeg', 'JPEG', 'PNG', 'JPG', 'BMP', 'PJPEG', 'PDF', 'DOCX', 'PPTX');	

			$path = $_FILES['fileUpload']['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			if(!in_array($ext,$allowed)){
					$feedback.="<br/> Document uploaded is not of type: .jpg, .jpeg, .bmp, .pjpeg, .png, .pdf, .doc or .docx";
					$count++;
			}//end of if
		}else{
			$count++;
			$feedback.="<br/> No image submitted.";
		}
		
		if($count==0){
			/*
			//unlink any old file if it exists
			require('DBConnect.php');
			//this won't run on a live server since most hosting platforms disable certain PHP functions
			if($stmt=mysqli_prepare($mysqli, 
			"SELECT tbluser.pictureID FROM tbluser WHERE tbluser.userID=?")){
				//bind params
				mysqli_stmt_bind_param($stmt, 'i', $uID);
				
				//execute
				mysqli_stmt_execute($stmt);
				
				//bind ss
				mysqli_stmt_bind_result($stmt, $pictureID);
				
				if(mysqli_stmt_fetch($stmt)){
					if($pictureID!=null){
						/*This code isn't mine
					Code Author: Sandhya Nair
					Code Accessed On: 3/06/2020
					Code Available At: https://stackoverflow.com/questions/43021477/php-unlink-no-such-file-or-directory
					
					 $base_dir = realpath($_SERVER["DOCUMENT_ROOT"]);
					 $file_delete =  "$base_dir/Sincerely/accountAvatar/$pictureID";
					 if (file_exists($file_delete)) {unlink($file_delete);}
					}
				mysqli_stmt_close($stmt);
				}//end of stmt
			mysqli_close($mysqli);
			}
			*/
			
			//create random number for the file upload
			$rand=rand(1,1000);
		
			if((($_FILES['fileUpload']['type']== 'image/jpg')||

			($_FILES['fileUpload']['type']== 'image/jpeg')||

			($_FILES['fileUpload']['type']== 'image/pjpeg')||

			($_FILES['fileUpload']['type']== 'image/bmp')||

			($_FILES['fileUpload']['type']== 'image/png'))||

			($_FILES['fileUpload']['type']== 'application/pdf')||

			($_FILES['fileUpload']['type']== 'application/doc')

			&&

			($_FILES['fileUpload']['size']<1000000))

			{

				$uploadedFile = $_FILES['fileUpload']['name']."(".$_FILES['fileUpload']['type'].",".ceil($_FILES['fileUpload']['size']/1024).")Kb"."<br />";

			}

				move_uploaded_file($_FILES['fileUpload']['tmp_name'],'accountAvatar/'.$rand.$_FILES['fileUpload']['name']);



			$fileName= $rand.$_FILES['fileUpload']['name'];
			

			//store filename in database
			include('dbConnect.php');
			
			if ($stmt = mysqli_prepare($mysqli,

				"UPDATE tbluser SET tbluser.pictureID=? WHERE tbluser.userID=?")){

				//Bind parameters to SQL Statement Object

				mysqli_stmt_bind_param($stmt, "si", $fileName, $uID);

				

				//Execute statement object and check if successful

				if(mysqli_stmt_execute($stmt)){

					$success.= "Avatar updated Successfully! You may need to reload this page to see the effects take place.";

				}else{

					$feedback.= "<br/>Avatar updated Unsuccessfully. Please contact a technician.";

					$count++;

				}//end of feedback if else 
				mysqli_stmt_close($stmt);
			}//end of stmt
		mysqli_close($mysqli);
		}
	}//end of upload isset
	
	//this handles an insert of the user's description
	function insert($r, $uID){
		global $count;
		global $feedback;
		global $success;
		
		//make database connection
		include('dbConnect.php');
		
		//make stmt
		if($stmt=mysqli_prepare($mysqli, 
		"UPDATE tbluser SET description=? WHERE userID=?")){
			//bind parameters to SQL Object
			mysqli_stmt_bind_param($stmt,"si", $r, $uID);
			
			//execute statement and see if successful
			if(mysqli_stmt_execute($stmt)){
				$success.="Description Updated! You may need to reload this page to see the effects take place.";
				return true;
			}else{
				$feedback.="<br/> Description Update Unsuccessful. Please contact an administrator for assistance!";
				return false;
			}//end of if-else
			mysqli_stmt_close($stmt);
		}//end of stmt
		mysqli_close($mysqli);
		
	}//end of insert
	
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
	
	/*function to sanitize data with mysqli functions 
	*jText is the markup text
	*/
	function sanitize($jText){
		//sanitize form data
		$jText= filter_var($jText, FILTER_SANITIZE_STRING); 
		
		//include escape string function here, this uses the mysqli escape string to prevent special characters from being entered into the db
		escapeString($jText);
		
	}//end of sanitize

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
          <li><a href="userAccount.php">Your Account</a></li>
          <li><a href="editDescription.php">Edit Description</a></li>
        </ol>
        <h2>Edit Account Details</h2>

      </div>
	  
    </section><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="editDescription">
      
	  <!--Content Here :0-->
	  <div class="container">
	  
		<?php
		//error handling and displaying
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
		
		<form class="class-horizontal" action="editDescription.php" method="post" onSubmit="return valEmail(this)">
		
			<div class="form-group">
				<label class="control-label col-sm-3"><h4>Contact Email:</h4></label>
				<div class="col-sm-12">
				<input type="text" class="form-control" name="uEmail" id="uEmail" aria-labelledby="email" value="<?php global $email; echo $email;?>"/> 
			 </div>
			 <span class="error" id="email_err"></span>
			</div>
		
			<div class="col-sm-12">
				<input type="submit" class="btn btn-outline-primary form-control sincerely" name="email" value="Submit"/>
			</div>
		</form>
	  
		<br/>
		<hr>
	  
		<form id="target" class="class-horizontal" action="editDescription.php" method="post">
		
			<div class="form-group">
				<label class="control-label col-sm-3"><h4>Edit Description: <br/>(2000 character limit)</h4></label>
				
				<div class="col-sm-12">
				<small>You can use emotes when you're making your post, click on the right, next to the omega (Ω) symbol.</small>
				<!--The id for the text area has to be 'editor' for the script to recognize it-->
					<textarea id="editor" name="body"></textarea>
					<!--This is needed to store the information that the user enters into the rich text area.-->
				</div>
			</div>
			<div class="col-sm-12">
				<input type="submit" class="btn btn-outline-primary form-control sincerely" name="description" value="Submit" onClick="return valEmail();"/>
			</div>
		
		</form>
		<br/>
		<hr>
		<form class="form-horizontal" action="editDescription.php" method="post" enctype="multipart/form-data">
		
			<div class="form-group"> 
				<label class="control-label col-sm-4"><h4>Upload New Avatar:</h4></label>
				<div class="col-sm-12">
					<p>Please be responsible with the image you upload. If your image is deemed inappropriate whereby it is related to: pornography, 
					substance abuse, general abuse, or harmful in any way, the administrators would address this.</p>
				</div>
				<br/>
				<?php
				global $picture;
					echo"<div class='container'>";
						if($picture!=null ||$picture!="" ){
							echo"<h5>Current Avatar:</h5>";
							echo"<img src='accountAvatar/$picture' style='width:300px; height:200px;'/>";
						}
					echo"</div>";
				
				?>
				<br/>
			 <div class="col-sm-12">
				<!--Code Adapted from:http://www.javascripthive.info/php/php-multiple-files-upload-validation/-->
				<!--Accessed on: 26/11/2017-->
				<input type="file" class="form-control" name="fileUpload" aria-labelledby="Select the image you want to upload as your avatar" multiple/> 
				<span id="img_err"></span>
			 </div>
			</div>
			
			<div class="col-sm-12">
				<input type="submit" class="btn btn-outline-primary form-control sincerely" name="upload" value="Upload"/>
			</div>
		
		</form>
		<br/>
		<div class="col-sm-12">
			<a href='userAccount.php'><button class="btn btn-outline-primary form-control sincerely">Return to Account</button></a>
		</div>
	  </div>
	  

	  <?php
	  
		global $description;
	  
	  ?>
	  <!-- Initialize Quill editor -->
		<script>
		
		var x="<?php echo $description ?>";
		
		CKEDITOR.replace( 'editor' );
		
		//this sets the text inside of the editor
		CKEDITOR.instances['editor'].setData(x);
		
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
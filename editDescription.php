<?php
	namespace DBlackborough\Quill;
	session_start();
	if($_SESSION['uName']==""){
		
		Header("Location:login.php?feedback=You must be logged in to access this page...");
		
	}
	require_once 'vendor/autoload.php';
	
	//set up error variables
	$count=0; 
	$feedback="";
	$success="";
	
	
	
	//get userID from session
	if(isset($_SESSION['uID'])){
		$uID= $_SESSION['uID'];
	}
	
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
		}//end of email validation
		
		//sanitize 
		sanitize($email);
		
		//insert email
		//make connection to database
		include('dbConnect.php');
		
		if($stmt=mysqli_prepare($mysqli,"UPDATE tbluser SET tbluser.email=? WHERE tbluser.userID=?")){
			//bind parameters
			mysqli_stmt_bind_param($stmt, "si", $email, $uID);
			
			//execute
			if(mysqli_stmt_execute($stmt)){
				$success.="<br/> Email successfully updated!";
			}else{
				$count++;
				$feedback.="<br/> Email could not be updated, an error occured, please contact a technician for assistance.";
			}
		}//end of if-stmt
		
	}//end of isset
	
	//this isset handles the description insert into the database
	if(isset($_POST['description'])){
		//get delta
		$quill_json=$_POST['quill_json'];
		
		//validate code so it fits the format
		validate($quill_json);
		
		//santize data so there are no scary things in it
		sanitize($quill_json);
		
		//this is a check done to ensure that there are no errors, if there are errors then the render/insert won't run
		if($count==0){
			//magic code that renders the quill delta into readable text
			try {
				$quill = new \DBlackborough\Quill\Render($quill_json, 'HTML');
				$result = $quill->render();
			} catch (\Exception $e) {
				echo $e->getMessage();
			}
			
			//other code that takes the entered text and turns it into HTML markup
			$markupText = htmlentities($result);
			
			//store the html in the database
			
			insert($result, $uID);
		}//end of count
	}//end of isset
	
	if(isset($_POST['upload'])){
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

			

			require('DBConnect.php');

			

			//store filename in database

			if ($stmt = mysqli_prepare($mysqli,

				"UPDATE tbluser SET tbluser.pictureID=? WHERE tbluser.userID=?")){

				//Bind parameters to SQL Statement Object

				mysqli_stmt_bind_param($stmt, "si", $fileName, $uID);

				

				//Execute statement object and check if successful

				if(mysqli_stmt_execute($stmt)){

					$success.= "<br/>Avatar updated Successfully!";

				

				}else{

					$feedback.= "<br/>Avatar updated Unsuccessfully. Please contact a technician.";

					$count++;

				}//end of feedback if else 
			}
		}
	}//end of upload isset
	
	function insert($r, $uID){
		global $count;
		global $feedback;
		global $success;
		
		//make database connection
		include('dbConnect.php');
		
		//make stmt
		if($stmt=mysqli_prepare($mysqli, 
		"UPDATE tblUser SET description=? WHERE userID=?")){
			//bind parameters to SQL Object
			mysqli_stmt_bind_param($stmt,"si", $r, $uID);
			
			//execute statement and see if successful
			if(mysqli_stmt_execute($stmt)){
				$success.="<br/> Description Updated!";
				return true;
			}else{
				$feedback.="<br/> Description Update Unsuccessful. Please contact an administrator for assistance!";
				return false;
			}//end of if-else
			mysqli_stmt_close($stmt);
		}//end of stmt
		mysqli_close($mysqli);
		
	}//end of insert
	
	/*function to validate the entered user data 
	*$mT is the user's entered text with the markup tags
	*/
	function validate($d){
		global $count;
		global $feedback; 
		
		if($d=="" || $d==null){
			$count++;
			$feedback.="<br/> Your post cannot be empty.";
		}
		
		if(strlen($d)>2000){
			$count++;
			$feedback.="<br/> Your post cannot be more than 2000 characters.";
		}
		
	}//end of validation
	
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
		
		<form class="class-horizontal" action="editDescription.php" method="post" onsubmit="return valEmail(this)">
		
			<div class="form-group">
				<label class="control-label col-sm-3"><h4>Contact Email:</h4></label>
				<?php
				if(isset($_GET['email'])){
				  $email = $_GET['email']; //some_value
				  echo "<div class='col-sm-10'><h5>Current Email:</h5><p>$email</p></div>";
				}
				
				?>
				<div class="col-sm-10">
				<input type="text" class="form-control" name="uEmail" id="uEmail" aria-labelledby="email"/> 
			 </div>
			 <span class="error" id="email_err"></span>
			</div>
		
			<div class="col-sm-10">
				<input type="submit" class="btn btn-outline-primary form-control sincerely" name="email" value="Submit" onClick="return valEmail();"/>
			</div>
		</form>
	  
		<br/>
		<hr>
	  
		<form id="target" class="class-horizontal" action="editDescription.php" method="post">
		
			<div class="form-group">
				<label class="control-label col-sm-3"><h4>Edit Description:</h4></label>
				
				<?php
				if(isset($_GET['description'])){
				  $description = $_GET['description']; //some_value
				  echo "<div class='col-sm-10'><h5>Current Description: </h5> $description</div>";
				}
				
				?>
				
				<div class="col-sm-10">
					<div id="editor">
						
					</div>
					<!--This is needed to store the information that the user enters into the rich text area.-->
					<input type="hidden" id="quill_json" name="quill_json" aria-labelledby="description writing area"/>
				</div>
			</div>
			<div class="col-sm-10">
				<input type="submit" class="btn btn-outline-primary form-control sincerely" name="description" value="Submit"/>
			</div>
		
		</form>
		<br/>
		<hr>
		<form class="form-horizontal" action="editDescription.php" method="post" enctype="multipart/form-data">
		
			<div class="form-group"> 
				<label class="control-label col-sm-4"><h4>Upload New Avatar:</h4></label>
				<div class="col-sm-10">
					Please be responsible with the image you upload. If your image is deemed inappropriate whereby it is related to: pornography, 
					substance abuse, general abuse, or harmful in any way, the administrators would address this.
				</div>
				<br/>
			 <div class="col-sm-10">
				<!--Code Adapted from:http://www.javascripthive.info/php/php-multiple-files-upload-validation/-->
				<!--Accessed on: 26/11/2017-->
				<input type="file" class="form-control" name="fileUpload" aria-labelledby="Select the image you want to upload as your avatar" multiple/> 
				<span id="img_err"></span>
			 </div>
			</div>
			
			<div class="col-sm-10">
				<input type="submit" class="btn btn-outline-primary form-control sincerely" name="upload" value="Upload"/>
			</div>
		
		</form>
		<br/>
		<div class="col-sm-10">
			<a href='userAccount.php'><button class="btn btn-outline-primary form-control sincerely">Return to Account</button></a>
		</div>
	  </div>
	  
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
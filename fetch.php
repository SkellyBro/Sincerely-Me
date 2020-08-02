<?php
session_start();
include('dbConnect.php');
if(isset($_POST['view'])){
	$output='';
	
if(isset($_SESSION['uID'])){
	$uID=$_SESSION['uID'];
}
	
// $con = mysqli_connect("localhost", "root", "", "notif");
if($_POST["view"] != '')
{
   $update_query = "UPDATE tblconfirmedposts SET status = 1 WHERE status=0";
   mysqli_query($mysqli, $update_query);
}


/*
if($stmt=mysqli_prepare($mysqli, "SELECT tblblogpost.heading
FROM tblblogpost, tblconfirmedposts, tbluser
WHERE tblblogpost.postID=tblconfirmedposts.postID
AND tblblogpost.userID=tbluser.userID
AND tbluser.userID=@?
AND tblconfirmedposts.confirmed=1
AND tbluser.position=1
ORDER By tblblogpost.postDate DESC LIMIT 10")){
			//bind post ID for the query
			mysqli_stmt_bind_param($stmt, "i", $uID);
			
			//execute query
			mysqli_stmt_execute($stmt);
			
			//get results
			$result=mysqli_stmt_get_result($stmt);
			
			while($row=mysqli_fetch_array($result))
			{
				
			  $output .= '
			  <li>
			  <a href="#">
			  <strong>'.$row["heading"].'</strong><br />
			  </a>
			  </li>
			  ';
           
			}//end of while loop
}//end of stmt
*/
//this works, do not mess with the query


$query = "
SELECT tblblogpost.heading
FROM tblblogpost, tblconfirmedposts, tbluser
WHERE tblblogpost.postID=tblconfirmedposts.postID
AND tblblogpost.userID=tbluser.userID
AND tbluser.userID=$uID
AND tblconfirmedposts.confirmed=1
AND tbluser.position=1
ORDER By tblblogpost.postDate DESC LIMIT 10
";
$result = mysqli_query($mysqli, $query);
$output = '';
if(mysqli_num_rows($result) > 0)
{
while($row = mysqli_fetch_array($result))
{
  $output .= '
  <li>
  <a href="#">
  <strong>'.$row["heading"].'</strong><br />
  </a>
  </li>
  ';
}
}
else{
    $output .= '<li><a href="#" class="text-bold text-italic">No Notifications Available</a></li>';
}


$status_query = "SELECT * FROM tblconfirmedposts WHERE status=0";
$result_query = mysqli_query($mysqli, $status_query);
$count = mysqli_num_rows($result_query);
$data = array(
   'notification' => $output,
   'unseen_notification'  => $count
);
echo json_encode($data);
}
?>
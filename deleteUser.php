<?php
ob_start();
	/*This script handles deleting a user.*/
	//error handling variables
	$feedback="";
	$count=0;
	$success="";
	$successCount=0;
	
	//array to hold blogpost ID's
	$blogpostID=[];
	$blogCount=0;
	
	//array to hold commentId's*/
	$commentID=[];
	$commentCount=0;
	
	//if the user is deleted
	if(isset($_POST['deleteUser'])){
		global $blogpostID;
		global $blogCount;
		//get the user ID and the page this script was invoked on 
		$userID=$_POST['userID'];
		$page=$_POST['page'];
		
		//minor validation to ensure that nothing messed up
		if($userID=="" || $userID==null){
			$count++;
			$feedback="<br/> User ID for delete could not be found. Please contact a technician for assistance.";
		}
		
		if($count==0){
			/*So, starting from blogposts for the user delete is a good place to start
			So, this code below will get all the blogpost ID's*/
			//make database connection	 
			 include('dbConnect.php');
			//code to get the tags out of the database
				if($stmt=mysqli_prepare($mysqli, 
				"SELECT tblblogpost.postID
				FROM tblblogpost
				WHERE tblblogpost.userID=?")){
					//bind post ID for the query
					mysqli_stmt_bind_param($stmt, "i", $userID);
					
					//execute query
					mysqli_stmt_execute($stmt);
					
						//get results
						$result=mysqli_stmt_get_result($stmt);
					
						while($row=mysqli_fetch_array($result, MYSQLI_NUM))
						{
							foreach ($row as $r)
							{
								$blogpostID[$blogCount]=$r;
								$blogCount++;
								
							}
						}//end of while loop
						mysqli_stmt_close($stmt);
				}//end of stmt
				
				/*Right, so now I should try to do a cascading delete on everything associated with a blogpost*/
				//We'll start with tags.
				include('dbConnect.php');
				
				global $blogpostID;
				
				//so, if there are blogposts we delete
				if(count($blogpostID)!=0){

					foreach($blogpostID as $blog){	
					//delete tags
						if($stmt=mysqli_prepare($mysqli, 
						"DELETE FROM tbltags WHERE tbltags.postID=?")){
							mysqli_stmt_bind_param($stmt, 'i', $blog);
							if(mysqli_stmt_execute($stmt)){
								$success.="All Blogpost tags for the user has been deleted!";
							}else{
								$count++;
								$feedback.="<br/> Blogpost tags could not be deleted.";
							}
							mysqli_stmt_close($stmt);
						}//end of delete tag
						
						if($stmt2=mysqli_prepare($mysqli,
						//delete images
						"DELETE FROM tblimages WHERE tblimages.postID=?")){
							mysqli_stmt_bind_param($stmt2, 'i', $blog);
							if(mysqli_stmt_execute($stmt2)){
								$success.="<br/> All Blogpost images for the user has been deleted!";
							}else{
								$count++;
								$feedback.="<br/> Blogpost images could not be deleted.";
							}
							mysqli_stmt_close($stmt2);
						}//end of delete images
						
						if($stmt3=mysqli_prepare($mysqli,
						//delete confirmation
						"DELETE FROM tblconfirmedposts WHERE tblconfirmedposts.postID=?")){
							mysqli_stmt_bind_param($stmt3, 'i', $blog);
							if(mysqli_stmt_execute($stmt3)){
								$success.="<br/>All blogpost confirmations deleted!";
							}else{
								$count++;
								$feedback.="<br/> Blogpost confirmation could not be deleted.";
							}
							mysqli_stmt_close($stmt3);
						}//end of delete confirmation
						
						//due to the complicated nature of the blogposts, 
						//I have to get the comment information for the blogposts if any and delete those too
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
									if(mysqli_stmt_execute($stmt)){
					
									}else{
										$count++;
										$feedback.=mysqli_stmt_error($stmt);
										//$feedback.="<br/> Comments could not be deleted.";
									}
									mysqli_stmt_close($stmt);
								}//end of delete confirmation

								if($stmt3=mysqli_prepare($mysqli, 
								//delete any reports
								"DELETE FROM tblcommentreport WHERE tblcommentreport.commentID=?")){
									mysqli_stmt_bind_param($stmt3, 'i', $comment);
									
										if(mysqli_stmt_execute($stmt3)){
											$success.="<br/>User comment reports deleted successfully!";
										}else{
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
							if(mysqli_stmt_execute($stmt5)){
								$success.="<br/>All user comments deleted successfully!";
							}else{
								$count++;
								$feedback.="<br/> User comments could not be deleted.";
							}
							mysqli_stmt_close($stmt5);
						}//end of delete confirmation
						
						//delete blogpost (finally)
							if($stmt6=mysqli_prepare($mysqli,
							"DELETE FROM tblblogpost WHERE tblblogpost.postID=?")){
							mysqli_stmt_bind_param($stmt6, 'i', $blog);
							if(mysqli_stmt_execute($stmt6)){
								$success.="<br/>All user blogposts deleted";
							}else{
								$count++;
								$feedback.="<br/>User Blogposts could not be deleted.";
							}
							mysqli_stmt_close($stmt6);
						}//end of delete confirmation
						
					}//end of foreach
				
				}//end of if
				
				//So at this point, the blogposts are deleted but the comments made by the blogger remains
				//So, this code below gets all of the comment ID's for the comments the user has made
				if($stmt2=mysqli_prepare($mysqli, 
				"SELECT tblbloggercomments.commentID
				FROM tblbloggercomments
				WHERE tblbloggercomments.userID=?")){
					//bind post ID for the query
					mysqli_stmt_bind_param($stmt2, "i", $userID);
					
					//execute query
					if(mysqli_stmt_execute($stmt2)){
						//get results
						$result=mysqli_stmt_get_result($stmt2);
					
						while($row=mysqli_fetch_array($result, MYSQLI_NUM))
						{
							foreach ($row as $r)
							{
								$commentID[$commentCount]=$r;
								$commentCount++;
								
							}
						}//end of while loop
						mysqli_stmt_close($stmt2);
					}
				}//end of stmt
				
				if(count($commentID)!=0){
					
					foreach($commentID as $comment){
						//this deletes the record that maintains which blogger made which comment
						if($stmt=mysqli_prepare($mysqli,
						"DELETE FROM tblbloggercomments WHERE tblbloggercomments.commentID=?")){
							mysqli_stmt_bind_param($stmt, 'i', $comment);
							if(!mysqli_stmt_execute($stmt)){
								$count++;
								$feedback.="<br/> Comments could not be deleted.";
							}
							mysqli_stmt_close($stmt);
						}//end of delete confirmation
						
						
						if($stmt2=mysqli_prepare($mysqli, 
						//this deletes any comment reports the user may have accumulated
						"DELETE FROM tblcommentreport WHERE tblcommentreport.commentID=?")){
							mysqli_stmt_bind_param($stmt2, 'i', $comment);
							
								if(mysqli_stmt_execute($stmt2)){
									$success.="<br/>User report deleted successfully!";
								}else{
									$count++;
									$feedback.="<br/> An error occured with deleting the user's report. Please contact a technician for assistance.";
								}
							mysqli_stmt_close($stmt2);
						}//end of stmt
				
						//this does a final delete on any comments the user made
						if($stmt3=mysqli_prepare($mysqli,
						"DELETE FROM tblblogcomments WHERE tblblogcomments.commentID=?")){
							mysqli_stmt_bind_param($stmt3, 'i', $comment);
							if(!mysqli_stmt_execute($stmt3)){
								$count++;
								$feedback.="<br/> Comments could not be deleted.";
							}
							mysqli_stmt_close($stmt3);
						}//end of delete confirmation
					}//end of foreach
				}//end of if
				
				//delete message reports and messages
				if($stmt3=mysqli_prepare($mysqli, 
				"DELETE FROM tblmessagereport WHERE (reportedUser=? OR reportedByID=?)")){
					mysqli_stmt_bind_param($stmt3, 'ii', $userID, $userID);
					
						if(mysqli_stmt_execute($stmt3)){
							$success.="<br/>User report deleted successfully!";
						}else{
							$count++;
							$feedback.="<br/> An error occured with deleting the user's report. Please contact a technician for assistance.";
						}
					mysqli_stmt_close($stmt3);
				}//end of stmt
				
				//delete messages user has made
				if($stmt5=mysqli_prepare($mysqli, 
				"DELETE FROM tblmessage WHERE (tblmessage.originalSender=? OR tblmessage.originalRecipient=?)")){
					mysqli_stmt_bind_param($stmt5, 'ii', $userID, $userID);
					if(mysqli_stmt_execute($stmt5)){
						$success.="<br/>All user messages deleted";
					}else{
						$count++;
						$feedback.="<br/> User messages could not be deleted.";
					}
					mysqli_stmt_close($stmt5);
				}//end of delete tag
				
				//delete user blogger status
				if($stmt6=mysqli_prepare($mysqli, 
				"DELETE FROM tblblogger WHERE tblblogger.userID=?")){
					mysqli_stmt_bind_param($stmt6, 'i', $userID);
					if(!mysqli_stmt_execute($stmt6)){
						$count++;
							$feedback.=mysqli_stmt_error($stmt6);
						$feedback.="<br/> User account could not be deleted.";
					}
					mysqli_stmt_close($stmt6);
				}//end of delete tag
				
				//delete user account
				if($stmt7=mysqli_prepare($mysqli, 
				"DELETE FROM tbluser WHERE tbluser.userID=?")){
					mysqli_stmt_bind_param($stmt7, 'i', $userID);
					if(mysqli_stmt_execute($stmt7)){
						$success="<br/>User account deleted";
					}else{
						$count++;
							$feedback.=mysqli_stmt_error($stmt6);
						$feedback.="<br/> User account could not be deleted.";
					}
					mysqli_stmt_close($stmt7);
				}//end of delete tag
				
				mysqli_close($mysqli);
				
				Header('Location:'.$page.'?success='.$success.'&count='.$count.'&feedback='.$feedback);
		}//end of count
	}//end of delete


?>
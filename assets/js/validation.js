//validation for the registration form
function valReg(frm){
	
	var passed=true;
	var errorCount=0;
	
	var uName= frm.uName.value;

	var email=frm.email.value;
	
	var pass=frm.pass.value;
	
	var cpass=frm.cPass.value;
	
	//username validation
	if(uName==""){
		//display error
		document.getElementById("user_err").innerHTML="Please enter a username.";
		errorCount++;
	}else if (!/^[a-z0-9]+$/i.test(uName)){
		//display error
		document.getElementById("user_err").innerHTML="Your username cannot have special characters or spaces.";
		errorCount++;
	}else if(uName.length<4){
		//display error
		document.getElementById("user_err").innerHTML="Your username cannot be less than 4 characters";
		errorCount++;
	}else if(uName.length>25){
		//display error
		document.getElementById("user_err").innerHTML="Your username cannot be more than 25 characters";
		errorCount++;
	}

	//email validation
	if(email==""){
		document.getElementById("email_err").innerHTML="You must enter your email address";
		errorCount++;
	}
	
	/*
	Code adapted from: https://stackoverflow.com/questions/46155/how-to-validate-an-email-address-in-javascript
	Code Author: rnevius
	Accessed on: 28 Feb 2019. 
	*/
	
	if(!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(email)){
		document.getElementById("email_err").innerHTML="Email is not in format xyz@xyz.com!";
		errorCount++;
	}//end of email validation
	
	//password validation
	if(pass==""){
		//display error
		document.getElementById("pass_err").innerHTML="Please enter a password.";
		errorCount++;
	}else if(pass.length<8){
		//display error
		document.getElementById("pass_err").innerHTML="Your password must be more than 8 characters.";
		errorCount++;
	}
	
	//confirm password validation
	if(cpass==""){			
		//display error
		document.getElementById("cpass_err").innerHTML="Please re-enter your password.";
		errorCount++;
	}
	
	if(pass != cpass){
		//display error
		document.getElementById("cpass_err").innerHTML="Your entered passwords are not equal";
		errorCount++;
	}
	
	if(errorCount !=0)
	{
		return false;
	}
}
/*This helps with login validation*/
function valLog(frm){
	var passed=true;
	var errorCount=0;
	var uName= frm.uName.value;	
	var pass=frm.pass.value;
	
	
	if(uName==""){
		//display error
		document.getElementById("user_err").innerHTML="Please enter a username.";
		errorCount++;
	}else if(!/^[a-z0-9]+$/i.test(uName)){
		//display error
		document.getElementById("user_err").innerHTML="Your username cannot have special characters.";
		errorCount++;
	}
	
	if(pass==""){
		////display error
		document.getElementById("pass_err").innerHTML="Please enter a password";
		errorCount++;
	}else if(pass.length <8){
		//display error
		document.getElementById("pass_err").innerHTML="Password must be more than 8 characters";
		errorCount++;
	}//end of if statement
	

	if(errorCount !=0)
	{
		return false;
	}
}
/*This function helps with blog validation*/
function valBlog(frm){
	var passed=true;
	var errorCount=0;
	var t= frm.title.value;	
	var tags= frm.tags.value;	
	
	if(t=="" || t==null){
		errorCount++;
		document.getElementById("title_err").innerHTML="You must enter a title";
	}else if(t.length>100){
		errorCount++;
		document.getElementById("title_err").innerHTML="Your title cannot be more than 100 characters.";
	}//end of title validation
	
	if(tags=="" || tags==null){
		errorCount++;
		document.getElementById("tag_err").innerHTML="You must enter atleast one tag";
	}//end of tag validation
	
		if(errorCount !=0)
	{
		return false;
	}
}//end of valBlog

/*This function validates the email for the user account edit*/
function valEmail(frm){
	var passed=true;
	var errorCount=0;
	var e= frm.uEmail.value;
	
	if(e=="" || e==null){
		errorCount++;
		document.getElementById("email_err").innerHTML=" Please enter an email if you wish to update your contact information.";
	}else if(!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(e)){
		errorCount++;
		document.getElementById("email_err").innerHTML="Your entered email must be in the format xyz@xyz.com";
	}
	
	if(errorCount !=0)
	{
		return false;
	}
}//end of valEmail

//admin deletion of user confirmation
function blogConfirmation(){
	var x = confirm("Are you sure you want to delete this blogpost?");
	if (x){
      return true;
	}else{
	  return false;
	}
}//end of confirmation

//admin deletion of user confirmation
function adminConfirmation(){
	var x = confirm("Are you sure you want to delete this user's account?");
	if (x){
      return true;
	}else{
	  return false;
	}
}//end of confirmation

//admin deletion of user comment confirmation
function commentConfirmation(){
	var x = confirm("Are you sure you want to delete this user's comment?");
	if (x){
      return true;
	}else{
	  return false;
	}
}//end of confirmation

/*User delete account confirmation*/
function confirmation(){
	var x = confirm("Are you sure you want to delete your account?");
	if (x){
      return true;
	}else{
	  return false;
	}
}//end of confirmation

/*Confirmation for the image delete with the edit post page*/
function imageDelete(){
	var x = confirm("Are you sure you want to delete these images?");
	if (x){
      return true;
	}else{
	  return false;
	}

}//end of delCon
/*This function is used to validate the post confirmation page where a user can select a reason to deny a post.*/
function valReason(frm){
	var passed=true;
	var errorCount=0;
	var r= frm.reason.value;
	
	if(r=="" || r==null){
		errorCount++;
		document.getElementById("reason_err").innerHTML="You must select a reason to deny the post.";
	}
	
		if(errorCount !=0)
	{
		return false;
	}
}

/*This function is used to validate the post confirmation page where a user can select a reason to deny a post.*/
function valReport(frm){
	var passed=true;
	var errorCount=0;
	var r= frm.reason.value;
	
	if(r=="" || r==null){
		errorCount++;
		document.getElementById("reason_err").innerHTML="You must select a reason to report this user.";
	}
	
		if(errorCount !=0)
	{
		return false;
	}
}

/*This helps with forgot password validation*/
function valUName(frm){
	var passed=true;
	var errorCount=0;
	var uName= frm.uName.value;	
	var email= frm.email.value;
	
	
	if(uName==""){
		//display error
		document.getElementById("user_err").innerHTML="Please enter a username.";
		errorCount++;
	}else if(!/^[a-z0-9]+$/i.test(uName)){
		//display error
		document.getElementById("user_err").innerHTML="Your username cannot have special characters.";
		errorCount++;
	}

	if(email==""){
		//display error
		document.getElementById("email_err").innerHTML="Please enter an email";
		errorCount++;
	}else if(!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(email)){
		errorCount++;
		document.getElementById("email_err").innerHTML="Your entered email must be in the format xyz@xyz.com";
	}
	
	if(errorCount !=0)
	{
		return false;
	}
}

/*This helps with resey password validation*/
function valPass(frm){
	var passed=true;
	var errorCount=0;
	var pass=frm.pass.value;
	var cpass=frm.cPass.value;
	
	
	//password validation
	if(pass==""){
		//display error
		document.getElementById("pass_err").innerHTML="Please enter a password.";
		errorCount++;
	}else if(pass.length<8){
		//display error
		document.getElementById("pass_err").innerHTML="Your password must be more than 8 characters.";
		errorCount++;
	}

	//confirm password validation
	if(cpass==""){			
		//display error
		document.getElementById("cpass_err").innerHTML="Please re-enter your password.";
		errorCount++;
	}

	if(pass != cpass){
		//display error
		document.getElementById("cpass_err").innerHTML="Your entered passwords are not equal";
		errorCount++;
	}
	
	if(errorCount !=0)
	{
		return false;
	}
}

function valPassReset(frm){
	var passed=true;
	var errorCount=0;
	var pass=frm.pass.value;
	var npass=frm.nPassword.value;
	var cpass=frm.cPassword.value;
	
	//password validation
	if(pass==""){
		//display error
		document.getElementById("pass_err").innerHTML="Please enter a password.";
		errorCount++;
	}else if(pass.length<8){
		//display error
		document.getElementById("pass_err").innerHTML="Your password must be more than 8 characters.";
		errorCount++;
	}

	//password validation
	if(npass==""){
		//display error
		document.getElementById("npass_err").innerHTML="Please enter a password.";
		errorCount++;
	}else if(npass.length<8){
		//display error
		document.getElementById("npass_err").innerHTML="Your password must be more than 8 characters.";
		errorCount++;
	}

	//confirm password validation
	if(cpass==""){			
		//display error
		document.getElementById("cpass_err").innerHTML="Please re-enter your password.";
		errorCount++;
	}

	if(npass != cpass){
		//display error
		document.getElementById("cpass_err").innerHTML="Your entered passwords are not equal";
		errorCount++;
	}
	
	if(errorCount !=0)
	{
		return false;
	}
}
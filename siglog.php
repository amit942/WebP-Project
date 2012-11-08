<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SignUp | LogIn | Upload2Share</title>
<style type="text/css">
.signup {
	position: absolute;
	left: 350px;
	top: 200px;
	border: 1.5px ridge #E2E2E2;
}
.login {
	position: absolute;
	top: 200px;
	right: 220px;
	border: 1.5px ridge #E2E2E2;
}
</style>
</head>

<body style="background-image:url(picture/topkelel1.jpg)">

<div style="font-family:'Arial Black', Gadget, sans-serif; font-size:18px; color:#CCFFFF; position:absolute; top:160px; left:370px;" >Sign Up Here!!</div>

<div class="signup" style="border-radius:15px;" >
<form method="post" action="signup.php" >
<pre style="font-family:'Arial Black', Gadget, sans-serif; font-size:14px; color:#999999;">
Full Name:       <input type="text" name="name" style="width:260px; height:25px;"  /><br />
Password :       <input type="password" name="pass" style="width:260px; height:25px;"  /><br />
Email    :          <input type="email" name="email" style="width:260px; height:25px;" /><br />
About Yourself:<textarea rows="2" cols="30" name='message' id='message'></textarea><br />
</pre>
<input type='submit' name='Submit' value='SignUp' style="background-color:#BAF3F3; width:112px; float:right;" />
</form>

</div>

<div style="position:absolute; top:200px; left:750px; width:5px; height:380px; background-color:#C0C0C0; border-top-left-radius:2px; border-top-right-radius:2px;" ></div>
<div style="position:absolute; top:200px; left:765px; width:5px; height:400px; background-color:#C0C0C0; border-top-left-radius:2px; border-top-right-radius:2px; border-bottom-left-radius:10px;" ></div>
<div style="position:absolute; top:200px; left:780px; width:5px; height:380px; background-color:#C0C0C0; border-top-left-radius:2px; border-top-right-radius:2px;" ></div>

<div style="position:absolute; top:160px; left:753px; color:#AFAFAF; font-size:20px;" >OR</div>
<div style="position:absolute; top:160px; left:835px; color:#A5D8D7; font-size:20px; font-family:Arial, Helvetica, sans-serif;" >Login Here!</div>



<div class="login" style="border-radius:15px;" >
<form method="post" action="checklogin.php"  >
<pre style="font-family:'Arial Black', Gadget, sans-serif; font-size:14px; color:#999999;">
Email       :<input type="text" name="uname" style="width:260px; height:25px; "  /><br />
Password :<input type="password" name="pswd" style="width:260px; height:25px;"  /><br />
</pre>
<input type='submit' name='submit' value='Login' style="background-color:#BAF3F3; width:112px; float:right;" />
</form>
</div>


<div class="bottom" style="position:absolute; top:600px;" ><?php include("footer.php"); ?></div>
</body>
</html>
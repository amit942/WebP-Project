<?php


include("query.php");

session_start();
    // Get the login credentials from user
	$_SESSION['submitted'] = true;
	
if(!isset($_SESSION['uname']))
{
	
//$username = $_POST['uname'];
//$userpassword = $_POST['pswd'];
    // Secure the credentials

$username = mysql_real_escape_string($_POST['uname']);

$userpassword = mysql_real_escape_string($_POST['pswd']);

$check="SELECT * FROM userdata WHERE email = '$username' and password = '$userpassword';";
/*
  $name=mysql_query($usern,$connect);  
  $pass=mysql_query($pwd,$connect);

    $uname=mysql_fetch_array($name);
	$passw=mysql_fetch_array($pass);
  */
  $result = mysql_query($check) or die ("unable to verify user because " . mysql_error());
  
    $count = mysql_num_rows($result);

if ($count >= 1)
     {
        $_SESSION['loggedIn'] = "true";
		$_SESSION['username'] = $username;
		$_SESSION['email'] = $username;
		$_SESSION['pwd'] = $userpassword;
        header("Location: http://localhost/my%20uploadNshare%20project/profile.php");
    }
else
    {
        $_SESSION['loggedIn'] = "false";
        echo "<script>alert('Login failed, username or password incorrect.');</script>";
		header("location: logerror.php");
    }

      }

else 
{
	echo "<a href='siglog.php' >login first</a>";
}
/*
while ($row = mysql_fetch_array($result)) 
{ 
   // display information for each row 
   if($username== $row[name])
   {
	   echo "got matched";
   }
   else
   {
	   echo "<br />" . "Name: $row[name] <br /> password: $row[password] " . "<br />"; 
   }
} 

	
	//aur yeh bhi try kar sakte hai..
	$query = "SELECT COUNT(`username`) AS `total` FROM `user` WHERE `username` = '$username' AND `password` = '$userpassword'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	if($row['total'] == 1)
	{
	//username and password found
	}
	else
	{
	//username and password not found
	}
*/

?>
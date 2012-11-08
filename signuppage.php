<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload2Share | <?php echo $name; ?></title>
<style type="text/css">

.body {
	background-color: #690;
	background-image: url(brainwheel.jpg);
}

</style>
</head>
<body class="body">

<?php
/*
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];
$password = $_POST['pass'];
*/

session_start();

if(isset($_SESSION['name']))
{/* user is logged in*/
echo "logged in already | Don't Fool Around";
}
else
{
	//mysql_real_escape_string($_POST['dob'])
	
$_SESSION['name']= mysql_real_escape_string($_POST['name']);
$_SESSION['email']= mysql_real_escape_string($_POST['email']);
$_SESSION['password']= mysql_real_escape_string($_POST['pass']);
$_SESSION['message']= mysql_real_escape_string($_POST['message']);

}


$name = mysql_real_escape_string($_POST['name']);
$email = mysql_real_escape_string($_POST['email']);
$password = mysql_real_escape_string($_POST['pass']);
$hpass = md5($password);
$message = mysql_real_escape_string($_POST['message']);

$connect = mysql_connect("localhost","username","password");
if (!$connect)
  {
  die('Could not connect: ' . mysql_error());
  }
  
mysql_select_db("signup_data", $connect);

$sql="INSERT INTO userdata (name, email, password, message)
VALUES
('$name','$email','$hpass','$message')";

if (!mysql_query($sql,$connect))
  {
  die('Error: ' . mysql_error());
  }
  
  mysql_close($connect);
  
 echo "<script> alert('Thanx! For Connecting With Us. login To Enjoy Our Services!!'); </script>"; 
header("Location: http://localhost/my%20uploadNshare%20project/profile.php");
exit;

?>

</div>
</body>
</body>
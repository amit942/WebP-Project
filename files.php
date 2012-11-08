<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Your Files | Upload2Share</title>
<style type="text/css">
.body .filediv {
	position: absolute;
	left: 250px;
	top: 100px;
	width: 760px;
	border-top-width: 2.5px;
	border-right-width: 2.5px;
	border-bottom-width: 2.5px;
	border-left-width: 2.5px;
	border-top-style: inset;
	border-right-style: outset;
	border-bottom-style: groove;
	border-left-style: inset;
	border-right-color: #F8F8F8;
	border-bottom-color: #D4D4D4;
	border-top-color: #F5F5F5;
	border-left-color: #F3F3F3;
}
.body {
	background-color: #E7E7E7;
}
</style>
</head>

<body class="body">
<b style="color:#B9AA57; position:absolute; top:50px; left:588px; font-size:20px; text-decoration:underline;">Your All Files</b>
<div class="filediv" >
<?php
require_once("query1.php");
session_start();
$name = $_SESSION['fname'];


$things = mysql_query("select * from uplofile where email = '$name'");
while ($getdata = mysql_fetch_array($things))
{
	$imgsrc = $getdata['fdir'];
	$imgname = $getdata['fname'];
	//echo $getdata['fdir'];
	echo "&nbsp;&nbsp;<a href='$imgsrc' style='text-decoration:none;'  >";
	echo "<img src='$imgsrc' width='350' height='350' title='$imgname' />";
	echo "</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
}

?>

</div>
</body>
</html>
<?php
$err = array(); 

$file_name = addslashes($_FILES['file']['name']);
/* $img_sized = getimagesize($_FILES['file']['tmp_name']); */
@$file_size = $_FILES['file']['size'];
$filedata = addslashes(file_get_contents($_FILES['file']['tmp_name']));
echo $file_name ."<br />". "img name" . "<br />";
echo $file_size ."<br />". "img size" . "<br />";



function getExtension($str)
    {
$i = strrpos($str,".");
if (!$i) { return ""; }
$l = strlen($str) - $i;
$ext = substr($str,$i+1,$l);
return $ext;
    }
$extension = getExtension($file_name);
$extension = strtolower($extension);

//yeh unique file name create karega...
$image_named_uniq = uniqid().'.'.$extension;

//yaha pe directory ke name banaye hai..
$upload_path_dis = 'upload/'.$image_named_uniq;

$date = "On " . date("F Y h:i:s A");


$uname = "amitdas1stTime";
/*
if (!isset($_SESSION['uname']))
$err[] = "You need to login";

else
{
$uname = $_SESSION['uname']; //session username

if(empty($sub) && empty($msg) && empty($filed))
$err[] = "All field required";
else
{
if(empty($sub))
$err[] = "Subject Requried";

if(empty($msg))
$err[] = "Message Requried";

if(empty($filed))
$err[] ="SORRY, you have to be upload a image";
else
{ 
if($img_sized == FALSE)
{
$err[] ="That's not an image";  
}
}
}
}


if(!empty($err))
{
foreach($err as $er)
{
echo "<font color=red>$er</font><br/>";
}
}

else
{  */
	
$connect=mysql_connect("localhost","username","password");
if (!$connect)
  {
  die('Could not connect: ' . mysql_error());
  }
  mysql_select_db("upload", $connect);

                             /* uid	 uniquename	        filesize	fdirectory */
$sql= "INSERT INTO site VALUES ('','$image_named_uniq','$file_size', '$upload_path_dis') ";

if(!$sql)
echo "Can't upload your data" . mysql_error();
if(!move_uploaded_file($_FILES['file']['tmp_name'],$upload_path_dis . $image_named_uniq))
{
die('File Not Uploading');
}
else
{
echo "data was saved/uploaded";
/*print "<script>";
print " self.location='" . $_SERVER['REQUEST_URI'] . "';";  Comment this line if you don't want to redirect
print "</script>";
*/

}
mysql_close($connect);       

//}       



?>



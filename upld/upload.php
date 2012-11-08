<?php
if(isset($_POST['submit']) == "Submit")
{   
$err = array(); 
$filed = addslashes($_FILES['file']['tmp_name']);
$img_named =    addslashes($_FILES['file']['name']);
// $img_sized1 = getimagesize($_FILES['file']['tmp_name']);
@$img_sized = $_FILES['file']['size'];
$imgd = addslashes(file_get_contents($_FILES['file']['tmp_name']));
echo $img_named . " img name" . "<br />";
echo $img_sized . " img size" . "<br />";
echo $filed . "file direc" . "<br />";


function getExtension($str)
    {
$i = strrpos($str,".");
if (!$i) { return ""; }
$l = strlen($str) - $i;
$ext = substr($str,$i+1,$l);
return $ext;
    }
$extension = getExtension($img_named);
$extension = strtolower($extension);
$image_named_uniq = uniqid().'.'.$extension;

$upload_path_dis = 'upload/';

$date = date("Y-m-d H:i:s");


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
*/

if(!empty($err))
{
foreach($err as $er)
{
echo "<font color=red>$er</font><br/>";
}
}

else
{
$connect=mysql_connect("localhost","amitdas","@nitc");
if (!$connect)
  {
  die('Could not connect: ' . mysql_error());
  }
  mysql_select_db("signup", $connect);

  
$sql= "INSERT INTO uplodata VALUES ('', 'upload/$image_named_uniq', '$image_named_uniq','file upload', 'upoad', 'take this', '$uname', '$date' ) ";
mysql_query($sql) or die("can't save yr file in db" . mysql_error());
if(!$sql)
echo "Can't submit your discussion" . mysql_error();
if(!move_uploaded_file($_FILES['file']['tmp_name'],$upload_path_dis . $image_named_uniq))
{
die('File Not Uploading');
}
else
{
echo "<b>data was saved/uploaded</b>";
/*print "<script>";
print " self.location='" . $_SERVER['REQUEST_URI'] . "';";  Comment this line if you don't want to redirect
print "</script>";
*/

}
mysql_close($connect);       
}       

}

?>

<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" 
name="discussion" enctype="multipart/form-data">
<b>Select your image</b>&nbsp;
<input type="hidden" name="MAX_FILE_SIZE" value="62914560"  />
<input type="file" name="file" style="font-family:Arial, Helvetica, sans-serif; background-color:#669999; color:#06C; width:190px; height:25px;" />&nbsp;<input type="submit" name="submit" value="Submit" style="height:27px; width:95px; background-color:#808080;" class="submit"><br>

</form>
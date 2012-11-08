<!--
<html>
<head>
<script src="jquery.js"></script>
<script>
$(document).ready(function(){
  $("button").click(function(){
    $("p").toggle();
  });
});
</script>

<script src="jquery.js"></script>
<script>
$(document).ready(function(){
  $("#hide").click(function(){
    $("p").hide();
  });
  $("#show").click(function(){
    $("p").show();
  });
});
</script>
</head>
<body>
-->

<?php

if(isset($_POST['upload']) == "upload")
  {   
		$err = array(); 
		$filed = addslashes($_FILES['file']['tmp_name']);
		$img_named =    addslashes($_FILES['file']['name']);
		$fname = $img_named;
		$ftsize = $_FILES['file']['size'];
		$fsize = $ftsize/1024;
		// $img_sized1 = getimagesize($_FILES['file']['tmp_name']);
		@$img_sized = $_FILES['file']['size'];
		$imgd = addslashes(file_get_contents($_FILES['file']['tmp_name']));
		//echo $img_named . " img name" . "<br />";
		//echo $img_sized . " img size" . "<br />";
		//echo $filed . "file direc" . "<br />";
		
		$_SESSION['flname'] = $fname;  //use for updating file in next page..

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

//$pflname = $_SESSION['email'];


	$date = date("Y-m-d H:i:s");

//making a direc and saving file in that dirc...
//$thisdir = getcwd(); 
	$uplo = "upload";
	$pflname = "/" . $_SESSION['email'];
	$fnameunq = "/" . $image_named_uniq;
	
	
     $fdir = $uplo . $pflname . $fnameunq ;
	
	$category = "test cat";
	$subcategory = "test sub cat";
	$email = $name;
	$upload_path_dis = "upload/" ;


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
   mysql_select_db("pfluplofile", $connect);

  
	$sql= "INSERT INTO uplofile VALUES ('$email','$fname', '$fsize','$fdir', '$category', '$subcategory','$date' ) ";
	mysql_query($sql) or die("can't save yr file in db" . mysql_error());
	if(!$sql)
	 echo "Can't submit your discussion" . mysql_error();
  if(!move_uploaded_file($_FILES['file']['tmp_name'],$fdir))
   {
      die('File Not Uploading');
   }
  else
   {
      echo "<b>data was saved/uploaded</b>&nbsp;&nbsp;";
	  echo "<a href='#' style='text-decoration:underline; color:#f24567; ' onclick=`showPopUp('dialog');` "; 
   }
  mysql_close($connect);       
 }       

}




/* Step 1. I need to know the absolute path to where I am now, ie where this script is running from...
$thisdir = getcwd(); 
$uplo = "/upload";
$pflname = "/" . $uname;
*/ 

/* Step 2. From this folder, I want to create a subfolder called "myfiles".  Also, I want to try and make this folder world-writable (CHMOD 0777). Tell me if success or failure...  

if(mkdir($thisdir . $uplo . $pflname , 0777)) 
{ 
   echo "Directory has been created successfully..."; 
} 
else 
{ 
   echo "Failed to create directory..."; 


} */

/*<!--

*/

?>
<!--
<button></button>
<p><a href='#' style='text-decoration:underline; color:#f24567; ' onclick="showPopUp('dialog');" ><b>click to add category | will Help for Searching</b></a></p>

-->

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
	//$pflname = "/" . $uname;
	$fnameunq = "/" . $image_named_uniq;
	
	$fildir = $uplo . $image_named_uniq;
/*
	if(mkdir($uplo . $pflname , 0777)) 
	{ 
	   $fdir = $uplo . $pflname . $fnameunq ;
	} 
*/
	$category = "test cat";
	$subcategory = "test sub cat";
	
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
  $connect=mysql_connect("localhost","username","password");
   if (!$connect)
    {
       die('Could not connect: ' . mysql_error());
    }
   mysql_select_db("signup", $connect);
  
	$sql= "INSERT INTO uplodata VALUES ('','$fildir','$fname', 'public file','Saving This From Main Page', 'Public','$date' ) ";
	mysql_query($sql) or die("can't save yr file in db" . mysql_error());
	if(!$sql)
	 echo "Can't submit your discussion" . mysql_error();
  if(!move_uploaded_file($_FILES['file']['tmp_name'],$upload_path_dis.$image_named_uniq))
   {
      die('File Not Uploading');
   }
  else
   {
      echo "<b>data was saved/uploaded</b>&nbsp;&nbsp;";
	  echo "<a href='siglog.php'  ><b>click here to process your file</b></a>";

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

?>
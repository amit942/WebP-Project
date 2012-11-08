<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload File | Upload2Share</title>
</head>

<body>

<?php
$filename = $_POST['name'];
$tags = $_POST['tags'];
$category = $_POST['category'];
$description = $_POST['Descrp'];
session_start();
$flname = $_SESSION['flname'];


//echo $filename . "<br />" . $tags . "<br />" . $category . "<br />" . $description;

include("query1.php");
$emailf = $_SESSION['email'];

$sqlq =mysql_query("UPDATE  uplofile SET  fname =  '$filename',category='$category',subcategory='$tags' WHERE  email =  '$emailf'  AND  category =  'test cat' AND fname = '$flname' AND  subcategory =  'test sub cat';");

if(!$sqlq)
{
	echo "can't update your file | <b>file is not uploaded in our database.try to upload again</b>";
	
}
else
{echo "updated your file.. thanx!!";}


/*
UPDATE  `pfluplofile`.`uplofile` SET  `fname` =  'marijawa.jpg',
`category` =  'dear friend',
`subcategory` =  'things for friend' WHERE  `uplofile`.`email` =  'kuna nuna' AND  `uplofile`.`fname` =  '677.jpg' AND  `uplofile`.`fsize` =  '68.9580078125' AND  `uplofile`.`fdir` =  'upload//50961b69040b4.jpg' AND `uplofile`.`category` =  'test cat' AND  `uplofile`.`subcategory` =  'test sub cat' AND  `uplofile`.`date` =  '2012-11-04' LIMIT 1 ;
*/

?>
<br />
<a href="profile.php" ><b>go back</b></a>

</body>
</html>
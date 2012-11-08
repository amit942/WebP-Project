
<?php
/*
if(isset($_POST['searchf']))
{
	echo "got it";
	
}
else
{
	header("Location: /error.php");
}
*/
if(isset($_POST['searchf']))
{
	$connect=mysql_connect("localhost","amitdas","@nitc");
      if (!$connect)
        {
           die('Could not connect to database: ' . mysql_error());
        }
      mysql_select_db("pfluplofile", $connect) or die("can't select specific database" . mysql_error());
	  $searfile = $_POST['uplo1'];
	  $sear = "select * from uplofile where category='$searfile';";
  	  $resf = mysql_query($sear);
	  
 if(mysql_num_rows($resf)>0)
  {
	  
	  while($data = mysql_fetch_array($resf))
	  {
		  $gotf = $data['fdir'];
		  echo '<a href="http://localhost/my%20uploadNshare%20project/"' . $gotf . '>chick here to download this file</a> <br />';
		  echo 'http://localhost/my%20uploadNshare%20project/' . $gotf;
		  $link = 'http://localhost/my%20uploadNshare%20project/' . $gotf;
		  
		   
		 
		 //header('Content-disposition: inline'); 
	     //readfile($link);
	  }
	  /* this code is to make downloadable file..
	  
	        $file = file_get_contents('$link');
			header('Content-Type: image/jpg'); //yaha pe image/jpg ke satte pe application/zip tha...
			header('Content-Disposition: attachment; filename="some.jpg"');  //yaha pe some.jpg ke jageh pe some.zip tha...
			header('Content-Length: ' . strlen($file));
			echo $file;
	  */
   }
		
	else
	{
		header("Location:http://localhost/my%20uploadNshare%20project/notfound.php");
	}
}
else
{
	header("Location: http://localhost/my%20uploadNshare%20project/emptyinput.php");
}

?>

<br /><img src='http://localhost/my%20uploadNshare%20project/<?php echo $gotf; ?> ' style="width:250px; height:250px; position:absolute; top:150px; left:45px;" />
		  


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload2Share |  Profile </title>


<style type="text/css">

.body {
	background-color: #690;
	background-image: url(picture/fancloud2.jpg);
}

.body .bottom {
	position: relative;
	width: auto;
	top: 555px;
}
.body .recent {
	position: absolute;
	height: 200px;
	width: 200px;
	left: 95px;
	top: 250px;
	border: 1.5px ridge #C1C1C1;
}
.body .topnav {
	background-color: #505;
	position: absolute;
	height: 45px;
	width: 45px;
}
.body .topnav ul {
	list-style-type: none;
	margin: 0px;
	padding: 0px;
	overflow: hidden;
}
.body .topnav ul li {
	float: left;
}
.body .topnav ul li a {
	display: block;
	background-color: #FF7F00;
	width: 60px;
}
</style>
<style type="text/css">

.body nav{
	margin:auto;
	text-align: center;
	position: absolute;
	width: 97%;
	left:30px;
	top:40px;
	}

.body nav ul ul{
	display: none;
	}

.body nav ul li:hover > ul {
	display: block;
	}


.body nav ul{
	background: #efefef;
	background: linear-gradient(top, #efefef 0%, #bbbbbb 100%);
	background: -moz-linear-gradient(top, #efefef 0%, #bbbbbb 100%);
	background: -webkit-linear-gradient(top, #efefef 0%,#bbbbbb 100%);
	box-shadow: 0px 0px 9px rgba(0,0,0,0.15);
	padding: 0 10px;
	border-radius: 5px;
	list-style: none;
	position: relative;
	display: inline-table;
	width: 100%;
	}
.body nav ul li .logo {
	background: linear-gradient(top, #A3A3A3 0%, #bbbbbb 100%);
	background: -moz-linear-gradient(top, #A3A3A3 0%, #bbbbbb 100%);
	background: -webkit-linear-gradient(top, #A3A3A3 0%,#bbbbbb 100%);
}

/*nav ul:after{
		content: ""; clear: both; display: block;
	}
*/
.body nav ul li{
	float: left;
	}
.body nav ul li ul {
	width: 175px;
}
.body nav ul li:hover{
	background: #4b545f;
	border-radius:16px;
	/*  
	
	background: linear-gradient(top, #4f5964 0%, #5f6975 40%);
	background: -moz-linear-gradient(top, #4f5964 0%, #5f6975 40%);
	background: -webkit-linear-gradient(top, #4f5964 0%,#5f6975 40%);
	
	*/
	}
.body nav ul li:hover a{
	color: #fff;
	}
		
.body nav ul li a{
	display: block; padding: 16px 65px;
	color: #757575; text-decoration: none;
	}
			
		
.body nav ul ul{
	background: #5f6975; border-radius: 5px; padding: 0;
	position: absolute; top: 100%;
	}
.body nav ul ul li{
	float: none; 
	border-top: 1px solid #6b727c;
	border-bottom: 1px solid #575f6a; position: relative;
	border-radius:10px;
	}
.body nav ul ul li a{
	padding: 15px 40px;
	color: #fff;
	border-radius:10px;
	}	
.body nav ul ul li a:hover{
	background: #4b545f;
	border-radius:10px;
	}
		
.body nav ul ul ul{
	position: absolute; left: 100%; top:0;
	}
	
	.body #dialog {
	display:    none;
	left:       450px;
	top:        200px;
	width:      300px;
	height:     325px;
	position:   absolute;
	z-index:    100;
	padding:    2px;
	font:       10pt tahoma;
	border:     1px solid gray;
	background-color:#CCC;
	border-radius:15px;
	border-top-right-radius:1px;
}
.body #popUpDiv 
{
	position:absolute;
	background:url(pop-back.jpg) no-repeat;
	width:400px;
	height:460px;
	border:5px solid #000;
	z-index: 9002;
	left: 500px;
	top: 300px;
}
.body .popupClass1 { width:100px;height:300px;background-color:red; }
.body .popupClass2 { width:300px;height:100px;background-color:green; }
.body #cover {
	display:        none;
	position:       absolute;
	left:           0px;
	top:            0px;
	width:          100%;
	height:         100%;
	filter:         alpha(Opacity = 50);
	opacity:        0.5;
	-moz-opacity:   0.5;
	-khtml-opacity: 0.5;
	background-color: Gray;
}

		
</style>
<script type="text/javascript" >
//yaha se popup ka js script hai..
        function showPopUp(el) {
            var cvr = document.getElementById("cover")
            var dlg = document.getElementById(el)
            cvr.style.display = "block"
            dlg.style.display = "block"
            if (document.body.style.overflow = "hidden") {
                cvr.style.width = "1024"
                cvr.style.height = "100%"
            }
        }
        function closePopUp(el) {
            var cvr = document.getElementById("cover")
            var dlg = document.getElementById(el)
            cvr.style.display = "none"
            dlg.style.display = "none"
            document.body.style.overflowY = "scroll"
        }
		//yaha tak tha js of popup..
		
function checkForm()
{
	
    var w=document.forms["signup"]["name"].value;
	var x=document.forms["signup"]["tags"].value;
	var y=document.forms["signup"]["category"].value;
	var z=document.forms["signup"]["Descrp"].value;
	
	   if(w!=="" )
	    {
		     if(x!=="")
	          {
		         if(y!=="")
	             {
		               if(z!=="")
	                   {
	                    	return true;
				       }
	                   else
					   {alert("please Write some Description!!"); return false;
					   }
	
	              }
	              else{alert("please select some category!!"); return false;}
	
	
	          }
	          else{alert("please add some tags!!");return false;}
	
	    }
	    else
	    {	alert("please give name to your file!!");
		    return false;
	    }
	
	/*var a=document.getElementsById('filename');
	var c=document.getElementsById('filetags');
	var d=document.getElementsById('category');
	var e=document.getElementsById('description');
	
	if(a && b && c && d && e >= 1)
	{
		alert("fill all the fields!!" );
		return true;
	}
	else 
	{
		alert("fill all the fields!!" );
        return false;
	}
	*/
	
	
	
}
</script>

</script>


</head>
<body class="body">
<nav>
  <ul>
    
	<li><div class="logo" style="background-color:#A3A3A3; border-radius:15px;" ><a href="#" ><b style="font-size:18px; " >Upload2Share</b></a></div></li>
    <li><a href="#" style="position:absolute; right:125px;" >Browse</a></li>
    <li></li>
    
    <li><a href="#" style="position:absolute; right:5px;" >Profile</a>
             <ul style="border-radius:10px; position:absolute; right:5px;">
				<li><a href="#"  >Edit Profile</a></li>
				<li><a href="#"  >Change Wall pic</a></li>
			</ul>
    </li>   
  </ul>
</nav>

<?php 
session_start();
if(!isset($_SESSION['username']))
{
  $name=$_SESSION['username'];
  
  
	header("Location: http://localhost/my%20uploadNshare%20project/uploadmain.php");
    exit;}
else
{
  $name= $_SESSION['email'];
  $pwd= $_SESSION['pwd'];
  
	$connect = mysql_connect("localhost","","@nitc");
       if (!$connect)
         {
		    die('Could not connect: ' . mysql_error());
 		 }
    mysql_select_db("signup_data", $connect);
	$qery = "select name from userdata where email='$name' and password='$pwd'";
    $ccode4 = mysql_query($qery);
	
	while($res = mysql_fetch_array($ccode4))
	{
	  $uname = $res['name'];

  include("loginheader.php");
  //echo "Thank You For Connecting With Us!!  ".$name;
	}
  
}
?>

<div class="bottom" >
<?php
    include("footer.php");
?>
</div>

<div class="recent" >
<b style="color:#003300;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Your Recent Files</b><br />
<?php  
require_once("query1.php");

	$_SESSION['fname'] = $name;


$things = mysql_query("select * from uplofile where email = '$name' order by email ASC LIMIT 0,4");
while ($getdata = mysql_fetch_array($things))
{
	$imgsrc = $getdata['fdir'];
	$imgname = $getdata['fname'];
	//echo $getdata['fdir'];
	echo "&nbsp;&nbsp;<a href='$imgsrc' style='text-decoration:none;'  >";
	echo "<img src='$imgsrc' width='85' height='85' title='$imgname' />";
	echo "</a>&nbsp;";
}	

?>
<br /><br />
&nbsp;&nbsp;&nbsp;<a href="files.php" style="text-decoration:underline; color:#066;" >click here for more files</a>
</div>

<div style="position:absolute; top:250px; left:450px;">
__________________<br />
Upload Your Files Here
<?php include("upld/uploadf.php");  ?>


<label for="file" ></label>
<form onsubmit="showPopUp('dialog');" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" 
name="discussion" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="62914560"  />
<input type="file" name="file" style="font-family:Arial, Helvetica, sans-serif; background-color:#669999; border-radius:8px; color:#06C; width:322px; height:25px;" />&nbsp;<input type="submit" name="upload" value="Upload"  style="height:27px; border-radius: 5px; border-bottom-right-radius:10px; border-top-left-radius:10px; width:150px; background-color:#808080;" ><br>

</form>
</div>


<div id="cover"></div>
<div id="dialog">
     <b style="color:#551F55; position:absolute; left:125px;">Fill Info</b>
      <br><br><form method="post" id="signup" name="signup" onsubmit="return checkForm()" action="uploadfile.php" >
      <pre ><b style="color:#F6F6F6;">
  FileName:   <input type="text" id="filename" name="name" style="width:150px; background-color:#B9B9B9; border-radius:5px;" ><br />
  Tag:        <input type="text" id="filetags" name="tags" style="width:150px; background-color:#B9B9B9; border-radius:5px;"  /><br />
  Category:   <select name="category" id="category" style="widows:150px;background-color:#B9B9B9; border-radius:5px; " >
  									   <option value="">Choose category</option>
    								   <option  >Autos &amp; Vehicles</option>
   									   <option  >Comedy</option>
								       <option  >Travel &amp; Events</option>
              </select><br /><br />
  Description:<textarea name="Descrp" id="description" style="width:150px; background-color:#B9B9B9; border-radius:5px;" ></textarea><br /><br /><br />
  </b> <input type="submit" name="submit" value="Upload" style="background-color:#A4A4A4; border-radius:5px; width:85px; float:right;" />
     </pre>
              </form>
     <a href="#" onclick="closePopUp('dialog');"><img src="close.gif" style="position:relative; top:-342px; left:282px;" /></a>
</div>
<br />
<!-- <a href="#" onclick="showPopUp('dialog');">Show</a>   this is to call popup fill form..-->

<?php

/* Step 2. From this folder, I want to create a subfolder called "myfiles".  Also, I want to try and make this folder world-writable (CHMOD 0777). 

if(mkdir($thisdir . $uplo . $pflname , 0777)) 
{ 
   echo "Directory has been created successfully..."; 
} 
else 
{ 
   echo "Failed to create directory..."; 


} */

?> 

</body>
</html>
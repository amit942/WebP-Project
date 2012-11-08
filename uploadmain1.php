<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload2share | free to upload,free to share!!</title>

<link rel="stylesheet" type="text/css" href="mainP.css" />
<link rel="STYLESHEET" type="text/css" href="http://localhost/my%20uploadNshare%20project/popup/popup-contact.css">
<!-- script for making dragable box..-->
<style type="text/css">
#div1 {width:350px;height:70px;padding:10px;border:1px solid #aaaaaa;}
</style>
<script>
function allowDrop(ev)
{
ev.preventDefault();
}

function drag(ev)
{
ev.dataTransfer.setData("Text",ev.target.id);
}

function drop(ev)
{
var data=ev.dataTransfer.getData("Text");
ev.target.appendChild(document.getElementById(data));
ev.preventDefault();
}
</script>

<!--script to clear input text,value blank se aur kuch bhi rakh sakte hai.. -->
<script type="text/javascript"> 
function make_blank()
{
document.search.uplo.value ="";

}
//to change pic..
function changepic()
{
	
	document.writeln("<img src='ad.jpg' width='350px' height='300px' /> ");
}

//to check data in form..
function checkForm()
{
	
    var x=document.forms["signup"]["pswd"].value;
	var y=document.forms["signup"]["pswd2"].value;
	var a=document.getElementsByName('pswd');
	var b=document.getElementsByName('pswd2');
	
	if(x==y)
	{
		return true;
	}
	else 
	{
		alert("fill the form correctly" );
        return false;
	}
	
	
	
}


function popupb()
{
	pop.window("<div id='popUpDiv' ></div>");
	
}

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
		
		
function checksignup(el)
{
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

function blank()
{
document.search1.uplo1.value ="";

}
</script>		
<!--script to print page(apne sa aaeb gelai..tai duware kahai chelyai ki try karait rahaila..) -->
<script type="text/javascript"> 
function print_page()
{
	window.print();                                                       //both same
	print("<img src='ad.jpg' width='350px' height='300px' />  ");	
}
</script>
<script type="text/javascript">
var popup1 = new Popup();
popup1.content = "This is the content of the DIV";
popup1.style = {'border':'3px solid black','backgroundColor':'white'};
</script>

</head>
<div class="topmenu" >
<?php include("header.php"); ?>
</div>

  
<body class="body" onload="javascript:fg_hideform('fg_formContainer','fg_backgroundpopup');">
<!--google custome search..  -->
<div id="googlesearch" style="position:absolute; top:30px; left:250px;" >
<form style="font-size:11px;" method="get" name="search" action="http://www.google.com/search" target="_blank">
    <input  type="text" name="uplo" onclick="make_blank()" style="width:155px;border-radius: 5px;color:#808080;font-style:italic;margin:0;" 
      size="20" value="Search Upload2Share.com" />
<input type="hidden" name="sitesearch" value="upload2share.com" />    
<input type="submit" style="margin:0;" value="Search" title="Search" />
	</form>
    </div>

<div class="uploadbox" >
<form method="post" name="search1" action="search/searchf.php" ><label for="uplo1" ></label>
<input type="text" name="uplo1" class="uploadfile" style="border-radius: 5px;" value="Search Your File Here" onclick="blank()" />
<input type="submit" name="searchf" value="Search" style="border-radius: 5px;" class="submit"  /><br />
</form>

<?php include("uploadf.php"); ?>
<label for="file" ></label>
<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" 
name="discussion" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="62914560"  />
<input type="file" name="file" style="font-family:Arial, Helvetica, sans-serif; background-color:#669999; border-radius: 5px; color:#06C; width:322px; height:25px;" />&nbsp;<input type="submit" name="upload" value="Upload" style="height:27px; border-radius: 5px; width:150px; background-color:#808080;" ><br>

</form>
</div></div>


<div id="cover"></div>
<div id="dialog">
    <b style="color:#551F55;">Sign Up</b>
  <br><br><form method="post" name="signup" onsubmit="return checkForm()" action="signup.php" >
 <pre ><b style="color:#FF7F00;">
  Name:   <input type="text" name="name" style="width:200px; background-color:#CFF;" ><br />
 Email:   <input type="email" name="email" style="width:200px; background-color:#CFF;" /><br />
 Password:<input type="text" name="pswd" style="width:200px; background-color:#CFF;" /><br />
 Confirm: <input type="text" name="pswd2" style="width:200px; background-color:#CFF;" /><br />
 D.O.B:</b>   <input type="date" name="dob" style="width:200px; background-color:#CFF;" /><br />
 <input type="submit" name="submit" value="SignUp" style="background-color:#FFDF55; width:85px; float:right;" />
 </pre>
 </form>
 <a href="#" onclick="closePopUp('dialog');"><img src="close.gif" style="position:relative; top:-318px; left:282px;" /></a>
    </div>

<div class="bottom" style="position:absolute; top:600px;" ><?php include("footer.php"); ?></div>



<?PHP

require_once('/popup/popup-contactform.php');

//2. link to the style file contact.css
?>

<?PHP
//3. php include contactform-code.php at the end of the page

require_once('/popup/contactform-code.php');
?>



<!--
<br><a href="#" onclick="closePopUp('dialog');"><img src="close.gif" style="position:relative; top:-335px; left:282px;" /></a>

<a href="#" onclick="showPopUp('dialog');">Show</a> 
-->

<!--  <img src="cartooncloud.jpg" onmouseover="this.src='idea.jpg';" onmouseout="this.src='cartooncloud.jpg'; " />  -->

<!-- ye changable bar jo ki value change aayega..type.check..
<form oninput="x.value=parseInt(a.value)">
<pre> 0<input type="range" name="a" value="44">100  <output name="x" for="a b"></output></pre>
</form>
-->
<!--  yaha pe draggable things hai....
<div id="div1" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
<br />
<p id="drag1" draggable="true" ondragstart="drag(event)">_<img src="/pic/hand.jpg" width="125px" height="100px" draggable="true"  /></p>


//this is to popup print page online wala..
<img src="submit.jpg" onclick="print_page()" width="55px" height="55px" /><br />

-->



<!--
//this one to print page online wala je edit bhi kair sakai chiayai...
<script>var pfHeaderImgUrl = '';var pfHeaderTagline = '';var pfdisableClickToDel = 0;var pfBtVersion='1';(function(){var js, pf;pf = document.createElement('script');pf.type = 'text/javascript';if('https:' == document.location.protocol){js='https://pf-cdn.printfriendly.com/ssl/main.js'}else{js='http://cdn.printfriendly.com/printfriendly.js'}pf.src=js;document.getElementsByTagName('head')[0].appendChild(pf)})();</script><a href="http://www.printfriendly.com" style="color:#6D9F00;text-decoration:none;" class="printfriendly" onclick="window.print();return false;" title="Printer Friendly and PDF"><img style="border:none;" src="http://cdn.printfriendly.com/button-print-grnw20.png" alt="Print Friendly and PDF"/></a>

-->


</body>
</html>

<style type="text/css">
.body #dialug {
	display:    none;
	width:      100px;
	height:     100px;
	position:   absolute;
	z-index:    100;
	padding:    2px;
	font:       10pt tahoma;
	border:     1px solid gray;
	background-color:#FFA;
	right: 5px;
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
	top: 350px;
}

.body #conver {
	display:        none;
	position:       absolute;
	left:           0px;
	top:            0px;
	filter:         alpha(Opacity = 50);
	opacity:        0.5;
	-moz-opacity:   0.5;
	-khtml-opacity: 0.5;
	background-color: Gray;
}



.topmenu {
	list-style-type: none;
	position: absolute;
	height: 40px;
	width: 280px;
	left: 470px;
}
.topmenu .ulist ul
{
float:left;
width:100%;
padding:0;
margin:0;
list-style-type:none;
}
.topmenu .ulist a
{
float:right;
width:6em;
text-decoration:none;
color:white;
background-color:#AA3F00;
padding:0.2em 0.6em;
border-right:1px solid white;
}
.topmenu .ulist a:hover {background-color:#FF7F00;}
.topmenu .ulist li {display:inline;}
</style>
<style type="text/javascript" >
function showPopUp(el) {
            var cvr = document.getElementById("conver")
            var dlg = document.getElementById(el)
            cvr.style.display = "block"
            dlg.style.display = "block"
            if (document.body.style.overflow = "hidden") {
                cvr.style.width = "1024"
                cvr.style.height = "100%"
            }
        }
        function closePopUp(el) {
            var cvr = document.getElementById("conver")
            var dlg = document.getElementById(el)
            cvr.style.display = "none"
            dlg.style.display = "none"
            document.body.style.overflowY = "scroll"
        }
		
		</style>

<style type="text/css" >
.thumbnail{
	position: relative;
	text-align: center;
	text-decoration: none;
	font-weight: bold;
	color: #D4D4D4;
	font-style: normal;
	display: compact;
	height: 25px;
}

.thumbnail:hover{
	background-color:#C60;
	z-index: 50;
	color: #000;
	height: auto;
}
.thumbnail span{ /*CSS for enlarged image*/
	position: absolute;
	background-color:#D45F00;
	padding: 5px;
	visibility: hidden;
	color: #808080;
	text-decoration: none;
	border-top-width: 2px;
	border-right-width: 2px;
	border-bottom-width: 2px;
	border-left-width: 2px;
	border-top-style: groove;
	border-right-style: inset;
	border-bottom-style: ridge;
	border-left-style: outset;
	border-top-color: #EBEBEB;
	border-right-color: #EBEBEB;
	border-bottom-color: #EBEBEB;
	border-left-color: #EBEBEB;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-weight: normal;
}
.thumbnail span img{ /*CSS for enlarged image*/
	border-width: 0;
	padding: 2px;
	background-color:#FFBF55;
	color: #505050;
}

.thumbnail:hover span{ /*CSS for enlarged image on hover*/
	visibility: visible;
	top: 0; /*position where enlarged image should offset horizontally */
	top: 26px;
	right: 0px;
}

</style>

</head>

<body>
<div class="topmenu" >
<ul class="ulist" >
   <li><div>
			<a class="thumbnail" style="height:20px;border-radius: 5px;" >login<span style="border-radius: 5px;"><form method="post" action="checklogin.php" class="thumbnail" >
			Username:<input type="text" name="uname" style="border-radius: 5px;" /><br />
			Password:<input type="password" name="pswd" style="border-radius: 5px;" /><br />
			<input type="submit" name="submit" value="login" style="background-color:#FF7F00; border-radius: 5px; width:75px; height:25px;" /></form></span></a></div></li>
   <li><a href='javascript:fg_popup_form("fg_formContainer","fg_form_InnerContainer","fg_backgroundpopup");' style="border-radius: 5px;"
>signup</a>
   </li>
</ul>
</div>
<!--
<a href="#" onClick="showPopUp('dialog');" >signup</a>
-->
</body>
</html>
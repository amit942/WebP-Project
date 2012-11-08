<head>
<style type="text/css">
.topmenu {
	list-style-type: none;
	position: relative;
	height: 40px;
	width: auto;
}

.topmenu .ulist ul
{
	float:left;
	width:100%;
	padding:0;
	margin:0;
	list-style-type:none;
	border-radius: 5px;
}
.topmenu .ulist a
{
	float:right;
	width:6em;
	text-decoration:none;
	color:white;
	background-color:#B4B4B4;
	padding:0.2em 0.6em;
	border-right:1px solid white;
	border-radius: 5px;
}
.topmenu .ulist a:hover {
	background-color:#4b545f;
}
.topmenu .ulist li {display:inline;}
</style>
</head>

<body>
<div class="topmenu" >
<ul class="ulist" >
   <li><a href="#" ><?php echo $uname; ?>
</a></li>
   <li><a href="signout.php" >signout</a></li>
</ul>
</div>
</body>
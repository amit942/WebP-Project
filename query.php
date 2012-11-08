<?php

$connect = mysql_connect("localhost","username","password");
if (!$connect)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("signup_data", $connect);

?>
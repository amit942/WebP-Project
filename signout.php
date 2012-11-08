
<?php

//unset($_SESSION['name']);
//unset($_SESSION['username']);
session_start();
    $_SESSION['loggedIn'] = "false";
        unset($_SESSION['loggedIn']);
		unset($_SESSION['username']);
		unset($_SESSION['pwd']);
       
session_destroy();


header("Location: http://localhost/snout.php");
exit;

?>
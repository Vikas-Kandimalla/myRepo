<?php
session_start();
include "dbinfo.php";
global $link;
if ( isset($_SESSION['uid']) && isset($_SESSION['user']) && isset($_SESSION['email']) && !empty($_SESSION['uid']) ) {
	header("Location: http://localhost/p/home.php");
}
if( !isset($_SESSION['user']) || !isset($_SESSION['email']) ) {
header("Location: http://localhost/p/index.php");	
} 
else  {
if ( isset($_POST['pin'])) {
	$user = $_SESSION['user'];
	$user = mysqli_real_escape_string($link,$user);
	$email = $_SESSION['email'];
	$email = mysqli_real_escape_string($link,$email);
	$query = "SELECT * FROM `userlogin` WHERE `username`='$user' AND `email`='$email'";
	
	$retval = mysqli_query($link,$query);
	$row = mysqli_fetch_array($retval,MYSQLI_BOTH);
	$n = mysqli_num_rows($retval);
	if ( $row['activated'] == 0) {
	if ( $n==1) {
		if ( $row['actcode'] == $_POST['pin'] ) {
			$_SESSION['uid'] = $row['loginid'];
			$_SESSION['dbstatus'] = $row['dbstatus'];
			$querya = "UPDATE `userlogin` SET `activated`=1 WHERE `username`='$user' AND `email`='$email'";
			$return = mysqli_query($link,$querya);
			if ( ! $return) {
				echo mysqli_error($link);
				header("Location: http://localhost/p/index.php");
			}
			
			header("Location: http://localhost/p/home.php");
		}
	}
	else if($n > 1 || $n < 0) {
		die("Error : Contact the Website administrator");
	}
	else {
		//echo "BLABLA";
		header("Location: http://localhost/p/");

	}
	}
	}

	
}


?>
<html>
<head><title>Activate</title></head>
<body>
<h3>Enter your pin here :</h3>
<form action="activate.php" method="POST">
<input name="pin" placeholder="pin" type="text">
<input type="submit" value="Login">
</form>
</body>
</html>





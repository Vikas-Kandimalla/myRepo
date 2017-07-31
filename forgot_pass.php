<?php
session_start();
include "dbinfo.php";
global $link;
function sendemail($email,$link,$uid,$name) {
//	$file = fopen("act.txt","a") or die("Cannot open file");
	//$str = $email.'--'.$actcode.'\n';
	$subject = 'Actication Code';
	$message ="Dear $name.Go to http://localhost/p/forgotpass_auth.php?key=$link&uid=$uid";
//	fwrite($file,$str);
	if ( !mail($email,$subject,$message)) {
		return false;
	}
	else {
		return true;
	}
}
 

if ( isset($_SESSION['uid']) && isset($_SESSION['user']) && isset($_SESSION['email']) && isset($_SESSION['dbstatus']) ) {
	echo "Session not set";
	header("Location: http://localhost/p/home.php");
}
if ( isset($_POST['email']) ) {
			$uid = mysqli_real_escape_string($link,$_POST['email']) ;
			//echo $uid;
	$query = "SELECT * FROM `userlogin` WHERE `email`='$uid'";
		$ret = mysqli_query($link,$query);
		$row = mysqli_fetch_array($ret,MYSQLI_BOTH);
		$n = mysqli_num_rows($ret);
		if ( $n == 1){
			$link = sha1($row['password'].$row['seed']);
			if( ! sendemail($uid,$link,$row['loginid'],$row['username'])){
				echo ' error';
			}
			else {
				echo "Email successfully sent";
			}
		}
	
	
}


?>

<html>

<body>

<form action="forgot_pass.php" method="POST">
<input type="email" name="email">
<input type="submit" value="submit">
    </form></body>
</html>

<?php 
session_start();
include "dbinfo.php";
global $link;


if(isset($_GET['key']) && isset($_GET['uid']) ) {
	
	$uid = mysqli_real_escape_string($link,$_GET['uid']) ;
	$query = "SELECT * FROM `userlogin` WHERE `loginid`=$uid";
		$ret = mysqli_query($link,$query);
		$row = mysqli_fetch_array($ret,MYSQLI_BOTH);
		$n = mysqli_num_rows($ret);
		if ( $n == 1) {
			
			$pass = $row['password'];
			$seed = $row['seed'];
			$phrase = sha1($pass.$seed);
			if(($_GET['key'] == $phrase ) ){
				$_SESSION['user'] = $row['username'];
				$_SESSION['uid'] = $row['loginid'];
				$_SESSION['email'] = $row['email'];
				header("Location: http://localhost/p/change_pass.php");
			}
			else {
				header("Location: http://localhost/p/");
			}
		
		}
		else  {
			header("Location: http://localhost/p/");
		}
	
	
	
	
}
else {
	header("Location: http://localhost/p/");
}


?>
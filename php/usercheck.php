<?php
function checkuser( $user , $pass ){
	global $link;
	
	$user = mysqli_real_escape_string($link,$user);
	$pass = mysqli_real_escape_string($link,$pass);
	
	$query = "SELECT * FROM `userlogin` WHERE `username`='$user'";
		
	$retval = mysqli_query($link,$query);
	
	
	if ( ! $retval) {
	
		return false;
	}
	$row = mysqli_fetch_array($retval,MYSQLI_BOTH);
	$numofrows = mysqli_num_rows($retval);
if ( $numofrows != 1) {
	return false;
}
else {
	
	$seed = $row['seed'];
	 $actualpass =  $row['password'];
	$checkpass = sha1($pass.$seed);
	
	if ( $checkpass == $actualpass ) {
		
		
		if ( $row['activated'] == 0) {
			$_SESSION['email'] = $row['email'];
			$_SESSION['user'] = $row['username'];
		header("Location: http://localhost/p/activate.php");
	}
		else if ( $row['disabled'] == 1) {
			header("Location: http://localhost/p/disabled.php");
		}
		else {
			$_SESSION['uid'] = $row['loginid'];
		$_SESSION['user'] = $row['username'];
		$_SESSION['email'] = $row['email'];
		$_SESSION['dbstatus'] = $row['dbstatus'];
		$_SESSION['ouid'] = null;
		return true;
		}
		}
	else {
		return false;
	}
	
}

}
?>
<?php
session_start();
if ( !isset($_SESSION['uid']) || !isset($_SESSION['user']) || !isset($_SESSION['email'])) {
	echo "Session not set";
	header("Location: http://localhost/p/index.php");
}
include "dbinfo.php";
global $link;

if ( isset($_POST['oldpass']) && isset($_POST['newpass']) ) {
	$user = $_SESSION['user'];
	$user = mysqli_real_escape_string($link,$user);
	$email = $_SESSION['email'];
	$email = mysqli_real_escape_string($link,$email);
	$query = "SELECT * FROM `userlogin` WHERE `username`='$user' AND `email`='$email'";
	$retval = mysqli_query($link,$query);
	
	$row = mysqli_fetch_array($retval,MYSQLI_BOTH);
	$n = mysqli_num_rows($retval);
	if ( $n == 1) {
		$pass = mysqli_real_escape_string($link,$_POST['oldpass']);
		if ( $row['password'] == sha1($pass.$row['seed']) ) {
			$newpass = sha1($_POST['newpass'].$row['seed']);
			$querya = "UPDATE `userlogin` SET `password`='$newpass' WHERE `username`='$user' AND `email`='$email'";
			$return = mysqli_query($link,$querya);
			echo mysqli_error($link);
			if ( ! $return ) {
				echo "Error : password has not been reset due to some problem.<br>Please Try again.";
			}
			else {
				echo "Password has been reset successfully";
				header("Location: http://localhost/p/home.php");
			}
		}
		else {
		echo "Please Check you password";
		}
	}
	else if ($n < 0) {
		echo "No such username exists";
	}
	else {
		die("Contact web Admin");
	}
	
	
}




?>
<html>
<title>Reset Password</title>
<body>
<form action="change_pass.php" method="POST">
<table>
<tr>
<td>Password </td>
<td><input name="oldpass" type="password" placeholder="Password"></td>
</tr>
<tr>
<td>New Password </td>
<td><input name="newpass" type="password" placeholder="Password"></td>
</tr>
<tr>
<td>Re-enter New Password </td>
<td><input name="reenterpass" type="password" placeholder="Re-enter password"></td>
</tr>
<tr>
<td>
<input type="submit" value="Save changes">
<td>
</tr>


</table>

</form>
<a href="home.php"><button>Home</button></a>


 </body>
</html>



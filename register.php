<?php 
session_start();
include "dbinfo.php";
function sendemail($email,$actcode,$name) {
	$file = fopen("act.txt","a") or die("Cannot open file");
	$str = $email.'--'.$actcode.'\n';
	$subject = 'Actication Code';
	$message ="Dear $name,<br>You have registered For Mywebsite.<br>Use this Activation password : '$actcode' to complete the registration process";
	fwrite($file,$str);
	if ( !mail($email,$subject,$message)) {
		return false;
	}
	else {
		return true;
	}
}
function seedgenerator($n) {
	$alphanum = "01qwertyuiop2asdf4ghhjkl56zxcvbnm789QWERTYUIOPASDFGHJKLZXCVBNM";
		$seed = '';
	for ( $i = 0 ; $i < $n;$i++ ) {
		$k = rand(0,61);
		$seed .= $alphanum[$k];
	}
	return $seed;
	}
global $link;
if ( isset($_SESSION['uid']) && isset($_SESSION['user']) && isset($_SESSION['email'])) {
	header("Location: http://localhost/p/home.php");
}
if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']) ){
	$username = $_POST['username'];
	$pass = $_POST['password'];
	$email = $_POST['email'];
	$username = mysqli_real_escape_string($link,$username);
	$pass = mysqli_real_escape_string($link,$pass);
	$email = mysqli_real_escape_string($link,$email);
	$seed = mysqli_real_escape_string($link,seedgenerator(12));
	$pass .= $seed;
	$actcode = seedgenerator(8);
	$pass = sha1($pass);
	$query = "INSERT INTO `userlogin`(`username`,`password`,`email`,`seed`,`actcode`) VALUES('$username','$pass','$email','$seed','$actcode')";
	
	$retval = mysqli_query($link,$query);
	if ( ! $retval ) {
		$error = mysqli_error($link);
		echo "$error :  Username or emailid already exists";
	}
	else {
		if(sendemail($email,$actcode,$_POST['username'])){
		$_SESSION['user'] = $_POST['username'];
		$_SESSION['email'] = $_POST['email'];
		header('Location: http://localhost/p/activate.php');
		}
		else {
			echo "Error : sending mail";
		}
	}
}
else {
	//header('Location: http://localhost/p/register.php');
}




?>

<html>
<title>Register </title>
<body>
<form action="register.php" method="POST">
<table>
<tr>
<td>Username </td>
<td><input name="username" type="text" placeholder="Username"></td>
</tr>
<tr>
<td>Password </td>
<td><input name="password" type="password" placeholder="Password"></td>
</tr>
<tr>
<td>Email </td>
<td><input name="email" type="email" placeholder="email"></td>
</tr>
<tr>
<td>
<input type="submit" value="register">
<td>
</tr>

</table>
</form>
</body>
</html>



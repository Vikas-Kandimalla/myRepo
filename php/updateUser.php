<?php 
session_start();
$pwd = 'http://'.$_SERVER['SERVER_NAME'].'/p';
if ( !isset($_SESSION['uid']) || !isset($_SESSION['user']) || !isset($_SESSION['email']) || !isset($_SESSION['dbstatus'])) {
	echo "Session not set";
	header("Location: ".$pwd."index.php");
}

if(isset($_GET['username']) && !empty($_GET['username']) && isset($_GET['email']) && !empty($_GET['email']) ){
	$con = mysqli_connect("127.0.0.1","root","","project") or die("710 Error couldn't connect to database");
	$database = $_SESSION['uid'];
	$newName = mysqli_real_escape_string($con,$_GET['username']);
	$newEmail = mysqli_real_escape_string($con,$_GET['email']);
	$id = $_SESSION['uid'];
	
	
	$query = "UPDATE `userlogin` SET `username` = '$newName' , `email` = '$newEmail' WHERE `loginid` = '$id';";
	$retval = mysqli_query($con,$query);
	if ( ! $retval){
		echo '773 Error  : cannot Update database';
		die();
	}
	else {
		echo '800 Success : Database Updated successfully';
		$_SESSION['user'] = $newName;
		$_SESSION['email'] = $newEmail;
	}
	


	
	}
	else {
		echo 'Error  :No input data';
		header("Location: http://localhost/p/index.php");
	}
	

?>
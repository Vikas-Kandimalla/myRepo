<?php 

include "dbinfo.php";
include "php/usercheck.php";
session_start();
$msg = '';
$pwd = 'http://'.$_SERVER['SERVER_NAME'].'/p';
//echo $pwd;
if ( isset($_SESSION['uid']) && isset($_SESSION['user']) && isset($_SESSION['email']) &&isset($_SESSION['dbstatus'])) {
	header("Location: ".$pwd."/home.php");
}
if ( isset($_POST['userid']) && isset($_POST['pass'])) {
	
	$user = $_POST['userid'];
	$pass = $_POST['pass'];
	
if ( checkuser($user,$pass) ){
	header("Location: ".$pwd."/home.php");
	}
else{
	$msg =  "! Invalid Username or password";
	}
}



?>
<!DOCTYPE html>
<html>

<head>
<title>Login
</title>
<script>
var obj = document.getElementsByClassName("username");
window.onload = function() {
obj[0].focus();


}
</script>
<style>


   
    div#login_panel {
        position : relative;
        top : 40%;
        left : 40%;
    width : 350px;
	border : 1px solid black;
	box-shadow: 0px 0px 20px green;
	border-radius : 5px;
}
div#login_panel > form {
	font-family : Tahoma, Geneva, sans-serif;
}
div#login_panel > div {
	margin-top : 40px;
	
	
}
span.login {
	padding-top : 60px;
	font-size : 35px;
	padding : 15px;
}
span.up {
	
	font-size : 25px;
	margin : 10px;
	padding : 10px;
}
input.username {
	margin : 10px;
    margin-left : 25px; 
	padding-left : 15px;
	height : 45px;
	font-size : 120%;
	width : 275px;
	border-radius : 3px;
	border : 1px solid grey;
}
input.password {
	font-size : 120%;	
	margin : 10px;
    margin-left : 25px;
	padding-left : 15px;
	height : 45px;
	width : 275px;
	border-radius : 3px;
	border : 1px solid grey;
}
input.up:focus {
	box-shadow : 0px 0px 5px blue;
	border : 1px solid blue;
	height : 45px;
    
	border-radius : 3px;
}

span.invalid {
	color : red;
	font-size : 80%;
}
input.login{
	background-color : blue;
	width : 275px;
	height : 45px;
	margin-top : 5px;
	margin-bottom : -8px;
    margin-left : 30px;
	border : 1px solid blue;
	font-size : 150%;
	border-radius : 5px;
	color : white;
	background: repeating-linear-gradient(#8080ff, #b3b3ff,#6666ff);
    
}
    input.login:focus ,input.login:hover {
    
    background: repeating-linear-gradient(#b3b3ff, #8080ff,#b3b3ff);
    transition : all 1;
    }

a.fp{
	text-decoration : none;
	font-family: sans-serif;
	color : black;
    width : 75px;
    padding : 10px 20px 10px 20px;
	background-color : #bfbfbf;
	border-radius : 3px;
	background: repeating-linear-gradient(#e6e6e6 , #999999, #e6e6e6);
	
}
div#register {
	background-color : white;
	height : 100px;
	
    //background: linear-gradient(#bfbfbf, white); /* Standard syntax */
}

a.register{
	text-decoration : none;
	padding : 10px 28px 10px 28px;
	font-family: sans-serif;
	color : black;
	border-radius : 3px;
	background: repeating-linear-gradient(#e6e6e6 , #999999, #e6e6e6);
	
}
    
    a:hover {
        text-decoration : none;
	color : white;
	border-radius : 3px;
	background: repeating-linear-gradient(#999999 , #e6e6e6, #999999);
    }
span.fpspan {
	font-size : 130%;
	padding : 2px 3px 2px 3px;
	color : grey;
	
}

	
</style>

</head>
<body>
   
<div id="login_panel">

<div>
<form action="index.php" method="POST">

<span class="login">Login</span><br>
<span class="invalid"><?php echo $msg;?></span><br>
<input name="userid" type="text" placeholder="Enter your username" class="username up" 	id="focus"><br>
<input name="pass" type="password" placeholder="Enter password" class="password up"><br>
<input type="submit" value="login" class="login"><br>
</form>
</div>

<div id="register">
<hr>
<div id="fp">
<span class="fpspan" align="left">Forgot your password? | </span><a class="fp" href="http://localhost/p/forgot_pass.php">Need help</a>
</div>
<br>
<div id="reg">
<span class="fpspan">Don't have an account? | </span>
<a class="register" href="register.php">Register</a><br>
</div>
</div>
</div>
    
</body>


</html>
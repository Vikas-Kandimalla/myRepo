<?php
session_start();
$link = mysqli_connect("127.0.0.1","root","","project");
if (! $link) {
    die('Error : Could not connect database.');
}


if(isset($_POST['ouid']) && !empty($_POST['ouid']) ) {
if ( $_POST['ouid'] != $_SESSION['ouid'])
$_SESSION['ouid'] = $_POST['ouid'];
}


$database=$_SESSION['ouid'];

$database = mysqli_real_escape_string($link,$database);
$queryd = "SELECT `loginid` FROM `userlogin` WHERE `username`='$database'";
if (! $ret = mysqli_query($link,$queryd)) {
	echo "Error cannot access database";
	die();
}
else {
	$rowa = mysqli_fetch_array($ret,MYSQLI_BOTH);
$numofrowsa = mysqli_num_rows($ret);
if ( $numofrowsa != 1){
	die("Error user not found");
}
else {
	$q = "use `$rowa[0]`";
//	echo $rowa[0];
	$r = mysqli_query($link,$q);
//	echo "<br>Error : ".mysqli_error($link);
	//	mysql_select_db($rowa);
}
	
}




    
    


    
    $sql = "SELECT * FROM `events` ORDER BY eventdate ASC,eventtime ASC;";

if (! $retval = mysqli_query($link,$sql) ) {

echo'could not select database.<br>';
die('Debug Error : '.mysqli_error($link) );
}
$content = '{"events":[' ;
$row = mysqli_fetch_array($retval,MYSQLI_BOTH);
$numofrows = mysqli_num_rows($retval);
$content .= "{\"numofevents\":\"$numofrows\"},";
do {
if ( !$row ) {
}
else  {
$content .= "{\"ID\":\"$row[0]\",\"name\":\"\",\"eventdate\":\"$row[2]\",\"eventtime\":\"$row[3]\",\"recordtime\":\"$row[4]\",\"eventduration\":\"$row[5]\" },";
    }
}while ( $row = mysqli_fetch_array($retval,MYSQLI_BOTH) );

$content = substr($content,0,-1);
$content .= ']}';
echo $content;





?>

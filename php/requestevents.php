<?php 
session_start();
include_once "scheduling.php";
$link = mysqli_connect("127.0.0.1","root","","project");
if (! $link) {
    die ('Error : Could not connect database.');
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
	$database = $rowa[0];
	//echo $rowa[0];
	$r = mysqli_query($link,$q);
//	echo "<br>Error : ".mysqli_error($link);
	//	mysql_select_db($rowa);
}
	
}


if ( isset($_POST['name']) && isset($_POST['date']) && isset($_POST['time']) && isset($_POST['recordtime']) && isset($_POST['eventduration']) && isset($_POST['eventstatus']) ) {
 $name = $_POST['name'];
 $date = $_POST['date'];
 $time = $_POST['time'];
 $recordtime    = $_POST['recordtime'];
    $eventduration  = $_POST['eventduration'];
    $status = $_POST['eventstatus'];
    $name = mysqli_real_escape_string($link,$name);

   $name = addslashes($name);
     
   $time = mysqli_real_escape_string($link,$time);
   $date = mysqli_real_escape_string($link,$date);
    $recordtime = mysqli_real_escape_string($link,$recordtime);
    $eventduration = mysqli_real_escape_string($link,$eventduration);
    $status = mysqli_real_escape_string($link,$status);
	
	if(!sched($link,$database,$date,$time,$eventduration)){
		echo "700 Error scheduling conflict";
		die();
		
	
	}
    
$sql = "INSERT INTO `events`(`name`,`eventdate`,`eventtime`,`recordtime`,`eventduration`,`eventstatus`) VALUES('$name','$date','$time','$recordtime','$eventduration','$status')";
    
    $retval = mysqli_query($link,$sql);
    
    if ( ! $retval) {
        die("Error : ".mysqli_error($link));
    }
    else {
        $sqlb = "SELECT ID FROM `events` WHERE recordtime=$recordtime";
        $return = mysqli_query($link,$sqlb);
        $ID = mysqli_fetch_array($return,MYSQLI_BOTH);
        
        

    }
    

}
else {
    echo "data not inserted";
}
?>

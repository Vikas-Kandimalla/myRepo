<?php
session_start();
include_once "scheduling.php";
	$database = $_SESSION['uid'];
$link = mysqli_connect("127.0.0.1","root","","$database");
if (! $link) {
    die ('dsadsadError : Could not connect database.'.mysqli_error($link));
}


if ( isset($_POST['eventid']) && !empty($_POST['eventid']) && isset($_POST['eventdate']) && isset($_POST['eventtime']) && isset($_POST['eventduration']) && isset($_POST['eventstatus']) && isset($_POST['schedskip']) ) {
    
    $eventid = $_POST['eventid'];
	 $eventid= mysqli_real_escape_string($link,$eventid);
    $name = $_POST['name'];
    $name = mysqli_real_escape_string($link,$name);
	$date = $_POST['eventdate'];
         $date = mysqli_real_escape_string($link,$date);
	$time = $_POST['eventtime'];
         $time = mysqli_real_escape_string($link,$time);
		 $duration = $_POST['eventduration'];
         $duration = mysqli_real_escape_string($link,$duration);
		 $status = $_POST['eventstatus'];
		 $status = mysqli_real_escape_string($link,$status);
		
		$skip = mysqli_real_escape_string($link,$_POST['schedskip']);
	if($skip == 0){
	if(!sched($link,$database,$date,$time,$duration,$eventid)){
		echo "700 Error scheduling conflict";
		die();
		
	}
	}
	$sql = "UPDATE `events` SET `name` = '$name',`eventdate` = '$date',`eventtime` = '$time' , `eventduration` = '$duration' , `eventstatus` = '$status' WHERE `ID` = $eventid";
        
        $retval = mysqli_query($link,$sql);
        if ( ! $retval) {
             die("Error : ".mysqli_error($link));
        }
		
	}
	
	
	?>
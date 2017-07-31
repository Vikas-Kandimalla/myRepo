<?php 
include_once "scheduling.php";
session_start();
$database = $_SESSION['uid'];
$link = mysqli_connect("127.0.0.1","root","","$database");
if (! $link) {
    die ('Error : Could not connect database.');
}

echo $_POST['recurtype'].'-------'.$_POST['recurlength'].'--------'.$_POST['recurdata'];
if ( isset($_POST['name']) && isset($_POST['date']) && isset($_POST['time']) && isset($_POST['recordtime']) && isset($_POST['eventduration']) && isset($_POST['eventstatus']) && isset($_POST['schedskip']) && isset($_POST['recurtype']) && isset($_POST['recurlength']) ) {
    
    
    $recurtype = mysqli_real_escape_string($link,$_POST['recurtype']);
    $recurlength = mysqli_real_escape_string($link,$_POST['recurlength']);
    $recurdata = mysqli_real_escape_string($link,$_POST['recurdata']);
    
    
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
	$skip = mysqli_real_escape_string($link,$_POST['schedskip']);
	if($skip == 0){
	if(!sched($link,$database,$date,$time,$eventduration)){
		echo "700 Error scheduling conflict";
		die();
		
	}
	}
    
    
    if ( $recurtype == -1 && $recurlength == -1) {
$sql = "INSERT INTO `events`(`name`,`eventdate`,`eventtime`,`recordtime`,`eventduration`,`eventstatus`) VALUES('$name','$date','$time','$recordtime','$eventduration','$status')";
    }
    else if ( $recurtype > 0 && $recurtype <= 4 && $recurlength > 0 ) {
        $sql = "INSERT INTO `recur_events`(`name`,`startdate`,`starttime`,`duration`,`event_status`,`enddate`,`recur_type`,`recur_length`,`recur_data`) VALUES('$name','$date','$time','$eventduration','$status','9999-12-31','$recurtype','$recurlength','$recurdata')";
    }
    $retval = mysqli_query($link,$sql);
    
    if ( ! $retval) {
        die("Error : ".mysqli_error($link));
    }
    else {
        echo "800 success : event succesfully added";
    }
    


}
else {
    echo "data not inserted";
}
    
?>

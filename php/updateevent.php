<?php 
session_start();
$database = $_SESSION['uid'];
$link = mysqli_connect("127.0.0.1","root","","$database");
if (! $link) {
    die ('Error : Could not connect database.');
}


if ( isset($_POST['eventid']) && !empty($_POST['eventid'])) {
    
    $eventid = $_POST['eventid'];
    
    if(isset($_POST['name']) && !empty($_POST['name'])) {
        
  
        $name = $_POST['name'];
          $name = mysqli_real_escape_string($link,$name);
    $name = addslashes($name);
        $sql = "UPDATE `events` SET `name` = '$name' WHERE `ID` = $eventid;";
        
        $retval = mysqli_query($link,$sql);
        if ( ! $retval) {
             die("Error in name : ".mysqli_error($link));
        }
    }
    if(isset($_POST['eventdate']) && !empty($_POST['eventdate'])) {
         
    
  
        $date = $_POST['eventdate'];
         $date = mysqli_real_escape_string($link,$date);
        $sql = "UPDATE `events` SET `eventdate`='$date' WHERE `ID`=$eventid";
        $retval = mysqli_query($link,$sql);
        if ( ! $retval) {
             die("Error in date");
        }
    }
    if(isset($_POST['eventtime']) && !empty($_POST['eventtime'])) {
         
    
  
        $time = $_POST['eventtime'];
         $time = mysqli_real_escape_string($link,$time);
        $sql = "UPDATE `events` SET `eventtime`='$time' WHERE `ID`=$eventid";
        $retval = mysqli_query($link,$sql);
        if ( ! $retval) {
             die("Error in time");
        }
    }
    if(isset($_POST['eventduration']) && !empty($_POST['eventduration'])) {
         
    
  
        $duration = $_POST['eventduration'];
         $duration = mysqli_real_escape_string($link,$duration);
        $sql = "UPDATE `events` SET `eventduration`='$duration' WHERE `ID`=$eventid";
        $retval = mysqli_query($link,$sql);
        if ( ! $retval) {
             die("Error in duration");
        }
    }
	if ( isset($_POST['eventstatus']) ) {
		$status = $_POST['eventstatus'];
		$status = mysqli_real_escape_string($link,$status);
		 $sql = "UPDATE `events` SET `eventstatus`='$status' WHERE `ID`=$eventid";
        $retval = mysqli_query($link,$sql);
        if ( ! $retval) {
             die("Error in status");
        }
	}
    
   
}

?>

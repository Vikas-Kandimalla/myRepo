<?php 
session_start();
$database = $_SESSION['uid'];
$link = mysqli_connect("127.0.0.1","root","","$database");
if (! $link) {
    die ('Error : Could not connect database.');
}

if ( isset($_POST['eventid']) ) {
    
    $eventid = $_POST['eventid'];
    
    $query = "DELETE FROM `events` WHERE `ID`=$eventid";
    $retval = mysqli_query($link,$query);
    
    if (! $retval ) {
        
        echo "Cannot delete the event";
        die("<br>try again");
    }
    else {
        
        echo "success";
    }
    
}
else {
    
    echo "data not inserted.";
}



?>

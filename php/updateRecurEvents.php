<?php

session_start();
$database = $_SESSION['uid'];
$link = mysqli_connect("127.0.0.1","root","","$database");
if (! $link) {
    die ('Error : Could not connect database.');
}


if ( isset($_POST['eventid']) && isset($_POST['name']) && isset($_POST['modifieddate'])  && isset($_POST['newdate']) && isset($_POST['newstarttime']) && isset($_POST['newduration']) && isset($_POST['newstatus']) && isset($_POST['deleteevent']) ) {
    
    
$id = mysqli_real_escape_string($link,$_POST['eventid']);
$name = mysqli_real_escape_string($link,$_POST['name']);
$modifieddate = mysqli_real_escape_string($link,$_POST['modifieddate']);
$newdate = mysqli_real_escape_string($link,$_POST['newdate']);
$newstarttime = mysqli_real_escape_string($link,$_POST['newstarttime']);
$newduration = mysqli_real_escape_string($link,$_POST['newduration']);
$newstatus = mysqli_real_escape_string($link,$_POST['newstatus']);
    
   
    
    $sql = 'SELECT * FROM `exp_recur_events` WHERE `ID` = "'.$id.'" AND `modifieddate` = "'.$modifieddate.'" ';
    $return = mysqli_query($link,$sql);
    $row = mysqli_num_rows($return);
    if ( $row == 0 ) {
   
        $sql = 'INSERT INTO `exp_recur_events`(`ID`,`name`,`modifieddate`,`newdate`,`newstarttime`,`newduration`,`newstatus`,`deleteevent`) VALUES ("'.$id.'","'.$name.'","'.$modifieddate.'","'.$newdate.'","'.$newstarttime.'","'.$newduration.'","'.$newstatus.'","0")';
        $return = mysqli_query($link,$sql);
        if ( !$return){
            echo mysqli_error($link);
        }
        else {
            echo '800 Success : Event updated successfully';
        }
    }
    else {
        
        
        $sql = 'UPDATE `exp_recur_events` SET `newdate` = "'.$newdate.'" ,  `newstarttime` = "'.$newstarttime.'" , `newduration` = "'.$newduration.'" , `newstatus` = "'.$newstatus.'"  WHERE `ID` = "'.$id.'" AND `modifieddate` = "'.$modifieddate.'" ';
         $retval = mysqli_query($link,$sql);
            if ( ! $retval) {
            echo mysqli_error($link);
            }
    
        else {
            echo '800 Success : Event updated successfully';
        }
        
    }
    
    
    
}

?>
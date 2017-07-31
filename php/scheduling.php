<?php 
function sched($con,$db,$date,$time,$duration,$eventid=null){
		
	$starttime = intval($time[0].$time[1])*60;
	
	$starttime += intval($time[3].$time[4]);

	$endtime = $starttime + $duration;
	if ( $eventid == null)
		$sql = "SELECT * FROM `events` WHERE `eventdate`='$date' ORDER BY eventdate ASC,eventtime ASC;";
	else
	$sql = "SELECT * FROM `events` WHERE `eventdate`='$date' AND `ID` != '$eventid' ORDER BY eventdate ASC,eventtime ASC;";
	$retval = mysqli_query($con,$sql);
	if (! $retval = mysqli_query($con,$sql) ) {
			echo'could not select database.<br>';
			die('Debug Error : '.mysqli_error($con) );
	}
	
	/*
	change the time into heights
	1min = 1;
	1hour = 60;
	
	*/
			
$row = mysqli_fetch_array($retval,MYSQLI_BOTH);
$n = mysqli_num_rows($retval);
$flag = 1;
while($row){
	$stime = intval($row['eventtime'][0].$row['eventtime'][1])*60;
	$stime += intval($row['eventtime'][3].$row['eventtime'][4]);
	$etime = $stime + intval($row['eventduration']);
	
	if ($stime <= $starttime){
		
		if( $etime  > $starttime){
			$flag = 0;
			break;
		}
	}
	else{
		
		if($endtime > $stime){
			
			$flag = 0;
			break;
		}
	}
	
	$row = mysqli_fetch_array($retval,MYSQLI_BOTH);
}
return $flag;
}


?>
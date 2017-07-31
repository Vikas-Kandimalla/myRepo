<?php

$dbLink = mysqli_connect("127.0.0.1","root","","ECE_SEM_5");
if( !$dbLink) {
	echo "Couldn't connect to database".mysqli_error($dbLink);
		die();
}
 
 /*
 `eventVenue` varchar(10),
  `prof` varchar(30),
  `credits` varchar(10),
  `courseName` varchar(10)
 
 INSERT INTO `recur_events` (`ID`, `name`, `starttime`, `duration`, `startdate`, `enddate`, `recur_type`, `recur_length`, `recur_data`, `event_status`, `eventVenue`, `prof`, `credits`, `courseName`) VALUES ('', 'EE 201', '09:00:00', '60', '2017-07-01', '2017-12-10', '1', '1', '0100000', '', '2001', 'SRA', '3-0-0-6', 'digital');
 
 
 */
 $sqlQuery = "SELECT * FROM `events`";
 $queryResult = mysqli_query($dbLink, $sqlQuery);

 $eventData = null;
 $eventIdData = 0;
 if ( !$queryResult) {
	 echo "Error: Cannot select Database".mysqli_error($dbLink);
	 die();
 }
 $row = mysqli_fetch_array($queryResult,MYSQLI_BOTH);
 $numOfRows = mysqli_num_rows($queryResult);
 

 while($row){
	 
$eventData[$eventIdData]['ID'] = $row['ID'];
$eventData[$eventIdData]['name'] = $row['name'];
$eventData[$eventIdData]['eventDate'] = $row['eventdate'];
$eventData[$eventIdData]['eventTime'] = $row['eventtime'];
$eventData[$eventIdData]['eventDuration'] = $row['eventduration'];
$eventData[$eventIdData]['eventVenue'] = $row['eventVenue'];
$eventData[$eventIdData]['courseName'] = $row['courseName'];
$eventData[$eventIdData]['prof'] = $row['prof'];
$eventData[$eventIdData]['credits'] = $row['credits'];
$eventIdData++;


	 $row = mysqli_fetch_array($queryResult,MYSQLI_BOTH);
 }
 
 
 $sqlQuery = "SELECT * FROM `recur_events`";
 $queryResult = mysqli_query($dbLink, $sqlQuery);

 $recurData = null;
 $recurIdData = 0;
 if ( !$queryResult) {
	 echo "Error: Cannot select Database".mysqli_error($dbLink);
	 die();
 }
 $row = mysqli_fetch_array($queryResult,MYSQLI_BOTH);
 $numOfRows = mysqli_num_rows($queryResult);
 
 while($row){
	 
$recurData[$recurIdData]['ID'] = $row['ID'];
$recurData[$recurIdData]['name'] = $row['name'];
$recurData[$recurIdData]['starttime'] = $row['starttime'];
$recurData[$recurIdData]['duration'] = $row['duration'];
$recurData[$recurIdData]['startdate'] = $row['startdate'];
$recurData[$recurIdData]['enddate'] = $row['enddate'];
$recurData[$recurIdData]['recur_type'] = $row['recur_type'];
$recurData[$recurIdData]['recur_length'] = $row['recur_length'];
$recurData[$recurIdData]['recur_data'] = $row['recur_data'];
$recurData[$recurIdData]['eventVenue'] = $row['eventVenue'];
$recurData[$recurIdData]['courseName'] = $row['courseName'];
$recurData[$recurIdData]['prof'] = $row['prof'];
$recurData[$recurIdData]['credits'] = $row['credits'];
$recurIdData++;


	 $row = mysqli_fetch_array($queryResult,MYSQLI_BOTH);
 }
 
 
 
 $sqlQuery = "SELECT * FROM `exp_recur_events`";
 $queryResult = mysqli_query($dbLink, $sqlQuery);

 $expRecurData = null;
 $expRecurIdData = 0;
 if ( !$queryResult) {
	 echo "Error: Cannot select Database".mysqli_error($dbLink);
	 die();
 }
 $row = mysqli_fetch_array($queryResult,MYSQLI_BOTH);
 $numOfRows = mysqli_num_rows($queryResult);
 
 while($row){
	 
$expRecurData[$expRecurIdData]['ID'] = $row['ID'];
$expRecurData[$expRecurIdData]['name'] = $row['name'];
$expRecurData[$expRecurIdData]['modifieddate'] = $row['modifieddate'];
$expRecurData[$expRecurIdData]['newduration'] = $row['newduration'];
$expRecurData[$expRecurIdData]['newstarttime'] = $row['newstarttime'];
$expRecurData[$expRecurIdData]['newdate'] = $row['newdate'];
$expRecurData[$expRecurIdData]['deleteevent'] = $row['deleteevent'];
$expRecurData[$expRecurIdData]['eventVenue'] = $row['eventVenue'];
$expRecurData[$expRecurIdData]['courseName'] = $row['courseName'];
$expRecurData[$expRecurIdData]['prof'] = $row['prof'];
$expRecurData[$expRecurIdData]['credits'] = $row['credits'];
$expRecurIdData++;


	 $row = mysqli_fetch_array($queryResult,MYSQLI_BOTH);
 }
 
 
 
	 
	 
	 if ($eventData != null)
		 $eventData = json_encode($eventData);
	 
	 if ($recurData != null)
		$recurData = json_encode($recurData);
	
	 if ($recurData != null)
		$expRecurData = json_encode($expRecurData);
	 
	 echo $eventData;
	
		echo "<br>";
	echo $recurData;
	
	echo "<br>";
	echo $expRecurData;
	



?>
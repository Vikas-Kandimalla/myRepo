<?php
session_start();
$pwd = 'http://'.$_SERVER['SERVER_NAME'].'/p';
if ( !isset($_SESSION['uid']) || !isset($_SESSION['user']) || !isset($_SESSION['email']) || !isset($_SESSION['dbstatus'])) {
	echo "Session not set";
	header("Location: ".$pwd."index.php");
}

class workinghours {
		 public $day;
		 public $starttime;
		 public $endtime;
		 public $offday;
		 private function timetolen($time){
			 return ($time[0].$time[1])*60 + ($time[3].$time[4]);
		 }
		 public function __construct($day,$stime,$etime,$offday,$btime=null){
			 $this->day = $day;
			 $this->starttime =($stime);
			
			
			
			 $this->endtime = ($etime);
			 $this->offday = $offday;
			 $this->breakhours = $btime;
			 
		 }
	 }

if (isset($_POST['starttime']) && isset($_POST['endtime']) && isset($_POST['offday'])  && !empty($_POST['starttime']) && !empty($_POST['endtime']) && !empty($_POST['offday']) ){
	//echo $_POST['starttime'];
	for ( $i =0 ;$i < 7; $i++){
		
		$workhours[$i] = new workinghours($i,substr($_POST['starttime'],1+6*$i,5).':00',substr($_POST['endtime'],1+6*$i,5).':00',substr($_POST['offday'],1+2*$i,1));
	//	echo $workhours[$i]->starttime;
	}
	// connection to database
	$database = $_SESSION['uid'];
	$link = mysqli_connect("127.0.0.1","root","",$database) or die("710 Error couldn't connect to database");
	$s = 1;
	for ( $i =0 ;$i < 7; $i++){
		$query = 'UPDATE `workinghours` SET `starttime`=\''.$workhours[$i]->starttime.'\' , `endtime`=\''.$workhours[$i]->endtime.'\' , `offday`='.$workhours[$i]->offday.' WHERE `day`='.$workhours[$i]->day.';';
		
		$retval = mysqli_query($link,$query);
		$s *= !(!$retval);
		
	}
	if ( !$s ){
		die('715 Error : Cannot update table.');
	}
	else {
		echo "800 Success : Table updated";
	}
	
	
}


 ?>
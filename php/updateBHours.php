<?php 
session_start();
$pwd = 'http://'.$_SERVER['SERVER_NAME'].'/p';
if ( !isset($_SESSION['uid']) || !isset($_SESSION['user']) || !isset($_SESSION['email']) || !isset($_SESSION['dbstatus'])) {
	echo "Session not set";
	header("Location: ".$pwd."index.php");
}
$database = $_SESSION['uid'];
$con = mysqli_connect("127.0.0.1","root","",$database) or die("710 Error : Cannot Connect to database");


class break_hours {
		public $day;
		public $starttime;
		public $endtime;
		public $name;
		public $id;
		private function timetolen($time){
			 return ($time[0].$time[1])*60 + ($time[3].$time[4]);
		 }
		 public function __construct($id,$day,$stime,$etime,$name=null){
			 $this->day = $day;
			 $this->starttime = $stime;
			 $this->endtime = $etime;
			 $this->name = $name;
			 $this->id = $id;
			  }
		}

if ( ($_SERVER['REQUEST_METHOD'] == 'POST')){
	if (  isset($_POST['bdata']) && isset($_POST['numofentries'])  ){
	
				$num = $_POST['numofentries'];
				$data = json_decode($_POST['bdata']);
			//	var_dump($data);
			//	echo $data->breakhours[$i]->name;
	
	
					$flag =1;	
				for($i = 0;$i < $num;$i++){
						$query = "UPDATE `breakhours` SET `name` = '".$data->breakhours[$i]->name."' , `day` = '".$data->breakhours[$i]->day."' , `starttime` = '".$data->breakhours[$i]->starttime."' , `endtime` = '".$data->breakhours[$i]->endtime."' WHERE `ID` = ".$data->breakhours[$i]->ID;
							if (!($retval = mysqli_query($con,$query))){
										die('715 Error : Cannot UPdate The Table in Line : '.$i);
							}		
						$flag *= !(!($retval));
				}
	
				if ( $flag == 1){
					echo "800 Success : Table Updated.";
				}
				else {
					die ("715 Error : There is some problem storing the new values");
				}
	}

	if(isset($_POST['bhid']) ){
		$id = mysqli_real_escape_string($con,$_POST['bhid']);
		$query = "DELETE FROM `breakhours` where `ID` = $id";
	if 	(!($retval = mysqli_query($con,$query))){
			echo "716 Error : Cannot delete a query";
			die();
	}
	else {
		echo "800 Success : breakhours updated succesfully";
	}
	}
    
    if ( isset($_POST['addbreaks']) && isset($_POST['num'] ) )  {
        $n = mysqli_real_escape_string($con,$_POST['num']);
   
        $data = json_decode($_POST['addbreaks']);
        
        //echo "num = " + $n;
        if ( json_last_error() == 0 ) {
            
            $flag = 1;
        for ( $i = 0;$i < $n ; $i++){
            $query = 'INSERT INTO `breakhours` (`day`,`starttime`,`endtime`,`name`) VALUES('.$data->breakhours[0]->day.',"'.$data->breakhours[0]->starttime.'","'.$data->breakhours[0]->endtime.'","'.$data->breakhours[0]->name.'")';
            
            $retval  = mysqli_query($con,$query);
            $flag *= !(!($retval));
        }
        if ( $flag == 1){
            echo '800 Success : breaks succesfully added';
        }
        else {
            echo "714 Error : Cannot add the query";
        }
    
        }
    }


}




?>
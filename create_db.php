<?php

 
function createdatabase($uid) {
	$link = mysqli_connect("127.0.0.1","root","",'project') or  die('710 Error : Could not connect !<br />Please contact the site\'s administrator.');
	
	$nm = mysqli_real_escape_string($link,$uid); 
	
	$query = "CREATE DATABASE IF NOT EXISTS `$nm`";
	$retval = mysqli_query($link,$query);
	if ( ! $retval ) {
		echo "712 Error : ".mysqli_error($link)."<br>Please contact web administrator";
		die();
	}
	else {
		$query = "use `$nm`";
		$ret = mysqli_query($link,$query);
		if (! $ret) {
			die ( "710 Error :".mysqli_error($link)."<br>Contact system administrator");
		}
		else {
			
			$querya = " ";
			$return = mysqli_query($link,$querya);
			$queryb = "CREATE TABLE IF NOT EXISTS `workinghours`( `day` int(1) NOT NULL,`starttime` time NOT NULL,`endtime` time NOT NULL,`offday` int(1) NOT NULL DEFAULT '0',PRIMARY KEY(`day`))";
			$returnb = mysqli_query($link,$queryb);
			
			$queryc = "INSERT INTO `workinghours` (`day`, `starttime`, `endtime`, `offday`) VALUES
(0, '00:00:00', '00:00:00', 1),
(1, '09:30:00', '17:30:00', 0),
(2, '09:30:00', '17:30:00', 0),
(3, '09:30:00', '17:30:00', 0),
(4, '09:30:00', '17:30:00', 0),
(5, '09:30:00', '17:30:30', 0),
(6, '00:00:00', '00:00:00', 1);";
$returnc = mysqli_query($link,$queryc);
            
            
            $queryd = "CREATE TABLE IF NOT EXISTS `breakhours` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `day` int(1) NOT NULL,
  `starttime` time NOT NULL,
  `endtime` time NOT NULL,
  PRIMARY KEY(`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
";
        $returnd = mysqli_query($link,$queryd);
            
            
            $queryd1 = "INSERT INTO `breakhours` (`ID`, `name`, `day`, `starttime`, `endtime`) VALUES
(7, 'lunch', 3, '13:00:00', '13:30:00'),
(8, 'lunch', 4, '13:00:00', '13:30:00'),
(10, 'snacks', 4, '16:00:00', '16:30:00'),
(11, 'lunch', 5, '12:30:00', '13:00:00'),
(12, 'lunch', 1, '12:00:00', '12:30:00'),
(13, 'lunch', 2, '12:00:00', '12:30:00'),
(14, 'snacks', 1, '16:30:00', '17:00:00');
";
            $returnd1 = mysqli_query($link,$queryd1);
            
            
            $querye = "CREATE TABLE IF NOT EXISTS `exp_workinghours` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `starttime` time NOT NULL,
  `endtime` time NOT NULL,
  `name` text,
  PRIMARY KEY(`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
";
            
            $returne = mysqli_query($link,$querye);
            
			
			if ( ! ($return || $returnb || $returnc || $returnd || returnd1 || $returne) ) {
	die ( "713 Error :".mysqli_error($link)."<br>Contact system administrator");
}
else {
	$quer = "use `project`";
	$ret = mysqli_query($link,$quer);
		if (! $ret) {
			die ( "Error :".mysqli_error($link)."<br>Contact system administrator");
		}
	$reta = "UPDATE `userlogin` SET `dbstatus`=1 WHERE `loginid`=$nm";
	if ( ! mysqli_query($link,$reta) ){
		die ( "Error :".mysqli_error($link)."<br>Contact system administrator");
	}
	
	
}

		}
	}
}


?>
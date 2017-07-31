<?php


    
class workinghours {
		 public $day;
		 public $starttime;
		 public $endtime;
		 public $breakhours;
		 public $offday;
		 private function timetolen($time){
			 return ($time[0].$time[1])*60 + ($time[3].$time[4]);
		 }
		 public function __construct($day,$stime,$etime,$offday,$btime=null){
			 $this->day = $day;
			 $this->starttime = $this->timetolen($stime);
			
			
			
			 $this->endtime = $this->timetolen($etime);
			 $this->offday = $offday;
			 $this->breakhours = $btime;
			 
		 }
	 }
	 
	class break_hours {
		public $day;
		public $starttime;
		public $endtime;
		public $name;
		private function timetolen($time){
			 return ($time[0].$time[1])*60 + ($time[3].$time[4]);
		 }
		 public function __construct($day,$stime,$etime,$name=null){
			 $this->day = $day;
			 $this->starttime = $this->timetolen($stime);
			 $this->endtime = $this->timetolen($etime);
			 $this->name = $name;
			 
		 }
		
		
		
	}
    class exp_workinghours {
        public $day;
        public $id;
        public $date;
        public $startitme;
        public $endtime;
        private function timetolen($time){
			 return ($time[0].$time[1])*60 + ($time[3].$time[4]);
		 }
        
        public function __construct($id,$date,$stime,$etime,$name=null){
			 $this->id = $id;
             $this->date = $date;
			 $this->starttime = $this->timetolen($stime);
			 $this->endtime = $this->timetolen($etime);
			 $this->name = $name;
			 
		 }
		
    }
	 



class calendar {

private $getdate = null;
public $currentmonth = null;
public $currentyear = null;
private $currentday = null; 
private $currentsunday = null; 
private $smonth = null;
private $syear = null;
private $presentday = 0;
private $numofdaysincurrentmonth = 0;
private $weekendday = null;
private $weekendmonth = null;
private $weekendyear = null;
private $whoursdata = null;    
private $bhoursdata = null;   
private $numofbhours = null;
private $exp_whoursdata = null;
private $numofexphours = null;
    
    
    
 function __construct($day=null , $month = null , $year = null) {
     if (isset($_GET['month']) && !empty($_GET['month']) )  {
           $month = $_GET['month'];
            if($month > 12){
                $month = date("m",time());
            }
   
        }
        else if ($month == null) {
            $month = date("m",time());
        }
        if (isset($_GET['year']) && !empty($_GET['year'])) {
                  $year = $_GET['year'];
                //$year = 3000;
        }
        else if ($year == null) {
            $year = date("Y",time());
        }
        if (isset($_GET['day']) && !empty($_GET['day'])) {
                  $day = $_GET['day'];
            if($day > $this->_numofdays($month,$year)){
                $day = date("d",time());
            }
                
        }
        else if ($day == null) {
            $day = date("d",time());
        }
    $this->currentday = $day;
    $this->currentyear = $year;
    $this->currentmonth = $month;
     
        $dayname = date("w",strtotime($day.'-'.$month.'-'.$year));
        $sunday = $day - $dayname;
    
if($sunday < 1 ) {
            
    $month--;
            if($month <= 0){
                $month = 12;
                $year--;
            }
    $sunday += $this->_numofdays($month,$year);
    
}
     $this->smonth = $month;
     $this->syear = $year;
    $this->currentsunday = $sunday; 
     $this->presentday = date('d',time());

     
        $database = $_SESSION['uid'];
		$con1 = mysqli_connect("127.0.0.1","root","","$database");
		if(! $con1){
			echo "710 Error cannot connect to database";
		}
        
        $workhours = null;
        $query1 = 'SELECT * FROM `workinghours` ORDER BY `day` ASC';
        $retval1 = mysqli_query($con1,$query1);
		if(!$retval1){
			echo "Error No Data";
        }
		$row = mysqli_fetch_array($retval1,MYSQLI_BOTH);
		$inc = 0;
		while($row){
			$workhours[$inc++] = new workinghours($row[0],$row[1],$row[2],$row[3]);
			$row = mysqli_fetch_array($retval1,MYSQLI_BOTH);
		}
        $this->whoursdata = $workhours;
 
        $breakhours = null;
     
        $query2 = 'SELECT * FROM `breakhours` ORDER BY `day` ASC,`starttime` ASC';
		$retval2 = mysqli_query($con1,$query2);
		$row1 = mysqli_fetch_array($retval2,MYSQLI_BOTH);
		$incr = 0;
		while($row1){
			$breakhours[$incr++] = new break_hours($row1["day"],$row1['starttime'],$row1['endtime'],$row1['name']);
			$row1 = mysqli_fetch_array($retval2,MYSQLI_BOTH);
			//echo $row1["day"];
		}
        $exp_workhours = null;
        $query3 = "SELECT * FROM `exp_workinghours` ORDER BY `date`";
        $retval3 = mysqli_query($con1,$query3);
        if ( ! $retval3){
        echo "There is a problem with table".mysqli_error($con1);
        }
        $row3 = mysqli_fetch_array($retval3,MYSQLI_BOTH);
        $exp_incr = 0;
        while($row3){
            $exp_workhours[$exp_incr++] = new exp_workinghours($row3['ID'],$row3['date'],$row3['starttime'],$row3['endtime'],$row3['name']);
            $row3 = mysqli_fetch_array($retval3,MYSQLI_BOTH);
        }
        $this->numofbhours = $incr;
        $this->bhoursdata = $breakhours;
        $this->exp_whoursdata = $exp_workhours;
        $this->numofexphours = $exp_incr;
     
}
    
    public function _heading() {
        $a = $this->currentday.'-'.$this->currentmonth.'-'.$this->currentyear;
          $content = date("M d Y",strtotime($a));
        echo $this->weekendday;
        return $content;
    }
    
private function _numofdays($month=null,$year=null) {
        if ( $month==null) {
            
            $month = date('m',time());
        }
        if ($year == null)  {
            $year = date('Y',time());
        }
        if($month < 1){
            $month = 12;
        }
        if($month > 12) {
            $month = 1;
            $year++;
        }
        
        return date('t',strtotime($year.'-'.$month.'-01'));
    }
public function _numofweeks($month=null,$year=null) {
        
         if ( $month==null) {
            
            $month = date('m',time());
        }
        if ($year == null)  {
            $year = date('Y',time());
        }
    
        
        
    $numofdays = $this->_numofdays($month,$year);
        $numofweeks = ( ($numofdays%7==0)? 0: 1 ) + intval($numofdays/7) ;
    
    $monthendingday = date('w',strtotime($year.'-'.$month.'-'.$numofdays)); 
  $monthstartday = date('w',strtotime($year.'-'.$month.'-'.'01')); 
        if ( $monthendingday < $monthstartday) {
            $numofweeks++;
        }
        return $numofweeks;
        
    }
public function _prevmonth() {
    $premonth = $this->currentmonth==1?12:intval($this->currentmonth -1);
     $preyear = $this->currentmonth == 1?intval(($this->currentyear) -1):($this->currentyear);
    
     $content = sprintf("%02d",$this->currentday);
    $content .= sprintf("%02d",$premonth);
    $content .= sprintf("%04d",$preyear);
    return $content;
}
public function _nextmonth() {
    $nextmonth = $this->currentmonth==12?1:intval($this->currentmonth+1);
    $nextyear = $this->currentmonth==12?intval($this->currentyear)+1 : $this->currentyear;
    
    $content = sprintf("%02d",$this->currentday);
    $content .= sprintf("%02d",$nextmonth);
    $content .= sprintf("%04d",$nextyear);
    return $content;
}
 

public function _nextweek() {
    $numofdaysinmonth = $this->_numofdays($this->currentmonth,$this->currentyear);
    $tempday = $this->currentsunday + 7;
    $tempmonth = $this->smonth;
    $tempyear = $this->syear;
    $fday= 0;
    $fmonth = 0;
    if ( $tempday > $numofdaysinmonth  ) {
        $tempday -= $numofdaysinmonth;
        $fday = 1;
    }
    if($fday == 1) {
        if($tempmonth == 12){
            $tempmonth = 1;
            $fmonth = 1;
        }
        else {
            $tempmonth++;
        }
    }
    if($fmonth == 1){
        $tempyear++;
    }
    
    
   $content = null;
    
    $content .= sprintf("%02d",$tempday);
    $content .= sprintf("%02d",$tempmonth);
    $content .= sprintf("%02d",$tempyear);
    
    return $content;
}
public function _prevweek() {
    $numofdaysinprevmonth = $this->_numofdays($this->currentmonth-1,$this->currentyear);
    $tempday = $this->currentsunday - 7;
    $tempmonth = $this->smonth;
    $tempyear = $this->syear;
    $fday = 0;
    $fmonth = 0;
    if ( $tempday < 1 ) {
        $tempday += $numofdaysinprevmonth;
       $tempmonth--;
        if($tempmonth < 1){
            $tempmonth = 12;
            $tempyear--;
        }
    }
    $content = null;
     $content .= sprintf("%02d",$tempday);
    $content .= sprintf("%02d",$tempmonth);
    $content .= sprintf("%02d",$tempyear);
    
    return $content;
    
}
public function _prevday() {
    $day = $this->currentday;
    $month = $this->currentmonth;
    $year = $this->currentyear;
    $numofdays = $this->_numofdays($month-1,$year);
    $day--;
    if ( $day < 1 ){
        $day = $numofdays;
        $month--;
        if ( $month < 1){
            $month = 12;
            $year--;
        }
    }
      $content = sprintf("%02d",$day);
    $content .= sprintf("%02d",$month);
    $content .= sprintf("%02d",$year);
    
    return $content;
    
}   
public function _nextday() {
    
      $day = $this->currentday;
    $month = $this->currentmonth;
    $year = $this->currentyear;
    $numofdays = $this->_numofdays($month,$year);
    $day++;
    if ( $day  > $numofdays ){
        $day -= $numofdays;
        $month++;
        if ( $month > 1){
            $month = 1;
            $year++;
        }
    }
      $content = sprintf("%02d",$day);
    $content .= sprintf("%02d",$month);
    $content .= sprintf("%02d",$year);
    
    return $content;
}
    
    
private function whoursdata(){
      
    
       
}
    
    

public function month_view($month=null,$year=null) {
        // set $month and year variables
       
        
       $month = $this->currentmonth;
       $year = $this->currentyear;
    
        
        $numofdaysinprev = $this->_numofdays($month-1,$year);
        
        
    // show the calender
        $numofweeks = $this->_numofweeks($month,$year);
        $numofdays =$this->_numofdays($month,$year);
        $monthstartday = date("w",strtotime($year.'-'.$month.'-'.'01'));
        $monthendingday = date('w',strtotime($year.'-'.$month.'-'.$numofdays)); 
       
       $content = '';
	   $content .= '<div class="header">
    
    
    </div>';
        $content .= '<div id="mw-container"><table class="mw-container" border="1" cellpadding="0">
        
    <thead>
        <th class="mw-weekday">Sun</th>
        <th class="mw-weekday">Mon</th>
        <th  class="mw-weekday">Tue</th>
        <th class="mw-weekday">Wed</th>
        <th class="mw-weekday">Thu</th>
        <th class="mw-weekday">Fri</th>
        <th class="mw-weekday">Sat</th>
    </thead> <tbody>';
             
        
        
   
        
$flag = 1;
        
        $day = 1;
        $startday = $numofdaysinprev - $monthstartday + 1;
        $endday = 1;
        for ($i=1;$i<=6;$i++){
      
            $content .= '<tr class="mw-event-container"> ';
            for($j=1;$j<=7;$j++){
                
                if($flag == 1) {
                    for ($k=0;$k<$monthstartday;$k++) {
                      $content .= '<td class="no-date">'.$startday.'</td>';  
                        $j++;
                        $startday++;
                        $flag = 0;
                    }
                            }
                if ($day == date("d",time())&& $month == date("m",time()) && $year == date("Y",time()) && $day <= $numofdays)  {
                    $content .= '<td class="mw-droppable">
            <div class="mw-over-hide" id="div-day-'.$year.'-'.sprintf("%02d",$month).'-'.date("d",strtotime($year.'-'.$month.'-'.$day)).'" >
            <table class="mw-event-table active" id="day-'.$year.'-'.sprintf("%02d",$month).'-'.date("d",strtotime($year.'-'.$month.'-'.$day)).'">
                <thead>
                <th  id = "tst" height="24px" align="right" style="vertical-align : text-top;">'.$day.'</th>
                </thead>
                <tbody ><tr><td id="mw-day-'.$year.'-'.sprintf("%02d",$month).'-'.date("d",strtotime($year.'-'.$month.'-'.$day)).'" class="mw-events-container">
                
                </td></tr>
                
                </tbody>
            
            </table>
                </div>
            </td>' ;
               $day++;
                }
                else if($day <= $numofdays && date("w",strtotime($year.'-'.$month.'-'.$day)) == 0 || date("w",strtotime($year.'-'.$month.'-'.$day)) == 6 ) {
                     $content .= '<td class="mw-droppable">
            <div  class="mw-over-hide" id="div-day-'.$year.'-'.sprintf("%02d",$month).'-'.date("d",strtotime($year.'-'.$month.'-'.$day)).'">
            <table class="mw-event-table holiday" id="day-'.$year.'-'.sprintf("%02d",$month).'-'.date("d",strtotime($year.'-'.$month.'-'.$day)).'" border="0" style="border-collapse : collapse padding:0px;" cellpadding = "0px">
                <thead>
                <th  id = "tst" height="24px" align="right" style="vertical-align : text-top;">'.$day.'</th>
                </thead>
                <tbody >
                <tr><td id="mw-day-'.$year.'-'.sprintf("%02d",$month).'-'.date("d",strtotime($year.'-'.$month.'-'.$day)).'" class="mw-events-container">
                
                </td></tr>
                
                </tbody>
            
            </table>
                </div>
            </td>';
               $day++;
                }
                
                else if ($day <= $numofdays) {
               $content .= '<td class="mw-droppable">
            <div class="mw-over-hide" id="div-day-'.$year.'-'.sprintf("%02d",$month).'-'.date("d",strtotime($year.'-'.$month.'-'.$day)).'">
            <table class="mw-event-table" id="day-'.$year.'-'.sprintf("%02d",$month).'-'.date("d",strtotime($year.'-'.$month.'-'.$day)).'" border="0" style="border-collapse : collapse padding:0px;" cellpadding = "0px">
                <thead>
                <th  id = "tst" height="24px" align="right" style="vertical-align : text-top;">'.$day.'</th>
                </thead>
                <tbody >
                <tr><td id="mw-day-'.$year.'-'.sprintf("%02d",$month).'-'.date("d",strtotime($year.'-'.$month.'-'.$day)).'" class="mw-events-container">
                
                </td></tr>
                
                </tbody>
            
            </table>
                </div>
            </td>';
              
                    
                    
                    
                    
                    $day++;
                }
                else  {
                    
                        $content .= '<td class="no-date">'.$endday.'</td>';
                        $endday++;
                        
                    
                    
                }
                
            }
            $content .= '</tr>';
            
        } 
        
        $content .= '</tbody></table></div>';
   
    return $content;
    }
public function week_view($day=null , $month = null , $year = null) {
	 
	 
	 
	 
		// get the timings from sdatabaSe Name workinghours.
		// 0-Sun 1-Mon 2-Tue 3-Wed 4-Thu 5-FRi 6-Sat
	   //	echo ($workhours[4]->starttime);
		
		// get the breakhours table
		
	$weekdays = null;
		
	
	
	$workhours = $this->whoursdata;
    $breakhours = $this->bhoursdata;
	$incr = $this->numofbhours;
	$exp_workhours = $this->exp_whoursdata;
	
    
    $dayInWeek = null;
    $month = $this->smonth;
    $year = $this->syear;
    $day = $this->currentday;
        
      
    
    $sunday =  $this->currentsunday;
     
    
    $weeknumber = date("W",strtotime($day.'-'.$month.'-'.$year));
    
    
    
        $content = '';
    
        $head = '<thead class="aw-head"><tr id="aw-week-head">';
    
        $head .= '<th class="aw-week-head aw-axis" id="aw-head-7"></th>';
    $numofdays = $this->_numofdays($month,$year);
    for ($i=0;$i < 7 ; $i++) {
       $tempmonth = $month;
        $tempyear = $year;
        $tempday = $sunday + $i;
        if ( $tempday > $numofdays) {
            $tempday -= $numofdays;
            if( $tempmonth == 12){
                $tempmonth = 1;
                $tempyear++;
            }
            else {
                $tempmonth++;
            }
        }
        if($i == 6) {
            $this->weekendday = $tempday;
            $this->weekendmonth = $tempmonth;
            $this->weekendyear = $tempyear;
        }
				$dayInWeek[$i] = sprintf("%02d-%02d-%04d",$tempday,$tempmonth,$tempyear);
        $head .= '<th class="aw-week-head aw-set-width" id="aw-head-'.$i.'" >'.date("D",strtotime(($tempday).'-'.$tempmonth.'-'.$tempyear)).'&nbsp;&nbsp;&nbsp;'.(sprintf("%02d",$tempday)).'/'.sprintf("%02d",$tempmonth).'</th>';
    
    
            $weekdays[$i]['day'] = $tempday;
            $weekdays[$i]['month'] = $tempmonth;
            $weekdays[$i]['year'] = $tempyear;
    
    }   
    
    
    
    
    
    
   // $head .= '<th class="aw-no-head" id="aw-scrollwidth" width="15px"></th>';
        $activeday = $day - $sunday;
    
        $content .= '';
		$content .= '<div class="aw-header"><table style="border : 1px black solid; border-collapse:collapse;">';
		$head .= '</tr></thead>';
		$content .= $head;
		$content .= '</table></div>';
        $content .= '<div class="aw-scrollable" id="aw-vertical-lines">
    
        <div class="aw-vertical-lines" style = "">
        <table border="1" style="border-collapse : collapse;" id="aw-tablewidth">';
        
    //$content .= $head;
        $content .= '<tbody class="aw-body">
            <tr class="setheight" height="2400px">
            <td class="aw-axis" id="aw-body-7"></td>';    
    for ( $i = 0 ; $i < 7;$i++){
         if ($i == $activeday && $day == date("d",time()) && $month == date("m",time()) && $year == date("Y",time())) {
            $content .= '<td   id="aw-body-'.$i.'" class="active aw-set-width"></td>';
        }
         else if ( $i == 0 || $i ==6){
             $content .= '<td  id="aw-body-'.$i.'" class="holiday aw-set-width"></td>';
        }
        
        else {
            $content .= '<td class="aw-set-width"  id="aw-body-'.$i.'"" ></td>';   
        }
    }
    $content .= '</tr></tbody></table>';
    
    $content .= '</div><div class="aw-time-grid"><table border="0" style="width : auto; border-collapse : collapse"><tbody>';
    
	
	for ($i = 0;$i<24;$i++){
    

$bhinc1 = 0;
	$bhinc2 = 0;
    
    $time ='';
        
		if ( $i == 0) {
			$time = '12am<br>';
		}
		
        else  if ($i <= 12) {
            
             $time = ($i).'am<br>';
        }
        
        else {
            
            $k = $i%12;
			
            $time = ($k).'pm<br>';
        
            
        }
        
        $content .= '<tr class="aw-time-grid-1 time-'.$i.':00" height="50px">
                        <td class="time-head aw-axis">
                        <span class="aw-time-head">'.$time.'</span>
                        </td>';
						
								for($k =0 ; $k < 7;$k++){
									
									if( $workhours[$k]->starttime <= $i*60 && $i*60 < $workhours[$k]->endtime && $workhours[$k]->offday == 0){
										$flag = 0;
										
										while(($bhinc1 < $incr )&& ($k == $breakhours[$bhinc1]->day)){
											
											if($breakhours[$bhinc1]->starttime <= $i*60 && $i*60 < $breakhours[$bhinc1]->endtime) {
											//echo '<br>  loop NO : '.$k.'   time : '.$i.'   name : '.$breakhours[$bhinc1]->name;
											$flag=1;
										$content .= '<td class="aw-set-width aw-time-grid-a day-'.$dayInWeek[$k].' nonworkinghour" id="aw-row-width">'.$breakhours[$bhinc1]->name.'</td>';
											}
											$bhinc1++;
											
									
									}
										if ( $flag !=1 )
										$content .= '<td class="aw-set-width aw-time-grid-a day-'.$dayInWeek[$k].' workinghour" id="aw-row-width"></td>';
									
									}else
									{
										  $flag  = 0;                
                                        
                                            for ($j = 0;$j < $this->numofexphours; $j++){
                            
                                                if ( ($weekdays[$k]['year'].'-'.$weekdays[$k]['month'].'-'.$weekdays[$k]['day']) == $exp_workhours[$j]->date){
                            if ($exp_workhours[$j]->starttime <= ($i*60) && $i*60 < $exp_workhours[$j]->endtime){
                                
                                 $content .= '<td class="aw-set-width aw-time-grid-b day-'.$dayInWeek[$k].' workinghour" id="aw-row-width"></td>';
			                     $flag = 1;
                            }
                      //  echo $exp_workhours[$j]->starttime.'  '.($i*60).'<br>';
                        }
                    }
                    if ( $flag == 0)
                    $content .= '<td class="aw-set-width aw-time-grid-b day-'.$dayInWeek[$k].' nonworkinghour" id="aw-row-width"></td>';
				}
								}								
            $content .=          '</tr><tr class="aw-time-grid-2  time-'.$i.':30" height="50px">
									<td class="time-head" >
									<span></span>
								</td>';
								
								
								
			for($k =0 ; $k < 7;$k++){
                if( $workhours[$k]->starttime <= $i*60+30 && $i*60+30 < $workhours[$k]->endtime && $workhours[$k]->offday == 0){
							$flag = 0;
										
										while($bhinc2 < $incr && $k == $breakhours[$bhinc2]->day){
											
											if($breakhours[$bhinc2]->starttime <= $i*60+30 && $i*60+30 < $breakhours[$bhinc2]->endtime) {
											$flag=1;
											$content .= '<td class="aw-set-width aw-time-grid-b day-'.$dayInWeek[$k].' nonworkinghour" id="aw-row-width">'.$breakhours[$bhinc2]->name.'</td>';
											}
											$bhinc2++;
											
										
											
									}
												if ( $flag != 1)
												$content .= '<td class="aw-set-width aw-time-grid-b day-'.$dayInWeek[$k].' workinghour" id="aw-row-width"></td>';
									}
                else{
							
                        $flag  = 0;                
                                        
                    for ($j = 0;$j < $this->numofexphours; $j++){
                            
                    if ( ($weekdays[$k]['year'].'-'.$weekdays[$k]['month'].'-'.$weekdays[$k]['day']) == $exp_workhours[$j]->date){
                            if ($exp_workhours[$j]->starttime <= ($i*60 + 30) && $i*60+30 < $exp_workhours[$j]->endtime){
                                
                                 $content .= '<td class="aw-set-width aw-time-grid-b day-'.$dayInWeek[$k].' workinghour" id="aw-row-width"></td>';
			                     $flag = 1;
                            }
                      //  echo $exp_workhours[$j]->starttime.'  '.($i*60).'<br>';
                        }
                    }
                    if ( $flag == 0)
                    $content .= '<td class="aw-set-width aw-time-grid-b day-'.$dayInWeek[$k].' nonworkinghour" id="aw-row-width"></td>';
			
                } 
            }
        
    }
    
    $content .= '</tr></tbody></table></div>';
    
    
    $content .= '<div class="aw-events">
            <table id="aw-droppable" style="z-index : 2;"><tr>
            <td class="aw-axis"></td>';
            
    
    for ($i=0;$i < 7 ; $i++) {
       $tempmonth = $month;
        $tempyear = $year;
        $tempday = $sunday + $i;
        if ( $tempday > $numofdays) {
            $tempday -= $numofdays;
            if( $tempmonth == 12){
                $tempmonth = 1;
                $tempyear++;
            }
            else {
                $tempmonth++;
            }
        }
        $content .= '<td class="aw-events-div aw-set-width">
                    <div class="aw-events-div" id="aw-day-'.$tempyear.'-'.sprintf("%02d",$tempmonth).'-'.sprintf("%02d",$tempday).'">
                   
                    
                    </div>
                   </div> </td>';
    
    }
    
    
    
        $content .= '</tr></table></div></div>';
    
    return  $content;
    
    
}
   
    public function day_view($day = null,$month = null ,$year = null) {
        
        $month = $this->currentmonth;
    $year = $this->currentyear;
    $day = $this->currentday;
            
	$workhours = $this->whoursdata;
    $breakhours = $this->bhoursdata;
	$incr = $this->numofbhours;
	
        $cday =  date('w',strtotime($year.'-'.$month.'-'.$day));
        
        $content = '';
		$head = '';
		$head .= '<div><table border="1" style="border-collapse : collapse">';
		$head .= '<thead><tr height="20px"><th class="ad-axis"></th>';
		$head .= '<th class="ad-set-width">'.date("l",strtotime($year.'-'.$month.'-'.$day)).'</th>';
        $head .= '</tr></thead></table></div>';
		$content .= $head;
		
    $content .= '<div class="ad-scrollable">
            <div class="ad-vertical-lines" >
        <table border="1" style="border-collapse : collapse; height : 2400px" id="ad-tablewidth">';
        
        if ( date('w',strtotime($year.'-'.$month.'-'.$day)) == 0 || date('w',strtotime($year.'-'.$month.'-'.$day)) == 6 ){
            $content .= '<tbody class="ad-body" height="2400px">
            <tr height="2400px">
            <td class="ad-axis" width="100x"></td>    
            <td class="holiday ad-set-width"></td>   
            </tr></tbody></table>';
        }
        else {
      
        $content .= '<tbody class="ad-body" height="2400px">
            <tr height="2400px">
            <td class="ad-axis" width="100x"></td>    
            <td class="ad-set-width"></td>   
            </tr></tbody></table>';
        }
        $content .= '</div><div class="ad-time-grid"><table border="0" style="width : auto; border-collapse : collapse"><tbody>';
    
    for ($i = 0;$i<24;$i++){
        $time ='';
        
        if ( $i == 0) {
			$time = '12am<br>';
		}
		
        else  if ($i <= 12) {
            
             $time = ($i).'am<br>';
        }
        
        else {
            
            $k = $i%12;
			
            $time = ($k).'pm<br>';
        
            
        }
        $content .= '<tr class="ad-time-grid-1">
                        <td class="time-head ad-axis">
                        <span class="ad-time-head">'.$time.'</span>
                        </td>';
                      
            $bhinc1 = 0;
            $bhinc2 = 0;
                        
        if ($workhours[$cday]->starttime <= $i*60 && $i*60 < $workhours[$cday]->endtime && $workhours[$cday]->offday == 0){
                        
        	$flag = 0;
										for ( $bhinc2 = 0;$bhinc2 < $incr ; $bhinc2++){
										if($bhinc2 < $incr && $cday == $breakhours[$bhinc2]->day){
											
											if($breakhours[$bhinc2]->starttime <= $i*60 && $i*60 < $breakhours[$bhinc2]->endtime) {
											$flag=1;
											$content .= '<td class="ad-set-width nonworkinghour" >'.$breakhours[$bhinc2]->name.'</td>';
											}
											
											
										
											
									}
                                        }
												if ( $flag != 1)
												$content .= '<td class="ad-set-width ad-time-grid-1 workinghour"></td>';
									}
        
        else    {             
                   $content .= '<td class="ad-set-width nonworkinghour"></td>';
        }
        
        
        
        $content .=  '</tr>
                        <tr class="ad-time-grid-2">
                        <td class="time-head ad-axis">
                        <span></span>
                        </td>';
    
        
         if ($workhours[$cday]->starttime <= ($i*60 + 30) && ($i*60 + 30) < $workhours[$cday]->endtime && $workhours[$cday]->offday == 0){
                                              
                    $flag = 0;          for ( $bhinc1 = 0;$bhinc1 < $incr ; $bhinc1++){
                                        if(($bhinc1 < $incr )&& ($cday == $breakhours[$bhinc1]->day)){
											
                                    if($breakhours[$bhinc1]->starttime <= ($i*60 + 30) && ($i*60 + 30) < $breakhours[$bhinc1]->endtime) {
											//echo '<br>  loop NO : '.$k.'   time : '.$i.'   name : '.$breakhours[$bhinc1]->name;
											$flag=1;
										$content .= '<td class="ad-set-width nonworkinghour" >'.$breakhours[$bhinc1]->name.'</td>';
											}
											
											
									
									}
                    }
										if ( $flag !=1 )
										$content .= '<td class="ad-set-width workinghour"></td>';
									
             
             
                    
        }
        else    {             
                   $content .= '<td class="ad-set-width nonworkinghour"></td>';
        }
                       
                        
        
    }
    
    $content .= '</tr></tbody></table></div>';
    
    
    $content .= '<div class="ad-events">
            <table id="ad-droppable" style="z-index : 2;"><tr>
            <td class="ad-axis"></td>';
        
        
         $content .= '<td class="ad-events-div ad-set-width" >
                    <div class="ad-events-div" id="ad-day-'.$year.'-'.sprintf("%02d",$month).'-'.sprintf("%02d",$day).'">
                   </div>
                   </div> </td>';
    
     $content .= '</tr></table></div></div>';
    return $content;
    }
    
    }


?>
<?php


class mini_calendar {

private $getdate = null;
private $currentmonth = null;
private $currentyear = null;
private $presentday = 0;
private $numofdaysincurrentmonth = 0;
private $pagemonth = null;
private $pageyear = null;
private $getmonth = null;
private $getyear = null;
function __construct() {
		if(isset($_GET['month'])){
			$this->getmonth = $_GET['month'];
			if($this->getmonth < 1 || $this->getmonth > 12){
				$this->getmonth = null;
			}
		}
		if(isset($_GET['year'])){
			$this->getyear = $_GET['year'];
			if($this->getyear < 0){
				$this->getyear = null;
			}
		}
	}
private function _numofdays($month=null,$year=null) {
        if ( $month==null) {
            
            $month = date('m',time());
        }
        if ($year == null)  {
            $year = date('Y',time());
        }
        
        return date('t',strtotime($year.'-'.$month.'-01'));
    }
private function _numofweeks($month=null,$year=null) {
        
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
private function _prev() {
    $premonth = $this->currentmonth==1?12:intval($this->currentmonth -1);
     $preyear = $this->currentmonth == 1?intval(($this->currentyear) -1):($this->currentyear);
    return '<th class="navi"><a class="prevmonth" href="javascript:miniprev();">&#10094;</a></th>';
}

public function _prevmonth() {
    $premonth = $this->currentmonth==1?12:intval($this->currentmonth -1);
     $preyear = $this->currentmonth == 1?intval(($this->currentyear) -1):($this->currentyear);
    
     $content = '';
    $content .= sprintf("%02d",$premonth);
    $content .= sprintf("%04d",$preyear);
    return $content;
}
public function _nextmonth() {
	 $nextmonth = $this->currentmonth==12?1:intval($this->currentmonth+1);
    $nextyear = $this->currentmonth==12?intval($this->currentyear)+1 : $this->currentyear;
    
	$content = '';
    $content .= sprintf("%02d",$nextmonth);
    $content .= sprintf("%04d",$nextyear);
    return $content;
}

private function _next() {
    $nextmonth = $this->currentmonth==12?1:intval($this->currentmonth+1);
    $nextyear = $this->currentmonth==12?intval($this->currentyear)+1 : $this->currentyear;
    
        return '<th class="navi "><a class="nextmonth" href="javascript:mininext();">&#10095;</a></th>';
    }
	
public function mini_view($get_month=null,$get_year=null) {
        // set $month and year variables
        
    $month = null;
    $year = null;
        
        if ($month == null && !empty($get_month)) {
           $month = $get_month;
				
   
        }
        else if ($month == null) {
            $month = date("m",time());
        }
        if ($year == null && !empty($get_year)) {
                  $year = $get_year;
                
        }
        else if ($year == null) {
            $year = date("Y",time());
        }
        
       $this->currentmonth = $month;
       $this->currentyear = $year;
    
        $this->presentday = date('d',time());
        
        
        
    // show the calender
        $numofweeks = $this->_numofweeks($month,$year);
        $numofdays =$this->_numofdays($month,$year);
        $monthstartday = date("w",strtotime($year.'-'.$month.'-'.'01'));
        $monthendingday = date('w',strtotime($year.'-'.$month.'-'.$numofdays)); 
       
       $content = '';
        $content .= '<div id = "mini_calender"  align="left">'.
         '<table class="nav-month">'.$this->_prev().
            '<th class="nav-head" colspan="5" ><span id="title">'.date('M',strtotime($year.'-'.$month.'-01')).'   '.date('Y',strtotime($year.'-'.$month.'-01')).'</span> </th>'.$this->_next().'';
             
        
        
   $content .= '<tr >    
        <td class="nav-head"><span style="color:red">S</span></td>
            <td class="nav-head">M</td>
            <td class="nav-head">T</td>
            <td class="nav-head">W</td>
            <td class="nav-head">T</td>
            <td class="nav-head">F</td>
            <td class="nav-head"><span style="color:red">S</span></td>
            </tr>';     
        
        $flag = 1;
        
        $day = 1;
        for ($i=1;$i<=$numofweeks;$i++){
      
            $content .= '<tr class="day">';
            for($j=1;$j<=7;$j++){
                
                if($flag == 1) {
                    for ($k=0;$k<$monthstartday;$k++) {
                      $content .= '<td class="nodate"></td>';  
                        $j++;
                        $flag = 0;
                    }
                            }
                if ($day == date("d",time())&& $month == date("m",time()) && $year == date("Y",time()) )  {
                    $content .= '<td class="active"><a href="?day='.$day.'&month='.$month.'&year='.$year.'"><div>'.$day.'</div></td>' ;
               $day++;
                }
                else if(date("w",strtotime($year.'-'.$month.'-'.$day)) == 0 || date("w",strtotime($year.'-'.$month.'-'.$day)) == 6) {
                     $content .= '<td class="holidays"><a href="?day='.$day.'&month='.$month.'&year='.$year.'"><div>'.$day.'</div></td>';
               $day++;
                }
                
                else {
               $content .= '<td><a href="?day='.$day.'&month='.$month.'&year='.$year.'"><div>'.$day.'</div></td>';
               $day++;
                }
                if ($day > $numofdays) {
                    for ( $a = $j;$a<7;$a++) {
                        $content .= '<td class="nodate"></td>';
                    }
                    break;
                }
                
            }
            $content .= '</tr>';
            
        } 
        
        $content .= '</table></div>';
   
   $content .= '<div id="minidata" display="none">
				<span id="ndate">'.$this->_nextmonth().'</span>
				<span id="pdate">'.$this->_prevmonth().'</span>
				</div>';
   
    return $content;
    }

   
}








$mincal = new mini_calendar;

if(isset($_GET['month']) && isset($_GET['year']) ){
	echo $mincal->mini_view($_GET['month'],$_GET['year']);
}
else
	echo $mincal->mini_view();


?>

        
        
   
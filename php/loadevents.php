<?php
session_start();
$database = $_SESSION['uid'];

$link = mysqli_connect("127.0.0.1","root","","$database");
if (! $link) {
    die('Error : Could not connect database.');
}

   function cmp($a , $b){

       
     $x = strcmp($a["eventdate"],$b["eventdate"]);
        if ( $x != 0)
            return $x;
       
    $y = strcmp($a['eventtime'],$b['eventtime']);
       if ( $y != 0) {
           return $y;
       }
return ( $a["eventduration"] -  $b["eventduration"]);
       
        }

if ( isset($_POST['eventid']) && !empty($_POST['eventid'])){

    $eventid = $_POST['eventid'];   
    $sql = "SELECT * FROM `events` WHERE ID=$eventid;";

if (! $retval = mysqli_query($link,$sql) ) {

echo'could not select database.<br>';
die('Debug Error : '.mysqli_error($link) );
}
    
$content = '{"events":[' ;
$row = mysqli_fetch_array($retval,MYSQLI_BOTH);
$numofrows = mysqli_num_rows($retval);

if ( $numofrows == 1) {
$content .= "{\"ID\":\"$row[0]\",\"name\":\"$row[1]\",\"eventdate\":\"$row[2]\",\"eventtime\":\"$row[3]\",\"recordtime\":\"$row[4]\",\"eventduration\":\"$row[5]\",\"eventstatus\":\"$row[6]\" }";
    }


$content .= ']}';
echo $content;
}
    
    

else {
    
    
    $edinc = 0;
    $eventdata = null;
    $recurdata = null;
    
    if ( isset($_POST['date'])) {
        
        $x =  $_POST['date'];
        //echo $x;
        
      
            if ($x == ''){
            
            $temp =  date("Y-m");
            $temp .= "-00";
            $startdate = date("Y-m-d", strtotime($temp." -1 week"));
            $enddate = date("Y-m-d",strtotime($temp."+1 month +1 week"));
            
            }
            else {
      
                    $startdate = date("Y-m-d",strtotime($x[2].$x[3].$x[4].$x[5].'-'.$x[0].$x[1].'-00'.' -1 week'));

                    $enddate = date("Y-m-d",strtotime($x[2].$x[3].$x[4].$x[5].'-'.$x[0].$x[1].'-00'.' +1 month +1 week'));
    
                
            }
    
        
 //       echo $startdate;                 
        
    //    $startdate = date("Y-m-d", strtotime(""))
        
    $sql = "SELECT * FROM `events` WHERE `eventdate` <= '$enddate' AND `eventdate` >= '$startdate' ORDER BY eventdate ASC,eventtime ASC;";

if (! $retval = mysqli_query($link,$sql) ) {

echo'could not select database.<br>';
die('Debug Error : '.mysqli_error($link) );
}
$row = mysqli_fetch_array($retval,MYSQLI_BOTH);
$numofrows = mysqli_num_rows($retval);
do {
if ( !$row ) {
}
else  {
$eventdata[$edinc]['ID'] = $row['ID'];
$eventdata[$edinc]['name'] = $row['name'];
$eventdata[$edinc]['eventdate'] = $row['eventdate'];
$eventdata[$edinc]['eventtime'] = $row['eventtime'];
$eventdata[$edinc]['eventduration'] = $row['eventduration'];
$eventdata[$edinc]['eventstatus'] = $row['eventstatus'];
$edinc++;
    

}
}while ( $row = mysqli_fetch_array($retval,MYSQLI_BOTH) );

        
    
        
// load the recurring events.
        
        
        
        
        $query = "SELECT * FROM `recur_events` WHERE `enddate` >= '$startdate'";
        
        if ( ! $returnval = mysqli_query($link,$query)){
            echo("Couldn't select the database.<br>");
            die('Debug Error : '.mysqli_error($link));
        }
        $row1 = mysqli_fetch_array($returnval,MYSQLI_BOTH);
        $numofeventdata = mysqli_num_rows($returnval);
        
        $rincr = 0;
        while($row1) {
            $recurdata[$rincr]['ID'] = $row1['ID'];
            $recurdata[$rincr]['name'] = $row1['name'];
            $recurdata[$rincr]['starttime'] = $row1['starttime'];
            $recurdata[$rincr]['duration'] = $row1['duration'];
            $recurdata[$rincr]['startdate'] = $row1['startdate'];
            $recurdata[$rincr]['enddate'] = $row1['enddate'];
            $recurdata[$rincr]['recur_type'] = $row1['recur_type'];
            $recurdata[$rincr]['recur_length'] = $row1['recur_length'];
            $recurdata[$rincr]['recur_data'] = $row1['recur_data'];
            $recurdata[$rincr]['eventstatus'] = $row1['event_status'];
            $rincr++;
        if ( strcmp($row1['startdate'],$startdate) > 0){
               $sdate = $row1['startdate'];
            }
            else {
                $sdate = $startdate;
            // echo $sdate;
            }
            
            if( $row1['enddate'] == '9999-12-31'){
                $len = date_diff(date_create($sdate),date_create($enddate));
                $len = $len->format("%R%a");
               // echo $len, $sdate , $enddate;
            }
            else {
                $len = date_diff(date_create($sdate),date_create($row1['enddate']));
                $len = $len->format("%R%a");
            }
        
      
        
        
        
        
        if ( $len > 0 ) {
        
        if ( $row1['recur_type'] == 1) { // if the recurevents are recurring  day type with recur_length so and so.
        
            //$sdate = (date_diff(date_create($row1['startdate']),date_create($startdate)))?$row1['startdate']:$startdate;
            // if  $row1['startdate'] is after startdate then make sdate as $row1['startdate']
            
         //   echo $len;
            $datacontent = null;
            
            for ( $i = 0; $i < $len ; $i++ ){
                
                $diff = date_diff(date_create(date("Y-m-d",strtotime($sdate.' +'.$i.'days'))),date_create($row1['startdate']));
                if ( ($diff->format("%R%a"))%(int)($row1['recur_length']) == 0 ) {
                    
                   $tempquery = 'SELECT * FROM `exp_recur_events` WHERE `ID` = "'.$row1['ID'].'" AND `modifieddate` = "'.date("Y-m-d",strtotime($sdate.' +'.$i.'days')).'";';
                    
                    $rval1 = mysqli_query($link,$tempquery);
                    $expevent = mysqli_fetch_array($rval1,MYSQLI_BOTH);
                     
                    if ( $expevent ) {
                        
                        if ( $expevent['deleteevent'] != 1) {
                                $eventdata[$edinc]['ID'] = $row1['ID'].'_repeat_'.date("Y-m-d",strtotime($sdate.' +'.$i.'days'));
                                $eventdata[$edinc]['name'] = $row1['name'];
                                $eventdata[$edinc]['eventdate'] = $expevent['newdate'];
                                $eventdata[$edinc]['eventtime'] = $expevent['newstarttime'];
                                $eventdata[$edinc]['eventduration'] = $expevent['newduration'];
                                $eventdata[$edinc]['eventstatus'] = $expevent['newstatus'];
                                $edinc++;
                        }
                    }
                    else {
                            $eventdata[$edinc]['ID'] = $row1['ID'].'_repeat_'.date("Y-m-d",strtotime($sdate.' +'.$i.'days'));
                            $eventdata[$edinc]['name'] = $row1['name'];
                            $eventdata[$edinc]['eventdate'] = date("Y-m-d",strtotime($sdate.' +'.$i.'days'));
                            $eventdata[$edinc]['eventtime'] = $row1['starttime'];
                            $eventdata[$edinc]['eventduration'] = $row1['duration'];
                            $eventdata[$edinc]['eventstatus'] = $row1['event_status'];
                            $edinc++;
                    } 
                }
                
                
                
            }
            
            
        }
        
        
        
        else if($row1['recur_type'] == 2){ // week recurring type
            
            //get  the startdate 
            
            for($i = 0;$i < $len;$i++){
                
                
                $diff = date_diff(date_create(date("Y-m-d",strtotime($sdate.' +'.$i.'days'))),date_create($row1['startdate']));
                
                
                $flag = 0;
                for($z = 0; $z < strlen($row1['recur_data']) ; $z++){
                    if (((($diff->format("%a") + date('w',strtotime($row1['startdate'])) )/7) %  ($row1['recur_length'] )) == 0){
                        
                        if ( date('w' , strtotime($sdate.' +'.$i.' days')) == $row1['recur_data'][$z])
                        $flag = 1;
                    }
                }
                
                
              //  echo (($diff->format("%a")/7) %  ($row1['recur_length'] )).'-'.$flag.'                ';
                
                if (  $flag){
                     
                 
                    
                  $tempquery = 'SELECT * FROM `exp_recur_events` WHERE `ID` = "'.$row1['ID'].'" AND `modifieddate` = "'.date("Y-m-d",strtotime($sdate.' +'.$i.'days')).'";';
                    
                    $rval1 = mysqli_query($link,$tempquery);
                    $expevent = mysqli_fetch_array($rval1,MYSQLI_BOTH);
                     
                    if ( $expevent ) {
                        
                        if ( $expevent['deleteevent'] != 1) {
                                $eventdata[$edinc]['ID'] = $row1['ID'].'_repeat_'.date("Y-m-d",strtotime($sdate.' +'.$i.'days'));
                                $eventdata[$edinc]['name'] = $row1['name'];
                                $eventdata[$edinc]['eventdate'] = $expevent['newdate'];
                                $eventdata[$edinc]['eventtime'] = $expevent['newstarttime'];
                                $eventdata[$edinc]['eventduration'] = $expevent['newduration'];
                                $eventdata[$edinc]['eventstatus'] = $expevent['newstatus'];
                                $edinc++;
                        }
                    }
                    else {
                            $eventdata[$edinc]['ID'] = $row1['ID'].'_repeat_'.date("Y-m-d",strtotime($sdate.' +'.$i.'days'));
                            $eventdata[$edinc]['name'] = $row1['name'];
                            $eventdata[$edinc]['eventdate'] = date("Y-m-d",strtotime($sdate.' +'.$i.'days'));
                            $eventdata[$edinc]['eventtime'] = $row1['starttime'];
                            $eventdata[$edinc]['eventduration'] = $row1['duration'];
                            $eventdata[$edinc]['eventstatus'] = $row1['event_status'];
                            $edinc++;
                    }
                }
                
            }
            
        }
        
        
        else if ($row1['recur_type'] == 3 ) { // if the recurring is month type
            
            
                // every that date of that month or Xth week X day
                // new algo check if the given month is in that compatable with recur_length

              
            // for loop is unnecessary ;
            // just stick with the start and enddates;
            
               
                  
                
                $diff =   (date("m",strtotime($startdate))-((int)($row1['startdate'][5].$row1['startdate'][6]))) + 12*(date("Y",strtotime($startdate)) - date("Y",strtotime($row1['startdate'])));
                
              //  echo $diff.' ---  '.(date("Y",strtotime($startdate)) - date("Y",strtotime($row1['startdate']))).'            ';
                
                if ( ($diff)%$row1['recur_length'] == 0 ){
                        
                    $eventdata[$edinc]['ID'] = $row1['ID'].'_repeat_'.date('Y-m-',strtotime($startdate)).$row1['startdate'][8].$row1['startdate'][9];
                    $eventdata[$edinc]['name'] = $row1['name'];
                    $eventdata[$edinc]['eventdate'] = date('Y-m-',strtotime($startdate)).$row1['startdate'][8].$row1['startdate'][9];
                    $eventdata[$edinc]['eventtime'] = $row1['starttime'];
                    $eventdata[$edinc]['eventduration'] = $row1['duration'];
                    $eventdata[$edinc]['eventstatus'] = $row1['event_status'];
                    $edinc++;
                    
                }
            
            
            
            
                $diff =   (date("m",strtotime($startdate)) + 1 - ((int)($row1['startdate'][5].$row1['startdate'][6]))) + 12*(date("Y",strtotime($startdate.' +15 days')) - date("Y",strtotime($row1['startdate'])));
                
              //  echo $diff.' ---  '.(date("Y",strtotime($startdate.' +15 days')) - date("Y",strtotime($row1['startdate']))).'        ';
                
                if ( ($diff)%$row1['recur_length'] == 0 ){
                        
                    $eventdata[$edinc]['ID'] = $row1['ID'].'_repeat_'.date('Y-m-',strtotime($startdate.' +15 days')).$row1['startdate'][8].$row1['startdate'][9];
                    $eventdata[$edinc]['name'] = $row1['name'];
                    $eventdata[$edinc]['eventdate'] = date('Y-m-',strtotime($startdate.' +15 days')).$row1['startdate'][8].$row1['startdate'][9];
                    $eventdata[$edinc]['eventtime'] = $row1['starttime'];
                    $eventdata[$edinc]['eventduration'] = $row1['duration'];
                    $eventdata[$edinc]['eventstatus'] = $row1['event_status'];
                    $edinc++;
                    
                }
            
            
            
                $diff =   (date('m',strtotime($enddate))-((int)($row1['startdate'][5].$row1['startdate'][6]))) + 12*(date("Y",strtotime($enddate)) - date("Y",strtotime($row1['startdate'])));
                
            //    echo $diff.' ---  '.(date("Y",strtotime($enddate)) - date("Y",strtotime($row1['startdate']))). '       ';
                
                if ( ($diff)%$row1['recur_length'] == 0 ){
                        
                    $eventdata[$edinc]['ID'] = $row1['ID'].'_repeat_'.date('Y-m-',strtotime($enddate)).$row1['startdate'][8].$row1['startdate'][9];
                    $eventdata[$edinc]['name'] = $row1['name'];
                    $eventdata[$edinc]['eventdate'] = date('Y-m-',strtotime($enddate)).$row1['startdate'][8].$row1['startdate'][9];
                    $eventdata[$edinc]['eventtime'] = $row1['starttime'];
                    $eventdata[$edinc]['eventduration'] = $row1['duration'];
                    $eventdata[$edinc]['eventstatus'] = $row1['event_status'];
                    $edinc++;
                    
                }
            
            
                
                
                
            }
            
    
        }
            $row1 = mysqli_fetch_array($returnval,MYSQLI_BOTH);
        }
    
        
        if ( $eventdata != null)
        usort($eventdata, "cmp");
        $eventdata = json_encode($eventdata);
        $recurdata  = json_encode($recurdata);
        $eventdata = '{"numofevents" : "'.($edinc).'" , "eventdata" : '.$eventdata.' ,"numofrecurevents" : "'.$rincr.'", "recurdata" : '.$recurdata.'}';
        echo $eventdata;
        
        
    }
}
?>

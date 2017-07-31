<?php 
session_start();


$pwd = 'http://'.$_SERVER['SERVER_NAME'].'/p';
include_once "create_db.php";
if ( !isset($_SESSION['uid']) || !isset($_SESSION['user']) || !isset($_SESSION['email']) || !isset($_SESSION['dbstatus'])) {
	echo "Session not set";
	header("Location: http://".$_SERVER['SERVER_NAME']."/p/index.php");
}
if ( $_SESSION['dbstatus'] == 0) {
	createdatabase($_SESSION['uid']);
}

require "php/calendar.php";

$cal = new calendar;

function _curmonth() {
	 $content = '';
	 if(isset($_GET['month']))
		 $content .= sprintf("%02d",$_GET['month']);
	 else
		$content = null;
	if(isset($_GET['year']))	
		$content .= sprintf("%02d",$_GET['year']);
	else
		$content .= null;
    
return $content;
}
 
 
 //$cal->setdate();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/png" sizes="16x16" href="styles/favicon-16x16.png">
    <link href="jquery/jquery-ui.theme.css" rel="stylesheet">
    <link href="jquery/jquery-ui.css" rel="stylesheet">
    <link href="styles/week-view.css" rel="stylesheet">
    <link href="styles/day-view.css" rel="stylesheet">
    <link href="styles/mini_calendar.css" rel="stylesheet">
    <link href="styles/event-colors.css" rel="stylesheet">
        <link href="styles/month-view.css" rel="stylesheet">
<script src='jquery/jquery.js'></script>
<script src='jquery/jquery-ui.js'></script>
    
<title>calender</title>
      
    <script>
    var returndata , ofdatedata;
	var tempeventid,tempname,tempdate,temptime,tempduration,tempstatus,temprectime;
      var eventstatus = ["docadd","patientadd","referadd","note","checkedin","checkedout","docconf","patientconf","requestconf"]; 
	   
        var tempevent;
        function eventstruct(ID,name,date,time,duration,status){
            this.ID = ID;
            this.name = name;
            this.time = time;
            this.duration = duration;
            this.date = date;
			this.status = status;
        }
		function addevent(_eventid,_name,_date,_time,_duration,_class=null,_status) {
          
		
		//  console.log(_name + '   ' + _status);
          var name = _name;
          var day = _date;
          var time = _time;
		  time = _time.slice(0,5);
		  var hours = parseInt(_time.slice(0,2));
		  
		  var min = parseInt(_time.slice(3,5));
		  var flag = 0,flaga = 0;
		  hoursa = parseInt(hours + _duration/60);
		  mina = parseInt(min + _duration%60);
		if ( mina > 59) {
			mina = mina%60;
			hoursa++;
		}
		if ( hoursa > 12) {
			  hoursa = hoursa%12;
			flag =1;  
		  } 
		  if ( hours > 12) {
			  hours = hours%12;
			  flaga = 1;
		  }
		  
		  if ( hoursa < 10) {
			  hoursa = '0' + hoursa;
		  }
		  
		  if ( mina < 10) {
			  mina = '0' + mina; 
		  }
		  if ( hours < 10) {
			  hours = '0' + hours;
		  }
		  
		  if ( min < 10) {
			  min = '0' + min; 
		  }
		  
		  var endtime = '' + hoursa + ':' + mina;
		 if( flag == 1) {
			 endtime = hoursa+':'+mina+'pm';
		 }
		 else { 
			 endtime = '' + hoursa + ':' + mina+'am';
			 }
			 if ( flaga == 1) {
				 time = hours +':'+ min + 'pm';
			 }
			 else {
				 time = hours +':'+ min + 'am';
			 }
			 
			 
        var eventid = _eventid;
        
        var event = '<div id="mw-eventid-'+eventid+'" class="mw-events '+_class+' eventsdis '+eventstatus[_status]+'" style = "z-index : 2"><a href="javascript:void(0)"><div>'+time+'  '+name+'</div></a></div>';
		$(event).appendTo("td#mw-day-"+day);
        
		var aw_event = '<div class="event-aw resizable eventsdis '+eventstatus[_status]+'" id="aw-eventid-'+eventid+'"  style="top : 0px; z-index : 11" ><div class="aw-drag">'+name+'</div><div class="aw-events" id ="some_id"><div><span id="aw-starttime-eventid-'+eventid+'">'+'From :&nbsp;'+time+'&nbsp;<br></span><span id="aw-endtime-eventid-'+eventid+'">To&nbsp;&nbsp;&nbsp;:&nbsp;'+endtime+'</span></div></div></div>'; 
        $(aw_event).appendTo("#aw-day-"+day);
         var ad_event = '<div class="event-ad resizable eventsdis '+eventstatus[_status]+'" id="ad-eventid-'+eventid+'"  style="top : 0px; z-index : 11" ><div class="ad-drag">'+name+'</div><div class="ad-events" id ="some_id"><div><span id="ad-starttime-eventid-'+eventid+'">'+'From :&nbsp;'+time+'&nbsp;<br></span><span id="ad-endtime-eventid-'+eventid+'">To&nbsp;&nbsp;&nbsp;:&nbsp;'+endtime+'</span></div></div></div>'; 
        $(ad_event).appendTo("#ad-day-"+day);
        drag_mw();
        drag_aw();
        drag_ad();
        resize();
        
    
        
      }
		function  events_reload() {
		  $(".eventsdis").remove();
		  $(".eventsdis").remove();
		  loadevents();
	  }
		function viewevents(_date){
		  var id = "#div-day-" + _date;
		  var tid = "#mw-day-" + _date;
		  $(tid + " > div.hideevents").css({"display" : "block"});
		//  $(id + "> table > tbody > tr").css({"border" : "3px solid black"});
		  $(id).css({"overflow" : "visible" , "border" : "0px solid black" , "z-index" : "10"});
		  $(tid + "> div").css({"z-index" : 100});
		  $(tid + " > div.view-more").css({"display" : "none"});
		 $("div.view-less").css({"display" : "block"});
		  var event = '<div class="view-less eventsdis"><a class="view-less" href="javascript:hideevents(\''+_date+'\')">View less</a></div>';
		  $(event).appendTo("td#mw-day-"+_date);
		 
		  
	  }
		function hideevents(_date){
		   var id = "#div-day-" + _date;
		     var tid = "#mw-day-" + _date + " > ";
			 $(id + "> table > tbody > tr").css({"border" : "0px solid black"});
		  $(tid + "div.hideevents").css({"display" : "none"});
		  $(tid + " div").css({"z-index" : 2});
		  $(id).css({"overflow" : "hidden" , "border" : "0px solid black" , "z-index" : "2"});
		  $(tid + "div.view-more").css({"display" : "block"});
		 $(tid + "div.view-less").remove();
		
		 
	  }
		function viewmore(_day) {
		  
		  var event = '<div class="view-more eventsdis"><a class="view-more" href="javascript:viewevents(\''+_day+'\')">View more</a></div>';
		  $(event).appendTo("td#mw-day-"+_day);
		   
	  }
		function event_form(_day = null) {
               
                repeateventform();
               document.getElementById("addevent-name").value = null;
               document.getElementById("addevent-time").value = null;
			   document.getElementById("addevent-date-picker").value = _day; 
        $("#addevent-form").dialog("open");
           }
		function remevent (_eventid) {
            var eventid= _eventid;
			$("#showevent-form").dialog("close");
            $.post("php/remevents.php",{
                    'eventid' : eventid,
                    },
                        function(data , status){
                        if (  status == "success" && data == "success"){
                            alert("event removed");
							
                        }
                        else {
                            alert("Event not deleted\nPlease try again.");
							location.reload();
                        }
             
                    });
        }
		function editevent (_eventid=null,_name=null,_date=null,_time=null,_duration=null,_status=null){
           
		   
					   if (_time != null) {
							var timea = _time.slice(0,5);
							
							
						}
						var statusa = false;
						if ( _status == 6 || _status == 7 || _status == 8)
							statusa= true;
						if ( _status != 6 && _status != 0 && _status != 3){
						
						document.getElementById("updateevent-name").disabled = true;
					}
						
						document.getElementById("updateevent-name").value = _name;
						document.getElementById("updateevent-time").value = timea;
						document.getElementById("updateevent-date-picker").value = _date;
						document.getElementById("updateevent-duration").value = _duration;
						document.getElementById("updateevent-status").value = _status;
						document.getElementById("updateevent-status").checked = statusa;
						$("#updateevent-form").dialog("open");
							
            
            
            
        }
        function updateevent (_eventid,_name=null,_date=null,_time=null,_duration=null,_status=null){
            var eventid = _eventid.slice(8);
              //  alert(_eventid);
            var repeat = _eventid.search("repeat");
            if ( repeat == -1) {
            $.post("php/updateevent.php",{
                    eventid : eventid,
                    name : _name,
                    eventdate : _date,
                    eventtime : _time,
                    eventduration : _duration
                },
                function(data , status){
                    if ( status == 'success') {
                       var errdata = data;
                        errdata = errdata.slice(0,5);
                    
                        if ( errdata == "Error") {
                            show_message("Event not updated.\nPlease try again");
                            events_reload();
                             
                        }
                        
                    }
                    
                });
        }
            else {
                var eventid = _eventid.slice(8);
                var id = eventid.slice(0,repeat - 9);
                var adate = eventid.slice(-10,-1) + eventid.slice(-1);
                var data = JSON.parse(returndata);
                var inc = 0;
                var dataoffset = -1;
              
           
                for(inc = 0; inc < data.numofevents; inc++) {
                    if ( data.eventdata[inc].ID == eventid){
                        dataoffset = inc;
                        break;
                        
                    }
                }
               // show_message(dataoffset);
                $.post("php/updateRecurEvents.php",{
                    eventid : id,
                    name : data.eventdata[dataoffset].name,
                    modifieddate : adate,
                    newdate : _date || data.eventdata[dataoffset].eventdate,
                    newstarttime : _time || data.eventdata[dataoffset].eventtime,
                    newduration : _duration || data.eventdata[dataoffset].eventduration,
                    newstatus : _status || data.eventdata[dataoffset].eventstatus,
                    deleteevent : 0
                },function(data , status) {
                    if ( status == 'success') {
                    
                        if ( data.slice(0,3) == '800') {
                            events_reload();
                        }
                        
                        else {
                            show_message(data);
                        }
                    
                    }
                });
                
            }
        }
        
        function getevents() {
               
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if(xhttp.readyState == 4 && xhttp.status ==  200) {
                     var   load = xhttp.responseText;
                        return load;
                    }
                }
                xhttp.open("POST","php/loadevents.php", true);
                xhttp.send();
                
        }
        
        
        
        
        function resize_events(numofevents,eventdata,_width) {
            
            
              $(".eventsdis").remove();
              $(".eventsdis").remove();
		    
            
            
           
              var ofdate = [{ 'date' :"0000-00-00",'numofevesinthatdate' : 0}];       
            var flag = 1; var i = 0;
            
            			
            var tempdate,inc = 0,incflag = 0;
            var j=0;
			
            var p = 0;
              for ( i =0; i < numofevents ; i++) {
                                
                           
                    
                            
                           // This is loop is for month-view to hide the events
        
                                
								if ( j == 0) {   // if it is the first event in that day then add the event with null class
						addevent(eventdata[i].ID,eventdata[i].name,eventdata[i].eventdate,eventdata[i].eventtime,eventdata[i].eventduration,null,eventdata[i].eventstatus);			
						tempdate = eventdata[i].eventdate;
						j++;
				                            }
                            
                            // now j is 1
			  
			
			   else if ( j  != 0 ) {	// The second event will always go through this loop 
				  if ( tempdate == eventdata[i].eventdate) {     // if second event is on the same date.
					  if ( j > 0) {
						  incflag = 1;                     
						  //alert(i);
                          ofdate[inc] = { 'id' : i,'date' : tempdate , 'numofevesinthatdate' : (j+1)}; 
                    //      console.log(ofdate[inc]);
                    //      console.log(' INC : ' + inc);
                          //ofdate  store the last event in a given date with no.of events in that date
					  }
					
					    if ( j > 2) { // max limit is three j starts from 0. other than three make _class = hideevents and add view more option
							
						  addevent(eventdata[i].ID,eventdata[i].name,eventdata[i].eventdate,eventdata[i].eventtime,eventdata[i].eventduration,'hideevents',eventdata[i].eventstatus);
						  
						  if ( p == 0) {
							
							viewmore(eventdata[i].eventdate);
							p = 1;
						}
						
						  
					  }
					  else { // for j < 2 just add the events.
						  addevent(eventdata[i].ID,eventdata[i].name,eventdata[i].eventdate,eventdata[i].eventtime,eventdata[i].eventduration,null,eventdata[i].eventstatus);
					  }
                                // increment the j as it is on the same date
					  j++;
				  }
				  else {  // if the next event is on another date . Then add the event.
					addevent(eventdata[i].ID,eventdata[i].name,eventdata[i].eventdate,eventdata[i].eventtime,eventdata[i].eventduration,null,eventdata[i].eventstatus);
					  tempdate = eventdata[i].eventdate;
					  j = 1;  // j is 1 since j = 0 is already printed.
					
					 if ( incflag == 1) {
						 
					incflag = 0;
					  inc++;
					 }
					  p = 0;
				  }
			  }
			  
			
			  
		  }
              for ( i =0; i < numofevents ; i++) {
			  positionevent(eventdata[i].ID,eventdata[i].eventtime,eventdata[i].eventduration);
			} 
		      var xb = 0;
              for(xb = 0 ; xb < ofdate.length ; xb++){ // for every xb in ofdate
	       var endid = ofdate[xb].id;             // get the last id
	//		console.log(ofdate[xb]);	
			var i=1,j=1,n=ofdate[xb].numofevesinthatdate; 
			var id = endid - n + 1;              // get the first id
			var tempid;                           // temp to store the last id.
			
			var tempd1,tempt1,tempt2,tempd2,h1,h2,m1,m2,inc = 0,diff;				
					
					
				
				
			
			
			
			while ( i < n){                      // i = 1 to last event
                                                    
				
				tempd1 = parseInt(eventdata[id+i-1].eventduration) + inc; 
				tempt1 = eventdata[id+i-1].eventtime;
				h1 = parseInt(tempt1.slice(0,2));
				m1 = parseInt(tempt1.slice(3,5));
				
				tempd2 = parseInt(eventdata[id+i].eventduration) ; 
				tempt2 = eventdata[id+i].eventtime;
				h2 = parseInt(tempt2.slice(0,2));
				m2 = parseInt(tempt2.slice(3,5));
				
				var height = (h2-h1)*60 + (m2-m1);        // change time to height if event2 minus event1
				
				if (height < tempd1) {    // if height is less than duration of event1 then Then it is overlapping note the j
					
					j = j + 1;
				
					
				}
				else {      // If it is not overlapping 
					
					var k = 0;
				
				
					var width = _width/j;
				for ( k = i-j; k < i; k++) {
					
				positionevent(eventdata[id+k].ID,eventdata[id+k].eventtime,eventdata[id+k].eventduration,(width),((k-i+j)*width),(10+k-i+j));
					}
					j = 1;
					
				}
				i++;
				
				
				
				
			}
			if ( j != 1 ) {
				var k = 0;
				
				var width = _width/j;
				for ( k = n-j; k < n; k++) {
                    
                   
			if ( k != 0)	{
				positionevent(eventdata[id+k].ID,eventdata[id+k].eventtime,eventdata[id+k].eventduration,(width),((k -n + j)*width),(10+k));
			}
			else {
				positionevent(eventdata[id+k].ID,eventdata[id+k].eventtime,eventdata[id+k].eventduration,(width),0,(10+k));  
			}
		
					}
			}
			
}

        }
        
        
        
        
        
		function loadevents() {
            
         var load;
        var ofdate = [{ 'date' :"0000-00-00",'numofevesinthatdate' : 0}];               
           var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
            
            if (xhttp.readyState == 4 && xhttp.status == 200) {
              
                    load = xhttp.responseText;
                    returndata = load;
            // console.log(load);
                    resize_window();
                
                /*        var flag = 1; var i = 0;
                    numofevents = res.events[0].numofevents;
                  
        
					
					var tempdate,inc = 0,incflag = 0;
                    var j=0;
			
                    var p = 0;
                
                // adding the events
                    for ( i =1; i <= numofevents ; i++) {
                                
                           
                    
                            
                           // This is loop is for month-view to hide the events
        
                                
								if ( j == 0) {   // if it is the first event in that day then add the event with null class
						addevent(res.events[i].ID,res.events[i].name,res.events[i].eventdate,res.events[i].eventtime,res.events[i].eventduration,null,res.events[i].eventstatus);			
						tempdate = res.events[i].eventdate;
						j++;
				                            }
                            
                            // now j is 1
			  
			
			   else if ( j  != 0 ) {	// The second event will always go through this loop 
				  if ( tempdate == res.events[i].eventdate) {     // if second event is on the same date.
					  if ( j > 0) {
						  incflag = 1;                     
						  //alert(i);
                          ofdate[inc] = { 'id' : i,'date' : tempdate , 'numofevesinthatdate' : (j+1)}; 
                    //      console.log(ofdate[inc]);
                    //      console.log(' INC : ' + inc);
                          //ofdate  store the last event in a given date with no.of events in that date
					  }
					
					    if ( j > 2) { // max limit is three j starts from 0. other than three make _class = hideevents and add view more option
							
						  addevent(res.events[i].ID,res.events[i].name,res.events[i].eventdate,res.events[i].eventtime,res.events[i].eventduration,'hideevents',res.events[i].eventstatus);
						  
						  if ( p == 0) {
							
							viewmore(res.events[i].eventdate);
							p = 1;
						}
						
						  
					  }
					  else { // for j < 2 just add the events.
						  addevent(res.events[i].ID,res.events[i].name,res.events[i].eventdate,res.events[i].eventtime,res.events[i].eventduration,null,res.events[i].eventstatus);
					  }
                                // increment the j as it is on the same date
					  j++;
				  }
				  else {  // if the next event is on another date . Then add the event.
					addevent(res.events[i].ID,res.events[i].name,res.events[i].eventdate,res.events[i].eventtime,res.events[i].eventduration,null,res.events[i].eventstatus);
					  tempdate = res.events[i].eventdate;
					  j = 1;  // j is 1 since j = 0 is already printed.
					
					 if ( incflag == 1) {
						 
					incflag = 0;
					  inc++;
					 }
					  p = 0;
				  }
			  }
			  
			
			  
		  }
		  
                // position events algo First position all the events normally
                
		  for ( i =1; i <= numofevents ; i++) {
			  positionevent(res.events[i].ID,res.events[i].eventtime,res.events[i].eventduration);
			} 
		 
// SETTING THE OVERLAPPING POSITIONS Algorithm -1 
 -- should comment here
			var xa = 0;
			for(xa = 0 ; xa < ofdate.length ; xa++){
				console.log(ofdate[xa].date);
				var endid = ofdate[xa].id;
				
			var i=0,j=0,n=ofdate[xa].numofevesinthatdate;
			var id = endid - n + 1;
			while ( i <  n){
		
				for ( j = n-1 ; j > i ; j--) {
					
					console.log('endid  = ' + (id+j) + '  sid = ' + (id+i) );
				
				var tempd1,tempt1,tempt2,h1,h2,m1,m2;				
					
					
					tempd1 = parseInt(res.events[id + i].eventduration); 
				tempt1 = res.events[id + i].eventtime;
				tempt2 = res.events[(id + j)].eventtime;
			
				h1 = parseInt(tempt1.slice(0,2));
				m1 = parseInt(tempt1.slice(3,5));
				h2 = parseInt(tempt2.slice(0,2));
				m2 = parseInt(tempt2.slice(3,5));
			var diff = ((h2-h1)*60 + m2-m1) - tempd1;		
					
					
					
					if ( diff < 0) {
					console.log(tempt1 + '  ' + tempt2);
					var numofsets = j - i +1;
					console.log("Setting the things" + ' i=' + (i) + ' j = ' + j + 'num of position settings : ' + numofsets );
					var g = 0;
					width =  140/numofsets;
					for ( g = i ; g <= j ; g++) {
						positionevent(res.events[id+g].ID,res.events[id+g].eventtime,res.events[id+g].eventduration,width,((g-i)*width - 5*(g-i)),(10+g-i));
					}
					i = j;
				
					break;
					}
				}
				i++;
				
			}
			}
			
			// SETTING THE OVERLAPPING events II Algoritm -II
-- end the first comment here 
                
        // Now go for the ovelapping events we have the increment factor i as ID. and ofdate data
var xb = 0;
for(xb = 0 ; xb < ofdate.length ; xb++){ // for every xb in ofdate
	var endid = ofdate[xb].id;             // get the last id
	//		console.log(ofdate[xb]);	
			var i=1,j=1,n=ofdate[xb].numofevesinthatdate; 
			var id = endid - n + 1;              // get the first id
			var tempid;                           // temp to store the last id.
			
			var tempd1,tempt1,tempt2,tempd2,h1,h2,m1,m2,inc = 0,diff;				
					
					
				
				
			
			
			
			while ( i < n){                      // i = 1 to last event
                                                    
				
				tempd1 = parseInt(res.events[id+i-1].eventduration) + inc; 
				tempt1 = res.events[id+i-1].eventtime;
				h1 = parseInt(tempt1.slice(0,2));
				m1 = parseInt(tempt1.slice(3,5));
				
				tempd2 = parseInt(res.events[id+i].eventduration) ; 
				tempt2 = res.events[id+i].eventtime;
				h2 = parseInt(tempt2.slice(0,2));
				m2 = parseInt(tempt2.slice(3,5));
				
				var height = (h2-h1)*60 + (m2-m1);        // change time to height if event2 minus event1
				
				if (height < tempd1) {    // if height is less than duration of event1 then Then it is overlapping note the j
					
					j = j + 1;
				
					
				}
				else {      // If it is not overlapping 
					
					var k = 0;
				
				
					var width = 190/j;
				for ( k = i-j; k < i; k++) {
					
				positionevent(res.events[id+k].ID,res.events[id+k].eventtime,res.events[id+k].eventduration,(width),((k-i+j)*width),(10+k-i+j));
					}
					j = 1;
					
				}
				i++;
				
				
				
				
			}
			if ( j != 1 ) {
				var k = 0;
				
				var width = 190/j;
				for ( k = n-j; k < n; k++) {
                    
                    
				
			if ( k != 0)	{
				positionevent(res.events[id+k].ID,res.events[id+k].eventtime,res.events[id+k].eventduration,(width),((k -n + j)*width),(10+k));
			}
			else {
				positionevent(res.events[id+k].ID,res.events[id+k].eventtime,res.events[id+k].eventduration,(width),0,(10+k));  
			}
		
					}
			}
			
}
			
			
			
			
			
	
			  
		
								
			
            drag_mw();
            drag_aw();
            drag_ad();
            resize();
            resize_window();
                */
        
            }                  
        
  };
                xhttp.open("POST","php/loadevents.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=UTF-8;");
                xhttp.send('date='+'<?php echo _curmonth() ?>');
                }
		function positionevent(_eventid , _eventtime ,_eventduration,x=0,y=0,z=0,_eventday = null) {
            var id = _eventid;
            var ht = _eventtime;
            var height = _eventduration;
            height = (height * 50)/30 ; 
            var minutes = ht.slice(3,5);
            var hours = ht.slice(0,2);
            var top = minutes*50/30 + hours*100;
		//	var width = parseInt$("#ad-eventid-" + id).width();
			
		//	tp = $("#ad-eventid-" + id).css("margin-left") || '00px';
		//	 var tp = parseInt((tp).slice(0,-2));
		//	console.log(tp);
		//	x = x + tp/2;
		//	tm = $("#ad-eventid-" + id).css("margin-right") || '00px';
			// var tm = parseInt((tm).slice(0,-2));
		//	 y =y + tm/2;
	 
	var width =  ($(".aw-set-width").width() - 8) || 190;
	 if ( x  > 0) {
		 width = x;
	 }
	 
	var	z = 2;
		if(z > 0) {
			z = z;
		}
		
			var mlft = y;
			
          //  var aw_week_head = parseInt(localStorage.getItem('aw_week_head'));
           // var ad_week_head = parseInt(localStorage.getItem('ad_week_head'));
            var aw_top  = top;
            
            var ad_top = top;
        
        
            $("#aw-eventid-"+id).css({
                
                    "top" : top+'px',
                    "height" : height+'px',
                    "width" : width+'px',
                    "margin-left" : mlft + 'px',
				    "z-index" : z,
                                     });
        
            $("#ad-eventid-"+id).css({
                
                    "top" : top +'px',
                    "height" : height+'px',
                    "width" :  ((width + 5)*7) + 'px',      
                    "margin-left" : (mlft*7) + 'px',
					"z-index" : z,
                                     
                                     });
            
            
        }
		function showevent(_eventid) {
			$("#showevent-form").dialog("close");
            var data = JSON.parse(returndata);
            var repeat = _eventid.search("repeat");
            dataoffset = -1;
            if ( repeat == -1) {
                
                for ( inc = 0; inc < data.numofevents;inc++ ) {
                    if ( _eventid == data.eventdata[inc].ID ){
                        dataoffset = inc;
                        break;
                    }
                }
        var    name = data.eventdata[dataoffset].name;
        var    date = data.eventdata[dataoffset].eventdate;
        var    time = data.eventdata[dataoffset].eventtime;
        var    duration = data.eventdata[dataoffset].eventduration;
        var    statusb = data.eventdata[dataoffset].eventstatus;
        
				var statusa = "No";
				
				if ( statusb == 6 || statusb == 7){
					statusa = "Yes";
				}
				
				tempevent = {
					id : _eventid,
					name : name,
					date :date,
					time : time,
					duration : duration,
					status : statusb
				};
				
                time = time.slice(0,5);
                durationa = (parseInt(duration/60) )+ " hours " + (duration % 60) +" minutes";
                
                document.getElementById("showevent-name").innerHTML = name;
                document.getElementById("showevent-date").innerHTML = date;
                document.getElementById("showevent-time").innerHTML = time;
                document.getElementById("showevent-duration").innerHTML = durationa;
				document.getElementById("showevent-status").innerHTML = statusa;
               
                $("#showevent-form").dialog("open");	
            }
				
    
             
        }
		function drag_aw() {
                var inittop = 0;
                var initleft = 0;
				var id;
            var aw_width = $(".aw-set-width").width();
				//var top = document.getElementById("aw-week-head").offsetHeight;
				var top = 0;
			 $("div.event-aw").draggable({
                 
                grid : [0.1,1.5],
                 opacity : 0.75,
                 scroll : true,
                 handle : "div.aw-drag",
                 start : function (event,ui){
                     inittop = ui.position.top; 
                    initleft =  ui.position.left;
					id = ($(this).attr('id').slice(11));
					$(this).css({'width' : aw_width});
					$(this).css({'margin-left' : '0px'});
                 },
                 drag : function (event,ui) {
                 
				   $(this).css({'z-index' : 100});
				 var timeh = (ui.position.top - top)/25;
				   var hoursh = parseInt(timeh/4);
                     var minutesh = parseInt((timeh%4)*15);
					 
                     if (minutesh >= 60) {
                         hoursh++;
                         minutesh = minutesh%60;
						 minutesh = parseInt(minutesh);
							
                       }
					  if(minutesh < 10){
                             minutesh = '0'+ minutesh;
                         }
						 if(hoursh < 10) {
							 hoursh = '0' + hoursh;
						 }
					 ftime = hoursh + ':' + minutesh;
				      document.getElementById("aw-starttime-eventid-"+id).innerHTML = 'From :&nbsp;'+ftime+'&nbsp;<br>';
				
                 },
                 stop : function (event,ui){
			                   
				   var eventid = $(this).attr("id");
                     eventid = eventid.slice(3);
                     var date = $(this).parent().attr("id");
                     date = date.slice(7);
                      
                     var year = date.slice(0,4);
                     var month = date.slice(5,7);
                     var day = date.slice(8,10);
                     var d = new Date();
                     d.setFullYear(year,month-1,day);
                     var daysTBMVD = (ui.position.left - initleft)/aw_width;
                     
                 if ( ui.position.top > top ) {
                     var timech = (ui.position.top - top)/25;
                     var hoursch = parseInt(timech/4);
                     var minutesch = parseInt(Math.round((timech%4)))*15;
                     if (minutesch >= 60) {
                         hoursch++;
                         minutesch = minutesch%60;
                         if(minutesch < 10){
                             minutesch = '0'+minutesch;
                         }
						 if(hoursch < 10) {
							 hoursch = '0' + hoursch;
						 }
                     }
                     var temptime = hoursch + ':' + minutesch + ':30';
                 }
                     else {
                         temptime = '';
                     }
                     d.setDate(d.getDate()  + daysTBMVD);
                     var temp = (parseInt(d.getMonth())+1);
                     if(temp < 10)
                        var newdate = d.getFullYear()+'-0'+(parseInt(d.getMonth())+1)+'-'+d.getDate();
                     else
                         var newdate = d.getFullYear()+'-'+(parseInt(d.getMonth())+1)+'-'+d.getDate();
                        
                     updateevent(eventid,'',newdate,temptime);
                     window.location="#week-view";
					
				  //   events_reload();
				  events_reload();
                     
                 }
             });
        }
        function drag_ad() {
               var inittop = 0;
                var initleft = 0;
			//	var top = document.getElementById("ad-week-head").offsetHeight;
			var top=0;
				var id;
             $("div.event-ad").draggable({
                 handle : 'div.ad-drag',
                 opacity : 0.75,
                 scroll : true,
				 axis : 'y',
                grid : [0,1],
                 start : function (event,ui){
					 id = ($(this).attr('id').slice(11));
                     inittop = ui.position.top; 
                    initleft =  ui.position.left;
                 },
                 drag : function (event,ui) {
                  $(this).css({'z-index' : 100});
					var timeh = (ui.position.top - top)/25;
						var hoursh = parseInt(timeh/4);
                     var minutesh = parseInt((timeh%4)*15);
					 
                     if (minutesh >= 60) {
                         hoursh++;
                         minutesh = minutesh%60;
						 minutesh = parseInt(minutesh);
							
                       }
					  if(minutesh < 10){
                             minutesh = '0'+ minutesh;
                         }
						 if(hoursh < 10) {
							 hoursh = '0' + hoursh;
						 }
					 ftime = hoursh + ':' + minutesh;
				      document.getElementById("ad-starttime-eventid-"+id).innerHTML = 'From :&nbsp;'+ftime+'&nbsp;<br>';				  
                 },
                 stop : function (event,ui){
                     var eventid = $(this).attr("id");
                     eventid = eventid.slice(3);
                     var date = $(this).parent().attr("id");
                     date = date.slice(7);
                     
                     
                     var year = date.slice(0,4);
                     var month = date.slice(5,7);
                     var day = date.slice(8,10);
                     var d = new Date();
                     d.setFullYear(year,month-1,day);
                     var daysTBMVD = (ui.position.left - initleft)/150;
                 if ( ui.position.top > top ) {
                     var timech = (ui.position.top - top)/25;
                     var hoursch = parseInt(timech/4);
                     var minutesch = parseInt(Math.round((timech%4)))*15;
                     if (minutesch >= 60) {
                         hoursch++;
                         minutesch = minutesch%60;
                         if(minutesch < 10){
                             minutesch = '0'+minutesch;
                         }
                     }
                     var temptime = hoursch + ':' + minutesch + ':30';
                 }
                     else {
                         temptime = '';
                     }
                     
                        
                     updateevent(eventid,'','',temptime);
                     window.location="#day-view";
                     events_reload();
                     
                 }
             });
            
            
        }
        function resize() {
			//var top = document.getElementById("ad-week-head").offsetHeight;
			var top=0;
				var id;
            $("div.resizable").resizable({
				
                handles : "s",
                grid : [0,1],
				start : function (event,ui) {
					id = ($(this).attr('id').slice(11));
					$view = $(this).attr('id').slice(0,2);
					
				},
				resize : function(event , ui){
					$(this).css({'z-index' : 100});
					
						var timeh = (ui.position.top + ui.size.height - top)/25;
						var hoursh = parseInt(timeh/4);
                     var minutesh = parseInt((timeh%4)*15);
					 
                     if (minutesh >= 60) {
                         hoursh++;
                         minutesh = minutesh%60;
						 minutesh = parseInt(minutesh);
							
                       }
					  if(minutesh < 10){
                             minutesh = '0'+ minutesh;
                         }
						 if(hoursh < 10) {
							 hoursh = '0' + hoursh;
						 }
					 ftime = hoursh + ':' + minutesh;
					 if ( $view == 'ad')
				      document.getElementById("ad-endtime-eventid-"+id).innerHTML = 'To&nbsp;&nbsp;&nbsp; :&nbsp;'+ftime+'&nbsp;<br>';
				      else 
					  document.getElementById("aw-endtime-eventid-"+id).innerHTML = 'To&nbsp;&nbsp;&nbsp; :&nbsp;'+ftime+'&nbsp;<br>';
					  
				},
                stop : function(event,ui) {
                 var eventid = $(this).attr("id");
                 var whichview = eventid.slice(0,2);
                     eventid = eventid.slice(3);
                     var date = $(this).parent().attr("id");
                     date = date.slice(7);
                    
                    var duration =  parseInt(Math.round(ui.size.height/12.5)) * 7.5;
                    if ( duration < 5) {
                        duration = 15;
                    }
                    if ( ui.size.height > 2400 - ui.position.top ) {
                        duration = null;
                    }
                    updateevent(eventid,'','','',duration);
                    if ( whichview == 'aw') {
                        window.location = "#week-view";
                        
                    }
                    else
                        window.location = "#day-view";
                   events_reload();
                }
            });
        }
        function drag_mw() {
             $(".mw-events").draggable({
               containment : "#mw-container",
               revert : true,
                 scroll : false,
				 drag : function (event,ui){
					 var id = ($(this).parent().attr("id"));
					 
					 did = '#div-'+id.slice(3);
					 $("#"+$(this).attr('id')).css({'z-index' : 100});
					 $(did).css({'overflow' : 'visible'});
					
				 }
               });
        }
		function drop_mw() {
            $(".mw-droppable").droppable({
                accept : "div.mw-events",
                drop : function (event , ui) {
                  var eventid = (ui.draggable.attr("id"));
                    var content = ui.draggable.html();
                    var destiny = $(this).find("table").attr("id");
                    
                    
                   var Eventid = eventid.slice(3);
                   var  Destiny = destiny.slice(4);
                    updateevent(Eventid,'',Destiny);
               
                   $("div#"+eventid).remove();
                    
                   // var event = '<div id="mw-eventid-'+eventid+'" class="mw-events"><a href="javascript:void(0)"><div>'+content+'</div></a></div>';
                        
                   // $(event).appendTo("td#mw-"+destiny);
                    window.location="#month-view";
                //    events_reload();
                    
                   
                   
                 
                }
            });
        }
		function setstyle() {
            var d = new  Date();
            var height = d.getHours()*60 + d.getMinutes();
            height = height * 100/60;
    //    document.getElementById("aw-vertical-lines").scrollTop = height-400;
	//	document.getElementById("ad-vertical-lines").scrollTop = height-400;
      //  var aw_top = document.getElementById("aw-week-head").offsetHeight;
      //  var ad_top = document.getElementById("ad-week-head").offsetHeight;    
        $("#aw-tablewidth").css({'top' : top, 'height' : 2400});
       // $("div.aw-events").css({'top' : aw_top});
      //  $("div.aw-time-grid").css({'top' : aw_top});
       //     $("div.ad-events").css({'top' : ad_top});
        //   $("div.ad-time-grid").css({'top' : ad_top});
          // localStorage.setItem('aw_week_head', aw_top);
          // localStorage.setItem('ad_week_head',ad_top);
           $("#ad-tablewidth").css({'top' : top, 'height' : 2400});
        
            
        }
        function submit_form(path,params,method) {
                method = method || 'POST';
                var form = document.createElement("form");
                form.setAttribute("method",method);
                form.setAttribute("action",path);

            for (var key in params) {
                if(params.hasOwnProperty(key)) {
                    var hidden = document.createElement("input");
                    hidden.setAttribute("type","hidden");
                    hidden.setAttribute("name",key);
                    hidden.setAttribute("value",params[key]);
                    
                    
                    form.appendChild(hidden);
                
            }
            }
                    document.body.appendChild(form);
                    form.submit();
            }
        function next() {
     var current_view = null;
         var str = (window.location.href);
       
        if( str.search("month-view") > 0 ) {
            current_view = 'tab-month';
        }
        if( str.search("week-view") > 0 ) {
            current_view ='tab-week';
        }
        if( str.search("day-view") > 0 ) {
            current_view =  $(".agenda-view").find("li.current > a").attr("id") || 'tab-day';
        }
		current_view =  $(".agenda-view").find("li.current > a").attr("id") || current_view; 
        if(current_view == 'tab-day') {
            var date1 = '<?php echo $cal->_nextday(); ?>';
            var day = date1.slice(0,2);
            var month = date1.slice(2,4);
            var year = date1.slice(4);
            submit_form('home.php#day-view',{'day' : day , 'month' : month , 'year' : year},'GET');   
    }
        else if(current_view == 'tab-week') {
               var date1 = '<?php echo $cal->_nextweek(); ?>';
               var day = date1.slice(0,2);
               var month = date1.slice(2,4);
               var year = date1.slice(4);
               submit_form('home.php#week-view',{'day' : day , 'month' : month , 'year' : year},'GET');
        }
    
        else {
                var date1 = '<?php echo $cal->_nextmonth(); ?>';
                var day = date1.slice(0,2);
                var month = date1.slice(2,4);
                var year = date1.slice(4);
                submit_form('home.php#month-view',{'day' : day , 'month' : month , 'year' : year},'GET');            }  
}
        function prev() {
         var current_view = null;
         var str = (window.location.href);
       
       if( str.search("month-view") > 0 ) {
            current_view = 'tab-month';
        }
        if( str.search("week-view") > 0 ) {
            current_view ='tab-week';
        }
        if( str.search("day-view") > 0 ) {
            current_view =  $(".agenda-view").find("li.current > a").attr("id") || 'tab-day';
        }
		current_view =  $(".agenda-view").find("li.current > a").attr("id") || current_view;        
        if(current_view == 'tab-week') {
            var date1 = '<?php echo $cal->_prevweek(); ?>';
            var day = date1.slice(0,2);
            var month = date1.slice(2,4);
            var year = date1.slice(4);
            
            submit_form('home.php#week-view',{'day' : day , 'month' : month , 'year' : year},'GET');   
    }
        else if(current_view == 'tab-day') {
               var date1 = '<?php echo $cal->_prevday(); ?>';
               var day = date1.slice(0,2);
               var month = date1.slice(2,4);
               var year = date1.slice(4);
               submit_form('home.php#day-view',{'day' : day , 'month' : month , 'year' : year},'GET');
        }
    
        else {
                var date1 = '<?php echo $cal->_prevmonth(); ?>';
                var day = date1.slice(0,2);
                var month = date1.slice(2,4);
                var year = date1.slice(4);
                submit_form('home.php#month-view',{'day' : day , 'month' : month , 'year' : year},'GET');            }
    }
        function today() {
            
            var current_view = null;
         var str = (window.location.href);
       
        if( str.search("month-view") > 0 ) {
            current_view = $(".agenda-view").find("li.current > a").attr("id") || 'tab-month';
        }
        if( str.search("week-view") > 0 ) {
            current_view = $(".agenda-view").find("li.current > a").attr("id")  || 'tab-week';
        }
        if( str.search("day-view") > 0 ) {
            current_view =  $(".agenda-view").find("li.current > a").attr("id") || 'tab-day';
        }
         
            if(current_view == 'tab-week') {
            var d=new Date;
            submit_form("home.php#week-view",{'day' :d.getDate() ,'month' : d.getMonth() + 1 ,'year' : d.getFullYear()},'GET');
            }
            else if(current_view == 'tab-day') {
            var d=new Date;
            submit_form("home.php#day-view",{'day' :d.getDate() ,'month' : d.getMonth() + 1 ,'year' : d.getFullYear()},'GET');
            }
            else {
                 var d=new Date;
            submit_form("home.php#month-view",{'day' :d.getDate() ,'month' : d.getMonth() + 1 ,'year' : d.getFullYear()},'GET');
            }
        }
		
	
        
      function  mininext(){
		  	var date =  document.getElementById("ndate").innerHTML;
					var month = date.slice(0,2);
					var year = date.slice(2);
					var xhttp = new XMLHttpRequest();
					xhttp.onreadystatechange = function() {
								if (xhttp.readyState == 4 && xhttp.status == 200) {
												
												document.getElementById("mini_cal").innerHTML = xhttp.responseText;
									}
					};
					xhttp.open("GET","php/mini_calendar.php?month="+month+"&year="+year,true);
					xhttp.send();
					
	  }
	   function  miniprev(){
		  	var date =  document.getElementById("pdate").innerHTML;
					var month = date.slice(0,2);
					var year = date.slice(2);
					var xhttp = new XMLHttpRequest();
					xhttp.onreadystatechange = function() {
								if (xhttp.readyState == 4 && xhttp.status == 200) {
												
												document.getElementById("mini_cal").innerHTML = xhttp.responseText;
									}
					};
					xhttp.open("GET","php/mini_calendar.php?month="+month+"&year="+year,true);
					xhttp.send();
					
	  }
        
    //    document.addEventListener("resize",resize);
   
        function resize_window() {
            
                 var widthofmini =  ((window.innerWidth) * 15)/100;
                //  widthofmini = 300;
//            alert(widthofmini);
                var defaultwidth = 1920;
                var widthofagenda = ((window.innerWidth - widthofmini - 100 - ((defaultwidth - window.innerWidth)/10) ));
                document.getElementById("agenda-view").style.width = widthofagenda;
               
                document.getElementById("mini_cal").style.width = widthofmini;
                document.getElementById("mini_cal").style.height = widthofmini;
                document.getElementById("side_panel").style.width = widthofmini;
                ($("li.headingdate").css("left",(widthofagenda-50)/2));
				document.getElementsByClassName("headingdate")[0].style.width = widthofagenda/2;
                var mw_events = (widthofagenda)/7;
                $("table.mw-event-table").width(mw_events);
                $("div.mw-events").width(mw_events - 5);
                $("tr.mw-event-container").width(mw_events);
                
            
            
                try {
                    $("table.nav-month").width(widthofmini);
                    $("table.nav-month").height(300);
                     console.log('set width = ' + widthofmini + ' setted width = ' +  $("#side_panel").width());  
                   
                }
                catch(err) {
                    console.log(err.message);
                        
                }
                
            // setting the week width settings
                var aw_events = (widthofagenda  - 75)/7;
                
                $(".aw-axis").width(75);
                $(".ad-axis").width(75);
                $(".aw-set-width").width(aw_events - 4.5);
                $(".ad-set-width").width(widthofagenda - 75);
                 //   console.log(eventdata);
                try{
                    
                    var data = JSON.parse(returndata);
                //    console.log( returndata);
                    resize_events(data.numofevents,data.eventdata,aw_events-8);
    
                    
                }
                
                
            
            
                catch(err){
                    console.log(err.message);
                }
            
               
         		
        }
        
        // Specially for addevent form
        function repeateventform() {
    

                    var val = document.getElementById('addevent-repeat-status');
                   // console.log(val.checked);
                    if ( val.checked) {
                        document.getElementsByClassName('repeat_data')[0].style.display = "block";
                        repeat_data_type_1();
                    }
                    else {
                     document.getElementsByClassName('repeat_data')[0].style.display = "none";   
                    }
                }
        function repeat_data_type_1() {
    
                    var type = document.getElementById("repeat-data-type").value;
                    var arr = [' ','days','weeks','months','years'];
                    document.getElementById("repeat-data-type-1").innerHTML = arr[type];
                  //  console.log(arr[type]);

                    if ( type == 2){
                       var temp = document.getElementById("repeat-data-week");
                        temp.style.display = 'block';
                    }

                    else {
                                var temp = document.getElementById("repeat-data-week");
                                temp.style.display = 'none';

                        }
                }
        function show_message(msg) {
            document.getElementById("messageboard_content").innerHTML = msg;
            $("#messageboard").dialog("open");
        }
                    
    </script>
    <script>
$(document).ready(function() {
				
				
                  window.addEventListener("resize",resize_window);
                  
      //          console.log( 'Height : ' + window.innerHeight + ' Width : ' + window.innerWidth);
    
              
				
				//Getting the mini_calendar
				var sdate = '<?php echo _curmonth(); ?>';
				
				var smonth = sdate.slice(0,2);;
				var syear = sdate.slice(2);
					var xhttp = new XMLHttpRequest();
					xhttp.onreadystatechange = function() {
								if (xhttp.readyState == 4 && xhttp.status == 200) {
												document.getElementById("mini_cal").innerHTML = xhttp.responseText;
									}
					};
					xhttp.open("GET","php/mini_calendar.php?month="+smonth+"&year="+syear,true);
					xhttp.send();
				//end of getting the mini calendar
                setstyle();
             //   resize_window();
                loadevents();
                drag_ad();
                drag_aw();
                drag_mw();
                drop_mw();
                resize();
                
				$("#minidata").css({"display" : "none"});
				 
               $("#agenda-view").tabs({
                    heightStyle : "auto",
                });
               $( "#addevent-date-picker" ).datepicker({
                        appendText:"(yy-mm-dd)",
                        dateFormat:"yy-mm-dd",
                });
				 $( "#updateevent-date-picker" ).datepicker({
                        appendText:"(yy-mm-dd)",
                        dateFormat:"yy-mm-dd",
                });
               $( "#addevent-form" ).dialog({
                    autoOpen: false, 
                    show : "highlight",
                    modal : true,
                    title : "Add an event",
					width : 500
					
                  });
               $( "#updateevent-form" ).dialog({
                    autoOpen: false, 
                    width : 500,
                    modal : true,
                    title : "Edit event",
					
                  });
               $( "#showevent-form" ).dialog({
                    autoOpen: false, 
                    show : "highlight",
                    modal : true,
                    title : "Event",
					
                    });
				$("#schedskip-form").dialog({
					autoOpen : false,
					modal : true,
					title : "Scheduling Error",
				});
                $("#messageboard").dialog({
                    autoOpen : false,
                    modal : true,
                    title : "Message",
                });
               $("button.bform").click(function() {
                 
                    var name = document.getElementById("addevent-name").value;
                    var day = document.getElementById("addevent-date-picker").value;
                    var time = document.getElementById("addevent-time").value;
					
                    
                    var duration = document.getElementById("addevent-duration").value;
					var estatus = document.getElementById("addevent-status").checked? 6 : 0 ;
					var date = new Date;
                    var recordtime = date.getTime();
					
                    
                    var isrecurtrue = document.getElementById("addevent-repeat-status").checked;
                    var recurtype,recurlength,recurdata;
                   recurdata = '';
                   if ( isrecurtrue) {
                        recurtype = document.getElementById("repeat-data-type").value;
                        recurlength = document.getElementById("repeat-data-length").value;
                       if ( recurtype == 2){
                            
                           for ( i =0;i < 7 ; i++){
                               if ( document.getElementById("repeat-data-week-" + i ).checked ){
                                   recurdata += i.toString();
                               }
                           }
                       }
                   }
                   else {
                       recurtype = -1;
                       recurlength = -1;
                   }
                   
                   
                   console.log(recurtype + '  =  ' + recurlength  + '  =  ' + recurdata);
                 $("#addevent-form").dialog("close");
                $.post("php/addevents.php",{
                    name : name,
                    date : day,
                    time : time,
                    recordtime : recordtime,
                    eventduration : duration,
					eventstatus : estatus,
					schedskip : 0,
                    recurtype : recurtype,
                    recurlength : recurlength,
                    recurdata : recurdata
                    
                },
                function(data , status){
                    if ( status == 'success') {
                        var errdata = data;
						 errdata = errdata.slice(0,3);
                        console.log(data);
                        if ( errdata == "700") {
                            $("#schedskip-form").dialog("open");
							tempeventid = null;
							tempname = name;
							tempdate = day;
							temptime= time;
							temprectime = recordtime;
							tempduration = duration;
							tempstatus = estatus;
							//events_reload();
						}
                //        addevent(eventid,name,day,time);
                       events_reload();
                    }
                    
                });
				
                
                });
			   $("button.editform").click(function() {
					$("#showevent-form").dialog("close");
					editevent(tempevent.id,tempevent.name,tempevent.date,tempevent.time,tempevent.duration,tempevent.status);
                 });
			   $("button.remform").click(function() {
					$("#showevent-form").dialog("close");
					remevent(tempevent.id);
                    events_reload();
                    
                });
                $("button.messageboard_button").click(function() {
                    $("#messageboard").dialog("close");
                });
			  // $("#side_panel").datepicker();
               $( "div.aw-events-div" ).on( "click",".event-aw",function() {
				   var eventid =  $( this ).attr("id");
				   eventid = eventid.slice(11);
                   showevent(eventid);
        
               });
			   $( "div.ad-events-div" ).on( "click",".event-ad",function() {
                    var eventid =  $( this ).attr("id");
                    eventid = eventid.slice(11);
                    showevent(eventid);
        
               });
			   $( "td.mw-events-container" ).on( "click","div.mw-events",function() {
					
					
                   var eventid =  $( this ).attr("id");
                   eventid = eventid.slice(11);
                   showevent(eventid);
               });
			   $(".agenda-view > li > a.view").click(function(event) {
                   event.preventDefault();
                   $(this).parent().siblings().removeClass("current");
                   $(this).parent().addClass("current");
                   var current_view = ($(".agenda-view").find("li.current > a").attr("id"));
            
                });
               $("button.eform").on("click",function() {
					var _eventid = tempevent.id;
					
                    var name = document.getElementById("updateevent-name").value;
                    var day = document.getElementById("updateevent-date-picker").value;
                    var time = document.getElementById("updateevent-time").value;
                    var duration = document.getElementById("updateevent-duration").value;
					var estatus = document.getElementById("updateevent-status").checked;
					var vstatus = document.getElementById("updateevent-status").value;
					
					
					if ( vstatus == 6 || vstatus == 0 ) {
					if ( estatus == true)
						estatus = 6;
					else 
						estatus = 0;
					}
					else if (vstatus == 7 || vstatus == 1){
					if ( estatus == true)
						estatus = 7;
					else 
						estatus = 1;
					}
					else if ( vstatus == 8 || vstatus == 2){
						
					if ( estatus == true)
						estatus = 8;
					else 
						estatus = 2;
					}
					else 
						estatus = vstatus;
					
					

               
                
					 
                 
                 
                 $("#updateevent-form").dialog("close");
            if (  1 ) { 
			
                $.post("php/updateevent2.php",{
                    eventid : _eventid,
                    name : name,
                    eventdate : day,
                    eventtime : time,
                    eventduration : duration,
					eventstatus : estatus,
					schedskip : 0
                },
                function(data , status){
                    if ( status == 'success') {
                       var errdata = data;
					   console.log(data);
                        errdata = errdata.slice(0,3);
                        
                        if ( errdata == "700") {
                            $("#schedskip-form").dialog("open");
							tempeventid = _eventid;
							tempname = name;
							tempdate = day;
							temptime= time;
							tempduration = duration;
							tempstatus = estatus;
							
							events_reload();
                        }
                        else{
							 events_reload();
                        }
                    }
                    
                });
            }
                
                });
                
                
            $("button.schedskiptrue").on("click",function() {
				$("#schedskip-form").dialog("close");
				if ( tempeventid != null) {
				$.post("php/updateevent2.php",{
                    eventid : tempeventid,
                    name : tempname,
                    eventdate : tempdate,
                    eventtime : temptime,
                    eventduration : tempduration,
					eventstatus : tempstatus,
					schedskip : 1
                },
                function(data , status){
                    if ( status == 'success') {
                       var errdata = data;
					   console.log(data);
                        errdata = errdata.slice(0,3);
                        
                        if ( errdata == "700") {
                            $("#schedskip-form").dialog("open");
							events_reload();
                        }
                        else{
							 events_reload();
                        }
                    }
                    
                })
				}
				else {
					
					
					$.post("php/addevents.php",{
                  
                    name : tempname,
                    date : tempdate,
                    time : temptime,
					recordtime : temprectime,
                    eventduration : tempduration,
					eventstatus : tempstatus,
					schedskip : 1
                },
                function(data , status){
                    if ( status == 'success') {
                       var errdata = data;
					   console.log(data);
                        errdata = errdata.slice(0,3);
                        
                        if ( errdata == "700") {
                            $("#schedskip-form").dialog("open");
							events_reload();
                        }
                        else{
							 events_reload();
                        }
                    }
                    
                })
				}
			});
            
			$("button.schedskipfalse").on("click",function() {
				$("#schedskip-form").dialog("close");
				tempeventid = null;
				tempname = null;
				tempdate = null;
				temptime = null;
				tempduration = null;
				tempstatus = null;
			});
    
             resize_window();
			
		});
	
    </script>
    
    <style>
	
		.workinghour{
		//	border  : 1px solid grey;
		//	border-style : none solid none solid;
			background-color : white;
		}
		.nonworkinghour{
			background-color : #cccccc;
		}
		
		
        #side_panel{
			float : left;
			overflow : visible;
		}
		#agenda-view {
			float:right;
            
			
		}
		
       
        td {
            vertical-align : text-top;
        }
        ul#menu {
            padding: 0;
        }
     
        ul#menu li {
            display: inline;
            padding-top : 8px;
            position : static;

        }

        ul#menu li a {
            background: #d7ebf9 url("jquery/images/ui-bg_glass_80_d7ebf9_1x400.png") 50% 50% repeat-x;
            color: #2779aa;
			border : 1px solid #aed0ea;
			border-style : solid solid none solid;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px 4px 0 0;
        }


        ul#menu li a:hover {
			border : 1px solid #2694e8;
            background: #3baae3 url("jquery/images/ui-bg_glass_50_3baae3_1x400.png") 50% 50% repeat-x;
            color : white;
           
            
        }
        @keyframes padit {
            from {background-color:#1d80de;padding : 10px 20px;}
            to {background-color: #0f4171; padding : 20px 25px;}
        }
        ul#menu li.headingdate {
            
            position: absolute;
            top : 2.55px;
            left : 525px;
            
            
        }
        ul#menu li.headingdate a.heading {
            border-radius : 5px;
            font-size: 120%;
        }
        ul#menu li.a a.heading:hover {
            
        }
   
        
		div.header {
			position : relative;
			width : 100%;
			display : block;
			
		}
		div.eventtoools {
				
				display : block;
				overflow : visible;
				
		}
		div.eventtools > a {
			
			overflow : visible;
			display : block;
			color : white;
			border : 1px solid white;
			font-size : 120%;
			background-color : #1d80de;
			height : 30px;
			width : 90%;
			text-align : center;
			vertical-align : bottom;
			padding-top : 5px;
			border-radius : 5px;
			text-decoration : none;
		
	}
		div.eventtools > a:hover {
			background-color : #0f4171;
			animation-name: tadit;
            animation-duration: 0.3s; 
			width: 100%;
			
		}
		
		 @keyframes tadit {
            from {background-color:#1d80de;width : 90%;}
            to {background-color: #0f4171; width : 100%; }
        }
		
		th.navi {
			cursor : pointer;
		}
		
		#minidata {
			display : none;
		}
		a.headerlink1 {
			margin : 2px;
			margin-top : -50px;
			padding : 5px;
			float : right;
			height : 40px;
			width : 120px;
			font-size : 20px;
			font-family : Arial, Helvetica, sans-serif;
			background-color : black;
			color : white;
			text-decoration : none;
			align-content : right;
			border-radius : 10px 10px 0px 0px;
			align-content : left;
			text-align : center;
			vertical-align : middle;
			
		}
		a.headerlink1 > span {
			align-content : center;
			vertical-align : middle;
			padding-top : 15px;
		}
		a.headerlink1:hover {
			background-color : orange;
		}
    </style>
    
    </head>
    <body>
  
       
         
     <div style = "margin-top : 30px; border : 1px solid black; border-style : none none solid none">
         <h2 style = "font-family : 'Palatino Linotype', 'Book Antiqua', Palatino, serif; padding : 5px 2px 2px 3px; margin-left : 10px;font-size : 30px;">Welcome <?php echo $_SESSION['user']?></h2>
       
	   <a class= "headerlink1" href="logout.php"><img src="pics/logout.png" height="30px" width="30px"><span>Logout</span></a>
	   
	   
	   
	   <a class="headerlink1" href="settings.php"><img src="pics/settings.png" height="30px" width="30px"><span>Settings</span></a>
	   
	   <a class="headerlink1"href="home.php"><img src="pics/home.png" height="30px" width="30px"><span>Home</span></a>
	   
	   
	   </div>  
        <div width="100%">
            <div id="side_panel">
			<div id="mini_cal">
			
			</div>
			<div class="eventtools">
			<span>Tools</span>
			<a href="viewothers.php">Request Appointment</a>
			<a href="javascript:event_form()">Add Event</a>
			<a href="javascript:void(0)">Remove Event</a>
			</div>
			</div>
            <div id="agenda-view">
			<div class="header">
         <ul class="agenda-view">
            <li ><a  class="view" id="tab-month" href = "#month-view">Month</a></li>
             <li><a  class="view" href = "#week-view" id="tab-week">Week</a></li>
            <li><a  class="view"  href = "#day-view" id = "tab-day">Day</a></li>
             
<ul id="menu" style="float : left">
    <li class="headingdate"><a class="heading" href="javascript:void(0)"><?php echo $cal->_heading() ?></a></li>   
    <li><a href="javascript:next()">&#10095;</a></li>
    <li><a href="javascript:today()">Today</a></li>
    <li><a href="javascript:prev()">&#10094;</a></li>
    
    </div>
    
   
        
        </ul>
         </ul>
        
        
        <div id="month-view">
        <?php echo $cal->month_view(); ?>
        </div>
        <div id="week-view">
        <?php echo $cal->week_view(); ?>
        </div>
        <div id="day-view">
        <?php echo $cal->day_view(); ?>
        </div>
                
            </div>
   </div>     
        
       <div id="addevent-form" style = "border : 1px solid grey; width : auto" align = "center">
        <h2>Please enter the details</h2>
            
        <div>
         <label>Name&nbsp;:&nbsp;</label>   
            <input type = "text" id="addevent-name" placeholder="Name" name="name">
        </div>
        <br>
        <div>
            <label>Date&nbsp;:&nbsp;</label>
            <input type = "text" id="addevent-date-picker" placeholder="day" name="date">
        </div>
        <br>
        <div>
            <label>Time&nbsp;:&nbsp;</label>
            <input type = "time" id="addevent-time" name="time" placeholder="time">
        </div>
        <br>
        <div>
            <label>confirmed </label>
            <input type="checkbox" id="addevent-status" value="6">
        </div>
        <br>
        <div>
            <label>Duration&nbsp;:</label>
                 <select name="eventduration" id="addevent-duration">
                    <option value="15">15 mins</option>
                    <option value="30">30 mins</option>
                    <option value="60">1 hour</option>
                    <option value="120">2 hours</option>
                  </select>
        </div>
        <br>
        
        <div class="repeat_status">
            <label>repeat event : </label>
            <input type="checkbox" id = "addevent-repeat-status" onchange="repeateventform()">
        </div>
        <div class="repeat_data">
        <label>Repeat</label>
        <select id = "repeat-data-type" onchange = "repeat_data_type_1()">
        <option value="1">Daily</option>
        <option value="2">Weekly</option>
        <opiton value="3">Monthly</opiton>
        <option value="4">Yearly</option>
        </select>
        <label>&nbsp;&nbsp;every&nbsp;&nbsp;</label>
        <input type="number" id="repeat-data-length" size = "2">
        <span id = "repeat-data-type-1"></span>
           
        <div id = "repeat-data-week">
        <span>On : </span>
        <label>Sun </label><input type="checkbox" id = "repeat-data-week-0">    
        <label>Mon </label><input type="checkbox" id = "repeat-data-week-1">    
        <label>Tue </label><input type="checkbox" id = "repeat-data-week-2">    
        <label>Wed </label><input type="checkbox" id = "repeat-data-week-3">    
        <label>Thu </label><input type="checkbox" id = "repeat-data-week-4">    
        <label>Fri </label><input type="checkbox" id = "repeat-data-week-5">    
        <label>Sat </label><input type="checkbox" id = "repeat-data-week-6">    
        </div>   
        
        </div>
        
        <div>
            <button class="bform">Submit</button>
        </div>        
            
            
        </div>
    
        
        
        
         <div id="updateevent-form" border = "1">
            
        
             <table class="form" border="0" width="auto" height="auto" style = "table-layout : fixed ">
                <caption><span>Please enter the details</span></caption>
                <tr>
            <td>Name&nbsp;:&nbsp;</td>
            <td colspan="3"><input type = "text" id="updateevent-name" placeholder="Name" name="name"></td>
                </tr>
                <tr>          
                    <td>Date&nbsp;:&nbsp;</td>
                    <td colspan="3"><input type = "text" id="updateevent-date-picker" placeholder="day" name="date"></td>
                </tr>
                <tr>
                    <td>Time&nbsp;:&nbsp;</td>
                    <td><input type = "time" min="0" max="12" id="updateevent-time" name="time" placeholder="Time"></td>
					</tr>
               
				 <tr>
				 <td>confirmed </td>
				 <td><input type="checkbox" id="updateevent-status" value="6">
				 </td>
				 </tr>
				 <tr>
				 <td>
                 Duration&nbsp;:
                 </td>
                 <td>
                 <select name="eventduration" id="updateevent-duration">
    <option value="15">15 mins</option>
    <option value="30">30 mins</option>
    <option value="60">1 hour</option>
    <option value="120">2 hours</option>
  </select>
                 </td>
                 </tr>        
                </table>
                
                <button class="eform">Submit</button>
            
            
        </div>
         <div id="showevent-form">
        <table>
			<tr>
            <td>Name&nbsp;:&nbsp;</td><td id="showevent-name"></td>
			</tr>
            
			<tr>
            <td>Event-Date&nbsp;:&nbsp;</td><td id="showevent-date"></td>
			</tr>
            
			<tr>
            <td>Event-Time&nbsp;:&nbsp;</td><td id="showevent-time"></td>
			</tr>
			<tr>
				 <td>confirmed </td>
				 <td id="showevent-status">
				 </td>
            <tr>
            <td>Event-Duration&nbsp;:&nbsp;</td><td id="showevent-duration"></td>
            </tr>
			
            <tr>
            <td><button class="editform">Edit event</button></td><td><button class="remform">Remove event</button></td>
            </tr>
        </table>
        </div>
        
    <div id="schedskip-form">
	<div>This appointment is conflicting with other appointments.Click OK to make an appointment anyway.</div>
	<button class="schedskiptrue">Ok</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="schedskipfalse">Cancel</button>
	</div>    
    <div id="messageboard">
    <div id="messageboard_content"></div>
    <button class="messageboard_button">Close</button>
    </div>
    </body>

</html>
        
        
   
